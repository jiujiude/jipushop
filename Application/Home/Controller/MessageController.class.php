<?php
/**
 * 站内消息控制器
 * @version 2014122001
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class MessageController extends HomeController{

  public function index(){
    $map = array(
      'uid' => UID,
      'type' => I('get.type', 'all')
    );
    $lists = A('Message', 'Event')->getLists($map);
    $this->tab_active = array($map['type'] => ' class="active"');
    $this->lists = $lists;
    $this->meta_title = '站内消息';
    $this->display(IS_AJAX ? 'messageList': null);
  }

  /**
   * 获取消息内容
   * @author Max.Yu <max@jipu.com>
   */
  public function detail(){
    $message_id = I('post.message_id', 0);
    $where = A('Message', 'Event')->getWhereArray(UID);
    $data = M('Message')->field('id, title, content, create_time')->where($where)->getById($message_id);
    $data['content'] = nl2br(text2links($data['content']));
    $data['create_time'] = time_format($data['create_time']);
    //改为已读
    D('MessageRecord')->update($data['id']);
    //获取未读数量
    $data['unread_num'] = A('Message', 'Event')->getUnreadNum(UID);

    $this->ajaxReturn($data);
  }

  /**
   * 设置为已读
   * @author Max.Yu <max@jipu.com>
   */
  public function setReadStatus(){
    $message_id = I('post.message_id', 0);
    $map = A('Message', 'Event')->getWhereArray(UID);
    $map['id'] = array('IN', $message_id);
    $lists = M('Message')->field('id')->where($map)->select();
    if($message_id == 0 || empty($lists)){
      $this->error('请选择消息！');
    }
    foreach($lists as $k){
      D('MessageRecord')->update($k['id']);
    }
    $this->success('已设置为已读！');
  }
  
  /**
   * 删除消息
   * @author Max.Yu <max@jipu.com>
   */
  public function delete(){
    $message_id = I('post.message_id', 0);
    $map = A('Message', 'Event')->getWhereArray(UID);
    $map['id'] = array('IN', explode(',', $message_id));
    $lists = M('Message')->field('id')->where($map)->select();
    if($message_id == 0 || empty($lists)){
      $this->error('请选择消息！');
    }
    foreach($lists as $k){
      D('MessageRecord')->update($k['id'], 1);
    }
    $this->success('删除成功！');
  }

}
