<?php
/**
 * 店铺控制器
 * @author Justin
 */

namespace Admin\Controller;

class ShopController extends AdminController{

  /**
   * 店铺列表
   */
  function index($status = 2, $start_time = '', $end_time = ''){
    $where[] = 'status>-2';
    if(I('get.keywords')){
      $where['name|audit_data|intro'] = array('like', '%'.I('get.keywords').'%');
    }
    if(I('get.uid')){
      $where['uid'] = I('get.uid');
    }
    if(in_array($status, array(1, 0, -1))){
      $where['status'] = $status;
    }
    //过滤掉没有选商品的店
    $where[] = "item_ids != '' ";
    $this->setListOrder();
    $this->assign('time_search', $start_time || $end_time);
    $this->assign('status', $status);
    parent::index($where);
  }
  
  /**
   * 店铺列表统计处理
   */
  function _before_index_display(&$lists){
    $start_time = I('get.start_time');
    $end_time = I('get.end_time');
    //默认值
    empty($start_time) && $start_time = date('2010-01-01');
    empty($end_time) && $end_time = date('Y-m-d'); 
    //合法性判断
    if(strtotime($end_time) < strtotime($start_time)){
      return false;
    }
    foreach($lists as &$v){
      $res = D('Shop')->getStatInfo($v['uid'], $start_time, $end_time);
      if($res){
        $v['stat_amount'] = $res['amount'];
        $v['stat_order_count'] = $res['order_count']?: '';
      }
    }
  }

  /**
   * 店铺详情
   */
  public function detail($id = 0){
    $data = M('Shop')->find($id);
    $data['audit_data'] = unserialize($data['audit_data']);
    $data['stat_info'] = D('Shop')->getStatInfo($data['uid']);
    $this->data = $data;
    $this->meta_title = '店铺详细信息';
    $this->display();
  }
  
}
