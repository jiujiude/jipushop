<?php
/*
 * 礼品卡模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class CardModel extends AdminModel {

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('name', 'require', '卡名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('quantity', 'require', '卡数量不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('quantity', '/^-?\d+$/', '卡数量格式不合法，必须为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_INSERT),
    array('amount', 'require', '卡面值不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('amount', '/^-?\d+$/', '卡面值格式不合法，必须为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_INSERT),
    array('length', 'require', '卡号长度不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('length', '/^-?\d+$/', '卡号长度格式不合法，必须为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_INSERT),
    array('expire_time', 'require', '有效日期不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('expire_time', '/^\d{4,4}-\d{1,2}-\d{1,2}$/', '日期格式不合法,请使用"年-月-日"格式,全部为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_INSERT),
    array('expire_time', 'checkExpireTime', '有效日期不能小于当天日期', self::VALUE_VALIDATE  , 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
  );

  /**
   * 更新礼品卡期状态
   */
  public function updateExpireStatus(){
    $this->execute("UPDATE __PREFIX__card SET is_expire = 1 WHERE (expire_time + 86400 - UNIX_TIMESTAMP(NOW())) < 0");
  }
  
  /**
  * 自定义检测截止有效期（自动验证使用）：必须大于今天
  * @param string $expire_time 截止有效期
  * @version 2015070916
  * @return boolen
  * @author Justin <justin@jipu.com>
  */
  protected function checkExpireTime($expire_time){
    return strtotime($expire_time) >= strtotime(date('Y-m-d',strtotime('+1 day'))) ? true : false;
  }
  
}