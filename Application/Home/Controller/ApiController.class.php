<?php
/**
 * 前台API控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

use Think\Controller;
use Common\Api\UserApi;

class ApiController extends Controller{
  public function _initialize(){
    $config = api('Config/lists');
    C($config);
  }
  /**
   * 微信支付异步通知
   */
  public function weixinNotify(){
    //订单类型
    $order_type = I('get.order_type', 'item_order');
    //引入微信支付通用类
    import('Org.Wechat.Pay.WxPayPubHelper', '', '.php');
    //使用通用通知接口
    $notify = new \Notify_pub();
    //存储微信的回调
    $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
    $notify->saveData($xml);
    //验证签名，并回应微信
    $signBool = $notify->checkSign();
    if($signBool == FALSE){
      $notify->setReturnParameter("return_code", "FAIL"); //返回状态码
      $notify->setReturnParameter("return_msg", "签名失败"); //返回信息
    }else{
      $notify->setReturnParameter("return_code", "SUCCESS"); //设置返回码
    }
    $returnXml = $notify->returnXml();
    echo $returnXml;
 
    //校验Sign
    if($signBool == TRUE){
      $data = $notify->data;
      if($data["return_code"] == "SUCCESS" && $data["result_code"] == "SUCCESS"){
        //处理支付通知
        $res = A('Pay', 'Event')->afterPaySuccess($data['out_trade_no'], $data, $order_type);
        if($res == 'unpay'){
           $this->error('订单支付失败，钱已充入余额！', U('Member/order'));
        }
      }else{
        $data['get'] = I('get.');
        //订单错误结果缓存
        F('WeixinPay/notify/'.date('Ym/d/').$data['out_trade_no'].'-'.date('-His'), $data);
      }
    }
  }

  /**
   *  微信维权通知URL 
   */
  public function support(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    echo 'success';
  }

  /**
   *  微信告警通知URL 
   */
  public function notice(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    echo 'success';
  }

  /**
   * 支付宝、网银等异步通知接口
   */
  public function payNotify(){
    if(IS_POST && !empty($_POST)){
      $notify = $_POST;
    }elseif(IS_GET && !empty($_GET)){
      $notify = $_GET;
      unset($notify['order_type']);
      unset($notify['method']);
      unset($notify['apitype']);
    }else{
      exit('Access Denied');
    }
    //获取支付类型
    $payment_type = I('get.apitype');
    if(I('get.method') == 'merchant'){
      $this->success('支付取消！', U('Member/order'));
    }

    //获取支付类型
    $payment_type = I('get.apitype');
    //获取订单类型
    $order_type = I('get.order_type');
    //根据支付类型设置支付接口参数
    $payment_config = A('Pay', 'Event')->setPaymentConfig($payment_type);

    //实例化支付接口
    $pay = new \Think\Pay($payment_type, $payment_config);
    //验证支付通知
    if($pay->verifyNotify($notify)){
      //获取订单支付返回信息
      $info = $pay->getInfo();
      if($info['status']){
        //处理订单信息
        $res = A('Pay', 'Event')->afterPaySuccess($info['out_trade_no'], $info, $order_type);
        if($res == 'unpay'){
           $this->error('订单支付失败，钱已充入余额！', U('Member/order'));
        }
        if(I('get.method') == 'return'){
          $url = U('Order/info', array('order_type' => $order_type, 'order_sn' => $info['out_trade_no']));
          redirect($url);
        }else{
          $pay->notifySuccess();
        }
      }else{
        F('AliPay/notify/'.date('Ym/d/').$info['out_trade_no'].'-'.date('-His'), $info);
        $this->error('抱歉，支付失败！');
      }
    }else{
      E('Access Denied');
    }
  }

  /**
   * 支付宝退款异步通知接口
   */
  public function refundNotify(){
    if(IS_POST && !empty($_POST)){
      $notify = $_POST;
    }elseif(IS_GET && !empty($_GET)){
      $notify = $_GET;
      unset($notify['method']);
      unset($notify['apitype']);
    }else{
      exit('Access Denied');
    }
    add_wechat_log($_POST, 'refund-notify-post');
    //获取支付类型
    $apitype = I('get.apitype');
    //设置支付接口参数
    $refund_notify = array(
      'notify_url' => U('Home/Api/refundNotify', array('apitype' => $apitype, 'method' => 'notify'), false, true)
    );
    $pay_config=($apitype=='alipayrefund')?'alipay':$apitype;
    $refund_config = array_merge($refund_notify, C(strtoupper($pay_config)));

    //实例化支付接口
    $pay = new \Think\Pay($apitype, $refund_config);
    add_wechat_log($notify, 'refund-notify');
    //验证支付通知
    if($pay->verifyNotify($notify)){
      //获取订单支付返回信息
      $info = $pay->getInfo();
      add_wechat_log($info, 'refund-info');
      if($info['status']){
        //更新退款订单信息
        A('Admin/Refund', 'Event')->afterRefundSuccess($info['refund_no'], $info);
        $pay->notifySuccess();
      }else{
        //F('AliPay/notify/'.date('Ym/d/').$info['out_trade_no'].'-'.date('-His'), $info);
        $this->error('抱歉，退款失败！');
      }
    }else{
      E('Access Denied');
    }
  }
  
  /**
   * 获取分类下拉JS数据
   */
  public function getTypeData($type = null){
    if(!$type){return false;}
    $cache_name = 'typeData_'.$type.'_cache';
    if(S($cache_name)){
      $content = S($cache_name);
    }else{
      $content = "var $type=[];";
      $where = array();
      if(in_array($type, array('itemCategory'))){
        $where['status'] = 1;
      }
      $list = M($type)->where($where)->order('pid asc,sort asc')->select();
      $_showlist = array();
      foreach($list as $v){
        $name = $v['name'] ? $v['name'] : $v['title'];
        $_showlist[$v['pid']][] = array('id' => $v['id'], 'name' => $name, 'pid' => $v['pid']);
      }
      foreach($_showlist as $k => $v){
        $content .= "\r\n ".$type.'['.$k.'] = '.json_encode($v).';';
      }
      S($cache_name, $content);
    }
    die($content);
  }
  
  /**
   * 访问接口可获取微信openId
   */
  public function openId(){
    //允许的域名列表
    $domain_list = array(
      'www.jipushop.com',
    );
    $call_url = I('get.call_url');
    if($call_url){
      $_pu = parse_url($call_url);
      if(empty($_pu['host']) || !in_array($_pu['host'], $domain_list)){
        $return_data = array(
          'status' => 'error',
          'msg' => 'domain not in safe list'
        );
      }else{
        $open_id = A('Pay', 'Event')->getOpenId();
        $authkey = substr(md5($open_id . md5('jipukeji_2015_uhniw')), 10, 26);
        $return_data = array(
          'status' => 'success',
          'open_id' => $open_id,
          'authkey' => $authkey,
        );
      }
    }else{
      $return_data = array(
        'status' => 'error',
        'msg' => 'call_url can not be empty'
      );
    }
    redirect($call_url.(strpos($call_url, '?') > 0 ? '&' : '?').'return_data='.json_encode($return_data));
  }
  
  /**
   * 获取购物车商品数量
   */
  public function getCartCount(){
    //初始化当前用户统计数据
    if(!is_login()){
      $user_count['cart_count'] = (cookie('__cart__') !== null) ? count(json_decode(cookie('__cart__'), true)) : 0;
    }else{
      $user_count = D('Usercount')->getUserCount(is_login());
    }
    $data = array(
      'cart_count' => $user_count['cart_count'],
      'message_count' => A('Message', 'Event')->getUnreadNum(is_login())
    );
    $this->ajaxReturn($data);
  }
  
  /**
   * 返回请求数据对应的二维码
   */
  public function qrcode(){
    $data = I('get.data', $_SERVER['HTTP_REFERER']);
    $sec_code = I('get.sec_code', 'G');
    vendor('Qrcode.Phpqrcode');
    $QRcode = new \Vendor\Qrcode();
    $QRcode->png($data, false, $sec_code, 5, 1);
  }
  
  /**
   * 获取快递100查询接口
   */
  public function getDeliveryInfo($postid = ''){
    if(empty($postid)){
      $this->error('请完善参数！');
    }
    $type = 'shentong';
    $url = 'http://www.kuaidi100.com/query?type='.$type.'&postid='.$postid.'&id=1&valicode=&temp='.  mt_rand(0, 4);
    $content = read_file($url, 10, 'http://www.kuaidi100.com/');
    $data = json_decode($content, true);
    //
    if($data['message'] == 'ok' && is_array($data['data'])){
      $info['result'] = $data['data'];
      foreach($info['result'] as &$v){
        $v['context'] = explode('|', $v['context']);
		$v['context'] = $v['context'][2];
      }
      $info['status'] = 1;
      $this->ajaxReturn($info);
    }else{
      $this->error('获取快递信息失败！');
    }
  }
  
  /*共享用户登陆*/
  public function loginCheack(){
    $user = new UserApi;
    $info=$user->info(I('get.uid'));
    $member = D('Member')->where("`uid`='" . I('get.uid'). "'")->find();
    $sign=md5(md5($info['username'].$info['password']).$info['last_login_time']);
    if(md5($sign.I('get.time'))==I('get.sign') && $member['last_login_time']>(time()-60*60*5)){
      $re=array('code'=>200,'title'=>'同步登陆成功','body'=>$info);
    }else{
      $re=array('code'=>'1','title'=>'同步登陆验证失败,登陆超时或不在同一台电脑登陆。');
    }
    return $this->ajaxReturn($re);
  }

  /*第三方登陆*/
  public function thirdLogin($parms=null){
      $io=$parms?'out':'in';
      if($io=='out'){
        $conf=C('USER_SHARE');
        foreach($conf as $host){
          $thirdLogin_url=sprintf($host['thirdLogin'],str_replace('.','_',$_SERVER['HTTP_HOST']),$parms['username'],$parms['password'],md5($parms['username'].$parms['password'].date('Ymd')));
          $info=file_get_contents($thirdLogin_url);
          $re=json_decode($info,true);
          if($re['code']==200){
            $type=checkUserType($parms['username']);
            $member_model = D('Member');
            $member = D('User')->where("`email`='" . $parms['username'] . "' || `username`='" . $parms['username'] . "'")->find();  //判断是否为本系统用户
            if ($member) {
              $member_model->login($member['id']); //登陆
            } else {
              $user_api = new UserApi();
              $uid = $user_api->register(0,  $parms['username'],  $parms['password'], $type);  //注册用户
              if ($uid > 0) {
                $member_model->login($uid);         //登陆
              }
            }
            return true;
          }
        }
      }else{
        $username=I('get.username');
        $password=I('get.password');
        $sign=I('get.sign');
        if(md5($username.$password.date('Ymd'))==$sign){
          $type=checkUserType($username);
          $user_api = new UserApi();
          $uid=$user_api->login($username,$password,$type);
          if($uid){
            $user=$user_api->info($uid);
            echo json_encode(array('code'=>200,'title'=>'同步登陆成功','body'=>$user));
          }
        }
      }
  }

  /**
   *登陆跳转
   */
  public function loginRedirect(){
    $io=I('get.host')?'out':'in'; //判断是转出或是转入
    preg_match('/[https]+:\/\/([\w.]+)\//i',$_SERVER["HTTP_REFERER"],$http);
    $host=strtoupper(I('get.host')?I('get.host'):($http[1]?$http[1]:I('get.from')));
    $host=str_replace('_','.',$host);
    $conf=C('USER_SHARE');
    if($conf[$host]){
        if($io=='out'){
          $user_api = new UserApi();
          $uid=is_login();
          $info=$user_api->info($uid);
          $sign=md5(md5($info['username'].$info['password']).$info['last_login_time']);
          $time=$info['last_login_time'];
          $url=sprintf($conf[$host]['loginRedirect'],str_replace('.','_',$_SERVER['HTTP_HOST']),$uid,$time,$sign);
        }else {
          $url = '/';
          $time = time();
          $cheack_url=sprintf($conf[$host]['loginCheack'],str_replace('.','_',$_SERVER['HTTP_HOST']),I('get.uid'),$time,md5(I('get.sign') . $time));
          $user = file_get_contents($cheack_url);
          $re = json_decode($user, ture);
          if ($re['code'] == 200) {
            $user = $re['body'];
            $username = $user['phone'] ? $user['phone'] : ($user['email'] ? $user['email'] : $user['uname']);
            $password = substr($user['password'], 16);
            $type = $this->_checkUserType($username);

            $member_model = D('Member');
            $member = D('User')->where("`email`='" . $username . "' || `username`='" . $username . "'")->find();  //判断是否为本系统用户
            if ($member) {
              $member_model->login($member['id']); //登陆
            } else {
              $user_api = new UserApi();
              $uid = $user_api->register(0, $username, $password, $type);  //注册用户
              if ($uid > 0) {
                $member_model->login($uid);         //登陆
              }
            }
          }
        }
    }else{
        $url='/';
    }
	redirect($url);
  }
  
  /*共享用户登陆扩展方法*/
  private function _checkUserType($username){
    return checkUserType($username);
  }


  /*Test*/
  public function test(){
    $x=array_intersect(array(1,2,3),array(5,4));
    print_r($x);
  }
}
