<?php
/**
 * 礼品卡模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;
use Think\Model;

class CardModel extends Model {

  /**
   * 获取礼品卡列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map, $order = 'id ASC', $field = true){
    return $this->where($map)->field($field)->order($order)->select();
  }

  /**
   * 获取礼品卡详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map){
    return $this->where($map)->find();
  }

  /**
   * 更新礼品卡信息
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
   * 更新礼品卡信息，条件更新
   * @param array $data 礼品卡数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function updateFeilds($map, $data = null){
    if($map && $data){
      $result = $this->where($map)->save($data);
      if($result){
        return $result;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

  /**
   * 更新礼品卡期状态
   * @author Max.Yu <max@jipu.com>
   */
  public function updateExpireStatus(){
    $this->execute("UPDATE __PREFIX__card SET is_expire = 1 WHERE (expire_time + 86400 - UNIX_TIMESTAMP(NOW())) < 0");
  }

}
