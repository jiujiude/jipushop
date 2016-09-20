<?php
/**
 * 支付模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
*/

namespace Home\Model;

class PaymentModel extends HomeModel {

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', UID, self::MODEL_INSERT),
    array('is_use_finance', '_getIsUseFinace', self::MODEL_INSERT, 'callback'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH)
  );
  
  function _getIsUseFinace(){
    return I('post.is_use_finance', 0);
  }

}
