<?php
/**
 * 红包控制器
 * @version 20102011513
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;
use Think\Page;

class RedpacketController extends AdminController {
  private $payment_type = array('alipay' => '支付宝', 'alipaywap' => '手机支付宝', 'bankpay' => '网银支付', 'wechatpay' => '微信支付', 'crowdfunding' => '众筹支付', 'admin' => '官方发放');
  private $red_type = array('multi' => '群抢红包', 'single' => '普通红包');

  /**
   * 红包列表页
   * @author tony <tony@jipu.com>
   */
  public function index(){
    $keywords = trim(I('keywords'));
    $uid = trim(I('uid'));
    $type = trim(I('type', 'send'));
    $id = trim(I('id'));
    //查询关键词
    $keywords ? $map['amount|quantity'] = $keywords : '';
    $map['redpacket_status'] = $type;
    if($type == 'send'){
      $map['payment_status'] = 1;
      $map['status'] = array('neq', -1); // 将已取消的红包过滤掉
    }else if($type == 'receive'){
      $id ? $map['redpacket_id'] = $id : '';
    }
    $uid ? $map['uid'] = $uid : '';
    $field = 'id, uid, amount, quantity, msg, payment_type ,payment_time, type, limit_money';
    $Redpacket = D('Redpacket');
    $lists = $this->lists('Redpacket', $map, 'id DESC', $field);
    if($lists){
      $quantity = $Redpacket->redCount($map);
      $amount = $Redpacket->redSum($map);
    }
    $this->quantity = $quantity ? $quantity : 0;
    $this->amount = $amount ? $amount : '0.00';
    $intToStringMap = array(
      'payment_type' => $this->payment_type,
      'type' => $this->red_type
    );
    int_to_string($lists, $intToStringMap);
    if($type == 'send'){
      $map_str['redpacket_status'] = 'receive';
      foreach($lists as $k => $v) {
        $map_str['redpacket_id'] = $v['id'];
        $received_quantity = $Redpacket->redCount($map_str);
        $received_amount = $Redpacket->redSum($map_str);
        $lists[$k]['received_quantity'] = $received_quantity ? $received_quantity : 0;
        $lists[$k]['received_amount'] = $received_amount ? $received_amount : '0.00';
      }
    }
    $this->lists = $lists;
    $this->meta_title = '红包管理';
    $this->display();
  }

  /**
   * 红包领取列表页
   * @author tony <tony@jipu.com>
   */
  public function receive(){
    $id = trim(I('id'));
    $sort = I('sort');
    $sort = $sort ? ($sort == 'desc' ? 'amount DESC' : 'amount ASC') : 'id DESC';
    $map['redpacket_status'] = trim(I('type','receive'));
    $map['redpacket_id'] = $id;
    $Redpacket = D('Redpacket');
    $field = 'id, uid, amount, msg, create_time';
    $lists = $this->lists('Redpacket', $map, $sort, $field);
    if($lists){
      $quantity = $Redpacket->redCount($map);
      $amount = $Redpacket->redSum($map);
    }
    $this->info = $Redpacket->getOrderInfo(array('id'=>$id));
    $this->quantity = $quantity ? $quantity : 0;
    $this->amount = $amount ? $amount : '0.00';
    $this->lists = $lists;
    $this->meta_title = '红包领取详情';
    $this->display();
  }

  /**
   * 后台添加红包
   * @author tony <tony@jipu.com>
   */
  public function add(){
    if(IS_POST){
      //定义最小群红包数量为2
      $minNum = 2;
      //定义最大红包数量为1000
      $maxNum = 1000;
      $data['quantity'] = I('quantity');
      $data['amount'] = I('amount');
      $data['msg'] = trim(I('msg')) ? trim(I('msg')) : trim(I('msg_def'));
      $data['type'] = trim(I('type'));
      $data['redpacket_status'] = 'send';
      $data['limit_money'] = (float)I('limit_money');
      $this->checkEmpty($data['quantity'], '红包数量');
      $this->checkEmpty($data['amount'], '红包金额');
      $this->checkEmpty($data['type'], '红包类型');
      //对支付金额进行有效验证
      $this->checkMoneyStyle($data['amount'], '支付金额非法！');
      $this->checkMoneyStyle($data['limit_money'], '限制金额非法！');
      is_numeric($data['quantity']) ? '' : $this->error('数量非法！');
      $data['limit_money'] < 0.01 ? $this->error('限制金额非法！') : '';
      //普通红包时，红包金额为数量和单个金额的乘积
      $data['quantity'] = intval($data['quantity']);
      $data['amount'] = (float)$data['amount'];
      if($data['type'] == 'single'){
        $data['amount'] = $data['amount'] * $data['quantity'];
      }else if($data['type'] == 'multi' && $data['quantity'] < $minNum){
        $this->error("群红包数量不能小于{$minNum}！");
      }
      if($data['quantity'] > $maxNum){
        $this->error("红包数量不能大于{$maxNum}个！");
      }
      if($data['amount']*100 < $data['quantity']){
        $this->error("红包可分配金额少于要分配数量");
      }
      $data['payment_type'] = 'admin'; //管理员后台添加红包
      $data['payment_status'] = 1;
      $id = D('Redpacket')->update($data);
      
      if($id){
        $this->get_qrcode($id);
        $data = array(
          'status' => 1,
          'info' => '红包发放成功',
          'url' => U('qrcode', array('id'=>$id))
        );
        //记录行为
        action_log('update_redpacket', 'redpacket', $id, UID);
      }else{
        $data = array(
          'status' => 0,
          'info' => '抱歉，红包发放失败'
        );
      }
      $this->ajaxReturn($data);
    }else{
      $this->meta_title = '发红包';
      $this->display();
    }
  }

  /**
   * 红包二维码展示
   * @author tony <tony@jipu.com>
   */
  public function qrcode(){
    $this->id = I('id');
    // 二维码图片路径
    $this->url = __ROOT__.ltrim(C('QRCODE_CONFIG.rootPath'), '.').'redpacket';
    (IS_AJAX) ? $this->display('qrcodeAjax') : $this->display();
  }

  /*
   * 生成红包二维码
   * @author Max.Yu <max@jipu.com>
   */
  private function get_qrcode($redpacket_id, $level = 'H', $size = 10, $margin = 2){
    if(!$redpacket_id){
      return false;
    }
    $data = 'http://'.$_SERVER['SERVER_NAME'].'/Redpacket/share/id/'.$redpacket_id.'.html';
    vendor('Qrcode.Phpqrcode');
    $path = C('QRCODE_CONFIG.rootPath');
    $filename = $path.'redpacket/'.$redpacket_id.'/'.$redpacket_id.'.png';
    if(!file_exists($filename)){
      mkdir($path.'redpacket/'.$redpacket_id.'/');
    }
    $QRcode = new \Vendor\QRcode();
    $QRcode->png($data, $filename, $level, $size, $margin);
    return $filename;
  }


  /**
   * 取消红包
   * @author tony <tony@jipu.com>
   */
  public function del(){
    $ids = I('ids');
    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }
    $Redpacket = D('Redpacket');
    $id_str = '';
    is_string($ids) ? $ids = array($ids) : '';
    foreach($ids as $k => $v) {
      $map['redpacket_id'] = $v;
      $map['redpacket_status'] = 'receive';
      $count = $Redpacket->redCount($map);
      if($count > 0){
        unset($ids[$k]);
        $id_str .= ','.$v;
      } 
    }
    $id_str = ltrim($id_str, ',');
    unset($map);
    $map['id'] = array('in', $ids);
    $map['payment_type'] = 'admin';
    $data['status'] = -1;
    $ids ? $result = $Redpacket->where($map)->save($data) : $this->error('删除失败！红包id为'.$id_str.'的红包已被领取不能删除');
    if($result){
      //记录行为
      action_log('update_redpacket', 'redpacket', $id_str, UID);
      $id_str ? $this->success('部分删除成功！红包id为'.$id_str.'的红包已被领取不能删除') : $this->success('删除成功！');  
    }else{
      $this->error('删除失败！');
    }
  }


  /**
   * 用来检测提交的数据是否为空的方法
   * @author tony <tony@jipu.com>
   * $data：提交的数据
   * $msg：提交的数据描述
   */
  private function checkEmpty($data, $msg = ''){
    if(empty($data)){
      $this->error($msg.'不能为空');
    }
  }

  /**
   * 用来检测提交的数据是否为空的方法
   * @author tony <tony@jipu.com>
   * $data：提交的数据
   * $msg：提交的数据描述
   */
  private function checkMoneyStyle($money, $msg = '支付金额非法！'){
    is_numeric($money) ? '' : $this->error($msg);
    $pay_money_check = explode('.' , $money);
    strlen($pay_money_check[1]) > 2 ? $this->error('只能保留2位小数！') : '';
  }



}