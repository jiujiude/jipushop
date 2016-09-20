<?php
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

namespace Admin\Controller;

use Common\Api\UserApi;

class PublicController extends \Think\Controller {

  /**
   * 后台用户登录
   * @author 麦当苗儿 <zuojiazi@vip.qq.com>
   */
  public function login($username = null, $password = null, $verify = null){
    $login_times = session('admin_login_times') ? : 0;
    if(IS_POST){
      if($login_times >= 5){
          //TODO:验证用户
          $verifyModel = new \Think\Verify();
          $verifyModel->check($verify) || $this->error('验证码错误', U('Public/login'));
      }
      if(empty($username) || empty($password)){
          $this->error('账号密码不能为空', U('Public/login'));
      }
      /* 调用UC登录接口登录 */
      $login_type = $this->_checkUserType($username);
      $User = new UserApi;
      $uid = $User->login($username, $password, $login_type);

      if(0 < $uid){ //UC登录成功
        /* 登录用户 */
        $Member = D('Member');
        if($Member->login($uid)){ //登录用户
          //用户访问权限验证
          static $Auth = null;
          if (!$Auth){
              $Auth = new \Think\Auth();
          }
          $rule  = strtolower(MODULE_NAME.'/Index/index');
          if(!$Auth->check($rule,$uid)){
              $Member->logout();  //退出登录
              $login_times ++ ;
              session('admin_login_times',$login_times);
              $this->error('抱歉，无权访问', U('Public/login'));
          }else{
              $this->success('登录成功！', Cookie('__forward__') ?: U('Index/index'));
          }
        }else{
          $this->error($Member->getError());
        }
      }else{ //登录失败
        switch($uid) {
          case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
          case -2: $error = '密码错误！'; break;
          default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
        }
        $login_times ++ ;
        session('admin_login_times',$login_times);
        $this->error($error);
      }
    }else{
      if(is_login()){
        $this->redirect('Index/index');
      }else{
        /* 读取数据库中的配置 */
        $config = S('DB_CONFIG_DATA');
        if(!$config){
          $config = D('Config')->lists();
          S('DB_CONFIG_DATA',$config);
        }
        C($config); //添加配置
        $this->assign('login_times',$login_times);
        $this->display();
      }
    }
  }

  /* 退出登录 */
  public function logout(){
    if(is_login()){
      D('Member')->logout();
      session('[destroy]');
      $this->success('退出成功！', U('login'));
    }else{
      $this->redirect('login');
    }
  }

  /**
   * 获取验证码
   */
  public function verify(){
      $verify = new \Think\Verify();
      $verify->entry();
  }

  /**
   * 判断用户名是邮箱还是手机
   * @return int 1-邮箱，2-手机
   */
  private function _checkUserType($username){
    if(!$username){
      return false;
    }
    return strstr($username, '@') ? 1 : 2;
  }

}
