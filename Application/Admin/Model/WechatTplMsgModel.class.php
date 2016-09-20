<?php
/**
 * 微信模板消息模型
 * @version 2015080416 
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class WechatTplMsgModel extends AdminModel{

  protected $_validate = array(
    array('tpl_id', 'require', '模板ID不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
  );
  
  /**
   * 更新记录
   * @param array $data 更新数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    ($data['id']) ? $this->save() : $id = $this->add();
    return $data;
  }
}
