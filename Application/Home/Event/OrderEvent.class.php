<?php
/**
 * 订单事件模型
 * @author max <max@jipu.com>
 */

namespace Home\Event;

class OrderEvent{

  /**
   * 获取订单类型
   */
  public function checkOrderType($order_id){
    if(!$order_id){
      return false;
    }
    $map_order['order_id'] = $order_id;
    //直接使用detail方法都会输出数据
    $check = M('CrowdfundingOrder')->where($map_order)->count();
    if($check){
      $result['name'] = 'crowdfunding';
      //TODO：CrowdfundingOrder中的detail方法调整，增加是否调用众筹用户列表的判断
      $result['data'] = D('CrowdfundingOrder')->getOrderInfo($order_id);
    }else{
      $result['name'] = 'item';
    }
    return $result;
  }

  /**
   * 获取各订单类型数量
   */
  public function getOrderNum($uid = 0){
    $return_data = array(
      'payment' => 0, //待付款
      'unship' => 0, //待发货
      'unreceive' => 0, //待收货
      'success' => 0, //已完成
      'recycle' => 0, //回收站
    );
    $where = array(
      'status' => 1,
      'uid' => $uid > 0 ? $uid : UID,
    );
    $field = 'id, o_status';
    $order_list = M('Order')->field($field)->where($where)->select();
    foreach($order_list as $l){
      if($l['o_status'] == 202){ //已完成
        $return_data['success'] ++;
      }elseif($l['o_status'] == 0){
        $return_data['payment'] ++;
      }elseif($l['o_status'] == 200){
        $return_data['unship'] ++;
      }elseif($l['o_status'] == 201){
        $return_data['unreceive'] ++;
      }
    }
    $where = array(
      'status' => 2,
      'uid' => $uid > 0 ? $uid : UID,
    );
    $return_data['recycle'] = M('Order')->where($where)->count();
    return $return_data;
  }

  /**
   * 保存微信接口返回的收货地址
   * @param boolean $return 是否直接返回操作状态
   * @author Max.Yu <max@jipu.com>
   */
  public function saveWechatAddress($return = false){
    $address = I('get.address', '');
    $res = 0;
    if($address){
      $get_data = json_decode($address, true);
      if($get_data['err_msg'] == 'edit_address:ok'){
        $arcode = $get_data['nationalCode'];
        $save_data = array(
          'name' => $get_data['userName'],
          'province' => substr($arcode, 0, 2).'0000',
          'district' => substr($arcode, 0, 4).'00',
          'city' => $arcode,
          'address' => $get_data['addressDetailInfo'],
          'mobile' => $get_data['telNumber'],
          'zipcode' => $get_data['addressPostalCode']
        );
        $_POST = $save_data;
        $res = D('Receiver')->update($save_data);
      }
    }
    if($res > 0){
      if($return){
        return $res;
      }else{
        redirect(U('Receiver/detail', array('id' => $res)));
      }
    }
    return false;
  }

  /**
   * 获取共享的微信地址参数
   * @param boolean $toAuth 如果未授权是否跳到授权
   * @author Max.Yu <max@jipu.com>
   */
  public function getWechatAddressConfig($toAuth = false){
    $nonceStr = get_randstr(6);
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $appid = C('WECHAT_APPID');
    $code = I('get.code');
    //加载微信SDK
    $wechat = new \Org\Wechat\WechatAuth($appid, C('WECHAT_SECRET'));
    $access_token = $wechat->getAccessToken('code');
    if(empty($access_token)){
      if($toAuth == false){
        return array();
      }
      if(!$code){
        //跳转到授权页面
        redirect($wechat->getRequestCodeURL($url));
      }else{
        $access_token = $wechat->getAccessToken('code', $code);
      }
    }
    $token_str = $access_token['access_token'];
    $now_time = time();
    $sign = "accesstoken={$token_str}&appid={$appid}&noncestr={$nonceStr}&timestamp={$now_time}&url={$url}";
    $addrSign = sha1($sign);
    $return_data = array(
      'appId' => $appid,
      'scope' => 'jsapi_address',
      'signType' => 'SHA1',
      'addrSign' => $addrSign,
      'timeStamp' => "$now_time",
      'nonceStr' => "$nonceStr",
    );
    return $return_data;
  }

