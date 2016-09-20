<?php
/**
 * 微信自动创建账号
 * ezhu <ezhu@jipukeji.com>
 */
namespace Home\Controller;
use Think\Controller;
use Common\Api\UserApi;

class AutoLoginController extends Controller{
    
    
    /**
     * 自动创建账号
     * @param unknown $userinfo
     */
    public function register($userinfo){
        $username = get_short($userinfo['nickname'],15);
        $password = get_short($userinfo['openid'],8);
        $type = 2;   //1手机登录，2邮箱登录
        $user_api = new UserApi;
        $uid = $user_api->register(0, $username, $password, $type);
        if($uid){
            //注册成功后自动登录
            $uid = $user_api->login($username, $password, $type);
            //注册来源-第三方帐号绑定
            $oauth['uid'] = $uid;
            $oauth['type'] = 'wechat';
            $oauth['type_uid'] = $userinfo['openid'];
            $oauth['oauth_token'] = $userinfo['oauth_token'];
            $oauth['oauth_token_secret'] = $userinfo['openid'];
            M('login')->add($oauth);
            //绑定后检测红包信息
            A('RedPackage', 'Event')->isRegDoit($oauth['type_uid']);
            //登录用户
            $member_model = D('Member');
            if($member_model->login($uid)){ //登录用户
                //登录后更新用户头像和昵称 TODO:
                $member_data = array(
                        'uid' => $uid,
                        'nickname' => $username,
                        'avatar' => $userinfo['avatar'],
                        'sex' => $userinfo['sex']
                );
                $this->saveThirdInfo($member_data);
                action_log('user_reg', 'user', $uid, $uid, 'user_reg_callback');
                //TODO:跳转到首页
                $this->success('注册成功！', '/');
                exit;
            }else{
                $this->error($member_model->getError());
            }
        }else{ //注册失败，显示错误信息
            $this->error($this->showRegError($uid));
        }
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