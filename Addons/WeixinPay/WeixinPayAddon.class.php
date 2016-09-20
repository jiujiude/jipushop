<?php

namespace Addons\WeixinPay;

use Common\Controller\Addon;

/**
 * 微信支付插件
 * @author Max.Yu <max@jipu.com>
 */
class WeixinPayAddon extends Addon{

  public $info = array(
    'name' => 'WeixinPay',
    'title' => '微信支付',
    'description' => '微信支付插件',
    'status' => 1,
    'author' => 'Max.Yu',
    'version' => '1.0'
  );

  public function install(){
    return true;
  }

  public function uninstall(){
    return true;
  }

  //实现的weixinPay钩子方法
  public function weixinPay($param){
    $order_id = intval($param['order_id']);
    //检测订单号
    $order_id == 0 && die();
    
    if($_SERVER['HTTP_REFERER'] && strpos($_SERVER['HTTP_REFERER'], SITE_URL) == 0){
      session('cancelpay_tourl', $_SERVER['HTTP_REFERER']);
    }
    //微信支付参数
    $param['order_type'] = empty($param['order_type']) ? 'item_order' : $param['order_type'];
    $weixinPayParam = A('Home/Pay', 'Event')->getWeixinPayParam($order_id, $param['order_type']);
    $this->assign('weixinPayParam', $weixinPayParam);
    $param['order_id'] = $order_id;
    $this->assign('param', $param);
    if($param['order_type'] == 'item_order'){
      $order_sn = M('Order')->getFieldById($order_id, 'order_sn');
      session('cancelpay_tourl', U('Order/preview', array('order_sn' => $order_sn)));
    }
    $this->display('weixinPay');
  }

}
