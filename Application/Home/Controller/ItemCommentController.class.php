<?php
/**
 * 商品评价管理控制器
 * @version 2015071816 
 * @author Justin <justin@jipu.com>
 */

namespace Home\Controller;

class ItemCommentController extends HomeController{
  
  /**
   * 添加商品评价
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    if(IS_POST){
      $data = array(
        'item_id' => I('post.item_id'),
        'order_id' => I('post.order_id'),
        'star_amount' => I('post.star_amount'),
        'content' => I('post.content')
      );
      $add = D('ItemComment')->update($data);
      $result['status'] = $add ? 1 : 0;
      $this->ajaxReturn($result);
    }else{
      $data['item_id'] = I('request.item_id');
      $data['order_id'] = I('request.order_id');
      $this->assign('data', $data);
      $this->display();
    }
  }
  
   /**
   * 商品评价详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($uid, $item_id, $order_id){
    if(empty($uid) || empty($item_id)){
      return false;
    }
    $map['uid'] = $uid;
    $map['item_id'] = $item_id;
    $map['order_id'] = $order_id;
    $data = D('ItemComment')->detail($map);
    $this->assign('data', $data);
    $this->display();
  }
 
}
