<?php
/**
 * 供应商事件接口
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Event;

class SupplierEvent{
  
  /**
   * 供应商身份登录后处理菜单信息
   */
  public function menusInit($menus){
    foreach($menus['child'] as $k => $v){
      if($k == '供应商信息'){
        unset($menus['child'][$k]);
      }
    }
    if(CONTROLLER_NAME == 'Supplier'){
      foreach($menus['main'] as &$m){
        $m['class'] = '';
      }
      $menus['child'] = array(
        '供应商信息' => M('Menu')->where('`group` = "供应商信息"')->select()
      );
    }
    $menus['main'][] = array(
      'id' => 0,
      'title' => '设置',
      'url' => 'Supplier/index',
      'class' => CONTROLLER_NAME == 'Supplier' ? 'current' : ''
    );
    return $menus;
  }
}
