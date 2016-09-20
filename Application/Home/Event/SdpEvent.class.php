<?php
/**
 * 分销平台事件表
 *
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Event;

class SdpEvent{

  /**
   * 获取分销金额概况
   * @author Max.Yu <max@jipu.com>
   */
  public function getSdpDetail(){
    $total_revenue = $yesterday_finance = '0.00';
    $shop = M('Shop')->getBySecret(SHOP_SECRET);
    if($shop){
      $total_revenue = $shop['total_revenue'];
      $today_start = strtotime(date('Y-m-d'));
      $where = array(
        'sdp_uid' => $shop['uid'],
        'create_time' => array('gt', $today_start)
      );
      $sdp_count = M('SdpRecord')->field('sum(cashback_amount) as t_finance')->where($where)->find();
      $sdp_count['t_finance'] && $today_finance = $sdp_count['t_finance'];
    }
    $data = array(
      'total_revenue' => $total_revenue,
      'today_finance' => $today_finance
    );
    return $data;
  }
  
  /**
   * 分销收入明细
   * @author Max.Yu <max@jipu.com>
   */
  public function getSdpRecordList(){
    $where = array(
      'sdp_uid' => UID,
    );
    $lists = A('Page', 'Event')->lists('SdpRecord', $where, 'id desc');
    foreach($lists as &$v){
      $map = array(
        'order_id' => $v['order_id'],
        'item_id' => $v['item_id'],
        'item_code' => $v['item_code']
      );
      $order_item = M('OrderItem')->field('name, spec')->where($map)->find();
      $v['item'] = array(
        'name' => $order_item['name'],
        'spec' => unserialize($order_item['spec'])
      );
    }
    return $lists;
  }

}
