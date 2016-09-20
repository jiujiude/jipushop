<?php
/**
 * 微信自定义菜单模型
 * @version 2015061610
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;
use Think\Model;

class WechatUserModel extends Model{

  /**
   * 获取微信粉丝列表
   * @param array $map 查询条件参数
   * @param string $order 排序规则
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map, $order = '`id` DESC', $field = true, $limit = '10'){
    $result = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    return $result;
  }

}