<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Addons\SiteStat;

use Common\Controller\Addon;

/**
 * 系统环境信息插件
 * @author thinkphp
 */
class SiteStatAddon extends Addon{

  public $info = array(
    'name' => 'SiteStat',
    'title' => '站点统计信息',
    'description' => '统计站点的基础信息',
    'status' => 1,
    'author' => 'thinkphp',
    'version' => '0.1'
  );

  public function install(){
    return true;
  }

  public function uninstall(){
    return true;
  }

  //实现的AdminIndex钩子方法
  public function adminIndex($param){
    $config = $this->getConfig();
    $this->assign('addons_config', $config);
    if($config['display']){
      if(IS_SUPPLIER){
        $order_map = array(
          'status' => 1,
          'payment_time' => array('gt', 0),
          'supplier_ids' => UID,
        );
        $data = array(
          'info' => array(
            'order' => M('Order')->where($order_map)->count(),
          ),
          'todolist' => array(
            'ship' => M('Order')->where($order_map)->where('`o_status` = 200')->count(),
          )
        );
      }else{
        // 统计商品、订单、用户、粉丝
        $data['info'] = array(
          'user' => M('User')->join('__MEMBER__ ON __USER__.id=__MEMBER__.uid AND __USER__.status>=0')->count(),
          'item' => M('Item')->where('status >= 0')->count(),
          'order' => M('Order')->where('status >= 1')->count(),
         // 'wechat_user' => M('WechatUser')->count(),
          'shop' => M('Shop')->count(),

        );
        // 统计待办事项
        $data['todolist'] = array(
          'ship' => M('Order')->where('o_status = 200 AND status = 1')->count(),
          'item' => M('Item')->where('stock = 0 AND status=1')->count(),
          'refund' => M('Refund')->where('refund_status = 0')->count(),
          'unrefund' => M('Order')->where('o_status = 300 AND status = 1')->count(),
          'withdraw' => M('Withdraw')->where('status = 100')->count(),
          'shop' => M('Shop')->where('status=0')->count(),
        );
      }
      
      //移除0待办
      foreach($data['todolist'] as $k => $v){
        if(0 == $v){
          unset($data['todolist'][$k]);
        }
      }

      $this->assign('data', $data);
      $this->display('info');
    }
  }

}
