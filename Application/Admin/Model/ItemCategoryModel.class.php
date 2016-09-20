<?php
/**
 * 分类模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Think\Model;

class ItemCategoryModel extends Model{

  protected $_validate = array(
    array('name', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    array('ename', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    array('ename', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
    array('meta_title', '1,50', '网页标题不能超过50个字符', self::VALUE_VALIDATE , 'length', self::MODEL_BOTH),
    array('meta_keywords', '1,255', '网页关键字不能超过255个字符', self::VALUE_VALIDATE , 'length', self::MODEL_BOTH),
    array('meta_description', '1,255', '网页描述不能超过255个字符', self::VALUE_VALIDATE , 'length', self::MODEL_BOTH),
  );

  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
    array('status', '1', self::MODEL_BOTH),
  );


  /**
   * 获取分类详细信息
   * @param  milit   $id 分类ID或标识
   * @param  boolean $field 查询字段
   * @return array     分类信息
   * @author Max.Yu <max@jipu.com>
   */
  public function info($id, $field = true){
    /* 获取分类信息 */
    $map = array();
    if(is_numeric($id)){ //通过ID查询
      $map['id'] = $id;
    }else{ //通过标识查询
      $map['ename'] = $id;
    }
    return $this->field($field)->where($map)->find();
  }

  public function getName($id){
    /* 获取分类名称 */
    return $this->where('id = '.$id)->getField('name');
  }

  /**
   * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
   * @param  integer $id    分类ID
   * @param  boolean $field 查询字段
   * @return array          分类树
   * @author Max.Yu <max@jipu.com>
   */
  public function getTree($id = 0, $field = true){
    /* 获取当前分类信息 */
    if($id){
      $info = $this->info($id);
      $id   = $info['id'];
    }

    /* 获取所有分类 */
    $map  = array('status' => array('gt', -1));
    $list = $this->field($field)->where($map)->order('sort')->select();
    $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);

    /* 获取返回数据 */
    if(isset($info)){ //指定分类则返回当前分类极其子分类
      $info['_'] = $list;
    }else{ //否则返回所有分类
      $info = $list;
    }

    return $info;
  }

  /**
   * 获取指定分类的同级分类
   * @param  integer $id    分类ID
   * @param  boolean $field 查询字段
   * @return array
   * @author Max.Yu <max@jipu.com>
   */
  public function getBrother($id, $field = true){
    $info = $this->info($id, 'pid');
    $map = array('pid' => $info['pid'], 'status' => 1);
    return $this->field($field)->where($map)->order('sort')->select();
  }

  /**
   * 更新分类信息
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    $data = $this->create();
    if(!$data){ //数据对象创建错误
      return false;
    }

    /* 添加或更新数据 */
    if(empty($data['id'])){
      $res = $this->add();
    }else{
      $res = $this->save();
    }

    //更新分类缓存
    S('sys_item_category_list', null);

    //记录行为
    action_log('update_category', 'category', $data['id'] ? $data['id'] : $res, UID);

    return $res;
  }
}
