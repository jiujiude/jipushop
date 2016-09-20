<?php
/**
 * 用户模型
 */

namespace Common\Model;

use Think\Model;

class UserModel extends Model{

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('password', 'require', -1, self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    array('repassword', 'require', -2, self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    array('password', '6,30', -3, self::EXISTS_VALIDATE, 'length'),
    array('repassword', 'password', -4, self::EXISTS_VALIDATE, 'confirm'),
    //array('mobile', '', -11, 0, 'unique', 1), //在新增的时候验证mobile字段是否唯一 TODO:用户被删除了还能注册
  );

  /**
   * 用户模型自动完成
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function'),
    array('reg_time', NOW_TIME, self::MODEL_INSERT),
    array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
    array('update_time', NOW_TIME),
    array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
  );

  /**
   * 检测手机是不是被禁止注册
   * @param  string $mobile 手机
   * @return boolean ture - 未禁用，false - 禁止注册
   */
  protected function checkMobile($mobile){
    
  }

  /**
   * 根据配置指定用户状态
   * @return integer 用户状态
   */
  protected function getStatus(){
    return true; //TODO: 暂不限制，下一个版本完善
  }

  /**
   * 注册一个新用户
   * @param string $username 用户名
   * @param string $password 用户密码
   * @param string $email 用户邮箱
   * @param string $mobile 用户手机号码
   * @return integer 注册成功-用户信息，注册失败-错误编号
   */
  public function register($group_id, $username, $password, $type){
    $data['group_id'] = $group_id;
    ($type == 1) ? $data['email'] = $username : $data['mobile'] = $username;
    $data['password'] = $password;
    //邮箱注册，截取邮箱@前作为用户名
    $data['username'] = ($type == 1) ? $this->getUserName($username) : $username;
    //判断是否扫描带参数的微信二维码加入
    $openid = A('Home/Pay', 'Event')->getOpenId();
    if($openid){
      //获取所扫描的二维码ID
      $where['open_id'] = $openid;
      $wechat_qrcode_log_lists = M('WechatQrcodeLog')->where($where)->select();
      //取ID最小的（最先扫过的）
      // $wechat_qrcode_log_lists[0] && $data['from_union_id'] = $wechat_qrcode_log_lists[0]['union_id'];

    }
    //添加用户
    if($this->create($data)){
      $uid = $this->add();

      if(!empty($wechat_qrcode_log_lists[0]['union_id'])  && C('DIS_START') == 1){
        $recomender = M('Distribution')->alias('a')->join('__UNION__ b on b.uid=a.user_id')->where('b.id='.$wechat_qrcode_log_lists[0]['union_id'])->field('a.*')->find();
        set_log('psd' ,M('Distribution')->getLastSql());
        if($recomender){
          $userdata = array(
            'user_id'     => $uid ,
            'oneagents'   => C('DIS_CLASS') > 0 ? $recomender['user_id']   : 0,
            'twoagents'   => C('DIS_CLASS') > 1 ? $recomender['oneagents'] : 0,
            'threeagents' => C('DIS_CLASS') > 2 ? $recomender['twoagents'] : 0,
          );
          M('Distribution')->add($userdata);
          $this->toweixin($userdata);
        }
          
      }
      $this->regInvite($uid); //邀请奖励
      return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
    }else{
      return $this->getError(); //错误详情见自动验证注释
    }
  }

/**
   * 微信消息通知
   * @param 
   */
  public function toweixin($data){
    $name = $info['nickname'] = get_nickname($data['user_id']) ;
    unset($data['user_id']);
    $i = 1 ;
    foreach($data as $k=>$v){
      if($v){
        $myname = get_nickname($v);
        $info['title']  = '您好，【'.$myname.'】。'.$name.'通过扫描您的专属二维码成为您的'.$i.'级代理成员';
        $info['uid']    = $v;
        A('WechatTplMsg', 'Event')->wechatTplNotice('applyer', $info);
      }
      ++$i;
    }
  }

  /**
   * 邀请注册，奖励
   * @param int $uid 新用户ID
   */
  public function regInvite($uid){
    $invite_user = session('invite_user');
    
    if(empty($invite_user) || empty($uid)){
      return false;
    }
    $invite_uid = invite_code($invite_user['invite_code']);
    $money = C('INVITE_REWARD_MONEY');
    //合法性的邀请session
    if($invite_uid == $invite_user['invite_uid'] && $money > 0){
      //邀请返现限制人数
      $where = array(
        'invite_uid' => $invite_uid, 
        'invite_code' => $invite_user['invite_code'],
        'create_time' => array('gt', strtotime(date('Y-m-d')))
      );
      $reged = M('Invite')->where($where)->count();
      if($reged >= intval(C('INVITE_MAX_PEOPLE'))){
        return false;
      }
      //销毁邀请sessin
      session('invite_user', null);
      $invite_data = array(
        'invite_uid' => $invite_uid,
        'invite_code' => $invite_user['invite_code'],
        'reg_uid' => $uid,
        'reward_status' => 0,
        'reward_amount' => $money,
        'create_time' => time()
      );
      $invite_id = M('Invite')->add($invite_data);
      if($invite_id){
        //现金流水表-邀请注册奖励
        $in_data = array(
          'uid' => $invite_uid,
          'order_id' => $invite_id,
          'type' => 'invite_reward',
          'amount' => $money,
          'flow' => 'in',
          'memo' => '邀请注册奖励',
          'create_time' => time()
        );
        $finance_id = M('finance')->add($in_data);
        if($finance_id){
          $res = D('Home/Member')->updateFinance($invite_uid, $money, 'inc');
          if($res){
            return M('Invite')->where('id='.$invite_id)->setField('reward_status', 1);
          }
        }
      }
    }
    return false;
  }

  /**
   * 检测用户邮箱或手机是否可以注册
   * @param string $username 邮箱或手机
   * @param string $type 邮箱或手机类型
   * @return boolean true - 可注册，false - 已注册过
   */
  public function checkUsername($username, $type){
    $map = array();
    switch($type){
      case 1:
        $map['email'] = $username;
        break;
      case 2:
        $map['mobile'] = $username;
        break;
      default:
        return false;
    }
    //获取用户数据
    $user = $this->where($map)->count();
    return $user > 0 ? true : false;
  }

  /**
   * 用户登录认证
   * @param string $username 用户名
   * @param string $password 用户密码
   * @param integer $type 用户名类型 （1-邮箱，2-手机）
   * @return integer 登录成功-用户ID，登录失败-错误编号
   */
  public function login($username, $password, $type = 1){
    $map = array();
    $map['status'] = array('egt',0);
    switch($type){
      case 1:
        $map['email'] = $username;
        break;
      case 2:
        $map['mobile|username'] = $username;
        break;
      case 3:
        $map['username'] = $username;
        break;
      case 4:
        $map['id'] = $username;
        break;
      default:
        return 0; //参数错误
    }
    /* 获取用户数据 */
    $user = $this->where($map)->find();
    if(is_array($user) && $user['status']){
      /* 验证用户密码 */
      if(think_ucenter_md5($password) === $user['password']){
        $this->updateLogin($user['id']); //更新用户登录信息
        return $user['id']; //登录成功，返回用户ID
      }else{
        return -2; //密码错误
      }
    }else{
      return -1; //用户不存在或被禁用
    }
  }

  /**
   * 获取用户信息
   * @param string  $uid 用户ID或用户名
   * @param boolean $is_username 是否使用用户名查询
   * @return array 用户信息
   */
  public function info($uid, $is_email = false){
    $map = array();
    if($is_email){ //通过邮箱获取
      $map['email'] = $uid;
    }else{
      $map['id'] = $uid;
    }
    $user = $this->where($map)->field('id, group_id, from_union_id, username, password, email, mobile, mobile_bind_status,last_login_time, last_login_ip, status')->find();
    if(is_array($user) && $user['status'] == 1){
      $user['avatar'] = ($user['avatar']) ? $user['avatar'] : __ROOT__.'/Public/'.MODULE_NAME.'/'.C('DEFAULT_THEME').'/images/avatar-default.png';
      return $user;
    }else{
      return -1; //用户不存在或被禁用
    }
  }

  /**
   * 获取用户信息
   * @param string  $uid 用户ID或用户名
   * @param boolean $is_username 是否使用用户名查询
   * @return array 用户信息
   */
  public function infoByMobile($mobile = ''){
    $map = array();
    $map['mobile'] = $mobile;
    $user = $this->where($map)->field('id,username,password,email,mobile,status')->find();
    if(is_array($user) && $user['status'] == 1){
      $user['avatar'] = ($user['avatar']) ? $user['avatar'] : __ROOT__.'/Public/'.MODULE_NAME.'/'.C('DEFAULT_THEME').'/images/avatar-default.png';
      return $user;
    }else{
      return -1; //用户不存在或被禁用
    }
  }

  /**
   * 检测用户信息
   * @param string $field  用户名
   * @param integer $type 用户名类型 1-用户邮箱，2-手机注册，3-用户名注册
   * @return integer 错误编号
   */
  public function checkField($field, $type = 1){
    $data = array();
    switch($type){
      case 1:
        $data['email'] = $field;
        break;
      case 2:
        $data['mobile'] = $field;
        break;
      case 3:
        $data['username'] = $field;
        break;
      default:
        return 0; //参数错误
    }
    return $this->create($data) ? 1 : $this->getError();
  }

  /**
   * 更新用户登录信息
   * @param integer $uid 用户ID
   */
  protected function updateLogin($uid){
    $data = array(
      'id' => $uid,
      'last_login_time' => NOW_TIME,
      'last_login_ip' => get_client_ip(1),
    );
    $this->save($data);
  }

  /**
   * 更新用户信息
   * @param int $uid 用户id
   * @param string $password 密码，用来验证
   * @param array $data 修改的字段数组
   * @return true 修改成功，false 修改失败
   * @author huajie <banhuajie@163.com>
   */
  public function updateUserFields($uid, $password, $data){
    if(empty($uid) || empty($password) || empty($data)){
      $this->error = '参数错误！';
      return false;
    }

    //更新前检查用户密码
    if(!$this->verifyUser($uid, $password)){
      $this->error = '验证出错：密码不正确！';
      return false;
    }

    //更新用户信息
    $data = $this->create($data);
    if($data){
      return $this->where(array('id' => $uid))->save($data);
    }
    return false;
  }
  
  /**
   * 更新数据
   * @param int $uid 用户id
   * @param string $field 字段名
   * @param string $value 字段值
   * @return true 修改成功，false 修改失败
   * @version 2015071715
   * @author Justin
   */
  function update($uid, $field, $value){
    if(empty($uid) || empty($field)){
      $this->error = '参数错误！';
      return false;
    }
    //更新用户信息
    $data = $this->create();
    if($data){
      $data[$field] = $value;
      return $this->where(array('id' => $uid))->save($data);
    }
    return false;
  }

  /**
   * 根据邮箱截取@前作为用户名
   * @return integer 用户状态
   */
  protected function getUserName($username){
    return preg_replace('/@.*/', '', $username);
  }

  /**
   * 验证用户密码
   * @param int $uid 用户id
   * @param string $password_in 密码
   * @return true 验证成功，false 验证失败
   * @author huajie <banhuajie@163.com>
   */
  protected function verifyUser($uid, $password_in){
    $password = $this->getFieldById($uid, 'password');
    if(think_ucenter_md5($password_in) === $password){
      return true;
    }
    return false;
  }

}
