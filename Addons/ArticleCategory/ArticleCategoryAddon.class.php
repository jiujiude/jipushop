<?php

namespace Addons\ArticleCategory;
use Common\Controller\Addon;

/**
 * 后台文章分类联动插件
 * @author Max.Yu <max@jipu.com>
 */
class ArticleCategoryAddon extends Addon{

  public $info = array(
    'name'=>'ArticleCategory',
    'title'=>'文章分类联动',
    'description'=>'文章分类联动',
    'status'=>1,
    'author'=>'Max.Yu',
    'version'=>'1.0'
  );

  public function install(){
    return true;
  }

  public function uninstall(){
    return true;
  }

  /**
   * 商品分类三级联动钩子
   * @param array('category_id'=>'分类id', 'tpl' => '模板')
   */
  public function articleCategory($param){
    $category = D('ArticleCategory')->getTree(0,'id,pid,name,ename,sort,is_display,status');
    $this->assign('category', $category);
    $this->assign('param', $param);
    $this->display('widget');
  }

}