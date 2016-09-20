<?php

/**
 * 配送区域模型
 * @version 2015070610 
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

class AreaModel extends AdminModel{

  protected $_validate = array(
    array('title', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
  );

}
