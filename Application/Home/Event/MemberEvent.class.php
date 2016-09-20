<?php
/**
 * 会员事件处理
 * 主要用于处理短信校验码发送、邮件验证、手机短信验证、邮件验证
 * @version 2014102315
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Event;

use Org\ThinkSDK\ThinkSms;

class MemberEvent{

  //手机验证码有效期
  private $mobile_expire;

  public function __construct(){
    $this->mobile_expire = 500;
  }

  /**
   * 发送验证短信
   * @param $mobile 手机号
   * @param $randcode 校验码
   * @return boolean
   * @author Max.Yu <max@jipu.com>
   */
  public function sendValidateSms($mobile, $randcode){
    if(empty($mobile) || empty($randcode)){
      return false;
    }
    //限制用户当日短信校验码发送次数 TODO:
    //加载ThinkSms类并实例化一个对象
    $sms = ThinkSms::getInstance();
    //IG模式获取access_token
    $access_token = $sms->getAccessToken();
    if(is_array($access_token)){
      $access_token = $access_token['access_token'];
      //获取信任码
      $token = $sms->getRandcode($access_token);
      if(is_array($token)){
        //短信发送
        if($token['res_code'] === 0){
          $sendsms = $sms->doSendSms($access_token, $token['token'], $mobile, $randcode, $this->mobile_expire);
          if(is_array($sendsms)){
            //返回短信校验码发送结果
            return ($sendsms['res_code'] === 0) ? true : false;
          }
        }
      }else{
        return false;
      }
    }
    return false;
  }

  /** 
   * 判断验证码ID是否有效
   * @param string $code_id 校验码id
   * @return boolean
   * @author Max.Yu <max@jipu.com>
   */
  public function validateMobileCodeId($code_id){
    if(empty($code_id)){
      return false;
    }
    $expire_time = NOW_TIME - $this->mobile_expire*60*1000;
    $map_code = array(
      'id' => $code_id,
      'has_used' => 0,
      'uid' => UID,
      'type' => 1,
      'create_time' => array('gt', $expire_time)
    );
    return D('MemberBind')->where($map_code)->count();
  }

  /** 
   * 执行校验码(手机、邮件)校验
   * @param string $randcode 校验码
   * @param int $type 类型：1-手机校验码，2-邮件校验码
   * @return array
   * @author Max.Yu <max@jipu.com>
   */
  public function validateCode($randcode, $type = 1){
    //根据类型判断校验码有效期
    $expire_time = $this->mobile_expire*60*1000;
    echo $this->mobile_expire;
    echo $this->wechat_appid;
    exit();

    //获取数据库中最后一次发送的校验码
    $map_code = array(
      'uid' => UID,
      'type' => $type
    );

    $last_randcode = D('MemberBind')->where($map_code)->order('create_time DESC')->find();
    if(!$randcode){
      $result = array(
        'status' => 0,
        'info' => '请您输入收到的校验码'
      );
    }else if(!$last_randcode){
      $result = array(
        'status' => 0,
        'info' => '抱歉，校验码无效'
      );
      $this->error('抱歉，验证失败');
    }else if((NOW_TIME - $last_randcode['create_time']) > $expire_time){
      $result = array(
        'status' => 0,
        'info' => '校验码已过期'
      );
    }else{
      if($randcode === $last_randcode['code']){
        //验证成功后更新校验码状态
        $result = array(
          'status' => 1,
          'info' => '验证成功',
          'code_id' => $last_randcode['id']
        );
      }else{
        $result = array(
          'status' => 0,
          'info' => '抱歉，校验码无效'
        );
      }
    }
    return $result;
  }

  /** 
   * 处理手机绑定业务
   * @param string $randcode 校验码
   * @param int $type 类型：1-手机校验码，2-邮件校验码
   * @return array
   * @author Max.Yu <max@jipu.com>
   */
  public function dealMobileBind($mobile){
    //更新ucenter_member中用户手机号
    $user_api = new UserApi;
    //$update_user = $user_api->updateUserMobile(UID, $mobile);
    $update_user = $user_api->update(UID, 'mobile', $mobile);
    //更新member表中手机绑定状态
    $data_member = array(
      'uid' => UID,
      'is_mobile_bind' => 1
    );
    $update_member = D('Member')->update($data_member);
    if(!$update_user || !$update_member){
      $result = array(
        'status' => 0,
        'info' => '抱歉，手机号码更新失败'
      );
    }else{
      $result = array(
        'status' => 1,
        'info' => '恭喜，验证成功'
      );
    }
  }

}
