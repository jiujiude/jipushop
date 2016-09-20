<?php
/**
 * 前台用户充值控制器
 * @version 2014092214
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;
use Org\Wechat\WechatPay;

class RechargeController extends HomeController {

  private $wechat_config;

  public function _initialize() {
    parent::_initialize();

    //用户登录验证
    parent::login();

    //获取微信支付接口配置参数
    $this->wechat_config = C('WECHATPAY');
  }

  /**
   * 根据支付类型设置支付接口参数
   * @param string $payment_type 支付类型
   * @author Max.Yu <max@jipu.com>
   * @return array
   */
  private function setPaymentConfig($payment_type){
    $payment_config = array();
    if($payment_type){
      $payment_config['notify_url'] = U('Recharge/notify', array('apitype' => $payment_type, 'method' => 'notify'), false, true);
      $payment_config['return_url'] = U('Recharge/notify', array('apitype' => $payment_type, 'method' => 'return'), false, true);
      $payment_config['merchant_url'] = U('Recharge/notify', array('apitype' => $payment_type, 'method' => 'merchant'), false, true);
      $payment_config = array_merge($payment_config, C(strtoupper($payment_type)));
    }
    return $payment_config;
  }

  /**
   * 支付业务逻辑处理
   * 微信支付采用单独处理方法，后续整合入ThinkPay
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //充值金额
    $amount = I('post.amount');
    if(empty($amount) || $amount <= 0){
      $this->error('请输入充值金额！');
    }

    //充值方式
    $payment_type = I('post.payment_type');
    if(empty($payment_type)){
      $this->error('请选择充值方式！');
    }

    //充值银行
    $bank = I('post.bank');
    if($payment_type == 'bankpay'){
      if(empty($bank)){
        $this->error('请选充值银行！');
      }
    }

    //生成账户充值流水号
    $flowid = create_sn();

    //构建支付请求数据
    $payment_data['out_trade_no'] = $flowid;
    $payment_data['subject'] = '账户充值';
    $payment_data['body'] = '账户预存款充值';
    $payment_data['total_fee'] = $amount;

    //微信支付采用单独处理方法，后续整合入ThinkPay TODO:
    if($payment_type !== 'wechatpay'){
      //新增充值记录（备注：由于支付宝手机支付不能返回支付金额，采取支付前写交易记录，充值成功后改变支付状态的方案）
      $transaction_data = array(
        'uid' => UID,
        'flowid' => $flowid,
        'type' => '充值',
        'mode' => $payment_type,
        'amount' => $amount,
        'flow' => 'in',
        'memo' => '账户预存款充值',
        'status' => 0,
      );
      $result = D('Transaction')->update($transaction_data);

      if($result){
        //根据支付类型设置支付接口参数
        $payment_config = $this->setPaymentConfig($payment_type);

        //实例化支付接口
        $pay = new \Think\Pay($payment_type, $payment_config);
        echo $pay->buildRequestForm($payment_data);
      }else{
        $this->error('充值记录写入失败！');
      }
    }else{
      $this->doWeixin($flowid, '账户充值', $amount);
    }
  }

  /**
   * 支付宝支付完成后的通知回调方法
   * @author Max.Yu <max@jipu.com>
   */
  public function notify(){
    if(IS_POST && !empty($_POST)){
      $notify = $_POST;
    }elseif(IS_GET && !empty($_GET)){
      $notify = $_GET;
      unset($notify['method']);
      unset($notify['apitype']);
    }else{
      exit('Access Denied');
    }

    //获取支付类型
    $payment_type = I('get.apitype');

    //根据支付类型设置支付接口参数
    $payment_config = $this->setPaymentConfig($payment_type);

    //实例化支付接口
    $pay = new \Think\Pay($payment_type, $payment_config);

    //验证支付通知
    if($pay->verifyNotify($notify)){
      //获取支付返回信息
      $info = $pay->getInfo();

      //获取充值交易记录
      $transaction = M('Transaction')->where(array('flowid'=>$info['out_trade_no']))->find();

      if($info['status']){
        //防止用户重复点击“返回商户页面”按钮造成重复充值
        if($transaction['status']==1){
          $this->success('充值成功！', U('Member/finance'));
        }else{
          //实例化用户模型
          $member_model = D('Member');

          //获取账户充值前余额
          $balance = $member_model->getFinance(UID);

          //更新账户余额
          $updateFinance = $member_model->updateFinance(UID, $transaction['amount'], 'inc');

          //更新充值交易状态
          if($updateFinance){
            $transaction_data = array(
              'id' => $transaction['id'],
              'status' => 1,
              'number' => $info['trade_no'],
            );
            D('Transaction')->update($transaction_data);
          }

          if(I('get.method') == 'return'){
            $this->success('充值成功！', U('Member/finance'));
          }elseif(I('get.method') == 'merchant'){
            $this->success('充值取消！', U('Member/finance'));
          }else{
            $pay->notifySuccess();
          }
        }
      }else{
        //更新充值交易状态
        $transaction_data = array(
          'id' => $transaction['id'],
          'status' => -1,
        );
        D('Transaction')->update($transaction_data);

        $this->error('充值失败！');
      }
    }else{
      E('Access Denied');
    }
  }

  /*微信维权通知URL*/
  public function support(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    logger($postStr);
    echo 'success';
  }

  /*微信告警通知URL*/
  public function notice(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    echo 'success';
  }

  /**
   * 执行微信支付
   * @author Max.Yu <max@jipu.com>
   */
  private function doWeixin($out_trade_no, $subject, $total_fee){
    $wechat = new WechatPay($this->wechat_config);
    $data = array(
      'timestamp' => time(), //10位时间戳
      'noncestr' => md5(uniqid(time(),true)), //随机字符串
      'body' => $subject, //商品描述
      'notify_url' => 'http://'.SITE_DOMAIN.U('Recharge/weixinNotify'),
      'out_trade_no' => $out_trade_no, //交易号
      'spbill_create_ip' => $_SERVER['REMOTE_ADDR'], //微信用户IP
      'total_fee' => $total_fee*100, //支付金额，前台提交的是元，转换为分
    );

    $data += $wechat->bulidForm($data);
    $data['appId'] = $wechat->app_id;
    $data['partnerid'] = $wechat->partnerid;
    $data['partnerKey'] = $wechat->partnerKey;
    $data['paySignKey'] = $wechat->paySignKey;
    $data['successUrl'] = U('Member/finance');
    $this->data = $data;
    $this->display('weixin');
  }

  /**
   * 查询订单并更新订单状态至数据库
   * @author Max.Yu <max@jipu.com>
   */
  public function weixinOrderQuery(){
    $flowid = I('post.flowid');
    if(empty($flowid)){
      $this->error('交易流水号不能为空！');
    }

    //查询微信接口订单
    $weixin = new WechatPay($this->wechat_config);
    $result = $weixin->order_query($flowid);
    $order_info = $result['order_info'];
    if($order_info['trade_state'] == 0){
      //充值成功后的业务处理
      $this->afterWeixinPaySuccess($flowid, $order_info);
    }
    $this->ajaxReturn($result);
  }

  /**
   * 充值成功后的业务处理
   * @author Max.Yu <max@jipu.com>
   */
  private function afterWeixinPaySuccess($flowid, $order_info){
    //获取当前流水号的充值记录
    $transaction = M('Transaction')->where(array('flowid'=>$flowid))->find();

    if(empty($transaction)){ //检测用户是否点击“返回”按钮造成重复充值
      //获取账户充值前余额
      $member_model = D('Member');
      $balance = $member_model->getFinance(UID);

      //更新账户余额
      $update = $member_model->updateFinance(UID, $order_info['total_fee']/100, 'inc');

      //新增充值记录
      if($update){
        $transaction_data = array(
        'uid' => UID,
        'flowid' => $flowid,
        'number' => $order_info['transaction_id'],
        'type' => '充值',
        'mode' => 'wechatpay',
        'amount' => $order_info['total_fee']/100,
        'balance' => $balance,
        'flow' => 'in',
        'memo' => '账户预存款充值',
        'status' => 1,
        );
        D('Transaction')->update($transaction_data);
      }
    }
  }

  /*
   * 微信支付结果通知回调
   */
  public function weixinNotify(){
    //实例化微信支付类
    $weixin = new WechatPay($this->wechat_config);

    $isweixin = $weixin->verifyNotify($_GET, $_GET['sign']);
    if($isweixin){//验证服务器通知是否是微信回调
      //获取通知参数
      $out_trade_no = $_GET['out_trade_no'];
      $trade_no = $_GET['transaction_id'];
      $trade_status = $_GET['trade_state'];

      if($trade_status == 0){//支付成功
        //查询微信接口订单
        $result = $weixin->order_query($flowid);
        $order_info = $result['order_info'];

        //充值成功后的业务处理
        $this->afterWeixinPaySuccess($out_trade_no, $order_info);

        //发货通知
        $xml = $weixin->getXmlArray();
        $openid = $xml['openid'];
        $delivernotify = $weixin->delivernotify($openid, $trade_no, $out_trade_no);

        echo "success";
      }else{
        echo "fail";
      }
    }else{
      echo "不是微信回调";
    }
  }

}
