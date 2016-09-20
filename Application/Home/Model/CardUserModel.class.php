<?php
/**
 * 礼品卡用户模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;

class CardUserModel extends RelationModel {

  /**
   *自动完成规则
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   *关联规则
   * @author Max.Yu <max@jipu.com>
   */
  protected $_link = array(
    'Card'=> array(
      'mapping_type' => self::BELONGS_TO,
      'class_name' => 'Card',
      'foreign_key' => 'card_id',
    ),
  );

  /**
   * 获取用户礼品卡列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map,$limit=20){
    //更新礼品卡过期状态
    D('Card')->updateExpireStatus();

    //输出礼品卡数据
    $lists = $this->where($map)->relation('Card')->order('create_time desc')->limit($limit)->select();
    return $lists;
  }

  /**
   * 分页形式获取用户礼品卡列表
   * @author Max.Yu <max@jipu.com>
   */
  public function listsPage($map){
    //更新礼品卡过期状态
    D('Card')->updateExpireStatus();
    
    //输出礼品卡分页数据
    $prefix = C('DB_PREFIX');
    $l_table = $prefix.'card_user';
    $r_table = $prefix.'card';
    $field = 'cu.*, c.id, c.number, c.name, c.amount, c.balance, c.is_expire, c.expire_time, c.is_use';
    $model = M()->table($l_table.' cu')->join($r_table.' c ON cu.card_id = c.id');
    $lists = A('Page', 'Event')->lists($model, $map, 'cu.create_time DESC', 15, NULL, $field);
    return $lists;
  }

  /**
   * 获取礼品卡详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map){
    return $this->where($map)->relation('Card')->find();
  }

  /**
   * 更新礼品卡信息
   * @param array $data 礼品卡数据
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
      $this->updateCount();
    }
    return $result;
  }

  /**
   * 更新礼品卡信息，条件更新
   * @param array $data 礼品卡数据
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
   * 删除礼品卡数据
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
   * 更新当前用户礼品卡统计数据
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  protected function updateCount($data){
    //更新用户统计
    $map['uid'] = UID;
    $count = $this->where($map)->count();
    $update_user = D('Usercount')->setKeyValue(UID, 'card_count', $count);
  }

}
