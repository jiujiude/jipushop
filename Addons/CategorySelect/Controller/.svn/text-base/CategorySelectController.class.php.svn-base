<?php

namespace Addons\CategorySelect\Controller;
use Home\Controller\AddonsController;

/**
 * 后台商品分类三级联动插件控制器
 * @author Jacky.Liu <273984177@qq.com>
 */
class CategorySelectController extends AddonsController{

	public function getParent(){
		$map['pid'] = 0;
		$list = M('item_category')->where($map)->field('id, name')->select();
	   	echo json_encode($list);
	}

	public function getChild(){
		$map['pid'] = $_GET['pid'];
   		$list = M('item_category')->where($map)->field('id, name')->select();
		echo json_encode($list);
	}
}
