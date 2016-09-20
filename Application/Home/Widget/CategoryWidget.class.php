<?php
/**
 * 分类widget
 * 用于动态调用分类信息
 * @version 2014102015
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Widget;

use Think\Controller;

class CategoryWidget extends Controller{
	
	/**
	 * 显示指定分类的同级分类或子分类列表
	 */
	public function lists($cate, $child = false){
		$field = 'id,name,pid,title,link_id';
		if($child){
			$category = D('Category')->getTree($cate, $field);
			$category = $category['_'];
		}else{
			$category = D('Category')->getSameLevel($cate, $field);
		}
		$this->category = $category;
		$this->current = $cate;
		$this->display('Category/lists');
	}
	
}
