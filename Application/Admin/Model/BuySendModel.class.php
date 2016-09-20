<?php
/**
 * 买送模型
 * @version 2015100915
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class BuySendModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('item_id', 'require', '请选择活动的主商品', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('item_id', '_checkItemId', '您选择的商品已参加买送活动', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
    array('expire_time', 'require', '请选择结束时间', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('item_id', '_checkSendItem', '请选择赠品', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
    array('send_item', '_formatSendItem', self::MODEL_BOTH, 'callback'),
    array('start_time', '_getStartTime', self::MODEL_BOTH, 'callback'),
    array('expire_time', 'strtotime', self::MODEL_BOTH, 'function'),
  );
  
  /**
   * 检测活动主商品ID
   */
  protected function _checkItemId(){
    $item_id = I('post.item_id', 0);
    $id = I('post.id', 0);
    $line = $this->where(array('item_id' => $item_id, 'status' => 1))->find();
    return empty($line) || $line['id'] == $id;
  }


  /**
   * 获取活动开始时间
   */
  protected function _getStartTime(){
    return I('post.start_time') ? strtotime(I('post.start_time')) : time();
  }
  
  /**
   * 检测处理赠品信息
   */
  protected function _checkSendItem(){
    $item_num = I('post.item_num');
    if($item_num){
      $_array = array();
      foreach($item_num as $k => $v){
        $_array[] = array('id' => $k, 'num' => $v);
      }
      $_POST['send_item'] = $_array;
    }
    return !empty($item_num);
  }

  /**
   * 格式化赠品信息
   */
  public function _formatSendItem(){
    return json_encode(I('post.send_item'));
  }
  
}
