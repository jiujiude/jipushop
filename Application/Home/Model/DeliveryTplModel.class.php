<?php
/**
 * 物流模板模型
 * @version 2015060808
 * @author Max.Yu <max@jipu.com>
*/

namespace Home\Model;

use Think\Model;

class DeliveryTplModel extends Model{

  /**
   * 获取列表
   * @param array $map 查询条件参数
   * @param string $order 排序规则
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 列表
   * @author Max.Yu <max@jipu.com>
  */
  public function lists($map = array(), $order = '`id` DESC, `create_time` DESC', $field = true, $limit = '10'){
    $list = $this->field($field)->where($map)->limit($limit)->order($order)->select();
    return $list;
  }
  
}
