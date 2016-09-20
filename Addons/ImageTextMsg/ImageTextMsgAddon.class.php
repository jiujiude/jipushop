<?php

namespace Addons\ImageTextMsg;
use Common\Controller\Addon;

/**
 * 微信推送内容选择器插件
 * @author Max.Yu <max@jipu.com>
 */

class ImageTextMsgAddon extends Addon{

		public $info = array(
			'name'=>'ImageTextMsg',
			'title'=>'图文消息内容选择器',
			'description'=>'微信推送内容选择器',
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

    //实现的AdminIndex钩子方法
    public function imageTextMsg($param = null){
      if(empty($param['event'])){
        $param['event'] = 'event-'.get_randstr(8);
      }
      $param['manage_page'] = addons_url("ImageTextMsg://ImageTextMsg/index", array('event' => $param['event']));
      $param['detail_page'] = addons_url("ImageTextMsg://ImageTextMsg/detail", array('event' => $param['event']));
      $param['saveorder_page'] = addons_url("ImageTextMsg://ImageTextMsg/saveOrder", array('event' => $param['event']));
      $this->assign('param', $param);
      $this->display('widget');
    }
}