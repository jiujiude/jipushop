<?php

namespace Addons\AreaSelect\Controller;
use Home\Controller\AddonsController;

class AreaSelectController extends AddonsController{

  public function getParent(){
    $map['pid'] = 0;
    $list = M('area')->where($map)->field('id, title')->select();
    echo json_encode($list);
  }

  public function getChild(){
    $map['pid'] = $_GET['pid'];
    $list = M('area')->where($map)->field('id, title')->select();
    echo json_encode($list);
  }

}
