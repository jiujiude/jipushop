<?php
/**
 * 店铺资料管理模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Admin\Model\AdminModel;

class ShopModel extends AdminModel{

  /**
   * 获取统计数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getStatInfo($sdp_uid = 0, $start_time = '', $end_time = ''){
    //默认值
    empty($start_time) && $start_time = date('2010-01-01');
    empty($end_time) && $end_time = date('Y-m-d'); 
    //检测数据合法性
    if($sdp_uid == 0){
      return false;
    }
    $start = strtotime($start_time);
    $end = strtotime($end_time) + 86400;
    $where = array(
      'sdp_uid' => $sdp_uid,
      'create_time' => array('between', array($start, $end))
    );
    $model = M('SdpRecord');
    $order_count = count($model->where($where)->group('order_id')->select());
    return array(
      'amount' => $model->where($where)->sum('cashback_amount'),
      'order_count' => $order_count
    );
  }

}
