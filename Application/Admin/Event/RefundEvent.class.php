<?php
/**
 * 退款事件模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Event;

class RefundEvent{

  /**
   * 执行退款
   * @param array $ids 订单编号集合
   * @param string $dotype 执行类型（cancel取消订单，refund退款）
   * @return array 执行结果
   */
  public function doit($ids, $dotype = 'cancel'){
    $return_data = array('code' => 300);
    $map['id'] = array('in', $ids);
    if(M('Order')->where($map)->setField('o_status', ($dotype == 'cancel' ? 404 : 303))){
      //处理库存和退款
      $order_lists = D('Order')->lists($map);
      if($order_lists){
        foreach($order_lists as $key => $value){
          //商品库存处理
          $order_items = get_order_item($value['id']);
          if($order_items){
            $item_model = M('Item');
            foreach($order_items as $item){
              $item_model->where('id='.$item['item_id'])->setInc('stock', $item['quantity']);
            }
          }
          //第三方退款处理
          if($value['total_amount'] > 0){
            $payment = M('Payment')->getById($value['payment_id']);
            
            $payment_return = json_decode($payment['payment_return'], true);
            $trade_no = $payment_return['trade_no'];
            if(empty($trade_no)){
              $trade_no = $payment_return['transaction_id'];
            }
            if(empty($trade_no)){
              $trade_no = M('Payment')->getFieldByOrderId($value['id'], 'payment_sn');
            }
            $refund_data = array(
              'uid' => $value['uid'],
              'order_id' => $value['id'],
              'payment_type' => $payment['payment_type'],
              'trade_no' => $trade_no,
              'refund_type' => 'item',
              'amount' => $value['total_amount'],
              'detail' => '购物退款'
            );
            D('Refund')->update($refund_data);
          }
          
          //账户余额返还
          if($value['finance_amount'] > 0){
            $this->refundFinance($value['id']);
          }
          
          //分销订单取消返现
          if($value['sdp_uid'] > 0){
            foreach($order_items as $keys=>$vals){
                $shop = M('shop')->where(array('secret'=>$vals['sdp_code']))->find();
                $line = M('SdpRecord')->where(array('sdp_uid' => $shop['uid'], 'order_id' => $value['id']))->field('sum(cashback_amount) as sum_amount')->find();
                if($line){
                    $finance_data = array(
                            'uid' => $shop['uid'],
                            'order_id' => $value['id'],
                            'type' => 'sdp_refund',
                            'amount' => $line['sum_amount'],
                            'flow' => 'out',
                            'memo' => '分销订单退款',
                            'create_time' => NOW_TIME
                    );
                    if(M('Finance')->add($finance_data)){
                        M('Member')->where('uid = '.$shop['uid'])->setDec('finance', $line['sum_amount']);
                    }
                }
            }
            
          }
        }
      }
      //action_log('cancel_order', 'Order', $id, UID);
      $return_data = array('code' => 200, 'info' => '订单'.($dotype == 'cancel' ? '取消' : '退款').'成功！');
    }else{
      $return_data = array('code' => 300, 'info' => '订单'.($dotype == 'cancel' ? '取消' : '退款').'失败！');
    }
    return $return_data;
  }

  /**
   * 退款成功后，更新退款订单
   */
  public function afterRefundSuccess($refund_no, $refund_info){
    //获取退款订单信息
    $refund_details = explode('#', $refund_info['details']);
    add_wechat_log($refund_details, 'refund-details');
    if($refund_details){
      foreach($refund_details as $key => $value){
        $refund_item = explode('^', $value);
        $refund_map = array(
          'trade_no' => $refund_item[0],
          'refund_no' => $refund_no
        );
        $refund_orders = M('Refund')->where($refund_map)->select();
        foreach($refund_orders as $refund_order){
          if(($refund_item[2] == 'SUCCESS') && ($refund_order['refund_status'] == 0)){
            $refund_data = array(
              'refund_status' => 1,
              'refund_return' => json_encode($refund_info)
            );
            add_wechat_log($refund_data, 'refund-data');
            M('Refund')->where('id = '.$refund_order['id'])->save($refund_data);
            $this->series_refund($refund_order['order_id']);
          }
        }
      }
    }
  }
   // 推广分销返现 取消
  function series_refund($orderid){
    $where['type']   = array('in' ,array('union_order')) ;
    $where['flow']   = 'in' ;
    $where['status'] = 0 ;
    $where['order_id']     = $orderid ;
    $result = M('Finance')->where($where)->getField('id' ,true);
    if(!$result){
      return false;
    }
    $map = implode(',' , $result);
    M('Finance')->where('id in ('.$map.')')->setField('status' , '2');
    $users = M('Finance')->where('id in ('.$map.')')->field('uid,amount')->select();
    foreach($users as $k => $v){
      M('Member')->where('uid ='.$v['uid'])->setDec('finance' ,$v['amount']);
    }
  }
  /**
   * 账户余额返还处理
   */
  public function refundFinance($order_id, $memo = '订单退款'){
    $order = M('Order')->find($order_id);
    if($order && $order['finance_amount'] > 0){
      //更新用户账户余额
      $update_member = M('Member')->where('uid = '.$order['uid'])->setInc('finance', $order['finance_amount']);
      if($update_member){
        //增加更新记录
        $data_finance = array(
          'uid' => $order['uid'],
          'order_id' => $order['id'],
          'type' => 'refund',
          'amount' => $order['finance_amount'],
          'flow' => 'in',
          'memo' => $memo,
          'create_time' => NOW_TIME
        );
        $update_finance = M('Finance')->add($data_finance);
      }else{
        return false;
      }
      return true;
    }else{
      return false;
    }
  }

  /**
   * 优惠券返还处理
   */
  public function refundCoupon($order_id){
    //优惠券暂无须处理，不返还
  }

  /**
   * 礼品卡返还处理
   */
  public function refundCard($order_id){
    $order = M('Order')->find($order_id);
    if($order && $order['is_use_card'] == 1){
      //更新card_user表
      $card_id = $order['card_id'];
      $map_user['card_id'] = array('IN', $card_id);
      $data_user = array(
        'status' => 0,
        'use_time' => ''
      );
      $update_card_user = D('CardUser')->where($map_user)->save($data);

      //更新card表
      $map_card['id'] = array('IN', $card_id);
      $data_card = array(
        'balance' => array('exp', 'balance + '.$order['card_amount']),
        'use_time' => '',
        'is_use' => 0
      );
      $update_card = M('Card')->where($map_card)->save($data_card);
      if($update_card_user && $update_card){
        return true;
      }else{
        return false;
      }
    }
  }
  
  
  /**
   * 单笔退款
   * @param int $refund_id 退款记录ID
   */
  public function dealRefund($refund_id){
    $return_data = array('status' => 0, 'info' => '退款出错了');
    $data = M('Refund')->find($refund_id);
    $data['order'] = M('Order')->find($data['order_id']);
    if($data['refund_satus'] != 0){
      $return_data['info'] = '当前状态，不可进行退款操作';
      return $return_data;
    }
    //微信退款
    if($data['payment_type'] == 'wechatpay'){
      $pay_data = M('Payment')->getById($data['order']['payment_id']);
      $out_refund_no = date('YmdHis').mt_rand(100000, 999999);
      import('Org.Wechat.Pay.WxPayPubHelper', '', '.php');
      $payment_way = C('WECHATPAY');
      $refund_api = new \Refund_pub();
      $refund_api->setParameter("transaction_id", $pay_data['payment_sn']); //
      $refund_api->setParameter('out_refund_no', $out_refund_no);
      $refund_api->setParameter('total_fee', $pay_data['payment_amount']*100);
      $refund_api->setParameter('refund_fee', $data['order']['total_amount']*100);
      $refund_api->setParameter('op_user_id', $payment_way['mch_id']);
      //退款
      $res = $refund_api->getResult();
      if($res['return_code'] == 'FAIL'){
        $refund_query_api = new \RefundQuery_pub();
        $refund_query_api->setParameter("transaction_id", $pay_data['payment_sn']); //openid
        //退款状态
        $res_query = $refund_query_api->getResult();
      }
      if($res['return_code'] == 'SUCCESS' || $res_query['result_code'] == 'SUCCESS'){
        $save_data = array(
          'refund_no' => $out_refund_no,
          'refund_status' => 1,
          'refund_return' => json_encode($res),
        );
        M('Refund')->where(array('id' => $data['id']))->save($save_data);
        $order_id = M('Refund')->where('id='.$data['id'])->getField('order_id');
        $this->series_refund($order_id);
        return array('status' => 1, 'info' => '退款发起成功');
      }else{
        $return_data['info'] = $res['return_msg'];
      }
    }
    return $return_data;
  }

}
