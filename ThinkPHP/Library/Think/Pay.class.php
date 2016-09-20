<?php

/**
 * 通用支付接口类
 * @author yunwuxin<448901948@qq.com>
 */

namespace Think;

class Pay {

  /**
   * 支付驱动实例
   * @var Object
   */
  private $payer;

  /**
   * 配置参数
   * @var type
   */
  //private $config;

  /**
   * 支付参数
   * @var data
   */
  //private $data;

  /**
   * 构造方法，用于构造支付实例
   * @param string $driver 要使用的支付驱动
   * @param array $config 配置
   * @param array $data 支付参数
   */
  public function __construct($driver, $config = array()) {
    /* 配置 */
    //$pos = strrpos($driver, '\\');
    //$pos = $pos === false ? 0 : $pos + 1;
    //$apitype = strtolower(substr($driver, $pos));

    // 通知和回调路径后续放在配置文件中 TODO:
    //$this->config['notify_url'] = U('Pay/notify', array('apitype' => $apitype, 'method' => 'notify'), false, true);
    //$this->config['return_url'] = U('Pay/notify', array('apitype' => $apitype, 'method' => 'return'), false, true);
    //$this->config['merchant_url'] = U('Pay/notify', array('apitype' => $apitype, 'method' => 'merchant'), false, true);
    //$config = array_merge($this->config, $config);

    /* 设置支付驱动 */
    $class = strpos($driver, '\\') ? $driver : 'Think\\Pay\\Driver\\' . ucfirst(strtolower($driver));
    $this->setDriver($class, $config);
  }

  /**
   * 建立支付请求
   * @param string $class 驱动类名称
   */
  public function buildRequestForm($data) {
    $this->payer->check();
    if($data){
      return $this->payer->buildRequestForm($data);
    }else{
      E('支付参数不完整');
    }
  }

  /**
   * 设置支付驱动
   * @param string $class 驱动类名称
   */
  private function setDriver($class, $config) {
    $this->payer = new $class($config);
    if(!$this->payer){
      E("不存在支付驱动：{$class}");
    }
  }

  public function __call($method, $arguments) {
    if(method_exists($this, $method)) {
      return call_user_func_array(array(&$this, $method), $arguments);
    }elseif(!empty($this->payer) && $this->payer instanceof Pay\Pay && method_exists($this->payer, $method)) {
      return call_user_func_array(array(&$this->payer, $method), $arguments);
    }
  }

}
