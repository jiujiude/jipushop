<?php
/**
 * 订单详情模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class OrderItemModel extends Model{

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取列表
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @param string $order 排序规则
   * @param string $limit 分页参数
   * @return array 列表数据
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map = array(), $field = true, $order = '`id` DESC', $limit = '10'){
    $map['status'] = 1;
    $lists = $this->where($map)->field($field)->order($order)->limit($limit)->select();
    if($lists && is_array($lists)){
      foreach ($lists as $key => &$value){
        if($value['thumb']){
          $value['cover_path'] = get_cover($value['thumb'], 'path');
        }
        $value['is_comment'] = is_comment(UID, $value['item_id']);
      }
    }
    return $lists;
  }

  /**
   * 更新订单明细信息
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    //添加或更新数据
    return ($data['id']) ? $this->save() : $this->add();
  }

  /**
   * 根据订单id获取订单详细信息
   * @param $id 订单ID
   * @param string $order 排序规则
   * @return array 商品列表
   * @author Max.Yu <max@jipu.com>
   */
  public function getOrderItem($id, $field = true){
    $map['order_id'] = $id;
    $lists = $this->field($field)->where($map)->select();
    //获取商品缩略图
    if($lists && is_array($lists)){
      foreach ($lists as $key => &$value){
        if($value['thumb']){
          //规格图片
          $pic = get_cover(M('PropertyOption')->getFieldByCode($value['item_code'], 'pic'), 'path');
          //不存在则为封面图片
          $value['cover_path'] = $pic ? $pic : get_cover($value['thumb'], 'path');
        }
      }
    }
    return $lists;
  }

}
