<?php
/**
 * 前台礼品卡控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class CardController extends HomeController {

  private $Card;

  public function _initialize(){
    parent::_initialize();
    //用户登录验证
    parent::login();
    $this->Card = D('Card');
  }

  /**
   * 礼品卡绑定
   * @author Max.Yu <max@jipu.com>
   */
  public function bindCard($number, $password){
    //卡号密码输入校验
    $map['number'] = $number;
    $map['password'] = $password;
    if(empty($map['number'])){
      $this->error('请输入卡号！');
    }
    if(empty($map['password'])){
      $this->error('请输入密码！');
    }
    //获取礼品卡数据，验证礼品卡有效性，绑定礼品卡
    $card = $this->Card->detail($map);
    if($card){
      //判断是否已被绑定
      if($card['is_bind']==1){
        $this->error('该卡已经被绑定。');
      }else{
        //判断是否过期
        if($card['expire_time'] <= NOW_TIME - 86400 ){
          $this->error('该卡已过期。');
        }else{
          //绑定礼品卡
          $data['uid'] = UID;
          $data['card_id'] = $card['id'];
          $bind = D('CardUser')->update($data);
          if($bind){
            $bind_time = NOW_TIME;
            //更新礼品卡绑定状态
            $update_data = array(
              'id' => $card['id'],
              'is_bind' => 1,
              'bind_time' => $bind_time,
            );
            $card_update = D('Card')->update($update_data);
            if($card_update){
              $result = array(
                'status' => 1,
                'info' => '绑定成功！',
                'data' => array(array(
                  'id' => $card['id'],
                  'number' => $card['number'],
                  'name' => $card['name'],
                  'amount' => $card['amount'],
                  'balance' => $card['balance'],
                  'expire_time' => time_format($card['expire_time'],'Y-m-d'),
                )),
              );
              $this->ajaxReturn($result);
            }else{
              $this->error('绑定失败。');
            }
          }else{
            $this->error('绑定失败。');
          }
        }
      }
    }else{
      $this->error('您输入的卡号或密码有误。');
    }
  }

  /**
   * 验证用户选择的礼品卡
   * @author Max.Yu <max@jipu.com>
   */
  public function checkSelectedCard() {
    $number = I('post.number');
    if(empty($number)){
      $result = array(
        'status' => 0,
        'msg' => '请选择礼品卡。',
      );
    }

    //获取礼品卡信息
    $where = array(
      'number' => array('IN', $number),
      'expire_time' => array('GT', (NOW_TIME - 86400)),
    );
    $cards = $this->Card->lists($where);

    if($cards){

      $card_ids = array();
      foreach($cards as $key => $value){
        $card_ids[] = $value['id'];
      }

      $map = array(
        'uid' => UID,
      	'card_id' => array('IN', $card_ids),
      );
      $card_user = M('CardUser')->where($map)->select();

      $card_user_new = array();
      foreach($card_user as $key => $value){
        $card_user_new[$value['card_id']] = $value;
      }

      //验证客户端传递过来的礼品卡卡号是否属于当前用户
      foreach($cards as $key => $value){
        if(empty($card_user_new[$value['id']])){
          unset($cards[$key]);
        }
      }
    }

    if($cards){
      $result = array(
        'status' => 1,
        'data' => $cards,
        'msg' => '礼品卡验证成功。',
      );
    }else{
      $result = array(
        'status' => -1,
        'msg' => '抱歉，您选择的礼品卡无效。',
      );
    }
    $this->ajaxReturn($result);
  }

}