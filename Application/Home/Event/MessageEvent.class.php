<?php
/**
 * 用户站内消息事件处理
 * @version 2015102015
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Event;

class MessageEvent{

  /**
   * 获取查询条件
   * @author Max.Yu <max@jipu.com>
   */
  public function getWhereArray($uid = 0){
    $where = array(
      '_complex' => array(
        'to_uid' => 0,
        '_string' => ' FIND_IN_SET('.$uid.', `to_uid`)',
        '_logic' => 'or',
      ),
      'status' => 1
    );
    return $where;
  }

  /**
   * 获取站内消息列表
   * @author Max.Yu <max@jipu.com>
   */
  public function getLists($map = array()){
    $record = M('MessageRecord');
    $uid = intval($map['uid']);
    $where = $this->getWhereArray($uid);
    //已删除数据
    $delete_lists = $record->field('message_id')->where(array('uid' => $uid, 'status' => -1))->select();
    $del_ids = array_column($delete_lists, 'message_id');
    //已读未读查询
    if($map['type'] != 'all'){
      $mr_lists = $record->field('message_id')->where(array('uid' => $uid, 'status' => 1))->select();
      $mids = array_column($mr_lists, 'message_id');
      empty($mids) && $mids = array(0);
      if($map['type'] == 'unread'){
        $where['id'] = array('NOT IN', array_merge($del_ids, $mids));
      }elseif($map['type'] == 'read'){
        $where['id'] = array('IN', $mids);
      }
    //全部消息
    }else{
      $del_ids && $where['id'] = array('NOT IN', $del_ids);
    }
    $lists = A('Page', 'Event')->lists('Message', $where, 'id desc', 15, '', 'id,title,create_time, to_uid, status');
    foreach($lists as &$li){
      $r_line = $record->where(array('uid' => $uid, 'message_id' => $li['id']))->find();
      $li['is_read'] = $r_line ? 1 : 0;
    }
    return $lists;
  }

  /**
   * 获取未读条数
   * @author Max.Yu <max@jipu.com>
   */
  public function getUnreadNum($uid = 0){
    $mid_lists = M('MessageRecord')->field('message_id')->where(array('uid' => $uid))->select();
    $mids = array_column($mid_lists, 'message_id');
    empty($mids) && $mids = array(0);
    $where = $this->getWhereArray($uid);
    $where['id'] = array('NOT IN', $mids);
    $count = M('Message')->where($where)->count();
    return $count;
  }

}

