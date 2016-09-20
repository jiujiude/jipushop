<?php
/**
 * 后台发货单控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class ShipController extends AdminController{

  /**
   * 发货单列表
   * @author Max.Yu <max@jipu.com>
   * @version 2015070111 Justin 
   */
  public function index($delivery_corp = null, $keywords = null){
    //查询条件初始化
    $where = array();
    $where['status'] = 1;
    //查询条件：物流公司
    if($delivery_corp){
      $where['delivery_corp'] = $delivery_corp;
    }
    if(IS_SUPPLIER){
      $where['supplier_id'] = UID;
    }
    //查询条件：发货编号、订单编号或物流单号
    if($keywords){
      $where['_string'] = '(order_id like "%'.$keywords.'%")  OR (delivery_sn like "%'.$keywords.'%")';
    }

    parent::index($where);
    
  }
   
  /**
   * 新增发货单
   * @author Max.Yu <max@jipu.com>
   */
  public function add($order_id = 0){
    if(empty($order_id)){
      $this->error('订单ID不能为空。');
    }
    //获取订单信息
    $order = M('Order')->find($order_id);
    if(empty($order)){
      $this->error('获取订单信息失败。');
    }
    if(!in_array($order['o_status'],array(200,405)) ){
      $this->error('当前状态不能发货！');
    }
    //获取订单项目信息
    $orderItemInfo = get_order_item($order_id);
    //获取物流信息
    $this->delivery = C('EXPRESS_LISTS');
    //获取收货人信息
    $this->ship = M('OrderShip')->getByPaymentId($order['payment_id']);
    //物流模板信息
    ($order['delivery_id'] > 0) && $order['delivery_tpl'] = M('DeliveryTpl')->getFieldById($order['delivery_id'], 'company');
    //模板输出变量赋值
    $this->assign('order', $order);
    $this->assign('orderItemInfo', $orderItemInfo);
    $this->meta_title = '新增发货单';
    $this->display();    
  }  

  /**
   * 查看发货单
   * @author Max.Yu <max@jipu.com>
   */
  public function view($id = 0){
    $info = array();

    /* 获取数据 */
    $info = M('Ship')->find($id);

    if(false === $info){
      $this->error('获取收货信息错误');
    }

    //获取订单信息
    $orderInfo = M('Order')->find($info['order_id']);
    $payment_type = $this->payment_type;
    $orderInfo['payment_type_text'] = $payment_type[$orderInfo['payment_type']];

    //获取订单明细
    $orderItemInfo = get_order_item($info['order_id']);

    //收货人信息
    $info['ship'] = M('OrderShip')->getByPaymentId(M('Order')->getFieldById($info['order_id'], 'payment_id'));
    //模板输出变量赋值
    $this->assign('info', $info);
    $this->assign('orderInfo', $orderInfo);
    $this->assign('orderItemInfo', $orderItemInfo);
    $this->meta_title = '查看发货单信息';
    $this->display();
  }
  
  /**
   * 数据升级
   */
  public function upgrade(){
    $_tmp = array();
    $delivery_list = M('DeliveryTpl')->field('id, company')->select();
    foreach($delivery_list as $v){
      $_tmp[$v['id']] = $v['company'];
    }
    $ship = M('Ship')->select();
    foreach($ship as $s){
      if(is_numeric($s['delivery_corp'])){
        $save_data = array(
          'delivery_type' => $_tmp[$s['delivery_corp']],
          'delivery_type_id' => $s['delivery_corp'],
        );
        M('Ship')->where('id='.$s['id'])->save($save_data);
      }
    }
  }
  
  
  /**
   * 设置一条或者多条数据的状态
   */
  public function setStatus(){
    $msg   = array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX);
    $ids = I('request.ids');
    $status = I('request.status');
    if(empty($ids)){
      $this->error('请选择要操作的数据');
    }
    $id    = is_array($ids) ? implode(',',$ids) : $ids;
    $map['id'] = array('in',$ids);
    
    $ship = M('Ship')->where($map)->find();
    $ship || $this->error($msg['error'],$msg['url'],$msg['ajax']);
    $data['status'] = $status ? : -1;
    
    if(M('Ship')->where($map)->save($data)!==false){
        //记录行为
        $model = CONTROLLER_NAME;
        action_log('update_'.$model, $model, $id, UID);
        //修改订单发货状态为已付款，待发货
        M('Order')->where(array('id'=>$ship['order_id']))->save(array('o_status'=>'200'));
        $this->success($msg['success'],$msg['url'],$msg['ajax']);
    }else{
        $this->error($msg['error'],$msg['url'],$msg['ajax']);
    }
  }

}
