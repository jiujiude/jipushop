<?php
/**
 * 订单评价模型
 * @version 2014090812
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;

class ItemCommentModel extends RelationModel {

  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
    array('star_amount', '/^[1-5]\d*$/', '请对商品评星', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    array('content', 'require', '标题不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH)
  );

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 关联规则
   * @var array
   */
  protected $_link = array(
    'Member'=> array(
      'mapping_type' => self::HAS_ONE,
      //'class_name' => 'Member',
      'foreign_key' => 'uid',
      'mapping_key' => 'uid',
      'mapping_fields' => 'nickname, sex, avatar'
    )
  );

  /**
   * 获取列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map){
    $lists = $this->where($map)->relation('Member')->order('create_time desc')->select();
    return $lists;
  }

  public function detail($map){
    return $this->where($map)->relation('Member')->find();
  }

  /**
   * 更新信息
   * @param array $data 数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    //已评价则不能再次评价
    if(is_comment($data['uid'], $data['item_id'])){
      return false;
    }
    $result = ($data['id']) ? $this->save() : $this->add();
    return $result;
  }
}