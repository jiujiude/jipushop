<?php
/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

namespace Admin\Model;

use Think\Model;

class MemberModel extends Model {

  protected $_validate = array(
    array('nickname', '1,16', '昵称长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),
    array('nickname', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用
  );

  public function lists($map = 1, $order = 'uid DESC', $field = true){
    return $this->field($field)->where($map)->order($order)->select();
  }

  /**
   * 登录指定用户
   * @param  integer $uid 用户ID
   * @return boolean      ture-登录成功，false-登录失败
   */
  public function login($uid){
    /* 检测是否在当前应用注册 */
    $user = $this->field(true)->find($uid);
    if(!$user || 1 != $user['status']){
      $this->error = '用户不存在或已被禁用！'; //应用级别禁用
      return false;
    }

    //记录行为
    action_log('user_login', 'member', $uid, $uid);

    /* 登录用户 */
    $this->autoLogin($user);
    return true;
  }

  /**
   * 注销当前用户
   * @return void
   */
  public function logout(){
    session('user_auth', null);
    session('user_auth_sign', null);
  }

  /**
   * 自动登录用户
   * @param  integer $user 用户信息数组
   */
  private function autoLogin($user){
    /* 更新登录信息 */
    $data = array(
      'uid'             => $user['uid'],
      'login'           => array('exp', '`login`+1'),
      'last_login_time' => NOW_TIME,
      'last_login_ip'   => get_client_ip(1),
    );
    //条件
    $map['uid'] = $user['uid'];
    $this->where($map)->save($data);

    /* 记录登录SESSION和COOKIES */
    $nickname = $user['nickname'];
    $user_line = M('User')->field(true)->find($user['uid']);
    if(empty($nickname)){
        $nickname = !empty($user_line['username'])?$user_line['username']:(empty($user_line['email'])?$user_line['mobile']:$user_line['email']);
    }
    $auth = array(
      'uid'             => $user['uid'],
      'username'        => $nickname,
      'email'   =>  $user_line['email'],
      'mobile'  =>  $user_line['mobile'],
      'last_login_time' => $user['last_login_time'],
    );

    session('user_auth', $auth);
    session('user_auth_sign', data_auth_sign($auth));

  }

  public function getNickName($uid){
    return $this->where(array('uid'=>(int)$uid))->getField('nickname');
  }

}
