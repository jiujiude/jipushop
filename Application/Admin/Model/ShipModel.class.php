<?php
/**
 * 收货人模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Admin\Model\AdminModel;

class ShipModel extends AdminModel {
  
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('delivery_name', 'require', '请选择快递公司', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('delivery_sn', 'require', '快递单号不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );
  
  /**
   * 插入成功后的回调方法
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected function _after_insert($data,$options){
    //更新发货状态(已发货，待确认收货)、发货时间
    $save_data = array('o_status' => 201, 'shipping_time' => time());
    $where['id'] = $data['order_id'];
    $order = M('Order')->where($where)->save($save_data);
    //更新用户积分
    if($order){
      $this->_update_user_score($data['order_id'], $data['order_sn']);
    }
    //修改打包状态
    M('Order')->getFieldById('is_packed', $data['order_id']) != 1 && M('Order')-> where($where)->setField('is_packed','1');
    //记录行为
    action_log('add_order_ship', 'Ship', $id, UID);
    //发送微信模板消息
    A('Home/WechatTplMsg', 'Event')->wechatTplNotice('shipped', $data);
  }
  
  /**
   * 根据订单中商品设置的积分数量更新下单用户的积分
   * @param integer $order_id 订单ID
   * @param string  $order_sn 订单编号
   * @author Max.Yu <max@jipu.com>
   */
  private function _update_user_score($order_id = null, $order_sn = null){
    if(empty($order_id) || empty($order_sn)){
      return false;
    }

    //获取订单用户ID
    $uid = M('Order')->getFieldById($order_id, 'uid');

    //获取订单商品ID和对应的购买数量
    $order_items = M('OrderItem')->where(array('order_id' => $order_id))->getField('item_id,quantity', true);

    //根据订单商品ID和购买数量计算订单总积分
    $total_credit = 0;
    if($uid && $order_items && is_array($order_items)){
      foreach($order_items AS $key => $val){
        $item_credit = M('Item')->getFieldById($key, 'credit');
        if($item_credit && is_numeric($item_credit)){
          $total_credit = $total_credit + ($item_credit * $val);
        }
      }

      //更新订单用户积分
      if($total_credit){
        $result = M('Member')->where(array('uid' => $uid))->setInc('score', $total_credit);
        if($result){
          //记录积分日志
          $data = array(
            'uid' => $uid,
            'order_id' => $order_id,
            'order_sn' => $order_sn,
            'type' => 'in',
            'amount' => $total_credit,
            'memo' => '购物订单完成奖励积分',
            'create_time' => NOW_TIME,
          );
          M('ScoreLog')->add($data);
        }
      }
    }
  }
  
}