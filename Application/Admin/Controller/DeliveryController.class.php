<?php
/**
 * 后台配送控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;
use Think\Page;

class DeliveryController extends AdminController{

  /**
   * 配送首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->display();
  }

  /**
   * 运费模板
   * @author Max.Yu <max@jipu.com>
   */
  public function model(){
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->display();
  }

}