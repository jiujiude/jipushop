<?php

namespace Addons\ItemSel\Controller;
use Home\Controller\AddonsController;

class ItemSelController extends AddonsController{
  private $listRows = 8;
  /**
   * 获取商品列表
   */
  public function index(){
    $items = I('get.items', '');
    $keywords = I('get.keywords', '');
    $tpl = I('get.tpl', 'index');
    $active = I('get.active');
    $where = array('status' => 1);
    //活动获取产品
    switch ($active){
        case 'join' :  //拼团活动，只能添加一个活动产品
            $joinIds = A('Home/Join','Event')->getItemIds();
            $where['id'] = array('not in',$joinIds);
        break;
    }
    if($items){
      $where['id'] = array('IN', explode(',', $items));
    }
    
    //分类过滤
    I('cid_1') && $where['cid_1'] = I('cid_1');
    I('cid_2') && $where['cid_2'] = I('cid_2');
    I('cid_3') && $where['cid_3'] = I('cid_3');
    
    if($keywords){
      $where['category|name|summary'] = array('LIKE', '%'.$keywords.'%');
    }
    $page = $this->_page(M('Item'), $where,'sort ASC,id DESC');
    foreach($page['list'] as &$value){
      if($value['thumb']){
        $coverArr = get_cover($value['thumb']);
        $value['cover_path_tiny'] = get_image_thumb($coverArr['path'], 100, 100);
      }
    }
    $this->assign(array(
      'page' => $page,
      'items' => $items,
      'item_list' => I('item_list')
    ));
    $this->display($this->_returnTpl($tpl));
  }
  
  /**
   * 获取商品信息
   */
  public function getItemInfo(){
    $item_id = I('get.item_id', 0, intval);
    $item = M('Item')->field('id, name, thumb, subname, price')->getById($item_id);
    $item['thumb'] = get_cover($item['thumb'], 'path');
    $this->ajaxReturn($item);
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
    $page->rollPage = 6;
    if($count > $this->listRows){
      $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
    }
    $p = $page->show();
    $first_row = $page->firstRow;
    $list = $model->where($where)->limit($first_row, $this->listRows)->order($order)->select();
    return array(
      'list' => $list,
      'p' => $p,
    );
  }
}
