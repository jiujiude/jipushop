<?php
/**
 * 后台微信自定义菜单控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Admin\Model\AuthGroupModel;
use Think\Page;
use Org\Wechat\WechatAuth;

class WechatMenuController extends AdminController{

  private $wechat_appid;
  private $wechat_secret;
  private $wechat_auth;

  protected function _initialize(){
    parent::_initialize();

    $this->wechat_appid = C('WECHAT_APPID');
    $this->wechat_secret = C('WECHAT_SECRET');

    if(!$this->wechat_appid || !$this->wechat_secret){
      $this->error('请您先配置微信app_id和secret！');
    }
    $this->wechat_auth = new WechatAuth($this->wechat_appid, $this->wechat_secret);
  }

  public function index(){
    // 记录当前列表页的cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $map['status'] = array('gt', -1);
    $lists['menu'] = $this->getCustomMenu($map);
    /* 获取菜单树 */
    $lists['tree'] = $this->getCustomTree();
    $lists['num'] = count($lists['tree']);
    $this->meta_title = '自定义菜单';
    $this->assign('lists', $lists);
    //$menus = $this->getCustomMenu();
    $r = list_to_tree($lists['menu'], $pk = 'id', $pid = 'pid', $child = 'sub');
    //dump($r);
    $this->display();
  }

  /**
   * 添加自定义菜单
   * @author Max.Yu <max@jipu.com>
   */
  public function add($model = null){

    /* 获取一级菜单 */
    $map['pid'] = 0;
    $map['mid'] = $this->mid;
    $menus = D('WechatMenu')->where($map)->select();
    foreach($menus as $v){
      $data['menu'] .= $v['id'].':'.$v['title']."\r\n";
    }

    /* 获取菜单树 */
    $data['tree'] = $this->getCustomTree();
    $data['num'] = count($data['tree']);

    /* 获取关联URL */
    //$data['category'] = D('ItemCategory')->getTree();
    $this->event1 = 'event-'.get_randstr(8);
    $this->meta_title = '新增自定义菜单';
    $this->assign('data', $data);
    $this->display();
  }

  /**
   * 自定义菜单编辑页面初始化
   * @author Max.Yu <max@jipu.com>
   */
  public function edit(){
    $id = I('get.id', '');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    /* 获取一条记录的详细数据 */
    $data['detail'] = M('WechatMenu')->where('id = '.$id)->find();
    if(!$data){
      $this->error('自定义菜单不存在');
    }

    // 获取一级菜单
    $map['pid'] = 0;
    $map['mid'] = $this->mid;
    $map['id'] = array('neq', $id);
    $menus = D('WechatMenu')->where($map)->select();
    foreach($menus as $v){
      $data['menu'] .= $v['id'].':'.$v['title']."\r\n";
    }
    
    //自定义回复信息
    $data['detail']['type'] == 'click' && $data['detail']['keyword'] && $data['detail']['type'] = 'text';
    //获取事件数据
    if($data['detail']['type'] == 'click' && !empty($data['detail']['event'])){
      $event_data = M('WechatEvent')->getByKey($data['detail']['event']);
      session('tmp_'.$event_data['key'], json_decode($event_data['data'], true));
    }
    /* 获取菜单树 */
    $data['tree'] = $this->getCustomTree();
    $data['num'] = count($data['tree']);
    $this->meta_title = '编辑自定义菜单';
    $this->assign('data', $data);
    $this->display();
  }

  /**
   * 更新一条数据
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    I('post.type') == 'click' && $_POST['keyword'] = null;
    $res = D('WechatMenu')->update();
    if(!$res){
      $this->error(D('WechatMenu')->getError());
    }else{
      $this->success($res['id'] ? '更新成功' : '新增成功', Cookie('__forward__'));
    }
  }

  /**
   * 删除自定义菜单
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    /* 参数过滤 */
    $ids = I('param.ids');
    if(empty($ids)){
      $this->error('请选择要删除的菜单');
    }
    $res = D('WechatMenu')->remove($ids);
    if($res !== false){
      $this->success('菜单删除成功！');
    }else{
      $this->error('菜单删除失败！');
    }
  }

  /**
   * 发送自定义菜单到微信
   * @author Max.Yu <max@jipu.com>
   */
  public function send(){
    $map['status'] = 1;
    $data = $this->getCustomMenu($map);
    foreach($data as $k => $d){
      if($d['pid'] != 0){
        continue;
      }
      $tree['button'][$d['id']] = $this->dealCustomMenu($d);
      unset($data[$k]);
    }
    foreach($data as $k => $d){
      $tree['button'][$d['pid']]['sub_button'][] = $this->dealCustomMenu($d);
      unset($data[$k]);
    }
    $tree2 = array();
    $tree2['button'] = array();
    foreach($tree['button'] as $k => $d){
      $tree2['button'][] = $d;
    }
    $res = $this->wechat_auth->menuCreate($tree2);
    if($res['errcode'] == 0){
      $this->success('发送菜单成功');
    }else{
      $this->error('发送菜单失败，错误的返回码是：'.$res['errcode'].', 错误的提示是：'.$res['errmsg']);
    }
  }

  /**
   * 删除微信自定义菜单
   * @author Max.Yu <max@jipu.com>
   */
  public function remove(){
    $res = $this->wechat_auth->menuDelete();
    if($res['errcode'] == 0){
      $this->success('菜单删除成功');
    }else{
      $this->error('菜单删除失败，错误的返回码是：'.$res['errcode'].', 错误的提示是：'.$res['errmsg']);
    }
  }

  /**
   * 获取格式化的菜单
   * @author Max.Yu <max@jipu.com>
   */
  private function getCustomTree(){
    $menus = $this->getCustomMenu();
    return list_to_tree($menus, $pk = 'id', $pid = 'pid', $child = 'sub');
  }

  /**
   * 判断自定义菜单是否可以再增加
   * @author Max.Yu <max@jipu.com>
   */
  private function checkMenu(){
    
  }

  /**
   * 获取自定义菜单
   * @author Max.Yu <max@jipu.com>
   */
  private function getCustomMenu($map){
    $list = M('WechatMenu')->where($map)->order('pid asc, sort asc')->select();
    // 获取一级菜单
    foreach($list as $k => $vo){
      if($vo['pid'] != 0){
        continue;
      }
      $one_arr[$vo['id']] = $vo;
      unset($list[$k]);
    }

    foreach($one_arr as $p){
      $data[] = $p;
      $two_arr = array();
      foreach($list as $key => $l){
        if($l['pid'] != $p['id'])
          continue;
        $l['title'] = '<span class="tab-sign"></span>'.$l['title'];
        $two_arr[] = $l;
        unset($list[$key]);
      }
      $data = array_merge($data, $two_arr);
    }
    return $data;
  }

  /**
   * 格式化自定义菜单
   * @author Max.Yu <max@jipu.com>
   */
  private function dealCustomMenu($data){
    $res['name'] = str_replace('<span class="tab-sign"></span>', '', $data['title']);
    //发送地理位置
    if($data['type'] == 'location_select'){
      $res['type'] = 'location_select';
      $res['key'] = 'rselfmenu_2_0';
    }else if(!empty($data['keyword'])){
      $res['type'] = 'click';
      $res['key'] = $data['keyword'];
    }else if(!empty($data['event'])){
      $res['type'] = 'click';
      $res['key'] = $data['event'];
    }else{
      $res['type'] = 'view';
      $res['url'] = $data['url'];
    }
    return $res;
  }

}
