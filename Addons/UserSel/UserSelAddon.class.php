<?php

namespace Addons\UserSel;
use Common\Controller\Addon;

/**
 * 用户选择器插件
 * @author Max.Yu <max@jipu.com>
 */

	class UserSelAddon extends Addon{

		public $info = array(
			'name'=>'UserSel',
			'title'=>'用户选择器',
			'description'=>'通用型用户选择器',
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
     * 弹窗选择用户钩子
     * @param array('name'=>'文本框名字')
     */
    public function userSel($param){
      $this->assign('param', $param);
      $this->display('widget');
    }
	}