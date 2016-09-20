<?php
/**
 * 找回密码模型
 * @version 2014080811
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class FindPasswordModel extends Model {

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT)
  );

  /**
   * 更新找回密码信息
   * @param array $data 找回密码数据
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
