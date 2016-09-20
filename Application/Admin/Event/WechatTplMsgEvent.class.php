<?php
/**
 * 微信模板消息事件类
 * 
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Event;

class WechatTplMsgEvent{

  public function formatDataList($list){
    foreach($list as $type => &$v){
      foreach($v as $key => &$val){
        $where = array(
          'type' => $type,
          'tpl_key' => $key
        );
        $val['data'] = M('WechatTplMsg')->where($where)->find();
      }
    }
    return $list;
  }
}
  