<?php
/**
 * 余额事件处理
 * @version 2015102015
 * @author Justin <justin@jipu.com>
 */

namespace Home\Event;

class FinanceEvent{
  
  /**
   * 获取可提现的余额
   * @author Justin <justin@jipu.com>
   */
  function getWithDrawFinance($uid = 0){
    $uid = $uid ? : UID;
    $finance = M('Member')->getFieldByUid($uid, 'finance');
    $where = array(
      'uid' => $uid,
      'flow' => 'in',
      'type' => array('in', array('union_order', 'union_subscribe', 'sdp_order', 'withdraw_refuse_cashback')),
      'status' => 1 ,
    );
    $withdraw_amount = M('Finance')->where($where)->sum('amount');
    //已提现的需要减去
    $where = array(
      'uid' => $uid,
      'flow' => 'out',
      'type' => array('in', array('withdraw', 'sdp_refund'))
    );
    $withdrawed = M('Finance')->where($where)->sum('amount');
    $money = $withdraw_amount - $withdrawed;
    return sprintf('%.2f', min($money, $finance));
  }
  
}
