<?php
/**
 * 订单模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class OrderModel extends AdminModel {

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
  );

  /**
   * 获取订单列表
   * @param $order_sn
   */
  public function lists($map, $order = '`id` DESC', $field = true, $limit = '10'){
    $result = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    return $result;
  }

  /**
   * 根据order_sn 获取 order_id
   * @param $order_sn
   */
  public function getIdBySn($order_sn = null){
    if(empty($order_sn)){
      return false;
    }

    $order_id = $this->getFieldByOrderSn($order_sn, 'id');

    if($order_id){
      return $order_id;
    }else{
      return false;
    }
  }
  
  /**
   * 获取灵通打单所需数据
   * @param array $ids 描述信息
   */
  public function getBestMartData($ids = array()){
    if(empty($ids)){
      return false;
    }
    $data = array();
    $orders = $this->field('id, order_sn, total_quantity, payment_id')->where(array('id' => array('IN', $ids)))->select();
    foreach($orders as $v){
      $ship = M('OrderShip')->getByPaymentId($v['payment_id']);
      $area = explode(' ', $ship['ship_area']);
      $data[] = array(
        $v['order_sn'], $ship['ship_uname'], $ship['ship_mobile'],
        $area[0], $area[1], $area[2], $ship['ship_address'], '休闲食品', $v['total_quantity'], ($ship['ship_phone'] ?: '')
      );
    }
    return $data;
  }
}