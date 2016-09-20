<?php
/**
 * 后台优惠券模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class CouponModel extends AdminModel{

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('number', 'require', '优惠券编号未填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('name', 'require', '优惠券名称未填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('amount', 'require', '优惠券金额未填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('expire_time', 'require', '有效日期不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('expire_time', '/^\d{4,4}-\d{1,2}-\d{1,2}$/', '日期格式不合法,请使用"年-月-日"格式,全部为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_INSERT),
    array('expire_time', 'checkExpireTime', '有效日期不能小于当天日期', self::VALUE_VALIDATE  , 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('expire_time', 'strtotime', self::MODEL_BOTH, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取优惠券详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '优惠券信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新优惠券后生成券码表
   */
  public function _after_update($data,$option){
    $time=time()+rand(10000000,99999999);
    $row=array();
    $start_id=M('CouponNum')->where(array('cn_coupon_id'=>$data['id']))->count();
    for($i=$start_id;$i<$data['num'];$i++){
      $row[]=array('cn_coupon_id'=>$data['id'],'coupon_num'=>substr(md5($time-$i),-8));
    }
    $res=M('CouponNum')->addAll($row);
  }

  /**
   * 添加优惠券时生成券码表
   */
  public function _after_insert($data,$option){
    $time=time()+rand(10000000,99999999);
    $row=array();
    $start_id=M('CouponNum')->where(array('cn_coupon_id'=>$data['id']))->count();
    for($i=$start_id;$i<$data['num'];$i++){
      $row[]=array('cn_coupon_id'=>$data['id'],'coupon_num'=>substr(md5($time-$i),-8));
    }
    $res=M('CouponNum')->addAll($row);
  }

  /**
   * 更新优惠券过期状态
   */
  public function updateExpireStatus(){
    $this->execute("UPDATE __PREFIX__coupon SET is_expire = 1 WHERE (expire_time + 86400 - UNIX_TIMESTAMP(NOW())) < 0");
  }

  /**
   * 删除优惠券
   * @return true 删除成功， false 删除失败
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($ids){
    $map['id'] = array('IN', $ids);
    $res = $this->where($map)->delete();
    return $res;
  }
  
  /**
  * 自定义检测截止有效期（自动验证使用）：必须大于今天
  * @param string $expire_time 截止有效期
  * @version 2015070916
  * @return boolen
  * @author Justin <justin@jipu.com>
  */
  protected function checkExpireTime($expire_time){
    return strtotime($expire_time) >= strtotime(date('Y-m-d',strtotime('+1 day'))) ? true : false;
  }
  
}
