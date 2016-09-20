<?php
/**
 * 众筹模型
 * @version 2014101009
 * @author tony <tony@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;

class CrowdfundingOrderModel extends RelationModel{

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
   * 关联规则
   * @var array
   */
  protected $_link = array(
    'CrowdfundingUsers' => array(
      'mapping_type' => self::HAS_MANY,
      'class_name' => 'CrowdfundingUsers',
      'foreign_key' => 'crowdfunding_id',
      'condition' => 'payment_status=1',
      'mapping_fields' => 'id,username,order_id,crowdfunding_id,pay_money, create_time'
    )
  );

  /**
   * 更新众筹信息
   * @param array $data 众筹数据
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
      //如果此订单的众筹已存在，就不能加添加众筹
      $id = $this->getFieldByOrderId($data['order_id'], 'id');
      if($id){
        $result = $id;
      }else{
        $result = $this->add();
      }
    }
    return $result;
  }

  /**
   * 众筹订单列表
   * @author tony <tony@jipu.com>
   */
  public function lists($map, $field = true, $order = 'create_time DESC', $limit = '10'){
    $list = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    return $list;
  }

  /**
   * 众筹单订单列表查询
   * @author tony <tony@jipu.com>
   */
  public function getOrderInfo($map){
    $info = $this->getByOrderId($map);
    return $info;
  }

  /**
   * 众筹订单详情数据
   * @author tony <tony@jipu.com>
   */
  public function detail($map, $field){
    $info = $this->relation('CrowdfundingUsers')->field($field)->where($map)->find();
    //求支付过的金额累加之和
    $sum_payed = 0;
    if($info['CrowdfundingUsers']){
      foreach($info['CrowdfundingUsers'] as $k => $v){
        $sum_payed += $v['pay_money'];
        $info['CrowdfundingUsers'][$k]['create_time'] = format_date($v['create_time']);
      }
    }
    $total_amount = M('Order')->getFieldById($info['order_id'], 'total_amount');
    //筹集的金额
    $info['raise_amount'] = $sum_payed;
    //剩余待支付金额
    $info['surplus_amount'] = sprintf('%0.2f', $total_amount - $sum_payed);
    $info['percent'] = round(($sum_payed / $total_amount)*100).'%';
    $info['has_finish'] = ($info['percent'] == '100%') ? 1 : 0;
    return $info;
  }

}
