<?php
/**
 * 统计数据模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Think\Model;

class StatModel extends Model{

  protected $tableName = 'Order';

  /**
   * 获取商户的订单统计数据
   * @return array 统计数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getOrderStatNum($start_date = '', $end_date = ''){
    $return_data = array();
    $start_time = !empty($start_date) ? strtotime($start_date) : 0;
    $end_time = !empty($end_date) ? strtotime($end_date.' +1 day') : time();
    //供应商过滤
    if(IS_SUPPLIER){
      $where['supplier_id'] = UID;
      $order_where[] = 'find_in_set('.UID.', supplier_ids)';
    }
    //订单总数
    $order_where['status'] = array('egt', 1);
    $order_where[] = "`create_time`<{$end_time} AND `create_time`>$start_time";
    $return_data['order_count'] = M('Order')->where($order_where)->count();

    //待支付订单数量
    $add_where = array('o_status' => 0);
    $return_data['order_unpay_count'] = M('Order')->where($order_where)->where($add_where)->count();
    //交易成功
    $add_where = array('o_status' => 202);
    $return_data['order_success_count'] = M('Order')->where($order_where)->where($add_where)->count();
    //退款
    $add_where = array('o_status' => array('IN', '300,301,302,303'));
    $return_data['order_refund_count'] = M('Order')->where($order_where)->where($add_where)->count();
    //已支付
    $add_where = array('payment_time' => array('gt', 0));
    $return_data['order_ispay_count'] = M('Order')->where($order_where)->where($add_where)->count();
    
    //已取消订单
    //$add_where = array('order_status'=>-1);
    //$return_data['order_cancel_count'] = M('Order')->where($order_where)->where($add_where)->count();
    //已成交金额
    $shop = $this->field('sum(total_amount) amount')->where('o_status=202')->where($order_where)->find();
    $return_data['finish_amount'] = $shop['amount'];

    return $return_data;
  }

  /**
   * 获取订单图表所需数据
   * @param date $start_date 开始日期
   * @param date $end_date 结束日期
   * @return array 组装好的json数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getOrderChartData($start_date = '', $end_date = '', $return_type = 'chat'){
    $return_data = $return_array = array();
    //起始时间处理
    $todayStart = strtotime(date('Y-m-d'));
    $start_time = !empty($start_date) ? strtotime($start_date) : $todayStart - 30 * 86400;
    $end_time = !empty($end_date) ? strtotime($end_date) : $todayStart;
    $end_time += 86400;
    if($start_time >= $end_time){
      $return_data['error'] = '非正常时间段';
      return $return_data;
    }
    //日期模板
    $data_tpl = array();
    for($i = $start_time; $i < $end_time; $i+=86400){
      $data_tpl[date('m-d', $i)] = 0;
      $return_array[date('Y-m-d', $i)] = array('create' => 0, 'payment' => 0, 'total_amount' => 0);
    }
    $order_where = array('status' => 1);
    $typearr = array(
      'create' => '下单笔数',
      'payment' => '支付笔数',
    );
    $return_data['legend'] = json_encode(array_values($typearr));
    $return_data['xAxis'] = json_encode(array_keys($data_tpl));
    foreach($typearr as $type => $desc){
      //按下单时间、支付时间
      $add_where = array(
        "`{$type}_time` < {$end_time}",
        "`{$type}_time` > {$start_time}"
      );
      //供应商过滤
      if(IS_SUPPLIER){
        $add_where[] = 'find_in_set('.UID.', supplier_ids)';
      }  
      $list = M('Order')->field("id,{$type}_time,total_amount")->where($add_where)->where($order_where)->select();
      $box = $data_tpl;
      foreach($list as $line){
        $box[date('m-d', $line["{$type}_time"])] ++;
        $return_array[date('Y-m-d', $line["{$type}_time"])][$type] ++;
        if($type == 'payment'){
          $return_array[date('Y-m-d', $line["{$type}_time"])]['total_amount'] += $line['total_amount'];
        }
      }

      $return_data['data'][] = array(
        'name' => $desc,
        'type' => 'line',
        //'stack' => '总量',
        'data' => array_values($box)
      );
    }
    $return_data['data'] = json_encode($return_data['data']);
    //返回组装好的数据
    return $return_type == 'chat' ? $return_data : $return_array;
  }

  /**
   * 获取用户注册统计数据
   * @return array 统计数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getUserStatNum(){
    $return_data = array();
    //今天开始时间
    $todayStart = strtotime(date('Y-m-d'));
    $user = M('User');
    //用户总数
    $urser_where = array('status' => 1);
    $return_data['user_count'] = $user->where($urser_where)->count();

    //今日注册
    $add_where = array(
      "`reg_time` <= ".time()." AND `reg_time`>= ".$todayStart,
    );
    $return_data['user_today_count'] = $user->where($urser_where)->where($add_where)->count();

    //昨日注册
    $add_where = array(
      "`reg_time` <= ".$todayStart." AND `reg_time`>= ".($todayStart - 86400),
    );
    $return_data['user_yesterday_count'] = $user->where($urser_where)->where($add_where)->count();
    //
    $w = date('w', $todayStart);
    //上周注册
    $add_where = array(
      "`reg_time` <= ".($todayStart - $w * 86400)." AND `reg_time`>= ".($todayStart - ($w + 7) * 86400),
    );
    $return_data['user_pweek_count'] = $user->where($urser_where)->where($add_where)->count();
    //本周注册
    $add_where = array(
      "`reg_time` <= ".time()." AND `reg_time`>= ".($todayStart - $w * 86400),
    );
    $return_data['user_week_count'] = $user->where($urser_where)->where($add_where)->count();
    return $return_data;
  }

  /**
   * 获取分销店铺返现概况
   * @return array 统计数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getSdpAmountStatNum(){
    $return_data = array();
    //今天开始时间
    $todayStart = strtotime(date('Y-m-d'));
    $sdp_model = M('SdpRecord');
    $w = date('w', $todayStart);
    //类型循环
    $type = array(
      'sdp_amount' => array(0, NOW_TIME),
      'sdp_today_amount' => array($todayStart, $todayStart+86400),
      'sdp_yesterday_amount' => array($todayStart-86400, $todayStart),
      'sdp_pweek_amount' => array($todayStart - ($w + 7) * 86400, $todayStart - $w * 86400),
      'sdp_week_amount' => array($todayStart - $w * 86400, NOW_TIME),
    );
    foreach($type as $k => $v){
      $where = array(
        'create_time' => array('between', array($v[0], $v[1]))
      );
      $line = $sdp_model->field('sum(cashback_amount) as sum_amount')->where($where)->find();
      $return_data[$k] = $line['sum_amount'] ? $line['sum_amount'] : 0;
    }
    return $return_data;
  }

  /**
   * 获取用户图表所需数据
   * @param date $start_date 开始日期
   * @param date $end_date 结束日期
   * @return array 组装好的json数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getUserChartData($start_date = '', $end_date = '', $return_type = 'chat'){
    $return_data = $return_array = array();
    //起始时间处理
    $todayStart = strtotime(date('Y-m-d'));
    $start_time = !empty($start_date) ? strtotime($start_date) : $todayStart - 30 * 86400;
    $end_time = !empty($end_date) ? strtotime($end_date) : $todayStart;
    $end_time += 86400;
    if($start_time >= $end_time){
      $return_data['error'] = '非正常时间段';
      return $return_data;
    }
    //日期模板
    $data_tpl = array();
    for($i = $start_time; $i < $end_time; $i+=86400){
      $data_tpl[date('m-d', $i)] = 0;
      $return_array[date('Y-m-d', $i)] = 0;
    }
    $user_where = array('status' => 1);
    $typearr = array(
      'reg' => '注册人数',
    );
    $return_data['legend'] = json_encode(array_values($typearr));
    $return_data['xAxis'] = json_encode(array_keys($data_tpl));
    foreach($typearr as $type => $desc){
      //按下单时间、支付时间
      $add_where = array(
        "`{$type}_time` < {$end_time}",
        "`{$type}_time` > {$start_time}"
      );
      $list = M('User')->field("id,{$type}_time")->where($add_where)->where($user_where)->select();
      $box = $data_tpl;
      foreach($list as $line){
        $box[date('m-d', $line["{$type}_time"])] ++;
        $return_array[date('Y-m-d', $line["reg_time"])] ++;
      }
      $return_data['data'][] = array(
        'name' => $desc,
        'type' => 'line',
        'data' => array_values($box)
      );
    }
    $return_data['data'] = json_encode($return_data['data']);
    //返回组装好的数据
    return $return_type == 'chat' ? $return_data : $return_array;
  }

  /**
   * 获取分销金额图表数据
   */
  public function getSdpAmountChartData($start_date = '', $end_date = '', $return_type = 'chat'){
    $return_data = $return_array = array();
    //起始时间处理
    $todayStart = strtotime(date('Y-m-d'));
    $start_time = !empty($start_date) ? strtotime($start_date) : $todayStart - 30 * 86400;
    $end_time = !empty($end_date) ? strtotime($end_date) : $todayStart;
    $end_time += 86400;
    //日期模板
    $data_tpl = array();
    for($i = $start_time; $i < $end_time; $i+=86400){
      $data_tpl[date('m-d', $i)] = 0;
      $return_array[date('Y-m-d', $i)] = 0;
    }
    $typearr = array(
      'amount' => '返现金额',
    );
    $return_data['legend'] = json_encode(array_values($typearr));
    $return_data['xAxis'] = json_encode(array_keys($data_tpl));
    $record_list = M('sdp_record')->where(array('create_time' => array('between', array($start_time, $end_time))))->select();
    $box = $data_tpl;
    foreach($record_list as $line){
      $box[date('m-d', $line["create_time"])] += $line['cashback_amount'];
      $return_array[date('Y-m-d', $line["create_time"])] += $line['cashback_amount'];
    }
    $return_data['data'][] = array(
      'name' => $typearr['amount'],
      'type' => 'line',
      'data' => array_values($box)
    );
    $return_data['data'] = json_encode($return_data['data']);
    //返回组装好的数据
    return $return_type == 'chat' ? $return_data : $return_array;
  }
  
  
  /**
   * 将数组值反转为主键
   */
  protected function arrKeyChangeByVal($array, $keyName){
    $array_tmp = array();
    foreach($array as $v){
      $array_tmp[$v[$keyName]] = $v;
    }
    return $array_tmp;
  }
  
  /**
   * 商品销量
   * @author Justin
   */
  function getItemStat($start_date = '', $end_date = ''){
    $return_data = array();
    $start_time = !empty($start_date) ? strtotime($start_date) : 0;
    $end_time = !empty($end_date) ? strtotime($end_date.' +1 day') : strtotime(date("Y-m-d", strtotime('+1 day')));
    //获取订单
    $where = array(
      'o_status' => array('IN', '200, 201, 202'),
      'create_time' => array('between', array($start_time, $end_time))
    );
    $item_id = I('get.item_id', 0, intval);
    if($item_id > 0){
      $where[] = 'FIND_IN_SET('.$item_id.', item_ids)';
    }
    
    $lists_order = M('Order')->field('id')->where($where)->cache(true, 86400)->select();

    //获取商品销量
    foreach($lists_order as $v){
      
      $lists_order_item = M('OrderItem')->field('item_id, quantity')->where('order_id='.$v['id'])->cache(true, 86400)->select();
      foreach($lists_order_item as $value){
        if(isset($return_data[$value['item_id']])){
          $return_data[$value['item_id']] += $value['quantity'];
        }else{
          $return_data[$value['item_id']] = $value['quantity'];
        }
      }
    }
    if($item_id > 0){
      $return_data = array($item_id => $return_data[$item_id]?:0);
    }
    return $return_data;
  }
  
}
