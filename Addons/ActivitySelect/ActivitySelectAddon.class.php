<?php

namespace Addons\ActivitySelect;
use Common\Controller\Addon;

/**
 * 后台专场选择插件
 * @author Max.Yu <max@jipu.com>
 */
class ActivitySelectAddon extends Addon{

	public $info = array(
		'name'=>'ActivitySelect',
		'title'=>'专场选择',
		'description'=>'商品专场选择，后期增加搜索支持',
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
	 * 专场选择钩子
	 * @param array('tid'=>'专场id', 'tpl' => '模板')
	 */
	public function activitySelect($param){
		$this->assign('param', $param);
		$this->display('widget');
	}

}