<?php
/**
 * 红包模型
 * @version 15121617
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class RedPackageModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('name', 'require', '活动名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('expire_time', 'require', '请选择领取截止时间', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('info', '5,50', '祝福语长度为5-50个字符', self::EXISTS_VALIDATE, 'length'),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT)
  );
  
  
  /**
   * 保存数据
   */
  public function _before_insert(&$data, $options){
    if($data['number'] == 0){
      $this->error = '红包个数必须为正整数';
      return false;
    }
    if(!is_numeric($data['amount'])){
      $this->error = '请填写总金额';
      return false;
    }
    if(($data['number'] * 0.01) > $data['amount']){
      $this->error = '单个红包金额最低 0.01 元';
      return false;
    }
    $data['code'] = $this->_getCode();
    parent::_before_update($data, $options);
  }
  
  /**
   * 获取code
   */
  protected function _getCode(){
    $str = get_randstr(16);
    $line = $this->getByCode($str);
    if(empty($line)){
      return $str;
    }else{
      return $this->_getCode();
    }
  }
}
