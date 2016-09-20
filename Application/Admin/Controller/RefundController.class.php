<?php
/**
 * 后台退款控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class RefundController extends AdminController{

  private $refund_type = array(
    'item' => '购物订单',
    'redpacket' => '红包'
  );

  private $payment_type = array(
    'alipay' => '支付宝',
    'alipaywap' => '手机支付宝',
    'bankpay' => '网银支付',
    'wechatpay' => '微信支付',
    'crowdfunding' => '众筹支付'
  );

  /**
   * 退款首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    $where = array();
    // 获取退款列表
    $list = $this->lists('Refund', $where, 'id desc', null);
    $map = array(
      'refund_status' => array(
        0 => '<span class="text-danger">未处理</span>',
        1 => '<span class="text-success">已处理</span>'
      ),
      'refund_type' => $this->refund_type,
      'payment_type' => $this->payment_type
    );
    int_to_string($list, $map);

    // 获取支付宝批量退款订单列表
    $where['refund_status'] = 0;
    $where['payment_type'] = array('like', 'alipay%');
    $alipay_count = M('Refund')->where($where)->count();
    //记录当前列表页的Cookie
    Cookie('__forward__',$_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->assign('_list', $list);
    $this->assign('alipay_count', $alipay_count);
    $this->meta_title = '退款列表';
    $this->display();
  }

  /**
   * 支付宝批量退款处理
   */
  public function alipay($ids = null){
    // 获取支付宝批量退款订单列表，每次最多处理50条
    $where = array(
      'refund_status' => 0,
      'payment_type' => array('like', array('alipay%', 'bankpay%'), 'OR')
    );
    $alipay_orders = D('Refund')->lists($where, '', 'id', 50);
    if(empty($alipay_orders)){
      $this->error('暂无支付宝退款订单！');
    }
    $alipay_order_ids = get_sub_by_key($alipay_orders, 'id', '', ture);
    $this->alipayRefund($alipay_order_ids);
  }

  /**
   * 单次退款处理
   */
  public function deal($ids = null){
    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }
    if(IS_POST){
      $torefund = I('post.torefund', 0);
      if(empty($torefund)){
        $this->error('请确认退款操作');
      }else{
        $res = A('Refund', 'Event')->dealRefund($ids);
        if($res['status'] == 1){
          $this->success($res['info']);
        }else{
          $this->error($res['info']);
        }
      }
    }else{
      //退款数据
      $data = M('Refund')->find($ids);
      $data['order'] = M('Order')->find($data['order_id']);
      $data['payment_type_text'] = $this->payment_type[$data['payment_type']];
      $this->data = $data;
      $this->display();
    }
  }

  /**
   * 支付宝批量退款处理
   */
  private function alipayRefund($ids = null){
    if(empty($ids)){
      return false;
    }
    $limit = count(explode(',', $ids));
    // 生成退款详细数据
    $where['id'] = array('IN', $ids);
    $refund_lists = D('Refund')->lists($where, '', true, $limit);
    $refund_detail = array();
    if($refund_lists){
      foreach($refund_lists as $value){
        $detail = $refund_detail[$value['trade_no']];
        if(isset($detail)){
          $refund_detail[$value['trade_no']] = array(
            $value['trade_no'],
            sprintf('%.2f', $value['amount'] + $detail[1]),
            '协议退款'
          );
        }else{
          $refund_detail[$value['trade_no']] = array(
            $value['trade_no'],
            $value['amount'],
            '协议退款'
          );
        }
      }
      foreach($refund_detail as $k => $v){
        $refund_detail[$k] = $v[0].'^'.$v[1].'^'.$v[2];
      }
    }
    $refund_notify = array(
      'notify_url' => U('/Api/refundNotify', array('apitype' => 'alipayrefund', 'method' => 'notify'), false, true)
    );
    $refund_config = array_merge($refund_notify, C('ALIPAY'));
    $refund_data = array(
      'batch_no' => date('Ymd').build_sn(), // 批次号
      'batch_num' => count($refund_detail), // 退款笔数
      'detail_data' => implode('#', $refund_detail), // 退款详细数据
    );
    // 更新所有退款订单的批次号
    $batch_data = array(
      'id' => array('IN', $ids),
      'operator_id' => UID,
      'refund_no' => $refund_data['batch_no']
    );
    D('Refund')->update($batch_data);
    // 实例化支付接口
    $pay = new \Think\Pay('alipayrefund', $refund_config);
    echo $pay->buildRequestForm($refund_data);
  }
}