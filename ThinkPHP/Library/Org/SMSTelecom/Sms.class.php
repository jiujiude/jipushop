<?php

/**
 * 天翼短信平台短信发送类
 * 主要功能：
 * 1、发送模板短信 send_tpl_sms()
 *
 * @version 2015012417
 * @author Max.Yu <max@jipu.com>
 */

namespace Org\SMSTelecom;

class Sms{

  //申请应用时分配的app_key
  protected $AppKey = '';
  //申请应用时分配的 app_secret
  protected $AppSecret = '';
  //无需用户授权的表单令牌
  protected $AccessTokenUITI = '';
  //获取令牌地址
  protected $TokenApi = 'https://oauth.api.189.cn/emp/oauth2/v3/access_token';
  //模板短信发送地址
  protected $SmsTplApi = 'http://api.189.cn/v2/emp/templateSms/sendSms';
  //获取信任码地址
  protected $RandCodeToken = 'http://api.189.cn/v2/dm/randcode/token';
  //自定义验证码发送地址
  protected $SmsCodeApi = 'http://api.189.cn/v2/dm/randcode/sendSms';

  //初始化一些参数
  public function __construct(){
    $config = C('THINK_SMS_TELECOM');
    $this->AppKey = $config['APP_KEY'];
    $this->AppSecret = $config['APP_SECRET'];
  }

  /**
   * 获取无需用户授权的令牌
   */
  public function getAccessTokenUITI(){
    if(!S('SMSTelecom_token_UITI')){
      $post_data = array(
        'grant_type' => 'client_credentials',
        'app_id' => $this->AppKey,
        'app_secret' => $this->AppSecret,
      );
      $res = $this->http($this->TokenApi, $post_data, 'POST');
      $res = json_decode($res, true);
      if($res['res_code'] == 0){
        $access_token = $res['access_token'];
        S('SMSTelecom_token_UITI', $access_token, $res['expires_in']);
      }
    }else{
      $access_token = S('SMSTelecom_token_UITI');
    }
    $this->AccessTokenUITI = $access_token;
    return $access_token;
  }

  /**
   * 获取信任码
   */
  public function getRandCodeToken(){
    $token = $this->getAccessTokenUITI();
    if(empty($token)){
      return '';
    }
    $get_data = array(
      'app_id' => $this->AppKey,
      'access_token' => $token,
      'timestamp' => date('Y-m-d H:i:s'),
    );
    ksort($get_data);
    $plaintext = '';
    foreach($get_data as $k => $v){
      $plaintext .= '&'.$k.'='.$v;
    }
    $plaintext = substr($plaintext,1);
    $get_data['sign'] = base64_encode(hash_hmac('sha1', $plaintext, $this->AppSecret, True));
    $res = $this->http($this->RandCodeToken, $get_data, 'GET');
    $res = json_decode($res, true);
    if($res['res_code'] == 0){
      $this->Randcode = $res['token'];
      return $res['token'];
    }else{
      return '';
    }
  }
  
  
  
  /**
   * 发送模板短信
   * @param mobileNo $acceptor_tel 手机号码 暂不支持0开头的小灵通号码
   * @param string $tpl_id 在天翼平台的短信模板ID
   * @param array $param 短信模板中替换的参数
   */
  public function send_tpl_sms($acceptor_tel = '', $tpl_id = 0, $param = array()){
    $return_data = array('code' => 300, 'msg' => '');
    if(!preg_match("/1[34578]{1}\d{9}$/", $acceptor_tel)){
      $return_data['msg'] = '非法手机号码';
      return $return_data;
    }
    if(strlen($tpl_id) < 6){
      $return_data['msg'] = '短信模板ID非法';
      return $return_data;
    }
    if(empty($param)){
      $return_data['msg'] = '短信替换参数不能为空';
      return $return_data;
    }
    $token = $this->getAccessTokenUITI();
    if(empty($token)){
      $return_data['msg'] = '非法请求，没有找到必要参数sms_token';
      return $return_data;
    }
    //请求的数据
    $post_data = array(
      'app_id' => $this->AppKey,
      'access_token' => $token,
      'acceptor_tel' => $acceptor_tel,
      'template_id' => $tpl_id,
      'template_param' => json_encode($param),
      'timestamp' => date('Y-m-d H:i:s'),
    );
    //dump($postData);
    $res = $this->http($this->SmsTplApi, $post_data, 'POST');
    $res = json_decode($res, true);
    //dump($res);
    if($res['res_code'] == 0){
      return array('code' => 200, 'msg' => '发送成功');
    }else{
      return array('code' => 300, 'msg' => $res['res_message']);
    }
  }

