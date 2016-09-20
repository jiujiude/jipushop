<?php

namespace Addons\Upload;
use Common\Controller\Addon;

/**
 * 附加上传插件
 * @author Max.Yu <max@jipu.com>
 */

class UploadAddon extends Addon{

  public $info = array(
    'name'=>'Upload',
    'title'=>'附加上传',
    'description'=>'用于上传图片、文件的插件',
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
	 * 上传图片钩子
	 * @param array('name'=>'表单name','value'=>'表单对应的值')
	 */
	public function uploadImage($data){
		$this->assign('addons_data', $data);
		$this->assign('addons_config', $this->getConfig());
		$this->display('content');
	}


}