<?php
/**
 * 复购优惠控制器
 * @version 2015101914
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class FugouController extends AdminController{
  
  /**
   * 过滤条件
   */
  public function index($keywords = ''){
    if(!empty($keywords)){
      $map['item_id|item_name'] = array('LIKE', '%'.$keywords.'%');
    }
    parent::index($map);
  }
  
  /**
   * 展示列表前格式化
   */
  public function _before_index_display(&$lists){
    foreach($lists as &$vo){
      $vo['item_info'] = $vo['item_id']?get_item_info($vo['item_id']):array();
      $buy_price = $vo['item_info']['price'] - $vo['dis_price'];
      $vo['buy_price'] = sprintf('%.2f', $buy_price);
    }
  }
}

