<?php
/**
 * 满减模型
 * @version 2015092916
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

class ManjianModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('name', 'require', '活动名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('expire_time', 'require', '请选择结束时间', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('man', 'require', '满多少不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('jian', 'require', '减多少不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
    array('start_time', '_getStartTime', self::MODEL_BOTH, 'callback'),
    array('expire_time', 'strtotime', self::MODEL_BOTH, 'function'),
  );
  
  protected function _getStartTime(){
    return I('post.start_time') ? strtotime(I('post.start_time')) : time();
  }
  
}
