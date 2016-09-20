<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------


namespace Addons\Language;
use Common\Controller\Addon;

/**
 * 开发团队信息插件
 * @author thinkphp
 */

    class LanguageAddon extends Addon{

        public $info = array(
            'name'=>'Language',
            'title'=>'简繁体转换',
            'description'=>'简繁体转换',
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
        public function pageHeader($param){
            $config = $this->getConfig();
            $this->assign('addons_config', $config);
            if($config['display'] && $config['lang'])
                $this->display('widget');
        }
    }