<?php

namespace Addons\UploadImages;
use Common\Controller\Addon;

/**
 * 图片批量上传插件
 * @author Max.Yu <max@jipu.com>
 */

class UploadImagesAddon extends Addon{

  public $info = array(
    'name' => 'UploadImages',
    'title' => '图片批量上传',
    'description' => '图片批量上传插件',
    'status' => 1,
    'author' => 'Max.Yu',
    'version' => '1.1'
  );

  public function install(){
    return true;
  }

  public function uninstall(){
    return true;
  }

  //实现的UploadImages钩子方法
  public function uploadImages($param){
    $param['images'] = get_images_info($param['value'], 'id, path');
    $this->assign('param', $param);
    $tpl = ($param['tpl']) ? $param['tpl'] : 'upload';
    $this->display($tpl);
  }
}