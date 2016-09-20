<?php
/**
 * 发送消息模型
 * @version 15101611
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class MessageModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('title', 'require', '消息标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('content', '10,8000', '消息内容至少10个字哦！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    array('send_confirm', 'require', '请确认发送！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('to_uid', '_format_to_uid', self::MODEL_BOTH, 'callback'),
    array('status', 1, self::MODEL_INSERT),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
  );
  
  
  /**
   * 默认为全体用户
   */
  protected function _format_to_uid(){
    return I('post.to_uid') ?: 0;
  }
}
