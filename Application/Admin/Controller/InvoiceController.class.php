<?php
/**
 * 后台发票申请单控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Think\Page;

class InvoiceController extends AdminController{

  /**
   * 发票申请单列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index($uid = null, $invoice_status = null, $express_status = null, $keywords = null){

    //获取发票记录列表
    $list = $this->pagelist($uid, $invoice_status, $express_status, $keywords);

    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->assign('list', $list);
    $this->assign('invoice_status', $invoice_status);
    $this->assign('express_status', $express_status);
    $this->meta_title = '开票记录';
    $this->display();
  }

  /**
   * 输出开票记录列表
   * @author Max.Yu <max@jipu.com>
   */
  public function pagelist($uid = null, $invoice_status = null, $express_status = null, $keywords = null){
    //实例化发票申请单模型
    $Invoice = M('Invoice');

    //定义查询条件
    $where = array();

    //查询条件：用户ID
    if(isset($uid)){
      $where['uid'] = $uid;
    }

    //查询条件：发票状态
    if(isset($invoice_status)){
      $where['invoice_status'] = $invoice_status;
    }

    //查询条件：发件状态
    if(isset($express_status)){
      $where['express_status'] = $express_status;
    }

    //搜索关键词
    if(isset($keywords)){
      $where['_string'] = '(invoice_sn like "%'.$keywords.'%")  OR (invoice_title like "%'.$keywords.'")';
    }

    //按条件查询结果并分页
    $list = $this->lists($Invoice, $where, 'id desc');
    $intToStringMap = array(
      'invoice_status' => array(0 => '<font color="#ff0000">未出票</font>', 1 => '<font color="#86DB00">已出票</font>'),
      'express_status' => array(0 => '<font color="#ff0000">未发件</font>', 1 => '<font color="#86DB00">已发件</font>', 2 => '<font color="#86DB00">已收件</font>'),
    );
    int_to_string($list, $intToStringMap);

    return $list;
  }

  /**
   * 发票申请-待开票订单列表（默认按订单申请开票）
   * @author Max.Yu <max@jipu.com>
   */
  public function apply($uid = null, $keywords = null){
    /* 实例化订单模型 */
    $Order = M('Order');

    //定义查询条件
    $where = array();

    //查询条件：用户ID
    $where['uid'] = $uid;

    //查询条件：订单状态（已支付200，待收货201，已完成202）
    $where['o_status'] = array('IN', array(200, 201, 202));

    //查询条件：发票状态
    $where['invoice_status'] = 0;

    if(isset($keywords)){
      $where['order_sn'] = array('like', '%'.$keywords.'%');
    }

    //按条件查询结果并分页
    $list = $this->lists($Order, $where, 'id desc');
    $intToStringMap = array(
      'o_status' => array(
        0 => '<span class="text-warning">待付款</span>',
        -1 => '<span class="text-cancel">交易取消</span>',
        200 => '<span class="text-success">已付款</span>',
        201 => '<span>已发货，待买家收货</span>',
        202 => '<span class="text-success">交易成功</span>',
        300 => '<span class="text-danger">申请退款</span>',
        301 => '<span class="text-danger">待买家退货</span>',
        302 => '<span class="text-danger">已退货</span>',
        303 => '<span class="text-cancel">退款成功，交易关闭</span>',
        404 => '<span class="text-cancel">系统取消订单</span>',
      ),
      'invoice_status' => array(0 => '<font color="#ff0000">未申请</font>', 1 => '<font color="#ff6600">出票中</font>', 2 => '<font color="#86DB00">已出票</font>'),
    );
    int_to_string($list, $intToStringMap);

    //获取待开票订单统计信息
    $orderCount = $this->orderCount($where);

    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->assign('list', $list);
    $this->assign('orderCount', $orderCount);
    $this->meta_title = '待开票订单列表';
    $this->display();
  }

  /**
   * 新增发票申请单
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    $orderids = I('request.orderids');

    if(empty($orderids)){
      $this->error('请勾选需要开票的订单!');
    }

    $where['id'] = array('in', $orderids);

    //实例化开票资质模型
    $InvoiceTitle = M('InvoiceTitle');

    //查询条件初始化
    $invoiceTitleWhere['user_id'] = UID;
    $invoiceTitleWhere['check_status'] = 1;

    //获取收货地址列表
    $invoiceTitleList = $InvoiceTitle->where($invoiceTitleWhere)->order('id desc')->select();

    //实例化收货地址模型
    $Receiver = M('Receiver');

    //查询条件初始化
    $receiverWhere['user_id'] = UID;

    //获取收货地址列表
    $receiverList = $Receiver->where($receiverWhere)->order('id desc')->select();

    //获取当前开票订单统计信息
    $orderCount = $this->orderCount($where);

    //获取当前开票订单信息
    $list = M('order')->where($where)->order('id desc')->select();

    //模板输出变量赋值
    $this->assign('orderids', is_array($orderids) ? arr2str($orderids) : $orderids);

    $this->assign('invoiceTitleList', $invoiceTitleList);
    $this->assign('receiverList', $receiverList);
    $this->assign('orderCount', $orderCount);
    $this->assign('list', $list);
    $this->meta_title = '核对开票信息';
    $this->display();
  }

  /**
   * 保存新增发票申请单
   * @author Max.Yu <max@jipu.com>
   */
  public function saveAdd(){
    if(IS_POST){
      $invoiceInfo['invoice_sn'] = build_sn();
      $invoiceInfo['user_id'] = UID;

      /* 获取收件人信息 */
      $receiverid = I('post.receiverid');
      $receiverInfo = M('Receiver')->field(true)->find($receiverid);

      if(false === $receiverInfo){
        $this->error('获取收货人信息信息错误');
      }else{//设置收货人信息
        $invoiceInfo['recipient_name'] = $receiverInfo['name'];
        $invoiceInfo['recipient_mobile'] = $receiverInfo['mobile'];
        $invoiceInfo['recipient_phone'] = $receiverInfo['phone'];
        $invoiceInfo['recipient_addr'] = $receiverInfo['area'].$receiverInfo['address'];
        $invoiceInfo['recipient_zipcode'] = $receiverInfo['zipcode'];
      }

      /* 设置发票类型、发票抬头、发票金额 */
      $invoiceInfo['invoice_type'] = I('post.invoice_type');
      $invoiceInfo['invoice_title'] = I('post.invoice_title');
      $invoiceInfo['invoice_amount'] = I('post.invoice_amount');

      $orderids = I('post.orderids');
      if(empty($orderids)){
        $this->error('发票申请提交失败!');
      }else{
        $invoiceInfo['order_ids'] = $orderids;
      }

      /* 设置申请时间 */
      $invoiceInfo['create_time'] = NOW_TIME;
      $invoiceInfo['update_time'] = NOW_TIME;

      $Invoice = M('Invoice');
      $id = $Invoice->add($invoiceInfo);

      if($id){
        // 实例化订单模型
        $Order = M('Order');

        $where['id'] = array('in', $orderids);

        //更新订单发票状态为：出票中
        $Order->where($where)->setField('invoice_status', 1);

        //记录行为
        //action_log('add_invoice', 'Invoice', $id, UID);
        $this->success('发票申请提交成功', U('myinvoice'));
      }else{
        $this->error('发票申请提交失败');
      }
    }else{
      $this->error('访问出错，请联系系统管理员。');
    }
  }

  /**
   * 编辑发票申请单
   * @author Max.Yu <max@jipu.com>
   */
  public function edit($id = 0){
    if(IS_POST){
      $Invoice = D('Invoice');
      $data = $Invoice->create();
      if($data){
        if($Invoice->save() !== false){
          // 实例化订单模型
          $Order = M('Order');

          $where['id'] = array('in', I('post.orderids'));

          //更新订单发票状态为：已出票
          $Order->where($where)->setField('invoice_status', 2);

          //记录行为
          //action_log('update_Invoice', '$Invoice', $data['id'], UID);
          $this->success('更新成功', Cookie('__forward__'));
        }else{
          $this->error('更新失败');
        }
      }else{
        $this->error($Invoice->getError());
      }
    }else{
      $info = array();

      /* 获取数据 */
      $info = M('Invoice')->find($id);

      if(false === $info){
        $this->error('获取发票申请单信息错误');
      }
      $this->assign('info', $info);
      $this->meta_title = '编辑发票申请单';
      $this->display();
    }
  }

  /**
   * 查看发票申请单
   * @author Max.Yu <max@jipu.com>
   */
  public function view($id = 0){
    $info = array();

    //获取发票申请单信息
    $info = M('Invoice')->find($id);
    if(false === $info){
      $this->error('获取订单信息错误');
    }

    $where['id'] = array('in', $info['order_ids']);

    //获取当前开票订单统计信息
    $orderCount = $this->orderCount($where);

    //获取当前开票订单信息
    $list = M('order')->where($where)->order('id desc')->select();

    //模板输出变量赋值
    $this->assign('info', $info);
    $this->assign('orderCount', $orderCount);
    $this->assign('list', $list);
    $this->meta_title = '查看/处理发票申请单';
    $this->display();
  }

  /**
   * 删除发票申请单（物理删除）
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');

    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }

    $map['id'] = array('in', $ids);
    if(M('Invoice')->where($map)->delete()){
      //记录行为
      //action_log('delete_Invoice', 'Invoice', $id, UID);
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }

  /**
   * 统计订单商品总数量（totalQuantity），商品总金额（totalAmount）
   * @author Max.Yu <max@jipu.com>
   */
  private function orderCount($where = null){
    //初始化统计信息返回值
    $returnData = array('totalQuantity' => 0, 'totalAmount' => 0.00);

    //实例化订单模型
    $Order = M('order');

    //定义返回或者操作的字段
    $field = 'SUM(total_quantity) AS totalQuantity, SUM(total_amount) AS totalAmount';

    //实例化数据操作模型
    $data = $Order->where($where)->field($field)->select();

    if($data[0]['totalQuantity']){
      $returnData['totalQuantity'] = $data[0]['totalQuantity'];
      $returnData['totalAmount'] = $data[0]['totalAmount'];
    }
    return $returnData;
  }

}
