<?php
/**
 * 前台广告模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class AdvertiseModel extends Model{

  /**
   * 获取广告列表
   * @param array $map 查询条件参数
   * @param string $order 排序规则
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 订单列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($where = array(), $field = true, $order = '`sort` ASC', $limit = '10'){
    $lists = $this->field($field)->where($where)->order($order)->limit($limit)->select();
    $type = C('ADVERTISE_TYPE');
    if($lists && is_array($lists)){
      foreach($lists as $key => &$value) {
        $value['type_name'] = $type[$value['type']];
      }
    }
    return $lists;
  }

  /**
   * 获取广告详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '广告信息不存在！';
      return false;
    }
    return $info;
  }

}
