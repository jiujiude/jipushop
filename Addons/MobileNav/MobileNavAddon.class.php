<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------


namespace Addons\MobileNav;
use Common\Controller\Addon;

/**
 * 开发团队信息插件
 * @author thinkphp
 */

    class MobileNavAddon extends Addon{

        public $info = array(
            'name'=>'MobileNav',
            'title'=>'wap端导航',
            'description'=>'wap端头部返回上一层页面',
            'status'=>1,
            'author'=>'Jipushop',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的AdminIndex钩子方法
        public function mobileTopNav($param){
            $config = $this->getConfig();
            $config = array_merge($config,$param);
            $this->assign('addons_config', $config);
            $this->display('nav');
        }
    }