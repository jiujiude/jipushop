<?php
/**
 * 站内消息后台控制器
 * @version 15101608
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class MessageController extends AdminController{

  /**
   * 首页过滤条件
   */
  public function index($keywords = ''){
    $where = array(
      'title|content' => array('LIKE', '%'.trim($keywords).'%')
    );
    parent::index($where);
  }

  /**
   * 站内消息详情
   */
  public function detail($id = 0){
    $data = M('Message')->where('status=1')->find($id);
    if(empty($data)){
      $this->error('站内消息不存在！');
    }
    $this->data = $data;
    $this->meta_title = '站内消息详情';
    $this->display();
  }
  
  /**
   * 消息预览
   */
  public function preview($title = '', $content = ''){
    $this->data = array(
      'title' => $title,
      'content' => nl2br(text2links($content))
    );
    $this->display();
  }

}
