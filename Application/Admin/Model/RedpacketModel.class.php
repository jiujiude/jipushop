<?php
/**
 * 红包模型
 * @author tony <tony@jipu.com>
 */

namespace Admin\Model;

use Think\Model;

class RedpacketModel extends Model{

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('order_sn', 'create_order_sn', self::MODEL_INSERT, 'function'),
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('payment_time', NOW_TIME, self::MODEL_INSERT)
  );

  /**
   * 众筹单订单列表查询
   * @author tony <tony@jipu.com>
   * id为红包订单id
   * $field 为设置字段信息
   * $map 为附加where条件
   */
  public function getOrderInfo($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    return $info;
  }

  /**
   * 更新红包信息
   * @param array $data 红包数据
   * @return boolean 更新状态
   * @author tony <tony@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    if($data['id']){
      $result = $this->save();
      $result = $data['id'];
    }else{
      $result = $this->add();
    }
    return $result;
  }

  /**
   * 统计红包订单数量
   * @author tony <tony@jipu.com>
   */
  public function redCount($map){
    $list = $this->where($map)->count();
    return $list;
  }

  /**
   * 统计红包订单金额
   * @author tony <tony@jipu.com>
   */
  public function redSum($map){
    $sum = $this->where($map)->sum('`amount`');
    return $sum;
  }

}
