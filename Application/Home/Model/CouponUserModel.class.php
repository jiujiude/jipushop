<?php
/**
 * 优惠券-用户模型
 * @version 2014101009
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model\RelationModel;

class CouponUserModel extends RelationModel {

  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  protected $_link = array(
    'Coupon'=> array(
      'mapping_type' => self::BELONGS_TO,
      'class_name' => 'Coupon',
      'foreign_key' => 'coupon_id',
      'condition' => 'status = 1'
    ),
  );

  /**
   * 获取用户优惠券列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map, $field = true, $order = 'id DESC'){
    //更新优惠券过期状态
    D('Coupon')->updateExpireStatus();
    //输出优惠券数据
    $lists = $this->where($map)->relation('Coupon')->field($field)->order($order)->select();
    return $lists;
  }

  /**
   * 获取用户带分页的优惠券列表
   * @author Max.Yu <max@jipu.com>
   */
  public function listsPage($map){
    //更新优惠券过期状态
    D('Coupon')->updateExpireStatus();
    
    //输出分页优惠券数据
    $prefix = C('DB_PREFIX');
    $l_table = $prefix.'coupon_user';
    $r_table = $prefix.'coupon';
    $field = 'cu.*, c.id, c.number, c.name, c.amount, c.items, c.norm, c.expire_time, c.is_expire';
    $model = M()->table($l_table.' cu')->join($r_table.' c ON cu.coupon_id = c.id');
    $lists = A('Page', 'Event')->lists($model, $map, 'cu.create_time DESC', '', NULL, $field);
    return $lists;
  }

  /**
   * 获取用户优惠券详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map){
    return $this->where($map)->relation('Coupon')->find();
  }

  /**
   * 更新优惠券信息
   * @param array $data 优惠券数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    $result = ($data['id']) ? $this->save() : $this->add();
    //更新统计
    if($result){
      $this->getCouponNum($result);
      $this->updateCount();
    }
    return $result;
  }

  /**
   * 更新优惠券信息，条件更新
   * @param array $data 优惠券数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function updateFeilds($map, $data = null){
    if(!$map && !$data){
      return false;
    }
    $result = $this->where($map)->save($data);
    //更新统计
    if($result){
      $this->updateCount();
    }
    return $result;
  }

  /**
   * 分配唯优惠券码号给用户
   */
  protected function getCouponNum($couponuser_id){
    $coupon_user=M('CouponUser')->find($couponuser_id);
    $coupon_num=M('CouponNum')->where(array('is_get'=>0,'cn_coupon_id'=>$coupon_user['coupon_id']))->find();
    if($coupon_num){
      $coupon_num['cn_couponuser_id']=$couponuser_id;
      $coupon_num['is_get']=1;
      M('CouponNum')->save($coupon_num);
    }else{
      $coupon_num=array(
          'cn_couponuser_id'=>$couponuser_id,
          'is_get'=>1,
          'coupon_num'=>substr(md5(time()),-8),
          'cn_coupon_id'=>$coupon_user['coupon_id']
      );
      $res=M('CouponNum')->add($coupon_num);
    }
  }
  /**
   * 删除优惠券数据
   * @param array $map 查询条件
   * @return boolean 删除结果
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($map){
    $result = $this->where($map)->delete();
    //更新统计
    if($result){
      $this->updateCount();
    }
    return $result;
  }

  /**
   * 更新当前用户优惠券统计数据
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  protected function updateCount($data){
    $map['uid'] = UID;
    $count = $this->where($map)->count();
    $update_user = D('Usercount')->setKeyValue(UID, 'coupon_count', $count);
  }

  /**
   * 判断用户是否领用过该优惠券
   * @author Max.Yu <max@jipu.com>
   */
  public function is_get($uid = null, $coupon_id = null){
    $where = array(
      'uid' => $uid,
      'coupon_id' => $coupon_id,
    );

    $result = $this->where($where)->getField('id');

    if($result){
      return true;
    }else{
      return false;
    }
  }

}
