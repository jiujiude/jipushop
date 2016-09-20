<?php
/**
 * UC API调用控制器层
 * 调用方法 A('Uc/User', 'Api')->login($username, $password, $type);
 * @version 2014091512
 */

namespace Common\Api;

abstract class Api{

  /**
   * API调用模型实例
   * @access  protected
   * @var object
   */
  protected $model;

  /**
   * 构造方法，检测相关配置
   */
  public function __construct(){
    $this->_init();
  }

  /**
   * 抽象方法，用于设置模型实例
   */
  abstract protected function _init();

}
