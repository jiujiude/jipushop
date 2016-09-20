<?php
/**
 * 店铺事件控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Event;

class ShopEvent{
  
  /**
   * 获取Shop分享串
   * @author Max.Yu <max@jipu.com>
   */
  public function getShopSecret(){
    $shop_secret = '';
    if(UID > 0){
      $shop_secret = M('Shop')->where(array('status' => 1))->getFieldByUid(UID, 'secret') ? : '';
    }
    return $shop_secret;
  }
  
}
