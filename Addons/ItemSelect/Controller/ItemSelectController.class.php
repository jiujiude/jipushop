<?php

namespace Addons\ItemSelect\Controller;
use Home\Controller\AddonsController;

class ItemSelectController extends AddonsController{

  public function selectItem(){
    if(I('request.item_ids')){
      $current_items = I('request.item_ids');
      $current_items_arr = explode(',', $current_items);
    }
    $field = 'id, name, price, thumb';
    
    // $cid_2 = I('request.cid_2');
    // $cid_3 = I('request.cid_3');
    // $keyword = I('request.keyword');
    $cid = I('request.cid');

    if(!I('request.p')){ 
      $where['status'] = 1;
      $data = D('Item')->listsAjax($where, $field, '`sort` ASC');
      if($data['lists']){
        foreach($data['lists'] as $key => &$value){
          if($value['thumb']){
            $coverArr = get_cover($value['thumb']);
            $value['cover_path_tiny'] = get_image_thumb($coverArr['path'], 100, 100);
          }
          if(in_array($value['id'], $current_items_arr)){
            $value['has_select'] = 1;
          }
        }
      }
      $data['count'] = $current_items_arr ? count($current_items_arr) : 0;
      $this->assign('data', $data);
      $this->display('Item/selectitem');
    }else{
      $page = I('request.p');
      $cid = I('request.cid');
      $where['status'] = array('in', '0,1');
      if($cid){
        $where['_string'] = 'cid_1='.$cid.' OR cid_2='.$cid.' OR cid_3='.$cid;
      }
      $data = D('Item')->listsAjax($where, $field, '`sort` ASC', 5, $page);
      if($data['lists']){
        foreach($data['lists'] as $key => &$value){
          if($value['thumb']){
            $coverArr = get_cover($value['thumb']);
            $value['cover_path_tiny'] = get_image_thumb($coverArr['path'], 100, 100);
            $value['url'] = U('/Item/detail', array('id' => $value['id']));
          }
          if(in_array($value['id'], $current_items_arr)){
            $value['has_select'] = 1;
          }
        }
      }
      if($data['lists']){
        $result['status'] = 1;
        $result['data'] = $data['lists'];
        $result['dataIds'] = get_sub_by_key($data['lists'], 'id');
        $result['page'] = $page;
      }else{
        $result['status'] = 0;
      }
      $this->ajaxReturn($result);
    }
  }

}
