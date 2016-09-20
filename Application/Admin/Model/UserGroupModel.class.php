<?php
/**
 * 会员等级模型
 * @version 2015061610
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

class UserGroupModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('title', 'require', '用户组名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('description', 'require', '用户组描述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array( 
  );
  
  /**
   * 获取会员等级
   * @return array 会员等级数组
   * @version 2015061618
   * @author Justin <justin@jipu.com>
   */
  function getUserGroup(){
    $where['status'] = 1;
    return $this->where($where)->field('id,title')->select();
  }
  
}

