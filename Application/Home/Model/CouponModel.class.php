<?php
/**
 * 优惠券模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class CouponModel extends Model {

  /**
   * 优惠券列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map, $field = true, $order = 'create_time DESC', $limit = '10'){
    $map['status'] = 1;
    $lists = $this->where($map)->field($field)->order($order)->limit($limit)->select();
    return $lists;
  }

  /**
   * 优惠券详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map){
    return $this->where($map)->find();
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
    return $result;
  }

  /**
   * 更新优惠券过期状态
   */
  public function updateExpireStatus(){
    $this->execute("UPDATE __PREFIX__coupon SET is_expire = 1 WHERE (expire_time + 86400 - UNIX_TIMESTAMP(NOW())) < 0");
  }

}
