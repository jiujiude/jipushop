<?php
/**
 * 后台收款单控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Think\Page;

class PaymentController extends AdminController {

  /**
   * 收款单列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index($keywords = null,$payment_type = null){
    //实例化收款单模型
    $Payment = M('Payment');

    //查询条件初始化
    $where = array();

    //查询条件：支付编号或订单编号
    if($keywords){
      $payment_order=M('Order')->where(array('order_sn'=>array('LIKE','%'.$keywords.'%')))->field('payment_id')->select();
      $payment_ids=array_column($payment_order,'payment_id');
      if(!$payment_ids)$payment_ids=array(0);
      $where['_string'] = '(payment_sn like "%'.$keywords.'%") OR (id in ('.implode(',',$payment_ids).'))';
    }
    if($payment_type){
      $where['payment_type'] = $payment_type;
    }
    $where['payment_status'] = 1;
    //按条件查询结果并分页
    $list = $this->lists($Payment, $where, 'id desc');
    int_to_string($list, array('payment_type' => get_payment_type_text()));

    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->assign('payment_type_array', $list);
    $this->assign('list', $list);
    $this->assign('payment_type', get_payment_type_text());
    $this->meta_title = '收款单列表';
    $this->display();
  }

  /**
   * 查看收款单
   * @author Max.Yu <max@jipu.com>
   */
  public function view($id = 0){
    /* 获取数据 */
    $info = array();
    $info = M('Payment')->find($id);
    $payment_type = $this->payment_type;
    $info['payment_type_text'] = !is_array(get_payment_type_text($info['payment_type'])) ? get_payment_type_text($info['payment_type']) : '';
    if(false === $info){
      $this->error('数据获取错误');
    }

    //获取订单信息
    $this->order_lists = M('Order')->field('id, order_sn, create_time')->where(array('payment_id' => $id))->select();
    //获取订单明细
    $order_ids = array_column($this->order_lists, 'id');
    $orderItemInfo = get_order_item($order_ids);
    //获取订单统计信息
    $orderCount = count_order_item($order_ids);
    //模板输出变量赋值
    $this->assign('info', $info);
    $this->assign('orderItemInfo', $orderItemInfo);
    $this->assign('orderCount', $orderCount);
    $this->meta_title = '收款单详情';
    $this->display();
  }
  
  /**
  * 去掉重复
  * @version 2015073015
  * @author Justin
  */
  function removeDuplicate(){
    R('Update/removeDuplicate', array('jipushop_payment', 'payment_sn')) && $this->success('去重成功！', Cookie('__forward__'));
  }
  
  /**
   * 收款单预览
   * @version 2015090816
   * @author Max.Yu <max@jipu.com>
   */
  public function preview($payment_id = 0){
    $data = M('Payment')->getById($payment_id);
    $data['payment_item_price'] = sprintf('%.2f', get_count_payment_price($data['id'])); //商品总价格
    $data['payment_delivery'] = sprintf('%.2f', get_count_payment_price($data['id'], 'delivery_fee')); //运费总价格
    $data['system_discount'] = sprintf('%.2f', $data['payment_item_price'] + $data['payment_delivery'] - $data['payment_amount'] - $data['finance_amount'] - $data['score_amount'] - $data['manjian'] - $data['coupon_amount'] - $data['card_amount']); //平台优惠金额
    $data['delivery_price'] = get_count_payment_price($data['id'], 'delivery_fee');
    $this->assign('data', $data);
    $this->display();
  }
  
}
