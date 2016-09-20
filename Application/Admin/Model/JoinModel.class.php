<?php
/**
 * 拼团模型
 * @author ezhu <ezhu@jipukeji.com>
 */

namespace Admin\Model;

class JoinModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
    array('name', 'require', '活动名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('item_ids', 'require', '请选择商品', self::MUST_VALIDATE, 'regex', self::MODEL_UPDATE),
    array('stime', 'require', '请选择开始时间', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('etime', 'require', '请选择结束时间', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('etime', '_checkExpireTime', '结束时间不能小于开始时间', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('ctime', NOW_TIME, self::MODEL_INSERT),
    array('stime', 'strtotime', self::MODEL_BOTH, 'function'),
    array('etime', 'strtotime', self::MODEL_BOTH, 'function'),
  );
  
  
  /**
   * 检测时间
   * @return boolean
   */
  protected function _checkExpireTime(){
    return strtotime(I('post.stime')) < strtotime(I('post.etime'));
  }
  
  
  /**
   * 添加拼团活动
   * @see \Common\Model\BaseModel::update()
   */
  public function update($data){
      $res = $data['id'] ? $this->save($data) : $this->add($data);
      if($res !== false){
          //记录行为
          action_log('update_'.CONTROLLER_NAME, CONTROLLER_NAME, $data['id'] ? : $res, UID);
      }else{
          $this->error = '操作失败！';
      }
      return $res;
  }
  
  
  /**
   * 获取商品属性选项配置值
   * @author ezhu <ezhu@jipu.com>
   */
  public function getOptionValue($item_info = null, $formtype = null){
      $returnInfo = array();
      $pic_path = array();
  
      if($item_info && is_array($item_info)){
          if($formtype === 'color'){
              $pic = $item_info['pic'];
  
              if($pic && is_array($pic)){
                  foreach($pic as $k => $v) {
                      if($v){
                          $pic_path[] = get_cover($v, 'path');
                      } else{
                          $pic_path[] = null;
                      }
                  }
              }
          }
  
          $property = $item_info['property'];
          if($property && is_array($property)){
              foreach($property as $key => $val) {
                  if($val && is_array($val)){
                      $i = 0;
                      foreach($val as $k => $v) {
                          $option = $this->getOptionByCode($v);
  
                          if($formtype === 'color'){
                              $option['item_pic'] = $pic_path[$i];
                              $i++;
                          }
  
                          $returnInfo[] = $option;
                      }
                  } else{
                      if($formtype === 'input' || $formtype === 'textarea'){
                          $returnInfo[] = $val;
                      }else{
                          $returnInfo[] = $this->getOptionByCode($val);
                      }
                  }
              }
          }
      }
      return $returnInfo;
  }
  
  /**
   * 输出商品属性选项值
   * @author Max.Yu <max@jipu.com>
   */
  public function getOptionByCode($code){
  
      $returnInfo = array();
  
      //实例化属性选项模型
      $PropertyOption = M('PropertyOption');
  
      //定义返回或者操作的字段
      $field = '*';
  
      //查询条件初始化
      $where['code'] = $code;
  
      //获取属性选项值列表
      $returnInfo = $PropertyOption->where($where)->field($field)->find();
  
      return $returnInfo;
  }
  
  
}
