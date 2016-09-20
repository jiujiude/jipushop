<?php
/**
 * 微信消息模型
 * @version 2015102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class WechatMsgModel extends Model{

  /**
   * 获取一条数据详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map){
    if(!$map){
      return false;
    }
    $map['status'] = 1;
    $data = $this->where($map)->find();
    if($data['attach']){
      $coverArr = get_cover($data['attach']);
      $data['attach_url'] = $coverArr['path'];
    }
    return $data;
  }

}
