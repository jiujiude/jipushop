<?php
/**
 * 后台订单控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class CrowdfundingController extends AdminController{

  private $payment_type;
  private $o_status_text;
  private $is_packed_text = array(
    0 => '<span class="text-warning">未打包</span>',
    1 => '<span class="text-success">已打包</span>',
  );

  protected function _initialize(){
    parent::_initialize();
    //支付方式
    $this->payment_type = get_payment_type_text();
    $this->o_status_text = get_o_status_text();
  }

  /**
   * 订单列表
   * @author Max.Yu <max@jipu.com>
   */
  public function order(){
    // 记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $orderList = array();
    //读取众筹订单列表
    $list = $this->lists(M('CrowdfundingOrder'));
    $oids=array();
    foreach($list as $v)$oids[]=$v['order_id'];
    $map = array();
    if(!empty($oids)){
        $map['id'] = array('in',$oids);
        $o_list=M('Order')->field('*')->where($map)->select();
        $orderList = A('Order', 'Event')->orderFormat($o_list);
    }
    $this->list = $orderList;
    // 增加排序
    $this->setListOrder();
    $this->meta_title = '订单列表';
    $this->display();
  }

  /**
   * 编辑订单
   * @author Max.Yu <max@jipu.com>
   */
  public function edit($id = 0){
    if(IS_POST){
      $Order = D('Order');
      $data = $Order->create();
      if($data){
        if($Order->save() !== false){
          //记录行为
          //action_log('update_order', '$Order', $data['id'], UID);
          $this->success('更新成功', Cookie('__forward__'));
        }else{
          $this->error('更新失败');
        }
      }else{
        $this->error($Order->getError());
      }
    }else{
      $info = array();
      // 获取数据
      $info = M('Order')->field(true)->find($id);
      // 检测订单是否已取消
      if(!(is_array($info) || -1 == $info['status'])){
        $this->error = '订单已取消，不能编辑！';
        return false;
      }

      if(false === $info){
        $this->error('获取订单信息错误');
      }
      $this->assign('info', $info);
      $this->meta_title = '编辑订单信息';
      $this->display();
    }
  }

  /**
   * 更新部分字段前置方法：如果更改打包状态，验证订单状态
   * @author justin <justin@jipu.com>
   * @version 2015071010
   */
  function _before_updateField(){
    $is_packed = I('get.is_packed', null);
    if(isset($is_packed)){
      $order=M('Order')->where(array('id'=>I('get.id')))->find();
      //$o_status = M('Order')->getFieldById(I('get.id'), 'o_status');
      //$o_status != 200 && $this->error('当前订单状态不允许更新打包状态！');
      $order['o_status'] != 200 && $this->error('当前订单状态不允许更新打包状态！');
      $order['is_packed'] == 1 && $this->error('商品已打包！');
    }
  }

  /**
   * 更新部分数据
   * @author Max.Yu <max@jipu.com>
   */
  public function updateField(){
    $res = D('Order')->updateField();
    if(!$res){
      $this->error(D('Order')->getError());
    }else{
      S('weixin_prepay_id_item_order_'.I('id'), null);
      //记录行为
      action_log('update_order', 'order', I('id'), UID);
      $this->success('修改成功', Cookie('__forward__'));
    }
  }

  /**
   * 查看订单
   * @author Max.Yu <max@jipu.com>
   */
  public function view($id = 0){
    //获取订单信息
    $info = M('Order')->find($id);
    if(false === $info){
      $this->error('获取订单信息错误');
    }
    // 计算优惠金额
    $info['discount_amount'] = sprintf("%.2f", ($info['total_price'] + $info['delivery_fee'] - $info['total_amount'] - $info['finance_amount']));
    // 获取支付方式
    $info['payment_type_text'] = get_payment_type_text(M('Payment')->getFieldById(M('Order')->getFieldById($info['id'], 'payment_id'), 'payment_type'));
    //订单商品信息
    $info['itemList'] = get_order_item($id);
    $info['itemCount'] = count_order_item($id);
    //付款信息
    $info['payment'] = M('Payment')->getById($info['payment_id']);
    //收货人信息
    $info['ship'] = M('OrderShip')->getByPaymentId($info['payment_id']);
    //物流信息
    $info['delivery'] = M('Ship')->getByOrderId($info['id']);
    //物流模板信息
    ($info['delivery_id'] > 0) && $info['delivery_tpl'] = M('DeliveryTpl')->getFieldById($info['delivery_id'], 'company');
    //分销店铺信息
    $info['sdp_shop'] = $info['sdp_uid'] > 0 ? M('Shop')->getByUid($info['sdp_uid']) : '';

    $this->assign('info', $info);
    $this->meta_title = '订单详情';
    $this->display();
   
  }

  /**
   * 回收站
   * @author Max.Yu <max@jipu.com>
   */
  public function recycle(){
    $where['status'] = -1;
    $list = $this->lists('Order', $where, 'id desc', null);
    int_to_string($list, array('o_status' => $this->o_status_text));
    foreach($list as &$value){
      $value['payment_type'] = M('Payment')->getFieldById($value['payment_id'], 'payment_type');
    }
    
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->assign('list', $list);
    $this->meta_title = '订单回收站';
    $this->display();
  }

  /**
   * 删除订单（物理删除）
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');

    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }
    $map['id'] = array('in', $ids);
    if(M('Order')->where($map)->delete()){
      //删除订单项目信息
      $where['order_id'] = array('in', $ids);
      //删除订单项目表记录
      M('OrderItem')->where($where)->delete();
      //删除物流信息表记录
      M('Ship')->where($where)->delete();
      //删除支付记录表
      M('Payment')->where($where)->delete();
      //删除现金流水表
      M('Finance')->where($where)->where(array('type' => 'website'))->delete();
      //记录行为
      action_log('del_order_recycle', 'order', $ids, UID);
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }

  /**
   * 取消订单
   * @author Max.Yu <max@jipu.com>
   */
  public function cancel(){
    $ids = I('request.ids');
    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }

    // 初始化退款事件
    $cancel_return = A('Refund', 'Event')->doit($ids, 'cancel');
    if($cancel_return['code'] == 200){
      $this->success($cancel_return['info']);
    }else{
      $this->error($cancel_return['info']);
    }
  }

  /**
   * 处理退款
   * @author Max.Yu <max@jipu.com>
   */
  public function refund(){
    $order_id = I('order_id');
    $order = M('Order')->find($order_id);
    if(empty($order)){
      die('订单不存在');
    }
    //申请退款，已退货
    if(in_array($order['o_status'], array(300, 301, 302))){
      if(IS_POST){
        $new_status = I('post.to_status');
        if($new_status == 301){
          M('Order')->save(array('id' => $order_id, 'o_status' => $new_status));
          $this->success('退款申请已审核');
        }elseif($new_status == 303){
          // 初始化退款事件
          $refund_return = A('Refund', 'Event')->doit(array($order_id), 'refund');
          if($refund_return['code'] == 200){
            $this->success($refund_return['info']);
          }else{
            $this->error($refund_return['info']);
          }
        }elseif($new_status == -1){
          $to_status = 0;
          //已支付
          $order['payment_time'] > 0 && $to_status = 200;
          //已发货
          $order['shipping_time'] > 0 && $to_status = 201;
          //已完成
          $order['complete_time'] > 0 && $to_status = 202;
          M('Order')->save(array('id' => $order_id, 'o_status' => $to_status));
          $this->success('已取消退款');
        }else{
          $this->error('请选择操作');
        }
      }
      if($order['reship_info']){
        $order['reship_info_text'] = json_decode($order['reship_info'], true);
      }
      $this->order = $order;
      $this->display();
    }else{
      die('当前状态不允许操作');
    }
  }
  
  /**
   * 打印请货单（商品清单）
   * @author Max.Yu <max@jipu.com>
   */
  public function printItem($id = 0){
    R('Order/view', array('id' => $id));
  }
  
  /**
   * 灵通打单
   */
  public function bestmart($ids = array()){
    $headArr = '业务单号;收件人姓名;收件人手机;收件省;收件市;收件区/县;收件人地址;品名;数量;备注';
    $filename = "灵通打单".date('Ymd-H');
    $data = D('Order')->getBestMartData($ids);
    $data = array_merge(array(explode(';', $headArr)), $data);
    /* 执行下载 */
    header('Content-type: text/csv;');
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');
    if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
      header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '.csv"');
    }else{
      header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    }
    foreach($data as $v){
      foreach($v as &$vv){
        $vv = mb_convert_encoding($vv, 'gbk', 'utf-8');
      }
      echo implode(',', $v) ."\r\n";
    }
    exit;
  }

}
