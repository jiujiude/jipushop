<?php

namespace Addons\AreaSelect;
use Common\Controller\Addon;

/**
 * 地区三级联动插件
 * @author Max
 */

    class AreaSelectAddon extends Addon{

        public $info = array(
            'name'=>'AreaSelect',
            'title'=>'地区三级联动',
            'description'=>'省市县地区三级联动',
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
         * 地区三级联动钩子
         * @param array('privince'=>'省id', 'district'=>'市id', 'area' => '县/区id', 'tpl' => '模板')
         */
        public function areaSelect($param){
            $this->assign('param', $param);
            $tpl = ($param['tpl']) ? $param['tpl'] : 'widget';
            $this->display($tpl);
        }

    }