  /**
   * 统计订单商品价格信息
   * @param array $item_ids 订单数据
   * @param bool $buynow 是否为立即购买
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   * @version 2015083115 Justin Rebuild
   */
  function doCount($item_ids, $buynow = false){
    $result = array(
      'total_num' => 0,
      'total_quantity' => 0,
      'total_price' => 0.00,
      'supplier' => array()
    );
    //第二件折扣
    $second_pieces_item = $this->getSecondPiecesItemIds($item_ids);
    if($second_pieces_item){
      $second_pieces_item_ids = array_column($second_pieces_item, 'item_id');
    }

    //获取购物车或者直从cookie中获取接购买的商品信息
    $cart_items = D('Order')->getCartItems($item_ids, $buynow);

    //计算重量，计算运费
    foreach($cart_items as $item){
      $supp_id = $item['supplier_id'];
      if(!isset($result['supplier'][$supp_id])){
        $result['supplier'][$supp_id] = array(
          'total_num' => 0,
          'total_quantity' => 0,
          'total_weight' => 0,
          'total_price' => 0,
          'delivery' => array()
        );
      }
      //商品种类数
      $result['supplier'][$supp_id]['total_num'] ++;
      //数量
      $result['supplier'][$supp_id]['total_quantity'] += $item['quantity'];
      //重量
      $result['supplier'][$supp_id]['total_weight'] += $item['weight'] * $item['quantity'];
      //商品价格
      $price = D('Order')->getItemPrice($item['item_id'], $item['item_code']);
      //第二件商品半价，除开秒杀商品
      if( in_array($item['item_id'], $second_pieces_item_ids) && ($item['quantity'] >= 2)){
        $item['quantity'] = 2;
        $second_price = $price * $second_pieces_item[$item['item_id']]['discount'];
        $result['supplier'][$supp_id]['total_price'] += $price + $second_price;
      }else{
        $result['supplier'][$supp_id]['total_price'] += $price * $item['quantity'];
      }
    }
    //订单商品种类数
    $result['total_num'] = count($cart_items);
    //订单商品数量
    $result['total_quantity'] = array_sum(array_column($result['supplier'], 'total_quantity'));
    //订单总价格
    $result['total_price'] = sprintf('%.2f', array_sum(array_column($result['supplier'], 'total_price')));
    //订单总重量
    $result['total_weight'] = sprintf('%.2f', array_sum(array_column($result['supplier'], 'total_weight')));
    //按供应商计算数据
    foreach($result['supplier'] as $supp_id => &$supp){
      //供应商总价格格式化
      $supp['total_price'] = sprintf('%.2f', $supp['total_price']);
      //最低免运费额度
      $free_amount = get_supplier_free_amount($supp_id);
      if($free_amount > 0 && $supp['total_price'] < $free_amount){
        $tpl_list = M('DeliveryTpl')->where(array('status' => 1, 'supplier_id' => $supp_id))->order('sort asc')->select();
        foreach($tpl_list as $line){
          $arr = array(
            'id' => $line['id'],
            'name' => $line['name'],
            'company' => $line['company'],
            'price_type' => $line['price_type'],
            'send_date' => $line['send_date'],
            'price' => $line['express_postage'],
          );
          if($line['price_type'] == 1){ //按件数计费
            if($supp['total_quantity'] <= $line['express_start']){
              $arr['price'] = $line['express_postage'];
            }else{
              $plus_w = $supp['total_quantity'] - $line['express_start'];
              $arr['price'] = number_format($plus_w * $line['express_postageplus'] + $line['express_postage'], 2);
            }
          }else if($line['price_type'] == 2){ //按重量计费
            if($supp['total_weight'] <= $line['express_start']){
              $arr['price'] = $line['express_postage'];
            }else{
              $plus_w = $supp['total_weight'] - $line['express_start'];
              $arr['price'] = ceil($plus_w) * $line['express_postageplus'] + $line['express_postage'];
            }
          }
          $arr['price'] = sprintf('%.2f', $arr['price']);
          $supp['delivery'][$arr['id']] = $arr;
        }
      }
    }
    //计算满减优惠（不包含邮费）
    $result['manjian'] = $result['total_price'] - $this->setOrderDiscountPrice($result['total_price']);
    return $result;
  }

