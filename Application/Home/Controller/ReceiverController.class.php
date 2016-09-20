<?php
/**
 * 前台收货地址控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class ReceiverController extends HomeController{

  private $receiver_model;

  protected function _initialize(){
    parent::_initialize();
    //用户登录验证
    parent::login();
    //实例化模型
    $this->receiver_model = D('Receiver');
  }

  /**
   * 获取当前用户收货地址列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    $map['uid'] = UID;
    if(isset($_GET['buynow'])){
      cookie('__buynowback__', I('buynow'));
    }
    //保存来路地址
    $code = I('get.code', '');
    $address = I('get.address', '');
    if(empty($code) && empty($address) && strpos($_SERVER['HTTP_REFERER'],'Order') > 0){
      session('sel_address_urlfrom', $_SERVER['HTTP_REFERER']);
    }
    //微信接口处理
    if(C('WECHAT_USERINFO_BY_API') == true){
      //判断是否直接返回订单页面
      A('Order', 'Event')->saveWechatAddress();
    }else{
      //判断生成获取微信收货地址接口参数
      $toAuth = I('selectAddress', 0) == 1;
      $config = A('Order', 'Event')->getWechatAddressConfig($toAuth);
      $this->wechatAddressConfig = json_encode($config);
      $this->code = I('get.code');
    }
    //购物车商品信息
    $lists = $this->receiver_model->lists($map);
    $this->meta_title = '选择收货地址';
    $this->lists = $lists;
    $this->display();
  }

  /**
   * 添加收货地址
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    $this->meta_title = '添加收货地址';
    $this->display();
  }

  /**
   * 修改收货地址
   * @author Max.Yu <max@jipu.com>
   */
  public function edit(){
    $map['id'] = intval(I('request.id'));
    $data = $this->receiver_model->detail($map);
    $data['redirect'] = I('redirect');
    $this->data = $data;
    $this->meta_title = '编辑收货地址';
    $this->display();
  }

  /**
   * 修改收货地址
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    $id = intval(I('post.id'));
    $name = I('post.name');
    $province = I('post.province');
    $district = I('post.district');
    $city = I('post.city');
    $address = I('post.address');
    $mobile = intval(I('post.mobile'));
    $redirect = I('post.redirect');

    empty($name) && $this->error('请您输入收货人姓名');
    empty($province) && $this->error('请您选择省');
    empty($district) && $this->error('请您选择市');
    empty($city) && $this->error('请您选择区/县');
    empty($address) && $this->error('请您输入详细地址');
    empty($mobile) && $this->error('请您输入手机号码');

    $res = $this->receiver_model->update();
    if($res){
      $url = ($redirect == 'member') ? U('Member/receiver') : U('Receiver/index');
      $this->ajaxReturn(array(
        'id' => $res,
        'url' => $url,
        'status' => 1,
        'info' => '操作成功！'
      ));
    }else{
      $this->error('操作失败！');
    }
  }

  /**
   * 获取收货地址详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($id){
    $map['id'] = $id;
    $res = $this->receiver_model->detail($map);
    if($res){
      $fromurl = session('sel_address_urlfrom');
      if($fromurl){
        $fromurl .= (strpos($fromurl, '?') > 0 ? '&' : '?') .'receiver_id='.$id;
        redirect($fromurl);
      }else{
        $this->redirect('Order/index', array('receiver_id' => $id));
      }
    }else{
      $this->error('选择失败!');
    }
  }

  /**
   * 设为默认地址
   * @author Max.Yu <max@jipu.com>
   */
  public function setDefault(){
    $map = array(
      'uid' => UID,
      'id' => intval(I('post.id'))
    );
    $mapOther = array(
      'uid' => UID,
      'id' => array('neq', intval(I('post.id')))
    );
    $data['is_default'] = 1;
    $dataOther['is_default'] = 0;
    $updateOther = $this->receiver_model->where($mapOther)->save($dataOther);
    $res = $this->receiver_model->where($map)->save($data);
    if($res){
      $this->success('设置成功!');
    }else{
      $this->error('设置失败!');
    }
  }

  /**
   * 删除收货地址
   * @author Max.Yu <max@jipu.com>
   */
  public function remove(){
    $id = intval(I('request.id'));
    if(empty($id)){
      $result = array(
        'status' => -1,
        'info' => '请选择要删除的地址！'
      );
      $this->ajaxReturn($result);
    }
    $map['id'] = $id;
    $map['uid'] = UID;
    if($this->receiver_model->remove($map)){
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }
}
