<?php
/**
 * 统计事件控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Event;

class StatEvent{

  /**
   * 商品销量明细列表
   */
  public function itemRecordList($start_date, $end_date, $item_id = 0){
    $start_time = !empty($start_date) ? strtotime($start_date) : 0;
    $end_time = !empty($end_date) ? strtotime($end_date.' +1 day') : strtotime(date("Y-m-d", strtotime('+1 day')));
    //获取订单
    $where = array(
      'o_status' => array('IN', '200, 201, 202'),
      'status' => 1,
      'create_time' => array('between', array($start_time, $end_time))
    );
    if($item_id > 0){
      $where[] = 'FIND_IN_SET('.$item_id.', item_ids)';
    }else{
      return [];
    }
    $lists_order = A('Home/Page', 'Event')->lists('Order', $where, 'id desc', 15, null, 'id, uid, payment_id, order_sn, create_time');
    foreach($lists_order as &$v){
      $map = array(
        'order_id' => $v['id'],
        'item_id' => $item_id,
      );
      $v['number'] = M('OrderItem')->where($map)->sum('quantity');
      $v['ship'] = M('OrderShip')->field('ship_uname,ship_mobile')->where(array('payment_id' => $v['payment_id']))->find();
    }
    return $lists_order;
  }

}
