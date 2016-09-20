<?php
/**
 * 统计控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Admin\Model\AuthGroupModel;
use Think\Page;

class StatController extends AdminController{

  /**
   * 订单统计
   */
  public function index($start_time = '', $end_time = '', $export = null){

    $today = date('Y-m-d');
    $reload = I('get.reload', '');
    if($reload){
      unset($_GET['reload'], $_GET['formhash']);
    }
    //供应商过滤
    if(IS_SUPPLIER){
      $cacheName = 'stat_order_'.md5(serialize(I('get.').UID));
    }else{
      $cacheName = 'stat_order_'.md5(serialize(I('get.')));
    }
    if(!S($cacheName) || $reload == 1){
      S($cacheName, null);
      //统计内容
      $stat = array(
        'shopname' => IS_SUPPLIER ? M('Supplier')->getFieldBySupplierId(UID, 'name') : C('WEB_SITE_TITLE'),
      );
      //统计信息
      $stat['stat_num'] = D('Stat')->getOrderStatNum($start_time, $end_time);
      //获取图表数据
      $stat['chartData'] = D('Stat')->getOrderChartData($start_time, $end_time);
      if(isset($stat['chartData']['error'])){
        $this->error($stat['chartData']['error']);
      }
      $stat['cacheTime'] = time();
      S($cacheName, $stat, 3600);
      if(IS_AJAX){
        $this->success(I('get.'));
      }
    }else{
      $stat = S($cacheName);
      if((time() - $stat['cacheTime']) < 20){
        $stat['is_new'] = 1;
      }
    }
    //订单导出
    if(isset($export) && $export == 1){
      $this->export($start_time, $end_time, 'order');
      exit();
    }
    $this->today = $today;
    $this->start_time = $start_time;
    $this->end_time = $end_time;
    $this->stat = $stat;
    $this->meta_title = '订单统计';
    $this->display();
  }

  /**
   * 商品统计
   */
  function goods($start_time = null, $end_time = '', $sort = 'desc', $export = null){
    //默认取当月
    !$start_time && $start_time = date("Y-m-".'01');
    $today = date('Y-m-d');
    //统计信息
    $stat['stat_num'] = D('Stat')->getItemStat($start_time, $end_time);
    //排序
    if('desc' == $sort){
      arsort($stat['stat_num'], SORT_NUMERIC);
    }else{
      asort($stat['stat_num'], SORT_NUMERIC);
    }
    //商品信息
    $where['id'] = array('in', array_keys($stat['stat_num']));
    if($stat['stat_num']){
      $order = "field(id, ".implode(',', array_keys($stat['stat_num'])).")";
      $stat['lists'] = M('Item')->field('id, number, name, category')->where($where)->order($order)->cache(true, 86400)->select();
    }
    //合并数据
    foreach($stat['lists'] as $k => $v){
      $stat['lists'][$k]['buynum'] = $stat['stat_num'][$v['id']];
      //防止导出科学计数法编号
      $stat['lists'][$k]['number'] = ' '.$stat['lists'][$k]['number'];
    }
    //订单导出
    if(isset($export) && $export == 1){
      $this->export($start_time, $end_time, 'item', $stat['lists']);
      exit();
    }
    $item_id = I('item_id', 0, intval);
    $this->today = $today;
    $this->start_time = $start_time;
    $this->end_time = $end_time;
    $this->stat = $stat;
    $this->meta_title = '商品销量';
    $this->item_id = $item_id;
    if($item_id > 0){
      $this->record_list = A('Stat', 'Event')->itemRecordList($start_time, $end_time, $item_id);
    }
    $this->display();
  }

  /**
   * 用户统计
   */
  public function user($start_time = '', $end_time = '', $export = null){

    $today = date('Y-m-d');
    $reload = I('get.reload', '');
    if($reload){
      unset($_GET['reload'], $_GET['formhash']);
    }
    $cacheName = 'stat_user_'.md5(serialize(I('get.')));
    if(!S($cacheName) || $reload == 1){
      S($cacheName, null);
      //统计内容
      $stat = array(
        'shopname' => C('WEB_SITE_TITLE'),
      );
      //统计信息
      $stat['stat_num'] = D('Stat')->getUserStatNum();
      //获取图表数据
      $stat['chartData'] = D('Stat')->getUserChartData($start_time, $end_time);
      if(isset($stat['chartData']['error'])){
        $this->error($stat['chartData']['error']);
      }
      $stat['cacheTime'] = time();
      S($cacheName, $stat, 3600);
      if(IS_AJAX){
        $this->success(I('get.'));
      }
    }else{
      $stat = S($cacheName);
      if((time() - $stat['cacheTime']) < 20){
        $stat['is_new'] = 1;
      }
    }
    //订单导出
    if(isset($export) && $export == 1){
      $this->export($start_time, $end_time, 'user');
      exit();
    }
    $this->today = $today;
    $this->start_time = $start_time;
    $this->end_time = $end_time;
    $this->stat = $stat;
    $this->meta_title = '用户统计';
    $this->display();
  }

  /**
   * 分销返现金额趋势
   */
  public function sdpAmount($start_time = '', $end_time = '', $export = null){

    $today = date('Y-m-d');
    $reload = I('get.reload', '');
    if($reload){
      unset($_GET['reload'], $_GET['formhash']);
    }
    $cacheName = 'stat_sdp_amount_'.md5(serialize(I('get.')));
    if(!S($cacheName) || $reload == 1){
      S($cacheName, null);
      //统计内容
      $stat = array(
        'shopname' => C('WEB_SITE_TITLE'),
      );
      //统计信息
      $stat['stat_num'] = D('Stat')->getSdpAmountStatNum();
      //获取图表数据
      $stat['chartData'] = D('Stat')->getSdpAmountChartData($start_time, $end_time);
      if(isset($stat['chartData']['error'])){
        $this->error($stat['chartData']['error']);
      }
      $stat['cacheTime'] = time();
      S($cacheName, $stat, 3600);
      if(IS_AJAX){
        $this->success(I('get.'));
      }
    }else{
      $stat = S($cacheName);
      if((time() - $stat['cacheTime']) < 20){
        $stat['is_new'] = 1;
      }
    }
    //订单导出
    if(isset($export) && $export == 1){
      $this->export($start_time, $end_time, 'sdp_amount');
      exit();
    }
    $this->today = $today;
    $this->start_time = $start_time;
    $this->end_time = $end_time;
    $this->stat = $stat;
    $this->meta_title = '分销返现';
    $this->display();
  }

  /**
   * 订单导出
   * @author Max.Yu <max@jipu.com>
   */
  private function export($start_time = '', $end_time = '', $type = 'order', $data = null){
    if($type == 'order'){
      $headArr = '日期;下单笔数;支付笔数;成交金额（元）';
      $filename = "订单数统计";
      $data = array();
      $_data = D('Stat')->getOrderChartData($start_time, $end_time, 'array');
      $create = $payment = $amount = 0;
      foreach($_data as $k => $line){
        $data[] = array(
          $k, $line['create'], $line['payment'], $line['total_amount']
        );
        $create += $line['create'];
        $payment += $line['payment'];
        $amount += $line['total_amount'];
      }
      $data[] = array('合计', "{$create} 笔", "{$payment} 笔", "{$amount} 元");
    }elseif($type == 'user'){
      $headArr = '日期;注册人数';
      $filename = "注册人数统计";
      $data = array();
      $_data = D('Stat')->getUserChartData($start_time, $end_time, 'array');
      $reg_count = 0;
      foreach($_data as $k => $line){
        $data[] = array(
          $k, $line
        );
        $reg_count += $line;
      }
      $data[] = array('合计', "{$reg_count} 人");
    }elseif($type == 'sdp_amount'){
      $headArr = '日期;返现金额';
      $filename = "分销返现金额统计";
      $data = array();
      $_data = D('Stat')->getSdpAmountChartData($start_time, $end_time, 'array');
      $reg_count = 0;
      foreach($_data as $k => $line){
        $data[] = array(
          $k, $line
        );
        $reg_count += $line;
      }
      $data[] = array('合计', "{$reg_count} 元");
    }elseif($type == 'item'){
      $headArr = '商品id;商品编号;商品类别;商品名称;总销量';
      $filename = "商品销量统计";
    }
    //调用Excel文件生成并导出函数
    createExcel($filename, explode(';', $headArr), $data);
  }

}
