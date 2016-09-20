<?php
/**
 * 用户API
 * @version 2014091512
 * @author Max.Yu <max@jipu.com>
 */

namespace Common\Api;

use Common\Api\Api;
use Common\Model\UserModel;

class UserApi extends Api{
  
  /**
   * 构造方法，实例化操作模型
   */
  protected function _init(){
    $this->model = new UserModel();
  }

  /**
   * 注册一个新用户
   * @param string $username 用户邮箱/手机号
   * @param string $password 用户密码
   * @param string $type 账号类型 （1-邮箱，2-手机）
   * @return integer 注册成功-用户信息，注册失败-错误编号
   */
  public function register($group_id, $username, $password, $type = 2){
    return $this->model->register($group_id, $username, $password, $type);
  }

  /**
   * 用户登录认证
   * @param string $username 用户名
   * @param string $password 用户密码
   * @param integer $type 账号类型（1-邮箱，2-手机）
   * @return integer 登录成功-用户ID，登录失败-错误编号
   */
  public function login($username, $password, $type = 1){
    return $this->model->login($username, $password, $type);
  }

  /**
   * 获取用户信息
   * @param string $uid 用户ID或用户名
   * @param boolean $is_username 是否使用邮箱查询
   * @return array 用户信息
   */
  public function info($uid, $is_email = false){
    return $this->model->info($uid, $is_email);
  }
  
  /**
   * 通过手机号码获取用户信息
   * @param string  $mobile 手机号码
   * @return array 用户信息
   */
  public function infoByMobile($mobile){
    return $this->model->infoByMobile($mobile);
  }
  /**
   * 检测用户名
   * @param string $username 用户名
   * @return integer 错误编号
   */
  public function checkUsername($username, $type){
    return $this->model->checkUsername($username, $type);
  }

  /**
   * 检测邮箱
   * @param string $username 用户名
   * @return integer 错误编号
   */
  public function checkEmail($username){
    return $this->model->checkUsername($username, 1);
  }

  /**
   * 检测手机
   * @param string $mobile 手机
   * @return integer 错误编号
   */
  public function checkMobile($mobile){
    return $this->model->checkUsername($mobile, 2);
  }

  /**
   * 更新用户信息
   * @param int $uid 用户id
   * @param string $password 密码，用来验证
   * @param array $data 修改的字段数组
   * @return true 修改成功，false 修改失败
   * @author huajie <banhuajie@163.com>
   */
  public function updateInfo($uid, $password, $data){
    if($this->model->updateUserFields($uid, $password, $data) !== false){
      $return['status'] = true;
    }else{
      $return['status'] = false;
      $return['info'] = $this->model->getError();
    }
    return $return;
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
    if($this->model->update($uid, $field, $value) !== false){
      $return['status'] = true;
    }else{
      $return['status'] = false;
      $return['info'] = $this->model->getError();
    }
    return $return;
  }
  
}
