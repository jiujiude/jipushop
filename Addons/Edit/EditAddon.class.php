<?php

namespace Addons\Edit;
use Common\Controller\Addon;

/**
 * 编辑器插件
 * @author Max.Yu <max@jipu.com>
 */

	class EditAddon extends Addon{

		public $info = array(
			'name'=>'Edit',
			'title'=>'编辑器',
			'description'=>'百度UMEditor',
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
		 * 编辑器挂载的后台文档模型文章内容钩子
		 * @param array('name'=>'表单name','value'=>'表单对应的值')
		 */
		public function Edit($data){
			$this->assign('addons_data', $data);
			$this->display('content');
		}


	}