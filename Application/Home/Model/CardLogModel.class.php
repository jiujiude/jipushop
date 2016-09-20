<?php
/**
 * 礼品卡使用日志模型
 * @version 2015060808
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;

class CardLogModel extends RelationModel {

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
  );

  /**
   * 自动完成规则
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT)
  );

  /**
   * 礼品卡关联规则
   * @author Max.Yu <max@jipu.com>
   */
  protected $_link = array(
    'Card'=> array(
      'mapping_type' => self::BELONGS_TO,
      'class_name' => 'Card',
      'foreign_key' => 'card_id',
      'mapping_fields' => 'number, name',
    ),
  );

  /**
   * 获取列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map){
    $lists = $this->where($map)->relation('Card')->select();
    return $lists;
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
    return ($data['id']) ? $this->save() : $this->add();
  }

}
