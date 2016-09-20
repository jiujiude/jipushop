<?php
/**
 * 搜索模型
 * @version 2015102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class SearchModel extends Model {

  /**
   * 获取搜索关键词列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map, $order = '`num` desc', $field = true){
    $list = $this->where($map)->field($field)->order($order)->select();
    return $list;
  }

  /**
   * 更新关键词信息
   * @param array $data 搜索关键词数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    $result = ($data['id']) ? $this->save() : $this->add();
    return $result;
  }

  public function hasKeyword($keyword){
    $map['keyword'] = $keyword;
    return $this->where($map)->find();
  }

}
