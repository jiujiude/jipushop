<?php
/**
 * 前台UI示例控制器
 * @version 201412200170
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class DemoController extends HomeController {

  //系统首页
  public function index(){
    $this->display();
  }

  public function ajaxLogin(){     
    $this->display();
  }

  public function test(){     
    $this->display();
  }

}