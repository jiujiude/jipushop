<?php
/**
 * 红包事件模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Event;

class RedPackageEvent extends \Think\Controller{

  /**
   * session记录抢红包的openid
   */
  public function getOpenId(){
    $openid = session('red_package_openid');
    if(!$openid){
      $openid = A('Pay', 'Event')->getOpenId();
      session('red_package_openid', $openid);
    }
    return $openid;
  }

  /**
   * 根据OpenId获取注册用户ID
   * @param string $open_id 微信用户OpenId
   * @return integer 用户ID （0为未用户不存在）
   * @author Max.Yu <max@jipu.com>
   */
  public function getUidByOpenId($open_id = ''){
    $uid = M('Login')->where(array('type_uid' => $open_id, 'type' => 'wechat'))->getField('uid') ? : 0;
    return $uid;
  }

  /**
   * 拆红包
   * @param array $data 红包数据
   * @param array $userinfo 用户数据
   * @author Max.Yu <max@jipu.com>
   */
  public function open($data = array(), $userinfo = ''){
    $return_data = array('status' => 0);
    $line = $this->getRecord($data['id'], $userinfo['openid']);
    if($line){
      return array('status' => 1, 'amount' => $line['amount']);
    }
    //剩余红包数
    $num = $data['number'] - $data['send_number'];
    //剩余金额
    $amount = $data['amount'] - $data['send_amount'];
    
    //状态过滤
    if((strtotime($data['expire_time']) > NOW_TIME) && $data['status'] == 1 && $amount > 0 && $num > 0){
      if($num == 1){
        $return_data = array(
          'status' => 1,
          'amount' => $amount
        );
      }elseif(round($amount - $num * 0.01, 2) == 0){
        $return_data = array(
          'status' => 1,
          'amount' => 0.01
        );
      }else{
        $amount_max = $amount * 100 - $num - 1;
        $rand_amount = mt_rand(1, min($amount_max, floor($amount * 100 / $num * 2)));
        if($rand_amount > 0){
          $return_data = array(
            'status' => 1,
            'amount' => round($rand_amount / 100, 2)
          );
        }
      }
      //保存红包拆开数据
      $return_data['amount'] > 0 && $this->saveRecord($data['id'], $userinfo, $return_data['amount']);
    }
    return $return_data;
  }

  /**
   * 保存红包数据
   * @param integer $red_package_id 红包ID
   * @param array $userinfo 用户信息
   * @param double $amount 红包金额
   * @author Max.Yu <max@jipu.com>
   */
  public function saveRecord($red_package_id = 0, $userinfo = array(), $amount = 0.00){
    $line = $this->getRecord($red_package_id, $userinfo['openid']);
    if(empty($line)){
      $data = array(
        'red_package_id' => $red_package_id,
        'uid' => $this->getUidByOpenId($userinfo['openid']),
        'open_id' => $userinfo['openid'],
        'amount' => $amount,
        'nickname' => $userinfo['nickname'],
        'sex' => $userinfo['sex'],
        'avatar' => $userinfo['headimgurl'],
        'country' => $userinfo['country'],
        'province' => $userinfo['province'],
        'city' => $userinfo['city'],
        'subscribe_time' => $userinfo['subscribe_time'],
        'create_time' => NOW_TIME
      );
      $res = M('RedPackageRecord')->add($data);
      $data['uid'] > 0 && $this->addUserFinance($data['uid'], $amount, $res);
      M('RedPackage')->where(array('id' => $red_package_id))->setInc('send_number');
      M('RedPackage')->where(array('id' => $red_package_id))->setInc('send_amount', $amount);
    }
  }

  /**
   * 获取红包领取记录
   * @param integer $red_package_id 红包ID
   * @param string $open_id 用户微信OpenID
   * @author Max.Yu <max@jipu.com>
   */
  public function getRecord($red_package_id = 0, $open_id = ''){
    $map = array(
      'red_package_id' => $red_package_id,
      'open_id' => $open_id
    );
    $line = M('RedPackageRecord')->where($map)->find();
    return $line? : array();
  }

  /**
   * 增加用户现金余额
   * @param integer $uid 用户UID
   * @param double $amount 增加金额
   * @param integer $record_id 对应的订单ID
   * @author Max.Yu <max@jipu.com>
   */
  public function addUserFinance($uid = 0, $amount = 0.00, $record_id = 0){
    $line = M('Finance')->where(array('uid' => $uid, 'order_id' => $record_id))->find();
    if(empty($line)){
      $data = array(
        'uid' => $uid,
        'type' => 'redpackage',
        'order_id' => $record_id,
        'amount' => $amount,
        'flow' => 'in',
        'memo' => '现金红包转账',
        'create_time' => NOW_TIME
      );
      $res = M('Finance')->add($data);
      if($res){
        M('Member')->where(array('uid' => $uid))->setInc('finance', $amount);
      }
    }
  }

  /**
   * 注册/绑定后操作-根据OPENID派红包到现金账户
   * @param string $open_id 用户微信OpenID
   * @author Max.Yu <max@jipu.com>
   */
  public function isRegDoit($open_id = ''){
    if(is_numeric($open_id) && $open_id * 1 == $open_id){
      $open_id = M('Login')->where(array('uid' => $open_id, 'type' => 'wechat'))->getField('type_uid') ? : '';
    }
    if(empty($open_id)){
      return false;
    }
    $map = array(
      'open_id' => $open_id,
      'uid' => 0
    );
    $model = M('RedPackageRecord');
    $list = $model->where($map)->select();
    $uid = $list ? $this->getUidByOpenId($open_id) : 0;
    if($list && $uid > 0){
      foreach($list as $li){
        $this->addUserFinance($uid, $li['amount'], $li['id']);
        $model->where(array('id' => $li['id']))->setField('uid', $uid);
      }
      $this->assign('member', D('Member')->info($uid));
    }
  }

}
