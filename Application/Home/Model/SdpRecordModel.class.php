<?php
/**
 * 分销记录模型
 * @version 2015080612
 * @author Justin <justin@jipu.com>
 */

namespace Home\Model;

class SdpRecordModel extends HomeModel{
  


	/**
	 * 支付完成，分销返现
	 * @param number $order_id 订单id
	 */
	public function sdpOrder($order_id = 0){
		//获取订单信息
		if($order_id){
			$map['order_id'] = $order_id;
			$order = M('Order')->where(array('id'=>$order_id,'status'=>1))->find();
			$items = M('OrderItem')->where($map)->field('price,quantity,sdp_code,item_id,item_code')->select();
			if($items){
				//返现总数
				$user_finance = array();
				$finance = 0;
				foreach($items as $key => $val){
					$finance = $this->getCashBackAmount($val['item_id'], $val['price'], $val['quantity']);
					if( $val['sdp_code'] && $finance > 0){
						$shop = M('shop')->where(array('secret'=>$val['sdp_code'],'status'=>1))->find();
						if($shop){
							$data_sdp_log[] = array(
									'uid' => $order['uid'],
									'sdp_uid' => $shop['uid'],
									'order_id' => $order_id,
									'item_id' => $val['item_id'],
									'item_code' => $val['item_code'],
									'item_price' => $val['price'],
									'quantity' => $val['quantity'],
									'cashback_amount' => $finance,
									'create_time' => NOW_TIME,
									'merchant_id'=>$order['merchant_id'],
							);
							//根据分销商用户，记录收入总额
							if(isset($user_finance[$shop['uid']])){
								$user_finance[$shop['uid']]['cashback'] += $finance;
							}else{
								$user_finance[$shop['uid']]['cashback'] = $finance;
							}
	
						}
	
					}
					$finance = 0;
				}//foreach
	
				foreach ($user_finance as $k=>$v){
					//累计收入
					M('Shop')->where('uid='.$k)->setInc('total_revenue', $v['cashback']);
					//增加推广余额
					M('Member')->where('uid='.$k)->setInc('finance', $v['cashback']);
					//添加到账户明细交易表中
					$data_finance = array(
							'uid' => $k,
							'order_id' => $order_id,
							'type' => 'sdp_order',
							'amount' => $v['cashback'],
							'flow' => 'in',
							'memo' => '分销订单返现',
							'create_time' => NOW_TIME,
							'merchant_id'=>$order['merchant_id'],
					);
					$update = M('Finance')->add($data_finance);
				}
	
				$this->addAll($data_sdp_log);
	
			}
		}
	}
	
	
  /**
   * TODO:目前已经暂停使用
   * 更新分销记录
   * @param int $order_id 订单id
   * @version 2015080612
   * @author Justin <justin@jipu.com>
   */
  function updateLog($order_id = 0){
    //获取订单信息
    if($order_id){
      $where['id'] = $order_id;
      $data_order = D('Order')->detail($where, 'id, uid, sdp_uid');
      if($data_order['sdp_uid']){
        //返现总数
        $finance = 0;
        foreach($data_order['items'] as $k => $v){
          if($this->getCashBackAmount($v['item_id'], $v['price'], $v['quantity']) > 0){
            $data_sdp_log[] = array(
              'uid' => $data_order['uid'],
              'sdp_uid' => $data_order['sdp_uid'],
              'order_id' => $data_order['id'],
              'item_id' => $v['item_id'],
              'item_code' => $v['item_code'],
              'item_price' => $v['price'],
              'quantity' => $v['quantity'],
              'cashback_amount' => $this->getCashBackAmount($v['item_id'], $v['price'], $v['quantity']),
              'create_time' => NOW_TIME,
            );
            $finance += $data_sdp_log[$k]['cashback_amount'];
          }
        }

        $this->addAll($data_sdp_log);
        //累计收入
        M('Shop')->where('uid='.$data_order['sdp_uid'])->setInc('total_revenue', $finance);
        
        //增加推广余额
        M('Member')->where('uid='.$data_order['sdp_uid'])->setInc('finance', $finance); 

        $data_finance = array(
          'uid' => $data_order['sdp_uid'],
          'order_id' => $order_id,
          'type' => 'sdp_order',
          'amount' => $finance,
          'flow' => 'in',
          'memo' => '分销订单返现',
          'create_time' => NOW_TIME
        );
        $update = M('Finance')->add($data_finance);
      }
    }
  }
  
  /**
   * 计算返现金额
   * @param int $item_id 商品id
   * @param double $item_price 商品价格
   * @param int $quantity 商品购买数量
   * @version 2015080612
   * @author Justin <justin@jipu.com>
   */
  function getCashBackAmount($item_id, $item_price, $quantity){
    $data = M('Item')->field('sdp_type, sdp')->find($item_id);
    if(1 == $data['sdp_type']){
      //比例
      $cashback_amount = $item_price * $data['sdp'] / 100;
    }else{
      $cashback_amount = $data['sdp'];
    }
    return ($cashback_amount > $item_price ? $item_price : $cashback_amount) * $quantity;
  }
  
}
