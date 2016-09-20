<?php
/**
 * 供应商模型
 * @version 2015082011
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

class SupplierModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Justin <justin@jipu.com>
   */
  protected $_validate = array(
    array('supplier_id', 'require', '请选择供应商', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('name', 'require', '供应商名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('link_name', 'require', '联系人不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('link_mobile', 'require', '联系方式不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Justin <justin@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('key', 'build_unique_code', self::MODEL_INSERT, 'function', 8),
    array('supplier_id', '_getSupplierId', self::MODEL_BOTH, 'callback'),
  );
  
  /**
   * 供应商id
   */
  public function _getSupplierId(){
    if(IS_SUPPLIER){
      return UID;
    }else{
      return I('post.supplier_id', 0);
    }
  }
  
}
