<?php
/**
 * 会员个人中心控制器
 * @version 2014091618
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

use Common\Api\UserApi;
use Org\ThinkSDK\ThinkSms;

class MemberController extends HomeController {

  protected function _initialize(){
    //记录当前页URL地址Cookie，点击我的登录完成后跳转至个人中心
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    parent::_initialize();
    //判断是否登录
    parent::login();
    $this->assign('user', $this->user);
    $this->assign('member', $this->member);
  }

  /**
   * 个人中心
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //获取订单和购物车商品
    $map['uid'] = UID;
    $field = 'id, uid, order_sn, o_status, item_ids, total_amount, create_time';
    $data['cart'] = D('Cart')->lists($map);
    $orders = D('Order')->listsItem(array_merge($map, array('status' => 1)), $field, 'create_time DESC', 5);
    $data['orders'] = $orders;
    //是否为代理联盟
   $this->union_status=M('Union')->where('uid='.UID)->getfield('status');
    //获取收藏商品列表
    $fav_list = D('Fav')->listUserFav(UID, 'item');
    $items_id = arr2str($fav_list);
    if($items_id){
      $item_map['id'] = array('IN', $items_id);
      $data['fav'] = D('Item')->lists($item_map, 'id, name, thumb', '', 6);
    }
    $map['status'] = 1;
    $share = array(
      'title' => C('MEMBER_INDEX_SHARE_TITLE'),
      'desc' => C('MEMBER_INDEX_SHARE_DESC'),
      'img_url' => SITE_URL.__IMG__.'/logo-avatar.png',
      'link' => SITE_URL.U('Member/index')
    );
    //更新红包应转入的金额
    A('RedPackage', 'Event')->isRegDoit(UID);
    
    $this->meta_share = $share;
    $this->user_account_count = M('UserAccount')->where($map)->count();
    $this->assign('order_num', A('Order','Event')->getOrderNum(UID));
    $this->assign('data', $data);
    $this->meta_title = '个人中心';
    $this->display();
  }

  /**
   * 用户详细信息
   * @author Max.Yu <max@jipu.com>
   */
  public function detailAjax(){
    $member = $this->member;
    $result = array(
      'uid' => $member['uid'],
      'finance' => $member['finance']
    );
    $this->ajaxReturn($result);
  }

  /**
   * 获取用户账户预存款余额（AJAX）
   * @return json
   * @author Max.Yu <max@jipu.com>
   */
  public function getFinanceByAjax(){
    $member = $this->member;
    $data = array(
      'uid' => $member['uid'],
      'finance' => $member['finance'],
    );
    $this->ajaxReturn($data);
  }
  
  /**
   * 获取用户账户积分
   * @return json
   * @author Max.Yu <max@jipu.com>
   */
  public function getScoreByAjax(){
    $member = $this->member;
    $data = array(
      'uid' => $member['uid'],
      'score' => $member['score'],
      'score_exchange' => C('SCORE_EXCHANGE_NUMBER'),
      'score_amount' => $member['score_amount']
    );
    $this->ajaxReturn($data);
  }
