<?php
/**
 * 后台专题/活动控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Admin\Model\AuthGroupModel;
use Admin\Model\PictureModel;
use Think\Page;

class ActivityController extends AdminController{

  private $activity_config;

  protected function _initialize(){
    parent::_initialize();
    $this->activity_config = array(
      'theme' => C('SALE_ACTIVITY_THEME'),
      'type' => C('SALE_ACTIVITY_TYPE'),
      'activity_pic' => C('UPLOAD_PIC_SIZE_CONVRNTION.ACTIVITY_PIC'),
    );
  }

  public function index(){
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //获取专题配置项
    $activity_config = $this->activity_config;

    // 获取列表
    $lists = $this->lists(M('Activity'), $where, '`create_time` DESC', null, $field);
    int_to_string($lists);
    int_to_string($lists, $map = array('theme' => $activity_config['theme']));

    // 获取缩略图
    if($lists){
      foreach($lists as $key => &$value){
        $value['src'] = get_image_thumb($value['src'], 70, 30);
      }
    }

    //模板输出变量赋值
    $this->assign('lists', $lists);
    $this->meta_title = '专题管理';
    $this->display();
  }

  /**
   * 添加专题
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    //获取专题配置项
    $activity_config = $this->activity_config;
    $theme = $this->getTheme();
    $this->assign('theme',$theme);
    $this->assign('activity_config', $activity_config);
    $this->meta_title = '添加专题';
    $this->display();
  }

  /**
   * 编辑专题
   * @author Max.Yu <max@jipu.com>
   */
  public function edit(){
    $id = I('get.id', '');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    //获取专题配置项
    $activity_config = $this->activity_config;

    //获取一条记录的详细数据
    $Activity = D('Activity');
    $data = $Activity->detail($id);
    if(!$data){
      $this->error($Activity->getError());
    }
    $theme = $this->getTheme();
    $this->assign('theme',$theme);
    $this->assign('activity_config', $activity_config);
    $this->assign('data', $data);
    $this->meta_title = '编辑专题';
    $this->display();
  }

  /**
   * 更新一条数据
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    $res = D('Activity')->update();
    if(!$res){
      $this->error(D('Activity')->getError());
    }else{
      $this->success('操作成功', Cookie('__forward__'));
    }
  }

  /**
   * 删除专题
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');

    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }

    $where['id'] = array('IN', $ids);
    //$pids = D('Activity')->where($where)->getField('background', true);
    //$pids = arr2str($pids);

    if(M('Activity')->where($where)->delete()){
      //记录行为
      action_log('update_activity', 'activity', $ids, UID);
      // 删除图片（含缩略图）
      //if($this->delPic($pids)){
      //  $this->success('删除成功！');
      //}else{
      //  $this->error('图片删除失败！');
      //}
    }else{
      $this->error('删除失败！');
    }
  }

  /**
   * 删除专题图片以及缩略图
   * @author Max.Yu <max@jipu.com>
   */
  public function delPic($pid = null){
    $result = array('status' => 1, 'info' => '图片删除成功！');

    // 验证参数的合法性
    if(empty($pid)){
      $result = array('status' => 0, 'info' => '参数不能为空！');
      $this->ajaxReturn($result, 'JSON');
    }else{
      if(!is_numeric($pid)){
        $result = array('status' => 0, 'info' => '参数错误！');
        $this->ajaxReturn($result, 'JSON');
      }
    }

    // 实例化图片模型
    $picture_model = D('Picture');

    // 获取专题图片缩略图规格配置
    $thumb_size = C('UPLOAD_PIC_THUMB_SIZE.TOPIC_PIC');

    if($picture_model->delById($pid, $thumb_size)){
      $this->ajaxReturn($result, 'JSON');
    }else{
      $result = array('status' => 0, 'info' => '图片删除失败！');
      $this->ajaxReturn($result, 'JSON');
    }
  }
  
  
  /**
   * 获取模板配置
   */
  public function getTheme(){
      $map['name'] = array('eq','SALE_ACTIVITY_THEME');
      $map['status'] = array('eq',1);
      $map['is_dev'] = array('eq',0);
      $conf = M('Config')->where($map)->find();
      $conf['extra'] = parse_config_attr($conf['extra']);
      return $conf;
  }

}
