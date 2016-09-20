<?php

namespace Addons\ActivitySelect\Controller;
use Home\Controller\AddonsController;

/**
 * 后台专场选择插件控制器
 * @author Max.Yu <max@jipu.com>
 */
class ActivitySelectController extends AddonsController{

	public function getType(){
		$type = C('SALE_ACTIVITY_TYPE');
		echo json_encode($type);
	}

	public function getActivity(){
		$map['tid'] = $_GET['tid'];
   	$list = M('activity')->where($map)->field('id, name')->select();
		echo json_encode($list);
	}
}
