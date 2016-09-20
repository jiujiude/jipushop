<?php
/*
 * 发票申请单模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Think\Model;

class InvoiceModel extends Model {
  
  /**
   * 自动验证规则
   */
  protected $_validate = array(
        array('express_company', 'require', '快递公司不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
      array('express_number', 'require', '快递单号不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   */
  protected $_auto = array(        
  );
}