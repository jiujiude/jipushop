<?php
// +----------------------------------------------------------------------
// | JipuShop
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.jipushop.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Max.Yu <max@jipu.com>
// +----------------------------------------------------------------------
namespace Org\ThinkSDK;
header("Content-Type: text/html; charset=utf-8");
use Think\Exception;
abstract class ThinkSms{
	/**
	 * oauth版本
	 * @var string
	 */
	protected $Version = '2.0';
	
	/**
	 * 申请应用时分配的app_key
	 * @var string
	 */
	protected $AppKey = '';
	
	/**
	 * 申请应用时分配的 app_secret
	 * @var string
	 */
	protected $AppSecret = '';

	/**
	 * 授权类型 response_type 目前只能为code
	 * @var string
	 */
	protected $ResponseType = 'code';

	/**
	 * grant_type 目前只能为 client_credentials
	 * @var string 
	 */
	protected $GrantType = 'client_credentials';
	
	/**
	 * 回调页面URL  可以通过配置文件配置
	 * @var string
	 */
	protected $Callback = '';
	
	/**
	 * 短信验证码接收URL  可以通过配置文件配置
	 * @var string
	 */
	protected $Receive = '';

	/**
	 * 短信发送结果
	 * @var string
	 */
	protected $SendResult = '';

	/**
	 * 获取request_code的额外参数 URL查询字符串格式
	 * @var srting
	 */
	protected $Authorize = '';
	
	/**
	 * 获取request_code请求的URL
	 * @var string
	 */
	protected $GetRequestCodeURL = '';

	/**
	 * 获取授权请求的URL
	 * @var string
	 */
	protected $GetAuthorizeURL = '';
	
	/**
	 * 获取access_token请求的URL
	 * @var string
	 */
	protected $GetAccessTokenURL = '';

	/**
	 * 获取信任码请求的URL
	 * @var string
	 */
	protected $GetRandcodeURL = '';

	/**
	 * 发送短信请求的URL
	 * @var string
	 */
	protected $GetSendSmsURL = '';

	/**
	 * API根路径
	 * @var string
	 */
	protected $ApiBase = '';
	
	/**
	 * 授权后获取到的TOKEN信息
	 * @var array
	 */
	protected $Token = null;

	/**
	 * 调用接口类型
	 * @var string
	 */
	private $Type = '';
	
	/**
	 * 构造方法，配置应用信息
	 * @param array $token 
	 */
	public function __construct($token = null){
		//获取应用配置
		$config = C('THINK_SMS_TELECOM');
		if(empty($config['APP_KEY']) || empty($config['APP_SECRET'])){
			throw new Exception('请配置您申请的APP_KEY和APP_SECRET');
		} else {
			$this->AppKey = $config['APP_KEY'];
			$this->AppSecret = $config['APP_SECRET'];
			$this->Token = $token; //设置获取到的TOKEN
		}
	}

	/**
   * 取得Sms实例
   * @static
   * @return mixed 返回Sms
   */
  public static function getInstance($token = null) {
  	require_once 'sdk/TelecomSDK.class.php';
  	$class = 'Org\\ThinkSDK\\sdk\\TelecomSDK';
  	if(class_exists($class)){
  		return new $class($token);
  	}else{
  		halt(L('_CLASS_NOT_EXIST_') . ':' . $class);
  	}
  }

	/**
	 * 初始化配置
	 */
	private function config(){
		$config = C('THINK_SMS_TELECOM');
		if(!empty($config['AUTHORIZE'])){
			$this->Authorize = $config['AUTHORIZE'];
		}
		if(!empty($config['CALLBACK'])){
			$this->Callback = $config['CALLBACK'];
		}else{
			throw new Exception('请配置回调页面地址');
		}
		if(!empty($config['RECEIVE'])){
			$this->Receive = $config['RECEIVE'];
		}else{
			throw new Exception('请配置短信验证码接收地址');
		}
	}
	
	/**
	 * 请求code 
	 */
	public function getRequestCodeURL(){
		$this->config();
		//Sms 标准参数
		$params = array(
			'app_id' => $this->AppKey,
			'redirect_uri' => $this->Callback,
			'response_type' => $this->ResponseType,
		);
		//获取额外参数
		if($this->Authorize){
			parse_str($this->Authorize, $_param);
			if(is_array($_param)){
				$params = array_merge($params, $_param);
			} else {
				throw new Exception('AUTHORIZE配置不正确！');
			}
		}
		return $this->GetRequestCodeURL . '?' . http_build_query($params);
	}
	
