<?php
/**
* 提现视图模型
* @version 2015081010 
* @author Justin
*/

namespace Common\Model;

use Think\Model\ViewModel;

class WithdrawViewModel extends ViewModel{
  
  public $viewFields = array(
    'Withdraw'=>array('id','uid','account_id', 'amount', 'fee', 'memo', 'status', 'create_time'),
    'UserAccount'=>array('name', 'type', 'bankname', 'account', '_on'=>'UserAccount.id = account_id'),
  );
  
}
