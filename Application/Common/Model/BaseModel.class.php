<?php
/**
 * 项目公用模型
 * 方法必须是共有
 * @version 20150521
 * @author Justin <justin@jipu.com>
 */

namespace Common\Model;

use Think\Model;

class BaseModel extends Model{
  
  /**
   * 获取详情页数据
   * @param integer $id 文档ID
   * @return array 详细数据
   */
  function detail($id, $where = array(), $field = true){
    //获取基础数据
    $info = $this->field(true)->where($where)->where(array('id' => $id))->find();
    //2015070617 Justin 增加!$info['status']防止status字段不存在
    if(is_array($info) && (!$info['status'] || $info['status'] == 1)){
      return $info;
    }else{
      $this->error = '数据被禁用或已删除！';
      return false;
    }
  }
  
  /**
   * 更新数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    //去除图片id字符串两端多余的逗号，解决图片显示不出来的BUG
    $data['images'] && $data['images'] = trim($data['images'], ',');
    if(!$data){ //数据对象创建错误
      return false;
    }
    $res = $data['id'] ? $this->save($data) : $this->add($data);
    //记录行为
    action_log('update_'.CONTROLLER_NAME, CONTROLLER_NAME, $data['id'] ? $data['id'] : $res, UID);
    return $res;
  }
  
}