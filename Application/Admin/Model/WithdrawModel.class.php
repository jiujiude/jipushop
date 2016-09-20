<?php
/**
 * 提现模型
 * @version 2015081012
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

class WithdrawModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Justin <justin@jipu.com>
   */
  protected $_validate = array(
    array('status', '_checkMemo', '拒绝原因不能为空', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
  );
  
  function detail($id){
    return D('WithdrawView')->find($id);
  }
  
  /**
  * 检测拒绝原因
  * @author Justin <justin@jipu.com>
  */
  function _checkMemo(){
    $status = I('post.status');
    if('101' == $status || '201' == $status){
      return I('post.memo') ? true : false;
    }
    return true;
  }
  
  /**
  * 当被拒绝时候返回扣除的余额
  * @author Justin <justin@jipu.com>
  */
  protected function _after_update($data,$options){
    if('101' == $data['status'] || '201' == $data['status']){
      $data_withdraw = M('Withdraw')->field('id, uid, amount, fee')->getById($data['id']);
      $amount = $data_withdraw['amount'] - $data_withdraw['fee'];
      M('Member')->where('uid='.$data_withdraw['uid'])->setInc('finance', $amount); 

      $data_finance = array(
        'uid' => $data_withdraw['uid'],
        'order_id' => '',
        'type' => 'withdraw_refuse_cashback',
        'amount' => $amount,
        'flow' => 'in',
        'memo' => '提现失败返还金额',
        'create_time' => NOW_TIME
      );
      $update = M('Finance')->add($data_finance);
    }
  }
    
}
