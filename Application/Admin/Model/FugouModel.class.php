<?php
/**
 * 复购优惠数据模型
 * @version 15101915
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

class FugouModel extends AdminModel{

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('item_id', 'require', '请选择活动的主商品', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('item_id', '_checkItemId', '您选择的商品已设置复购优惠', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
    array('dis_price', '_checkDisPrice', '请设置合适的优惠金额', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('item_name', '_getItemName', self::MODEL_BOTH, 'callback'),
    array('update_time', NOW_TIME, self::MODEL_BOTH)
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
   * 优惠金额信息
   */
  protected function _checkDisPrice(){
    $item_id = I('post.item_id', 0);
    $dis_price = I('post.dis_price');
    $price = M('Item')->getFieldById($item_id, 'price');
    return $dis_price > 0 && $dis_price < $price;
  }

  /**
   * 获取商品价格
   */
  protected function _getItemName(){
    $item_id = I('post.item_id', 0);
    return M('Item')->getFieldById($item_id, 'name');
  }
}
