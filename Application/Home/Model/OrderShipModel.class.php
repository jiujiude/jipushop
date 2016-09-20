<?php
/**
 * 订单收货信息模型
 * @version 2014102014
 * @author Justin <justin@jipu.com>
 */

namespace Home\Model;

class OrderShipModel extends HomeModel {

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH)
  );

}
