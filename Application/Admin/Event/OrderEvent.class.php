<?php
/**
 * 订单数据处理事件接口
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Event;

class OrderEvent extends \Think\Controller{

  /**
   * 获取分页列表
   */
  public function getPageList($where = array()){
    $where = array(
      'groupby' => 'payment_id',
      'status' => array('egt', 1)
    );
    //订单状态过滤
    $o_status = I('get.o_status', -2);
    if($o_status > -2){
      $where['o_status'] = array('eq', $o_status);
      
    }elseif($o_status == -3){
      $where['payment_time'] = array('gt', 0);
    }
    $this->o_status = $o_status;
    
    //用户ID过滤
    $uid = I('get.uid', 0);
    if($uid > 0){
      $where['uid'] = $uid;
    }
    //分销过滤
    $sdp_uid = I('get.sdp_uid', 0);
    if(1 == I('is_sdp', 0)){
      $where['sdp_uid'] = array('gt', 0);
    }
    if($sdp_uid > 0){
      $where['sdp_uid'] = $sdp_uid;
      $this->sdp_uid = $sdp_uid;
    }
    //供应商过滤
    if(IS_SUPPLIER){
      $where['supplier_ids'] = UID;
      $where['payment_time'] = array('gt', 0);
    }
    //时间过滤
    $time_type = I('get.time_type', null);
    $start_time = I('get.start_time', '');
    $end_time = I('get.end_time', '');
    if(isset($time_type) && in_array($time_type, array('create_time', 'payment_time'))){
      $start_time = !empty($start_time) ? strtotime($start_time) : '';
      $end_time = !empty($end_time) ? strtotime($end_time) + 24 * 3600 : '';
      if(!empty($start_time)){
        $where[] = "`$time_type` > $start_time";
      }
      if(!empty($end_time)){
        $where[] = "`$time_type` < $end_time";
      }
      if($time_type == 'payment_time' && ($start_time + $end_time) > 0){
        $where['payment_time'] = array('gt', 0);
      }
      $this->time_type = $time_type;
    }
    //字符串过滤
    $keywords = I('get.keywords', '', trim);
    if(!empty($keywords)){
      $where['order_sn'] = array('like', '%'.$keywords.'%');
      $this->keywords = $keywords;
    }
    //按条件查询结果并分页
    $list = array();
    C('LIST_ROWS', 10);
    //收货人名字或者手机搜索
    $ship = I('get.ship');
    if($ship){
      $ship_where['ship_uname|ship_mobile'] = array('like', '%'.$ship.'%');
      $ship_payment_ids = M('OrderShip')->where($ship_where)->getField('payment_id', true);
      if(empty($ship_payment_ids)){
        return false;
      } 
      $where['payment_id'] = array('IN', array_unique($ship_payment_ids));
    }
    $order = (-3 == $o_status) ? 'payment_time desc, id desc' : 'id desc';
    if(I('order_type' , 'int' ,0) > 0){
         $map['order_type'] = $where['order_type'] = I('order_type' );
         $this->order_type  = I('order_type' );
      }
    $payment_list = A('Home/Page', 'Event')->lists('Order', $where, $order, 10, array(), 'payment_id');
    if($payment_list){
      $payment_ids = array_column($payment_list, 'payment_id');
      $map = array(
        'payment_id' => array('IN', array_unique($payment_ids)),
        'status' => array('egt', 1)
      );
      //供应商过滤
      if(IS_SUPPLIER){
        $map['supplier_ids'] = UID;
      }
      $list = M('Order')->field('*')->where($map)->order('field(payment_id,'.implode(',', $payment_ids).')')->select();
    }
    return $list;
  }

  /**
   * 订单数据按支付单号分组
   */
  public function orderFormat($order_list){
    //空数据直接返回
    if(empty($order_list)){
      return array();
    }
    $list = array();
    foreach($order_list as $order){
      $payment_id = $order['payment_id'];
      if(!isset($list[$payment_id])){
        $list[$payment_id] = array(
          'num' => 0,
          'is_sdp' => $order['sdp_uid'] != 0,
          'invoice_need' => $order['invoice_need'],
          'payment' => M('Payment')->field('uid, payment_sn, payment_status, payment_type, payment_amount, finance_amount, create_time')->getById($payment_id),
          'ship' => M('OrderShip')->getByPaymentId($payment_id),
        );
      }
      $order['item_ids_arr'] = explode(',', $order['item_ids']);
      $list[$payment_id]['order'][$order['id']] = $order;
      $list[$payment_id]['num'] = count($list[$payment_id]['order']);
    }
    return $list;
  }

}
