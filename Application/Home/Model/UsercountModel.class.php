<?php
/**
 * 用户统计模型
 * @version 2015102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class UsercountModel extends Model{

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 设置指定用户指定Key值的统计数目
   * @param integer $uid 用户UID
   * @param string $key Key值
   * @param integer $value 设置的统计数值
   * @return void
   */
  public function setKeyValue($uid, $key, $value){
    $map['uid'] = $uid;
    $map['key'] = $key;
    $this->where($map)->delete();
    $map['value'] = intval($value);
    return $this->add($map);
  }

  /**
   * 更新某个用户的指定Key值的统计数目
   * Key值：
   * order_count：订单总数
   * cart_count：购物车商品数
   * receiver_count：收货人数
   * fav_count：收藏商品数
   * behavior_count：浏览过的商品数
   * @param string $key Key值
   * @param integer $nums 更新的数目
   * @param boolean $add 是否添加数目，默认为true
   * @param integer $uid 用户UID
   * @return array 返回更新后的数据
   */
  public function updateKey($key, $nums, $add = true, $uid = ''){
    if($nums == 0){
      //不需要修改
      $this->error = '不需要修改';
      return false;
    }
    //若更新数目小于0，则默认为减少数目
    if($nums < 0){
      $add = false;
    }

    //获取当前设置用户的统计数目
    $data = $this->getUserCount($uid);
    if(empty($data) || !$data){
      $data = array();
      $data[$key] = $nums;
    }else{
      $data[$key] = $add ? ($data[$key] + abs($nums)) :($data[$key] - abs($nums));
    }

    if($data[$key] < 0){
      $data[$key] = 0;
    }

    $map['uid'] = empty($uid) ? UID : $uid;
    $map['key'] = $key;
    $this->where($map)->limit(1)->delete();
    $map['value'] = $data[$key];
    $this->add($map);
    return $data;
  }

  /**
   * 获取指定用户的统计数据
   * @param integer $uid 用户UID
   * @return array 指定用户的统计数据
   */
  public function getUserCount($uid = ''){
    //默认为设置的用户
    if(empty($uid)){
      $uid = UID;
    }
    $map['uid'] = $uid;
    $data = array();
    $list = $this->where($map)->select();
    if(!empty($list)){
      foreach($list as $v){
        $data[$v['key']] = (int)$v['value'];
      }
    }
    return $data;
  }

}