	/**
	 * 获取access_token
	 * @param string $code 上一步请求到的code
	 */
	public function getAccessToken(){
		$this->config();
		$params = array(
			'grant_type' => $this->GrantType,
			'app_id' => $this->AppKey,
			'app_secret' => $this->AppSecret,
		);
		$data = $this->http($this->GetAccessTokenURL, $params, 'POST');
		$this->Token = $this->parseToken($data, $extend);
		return $this->Token;
	}

	/**
	 * 获取信任码token
	 * @param string $access_token 上一步请求到的access_token
	 */
	public function getRandcode($access_token){
		$this->config();
		$timestamp = date('Y-m-d H:i:s');
		$app_secret = $this->AppSecret;
    $url = 'http://api.189.cn/v2/dm/randcode/token?';
    $param['app_id']= 'app_id='.$this->AppKey;
    $param['timestamp'] = 'timestamp='.$timestamp;
    $param['access_token'] = 'access_token='.$access_token;
    ksort($param);
    $plaintext = implode('&', $param);
    $cipherText = base64_encode(hash_hmac('sha1', $plaintext, $appsecret, $raw_output=True));
    $param['sign'] = 'sign='.rawurlencode(base64_encode(hash_hmac('sha1', $plaintext, $app_secret, True)));
    $url .= implode('&', $param);
    $result = $this->curl_get($url);
    $this->Randcode = json_decode($result, true);

    // $token = $resultArray['token'];
    // $data = $this->http($this->GetRandcodeURL, $param);
    // print_r($data);
		// $params = array(
		// 	'app_id' => $this->AppKey,
		// 	'access_token' => $access_token,
		// 	'timestamp' => date('Y-m-d H:i:s'),
		// 	'sign' => rawurlencode(base64_encode(hash_hmac('sha1', $plaintext, $app_secret, $raw_output = True)))
		// );
		// $data = $this->http($this->GetRandcodeURL, $params);
		// print_r($data);
		// $this->Randcode = $this->parseToken($data);
		return $this->Randcode;
	}


	/**
	 * 发送短信
	 * @param string $access_token 
	 * @param string $token 
	 * @param string $phone 
	 * @param string $exp_time 
	 */
	public function doSendSms($access_token, $token, $phone, $randcode, $exp_time){
		$this->config();
		$timestamp = date('Y-m-d H:i:s');
		$app_secret = $this->AppSecret;
		$dataurl = $this->Receive;

    $param['app_id']= 'app_id='.$this->AppKey;
    $param['access_token'] = 'access_token='.$access_token;
    $param['timestamp'] = 'timestamp='.$timestamp;
    $param['token'] = 'token='.$token;
    $param['randcode'] = 'randcode='.$randcode;
    $param['phone'] = 'phone='.$phone;
    $param['url'] = 'url='.$dataurl;
    if(isset($exp_time)){
    	$param['exp_time'] = 'exp_time='.$exp_time;
    }

    ksort($param);
    $plaintext = implode('&', $param);
    $param['sign'] = 'sign='.rawurlencode(base64_encode(hash_hmac('sha1', $plaintext, $app_secret, $raw_output=True)));
    ksort($param);
    $str = implode('&', $param);
    $result = $this->curl_post($this->GetSendSmsURL, $str);
    $this->SendResult = json_decode($result, true);
    return $this->SendResult;
  }

	/**
	 * 合并默认参数和额外参数
	 * @param array $params  默认参数
	 * @param array/string $param 额外参数
	 * @return array:
	 */
	protected function param($params, $param){
		if(is_string($param)){
			parse_str($param, $param);
		}
		return array_merge($params, $param);
	}

	/**
	 * 获取指定API请求的URL
	 * @param  string $api API名称
	 * @param  string $fix api后缀
	 * @return string      请求的完整URL
	 */
	protected function url($api, $fix = ''){
		return $this->ApiBase . $api . $fix;
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
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_HTTPHEADER     => $header
		);

		/* 根据请求类型设置特定参数 */
		switch(strtoupper($method)){
			case 'GET':
				$opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
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
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if($error) throw new Exception('请求发生错误：' . $error);
		return  $data;
	}

	protected function curl_get($url='', $options=array()){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    if(!empty($options)){
      curl_setopt_array($ch, $options);
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
	}

	protected function curl_post($url='', $postdata='', $options=array()){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    if(!empty($options)){
      curl_setopt_array($ch, $options);
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
	}
	
	/**
	 * 抽象方法，在SNSSDK中实现
	 * 组装接口调用参数 并调用接口
	 */
	abstract protected function call($api, $param = '', $method = 'GET', $multi = false);
	
	/**
	 * 抽象方法，在SNSSDK中实现
	 * 解析access_token方法请求后的返回值
	 */
	abstract protected function parseToken($result, $extend);
	
	/**
	 * 抽象方法，在SNSSDK中实现
	 * 获取当前授权用户的SNS标识
	 */
	abstract public function openid();	
}