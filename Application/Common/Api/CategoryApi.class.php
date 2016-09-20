<?php
/**
 * 分类API接口
 * @version 2014091512
 */

namespace Common\Api;

class CategoryApi{

  /**
   * 获取分类信息并缓存分类
   * @param integer $id 分类ID
   * @param string $field 要获取的字段名
   * @return string 分类信息
   */
  public static function get_category($model, $id, $field = null){
    static $list;
    //非法分类ID
    if(empty($id) || !is_numeric($id)){
      return '';
    }
    //读取缓存数据
    if(empty($list)){
      $list = S('sys_article_category_list');
    }

    //获取分类名称
    if(!isset($list[$id])){
      $cate = $model->find($id);
      if(!$cate || 1 != $cate['status']){ //不存在分类，或分类被禁用
        return '';
      }
      $list[$id] = $cate;
      S('sys_article_category_list', $list); //更新缓存
    }
    return is_null($field) ? $list[$id] : $list[$id][$field];
  }

  /**
   * 根据ID获取分类标识
   */
  public static function get_category_name($model, $id){
    return self::get_category($model, $id, 'name');
  }

  /**
   * 获取参数的所有父级分类
   * @param int $cid 分类id
   * @return array 参数分类和父类的信息集合
   * @author huajie <banhuajie@163.com>
   */
  public static function get_parent_category($model, $cid){
    if(empty($cid)){
      return false;
    }
    $cates = $model->where(array('status'=>1))->field('id,name,pid')->order('sort')->select();
    $child = self::get_category($model, $cid); //获取参数分类的信息
    $pid = $child['pid'];
    $temp = array();
    $res[] = $child;
    while(true){
      foreach($cates as $key=>$cate){
        if($cate['id'] == $pid){
          $pid = $cate['pid'];
          array_unshift($res, $cate); //将父分类插入到数组第一个元素前
        }
      }
      if($pid == 0){
        break;
      }
    }
    return $res;
  }

}
