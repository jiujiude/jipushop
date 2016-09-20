<?php

/**
 * 用户视图模型
 * @version 2015061517
 * @author Justin <justin@jipu.com>
 */

namespace Addons\UserSel\Model;
use Think\Model\ViewModel;

class UserSelModel extends ViewModel{
  
  public $viewFields = array(
    'User'=>array('id', 'username', 'mobile', 'status', 'update_time'),
    'Member'=>array('uid', 'nickname', 'avatar', '_on' => 'User.id=Member.uid'),
  );
  
}
