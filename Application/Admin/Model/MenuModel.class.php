<?php
/**
 * 插件模型
 * @author yangweijie <yangweijiester@gmail.com>
 */

namespace Admin\Model;

use Think\Model;

class MenuModel extends Model {

  protected $_validate = array(
    array('title','require','标题必须填写'), 
    array('url','require','链接必须填写'), 
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('title', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
    array('status', '1', self::MODEL_INSERT),
  );

}