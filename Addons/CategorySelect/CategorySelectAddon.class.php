<?php

namespace Addons\CategorySelect;
use Common\Controller\Addon;

/**
 * 后台商品分类三级联动插件
 * @author Max.Yu <max@jipu.com>
 */
class CategorySelectAddon extends Addon{

  public $info = array(
    'name'=>'CategorySelect',
    'title'=>'商品分类三级联动',
    'description'=>'商品分类三级联动',
    'status'=>1,
    'author'=>'Max.Yu',
    'version'=>'0.1'
  );

  public function install(){
    return true;
  }

  public function uninstall(){
    return true;
  }

  /**
   * 商品分类三级联动钩子
   * @param array('cid_1'=>'一级分类id', 'cid_2'=>'二级分类id', 'cid' => '三级分类id', 'tpl' => '模板')
   */
  public function categorySelect($param){
    $this->assign('param', $param);
    $this->display('widget');
  }

}