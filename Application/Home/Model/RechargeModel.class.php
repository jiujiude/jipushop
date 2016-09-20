<?php
/**
 * 充值模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */
namespace Home\Model;

use Think\Model;

class RechargeModel extends Model{

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map){
    $lists = $this->where($map)->select();
    return $lists;
  }

  /**
   * 获取充值详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!is_array($info)){
      $this->error = '充值信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新充值信息
   * @param array $data 充值数据
   * @return int 充值订单ID
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }

    if($data['id']){
      $result = $this->save();
      $order_id = $data['id'];
    }else{
      $result = $order_id = $this->add();
    }

    return $result;
  }

  /**
   * 根据订单字段更新订单信息，主要用于支付
   * @param array $data 订单数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function updateByField($map, $data = null){
    if(!$map && !$data ){
      return false;
    }
    return $this->where($map)->save($data);
  }
  
}
