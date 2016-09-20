<?php
/**
 * 短信平台事件接口
 * @version 2015112345
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Event;

use Org\SMSYunpian\Sms;

class SmsEvent{
  
  /**
   * 查账户信息
   */
  public function getUser($flush = false){
    if(S('sms_user_get') == false || $flush){
      $data = SMS::doit('user/get');
      if($data['code'] == 0){
        S('sms_user_get', $data);
      }
    }else{
      $data = S('sms_user_get');
      //echo 1;
    }
    return $data;
  }
  
  /**
   * 设置账户信息
   */
  public function setUser(){
    if(check_form_hash()){
      $save_data = array(
        'emergency_contact' => I('post.emergency_contact', ''), //紧急联系人
        'emergency_mobile' => I('post.emergency_mobile', ''), //联系人电话
        'alarm_balance' => I('post.alarm_balance', ''), //短信阈值
      );
      $data = SMS::doit('user/set', $save_data);
      //更新缓存
      if($data['code'] == 0){
        $user_info = $this->getUser();
        $user_info['user'] = array_merge($user_info['user'], $save_data);
        S('sms_user_get', $user_info , 86400);
      }
    }else{
      $data = array('code' => '-997' , 'msg' => '非法提交');
    }
    return $data;
  }
  
  /**
   * 模板列表
   */
  public function listsTpl(){
    //默认模板
    if(S('sms_tpl_default') == false){
      $default = SMS::doit('tpl/get_default');
      if($default['code'] == 0){
        foreach($default['template'] as &$v){
          $v['is_system_create'] = 1; 
        }
        S('sms_tpl_default', $default);
      }
    }else{
      $default = S('sms_tpl_default');
    }
    //自定义模板
    if(S('sms_tpl_diy') == false){
      $diy = SMS::doit('tpl/get');
      if($diy['code'] == 0){
        S('sms_tpl_diy', $diy);
      }
    }else{
      $diy = S('sms_tpl_diy');
    }
    //拼装模板序列
    $tpl = array_merge($default['template'], $diy['template']);
    $tpl = $this->sort_by_array($tpl, 'tpl_id', 'desc');
    return $tpl;
  }
  
  /**
   * 根据字段值排序
   * @param array $array 数组
   * @param string $key 字段名
   * @param string $sort 排序方式（默认正序）
   * @return array 整理后的数组
   */
  public function sort_by_array($array, $key = 'id', $sort = 'asc'){
    $data = array();
    $vals = array_column($array, $key);
    if($sort == 'asc'){
      sort($vals);
    }else{
      rsort($vals);
    }
    foreach($vals as $val){
      foreach($array as $k => $v){
        if($v[$key] == $val){
          $data[$k] = $v;
        }
      }
    }
    return $data;
  }
  
  /**
   * 模板添加编辑
   */
  public function updateTpl(){
    if(check_form_hash()){
      $tpl_id = I('post.tpl_id', 0);
      $tpl_content = I('post.tpl_content', '');
      if($tpl_id == 0){
        $data = SMS::doit('tpl/add', array('tpl_content' => $tpl_content));
      }else{
        $data = SMS::doit('tpl/update', array('tpl_id' => $tpl_id, 'tpl_content' => $tpl_content));
      }
      if($data['code'] == 0){
        S('sms_tpl_diy', null);
      }
    }else{
      $data = array('code' => '-997' , 'msg' => '非法提交');
    }
    return $data;
  }
  
  /**
   * 模板删除
   */
  public function removeTpl($tpl_id = 0){
    if(check_form_hash()){
      $data = SMS::doit('tpl/del', array('tpl_id' => $tpl_id));
      if($data['code'] == 0){
        S('sms_tpl_diy', null);
      }
    }else{
      $data = array('code' => '-997' , 'msg' => '非法提交');
    }
    return $data;
  }
}
