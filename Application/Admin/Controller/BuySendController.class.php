<?php
/**
 * 买送活动控制器
 * @version 2015100914
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class BuySendController extends AdminController{
  
  /**
   * 展示列表前格式化
   */
  public function _before_index_display(&$lists){
    foreach($lists as &$vo){
      $vo['item_info'] = $vo['item_id']?get_item_info($vo['item_id']):array();
      $vo['send_item'] = json_decode($vo['send_item'], true);
      foreach($vo['send_item'] as &$item){
        $item['item_info'] = get_item_info($item['id']);
      }
    }
  }
}