  /**
   * 计算运费
   * @param array $supplier 为$order_count['supplier']
   * @author Justin <justin@jipu.com>
   */
  function getDeliveryAmount($supplier){
    $delivery_id = I('post.delivery_id');
    $delivery = 0;
    foreach($supplier as $k => $v){
      //$delivery += $v['delivery'][$delivery_id[$k]]['price'];
      $delivery_data[$k] = array(
        'amount' => $v['delivery'][$delivery_id[$k]]['price'],
        'delivery_id' => $delivery_id[$k]
      );
    }
    return $delivery_data;
  }

  /**
   * 计算总价
   * 获取订单金额，即礼品卡、优惠券、账户余额支付后需通过第三方支付的金额
   * 多种支付方式叠加时，优先判断是否使用礼品卡和优惠券
   * 减去礼品卡和优惠券金额后再获取使用账户余额支付的金额
   * @author Max.Yu <max@jipu.com>
   * @version 2015083115 Justin Rebuild
   */
  function getOrderTotalAmount($data){
    //使用优惠券
    self::_countCoupon($data);
    //使用礼品卡
    self::_countCard($data);
    //使用积分抵扣
    self::_countScore($data);
    //使用账户余额
    self::_countFinance($data);

    //计算使用优惠券、礼品卡、积分、账户余额后的剩余订单金额
    $mj_before = $total_amount = sprintf('%.2f', $data['total_price'] + $data['delivery_fee'] - $data['coupon_amount'] - $data['card_amount'] - $data['score_amount']);
    //计算打折商品总价（不包含邮费） 
    if($total_amount > 0){
      $total_amount = $this->setOrderDiscountPrice($total_amount - $data['delivery_fee']) + $data['delivery_fee'];
    }
    //满减优惠
    if($mj_before > $total_amount){
      $data['manjian'] = $mj_before - $total_amount;
    }
    //余额支付金额
    if($data['finance_amount']){
      $data['finance_amount'] = min($total_amount, $data['finance_amount']);
      $total_amount = $total_amount - $data['finance_amount'];
    }

    $data['total_amount'] = $total_amount ? : 0.00;
    //重置余额（因为余额没算折扣）
    //$order_price = $this->setOrderDiscountPrice($data['total_price'] - $data['coupon_amount'] - $data['score_amount'] - $data['card_amount']) + $data['delivery_fee'];
    //$data['finance_amount'] = sprintf('%.2f', min($data['finance_amount'], $order_price));
    //过滤订单数据
    self::_filterOrderData($data);
    return $data;
  }

  /**
   * 使用优惠券
   * @author Justin <justin@jipu.com>
   */
  private function _countCoupon(&$data){
    $coupon_id = I('post.coupon_id');
    if($coupon_id){
      $coupon_map['coupon_id'] = $coupon_id;
      $coupon_map['status'] = 0;
      $coupon_map['uid'] = UID;
      $coupon = D('CouponUser')->detail($coupon_map);
      if($coupon){
        $data['is_use_coupon'] = 1;
        $data['coupon_id'] = $coupon_id;

        //计算使用其他支付方式后的订单金额 - $data['finance_amount'] - $data['score_amount'] - $data['card_amount']
        $sub_amount = sprintf('%.2f', $data['total_price'] + $data['delivery_fee']);
        //订单还需支付金额大于等于优惠券余额，全部使用优惠券，否则使用还需支付金额
        $coupon_amount = ($sub_amount >= $coupon['Coupon']['amount']) ? $coupon['Coupon']['amount'] : $sub_amount;
        $data['coupon_amount'] = $coupon_amount;
      }
    }
  }