/**
   * 用户优惠卷激活
   * @return json
   * @author Max.Yu <max@jipu.com>
   */
  public function couponactive(){
    $map['coupon_num'] = I('post.number');
    if(empty($map['coupon_num'])){
      $this->error('请输入激活码。');
    }
    //获取优惠券数据，验证优惠券有效性，激活优惠券
    $couponNum = M('CouponNum')->where($map)->find();
    if($couponNum){
      if($couponNum['is_get'] == 0){
        $coupon=M('Coupon')->find($couponNum['cn_coupon_id']);
        if($coupon['expire_time'] <= NOW_TIME - 86400 ){
          $this->error('您输入的激活码已过期。');
        }
        $data['uid'] = UID;
        $data['coupon_id'] = $couponNum['cn_coupon_id'];
        $time = time();
        $activate = D('CouponUser')->update($data);
        $cndata['is_get'] = 1 ;
        $couponNum = M('CouponNum')->where($map)->save($cndata);
        if($activate){
          $result = array(
              'status' => 1,
              'info' => '优惠券激活成功。',
              'data' => array(
                  'id' => $coupon['id'],
                  'number' => $coupon['number'],
                  'name' => $coupon['name'],
                  'amount' => $coupon['amount'],
                  'norm' => $coupon['norm'] > 0  ? '满'.$coupon['norm'].'元使用':'全场不限制',
                  'expire_time' => time_format($coupon['expire_time'],'Y-m-d H:i'),
                  'create_time' => time_format($time,'Y-m-d H:i'),
              ),
          );
          $this->ajaxReturn($result);
        }else{
          $this->error('优惠券激活失败。');
        }
      }else{
          $this->error('您输入的激活码已经被领取。');
      }
    }else{
      $this->error('您输入的激活码不存在。');
    }
  }

  /**
   * 我的订单
   * @author Max.Yu <max@jipu.com>
   */
  public function order(){
    $map['uid'] = UID;
    $map['status'] = 1;
    $type = (I('get.type')) ? I('get.type') : 'all';
    switch($type){
      case 'payment': //待付款
        $map['o_status'] = 0;
        break;
      case 'success': //已完成订单
        $map['o_status'] = 202;
        break;
      case 'unship': //待发货
        $map['o_status'] = 200;
        break;
      case 'unreceive': //待确认收货
        $map['o_status'] = 201;
        break;
      case 'cancel': //已关闭订单
        $map['o_status'] = -1;
        break;
      case 'recycle': //回收站订单
        $map['status'] = 2;
        break;
    }
    $lists = D('Order')->listsItem($map);

    $tab[$type] = 'class="active"';
    $this->assign('tab_active', $tab);
    $this->assign('lists', $lists);
    $order_type = array('payment' => '待付款', 'success'=>'已完成', 'unship'=>'待发货', 'unreceive'=>'待确认收货', 'cancel'=>'已关闭', 'recycle'=>'回收站', 'all' => '全部');
    $this->meta_title = $order_type[$type].'订单';
    IS_AJAX ? $this->display('orderList') : $this->display();
  }

  /**
   * 我的收藏
   * @author Max.Yu <max@jipu.com>
   */
  public function fav(){
    //获取当前用户收藏商品列表
    $lists = array();
    $fav_list = D('Fav')->listUserFav(UID, 'item');
    if($fav_list){
      $items_id = arr2str($fav_list);
      $map['id'] = array('IN', $items_id);
      $lists = D('Item')->lists($map);
    }
    
    $this->assign('lists', $lists);
    $this->meta_title = '我的收藏';
    $this->display();
  }

  /**
   * 我的现金账户
   * @author Max.Yu <max@jipu.com>
   */
  public function finance($type = '', $flow = ''){
    $map['uid'] = UID;
    $type == 'union' && $type = array('in', array('union_order', 'union_subscribe'));
    if($type){
      $map['type'] = $type;
    }
    if($flow){
      $map['flow'] = $flow;
    }
    //可提现余额
    $this->withdraw_finance = A('Finance', 'Event')->getWithDrawFinance();
    $lists = $this->lists('Finance', $map);
    $this->assign('lists', $lists);
    $this->assign('type', $type);
    $this->meta_title = '现金收支明细';
    IS_AJAX ? $this->display('financeList') : $this->display();
  }

  /**
   * 现金账户消费记录
   * @author Max.Yu <max@jipu.com>
   */
  public function financelog(){
    $map['uid'] = UID;
    $map['type'] = 'finance';
    $lists = D('PaymentLog')->lists($map);
    $this->assign('lists', $lists);
    $this->display();
  }

  /**
   * 现金账户充值
   * @author Max.Yu <max@jipu.com>
   */
  public function recharge(){
    $map['uid'] = UID;
    $this->assign('lists', $lists);
    $this->meta_title = '账户充值';
    $this->display();
  }

  /**
   * 我的积分
   * @author Max.Yu <max@jipu.com>
   */
  public function score(){
    $type = I('get.type');
    $map['uid'] = UID;
    if($type){
      $map['type'] = $type;
    }
    $lists = M('ScoreLog')->where($map)->order('id DESC')->select();
    $lists = $this->lists('ScoreLog', $map);
    $this->assign('lists', $lists);
    $this->assign('type', $type);
    $this->meta_title = '积分明细';
    IS_AJAX ? $this->display('scoreList') : $this->display();
  }

  /**
   * 我的优惠券
   * @author Max.Yu <max@jipu.com>
   */
  public function coupon(){
    //获取当前用户优惠券列表
    $map['cu.uid'] = UID;
    $map['cu.status'] = 0;
    $map['c.status'] = 1;
    $lists = D('CouponUser')->listsPage($map);
    $this->assign('lists', $lists);
    $this->meta_title = '我的优惠券';
    IS_AJAX ? $this->display('couponList') : $this->display();
  }

  /**
   * 我的邀请
   * @author Max.Yu <max@jipu.com>
   */
  public function invite(){
    $invite_code = invite_code(UID);
    $map['invite_code'] = $invite_code;
    $lists = $this->lists('Invite', $map, 'id DESC');
    $this->assign('lists', $lists);
    $invite_path = SITE_URL.U('User/invite', array('s' => $invite_code));
    $meta_share = array(
      'title' => '速速入伙'.C('WEB_SITE_TITLE').'，注册即获现金100元！',
      'desc' => '如此安心健康又有品位的'.C('WEB_SITE_TITLE').'，就要与懂生活的人一起分享，赶紧加入吧！',
      'img_url' => SITE_URL.'/Public/'.MODULE_NAME.'/'.C(DEFAULT_THEME).'/images/logo-sm.png',
      'link' => $invite_path
    );
    if(C('WEB_INVITE_TITLE'))$meta_share['title']=C('WEB_INVITE_TITLE');
    if(C('WEB_INVITE_DESC'))$meta_share['desc']=C('WEB_INVITE_DESC');
    if(C('WEB_INVITE_LOGO'))$meta_share['img_url']=SITE_URL.get_cover(C('WEB_INVITE_LOGO'), 'path');

    $this->invite_path = $invite_path;
    $this->meta_title = '我的邀请';
    $this->meta_share = $meta_share;
    $this->display();
  }

  /**
   * 我的分销
   * @author Max.Yu <max@jipu.com>
   */
  public function sdp(){
    $shop_secret = SHOP_SECRET;
    if(empty($shop_secret)){
      $this->redirect('Shop/guide');
    }else{
      $sdp_event = A('Sdp', 'Event');
      $shop = M('Shop')->getBySecret(SHOP_SECRET);
      if(empty($shop['name'])){
        $this->redirect('Shop/manage');
      }
      $this->meta_title = '我的分销';
      $this->data = $sdp_event->getSdpDetail();
      $this->lists = $sdp_event->getSdpRecordList();
      $this->display();
    }
    
  }

  /**
   * 我的提现账户
   * @author Max.Yu <max@jipu.com>
   */
  public function withdraw(){
    $this->display();
  }

  /**
   * 礼品卡列表（pc端"个人中心-我的礼品卡"使用myCard方法）
   */
  public function card(){
    //获取当前用户优惠券列表
    $map['uid'] = UID;
    $lists = D('Card')->lists($map);
    $this->assign('lists', $lists);
    $this->meta_title = '我的优惠券';
    IS_AJAX ? $this->display('couponList') : $this->display();
  }
  
  /**
   * 我的礼品卡
   */
  public function myCard(){
      $map['uid'] = UID;
      $lists = D('CardUser')->lists($map);
      $this->assign('lists', $lists);
      $this->meta_title = '我的礼品卡';
      $this->display();
  }

  /**
   * 礼品卡使用日志
   * @author Max.Yu <max@jipu.com>
   */
  public function cardlog($card_id = null){
    if(empty($card_id)){
      $this->error('礼品卡ID不存在！');
    }else{
      $map['card_id'] = $card_id;
    }
    $map['uid'] = UID;
    $cardlog = M('CardLog')->where($map)->order('id DESC')->select();
    $this->assign('cardlog', $cardlog);
    $this->meta_title = '礼品卡使用日志';
    $this->display();
  }

  /**
   * 礼品卡绑定
   * @author Max.Yu <max@jipu.com>
   */
  public function cardBind($number, $password){
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
    $card_model = D('Card');
    $card = $card_model->detail($map);
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
                'data' => array(
                  'number' => $card['number'],
                  'name' => $card['name'],
                  'amount' => $card['amount'],
                  'balance' => $card['balance'],
                  'bind_time' => time_format($bind_time),
                  'expire_time' => time_format($card['expire_time'],'Y-m-d'),
                ),
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
   * 我的收货地址
   * @author Max.Yu <max@jipu.com>
   */
  public function receiver(){

    //微信接口处理
    if(C('WECHAT_USERINFO_BY_API') == true){
      //检测是否返回地址
      $res = A('Order', 'Event')->saveWechatAddress(true);
      if($res){
        $this->success('收货地址列表更新成功！',U('Member/receiver'));exit();
      }
    }else{
      //判断生成获取微信收货地址接口参数
      $toAuth = I('selectAddress', 0) == 1;
      $config = A('Order', 'Event')->getWechatAddressConfig($toAuth);
      if($config){
        $config['tourl'] = U('Member/receiver');
      }
      $this->wechatAddressConfig = json_encode($config);
      $this->code = I('get.code');
    }
    
    $map['uid'] = UID;
    $lists = D('Receiver')->lists($map);
    $this->assign('lists', $lists);
    $this->meta_title = '我的收货地址';
    $this->display();
  }

  /**
   * 绑定手机号码
   * @author Max.Yu <max@jipu.com>
   */
  public function mobileBind(){
    if(IS_POST){
      $mobile = I('post.mobile', '');
      $randcode = I('post.randcode', '');
      $res = D('Member')->setMobile($mobile, $randcode);
      if($res['status'] == 0){
        $this->error($res['info']);
      }elseif($res['status'] == 1){
        $this->success($res['info']);
      }
    }else{
      $this->meta_title = '绑定手机';
      $this->display();
    }
  }
  
  /**
   * 手机号绑定检测
   * @author Max.Yu <max@jipu.com>
   */
  public function checkMobileBind(){
    $mobile = I('post.param');
    $user = M('User')->where(array('mobile' => $mobile))->find();
    if($user && $user['id'] != UID){
      $result = array(
         'status' => 'n',
         'info' => '已被占用'
      );
    }else{
      if($user['id'] == UID && $user['mobile_bind_status'] == 1){
        $result = array(
          'status' => 'y',
          'info' => '已绑定，无需重复操作'
        );
      }else{
        $result = array(
          'status' => 'y',
          'info' => '可以绑定'
        );
      }
    }
    $this->ajaxReturn($result);
  }

  /**
   * 绑定手机号码Ajax方式
   * @author Max.Yu <max@jipu.com>
   */
  public function mobileBindAjax(){
    $this->display();
  }

  /**
   * 修改密码
   * @author Max.Yu <max@jipu.com>
   */
  public function changepwd(){
    if(IS_POST){
      //获取参数
      $uid = is_login();
      $password = I('post.old');
      $repassword = I('post.repassword');
      $data['password'] = I('post.password');
      empty($password) && $this->error('请输入原密码');
      empty($data['password']) && $this->error('请输入新密码');
      empty($repassword) && $this->error('请再次输入密码');

      if($data['password'] !== $repassword){
        $this->error('两次密码输入密码不一致');
      }

      if($data['password'] == $password){
        $this->error('您输入的新密码与原密码一致');
      }

      $user_api = new UserApi();
      $res = $user_api->updateInfo($uid, $password, $data);
      if($res['status']){
        $this->success('密码修改成功！', U('Member/index'));
      }else{
        $this->error($res['info']);
      }
    }else{
      $this->meta_title = '修改密码';
      $this->display();
    }
  }
  
  /**
   * 修改用户昵称
   * @author Justin <justin@jipu.com>
   * @version 2015062611
   */
  public function editNickname(){
    if(IS_POST){
      //获取参数
      $uid = UID;
      $nickname = I('post.nickname');
      !$nickname && $this->error('请输入新昵称');
      
      if(M('Member')->where('uid='.$uid)->setField('nickname', $nickname)){
        S('sys_user_nickname_list', null);
        $this->success('修改成功！', U('Member/index'));
      }else{
        $this->error('修改失败！');
      }
    }else{
      $this->meta_title = '修改昵称';
      $this->display();
    }
  }
  
  /**
   * 修改用户个人资料
   * @author Max.Yu <max@jipu.com>
   */
  public function editProfile(){
    if(IS_POST){
      $member_model = D('Member');
      $res = $member_model->updateProfile();
      if(!$res){
        $this->error($member_model->getError());
      }else{
        $this->success('资料更新成功！', U('Member/index'));
      }
    }else{
      $profile = M('Member')->field('uid, sex, nickname, avatar')->getByUid(UID);
      $this->profile = $profile;
      $this->meta_title = '个人资料设置';
      $this->display();
    }
  }
  
  /**
   * 推广联盟
   * @author Max.Yu <max@jipu.com>
   */
  public function union(){
    if(C('DIS_START') != 1){
      $this->error('功能暂时关闭，敬请期待...');
    }
    /**
    status----1、代理权限启用 0、代理权限禁用 
    */
    M('Union')->where('uid='.UID)->getfield('status') ==1 ?R('Union/index') : $this->redirect('Member/becomeunion');
  }
  /**
   * 审核成为代理联盟条件
   * 
   */
  public function becomeunion(){


    //是否为代理联盟
   $this->union_status=M('Union')->where('uid='.UID)->getfield('status');
   $this->union_status_count=M('Union')->where('uid='.UID)->count();
   if( $this->union_status==1)
   {
       $this->redirect('Member/union');
   }
   $this->mymobile=M('User')->where('id='.UID)->getfield('mobile');

    /**
    form_union_status----1、代理权限启用 0、无代理权限 -1、代理权限审核 
    */
   $this->form_union_status=M('User')->where('id='.UID)->getfield('form_union_status');

    $data = $this->checkuser();
    if(!$data){
      $this->error('错误...错误...');
    }

    if(IS_POST && $data['status'] == 1){
      $user['user_id'] = UID ;
      R('Union/add');
      if(!M('Distribution')->where('user_id='.UID)->find()){
         M('Distribution')->add($user) ;
      }
      M('User')->where('id='.UID)->setField('form_union_status', -1)?$this->success('恭喜您，申请成功',U('Member/becomeunion')) : $this->error('申请失败，请稍后再试~'); 
    }
    $this->assign('mess' , $data['mess']);
    $data['status'] && $this->assign('status' ,$data['status']);
    $this->display();
  }
  public function unionerlist(){
    $type = I('type', 1 ,'int');
    if(!in_array($type , array(1,2,3))){
        $this->error('参数错误~');
    }
    $uid  = UID;
    if(!empty($type) && !empty($uid)){
      switch ($type) {
        case 2:
          $field = 'a.twoagents=' ;
          $types = '二';
          break;
        case 3:
          $field = 'a.threeagents=' ;
          $types = '三';
          break;
        default:
          $field = 'a.oneagents=' ;
          $types = '一';
          break;
      }
      $data = M('Distribution')->alias('a')->join('__USER__ b on a.user_id=b.id')->where($field.$uid)->field('a.user_id,b.username,b.mobile')->select();
      $this->count = M('Distribution')->alias('a')->join('__USER__ b on a.user_id=b.id')->where($field.$uid)->field('a.user_id,b.username,b.mobile')->count();
      // var_dump($data);die;
      $this->type = $types;
      $this->data = $data ;
      $this->display();
    }else{
      $this->error('错误~错误');
    } 
  }

  /**
   * 站内消息
   * @author Max.Yu <max@jipu.com>
   */
  public function message(){
    R('Message/index');
  }
  /**
   * 检查是否有权限能成为代理
   * @return [type] [description]
   */
  private function checkuser(){
    if(empty(UID)){
      return false;
    }
    $where['o_status'] =  C('DIS_ORDERSTATUS') > 0 ? array('in','-3,200,201,202') :202;
    $where['uid']  = UID;
    switch (C('DIS_CONDITION.TYPE')) {  
      case '1': 
        $mess   = '无需条件，立即加入。';
        $status = 1 ;
        break;
      case '2': 
        $count  = M('Order')->where($where)->count();
        $status =  $count > 0 ? 1: 2;
        if($status != 1){
          $mess  = '暂不符合条件，需购买一单，即可加入。';
        }
        break;
      case '3':
        $count = M('Order')->where($where)->count() ;
        $status= $count>= C('DIS_CONDITION.REQUEST3') ? 1: 2;
        if($status != 1){
          $mess  = '暂不符合条件，需再购买'.(C('DIS_CONDITION.REQUEST3')-$count).'单，即可加入。';
        }
        break;
      case '4':
        $count = M('Order')->where($where)->sum('total_price');
        $status= $count >= C('DIS_CONDITION.REQUEST4') ? 1: 2;
        if($status != 1){
          $mess  = '暂不符合条件，购买额还差'.(C('DIS_CONDITION.REQUEST4')-$count).'元，即可加入。';
        } 
        break;
    }
    $status && $data['status'] = $status ;
    $data['mess'] = $mess;
    return $data;
  }
}
