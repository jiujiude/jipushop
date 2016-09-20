<?php
/**
 * 统计联盟事件
 * @author Justin <justin@jipu.com>
 */

namespace Home\Event;

class UnionEvent{
  
  /**
   * 获取统计数据
   * @param $type 类型 Subscribe为关注人数 Order为订单统计
   * @author Justin <justin@jipu.com>
   */
  function getCountData($type = 'Subscribe', $uid = UID, $start_time = null, $end_time = null){
    $function = "_get{$type}Count";
    //总人数
    $data['total'] = $this->$function($start_time, $end_time, $uid);
    $data['subscribe_cashback'] = $data['order_cashback'] = 0.00;
    if(UID == $uid){
      //本月
      $this_month_start = date("Y-m-01");
      $data['this_month'] = $this->$function($this_month_start, null, $uid);
      //上月
      $last_month_start =  date("Y-m-d", strtotime($this_month_start. '-1 month'));
      $data['last_month'] = $this->$function($last_month_start, $this_month_start, $uid);
      $data['sum'] = 0;
      //30天内
      $start_time = $start_time ? : date("Y-m-d", strtotime('-30 day'));
      $end_time = $end_time ? : date("Y-m-d");
      
      for($i = strtotime($start_time); $i <= strtotime($end_time); $i += 86400){
        $data['labels'][] = date("m-d", $i);
        $data['sum'] += $data['datas'][] = $this->$function(date("Y-m-d", $i), date("Y-m-d", $i + 86400), $uid);
      }
      //总金额
      $where = array(
        'uid' => UID,
        //'type' => 'union_subscribe'
      );
      if('Subscribe' == $type){
        $where['type'] = 'union_subscribe';
        $data['subscribe_cashback'] = M('Finance')->field('sum(amount) as subscribe_cashback')->where($where)->find();
        $data['subscribe_cashback'] = $data['subscribe_cashback']['subscribe_cashback'];
      }elseif('Order' == $type){
        $where['type'] = 'union_order';
        $data['order_cashback'] = M('Finance')->field('sum(amount) as order_cashback')->where($where)->find();
        $data['order_cashback'] = $data['order_cashback']['order_cashback'];
      }
      $where['type'] = array('in', array('union_subscribe', 'union_order'));
      $data['union_cashback'] = M('Finance')->field('sum(amount) as union_cashback')->where($where)->find();
      $data['union_cashback'] = $data['union_cashback']['union_cashback'];
    }
    //格式化返现金额
    $data['subscribe_cashback'] = sprintf('%.2f', $data['subscribe_cashback']);
    $data['order_cashback'] = sprintf('%.2f', $data['order_cashback']);
    $data['union_cashback'] = sprintf('%.2f', $data['union_cashback']);
    return $data;
  }
  
  /**
   * 获取时间段内关注人数
   * @author Justin <justin@jipu.com>
   */
  private function _getSubscribeCount($start_time = null, $end_time = null, $uid){
    $start_time = $start_time ? strtotime($start_time) : 0;
    $end_time = $end_time ? strtotime($end_time) : time();
    $where['union_id'] = M('Union')->getFieldByUid($uid, 'id');
    if(!$where['union_id']){
      return 0;
    }
    $where['create_time'] = array('between', array($start_time, $end_time));
    $result = M('WechatQrcodeLog')->where($where)->count();
    return $result;
  }
  
  /**
   * 获取时间段内订单
   * @author Justin <justin@jipu.com>
   */
  private function _getOrderCount($start_time = null, $end_time = null, $uid){
    $start_time = $start_time ? strtotime($start_time) : 0;
    $end_time = $end_time ? strtotime($end_time) : time();
    $where['union_id'] = M('Union')->getFieldByUid($uid, 'id');
    if(!$where['union_id']){
      $result = 0;
    }
    //获取支付ID
    $where['create_time'] = array('between', array($start_time, $end_time));
    $where['payment_status'] = 1;
    $result = M('Payment')->field('id')->where($where)->count();
    
    !$result && $result = 0;
    return $result;
  }
  
}
