<?php
//+----------------------------------------------------------------------
//| jipushop
//+----------------------------------------------------------------------
//| Copyright (c) 2014 http://www.jipushop.com All rights reserved.
//+----------------------------------------------------------------------
//| Author: Max.Yu <168834615@qq.com>
//+----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
use Admin\Model\PictureModel;

/**
 * 后台专场模型
 * @author Max.Yu <max@jipu.com>
 */
class ActivityModel extends Model{

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
  array('name', 'require', '专场标题未填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
// array('background', 'require', '专场背景图片未上传', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
  array('src', 'getImgsrc', self::MODEL_BOTH, 'callback'),
  array('create_time', NOW_TIME, self::MODEL_INSERT),
  array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取专场详情
   * @param array $id 专场id
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($id, $field = true){
    $map['id'] = $id;
    $info = $this->field($field)->where($map)->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '专场信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新专场信息
   * @param array $data 专场数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    $background = I('post.background');
    if(isset($background)){
      $path = get_cover(I('post.background'), 'path');
      $data['src'] = $path;
      //生成专题图片缩略图
      $this->createThumb($path);
    }
    ($data['id']) ? $this->save() : $id = $this->add();
    //记录行为
    action_log('update_activity', 'activity', $data['id'] ? $data['id'] : $id, UID);
    return $data;
  }

  /**
   * 生成专题图片缩略图
   * @author Max.Yu <max@jipu.com>
   */
  public function createThumb($path = null){
    if(empty($path)){
      return false;
    }

    //实例化图片模型
    $picture_model = D('Picture');

    //获取专题图片缩略图规格配置
    $thumb_size = C('UPLOAD_PIC_THUMB_SIZE.TOPIC_PIC');

    //开始生成缩略图
    if($thumb_size && is_array($thumb_size)){
      foreach($thumb_size as $size){
        get_image_thumb($path, $size['WIDTH'], $size['HEIGHT']);
      }
    }
  }

  /**
   * 更新部分字段，用于首页内容实时更新
   * @param array  $data 手动传入的数据
   * @return boolean fasle 失败 ， int 成功 返回完整的数据
   * @author Max.Yu <max@jipu.com>
   */
  public function updateField($data = null){
    $data = $this->create($data);
    if(empty($data)){
      return false;
    }
    if(I('post.background')){
      $coverArr = get_cover(I('post.background'));
      print_r($coverArr);
      $data['src'] = $coverArr['path'];
    }
    if($data['id']){
      //记录行为
      action_log('update_activity', 'activity', $data['id'], UID);
      return $this->save($data);
    }
  }

  /**
   * 删除专场
   * @return true 删除成功， false 删除失败
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($ids){
    $map['id'] = array('IN', $ids);
    $res = $this->where($map)->delete();
    return $res;
  }

  /**
   * 获取图片路径
   * @return string $result 图片路径
   * @author Max.Yu <max@jipu.com>
   */
  protected function getImgsrc(){
    $id = I('post.background');
    if(!$id){
      return null;
    }
    $imgArr = get_cover($id);
    $src = $imgArr['path'];
    return $src;
  }

  /**
   * 获取专场首字母
   * @return string $result 专场首字母
   * @author Max.Yu <max@jipu.com>
   */
  protected function getLetter(){
    $name = I('post.name');
    if(!$name){
      return false;
    }
    return get_first_letter($name);
  }

}
