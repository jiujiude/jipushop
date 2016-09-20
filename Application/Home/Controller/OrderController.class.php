<?php
/**
 * 前台订单控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class OrderController extends HomeController{

  private $Order;

  public function _initialize(){
    parent::_initialize();
    //跳过验证登录
    $jump_loginlist = array('info');
    if(!in_array(ACTION_NAME, $jump_loginlist)){
      parent::login();
    }
    //实例化订单模型
    $this->Order = D('Order');
  }

  /**
   * 订单首页
   * @param $item_ids 商品id，逗号分隔
   * @param $buynow 是否立即购买
   * @author Max.Yu <max@jipu.com>
   */
  public function index($item_ids = null, $buynow = 0){
    //购物车cookie加入
    $cart = cookie('__cart__');
    if($cart){
      D('Cart')->addCartCookie();
    }
    $map['uid'] = UID;
    if($item_ids){
       //TODO:去除秒杀产品
        $map['item_id'] = array('IN', $item_ids);
    }
    $data = array();

    if($buynow == 1){
      //直接购买
      $buynow_cookie = cookie('__buynow__');
      if(empty($buynow_cookie)){
        redirect(U('Member/index'));
      }
      $data['items'] = array(unserialize($buynow_cookie));
      $seckill = A('Seckill', 'Event')->getInfo($data['items'][0]['item_id'],$data['items'][0]['item_code']);
      //属于秒杀商品
      if($seckill){
          $this->error('秒杀系统升级中……');
          if($seckill['stime'] > NOW_TIME){
              $this->error('活动未开始');
          }
          if($seckill['etime'] <= NOW_TIME){
              $this->error('活动已结束');
          }
          if($seckill['item_stock'] < $data['items'][0]['quantity']){
              $this->error('库存不足');
          }
          if( $data['items'][0]['quantity'] > $seckill['quota_num']){
              $this->error('活动限购'.$seckill['quota_num'].'件商品');
          }
          R('Seckill/order');
          exit();
      }
    }else{
      //购物车下单
      $field = 'item_id, supplier_id, item_code, name, number, spec, price, thumb, quantity, weight';
      $data['items'] = D('Cart')->lists($map, $field, 'field(item_id,'.$item_ids.')');
    }

    $item_ids_arr = get_sub_by_key($data['items'], 'item_id');
    $item_ids = arr2str($item_ids_arr);
    //商品按供应商分组
    if($data['items']){
      $data['items'] = D('Cart')->formatBySupplier($data['items']);
    }
    
    $order_event = A('Order', 'Event');
    //获取当前用户购物车统计信息
    $order_count = $order_event->doCount($item_ids, $buynow == 1);
    //满减信息
    $order_count['manjian'] = $order_event->getManjianRule();
    //获取购物车中属于第二件折扣的item_id(array)
    $second_pieces_item = $order_event->getSecondPiecesItemIds($data['items']);
    if($second_pieces_item){
      $second_pieces_item_ids = array_column($second_pieces_item, 'item_id');
      foreach($data['items'] as $k => $v){
        foreach($v['item'] as $key => $value){
          if(in_array($value['item_id'], $second_pieces_item_ids) && ($value['quantity'] >= 2)){
            $data['items'][$k]['item'][$key]['quantity'] = 2;
            $second_price = $data['items'][$k]['item'][$key]['price'] * $second_pieces_item[$data['items'][$k]['item'][$key]['item_id']]['discount'];
            $data['items'][$k]['item'][$key]['subAmount'] = sprintf("%.2f", $data['items'][$k]['item'][$key]['price'] + $second_price);
            $data['items'][$k]['item'][$key]['discount_name'] = $second_pieces_item[$data['items'][$k]['item'][$key]['item_id']]['name'];
          }
        }
      }
    }

    //商品列表为空，跳回购物车
    if(empty($data['items'])){
      $this->redirect('Cart/index');
    }

    //获取当前用户收货地址信息
    $receiver = D('Receiver')->lists($map);
    //获取默认收货地址
    $receiver_id = I('get.receiver_id');
    if($receiver_id){
      $data['default_receiver'] = D('Receiver')->detail(array('id' => $receiver_id));
    }else{
      $dmap = array(
        'uid' => UID,
        'is_default' => 1,
      );
      $has_default = D('Receiver')->detail($dmap);
      $data['default_receiver'] = $has_default ? $has_default : $receiver[0];
    }

    //获取当前商品可用优惠券
    $coupon_map = array(
      'uid' => UID,
      'status' => 0,
    );
    $coupons = D('CouponUser')->lists($coupon_map);
    if($coupons){
      foreach($coupons as $key => &$value){
        //判断优惠券是否存在和过期
        if(!$value['Coupon'] || $value['Coupon']['is_expire'] == 1){
          unset($coupons[$key]);
        }
        //判断订单总额是否满足使用条件
        if($value['Coupon']['norm'] > 0 && $order_count['total_price'] < $value['Coupon']['norm']){
          unset($coupons[$key]);
        }
        //判断购物车商品id和优惠券可用商品id是否有交集
        if($value['Coupon']['items']){
          $coupon_items_arr = explode(',', $value['Coupon']['items']);
          if(!array_intersect($coupon_items_arr, $item_ids_arr)){
            unset($coupons[$key]);
          }
        }
      }
    }

    //获取当前用户所有可用礼品卡
    $card_map = array(
      'uid' => UID,
    );
    $cards = D('CardUser')->lists($card_map);
    if($cards){
      foreach($cards as $key => &$value){
        //判断礼品卡是否过期
        if($value['Card']['is_expire'] == 1){
          unset($cards[$key]);
        }
        if($value['Card']['balance'] <= 0){
          //去掉余额不足的礼品卡
          unset($cards[$key]);
        }
      }
    }
    //发票类型
    $invoiceType = C('INVOICE_TYPE');
    //发票内容
    $invoiceContent = C('INVOICE_CONTENT');
    $data['coupons']['lists'] = $coupons;
    $data['coupons']['total'] = count($data['coupons']['lists']);
    $data['cards']['lists'] = $cards;
    $data['cards']['total'] = count($data['cards']['lists']);
    $this->item_ids = $item_ids;
    $this->receiver = $receiver;
    $this->order_count = $order_count;
    $this->data = $data;
    $this->invoiceType = $invoiceType;
    $this->invoiceContent = $invoiceContent;
    $this->buynow = $buynow;
    $this->meta_title = '确认订单';
    $this->display();
  }

  /**
   * 订单明细
   * @author Max.Yu <max@jipu.com>
   */
  public function detail(){
    $map['uid'] = UID;
    $map['order_sn'] = I('get.order_sn');
    $data = $this->Order->detail($map);
    if(empty($data['items'])){
      $this->error('订单不存在！');
    }
    if($data['reship_info']){
      $data['reship_info_text'] = json_decode($data['reship_info'], true);
    }
    if(!empty($data['invoice_title'])){
      $tempdata = explode('&nbsp',$data['invoice_title']);
      $data['invoice_des']['发票类型']   = $tempdata[0];
      switch ($tempdata[0]) {
        case '增值税发票':
          $data['invoice_des']['单位名称']     = $tempdata[1];
          $data['invoice_des']['纳税人识别码'] = $tempdata[2];
          $data['invoice_des']['注册地址']=$tempdata[3];
          $data['invoice_des']['注册电话']=$tempdata[4];
          $data['invoice_des']['开户银行']=$tempdata[5];
          $data['invoice_des']['银行账户']=$tempdata[6];
          $data['invoice_des']['发票内容']=$tempdata[7];
          break;
        default:
          $data['invoice_des']['发票抬头']  = $tempdata[1];
          $data['invoice_des']['发票内容']  = $tempdata[2];
          break;
      }
    }
    if($data['o_status'] == 0){
      $data['memo'] = handlememo($data['memo']);
    }else{
      if(!empty($data['memo'])){
        $name = $data['supplier_ids'] == 0 ? C('WEB_SITE_TITLE') : get_supplier_text($data['supplier_ids']);
        $temp = $data['memo'];unset($data['memo']);
        $data['memo'][$name] = $temp;
      } 
    }
    $this->data = $data;
    $this->meta_title = '订单详情';
    $this->display();
  }

  /**
   * 订单预览
   * @param $order_sn 订单编号
   * @param $order_id 订单ID
   * @author Max.Yu <max@jipu.com>
   */
  public function preview($order_sn = '', $order_id = ''){
    if(empty($order_sn) && empty($order_id)){
      $this->error('订单编号不能为空！');
    }
    if($order_sn){
      $map['order_sn'] = $order_sn;
    }
    if($order_id){
      $map['id'] = $order_id;
    }

    $data = $this->Order->detail($map);
    $order = R('Pay/checkOrder');
    if(empty($data)){
      $this->error('订单不存在！');
    }
    if($data['o_status'] != 0){
      $this->error('该订单不可付款！');
    }
    if(!empty($data['invoice_title'] )){
      $tempdata = explode('&nbsp',$data['invoice_title']);
      $data['invoice_des']['发票类型']   = $tempdata[0];
      switch ($tempdata[0]) {
        case '增值税发票':
          $data['invoice_des']['单位名称']     = $tempdata[1];
          $data['invoice_des']['纳税人识别码'] = $tempdata[2];
          $data['invoice_des']['注册地址']=$tempdata[3];
          $data['invoice_des']['注册电话']=$tempdata[4];
          $data['invoice_des']['开户银行']=$tempdata[5];
          $data['invoice_des']['银行账户']=$tempdata[6];
          $data['invoice_des']['发票内容']=$tempdata[7];
          break;
        default:
          $data['invoice_des']['发票抬头']  = $tempdata[1];
          $data['invoice_des']['发票内容']  = $tempdata[2];
          break;
      }
    }
    if(is_weixin()){
      $this->assign('weixin',1);
    }
    $data['memo'] = handlememo($data['memo']);
    $this->data = $data;
    $this->meta_title = '订单已生成';
    $this->display();
  }
  /**
   * 支付成功后提示信息
   * @author Max.Yu <max@jipu.com>
   */
  public function info(){
    $order_id = I('get.order_id');
    $order_sn = I('get.order_sn');
    $order_type = I('get.order_type', 'item_order');

    //众筹订单跳转到doing
    if($order_type == 'crowdfunding_order'){
      if($order_id){
        $map['id'] = $order_id;
      }elseif($order_sn){
        $map['pay_id'] = $order_sn;
      }else{
        $map[] = '1=0';
      }
      $c_line = M('CrowdfundingUsers')->where($map)->find();
      if($c_line){
        $url = U('Crowdfunding/doing', array('id' => $c_line['crowdfunding_id'], 'oid' => $c_line['order_id']));
        redirect($url);
      }
      //红包订单跳转到doing
    }else if($order_type == 'redpacket_order'){
      if($order_id){
        $map['id'] = $order_id;
      }elseif($order_sn){
        $map['order_sn'] = $order_sn;
      }else{
        $map[] = '1=0';
      }
      $c_line = M('Redpacket')->where($map)->find();
      if($c_line){
        $url = U('Redpacket/send', array('id' => $c_line['id']));
        redirect($url);
      }
    //拼团订单
    }else if($order_type == 'join_order'){
        $url = U('Join/joinOrder');
        redirect($url);
    }
    $order_id ? $map['id'] = $order_id : $map['order_sn'] = $order_sn;
    //判断订单状态
    if(M('Order')->where($map)->getField('o_status') !== '200'){
      $this->error('订单支付失败，钱已充入余额！', U('Member/order'));
    }
    $this->display();
  }

  /**
   * 提交订单
   * @author Max.Yu <max@jipu.com>
   * @version 2015083115 Justin Rebuild
   */ 
  public function add(){
    //用户扫描带参数的二维码ID
    $union_id = $this->user['from_union_id'];
    $union_id && ($data['union_id'] = $union_id) && ($data = array_merge($data, $_POST));
    $result = $this->Order->update($data, I('post.buynow') == 1);
    if($result){
      $this->success('订单已生成，请付款', U('Order/preview', array('order_id' => $result)));
    }else{
      $this->error($this->Order->getError() ? $this->Order->getError() : '订单提交失败！', U('/'));
    }
  }

  /**
   * 提交订单
   * @author Max.Yu <max@jipu.com>
   */
  public function submit(){
    $result = false;
    $id = I('id');
    $quantity = intval(I('quantity'));
    if(empty($id)){
      $result = array(
        'status' => 'error',
      );
    }

    $add = $this->Cart->addCart($id, $quantity, UID);
    if($add){
      $result = array(
        'status' => 'success',
      );
    }else{
      $result = array(
        'status' => 'error',
        'info' => $info,
      );
    }
    $this->ajaxReturn($result);
  }

  /**
   * 修改订单
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    $id = intval(I('request.id'));
    $data['quantity'] = intval(I('quantity'));
    $data['uid'] = UID;
    $update = $this->Cart->updateCart($data);
    $total = $this->Cart->doCount();
    if($update){
      $result = array(
        'status' => 'success',
        'total' => $total,
      );
    }else{
      $result = array(
        'status' => 'error',
        'total' => $total,
      );
    }
    $this->ajaxReturn($result);
  }

  /**
   * 设置订单状态
   * @author Justin <justin@jipu.com>
   */
  public function setStatus($order_id = null, $type = 'cancel'){
    if(IS_POST){
      if(empty($order_id)){
        $this->error('订单ID不能为空！');
      }
      $text = array(
        'cancel' => '取消',
        'recycle' => '删除',
        'delete' => '删除',
        'restore' => '还原'
      );
      switch($type){
        case 'cancel' :
          $res = M('Order')->where(array('id' => $order_id, 'uid' => UID))->setField('o_status', -1);
          break;
        case 'recycle' :
          $new_status = 2;
          break;
        case 'delete' :
          $new_status = 3;
          break;
        case 'restore' :
          $new_status = 1;
          break;
      }

      $new_status && $res = M('Order')->where(array('id' => $order_id, 'uid' => UID))->setField('status', $new_status);
      if($res){
        $this->success('订单'.$text[$type].'成功！');
      }else{
        $this->error('订单'.($text[$type] ? $text[$type] : '处理').'失败！');
      }
    }else{
      $this->error('非法请求');
    }
  }

  /**
   * 确认收货
   * @author Max.Yu <max@jipu.com>
   */
  public function confirm(){
    $order_id = I('post.order_id');
    $order = M('Order')->where(array('id' => $order_id, 'uid' => UID))->find();
    if(empty($order)){
      $this->error('订单不存在');
    }
    //已发货，未确认收货
    if($order['o_status'] == 201){
      $save_data = array(
        'o_status' => 202,
        'complete_time' => NOW_TIME,
      );
      $res = M('Order')->where('id='.$order['id'])->save($save_data);
      if($res){
        if( C('DIS_ORDERSTATUS') == 0){
          distribute($order_id ,UID);
        }
        $this->success('确认收货成功');
      }else{
        $this->error('确认收货失败');
      }
    }else{
      $this->error('当前订单状态，不允许确认收货');
    }
  }

  /**
   * 申请退款
   * @author Max.Yu <max@jipu.com>
   */
  public function refund(){
    $order_id = I('post.order_id');
    $order = M('Order')->where(array('id' => $order_id, 'uid' => UID))->find();
    if(empty($order)){
      $this->error('订单不存在');
    }
    //未发货、已发货、已收货都可以申请退款
    if(in_array($order['o_status'], array(200, 201, 202))){
      if($order['o_status'] == 202 && ($order['complete_time'] < (NOW_TIME - 7 * 24 * 3600))){
        $this->error('订单已超过退款期限');
      }
      $save_data = array(
        'o_status' => 300,
      );
      $res = M('Order')->where('id='.$order['id'])->save($save_data);
      if($res){
        $this->success('申请成功，待管理员处理');
      }else{
        $this->error('申请失败');
      }
    }else{
      $this->error('当前订单状态，不允许申请退款');
    }
  }

  /**
   * 取消退款申请
   */
  public function unrefund(){
    $order_id = I('post.order_id');
    $order = M('Order')->where(array('id' => $order_id, 'uid' => UID))->find();
    if(empty($order)){
      $this->error('订单不存在');
    }
    if($order['o_status'] == 300){
      $to_status = 0;
      //已支付
      $order['payment_time'] > 0 && $to_status = 200;
      //已发货
      $order['shipping_time'] > 0 && $to_status = 201;
      //已完成
      $order['complete_time'] > 0 && $to_status = 202;

      //处理业务操作
      $res = M('Order')->where('id='.$order['id'])->setField('o_status', $to_status);
      if($res){
        $this->success('退款申请已取消');
      }else{
        $this->error('操作失败了！');
      }
    }else{
      $this->error('错误的状态操作！');
    }
  }

  /**
   * 退款物流
   * @author Max.Yu <max@jipu.com>
   */
  public function reShip(){
    $order_id = I('order_id');
    $order = M('Order')->where(array('id' => $order_id, 'uid' => UID))->find();
    if(empty($order)){
      die('订单不存在');
    }
    if($order['o_status'] == 301){
      //退货信息保存
      if(IS_POST){
        $company_name = I('post.company_name', '', trim);
        $ship_number = I('post.ship_number', '', trim);
        empty($company_name) && $this->error('请输入快递公司名称！');
        empty($ship_number) && $this->error('请输入快递单号！');
        $reship_info = array(
          'company_name' => $company_name,
          'ship_number' => $ship_number,
          'reship_time' => time()
        );
        $save_data = array(
          'o_status' => 302,
          'reship_info' => json_encode($reship_info),
        );
        $res = M('Order')->where('id='.$order['id'])->save($save_data);
        if($res){
          $this->success('保存成功，待管理员退款', U('Order/detail', array('order_sn' => $order['order_sn'])));
        }else{
          $this->error('保存失败');
        }
      }else{
        $this->order = $order;
        $this->meta_title = '退货物流';
        $this->display();
      }
    }else{
      if($order['reship_info']){
        $info_arr = json_decode($order['reship_info'], true);
        $this->data = $info_arr;
        $this->display('reShipDelivery');
      }else{
        die('当前订单状态，不允许填写退款信息');
      }
    }
  }

  /**
   * 获取物流动态信息
   * @author Max.Yu <max@jipu.com>
   */
  public function getDelivery($order_id = 0){
    $data = M('Ship')->getByOrderId($order_id);
    $this->data = $data;
    $this->display();
  }

}
