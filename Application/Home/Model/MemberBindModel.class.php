<?php
/**
 * 用户手机、邮箱绑定模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class MemberBindModel extends Model{

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  public function detail($map){
    return $this->where($map)->find();
  }

  /**
   * 更新用户绑定信息
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

}
