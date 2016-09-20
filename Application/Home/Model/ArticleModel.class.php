<?php
/**
 * 文章模型
 * @version 2015060808
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class ArticleModel extends Model{
  
  /**
   * 获取文章列表
   * @param array $where 查询条件
   * @param array $field 字段
   * @param array $order 排序
   * @param array $limit 数量
   * @return array 联系人列表
   * @version 2015060810
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($where, $field = true, $order = 'create_time DESC', $limit = 10){
    $where['status'] = 1;
    $lists = $this->field($field)->where($where)->order($order)->limit($limit)->select();
    if($lists){
      foreach($lists as $key => &$value){
        $value['images_src'] = get_cover($value['images'], 'path');
      }
    }
    return $lists;
  }

  public function detail($where, $field = true){
    return $this->where($where)->field($field)->find();
  }
  
}
