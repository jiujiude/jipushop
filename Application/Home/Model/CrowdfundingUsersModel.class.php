<?php
/**
 * 众筹用户模型
 * @version 2014101009
 * @author tony <tony@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class CrowdfundingUsersModel extends Model{

  /*自动完成规则*/
  protected $_auto = array(
    array('pay_id', 'create_order_sn', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 查询众筹用户订单
   * @author tony <tony@jipu.com>
   */
  public function lists($map, $field = true, $order = 'create_time DESC', $limit = '10'){
    $lists = $this->where($map)->field($field)->order($order)->limit($limit)->select();
    return $lists;
  }

  /**
   * 查询众筹用户已支付的总金额
   * @author tony <tony@jipu.com>
   */
  public function sumPayed($map = array()){
    $result = $this->where($map)->sum('`pay_money`');
    return $result;
  }

  /**
   * 通过支付id，查询参与众筹用户订单信息
   * @author tony <tony@jipu.com>
   */
  public function getOrderInfo($map = null, $field = true){
    $result = $this->field($field)->getByPayId($map);
    return $result;
  }


  /**
   * 更新众筹用户信息
   * @param array $data 用户数据
   * @return boolean 更新状态
   * @author tony <tony@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    //如果此用户的支付订单已存在，就不能再加添加订单
    $map['order_id'] = $data['order_id'];
    $map['open_id'] = $data['open_id'];
    $map['payment_status'] = 0;
    $line = $this->where($map)->find();
    if($line){
      $data['id'] = $line['id'];
      $result = $this->save($data);
    }else{
      $result = $this->add($data);
    }
    $result = $data['pay_id'];
    return $result;
  }

}
