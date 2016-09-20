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
 * 后台广告模型
 * @author Max.Yu <max@jipu.com>
 */
class AdvertiseModel extends Model{

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
  array('tid', 'require', '请选择广告位置', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  array('title', 'require', '广告标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  array('title', '1,60', '广告标题不能超过60个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
  array('image', 'require', '广告图片不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  array('link', 'require', '广告链接地址不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
  array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取广告列表
   * @param array $map 查询条件参数
   * @param string $order 排序规则
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 订单列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map = array(), $field = true, $order = '`sort` ASC, `update_time` DESC', $limit = '10'){
    $lists = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    $type = C('ADVERTISE_TYPE');
    if($lists){
      foreach($lists as $key => &$value){
        $value['tid_text'] = $type[$value['tid']];
        $value['src'] = get_cover($value['image'], 'path');
      }
    }
    return $lists;
  }

  /**
   * 获取广告详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '广告信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新广告信息
   * @param array $data 广告数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);

    if(!$data){
      return false;
    }

    if(I('post.image')){
      $path = get_cover(I('post.image'), 'path');
      $data['src'] = $path;

      //生成广告图片缩略图
      //$this->createThumb($path, $data['tid']);
    }

    ($data['id']) ? $this->save() : $id = $this->add();
    //记录行为
    action_log('update_advertise', 'advertise', $data['id'] ? $data['id'] : $id, UID);
    return $data;
  }

  /**
   * 生成广告图片缩略图
   * @author Max.Yu <max@jipu.com>
   */
  public function createThumb($path = null, $tid = null){
    if(empty($path)){
      return false;
    }

    //实例化图片模型
    $picture_model = D('Picture');

    //获取广告图片缩略图规格配置
    $thumb_size = C('UPLOAD_PIC_THUMB_SIZE.AD_PIC');
    switch ($tid){
      case 1: //首页顶部幻灯广告
        $thumb_size = $thumb_size['INDEX_SLIDE'];
        break;
      case 2: //首页中部横幅广告
        $thumb_size = $thumb_size['INDEX_MIDDLE_BANNER'];
        break;
      case 3: //首页底部横幅广告
        $thumb_size = $thumb_size['INDEX_BOTTOM_BANNER'];
        break;
    }

    //开始生成缩略图
    if($thumb_size && is_array($thumb_size)){
      foreach($thumb_size as $size){
        get_image_thumb($path, $size['WIDTH'], $size['HEIGHT']);
      }
    }
  }

  /**
   * 删除广告
   * @return true 删除成功， false 删除失败
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($ids){
    $map['id'] = array('IN', $ids);
    $res = $this->where($map)->delete();
    //记录行为
    action_log('update_advertise', 'advertise', $ids, UID);
    return $res;
  }

}
