<?php
/**
 * 拼团模型
 * @author ezhu <ezhu@jipukeji.com>
 */

namespace Admin\Model;

class JoinItemModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('item_id', 'require', '请选择商品', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('price', 'require', '请选择价格', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('stock', 'require', '请填写库存', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('join_id', 'require', '参数异常', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('join_num', 'require', '请填写参团人数', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('first_price', 'require', '请填写开团价', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('join_price', 'require', '请填写参团价', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );
  
  
  /**
   * 添加数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
      $data = $this->create();
      if(!$data){ //数据对象创建错误
          return false;
      }
      $map['item_id'] = $data['item_id'];
      $map['join_id'] = $data['join_id'];
      $propInfo = I('spc_price');
      $info = array();
      foreach ($propInfo as $key=>$val){
          array_push($info, $key);
      }
      $data['prop_info'] = json_encode($info);
      $res = $this->where($map)->save($data);
      return $res;
  }
  
  
}