  /**
   * 发送验证码短信
   * @param mobileNo $acceptor_tel 手机号码 暂不支持0开头的小灵通号码
   * @param string $randcode 短信验证码
   */
  public function send_code_sms($acceptor_tel = '', $randcode = ''){
    //模拟发送
    $test_send = C('SMS_TELECOM_TEST') ? true : false;
    $return_data = array('code' => 300, 'msg' => '');
    if(!preg_match("/1[34578]{1}\d{9}$/", $acceptor_tel)){
      $return_data['msg'] = '非法手机号码';
      return $return_data;
    }
    if(strlen($randcode) < 4){
      $return_data['msg'] = '短信验证码不能为空';
      return $return_data;
    }
    if($test_send){
      $token = 'test_token';
    }else{
      $token = $this->getAccessTokenUITI();
      if(empty($token)){
        $return_data['msg'] = '非法请求，没有找到必要参数sms_token';
        return $return_data;
      }
    }
    $post_data = array(
      'app_id' => $this->AppKey, //应用ID
      'access_token' => $token, //访问令牌
      'token' => $test_send ? 'test' : $this->getRandCodeToken(), //信任码
      'phone' => $acceptor_tel, //要发送的手机号码
      'randcode' => $randcode, //下发的验证码
      'exp_time' => '2', //有效期
      'timestamp' => date('Y-m-d H:i:s'), //时间戳
    );
    ksort($post_data);
    $plaintext = '';
    foreach($post_data as $k => $v){
      $plaintext .= '&'.$k.'='.$v;
    }
    $plaintext = substr($plaintext,1);
    $post_data['sign'] = base64_encode(hash_hmac('sha1', $plaintext, $this->AppSecret, True));
    if($test_send){
      $res['indentifier'] = 'test'.time();
      $res['create_at'] = time();
    }else{
      $res = $this->http($this->SmsCodeApi, $post_data, 'POST');
      $res = json_decode($res, true);
    }
    if($res['create_at']){
      return array('code' => 200, 'msg' => '发送成功', 'identifier' => $res['identifier'], 'create_at'=> $res['create_at']);
    }else{
      return array('code' => 300, 'msg' => '发送失败');
    }
  }

  /**
   * 发送HTTP请求方法，目前只支持CURL发送请求
   * @param  string $url    请求URL
   * @param  array  $params 请求参数
   * @param  string $method 请求方法GET/POST
   * @return array  $data   响应数据
   */
  protected function http($url, $params, $method = 'GET', $header = array(), $multi = false){
    $opts = array(
      CURLOPT_TIMEOUT => 30,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_HTTPHEADER => $header
    );
    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method)){
      case 'GET':
        $opts[CURLOPT_URL] = $url.'?'.http_build_query($params);
        break;
      case 'POST':
        //判断是否传输文件
        $params = $multi ? $params : http_build_query($params);
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POST] = 1;
        $opts[CURLOPT_POSTFIELDS] = $params;
        break;
      default:
        throw new Exception('不支持的请求方式！');
    }
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if($error){
      throw new Exception('请求发生错误：'.$error);
    }
    return $data;
  }
}
