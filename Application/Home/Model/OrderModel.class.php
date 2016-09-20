<?php
/**
 * 订单模型
 * @version 2014102014
 * @author Justin <justin@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;
use Think\Cache;

class OrderModel extends RelationModel{
  
  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
    array('receiver_id', 'require', '请您选择收货地址！'), 
    array('item_ids', 'require', '订单商品不能为空！'), 
    //array('payment_type', 'require', '请您选择支付方式！'), 
  );

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('order_sn', 'create_order_sn', self::MODEL_INSERT, 'function'),
    array('order_from', '_getOrderFrom', self::MODEL_INSERT, 'callback'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 关联规则
   * @var array
   */
  protected $_link = array(
    'Ship' => array(
      'mapping_type' => self::HAS_ONE,
      'class_name' => 'Ship',
      'foreign_key' => 'order_id',
      'mapping_fields' => 'id, delivery_sn, delivery_name, create_time'
    ),
    'OrderItem' => array(
      'mapping_type' => self::HAS_MANY,
      'class_name' => 'OrderItem',
      'foreign_key' => 'order_id',
      'mapping_fields' => 'name, item_id, item_code, thumb, spec, price, quantity, fugou_dis_price'
    )
  );

  /**
   * 获取订单列表
   * @param array $map 查询条件参数
   * @param string $order 排序规则
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 订单列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map = array(), $field = true, $order = '`id` DESC', $limit = 10){
    $lists = $this->field($field)->where($map)->order($order)->relation('OrderItem')->limit($limit)->select();
    if($lists){
      foreach($lists as $key => &$value){
        $value['timer'] = time2str($value['create_time'] + 24 * 3600 - NOW_TIME); //TODO:默认为24小时，后期可在后台配置
        $value['total_package'] = count(str2arr($value['item_ids']));
        $value['is_comment'] = is_comment($value['uid'], $value['id']);
      }
    }
    return $lists;
  }

  /**
   * 获取包含订单明细的订单列表
   * @param array $map 查询条件参数
   * @param string $order 排序规则
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 订单列表
   * @author Max.Yu <max@jipu.com>
   */
  public function listsItem($map = array(), $field = true, $order = '`id` DESC', $limit = 10){
    $Order = $this->field($field)->relation('OrderItem');
    $lists = A('Page', 'Event')->lists($Order, $map, $order, $limit);
    if($lists){
      foreach($lists as $key => &$value){
        $value['timer'] = time2str($value['create_time'] + 24 * 3600 - NOW_TIME); //TODO:默认为24小时，后期可在后台配置
        $value['total_package'] = count(str2arr($value['item_ids']));
        $value['show_comment_btn'] = $value['payment_time'] > 0;
        if($value['OrderItem'] && is_array($value['OrderItem'])){
          foreach($value['OrderItem'] as $k => &$v){
            //规格图片
            $pic = get_cover(M('PropertyOption')->getFieldByCode($v['item_code'], 'pic'), 'path');
            //不存在则为封面图片
            $v['cover_path'] = $pic ? $pic : get_cover($v['thumb'], 'path');
            $v['is_comment'] = is_comment($value['uid'], $v['item_id'], $value['id']);
          }
        }
        //判断订单类型
        $value['order_type'] = A('Order', 'Event')->checkOrderType($value['id']);
        //收货人信息
        $value['ship'] = M('OrderShip')->getByPaymentId($value['payment_id']);
      }
    }
    return $lists;
  }

  /**
   * 获取订单详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->relation('Ship')->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '订单信息不存在！';
      return false;
    }
    //统计包裹数量
    $info['total_package'] = count(str2arr($info['item_ids']));
    //获取订单商品明细
    $info['items'] = D('OrderItem')->getOrderItem($info['id'], 'name, item_id, thumb, quantity, price, spec, item_code, fugou_dis_price, memo');
    if($info['items']){
      foreach($info['items'] as $key => &$value){
        $value['is_comment'] = is_comment($info['uid'], $value['item_id'], $info['id']);
      }
    }
    //判断订单类型
    $info['order_type'] = A('Order', 'Event')->checkOrderType($info['id']);
    //收货人信息
    $info['ship'] = M('OrderShip')->getByPaymentId($info['payment_id']);
    //支付信息
    $info['payment'] = M('Payment')->getById($info['payment_id']);
    //计算优惠金额
    $info['discount_amount'] = sprintf("%.2f", ($info['total_price'] + $info['delivery_fee'] - $info['total_amount'] - $info['finance_amount']));
    //其他优惠
    $info['discount_amount'] = sprintf('%.2f', ($info['discount_amount']-$info['payment']['coupon_amount']-$info['payment']['score_amount']-$info['payment']['card_amount']-$info['payment']['manjian']));
    //支付方式
    $info['payment_type_text'] = $info['payment'] ? get_payment_type_text($info['payment']): '';
    return $info;
  }
  
  /**
   * 下单检测库存
   * @param array $item_ids 订单数据
   * @param bool $buynow 是否为立即购买
   * @return boolean 检测结果
   * @version 2015070817
   * @author Justin <justin@jipu.com>
   */
  function checkItemStock($item_ids, $buynow = false){
    $cart_items = $this->getCartItems($item_ids, $buynow);
    if($cart_items){
      foreach($cart_items as $key => &$value){
        $item_stock = $this->getItemStock($value['item_id'], $value['item_code']);
        if($cart_items[$key]['quantity'] > $item_stock){
          return false;
        }
      }
      return true;
    }
    return false;
  }
  
  /**
   * 下单检测限购
   * @param array $item_ids 商品集合
   * @param boolean $buynow 是否为立即购买
   * @return boolean 能否可以购买
   * @author Max.Yu <max@jipu.com>
   */
  function checkItemQuota($item_ids, $buynow = false){
    $cart_items = $this->getCartItems($item_ids, $buynow);
    if($cart_items){
      foreach($cart_items as $li){
        $quota_num = get_quota_num($li['item_id']);
        if($quota_num < $li['quantity']){
          return false;
        }
      }
      return true;
    }
    return false;
  }
  
  /**
   * 获取购物车商品
   * @param array $item_ids 订单数据
   * @param bool $buynow 是否为立即购买
   * @return array 购物车商品
   * @version 2015070817
   * @author Justin <justin@jipu.com>
   */
  function getCartItems($item_ids, $buynow){
    //获取当前用户购物车所有商品
    if($buynow == false){
      $map['uid'] = UID;
      $map['item_id'] = array('IN', $item_ids);
      $cart_items = D('Cart')->lists($map, true);
      //获取立即购买cookie中的商品
    }else{
      $cart_items = array(unserialize(cookie('__buynow__')));
    }
    return $cart_items;
  }
  
  
  /**
   * 秒杀商品下单（因为秒杀商品不包含其他的任何活动信息，所以需要单独剔除出来）
   * @author  ezhu
   */
  public function addSeckill(){
      if(!$this->_checkSeckill()){
          return false;
      }
      //检测用户数据
      $data = $this->create();
      if(!$data){
          return false;
      }
      //支付id
      $payment_id = D('Payment')->update($data);
      $data['payment_id'] = $payment_id;
      //根据购物车商品规格，重新获取商品价格
      $seckillEvent = A('Seckill', 'Event');
      $countData = $seckillEvent->getCount();
      //运费
      $countData['supplier'] = (array)$countData['supplier'];
      $delivery_data = A('Order', 'Event')->getDeliveryAmount($countData['supplier']);
      foreach($delivery_data as $k => $v){
          $data['delivery_fee'] += $v['amount'];
      }
      $data['delivery_data'] = $delivery_data;
      $data['total_price'] = $countData['total_price'];
      $data['total_quantity'] = $countData['total_quantity'];
      $data['total_weight'] = $countData['total_weight'];
      if($data['total_price'] == 0){
          M('Payment')->delete($payment_id);
          return false;
      }
      $result = $order_id = M('Order')->add($data);
      //更新order_item表
      if($result){
          //立即购买从cookie中取商品数据
          $buynow_item = unserialize(cookie('__buynow__'));
          if($buynow_item){
              unset($buynow_item['delivery']);
              unset($buynow_item['cover_path']);
              unset($buynow_item['subAmount']);
          }
          $items = array($buynow_item);
      
          if($items){
              $supp_ids = array();
              foreach($items as $key => &$value){
                  $item_data = M('Item')->field('supplier_id')->find($value['item_id']);
                  $value['order_id'] = $order_id;
                  $value['spec'] = serialize($value['spec']);
                  $value['price'] = $this->getItemPrice($value['item_id'], $value['item_code']);
                  $value['supplier_id'] = $item_data['supplier_id'];
      
                  $supp_ids[] = $value['supplier_id'];
              }
      
      
              foreach($items as $v){
                  M('OrderItem')->add($v);
              }
               
              //更新订单表的item_ids和总数量
              $new_data = array(
                   'total_quantity' => $data['total_quantity'],
                   'item_ids' => arr2str(array_unique(explode(',', $data['item_ids']))),
                   'supplier_ids' => arr2str(array_unique($supp_ids))
              );
              M('Order')->where('id='.$order_id)->save($new_data);
      
              //删除cookie商品数据
              cookie('__buynow__', null);
              //更新统计
              $this->updateCount();
          }
      }
      return $order_id;
      
  }

  /**
   * 更新订单信息
   * @param array $data 订单数据
   * @param boolean $buynow 是否为立即购买
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null, $buynow = false){
    if(!$this->_checkOrderData()){
      return false;
    }
    
    //支付id
    $payment_id = D('Payment')->update($data);
    
    //收货地址信息
    if(!$this->_orderShipUpdate($payment_id)){
      return false;
    }
    //获取数据，包括分销来源、订单来源、订单编号等
    $data = $this->create($data);
    //清理分销订单，避免重复的分销id
    session('sdp_uid',null);
    
    if(!$data){
      return false;
    }
    //发票处理
    if($data['invoice_title']){
        $data['invoice_need'] = 1;
    }
    
    $data['payment_id'] = $payment_id;
    
    //秒杀商品信息
    $seckill = A('Item','Event')->seckillData($data['item_ids'],'item_id');

    //根据购物车商品规格，重新获取商品价格
    $order_count = A('Order', 'Event')->doCount($data['item_ids'], I('post.buynow') == 1,$seckill);
    //运费
    $delivery_data = A('Order', 'Event')->getDeliveryAmount($order_count['supplier']);
    foreach($delivery_data as $k => $v){
      $data['delivery_fee'] += $v['amount'];
    }
    $data['delivery_data'] = $delivery_data;
    $data['total_price'] = $order_count['total_price'];
    $data['total_quantity'] = $order_count['total_quantity'];
    $data['total_weight'] = $order_count['total_weight'];
    //更新满减到支付表
    $order_count['manjian'] && M('Payment')->where('id='.$payment_id)->setField('manjian', $order_count['manjian']);
    //计算总价
    $data = A('Order', 'Event')->getOrderTotalAmount($data);
    if(!$data['item_ids'] || $data['total_price'] == 0){
      M('Payment')->delete($payment_id);
      return false;
    }
    $data['memo'] = serialize($data['memo']);
    $result = $order_id = $this->add($data);
    //更新order_item表
    if($result){    
      if($buynow == false){
        //获取用户购物车商品信息
        $cmap['uid'] = UID;
        $cmap['item_id'] = array('in', $data['item_ids']);
        $field = 'item_code, item_id, number, name, thumb, spec, price, quantity, weight ,sdp_code';
        $items = D('Cart')->lists($cmap, $field);
      }else{
        //立即购买从cookie中取商品数据
        $buynow_item = unserialize(cookie('__buynow__'));
        if($buynow_item){
          unset($buynow_item['delivery']);
          unset($buynow_item['cover_path']);
          unset($buynow_item['subAmount']);
        }
        $items = array($buynow_item);
        $data['seckill'] = A('Item', 'Event')->getSeckill($items[0]['item_id']);
        //属于秒杀商品
        if($data['seckill'] && ( ($data['seckill']['start_time'] > NOW_TIME) || ($data['seckill']['expire_time'] < NOW_TIME) )){
          //删除订单
          M('Order')->delete($order_id);
          $this->error = '秒杀活动尚未开始或者已过期!';
          return false;
        }
      }
      
      if($items){
        $supp_ids = array();
        //买送-获取赠品
        $_arr = array();
        //分销返现钱
        $sdp_cashback = 0;
        foreach($items as $key => &$value){
            if($value['sdp_code']){
                $sdp_cashback += D('SdpRecord')->getCashBackAmount($value['item_id'], $value['price'], $value['quantity']);
            }
          $item_data = M('Item')->field('supplier_id')->find($value['item_id']);
          $value['order_id'] = $order_id;
          $value['spec'] = serialize($value['spec']);
          $value['price'] = $this->getItemPrice($value['item_id'], $value['item_code']);
          $value['supplier_id'] = $item_data['supplier_id'];
          
          //第二件折扣
          $second_pieces_item = A('Order', 'Event')->getSecondPiecesItemIds($data['item_ids']);
          if($second_pieces_item){
            $second_pieces_item_ids = array_column($second_pieces_item, 'item_id');
          }
          if(in_array($value['item_id'], $second_pieces_item_ids) && ($value['quantity'] >= 2)){
            $value['quantity'] = 2;
            $second_price = $value['price'] * $second_pieces_item[$value['item_id']]['discount'];
            $value['sub_total'] = sprintf("%.2f", $value['price'] + $second_price);
            $value['memo'] = $second_pieces_item[$value['item_id']]['name'];
          }else{
            $value['sub_total'] = 0;
            $value['memo'] = '';
          }
          $value['fugou_dis_price'] = A('Fugou', 'Event')->getDisPriceByUser($value['item_id'], UID);
          $supp_ids[] = $value['supplier_id'];
          
          //买送-获取赠品
          if(isset($_arr[$value['item_id']])){
            $_arr[$value['item_id']][1] += $value['quantity'];
          }else{
            $_arr[$value['item_id']] = array($value['item_id'], $value['quantity']);
          }
        }

        $send_item = A('Item', 'Event')->getSendItems($_arr);
        foreach($send_item as $vo){
          $item_data = M('Item')->field('id, name, thumb, supplier_id, number')->find($vo['id']);
          $new = array(
            'item_id' => $vo['id'],
            'item_code' => $item_data['number'],
            'supplier_id' => $item_data['supplier_id'],
            'name' => $item_data['name'],
            'number' => $item_data['number'],
            'spec' => '',   
            'price' => 0.00,
            'thumb' => $item_data['thumb'],
            'quantity' => $vo['num'],
            'weight' => 0.00,
            'order_id' => $order_id,
            'sub_total' => 0,
            'memo' => '',
            'fugou_dis_price' => 0.00
          );
          $data['item_ids'] .= ','. $vo['id'];
          $data['total_quantity']+=$vo['num'];
          array_push($items, $new);
        }
        
        foreach($items as $v){
          M('OrderItem')->add($v);
        }
        
        //M('OrderItem')->addAll($items);
   
        //更新订单表的item_ids和总数量
        $new_data = array(
          'total_quantity' => $data['total_quantity'],
          'item_ids' => arr2str(array_unique(explode(',', $data['item_ids']))),
          'supplier_ids' => arr2str(array_unique($supp_ids))
        );
        //重置分销
        if($sdp_cashback){
          $new_data['sdp_uid'] = 1;
        }
        M('Order')->where('id='.$order_id)->save($new_data);
        
        //删除购物车商品数据
        if($buynow == false){
          $map['uid'] = UID;
          $map['item_id'] = array('in', $data['item_ids']);
          D('Cart')->remove($map);
          //删除cookie中的数据
        }else{
          cookie('__buynow__', null);
        }
        //更新统计
        $this->updateCount();
      }
    }
    return $order_id;
  }

  /**
   * 根据订单字段更新订单信息，主要用于支付
   * @param array $data 订单数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function updateByField($map, $data = null){
    if(!$map && !$data){
      return false;
    }
    return $this->where($map)->save($data);
  }

  /**
   * 更新当前用户订单统计数据
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  protected function updateCount(){
    $map['uid'] = UID;
    $map['status'] = 1;
    $count = $this->where($map)->count();
    $result = D('Usercount')->setKeyValue(UID, 'order_count', $count);
    return $result;
  }

  /**
   * 根据商品id获取价格
   * @param int $item_id 商品id
   * @param string $item_code 商品编码
   * @param bool  $iskill 是否秒杀(避免每次都请求秒杀表)
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  function getItemPrice($item_id, $item_code ,$iskill=false){
    if(empty($item_id) || empty($item_code)){
      return false;
    }
    //如果是秒杀商品返回秒杀价
    if($iskill){
        $time = NOW_TIME;
        $secMap['item_id'] = $item_id;
        $secMap['item_spc'] = $item_code;
        $secMap['status'] = 1;
        $secMap['stime'] = array('elt',$time);
        $secMap['etime'] = array('egt',$time);
        $secData = M('seckill_item')->where($secMap)->find();
        if($secData['item_price']){
            return $secData['item_price'];
        }
    }
    
    
    $spec_map = array(
      'item_id' => $item_id,
      'spc_code' => $item_code
    );
    $check_spec = M('ItemSpecifiction')->where($spec_map)->find();
    if($check_spec){
      $result = $check_spec['price'];
    }else{
      $item_map = array(
        'id' => $item_id,
      );
      $result = M('Item')->where($item_map)->getField('price');
    }
    
    //复购优惠
    $dis_price = A('Fugou', 'Event')->getDisPriceByUser($item_id, UID);
    if($dis_price > 0 && $result > 0){
     $result = sprintf('%.2f', $result - $dis_price);
    }
    
    return $result;
  }
  
  /**
   * 根据商品id获取库存
   * @param int $item_id 商品id
   * @param string $item_code 商品编码
   * @return string 库存
   * @version 2015070817
   * @author Justin <justin@jipu.com>
   */
  function getItemStock($item_id, $item_code){
    if(empty($item_id) || empty($item_code)){
      return false;
    }
    $spec_map = array(
      'item_id' => $item_id,
      'spc_code' => $item_code
    );
    $check_spec = M('ItemSpecifiction')->where($spec_map)->find();
    if($check_spec){
      $result = $check_spec['quantity'];
    }else{
      $item_map = array(
        'id' => $item_id,
      );
      $result = M('Item')->where($item_map)->getField('stock');
    }
    return $result;
  }
  
  /**
   * 获取分销用户id
   * @return int 分销用户id
   * @version 2015080609
   * @author Justin <justin@jipu.com>
   */
  protected function _getSdpUid(){
    return session('sdp_uid');//(UID != session('sdp_uid')) ? session('sdp_uid') : 0;
  }
  
  /**
   * 检测订单数据是否合法
   * @author Justin <justin@jipu.com>
   */
  private function _checkOrderData(){
    $item_ids = I('post.item_ids');
    $buynow = I('post.buynow');
    if($buynow){
      !cookie('__buynow__') && $this->error = '已超时请重新下单！';
    }
    //检测库存
    if(!$this->checkItemStock($item_ids, $buynow == 1)){
      $this->error = '部分商品库存不足导致下单失败！';
      return false;
    }
    //检测限购
    if(!$this->checkItemQuota($item_ids, $buynow == 1)){
      $this->error = '部分商品超过限购数量导致下单失败！';
      return false;
    }
    return true;
  }
  
  
  /**
   * 检查秒杀商品
   * @return boolean
   */
  public function _checkSeckill(){
      $item_ids = I('post.item_ids');
      !cookie('__buynow__') && $this->error = '已超时请重新下单！';
      //检测库存
      $cart_items = array(unserialize(cookie('__buynow__')));
      $redis = Cache::getInstance('Redis');
      $item = $redis->get('invoice_'.$item_ids);
      if($cart_items[0]['quantity'] > $item['item_stock']){
          $this->error = '商品库存不足，下单失败';
          return false;
      }
      //检测限购
      if($cart_items[0]['quantity'] > $item['quota_num']){
          $this->error = '部分商品超过限购数量导致下单失败！';
          return false;
      }
      return true;
  }
  
  
  /**
   * 写入订单收货信息
   * @author Justin <justin@jipu.com>
   */
  private function _orderShipUpdate($payment_id){
    $receiver_id = I('post.receiver_id');
    //获取收货人信息
    $receiver = D('Receiver')->detail(array('id' => $receiver_id));
    //过滤收货地址
    if(!M('Area')->find($receiver['city'])){
      $this->error = '您所选的收货地址无法送达，请重新选择！';
      return false;
    }  
    $data['ship_uname'] = $receiver['name'];
    $data['ship_mobile'] = $receiver['mobile'];
    $data['ship_phone'] = $receiver['phone'];
    $data['ship_province'] = $receiver['province'];
    $data['ship_district'] = $receiver['district'];
    $data['ship_city'] = $receiver['city'];
    $data['ship_area'] = $receiver['area'];
    $data['ship_address'] = $receiver['address'];
    $data['ship_zipcode'] = $receiver['zipcode'];
    
    $order_ship_data = $data;
    $order_ship_data['payment_id'] = $payment_id;
    D('OrderShip')->update($order_ship_data);
    return true;
  }
  
  /**
  * 下单来源
  * 订单来源终端：1-PC，2-手机触屏，3-微信，4-平板，5-iOS，6-Android
  * @author Justin <justin@jipu.com>
  */
  protected function _getOrderFrom(){
    $order_from = 1;
    if(is_mobile()){
      $order_from = is_weixin() ? 3 : 2;
    }
    return $order_from;
  }
  
}
