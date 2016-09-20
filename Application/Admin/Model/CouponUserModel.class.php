<?php
/**
 * 后台优惠券发放模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;
use Think\Model;

class CouponUserModel extends Model{

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
  array('coupon_id', 'require', '未选择要发放的优惠券', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  array('uid', 'require', '发放用户ID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH)
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
  array('create_time', NOW_TIME, self::MODEL_INSERT),
  array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取优惠券发放详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '优惠券发放信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新或修改信息
   * @param array $data 优惠券发放数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function updateByUids($coupon_id, $uids){
    if($coupon_id && $uids){
      $uid_arr = explode(',', $uids);
    }else{
      return false;
    }

    if(is_array($uid_arr)){
      foreach($uid_arr as $value){
        if(is_numeric($value)){
          $data = array(
          'coupon_id' => $coupon_id,
          'uid' => $value
          );
          $data = $this->create($data);
          $res = $this->add($data);
          if(empty($res)){
            return false;
          }else{
            //领取券码
            $this->getCouponNum($coupon_id,$res);
            //更新用户优惠券数量
            $this->updateCount($value);
          }
        }else{
          return false;
        }
      }
    }

    return true;
  }

  /**
   * 分配唯优惠券码号给用户
   */
  protected function getCouponNum($coupon_id,$cuid){
    $coupon_num=M('CouponNum')->where(array('is_get'=>0,'cn_coupon_id'=>$coupon_id))->find();
    if($coupon_num){
      $coupon_num['cn_couponuser_id']=$cuid;
      $coupon_num['is_get']=1;
      M('CouponNum')->save($coupon_num);
    }
  }

  /**
   * 更新当前用户优惠券统计数据
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  public function updateCount($uid){
    if(empty($uid)){
      return false;
    }
    $map['uid'] = $uid;
    $count = $this->where($map)->count();
    $update_user = D('Usercount')->setKeyValue($uid, 'coupon_count', $count);
  }

}
