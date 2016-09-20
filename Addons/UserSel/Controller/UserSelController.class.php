<?php

namespace Addons\UserSel\Controller;
use Home\Controller\AddonsController;

class UserSelController extends AddonsController{
  private $listRows = 8;
  /**
   * 获取用户列表
   */
  public function index(){
    $users = I('get.users', '');
    $username = I('get.username', '');
    $tpl = I('get.tpl', 'index');
    //根据昵称过滤uid
    if($username){
      $where['id|username|mobile|nickname'] = array('LIKE', '%'.$username.'%');
    }
    $where['status'] = 1;
    if($users){
      $where['id'] = array('IN', explode(',', $users));
    }
    $page = $this->_page(D('Addons://UserSel/UserSel'), $where,'id DESC');
    $this->assign(array(
      'page' => $page,
      'users' => $users,
    ));
    $this->display($this->_returnTpl($tpl));
  }
  
  /**
   * 获取用户信息
   */
  public function getUserInfo(){
    $uid = I('get.uid', 0);
    $user = D('Addons://UserSel/UserSel')->where(array('id' => $uid))->find();
    $user['nickname'] = get_nickname($user['uid']);
    $this->ajaxReturn($user);
  }
  
  /**
   * 获取自定义模板文件地址 
   */
  protected function _returnTpl($filename){
    $path = dirname(dirname(__FILE__)).'/Tpl/'.$filename.'.html';
    return $path;
  }

  /**
   * 分页数据
   */
  protected function _page($model, $where = array(), $order = 'id DESC'){
    $REQUEST = (array) I('request.');
    $count = $model->where($where)->count();
    $page = new \Think\Page($count, $this->listRows, $REQUEST);
    if($count > $this->listRows){
      $page->rollPage =  5;
      $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
    }
    $p = $page->show();
    $first_row = $page->firstRow;
    $list = $model->where($where)->limit($first_row, $this->listRows)->order($order)->select();
    //sql();
    return array(
      'list' => $list,
      'p' => $p,
    );
  }
}