  /**
   * 使用礼品卡
   * @author Justin <justin@jipu.com>
   */
  private function _countCard(&$data){
    $card_id = I('post.card_id');
    if($card_id){
      $card_map['card_id'] = array('IN', $card_id);
      $card_map['uid'] = UID;
      $card = D('CardUser')->lists($card_map);
      //获取使用的礼品卡总余额
      if($card){
        foreach($card as $key => $value){
          $card_balance += $value['Card']['balance'];
        }
        $data['is_use_card'] = 1;
        $data['card_id'] = $card_id;

        //减去优惠券后的订单金额$data['score_amount'] - $data['finance_amount'] -
        $sub_amount = sprintf('%.2f', $data['total_price'] + $data['delivery_fee'] - $data['coupon_amount']);

        //获取礼品卡支付金额，订单还需支付金额大于等于礼品卡余额，支付金额为礼品卡总余额，否则为订单还需支付金额
        $card_amount = ($sub_amount >= $card_balance) ? $card_balance : $sub_amount;
        $data['card_amount'] = $card_amount;
      }
    }
  }

  /**
   * 使用积分兑换
   * @author Justin <justin@jipu.com>
   */
  private function _countScore(&$data){

    $score_amount = M('Payment')->getFieldById($data['payment_id'], 'score_amount');

    //减去优惠券后的订单金额$data['score_amount'] - $data['finance_amount'] -
    $sub_amount = sprintf('%.2f', $data['total_price'] + $data['delivery_fee'] - $data['coupon_amount'] - $data['card_amount']);

    $data['score_amount'] = min($sub_amount, $score_amount);
  }

  /**
   * 使用账户余额
   * @author Justin <justin@jipu.com>
   */
  private function _countFinance(&$data){
    //使用账户余额
    $member = D('Member')->info(UID);
    if(I('post.is_use_finance') == 1){
      if($member['finance'] > 0){
        //计算使用其他支付方式后剩余的订单金额
        $sub_amount = sprintf('%.2f', $data['total_price'] + $data['delivery_fee'] - $data['coupon_amount'] - $data['score_amount'] - $data['card_amount']);

        //订单还需支付金额大于等于账户余额，全部使用账户余额，否则使用还需支付金额
        $finance_amount = ($sub_amount >= $member['finance']) ? $member['finance'] : $sub_amount;

        $data['finance_amount'] = $finance_amount;
      }
    }
  }

  /**
   * 过滤订单数据
   * @author Justin <justin@jipu.com>
   */
  private function _filterOrderData(&$data){
    //优惠券
    if(1 == $data['is_use_coupon']){
      $payment_data['is_use_coupon'] = 1;
      $payment_data['coupon_amount'] = $data['coupon_amount'];
      unset($data['is_use_coupon']);
      unset($data['coupon_id']);
      unset($data['coupon_amount']);
    }
    //礼品卡
    if(1 == $data['is_use_card']){
      $payment_data['is_use_card'] = 1;
      $payment_data['card_amount'] = $data['card_amount'];
      unset($data['is_use_card']);
      unset($data['card_amount']);
      unset($data['card_id']);
    }
    //余额
    if(1 == I('post.is_use_finance')){
      $payment_data['is_use_finance'] = 1;
      $payment_data['finance_amount'] = $data['finance_amount'];
    }

    //积分
    if($data['score_amount'] > 0){
      $payment_data['score_amount'] = $data['score_amount'];
      $payment_data['use_score'] = round($data['score_amount'] * C('SCORE_EXCHANGE_NUMBER'));
    }
    unset($data['score_amount']);

    //满减
    if($data['manjian'] > 0){
      $payment_data['manjian'] = $data['manjian'];
      unset($data['manjian']);
    }

    //运费数组
    $payment_data['delivery_data'] = serialize($data['delivery_data']);
    unset($data['delivery_data']);
    //更新支付信息
    if($payment_data){
      $payment_data['id'] = $data['payment_id'];
      D('Payment')->update($payment_data);
    }
    
  }

