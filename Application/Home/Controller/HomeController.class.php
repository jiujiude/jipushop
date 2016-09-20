<?php
/**
 * 前台公共控制器
 * @version 2014041618
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

use Common\Controller\BaseController;
use Common\Api\UserApi;

class HomeController extends BaseController{

  /**
   * 空操作，用于输出404页面
   * @author Max.Yu <max@jipu.com>
   */
  public function _empty(){
    redirect(U('/'));
  }

  protected function _initialize(){
    parent::_initialize();
    //限制仅初始化一次 @chunkuan 20151016.09
    if($this->is_init){
      return;
    }else{
      $this->is_init = 1;
    }
    
    //读取站点配置
    $config = api('Config/lists');
    C($config); //添加配置
    if(!C('WEB_SITE_CLOSE')){
      $this->error('站点已经关闭，请稍后访问~');
    }

    //获取当前用户ID
    define('UID', is_login());
    if(is_login()){
      //初始化用户
      $member_model = D('Member');
      $user_api = new UserApi;
      $member = $member_model->info(UID);
      $member['score_amount'] = C('SCORE_EXCHANGE_NUMBER')>0 ? sprintf('%.2f', $member['score']/C('SCORE_EXCHANGE_NUMBER')) : 0;
      $this->member = $member;
      $this->user = $user_api->info(UID);
    }

    //初始化当前用户统计数据
    if(!is_login()){
      $user_count['cart_count'] = (cookie('__cart__') !== null) ? count(json_decode(cookie('__cart__'), true)) : 0;
    }else{
      $user_count = D('Usercount')->getUserCount(UID);
    }

    //获取热门搜索关键词
    if(C('SEARCH_KEYWORDS')){
      $this->assign('keywords', C('SEARCH_KEYWORDS'));
    }
    if(C('SEARCH_KEYWORDS_INPUT')){
      $this->assign('hotwords', C('SEARCH_KEYWORDS_INPUT'));
    }
    //设置需显模板
    $this->setShowTpl();
    //设置常用常量
    $this->setDefine();
    //分销模块初始化
    $this->sdpInit();
    //站内消息未读条数
    $this->assign('message_unread', A('Message', 'Event')->getUnreadNum(UID));
    $this->assign('user_count', $user_count);
    
  }

  /**
   * 用户登录检测
   * @author Max.Yu <max@jipu.com>
   */
  protected function login($ajax = false){
    if(!is_login()){
      //判断是否微信中打开
      if(is_weixin() && is_mobile()){
        $this->redirect('User/wechatlogin');
      }else{
        $this->redirect('User/login');
      }
    }
  }

  /**
   * 分销平台初始化信息
   * @author Max.Yu <max@jipu.com>
   */
  private function sdpInit(){
    //获取当前用户的店铺分享Key
    $secret = C('SDP_IS_OPEN') ? A('Shop', 'Event')->getShopSecret() : '';
    define('SHOP_SECRET', $secret);
    //存储分销来源信息
    $sdp_secret = I('get.sdp_secret', $secret? : '');
    $sdp_uid = 0;
    if($sdp_secret){
      if(C('SDP_IS_OPEN')){
        $from_shop = M('Shop')->getBySecret($sdp_secret);
        if($from_shop && $from_shop['status'] == 1){
          $sdp_uid = $from_shop['uid'];
        }
      }
      //防止自己的uid顶掉来源的uid
      if($sdp_uid == UID && session('sdp_uid')){
        return;
      }else{
        session('sdp_uid', $sdp_uid);
      }
    }
  }

  /**
   * 设置需要显示的模板
   * @author Max.Yu <max@jipu.com>
   */
  private function setShowTpl(){
    //手机访问跳转至手机版
    if(is_mobile()){
      C('DEFAULT_THEME', C('DEFAULT_THEME').'-mobile');
      //百度手机端统计
      if(C('BAIDU_STAT_KEY')){
        vendor('BaiduStat.Hm');
        $_hmt = new \Vendor\_HMT(C('BAIDU_STAT_KEY'));
        $_hmtPixel = $_hmt->trackPageView();
        $this->assign('_hmtPixel', $_hmtPixel);
      }
    }else{
      //PC端
      $tpl = T();
      //模板不存在加载手机端模板
      if(!file_exists($tpl)){
        C('PC2MOBILE', 1);
        $default_theme = C('DEFAULT_THEME');
        C('DEFAULT_THEME', C('DEFAULT_THEME').'-mobile');
        //手机端模板不存在加载PC端模板
        $tpl = T();
        if(!file_exists($tpl)){
          C('PC2MOBILE', 0);
          C('DEFAULT_THEME', $default_theme);
        }
      }
    }
  }

  /**
   * 设置常用常量
   * @author Max.Yu <max@jipu.com>
   */
  protected function setDefine(){
    $same_path = __ROOT__.'/Public/'.MODULE_NAME.'/'.C('DEFAULT_THEME');
    !defined('__IMG__') && define('__IMG__', $same_path.'/images');
    !defined('__CSS__') && define('__CSS__', $same_path.'/css');
    !defined('__JS__') && define('__JS__', $same_path.'/js');
  }

}
