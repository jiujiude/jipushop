<?php
/**
 * 收货人模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class PaymentModel extends AdiminModel {
  
  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
    array('payment_type', 'require', '支付类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('payment_amount', 'require', '支付金额不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('payment_account', 'require', '付款人帐号不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('payment_sn', 'build_sn', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );
}