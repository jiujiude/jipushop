<?php
/**
 * 首页Widget
 * 用于调用首页相关数据
 * @version 2015020513
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Widget;

class IndexWidget extends BaseWidget{

  /**
   * 顶部浮动导航
   * @author Max.Yu <max@jipu.com>
   */
  public function fixedNav(){
    $this->display('Widget/fixedNav');
  }

}