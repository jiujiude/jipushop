<?php
// +----------------------------------------------------------------------
// | JipuShop
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.jipushop.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Max.Yu <max@jipu.com>
// +----------------------------------------------------------------------
// | TelecomSDK.class.php 2013-02-25
// +----------------------------------------------------------------------
namespace Org\ThinkSDK\sdk;
use Think\Exception;
use Org\ThinkSDK\ThinkSms;

class TelecomSDK extends ThinkSms{
	/**
	 * 获取requestCode的api接口
	 * @var string
	 */
	protected $GetRequestCodeURL = 'https://oauth.api.189.cn/emp/oauth2/v3/authorize';

	/**
	 * 获取授权的api接口
	 * @var string
	 */
	protected $GetAuthorizeURL = 'https://oauth.api.189.cn/emp/oauth2/v3/authorize';

	/**
	 * 获取access_token的api接口
	 * @var string
	 */
	protected $GetAccessTokenURL = 'https://oauth.api.189.cn/emp/oauth2/v3/access_token';

	/**
	 * 获取信任码的api接口
	 * @var string
	 */
	protected $GetRandcodeURL = 'http://api.189.cn/v2/dm/randcode/token';

	/**
	 * 短信发送api接口
	 * @var string
	 */
	protected $GetSendSmsURL = 'http://api.189.cn/v2/dm/randcode/sendSms';


	/**
	 * API根路径
	 * @var string
	 */
	protected $ApiBase = 'http://api.189.cn/v2';
	
	/**
	 * 组装接口调用参数 并调用接口
	 * @param  string $api    微博API
	 * @param  string $param  调用API的额外参数
	 * @param  string $method HTTP请求方法 默认为GET
	 * @return json
	 */
	public function call($api, $param = '', $method = 'GET', $multi = false){		
		$params = array(
			'access_token' => $this->Token['access_token'],
		);
		$vars = $this->param($params, $param);
		$data = $this->http($this->url($api, '.json'), $vars, $method, array(), $multi);
		return json_decode($data, true);
	}
	
	/**
	 * 解析access_token方法请求后的返回值
	 * @param string $result 获取access_token的方法的返回值
	 */
	protected function parseToken($result, $extend){
		$data = json_decode($result, true);
		if($data['access_token'] && $data['expires_in'] && ($data['res_code'] == 0) && $data['res_message']){
			return $data;
		}else{
			throw new Exception("获取天翼开放平台ACCESS_TOKEN出错：{$data['error']}");
		}
	}
	
	/**
	 * 获取当前授权应用的openid
	 * @return string
	 */
	public function openid(){
		$data = $this->Token;
		if(isset($data['openid'])){
			return $data['openid'];
		}else{
			throw new Exception('没有获取到天翼开发平台用户ID！');
		}
	}
	
}