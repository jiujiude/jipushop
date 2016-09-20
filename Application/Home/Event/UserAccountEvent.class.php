<?php
/**
 * 用户提现账户事件处理
 * @version 2015080810
 * @author Justin <justin@jipu.com>
 */

namespace Home\Event;

class UserAccountEvent{
  
  /**
   * 隐藏用户提现账户列表中的账户
   * @param array $lists 账户数组
   * @version 2015080810
   * @author Justin <justin@jipu.com>
   */
  function getHiddenAccount(&$lists){
    foreach($lists as $k => $v){
      if('alipay' == $v['type']){
        $lists[$k]['account'] = get_hidden_alipay($v['account']);
      }else{
        $lists[$k]['account'] = get_hidden_bankcard($v['account']);
      }
    }
    return $lists;
  }
  
}
