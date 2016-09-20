<?php
/**
 * 现金交易明细模型
 * @version 2014090812
 * @author Max.Yu <max@jipu.com>
 */
namespace Home\Model;

use Think\Model;

class FinanceModel extends Model{

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
  );

  /**
   * 获取列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map, $order = 'create_time DESC'){
    $lists = $this->where($map)->order($order)->select();
    return $lists;
  }

  /**
   * 获取现金交易记录详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!is_array($info)){
      $this->error = '数据不存在！';
      return false;
    }
    return $info;
  }

}
