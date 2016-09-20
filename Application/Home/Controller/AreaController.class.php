<?php
/**
 * 前台地区控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class AreaController extends HomeController {

  public function getParent(){
    $map['pid'] = 0;
    $list = M('area')->where($map)->field('id, title')->select();
    echo json_encode($list);
  }

  public function getChild($pid){
    $map['pid'] = $pid;
    $list = M('area')->where($map)->field('id, title')->select();
    echo json_encode($list);
  }

}
