<?php

/**
 * admin分组公共模型
 * @version 2015070110 
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

use Common\Model\BaseModel;

class AdminModel extends BaseModel{

   /**
   * 更新部分字段，用于首页内容更新
   * @param array  $data 手动传入的数据
   * @return boolean fasle 失败 ， int  成功 返回完整的数据
   * @author huajie <banhuajie@163.com>
   * @version 2015071009 justin 增加get获取数据
   */
  public function updateField($data = null){
    /* 获取数据对象 */
    $data = $this->create($data) ? $this->create($data) : I('get.');
    if(empty($data)){
      return false;
    }
    if($data['id']){
      return $this->save($data);
    }
  }

  //图片数组自动完成回调函数
  protected function getImages(){
    $images = I('post.images');
    $thumb = I('post.thumb');
    if($images && is_array($images)){
      foreach($images as $key => $value){
        if($value == $thumb){
          unset($images[$key]);
        }
      }
      return $thumb.','.rtrim(arr2str($images), ',');
    }else{
      return $images;
    }
  }

}
