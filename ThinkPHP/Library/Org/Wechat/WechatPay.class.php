<?php
/**
 *
 * 微信api基础类
 *
 */
namespace Org\Wechat;

class WechatPay{

  private $wechat_config;

  public function __construct($wechat_config){
    $this->app_id = $wechat_config['app_id'];
    $this->app_secret = $wechat_config['app_secret'];
    $this->partnerid = $wechat_config['partnerid'];
    $this->partnerKey = $wechat_config['partnerKey'];
    $this->paySignKey = $wechat_config['paySignKey'];
  }

  public function open($api_url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $ret = curl_exec($ch);
    $error = curl_error($ch);
    if($error){
      return false;
    }
    $json = json_decode($ret, TRUE);
    return $json;
  }

  public function post($api_url, $data){
    $context = array('http' => array('method' => "POST", 'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) \r\n Accept: */*", 'content' => $data));
    $stream_context = stream_context_create($context);
    $ret = @file_get_contents($api_url, FALSE, $stream_context);
    return json_decode($ret, true);
  }

  /**
   * 获取access token
   * @return array
   */
  public function access_token($cache=true){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->app_id&secret=$this->app_secret";
    $access_token = '';
    $cachefile = "./token.txt";
    if(file_exists($cachefile) && $cache){
      $access_token = file_get_contents($cachefile);
      return $access_token;
    }
    try{
      $ret = $this->open($url);
      @unlink($cachefile);
      file_put_contents($cachefile, $ret['access_token'], FILE_APPEND);
      return $ret['access_token'];
    }catch(Exception $e){
      return '';
    }
  }

  /**
   * 除去数组中的空值和签名参数
   * @param $para 签名参数组
   * return 去掉空值与签名参数后的新签名参数组
   */
  public function parafilter($para){
    $para_filter = array();
    foreach($para as $key => $val){
      if($key == 'sign_method' || $key == 'sign' || $val == ''){
        continue;
      }else{
        $para_filter[$key] = $para[$key];
      }
    }
    return $para_filter;
  }

  /**
   * 对数组排序
   * @param $para 排序前的数组
   * return 排序后的数组
   */
  public function argsort($para){
    ksort($para);
    reset($para);
    return $para;
  }

  /**
   * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
   * @param $para 需要拼接的数组
   * return 拼接完成以后的字符串
   */
  public function createlinkstring($para){
    $arg  = '';
    foreach($para as $key => $val ){
      $arg.=strtolower($key).'='.$val.'&';
    }
    //去掉最后一个&字符
    $arg = substr($arg, 0, count($arg)-2);
    //如果存在转义字符，那么去掉转义
    if(get_magic_quotes_gpc()){
      $arg = stripslashes($arg);
    }
    return $arg;
  }

  /**
   * 创建app_signature
   * @return string
   */
  public function create_app_signature($arr){
    $para = $this->parafilter($arr);
    $para = $this->argsort($para);
    $signValue = sha1($this->createlinkstring($para));
    return $signValue;
  }

  /**
   * 创建sign
   * @return string
   */
  public function create_sign($arr){
    $para = $this->parafilter($arr);
    $para = $this->argsort($para);
    $signValue = $this->createlinkstring($para);
    $signValue = $signValue.'&key='.$this->partnerKey;
    $signValue = strtoupper(md5($signValue));
    return $signValue;
  }

  /**
   * 获取用户基本信息
   * @return array
   */
  public function user_info($openid){
    $ret = $this->open('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token().'&openid=$openid&lang=zh_CN');
    if(in_array($ret['errcode'], array(40001, 40002, 42001))){
      $this->access_token(false);
      return $this->user_info($openid);
    }
    return $ret;
  }

  /**
   * 标记客户的投诉处理状态
   * @return bool
   */
  public function payfeedback_update($openid, $feedbackid){
    $url = 'https://api.weixin.qq.com/payfeedback/update?access_token='.$this->access_token().'&openid='.$openid.'&feedbackid='.$feedbackid;
    $ret = $this->open($url);
    if(in_array($ret['errcode'], array(40001, 40002, 42001))){
      $this->access_token(false);
      return $this->payfeedback_update($openid,$feedbackid);
    }
    return $ret;
  }

  /**
   * 发货通知
   * openid 购买用户的 OpenId，这个已经放在最终支付结果通知的 PostData 里了
   * transid 交易单号
   * out_trade_no 第三方订单号
   * deliver_timestamp 发货时间戳
   * deliver_status 发货状态  1:成功 0:失败
   * deliver_msg 发货状态信息
   */
  public function delivernotify($openid, $transid, $out_trade_no, $deliver_status = 1, $deliver_msg = 'ok'){
    $post = array();
    $post['appid'] = $this->app_id;
    $post['appkey'] = $this->paySignKey;
    $post['openid'] = $openid;
    $post['transid'] = $transid;
    $post['out_trade_no'] = $out_trade_no;
    $post['deliver_timestamp'] = time();
    $post['deliver_status'] = $deliver_status;
    $post['deliver_msg'] = $deliver_msg;
    $post['app_signature'] = $this->create_app_signature($post);
    $post['sign_method'] = 'SHA1';

    $data = json_encode($post);
    $url = 'https://api.weixin.qq.com/pay/delivernotify?access_token=' . $this->access_token();
    $ret = $this->post($url, $data);
    if(in_array($ret['errcode'], array(40001, 40002, 42001))){
      $this->access_token(false);
      return $this->delivernotify($openid, $transid, $out_trade_no, $deliver_status, $deliver_msg);
    }
    return $ret;
  }

  /**
   * 订单查询
   * @return array
   */
  public function order_query($out_trade_no){
    $post = array();
    $post['appid'] = $this->app_id;
    $sign = $this->create_sign(array('out_trade_no' => $out_trade_no , 'partner' => $this->partnerid ));
    $post['package'] = 'out_trade_no='.$out_trade_no.'&partner='.$this->partnerid.'&sign='.$sign;
    $post['timestamp'] = time();

    $post['app_signature'] = $this->create_app_signature(array('appid' => $this->app_id , 'appkey' => $this->paySignKey , 'package' => $post['package'] , 'timestamp' => $post['timestamp'] ));
    $post['sign_method'] = 'SHA1';

    $data = json_encode($post);
    $url = 'https://api.weixin.qq.com/pay/orderquery?access_token=' . $this->access_token();
    $ret = $this->post($url, $data);
    if(in_array($ret['errcode'],array(40001, 40002, 42001))){
      $this->access_token(false);
      return $this->order_query($out_trade_no);
    }
    return $ret;
  }

  /**
   * 构建支付请求数组
   * @return array
   */
  public function bulidForm($parameter){
    $parameter['package'] = $this->buildPackage($parameter); // 生成订单package
    $paySignArray = array(
      'appid' => $this->app_id,
      'appkey' => $this->paySignKey,
      'noncestr' => $parameter['noncestr'],
      'package' => $parameter['package'],
      'timestamp' => $parameter['timestamp']
    );
    $parameter['paysign'] = $this->create_app_signature($paySignArray);
    return $parameter;
  }

  /**
   * 构建支付请求包
   * @return string
   */
  public function buildPackage($parameter){
    $filter = array('bank_type', 'body', 'attach', 'partner', 'out_trade_no', 'total_fee', 'fee_type', 'notify_url','spbill_create_ip', 'time_start', 'time_expire', 'transport_fee', 'product_fee', 'goods_tag', 'input_charset');
    $base = array(
      'bank_type' => 'WX',
      'fee_type' => '1',
      'input_charset' => 'UTF-8',
      'partner' => $this->partnerid
    );
    $parameter = array_merge($parameter, $base);
    $array = array();
    foreach($parameter as $k => $v){
      if(in_array($k, $filter)){
        $array[$k] = $v;
      }
    }
    ksort($array);
    $signPars = '';
    reset($array);
    foreach($array as $k => $v){
      $signPars .= strtolower($k).'='.$v.'&';
    }
    $sign = strtoupper(md5($signPars.'key='.$this->partnerKey));
    $signPars = '';
    reset($array);
    foreach ($array as $k => $v){
      $signPars .= strtolower($k).'='.urlencode($v).'&';
    }
    return $signPars.'sign='.$sign;
  }

  /**
   * 从xml中获取数组
   * @return array
   */
  public function getXmlArray(){
    $postStr = @file_get_contents('php://input');
    if($postStr){
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      if(!is_object($postObj)){
        return false;
      }
      $array = json_decode(json_encode($postObj), true); // xml对象转数组
      return array_change_key_case($array, CASE_LOWER); // 所有键小写
    }else{
      return false;
    }
  }

  /**
   * 验证服务器通知
   * @param array $data
   * @return array
   */
  public function verifyNotify($post, $sign){
    $para = $this->parafilter($post);
    $para = $this->argsort($para);
    $signValue = $this->createlinkstring($para);
    $signValue = $signValue.'&key='.$this->partnerKey;
    $signValue = strtoupper(md5($signValue));
    if($sign == $signValue){
      return true;
    }else{
      return true;
    }
  }

  /**
   * 是否支持微信支付
   * @return bool
   */
  public function is_show_pay($agent){
    $ag1  = strstr($agent, 'MicroMessenger');
    $ag2 = explode('/', $ag1);
    $ver = floatval($ag2[1]);
    if($ver < 5.0 || empty($aid)){
      return false;
    }else{
      return true;
    }
  }

}