<?php
/**
 * 物流运费模板模型
 * @version 2015010714
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class DeliveryTplModel extends AdminModel {
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('name', 'require', '请填写模板名称', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('company', 'require', '请填写快递公司名称', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('send_date', 'require', '请选择发货时间', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('price_type', 'require', '请选择计价方式', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('express_start', 'require', '请填写首件/重数量', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('express_postage', 'require', '请填写首件/重价格', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('express_plus', 'require', '请填写加件/重数量', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('express_postageplus', 'require', '请填写加件/重价格', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH)
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('supplier_id', '_getSupplierId', self::MODEL_BOTH, 'callback'),
    array('express_unit', '_getExpressUnit', self::MODEL_BOTH, 'callback'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  public function lists($map, $order = '`is_top` DESC, `sort` ASC', $field = true, $limit = '10'){
    $result = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    return $result;
  }
  
  //插入成功后的回调方法
  protected function _after_insert($data, $options){
    //记录行为
    action_log('update_delivery', 'deliveryTpl', $data['id'], UID);
  }
  
  //更新成功后的回调方法
  protected function _after_update($data,$options){
    //记录行为
    action_log('update_delivery', 'deliveryTpl', $data['id'] ? $data['id'] : I('id'), UID);
  }

  public function _getExpressUnit(){
    return (I('post.price_type') == 1) ? '件' : 'kg';
  }
  
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