<?php
/**
 * 后台促销控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class PromoteController extends AdminController{

  /**
   * 促销工具列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->meta_title = '营销工具';
    $this->display();
  }

}