  /**
   * 按供应商拆分订单
   * @author Justin <justin@jipu.com>
   */
  function splitOrderBySupplier($order_id = 0){
    if($order_id){
      $new_order_data = $order_data = M('Order')->getById($order_id);
      //邮费
      $payment_data = M('Payment')->field('delivery_data')->find($order_data['payment_id']);
      $delivery_data = unserialize($payment_data['delivery_data']);

      $counts = $count = count($supplier_data = str2arr($order_data['supplier_ids']));
      $k = 0;
      $memo = unserialize($new_order_data['memo']);
      if(count($count) == 1 ){
        foreach($memo as $k => $v){
         if(!empty($v)){
            M('Order')->where('id ='.$order_id)->save(array('memo' => $v));
         }
        }
      }
      if($count > 1){
        //拆分订单
        do{
          $new_order_data = $order_data = M('Order')->getById($order_id);
          $total_amount = $order_data['total_amount'];
          //重置新订单数据
          unset($new_order_data['id']);
          //获取供应商的item_ids
          $where = array(
            'supplier_id' => $supplier_data[$k],
            'order_id' => $order_data['id']
          );
          $new_order_data['supplier_ids'] = $supplier_data[$k];
          $new_order_item_lists = M('OrderItem')->field('id, item_id')->where($where)->select();
          $new_order_item_item_ids = array_column($new_order_item_lists, 'item_id');
          $new_order_item_ids = array_column($new_order_item_lists, 'id');
          $new_order_data['item_ids'] = arr2str($new_order_item_item_ids);
          $new_order_data['order_sn'] = create_order_sn();
          $order_itme_where['id'] = array('in', $new_order_item_ids);
          $order_item = M('OrderItem')->where($order_itme_where)->select();

          $new_order_data['total_amount'] = $new_order_data['total_price'] = $new_order_data['total_quantity'] = $new_order_data['total_weight'] = 0;
          foreach($order_item as $v){
            $new_order_data['total_price'] += $v['price'] * $v['quantity'];
            $new_order_data['total_quantity'] += $v['quantity'];
            $new_order_data['total_weight'] += $v['weight'];
          }
          //运费
          $new_order_data['delivery_fee'] = $delivery_data[$new_order_data['supplier_ids']]['amount'] ? $delivery_data[$new_order_data['supplier_ids']]['amount'] : 0.00;
          $new_order_data['delivery_id'] = $delivery_data[$new_order_data['supplier_ids']]['delivery_id'];
          //总价
          if($order_data['total_amount'] != ($order_data['total_price'] + $order_data['delivery_fee'])){
            //有优惠
            $new_order_data['total_amount'] = $new_order_data['total_price'] / $order_data['total_price'] * $total_amount;  //两位小数
            //$new_order_data['total_amount'] = floor($new_order_data['total_price'] / $order_data['total_price'] * $total_amount);
          }else{
            //无优惠
            $new_order_data['total_amount'] = $new_order_data['total_price'] + $new_order_data['delivery_fee'];
          }
          //余额
          $new_order_data['finance_amount'] = min(ceil($new_order_data['total_price'] / $order_data['total_price'] * $order_data['finance_amount']), ($new_order_data['total_price'] + $new_order_data['delivery_fee']));
          if( $counts > 1 && !empty($memo)){
            $new_order_data['memo'] = $memo[$supplier_data[$k]];
          }
          
          //插入订单
          $new_order_id = M('Order')->add($new_order_data);
          if($new_order_id){
            $data['order_id'] = $new_order_id;
            $where['id'] = array('in', $new_order_item_ids);
            M('OrderItem')->where($where)->save($data);
          }

          //更新原订单
          unset($supplier_data[$k]);
          $old_order_data = array(
            'supplier_ids' => arr2str($supplier_data),
            'item_ids' => arr2str(array_diff(str2arr($order_data['item_ids']), $new_order_item_item_ids)),
            'total_amount' => $order_data['total_amount'] - $new_order_data['total_amount'],
            'total_price' => $order_data['total_price'] - $new_order_data['total_price'],
            'total_quantity' => $order_data['total_quantity'] - $new_order_data['total_quantity'],
            'total_weight' => $order_data['total_weight'] - $new_order_data['total_weight'],
            'delivery_fee' => $order_data['delivery_fee'] - $new_order_data['delivery_fee'],
            'finance_amount' => $order_data['finance_amount'] - $new_order_data['finance_amount'],
            'delivery_id' => $delivery_data[arr2str($supplier_data)]['delivery_id']
          );
          M('Order')->where('id='.$order_id)->save($old_order_data);

          $count--;
          $k++;
        }while($count > 1);
      }
      if($counts > 1 ){
        $order_data = M('Order')->getById($order_id);
        M('Order')->where('id='.$order_id)->setField('memo',$memo[$order_data['supplier_ids']]);
      }
    }
  }

