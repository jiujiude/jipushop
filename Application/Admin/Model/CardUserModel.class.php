<?php
/**
 * 后台礼品卡发放模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Think\Model;
use Think\Model\RelationModel;

class CardUserModel extends RelationModel{

  protected $_link = array(
    'User'=> array(
      'mapping_type' => self::BELONGS_TO,
      'class_name' => 'User',
      'foreign_key' => 'uid',
      'mapping_fields' => 'id, username, email, mobile'
    ),
  );

  /**
   * 获取用户领取的礼品卡列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map){
    $lists = $this->where($map)->relation('User')->select();
    return $lists;
  }

}