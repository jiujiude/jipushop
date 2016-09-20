<?php
/**
 * 后台微信消息控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Think\Page;
use Org\Wechat\WechatAuth;

class WechatMsgController extends AdminController{

  private $wechat_token;
  private $wechat_appid;
  private $wechat_secret;
  private $wechat_auth;

  /*事件类型*/
  private $wechat_msg_event = array(
    'normal' => '发送文字事件',
    'subscribe' => '关注公众号事件',
    // 'LOCATION' => '上报地理位置事件'
  );

  /*消息类型*/
  private $wechat_msg_type = array(
    'text' => '文本消息',
    'news' => '图文消息'
  );

  protected function _initialize(){
    parent::_initialize();

    $this->wechat_token = C('WECHAT_TOKEN');
    $this->wechat_appid = C('WECHAT_APPID');
    $this->wechat_secret = C('WECHAT_SECRET');
    if(!$this->wechat_appid || !$this->wechat_secret){
      $this->error('请先配置微信公众号Appid和Secret', U('Admin/Config/group', array('id' => 2)));
    }
    $this->wechat_auth = new WechatAuth($this->wechat_appid, $this->wechat_secret, $this->wechat_token);
  }

  /**
   * 首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //获取公众号列表
    $where = array(
      'status' => 1
    );
    // 记录当前列表页的cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $where['status'] = 1;
    $lists['data'] = $this->lists(M('WechatMsg'), $where, '`create_time` DESC', null);
    int_to_string($lists['data'], $map = array('event' => $this->wechat_msg_event, 'type' => $this->wechat_msg_type));
    $this->assign('lists', $lists);
    $this->meta_title = '微信消息设置';
    $this->display();
  }

  /**
   * 添加页面
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    $data['wechat_msg_event'] = $this->wechat_msg_event;
    $data['wechat_msg_type'] = $this->wechat_msg_type;
    $data['option'] = I('get.option') ? I('get.option') : 'single';
    $data['type'] = I('get.type') ? I('get.type') : 'news';
    $this->meta_title = '微信消息添加';
    $this->assign('data',$data);
    $this->display();
  }

  /**
   * 编辑页面
   * @author Max.Yu <max@jipu.com>
   */
  public function edit(){
    $id = I('get.id', '');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    /*获取一条记录的详细数据*/
    $data['detail'] = M('WechatMsg')->find($id);
    if(!$data){
      $this->error('消息不存在');
    }
    $data['wechat_msg_event'] = $this->wechat_msg_event;
    $data['wechat_msg_type'] = $this->wechat_msg_type;
    
    //获取事件数据
    if($data['detail']['type'] == 'news' && !empty($data['detail']['event'])){
      session('tmp_'.$data['detail']['create_time'], json_decode($data['detail']['content'], true));
      $data['detail']['event_name'] = $data['detail']['create_time'];
    }
    $this->meta_title = '微信消息编辑';
    $this->assign('data', $data);
    $this->display();
  }

  /**
   * 更新一条数据
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){ 
    $type = I('post.type');
    $event = I('post.event1');
    I('post.id') == 1 && $_POST['event'] = 'subscribe';
    $event_data = session('tmp_'.$event);
    $_POST['event1'] != 'subscribe' && $_POST['event1'] = 'normal';
    $_POST['event']  = $_POST['event1'] ;
    $type == 'news' && $_POST['content'] = json_encode($event_data);
    //发送文字事件
    if($_POST['event'] == 'normal'){
      if(I('post.keyword') == ''){
        $this->error('请您填写关键词');
      }
    }
    if(I('post.content') == ''){
      $this->error('请您填写消息内容');
    }
    //图文
//    if($type == 'news'){
//      if(I('post.title') == ''){
//        $this->error('请您填写消息标题');
//      }
//      if(I('post.attach') == ''){
//        $this->error('请您上传封面图片');
//      }
//    }
    $res = D('WechatMsg')->update();
    if(!$res){
      $this->error(D('WechatMsg')->getError());
    }else{
      $this->success($res['id'] ? '更新成功' : '新增成功', Cookie('__forward__'));
    }
  }

  /**
   * 删除一条数据
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    /*参数过滤*/
    $ids = I('param.ids');
    if(empty($ids)){
      $this->error('请选择要删除的消息');
    }
    $res = D('WechatMsg')->remove($ids);
    if($res !== false){
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }
}