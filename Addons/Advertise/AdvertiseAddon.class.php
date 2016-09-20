<?php

namespace Addons\Advertise;
use Common\Controller\Addon;

/**
 * 广告插件
 * @author Max
 */

  class AdvertiseAddon extends Addon{

    public $info = array(
      'name' => 'Advertise',
      'title' => '广告',
      'description' => '用于调用广告',
      'status' => 1,
      'author' => 'Max.Yu',
      'version' => '1.0'
    );

    public $admin_list = array(
      'model' => 'Advertise', //要查的表
      'fields' => '*', //要查的字段
      'map' => '', //查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
      'order' => 'id desc', //排序,
      'listKey' => array( //这里定义的是除了id序号外的表格里字段显示的表头名
        'tid' => '位置',
        'title' => '标题',
        'image' => '图片'
      ),
    );

    public function install(){
      return true;
    }

    public function uninstall(){
      return true;
    }

    //实现的advertise钩子方法
    public function advertise($param){
      $map = $param['where'];
      if($param['num'] <= 1){
        $data = D('Advertise')->where($map)->find();
        if($data){
          $data['image_src'] = get_cover($data['image'], path);
        }
        $modality = 'image';
      }else{
        $data = D('Advertise')->where($map)->order('sort ASC')->limit($param['num'])->select();
        if($data){
          foreach($data as $key => &$value){
            $value['image_src'] = get_cover($value['image'], path);
          }
        }
        $modality = 'slide';
      }

      $this->assign(array(
        'param' => $param,
        'data' => $data,
        'modality' => $modality
      ));

      $tpl = ($param['tpl']) ? $param['tpl'] : 'default';
      if(is_mobile()){
        $tpl.= 'Mobile';
      }

      // print_r($data);

      $this->display($tpl);
    }

  }