<?php
/**
 * 前台用户控制器
 * @version 2014091618
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

use Common\Api\UserApi;
use Org\ThinkSDK\ThinkOauth;
use Org\Wechat\WechatAuth;

class UserController extends HomeController{

  private $wechat_token;
  private $wechat_appid;
  private $wechat_secret;

  public function _initialize(){
    parent::_initialize();
    $this->wechat_token = C('WECHAT_TOKEN');
    $this->wechat_appid = C('WECHAT_APPID');
    $this->wechat_secret = C('WECHAT_SECRET');
    $this->assign('user', $this->user);
    $this->assign('member', $this->member);
  }

  /**
   * 用户注册页面
   * @author Max.Yu <max@jipu.com>
   */
  public function register($username = '', $password = '', $repassword = '', $verify = '', $randcode = ''){
    if(!C('USER_ALLOW_REGISTER')){
      $this->error('注册已关闭');
    }
    if(IS_POST){ //注册用户
      if(!$username){
        $this->error('账号不能为空！');
      }
      if(!$password){
        $this->error('密码不能为空！');
      }
      if(!$repassword){
        $this->error('确认密码不能为空！');
      }
      if($password != $repassword){
        $this->error('两次输入的密码不一致！');
      }
      $type = $this->_checkUserType($username);
      if($type == 1){
        $this->error('暂不支持邮箱注册！');
      }else if($type == 2){
        if(!preg_match('/1[34578]{1}\d{9}$/', $username)){
          $this->error('手机格式不正确！');
        }
        //验证手机号是否已经注册
        $map['mobile'] = array('eq',$username);
        $map['status'] = array('gt',0);
        $user = M('User')->where($map)->find();
        $user && $this->error('手机号已被占用');
      }


      
      //验证短信验证码
      if(C('REGISTER_MOBILE_VALID') == 1){
        $valid_status = check_ypsms_code($username, 5, $randcode);
        $valid_status_b = check_ypsms_code($username, 2, $randcode);
        if(!($valid_status || $valid_status_b)){
          $this->error('短信验证码错误！');
        }
      }

      //调用注册接口注册用户
      $user_api = new UserApi;
      $uid = $user_api->register(0, $username, $password, $type);
      if(0 < $uid){
        //TODO: 发送验证邮件
        //注册成功后自动登录
        $uid = $user_api->login($username, $password, $type);
        if(0 < $uid){ //UC登录成功
          //注册来源-第三方帐号绑定
          if(I('post.oauth_type')){
            set_log('sean08' , $oauth);
            set_log('sean08' , $_POST);
            $oauth['uid'] = $uid;
            $oauth['type'] = I('post.oauth_type');
            $oauth['type_uid'] = I('post.type_uid');
            $oauth['oauth_token'] = I('post.oauth_token');
            $oauth['oauth_token_secret'] = I('post.oauth_token_secret');
            M('login')->add($oauth);

            //绑定后检测红包信息
            if($oauth['type'] == 'wechat'){
              A('RedPackage', 'Event')->isRegDoit($oauth['type_uid']);
            }
          }
          //如果通过手机验证，则修改绑定状态
          if(C('REGISTER_MOBILE_VALID') == 1){
            M('User')->where('id='.$uid)->setField('mobile_bind_status', 1);
          }
          //登录用户
          $member_model = D('Member');
          if($member_model->login($uid)){ //登录用户
            //登录后更新用户头像和昵称 TODO:
            $member_data = array(
              'uid' => $uid,
              'nickname' => I('post.nickname'),
              'avatar' => I('post.avatar'),
              'sex' => I('post.sex')
            );
            $this->saveThirdInfo($member_data);
            action_log('user_reg', 'user', $uid, $uid, 'user_reg_callback');
            //TODO:跳转到首页
            $this->success('注册成功！', Cookie('__forward__'));
          }else{
            $this->error($member_model->getError());
          }
        }
      }else{ //注册失败，显示错误信息
        $this->error($this->showRegError($uid));
      }
    }else{ //显示注册表单
      $this->meta_title = '注册';
      $this->display();
    }
  }

  /**
   * 检测用户名合法性
   */
  public function checkUsername($param){
    $type = $this->_checkUserType($param);
    $is_bind = I('get.isbind', 0);
    if($type == 2){
      $user_api = new UserApi;
      $check = $user_api->checkUsername($param, $type);
      $info_success = ($type == 1) ? '邮箱可注册' : '手机可注册';
      $info_error = ($type == 1) ? '邮箱已被注册，请直接登录' : ('手机已被注册，请直接登录');
      //绑定查询
      if($is_bind == 1){
        if($check){
          $result = array(
            'status' => 'y',
            'info' => '可以绑定'
          );
        }else{
          $result = array(
            'status' => 'n',
            'info' => '请创建新账号'
          );
        }
      }else{
        if($check){
          $result = array(
            'status' => 'n',
            'info' => $info_error
          );
        }else{
          $result = array(
            'status' => 'y',
            'info' => $info_success
          );
        }
      }
    }else{
      $result = array(
        'status' => 'n',
        'info' => '请使用手机注册'
      );
    }
    $this->ajaxReturn($result);
  }

  /**
   * 检测用户名合法性
   */
  public function checkVerify($param){
    if(!check_verify($param, 1, false)){
      $result = array(
        'status' => 'n',
        'info' => '验证码输入错误！'
      );
    }else{
      $result = array(
        'status' => 'y',
      );
    }
    $this->ajaxReturn($result);
  }

  /**
   * 用户登录页面
   * @author Max.Yu <max@jipu.com>
   */
  public function login($username = '', $password = '', $remember = ''){
    $member_model = D('Member');
    if(IS_POST){ //登录验证
      /* 判断邮箱还是手机登录 */
      $login_type = $this->_checkUserType($username);
      $user_api = new UserApi;
      $uid = $user_api->login($username, $password, $login_type);
      if(0 < $uid){ //UC登录成功
        //登录用户
        if($member_model->login($uid)){ //登录用户
          //TODO:跳转到登录前页面
          //是否设置自动登录
          if($remember == 1){
            $remember_data = think_encrypt($username);
            //将remember_data存入数据库
            $auto_data = array(
              'uid' => $uid,
              'auto_login_token' => $remember_data
            );
            $member_model->update($auto_data);
            Cookie('__autologin__', $remember_data, C('USER_AUTO_LOGIN_DAYS') * 24 * 3600 + time());
          }
          $this->success('登录成功！', Cookie('__forward__'));
        }else{
          $this->error($member_model->getError());
        }
      }else{ //登录失败
        $third_login=\Home\Controller\ApiController::thirdLogin(array('username'=>$username,'password'=>$password));
        if($third_login)$this->success('登录成功！', Cookie('__forward__'));

        switch($uid){
          case -1: $error = '用户不存在或被禁用！';
            break; //系统级别禁用
          case -2: $error = '密码错误！';
            break;
          default: $error = '未知错误！';
            break; //0-接口参数错误（调试阶段使用）
        }
        $this->error($error);
      }
    }else{ //显示登录表单
      if(is_login()){
        $this->redirect('Member/index');
        //如果没有登录& 在微信内，自动跳转到微信登录
      }elseif(is_weixin() && !(strpos(SITE_DOMAIN, '192.168.') > -1)){
        $this->redirect('User/wechatlogin');
      }
      $auto_login_token = cookie('__autologin__');
      if($auto_login_token){
        $username = think_decrypt($auto_login_token);
        $this->username = $username;
      }
      $this->meta_title = '登录';
      $this->display();
    }
  }

  /**
   * 快速登录页面
   * @author Max.Yu <max@jipu.com>
   */
  public function quickLogin($username = '', $password = '', $remember = ''){
    $member_model = D('Member');
    if(IS_POST){ //登录验证
      //调用UC登录接口登录
      $login_type = $this->_checkUserType($username);
      $user = new UserApi;
      $uid = $user->login($username, $password, $login_type);
      if(0 < $uid){ //UC登录成功
        //登录用户
        if($member_model->login($uid)){ //登录用户
          //TODO:跳转到登录前页面
          //是否设置自动登录
          if($remember == 1){
            $remember_data = think_ucenter_md5($username);
            //将remember_data存入数据库
            $auto_data = array(
              'uid' => $uid,
              'auto_login_token' => $remember_data
            );
            $member_model->update($auto_data);
            cookie('__autologin__', $remember_data, C('USER_AUTO_LOGIN_DAYS') * 24 * 3600 + time());
          }
          $this->success('登录成功！', COOKIE('__forward__'));
        }else{
          $this->error($member_model->getError());
        }
      }else{
        switch($uid){
          case -1: $error = '用户不存在或被禁用！';
            break; //系统级别禁用
          case -2: $error = '密码错误！';
            break;
          default: $error = '未知错误！';
            break; //0-接口参数错误（调试阶段使用）
        }
        $this->error($error);
      }
    }else{
      $this->display();
    }
  }

  /**
   * 第三方登录
   * @author Max.Yu <max@jipu.com>
   */
  public function thirdlogin($type = null){
    if(empty($type)){
      $this->error('参数错误');
    }
    //判断是否开通
    $config = C("THINK_SDK_{$type}");
    if(empty($config['APP_KEY']) || empty($config['APP_SECRET'])){
      $this->error('您所选择的登录方式尚未开通！');
    }
    $sns = ThinkOauth::getInstance($type);
    //跳转到授权页面
    redirect($sns->getRequestCodeURL());
  }

  /**
   * 第三方登录授权回调页面
   * @author Max.Yu <max@jipu.com>
   */
  public function callback($type = null, $code = null){
    (empty($type) || empty($code)) && $this->error('参数错误');

    //加载ThinkOauth类并实例化一个对象
    $sns = ThinkOauth::getInstance($type);

    //腾讯微博需传递的额外参数
    $extend = null;
    if($type == 'tencent'){
      $extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
    }
    //请妥善保管这里获取到的Token信息，方便以后API调用
    //调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
    //如：$qq = ThinkOauth::getInstance('qq', $token);
    $token = $sns->getAccessToken($code, $extend);
    //获取当前登录用户信息
    if(is_array($token)){
      $access_token = $token['access_token'];
      $openid = $token['openid'];
      $userinfo = A('Type', 'Event')->$type($token);
      //检查是否成功获取用户信息
      if(empty($userinfo['name'])){
        $this->error('获取用户信息失败');
      }
      //检查是否存在这个用户的登录信息
      $map = array(
        'type_uid' => $openid,
        'type' => $type
      );
      $info = M('login')->where($map)->find();
      if($info){
        //获取用户信息
        $user = M('User')->where('id = '.$info['uid'])->find();
        //未在本站找到用户信息, 删除用户站外信息,让用户重新登录
        if(!$user){
          M('Login')->where($map)->delete();
          //已经绑定过，执行登录操作，设置token
        }else{
          if($info['oauth_token'] == ''){
            $syncdata['uid'] = $info['uid'];
            $syncdata['login_id'] = $info['login_id'];
            $syncdata['type_uid'] = $info['type_uid'];
            $syncdata['oauth_token'] = $access_token;
            $syncdata['oauth_token_secret'] = $openid;
            M('Login')->save($syncdata);
          }
          $user = new UserApi;
          $member_model = D('Member');
          if($member_model->login($info['uid'])){
            $this->success('登录成功！', COOKIE('__forward__'));
          }else{
            $this->error($member_model->getError());
          }
        }
      }else{
        $oauth = array(
          'type_uid' => $openid,
          'oauth_token' => $access_token,
          'oauth_token_secret' => $openid
        );
        //不存在用户登录信息，去注册页面
        $this->user = $userinfo;
        $this->type = $type;
        $this->oauth = $oauth;
        $this->display();
      }
    }else{
      $this->error('授权登录失败，请您稍后再试');
    }
  }

  /**
   * 第三方登录账户绑定页面
   * @author Max.Yu <max@jipu.com>
   */
  public function bind($username = '', $password = ''){
    //根据邮箱地址和密码判断是否存在该用户
    $login_type = $this->_checkUserType($username);
    $user_api = new UserApi;
    $uid = $user_api->login($username, $password, $login_type);
    if($uid > 0){
      //注册来源-第三方帐号绑定
      if(I('post.oauth_type')){
        //添加登录数据
        $data['uid'] = $uid;
        $data['type'] = I('post.oauth_type');
        $data['type_uid'] = I('post.type_uid');
        $data['oauth_token'] = I('post.oauth_token');
        $data['oauth_token_secret'] = I('post.oauth_token_secret');
        M('Login')->add($data);
        //更新用户信息
        $member_data = array(
          'uid' => $uid,
          'nickname' => I('post.nickname'),
          'avatar' => I('post.avatar'),
          'sex' => I('post.sex')
        );
        $this->saveThirdInfo($member_data);
      }else{
        $this->error('绑定失败，第三方信息不正确');
      }
      //登录用户
      $member_model = D('Member');
      if($member_model->login($uid)){
        $this->success('登录成功！', COOKIE('__forward__'));
        //异步保存用户头像
      }else{
        $this->error($member_model->getError());
      }
      return;
    }else{
      $this->error('绑定失败，请确认帐号密码正确'); //注册失败
    }
  }

  /**
   * 邀请链接业务处理
   */
  public function invite($s){
    cookie('__forward__','Member/index.html');
    $param = array();
    //初始化邀请码信息
    session('invite_user', null);
    //解析邀请链接
    if($s){
      $uid = invite_code($s);
      if($uid > 0){
        //记录邀请码信息
        session('invite_user', array('invite_code' => $s, 'invite_uid' => $uid));
      }
    }
    redirect(U('User/register'));
  }

  /**
   * 微信登录页面
   * @author Max.Yu <max@jipu.com>
   */
  public function wechatlogin(){
    //获取OpenId-快速登录
    $openid = A('Pay', 'Event')->getOpenId();
    $uid = M('Login')->where(array('type' => 'wechat', 'type_uid' => $openid))->getField('uid');
    if($uid > 0){
      if(D('Member')->login($uid)){
          redirect(COOKIE('__forward__'));
      }else{
          //微信登录地址
          $redirect_uri = SITE_URL.U('User/wechatcallback');
          //加载微信SDK
          $wechat = new WechatAuth($this->wechat_appid, $this->wechat_secret, $this->wechat_token);
          $this->error(D('Member')->getError(),$wechat->getRequestCodeURL($redirect_uri));
      }
      exit();
    }
    //如果需要从自定义API获取微信用户信息
    if(C('WECHAT_USERINFO_BY_API') == true){
      $this->wechatcallback();
    }else{
      $redirect_uri = SITE_URL.U('User/wechatcallback');
      //加载微信SDK
      $wechat = new WechatAuth($this->wechat_appid, $this->wechat_secret, $this->wechat_token);
      $access_token = $wechat->getAccessToken('code');
      if($access_token){
          $this->wechatcallback('isset');
      }else{
          //跳转到授权页面
          redirect($wechat->getRequestCodeURL($redirect_uri));
      }
    }
  }

  /**
   * 微信登录回调页面
   * @author Max.Yu <max@jipu.com>
   */
  public function wechatcallback($code = null){
    if(empty($code) && C('WECHAT_USERINFO_BY_API') != true){
      $this->redirect('User/login');
    }
    $wechat = new WechatAuth($this->wechat_appid, $this->wechat_secret, $this->wechat_token);
    $token = $wechat->getAccessToken('code', $code);
    //获取当前登录用户信息
    if(is_array($token)){
      $userinfo = $wechat->getUserInfo($token);
      $openid = $userinfo['openid'];
      //检查是否成功获取用户信息
      if(empty($openid)){
        $this->error('获取用户信息失败');
      }
      //检查是否存在这个用户的登录信息
      $map = array(
        'type_uid' => $openid,
        'type' => 'wechat'
      );
      $info = M('login')->where($map)->find();

      if($info){
        //获取用户信息
        $userMap['id'] = array('eq',$info['uid']);
        $userMap['status'] = array('egt',0);
        $user = M('User')->where($userMap)->find();
        //未在本站找到用户信息, 删除用户站外信息,让用户重新登录
        if(!$user){
          M('Login')->where($map)->delete();
          //自动登录
          if(C('AUTO_LOGIN') == 1){
              R('AutoLogin/register',array($userinfo));
          }
          $oauth = array(
              'type_uid' => $openid,
              'oauth_token' => $token['access_token'],
              'oauth_token_secret' => $openid
          );
          //不存在用户登录信息，去注册页面
          $this->userinfo = $userinfo;
          $this->oauth = $oauth;
          $this->meta_title = '微信登录';
          $this->display('wechatcallback');
          //已经绑定过，执行登录操作，设置token
        }else{
          //保存用户登录信息
          if($info['oauth_token'] == ''){
            $syncdata['uid'] = $info['uid'];
            $syncdata['login_id'] = $info['login_id'];
            $syncdata['type_uid'] = $info['type_uid'];
            $syncdata['oauth_token'] = $token['access_token'];
            $syncdata['oauth_token_secret'] = $openid;
            M('Login')->save($syncdata);
          }
          //更新用户信息
          $member_data = array(
            'uid' => $info['uid'],
            'nickname' => $userinfo['nickname'],
            'avatar' => $userinfo['headimgurl'],
            'sex' => $userinfo['sex']
          );
          $this->saveThirdInfo($member_data);
          $member_model = D('Member');
          //绑定后检测红包信息
          A('RedPackage', 'Event')->isRegDoit($openid);

          if($member_model->login($info['uid'])){
            $this->success('登录成功！', COOKIE('__forward__'));
          }else{
            $this->error($member_model->getError());
          }
        }
      }else{
        $oauth = array(
          'type_uid' => $openid,
          'oauth_token' => $token['access_token'],
          'oauth_token_secret' => $openid
        );
        //TODO:自动登录
        if(C('AUTO_LOGIN') == 1){
            R('AutoLogin/register',array($userinfo));
        }
        //不存在用户登录信息，去注册页面
        $this->userinfo = $userinfo;
        $this->oauth = $oauth;
        $this->meta_title = '微信登录';
        $this->display('wechatcallback');
      }
    }
  }

  /**
   * 用微信登录PC
   */
  public function wechatLoginPc(){
    $userEvent = A('User', 'Event');
    if(is_weixin()){
      $loginId = I('get.loginId');
      $res = $userEvent->wechatLoginPc($loginId);
      $this->res = $res;
      $this->display();
    }else{
      if(is_login()){
        $this->redirect('Member/index');
      }
      //获取二维码图片
      if(I('get.QRcode') == 1){
        $userEvent->getWechatLoginQrSrc();
      }
      //验证二维码是否登录并处理
      if(IS_AJAX){
        $res = $userEvent->wechatLoginCheck();
        $this->ajaxReturn($res);
      }else{
        $this->display();
      }
    }
  }

  /**
   * 退出登录
   * @author Max.Yu <max@jipu.com>
   */
  public function logout(){
    if(is_login()){
      D('Member')->logout();
      $this->success('退出成功！', U('/'));
    }else{
      $this->redirect('User/login');
    }
  }

  /**
   * 找回密码
   * @author Max.Yu <max@jipu.com>
   */
  public function forgetpwd(){
      $this->meta_title = '找回密码';
      $this->display();
  }

  /**
   * 邮件发送成功提示信息页面，不跳转
   * @return void
   */
  public function forgetmsg($url){
    $url = 'http://'.$url;
    $this->url = $url;
    $this->display();
  }

  /**
   * 重置密码页面
   * @return void
   */
  public function resetpwd($mobile = '', $randcode = ''){

    if(IS_POST){
      if(empty($mobile)){
        $this->error('手机号码不能为空！');
      }
      if(!preg_match('/1[34578]{1}\d{9}$/', $mobile)){
        $this->error('手机号码格式不正确！');
      }
      if(empty($randcode)){
        $this->error('手机验证码不能为空！');
      }
      //验证短信验证码
      //$valid_status = valid_randcode_sms($mobile, $randcode, 'forget');
      $valid_status = check_ypsms_code($mobile, 7, $randcode);
      if(!$valid_status){
        $this->error('手机验证码错误！');
      }
      //生成随机码
      $rand_code = md5(get_randstr(32).time());
      session('resetpwd_code', $rand_code);
      session('resetpwd_mobile_'.$rand_code, $mobile);
    }
    if(IS_AJAX && IS_POST){
      $this->success('验证成功！', U('User/resetpwd', array('code' => $rand_code)));
    }
    //兼容手机端跳过图片验证码
    if(empty($rand_code) && I('get.code') && I('get.code') == session('resetpwd_code')){
      $rand_code = I('get.code');
    }
    if(empty($rand_code)){
      redirect(U('User/forgetpwd'));
    }
    $this->rand_code = $rand_code;
    $this->meta_title = '输入新密码';
    $this->display();
  }

  /**
   * 执行重置密码页面
   * @return void
   */
  public function doResetpwd($rand_code = '', $password = '', $repassword = ''){

    if((session('resetpwd_code') != $rand_code) || session('resetpwd_mobile_'.$rand_code) == ''){
      $result = array('status' => 0, 'info' => '错误的请求！');
    }
    $mobile = session('resetpwd_mobile_'.$rand_code);
    if(empty($password) || empty($repassword)){
      $result['status'] = 0;
      $result['info'] = '新密码不能为空';
    }else if(strlen($password) < 6){
      $result['status'] = 0;
      $result['info'] = '密码不能少于6位';
    }else if($password !== $repassword){
      $result['status'] = 0;
      $result['info'] = '两次输入密码不一致';
    }else{
      $data_password = think_ucenter_md5($password);
      $user_api = new UserApi;
      $user = $user_api->infoByMobile($mobile);
      //$update = $user_api->updateUserPassword($user['id'], $data_password);
      $update = $user_api->update($user['id'], 'password', $data_password);
      if($update){
        session('resetpwd_code', null);
        session('resetpwd_mobile_'.$rand_code, null);
        $result['status'] = 1;
        $result['info'] = '密码修改成功，请重新登录';
        $result['url'] = U('User/login');
      }
    }
    if(IS_AJAX){
      $this->ajaxReturn($result);
    }else{
      if($result['status'] == 1){
        $this->success($result['info'], $result['url']);
      }else{
        $this->error($result['info']);
      }
    }
  }

  /**
   * 验证码，用于登录和注册
   * @return void
   */
  public function verify(){
    $config = array(
      'fontSize' => 50, //验证码字体大小
      'length' => 4, //验证码位数
      'useNoise' => true, //关闭验证码杂点
    );
    $verify = new \Think\Verify($config);
    $verify->entry(1);
  }

  /**
   * 检查重置密码的验证密钥
   * @return void
   */
  private function _checkResetPasswordCode($code){
    $map['code'] = $code;
    $map['is_used'] = 0;
    $uid = D('FindPassword')->where($map)->getField('uid');
    if(!$uid){
      $this->error('重置密码链接已失效，请重新找回', U('User/forgetpwd'));
    }
    $user_api = new UserApi;
    $user = $user_api->info($uid);
    if(!$user){
      $this->redirect('User/login');
    }
    return $user;
  }

  /**
   * 判断用户名是邮箱还是手机
   * @return int 1-邮箱，2-手机
   */
  private function _checkUserType($username){
    return checkUserType($username);
  }

  /**
   * 获取验证码 （云片）
   */
  public function getRandCode($type = ''){
      $verify = I('post.verify');
    if(!$verify){
        $this->ajaxReturn(['status'=>0,'info' => '请填写图形验证码']);
    }
    if(!check_verify($verify)){
      $this->ajaxReturn(['status'=>2,'info' => '图形验证码错误']);
    }
    $res_data = A('User', 'Event')->getRandCode($type);
    $this->ajaxReturn($res_data);
  }

  /**
   * 保存第三方登录后的返回信息到member表
   * @param integer $data 保存的信息
   * @return boolean 保存结果
   * @author Max.Yu <max@jipu.com>
   */
  private function saveThirdInfo($data){
    if(empty($data)){
      return false;
    }
    return D('Member')->update($data);
  }

  /**
   * 检测手机号
   */
  public function checkMobile($mobile = '', $verify = ''){
    if($verify != '' && check_verify($verify, '', false) == false){
      $res = array('status' => -2, 'info' => '图片验证码错误');
    }else{
      $res = D('Member')->checkMobile($mobile);
    }
    $this->ajaxReturn($res);
  }

  /**
   * 将头像保存到本地
   * @param $url
   * @param $openid
   * @param $uid
   * autor:xjw129xjt
   */
  private function saveAvatar($url, $openid, $uid, $nickname, $type){
    mkdir('./Uploads/Avatar/'.$type.'Avatar', 0777, true);
    $img = file_get_contents($url);
    $filename = './Uploads/Avatar/'.$type.'Avatar/'.$openid.'.jpg';
    file_put_contents($filename, $img);
    $data['uid'] = $uid;
    $data['nickname'] = $nickname;
    $data['avatar'] = $type.'Avatar/'.$openid.'.jpg';
    M('member')->save($data);
  }

  /**
   * 获取用户注册错误信息
   * @param integer $code 错误编码
   * @return string 错误信息
   * @author Max.Yu <max@jipu.com>
   */
  private function showRegError($code = 0){
    switch($code){
      case -1: $error = '密码不能为空！';
        break;
      case -2: $error = '确认密码不能为空！';
        break;
      case -3: $error = '密码长度必须在6-30个字符之间！';
        break;
      case -4: $error = '密码与确认密码不一致';
        break;
        case -5 : $error = '请填写图形验证码';
        break;
      default: $error = '未知错误';
    }
    return $error;
  }

}