  /**
   * 计算打折商品总价（不包含邮费）
   * @version 2015093010
   * @author Justin <justin@jipu.com>
   */
  function setOrderDiscountPrice($total_price){
    //满减
    $total_price = $this->_setOrderManjianPrice($total_price);
    //会员等级
    $total_price = $this->_setOrderUserGroupPrice($total_price);

    return $total_price;
  }

  /**
   * 满减价格
   * @param double $total_price 订单总价格
   * @version 2015093010
   * @author Justin <justin@jipu.com>
   */
  private function _setOrderManjianPrice($total_price){
    //获取满减活动
    $now = time();
    $where['man'] = array('elt', $total_price);
    $where['status'] = array('gt', 0);
    $where[] = "start_time < {$now}";
    $where[] = "{$now}< expire_time + 86400";
    $lists = A('Page', 'Event')->lists('Manjian', $where, 'man desc');
    if($lists){
      foreach($lists as $v){
        if($total_price >= $v['man']){
          $total_price -= $v['jian'];
          break;
        }
      }
    }
    return $total_price;
  }
  
  /**
   * 获取满减规则
   * @version 2015093010
   * @author Justin <justin@jipu.com>
   */
  public function getManjianRule(){
    $now = time();
    $where = array(
      'start_time' => array('elt', NOW_TIME),
      'expire_time' => array('gt', NOW_TIME-86400),
      'status' => 1
    );
    $rules = M('Manjian')->where($where)->order('`man` asc')->getField('man,jian', true);
    return $rules;
  }

  /**
   * 根据用户组设置订单价格
   * @param double $total_price 订单总价格
   * @version 2015061617
   * @author Justin <justin@jipu.com>
   */
  private function _setOrderUserGroupPrice($total_price){
    //获取用户组折扣
    $user_group_id = M('User')->getFieldById(UID, 'group_id');
    if($user_group_id){
      //获取折扣
      $discount = M('UserGroup')->getFieldById($user_group_id, 'discount');
    }
    if($discount && $discount < 1 && $discount > 0){
      $total_price = sprintf('%.2f', $total_price * $discount);
    }
    return $total_price;
  }

  /**
   * 获取购物车中属于第二件折扣
   * @return array
   * @version 2015100915
   * @author Justin <justin@jipu.com>
   */
  function getSecondPiecesItemIds($list){
    if($list){
      if(is_array($list)){
        //获取购物车中所有的item_id
        foreach($list as $k => $v){
          $item_ids .= arr2str(array_column($v['item'], 'item_id')).',';
        }
      }else{
        $item_ids = $list;
      }

      $now = time();
      $where = array(
        'item_id' => array('in', rtrim($item_ids, ',')),
        'status' => 1,
      );
      $where[] = "start_time < {$now}";
      $where[] = "{$now} < expire_time + 86400";
      $second_pieces_lists = M('SecondPieces')->where($where)->select();
      if($second_pieces_lists){
      //return $second_pieces_item = array_column($second_pieces_lists, 'item_id');
        foreach($second_pieces_lists as $v){
          $second_pieces_item[] = array(
            'item_id' => $v['item_id'],
            'discount' => $v['discount'],
            'name' => $v['name'],
          );
        }
      }
      $second_pieces_item = array_column($second_pieces_item, null, 'item_id');
      return $second_pieces_item;
    }
  }

}
