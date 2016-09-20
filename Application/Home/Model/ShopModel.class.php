<?php
/**
 * 订单模型
 * @version 2015102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class ShopModel extends Model{

  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
    array('name', 'require', '店铺名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_UPDATE),
  );

  /** 
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('secret', '_get_shop_secret', self::MODEL_INSERT, 'callback'),
    array('logo', '_get_shoplogo', self::MODEL_UPDATE, 'callback'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
    array('status', 0, self::MODEL_INSERT)
  );

  /**
   * 更新数据
   * @return string 返回分享Key
   */
  public function update($data){
    $data = $this->create($data);
    if(!$data){
      return '';
    }
    if(I('post.status') != 0){
      $this->error = '非法操作！';
      return false;
    }
    
    $audit_data = $this->_getAuditData();
  
    if($audit_data){
      $data['audit_data'] = $audit_data;
    }elseif($audit_data === false){
      return false;
    }
    //获取当前状态
    $shop_data = $this->getByUid(UID);
    if($shop_data){
      $data['id'] = $shop_data['id'];
    }
    
    if($data['id'] > 0){
      if($shop_data && $shop_data['status'] == 1){
        unset($data['audit_data']);
      }
      $this->save($data);
      return $data;
    }else{
      $res = $data['audit_data'] ? $this->add($data) : '';
      return $res ? $data : '';
    }
  }
  
  /**
   * 申请资质完整性验证
   */
  public function _getAuditData(){
    $data = I('post.audit_data');
    if(empty($data)){
      return '';
    }
    foreach($data as &$v){
      $v = trim($v);
    }
    if(empty($data['name'])){
      $this->error = '请输入您的姓名';
    }elseif(!is_mobile_number($data['mobile'])){
      $this->error = '请输入正确的手机号码';
    }elseif(empty($data['email'])){
      $this->error = '请输入您的Email地址';
    }elseif(strlen($data['person_id']) != 18){
      $this->error = '请输入您的身份证号码';
    }elseif(mb_strlen($data['intro']) < 10){
      $this->error = '请输入理由';
    }
    if($this->error){
      return false;
    }else{
      return serialize($data);
    }
  }
  
  /**
   * 添加商品到我的店铺
   * @author Max.Yu <max@jipu.com>
   */
  public function addItem($items = 0){
    $shop = $this->getByUid(UID);
    $new_ids = str_remove(0, str_add($items, $shop['item_ids']));
    return $this->where(array('id' => $shop['id']))->setField('item_ids', $new_ids);
  }

  /**
   * 添加我的店铺的商品
   * @author Max.Yu <max@jipu.com>
   */
  public function removeItem($itemid){
    $shop = $this->getByUid(UID);
    $new_ids = str_remove($itemid.',0', $shop['item_ids']);
    return $this->where(array('id' => $shop['id']))->setField('item_ids', $new_ids);
  }


  /**
   * 获取唯一的分享key
   * @author Max.Yu <max@jipu.com>
   */
  protected function _get_shop_secret(){
    $secret_str = get_randstr(5);
    $has_row = $this->getBySecret($secret_str);
    if($has_row){
      return $this->_get_shop_secret();
    }else{
      return $secret_str;
    }
  }
 
  /**
   * 获取最近30天的统计数据（按天显示）
   * @param int $sdp_uid 分销店铺UID
   * @author Max.Yu <max@jipu.com>
   */
  function getMonthData($sdp_uid = 0){
    if(empty($sdp_uid)){
      $sdp_uid = UID;
    }
    //订单金额
    $today_start = strtotime(date('Y-m-d'));
    $data = array();
    for($i= $today_start-30*86400; $i<=$today_start; $i+=86400){
      $data['labels'][] = date('n-j', $i);
      $where = array(
        'sdp_uid' => $sdp_uid,
        'create_time' => array('between', array($i, $i+86400))
      );
      //金额统计
      $amount_line = M('SdpRecord')->field('sum(`cashback_amount`) as amount')->where($where)->find();
      $data['amount_datas'][] = $amount_line['amount'] ? $amount_line['amount'] : 0;
      //订单量统计
      $orders = M('SdpRecord')->field('id')->group('order_id')->where($where)->select();
      $data['order_datas'][] = $orders ? count($orders) : 0;
    }
    return $data;
  }
  
  /**
   * 获取最近1年的统计数据（按月显示）
   * @param int $sdp_uid 分销店铺UID
   * @author Max.Yu <max@jipu.com>
   */
  function getYearData($sdp_uid = UID){
    $month_start = strtotime(date('Y-m-1') .' -12 month');
    $data = array();
    for($i = $month_start;$i< NOW_TIME;){
      $data['labels'][] = date('y年n月', $i);
      //截止日期
      $i = strtotime(date('Y-m-d', $month_start) .' +1month');
      $where = array(
        'sdp_uid' => $sdp_uid,
        'create_time' => array('between', array($month_start, $i))
      );
      //金额统计
      $amount_line = M('SdpRecord')->field('sum(`cashback_amount`) as amount')->where($where)->find();
      $data['amount_datas'][] = $amount_line['amount'] ? $amount_line['amount'] : 0;
      //订单量统计
      $orders = M('SdpRecord')->field('id')->group('order_id')->where($where)->select();
      $data['order_datas'][] = $orders ? count($orders) : 0;
      //截止日期作初始日期，重新开始循环
      $month_start = $i;
    }
    return $data;
  }
  
  /**
   * 获取店铺上传的头像
   * @return string 店铺头像路径
   * @author Max.Yu <max@jipu.com>
   */
  protected function _get_shoplogo(){
    //保存图片到服务器
    $serverid = I('post.avatar_serverid', '');
    $logo_id = I('post.logo_id', 0);
    if($serverid){
      $auth = new \Org\Wechat\WechatAuth(C('WECHAT_APPID'), C('WECHAT_SECRET'));
      $token = $auth->getAccessToken();
      if($token['access_token']){
        $url_path = $auth->mediaGet($serverid, $token['access_token']);
        $new_path = '/'.save_image('Shop', $url_path);
        return $new_path;
      }
    }
    if($logo_id){
      return is_numeric($logo_id) ? get_cover($logo_id, 'path') : $logo_id;
    }elseif(isset($_POST['logo_id'])){
      return '';
    }
    return $this->getFieldById(I('post.id'), 'logo');
  }
  
}
