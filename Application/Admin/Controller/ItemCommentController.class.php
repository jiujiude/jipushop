<?php
/**
 * 商品评价管理控制器
 * @version 2015051010 
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Controller;

class ItemCommentController extends AdminController{

  public function index(){
    $where['pid'] = 0;
    ($item_id = I('get.item_id')) && $where['item_id'] = $item_id;
    parent::index($where);
  }
  
  /**
   * 商品评价回复
   * @author Justin <9801836@qq.com>
   */
  function reply(){
    $id = I('id');
    if($id){
      $this->data = M('ItemComment')->getById($id);
      //回复内容
      $this->data_reply = get_item_comment_reply($id);
      $this->display();
    } 
  }
}
