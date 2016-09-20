<?php
/**
 * 消息模型
 * @version 20151015
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class MessageRecordModel extends Model {
  
  /**
   * 添加阅读记录
   * @author Max.Yu <max@jipu.com>
   */
  public function update($message_id = 0, $delete = 0){
    if(empty($message_id)){
      return 0;
    }
    $map = array(
      'uid' => UID,
      'message_id' => $message_id
    );
    $record_id = $this->where($map)->getField('id');
    if($delete == 1){
      $map['status'] = -1;
    }
    if(!$record_id){
      $map['read_time'] = NOW_TIME;
      $record_id = $this->add($map);
    }elseif(isset($map['status'])){
      $this->where('id='.$record_id)->save($map);
    }
    return $record_id;
  }

}
