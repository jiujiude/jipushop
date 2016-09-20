<?php
/**
 * 前台礼品卡领取控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;
use Think\Page;

class CardUserController extends HomeController {

	private $CardUser;

	public function _initialize(){
		parent::_initialize();
		//用户登录验证
		parent::login();
		$this->CardUser = D('CardUser');
	}

}