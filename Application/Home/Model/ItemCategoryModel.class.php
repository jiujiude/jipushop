<?php
/**
 * 商品分类模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class ItemCategoryModel extends Model{

  /**
   * 获取分类详细信息
   * @param milit $id 分类ID或标识
   * @param boolean $field 查询字段
   * @return array 分类信息
   * @author Max.Yu <max@jipu.com>
   */
  public function info($id, $field = true){
    //获取分类信息
    $map = array();
    if(is_numeric($id)){ //通过ID查询
      $map['id'] = $id;
    } else { //通过标识查询
      $map['ename'] = $id;
    }
    return $this->field($field)->where($map)->cache(true)->find();
  }

  /**
   * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
   * @param integer $id 分类ID
   * @param boolean $field 查询字段
   * @return array 分类树
   * @author Max.Yu <max@jipu.com>
   */
  public function getTree($id = 0, $field = true){
    //获取当前分类信息
    if($id){
      $info = $this->info($id);
      $id   = $info['id'];
    }

    //获取所有分类
    $map['status']  = 1;
    $list = $this->field($field)->where($map)->cache(true)->order('`sort` ASC, `id` DESC')->select();
    $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);

    //获取返回数据
    if(isset($info)){ //指定分类则返回当前分类极其子分类
      $info['_'] = $list;
    } else { //否则返回所有分类
      $info = $list;
    }

    return $info;
  }

  public function lists($map = array(), $field = true, $order = '`sort` ASC, `id` DESC', $limit = 10){
    $list = $this->where($map)->field($field)->order($order)->limit($limit)->select();
    if($list){
      foreach($list as $key => &$value){
        if($value){
          $value['children'] = $this->getChild($value['id'], 'id, name, ename');
        }
      }
    }
    return $list;
  }

  /**
   * 获取指定分类的级别
   * @param integer $id 分类ID
   * @param boolean $field 查询字段
   * @return array
   * @author Max.Yu <max@jipu.com>
   */
  public function getCategoryLevel($id){
    $info = $this->info($id, 'pid');

    if($info){
      if($info['pid'] == 0){
        $level = 1;
      }else{
        $child = $this->getChild($id, 'id');
        $level = ($child) ? 2 : 3;
      }
    }else{
      $level = 0;
    }

    return $level;
  }

  /**
   * 获取指定分类的父类详细信息
   * @param int $pid 父类ID
   * @param boolean $field 查询字段
   * @return array 父类详细信息
   * @author Max.Yu <max@jipu.com>
   */
  public function getParent($pid, $field = true){
    $map = array();
    if(is_numeric($pid)){
      $map['id'] = $pid;
    } else {
      return false;
    }
    return $this->field($field)->where($map)->cache(true)->find();
  }

  /**
   * 获取指定分类的同级兄弟分类
   * @param integer $id 分类ID
   * @param boolean $field 查询字段
   * @return array
   * @author Max.Yu <max@jipu.com>
   */
  public function getBrother($id, $field = true){
    $info = $this->info($id, 'pid');
    $map = array('pid' => $info['pid'], 'status' => 1);
    return $this->field($field)->where($map)->order('sort')->select();
  }

  /**
   * 获取指定分类的子级分类，若未指定分类则输出一级分类
   * @param integer $id 分类ID，值为0时则输出一级分类
   * @param boolean $field 查询字段
   * @return array
   * @author Max.Yu <max@jipu.com>
   */
  public function getChild($id, $field = true){
    $map = array('pid' => $id, 'status' => 1);
    $result = $this->field($field)->where($map)->order('sort')->select();
    return $result;
  }

}
