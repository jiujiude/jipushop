<?php
/**
 * 配送区域管理控制器
 * @version 2015070610 
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Controller;

class AreaController extends AdminController {

  public function index(){
    $pid = I('get.pid');
    $title = I('get.title');
    $pid ? $where['pid'] = $pid : $where['pid'] = 0;
    $title && $where['title'] = array('like', '%'.$title.'%');
    parent::index($where);
  }
  
  function add(){
    //获取上级区域
    I('get.pid') > 0 && $this->data_up = M('Area')->getById(I('get.pid'));
    parent::add();
  }
  
  /**
  * 编辑display前置方法
  * @version 2015070617 
  * @author Justin <justin@jipu.com>
  */
  function _before_edit_display(){
    //获取上级区域
    $this->data['pid'] > 0 && $this->data_up = M('Area')->getById($this->data['pid']);
  }

  /**
   * 删除一个区域
   * @author Max.Yu <max@jipu.com>
   */
  public function remove(){
    $area_id = I('id');
    if(empty($area_id)){
      $this->error('参数错误!');
    }

    //判断该区域下有没有子区域，有则不允许删除
    $child = M('Area')->where(array('pid'=>$area_id))->field('id')->select();
    if(!empty($child)){
      $this->error('请先删除该区域下的子区域');
    }

    //删除该区域信息
    $res = M('Area')->delete($area_id);
    if($res !== false){
      $this->success('删除区域成功！');
    }else{
      $this->error('删除区域失败！');
    }
  }

}
