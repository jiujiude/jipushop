<?php

namespace Addons\ItemSelect;
use Common\Controller\Addon;

/**
 * 弹窗选择商品插件
 * @author Max.Yu <max@jipu.com>
 */

  class ItemSelectAddon extends Addon{

    public $info = array(
      'name'=>'ItemSelect',
      'title'=>'弹窗选择商品',
      'description'=>'弹窗选择商品',
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
     * 弹窗选择商品钩子
     * @param array('items'=>'已选择商品id')
     */
    public function itemSelect($param){
      $param['name'] = ($param['name']) ? $param['name'] : 'items';
      if($param['value']){
        $item_ids = explode(',', $param['value']);
        if(is_array($item_ids)){
          foreach($item_ids as $key => $value){
            $param['item_ids'][$value] = $value;
          }
        }
      }
      $param['item_count'] = $param['item_ids'] ? count($param['item_ids']) : 0;
      $param['item_ids'] = json_encode($param['item_ids']);
      $this->assign('param', $param);
      $tpl = ($param['tpl']) ? $param['tpl'] : 'widget';
      $this->display($tpl);
    }

  }