<?php

namespace Addons\ArticleCategory\Controller;
use Home\Controller\AddonsController;

/**
 * 后台文章分类联动插件控制器
 * @author Max.Yu <max@jipu.com>
 */
class ArticleCategoryController extends AddonsController{

  public function getParent(){
    $map['pid'] = 0;
    $list = M('ArticleCategory')->where($map)->field('id, name')->select();
    echo json_encode($list);
  }

  public function getChild(){
    $map['pid'] = $_GET['pid'];
    $list = M('ArticleCategory')->where($map)->field('id, name')->select();
    echo json_encode($list);
  }
}
