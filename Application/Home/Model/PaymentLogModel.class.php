<?php
/**
 * 支付日志模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;

class PaymentLogModel extends RelationModel {

  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
  );

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT)
  );

  /**
   * 关联规则
   * @var array
   */
  protected $_link = array(
    'Order'=> array(
      'mapping_type' => self::BELONGS_TO,
      'class_name' => 'Order',
      'foreign_key' => 'order_id',
      'mapping_fields' => 'id, order_sn, o_status, payment_type, finance_amount'
    ),
  );

  /**
   * 获取列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map){
    $lists = $this->where($map)->relation('Order')->select();
    return $lists;
  }

  /**
   * 更新信息
   * @param array $data 数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    return ($data['id']) ? $this->save() : $this->add();
  }

}
