<?php
/**
 * 前台购物车控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class CartController extends HomeController{

  private $cart_model;

  public function _initialize(){
    parent::_initialize();
    //实例化购物车模型
    $this->cart_model = D('Cart');
  }

  /**
   * 购物车首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //记录当前页面Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //获取用户购物车信息
    if(!is_login()){
      //从cookie获取购物车商品
      $list = json_decode(cookie('__cart__'), true);
      if($list){
        foreach($list as $key => &$value){
          if($value['thumb']){
            //规格图片
            $pic = get_cover(M('PropertyOption')->getFieldByCode($value['item_code'], 'pic'), 'path');
            //不存在则为封面图片
            $value['cover_path'] = $pic ? $pic : get_cover($value['thumb'], 'path');
            $value['subAmount'] = sprintf("%.2f", $value['quantity'] * $value['price']);
            $value['amount'] = $value['quantity'] * $value['price'];
            $value['stock'] = get_item_stock($value['item_id'], $value['item_code']);
          }
        }
      }
      $cart_count = $this->cart_model->doCountCookie();
    }else{
      //将cookie中的商品加入购物车
      $this->cart_model->addCartCookie();

      $map['uid'] = UID;
      $list = $this->cart_model->lists($map);
      //获取当前用户购物车统计信息
      $cart_count = $this->cart_model->doCount();
    }
    $list = $this->cart_model->formatBySupplier($list);
    //获取购物车中属于第二件折扣的item_id(array)
    $second_pieces_item = A('Order', 'Event')->getSecondPiecesItemIds($list);
    $second_pieces_item && $this->second_pieces_item = json_encode($second_pieces_item);
    $this->assign('cart_count', $cart_count);
    $this->assign('list', $list);
    $this->meta_title = '购物车';
    $this->display();
  }

  /**
   * 获取前端用户选择的规格数组数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getSpecifiction(){
    $specifiction_key = I('key');
    $specifiction_val = I('val');
    $specifiction_code = I('code');
    $specifiction = array();

    foreach($specifiction_code as $code){
      $specifiction[$code]['key'] = $specifiction_key[$code];
      $specifiction[$code]['val'] = $specifiction_val[$code];
    }

    return $specifiction;
  }

  /**
   * 商品加入购物车
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    //判断用户是否登录，未登录则放入cookie
    $item_code = I('item_code');
    $code = I('code');
    $item_id = I('item_id');
    $quantity = intval(I('quantity'));
    $price = I('price');
    $buynow = I('buynow', 0);
    $sdp_code = I('sdp_code','');
    $specifiction = $this->getSpecifiction();
    
    //立即购买
    if($buynow == 1){
      //限购数量
      $quota_num = get_quota_num($item_id);
      if($quota_num < $quantity){
        $this->error('超出限购数量');
      }

      $item = D('Item')->detail($item_id);
      $buynow_data = array(
        'item_id' => $item_id,
        'item_code' => $item_code,
        'code' => $code,
        'sdp_code' => $sdp_code,
        'supplier_id' => $item['supplier_id'],
        'name' => $item['name'],
        'number' => $item['number'],
        'spec' => $specifiction,
        'price' => $price,
        'thumb' => $item['thumb'],
        'quantity' => $quantity,
        'weight' => $item['weight'],
        'cover_path' => get_cover($item['thumb'], 'path'),
        'subAmount' => sprintf("%.2f", $quantity * $price),
        //库存
        'stock' => get_item_stock($item_id, $item_code),
      );
      Cookie('__buynow__', serialize($buynow_data));
      //立即购买 跳转到下单页面
      Cookie('__forward__', U('Order/index', array('buynow' => 1)));
      $result = array(
        'status' => 1,
        'total' => $quantity
      );
      //加入购物车
    }else{
      $data['seckill'] = A('Item', 'Event')->getSeckill($item_id);
      //属于秒杀商品
      if($data['seckill'] && ( ($data['seckill']['start_time'] > NOW_TIME) || ($data['seckill']['expire_time'] < NOW_TIME) )){
        $this->error('活动尚未开始或者已过期!');
      }
      //未登录，放入cookie
      if(!is_login()){
        if($item_id){
          $field = 'id, number, name, price, weight, thumb, supplier_id';
          $item = D('Item')->detail($item_id, $field);
          $data = array(
            'item_code' => $item_code,
            'item_id' => $item_id,
            'supplier_id' => $item['supplier_id'],
            'number' => $item['number'],
            'spec' => $specifiction,
            'weight' => $item['weight'],
            'price' => $price,
            'thumb' => $item['thumb'],
            'quantity' => $quantity,
            'sdp_code' => $sdp_code
          );
        }
        $current_items = json_decode(cookie('__cart__'), true);
        //限购数量
        $quota_num = get_quota_num($item_id);
        $exists_num = 0;
        foreach($current_items as $v){
          if($v['item_id'] == $item_id){
            $exists_num += $v['quantity'];
          }
        }
        //限购判断
        if(($exists_num + $quantity) > $quota_num){
          $result = array(
            'status' => 0,
            'info' => '超出限购数量'
          );
        }else{

          if($current_items){
            $current_item_codes = get_sub_by_key($current_items, 'item_code');
            if(in_array($item_code, $current_item_codes)){
              foreach($current_items as $key => &$value){
                if($value['item_code'] == $item_code){
                  $value['quantity']+= $quantity;
                }
              }
            }else{
              if(count($current_items) >= 10){
                $this->ajaxReturn(array('status' => 0, 'info' => '临时购物车已满。请登录！'));
              }
              array_push($current_items, $data);
            }
            cookie('__cart__', json_encode($current_items));
          }else{
            $items[] = $data;
            cookie('__cart__', json_encode($items));
          }
          $total = $this->cart_model->doCountCookie();
          $result = array(
            'status' => 1,
            'total' => $total
          );
        }
        //登录后放入数据库
      }else{
        $result = false;
        if(empty($item_code)){
          $result = array(
            'status' => 0
          );
        }
        $add = $this->cart_model->addCart($item_code, $item_id, $quantity, $price, $specifiction, UID , $sdp_code);
        if($add){
          $total = $this->cart_model->doCount();
          $result = array(
            'status' => 1,
            'total' => $total
          );
        }else{
          $result = array(
            'status' => 0,
            'info' => $this->cart_model->getError()
          );
        }
      }
    }
    $this->ajaxReturn($result);
  }

  /**
   * 更新购物车
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    $item_code = I('item_code');
    $data['quantity'] = intval(I('quantity'));
    //用户是否登录
    if(!is_login()){
      if(cookie('__cart__') !== null){
        $current_items = json_decode(cookie('__cart__'), true);
        foreach($current_items as $key => &$value){
          if($value['item_code'] == $item_code){
            $value['quantity'] = $data['quantity'];
          }
        }
        cookie('__cart__', json_encode($current_items));
        $total = $this->cart_model->doCountCookie();
        $result = array(
          'status' => 1,
          'total' => $total
        );
      }
    }else{
      $where['uid'] = UID;
      $where['item_code'] = $item_code;
      $update = $this->cart_model->updateCart($where, $data);
      $total = $this->cart_model->doCount();
      if($update){
        $result = array(
          'status' => 1,
          'total' => $total,
        );
      }else{
        $result = array(
          'status' => 0,
          'total' => $total,
        );
      }
    }
    $this->ajaxReturn($result);
  }

  /**
   * 选择删除购物车数据（物理删除）
   * @author Max.Yu <max@jipu.com>
   */
  public function remove(){
    $item_code = I('request.item_code');
    if(empty($item_code)){
      $result = array(
        'status' => 0,
        'info' => '请选择要删除的商品！'
      );
    }

    //用户是否登录
    if(!is_login()){
      if(cookie('__cart__') !== null){
        $current_items = json_decode(cookie('__cart__'), true);
        foreach($current_items as $key => &$value){
          if($value['item_code'] == $item_code){
            unset($current_items[$key]);
          }
        }
        cookie('__cart__', json_encode($current_items));
        $total = $this->cart_model->doCountCookie();
        $result = array(
          'status' => 1,
          'total' => $total
        );
      }
    }else{
      $map['uid'] = UID;
      $map['item_code'] = $item_code;
      if($this->cart_model->remove($map)){
        $total = $this->cart_model->doCount();
        $result = array(
          'status' => 1,
          'total' => $total,
          'info' => '删除成功！'
        );
      }else{
        $result = array(
          'status' => 0,
          'info' => '删除失败！'
        );
      }
    }
    $this->ajaxReturn($result);
  }

  /**
   * 清空当前登录用户的购物车
   * @author Max.Yu <max@jipu.com>
   */
  public function clear(){
    //用户是否登录
    if(!is_login()){
      cookie('__cart__', null);
      $result = array(
        'status' => 1,
        'info' => '清空购物车成功！'
      );
    }else{
      $map['uid'] = UID;
      if($this->cart_model->remove($map)){
        $result = array(
          'status' => 1,
          'info' => '清空购物车成功！'
        );
      }else{
        $result = array(
          'status' => 0,
          'info' => '清空购物车失败！'
        );
      }
    }
    $this->ajaxReturn($result);
  }

  /**
   * 获取赠品信息
   * @author Max.Yu <max@jipu.com>
   */
  public function getSendItem($buynow = 0){
    $items = array();
    if($buynow == 1){
      $buynow_cookie = cookie('__buynow__');
      $buynow_item = unserialize($buynow_cookie);
      $items = array($buynow_item);
    }else{
      if(is_login()){
        $items = M('Cart')->where('uid='.UID)->select();
      }else{
        $cookieCart = cookie('__cart__');
        if($cookieCart !== null){
          $items = unserialize($cookieCart);
        }
      }
    }
    $_arr = array();
    foreach($items as $vo){
      if(isset($_arr[$vo['item_id']])){
        $_arr[$vo['item_id']][1] += $vo['quantity'];
      }else{
        $_arr[$vo['item_id']] = array($vo['item_id'], $vo['quantity']);
      }
    }

    $send_item = A('Item', 'Event')->getSendItems($_arr);
    if(empty($send_item)){
      $this->success('没有赠品信息');
    }else{
      $this->ajaxReturn(array('status' => 1, 'send' => $send_item));
    }
  }

}
