<?php
/**
 * 收藏模型
 * @version 2014060808
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class FavModel extends Model {

  /**
   * 收藏模型自动完成
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取某一用户的收藏列表
   * @param string $type 收藏类型
   * @author Max.Yu <max@jipu.com>
   */
  public function listUserFav($uid, $type){
    $map['uid'] = $uid;
    $map['type'] = $type;
    $list = $this->where($map)->select();
    $result = get_sub_by_key($list, 'fid');
    return $result;
  }

  /**
   * 更新收藏信息
   * @param array $data 优惠券数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    //已收藏则不添加
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    if(is_fav(UID, $data['fid'])){
      return false;
    }
    $result = ($data['id']) ? $this->save() : $this->add();
    //更新统计
    if($result){
      $this->updateCount($data);
    }
    return $result;
  }

  /**
   * 删除收藏数据
   * @param array $map 查询条件
   * @return boolean 删除结果
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($map){
    $map['uid'] = UID;
    $result = $this->where($map)->delete();
    //更新统计
    if($result){
      $this->updateCount();
    }
    return $result;
  }

  /**
   * 更新当前用户收藏统计数据
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  protected function updateCount($data){
    //更新用户统计
    $map['uid'] = UID;
    $count = $this->where($map)->count();
    $update_user = D('Usercount')->setKeyValue(UID, 'fav_count', $count);
    if($data['type'] == 'item'){
      //更新商品收藏数
      $update_item = D('Item')->where('id = '.$data['fid'])->setInc('favnum');
    }
  }

}
