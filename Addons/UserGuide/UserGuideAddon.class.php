<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------


namespace Addons\UserGuide;
use Common\Controller\Addon;

/**
 * 用户操作引导插件
 * @author thinkphp
 */

    class UserGuideAddon extends Addon{

        public $info = array(
            'name'=>'UserGuide',
            'title'=>'Jipushop操作帮助',
            'description'=>'Jipushop用户操作快速上手',
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
        public function AdminIndex($param){
            $config = $this->getConfig();
            $this->assign('addons_config', $config);
            if($config['display']){
                $info['user'] = M('Member')->count();
                $info['item'] = M('Item')->count();
                $info['order'] = M('Order')->where(array('status' => array('egt', 1)))->count();
                $info['action'] = M('ActionLog')->count();
                $this->assign('info',$info);
                $this->display('widget');
            }
        }
    }