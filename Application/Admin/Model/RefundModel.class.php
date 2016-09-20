<?php
/**
 * 退款模型
 * @version 2015022615
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;
use Think\Model;

class RefundModel extends Model {

  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
    array('uid', 'require', '用户UID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('order_id', 'require', '订单ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('trade_no', 'require', '交易订单号不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('refund_type', 'require', '退款类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('payment_type', 'require', '支付方式不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('amount', 'require', '退款金额不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT)
  );

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  public function lists($map, $order = 'id DESC', $field = true, $limit = '10'){
    $result = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    return $result;
  }

  /**
   * 获取一条记录详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新记录
   * @param array $data 更新数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    ($data['id']) ? $this->save($data) : $id = $this->add($data);
    //记录行为
    action_log('refund_order_alipay', 'refund', $data['id'] ? $data['id'] : $id, UID);
    return $data;
  }

  /**
   * 删除记录
   * @return true 删除成功，false 删除失败
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($ids){
    $map['id'] = array('IN', $ids);
    $res = $this->where($map)->delete();
    return $res;
  }

}