<?php
/**
 * 后台物流模板控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class DeliveryTplController extends AdminController{
  
  function _initialize(){
    parent::_initialize();
    //检测是否非法操作
    if(IS_SUPPLIER && $id = I('id')){
      UID != M('DeliveryTpl')->getFieldById($id, 'supplier_id') && $this->error('非法操作');
    }
    //供应商
    $this->lists_supplier = D('AuthGroup')->memberInGroup(C('SUPPLIER_GROUP_ID'));
  }
      
  function index(){
    if(IS_SUPPLIER){
      $where['supplier_id'] = UID;
    }else{
      $supplier_id = I('get.supplier_id', 'all');
      if(($supplier_id != 'all') && $supplier_id >= 0){
        $where['supplier_id'] = $supplier_id;
      }
    }
    parent::index($where);
  }
  
  public function ajaxList(){
    $delivery_tpl_model = D('DeliveryTpl');
    $map['status'] = 1;
    $list = $delivery_tpl_model->lists($map, '`id` DESC');
    $this->ajaxReturn($list);
  }

  public function ajaxDetail($id){
    $data = M('DeliveryTpl')->where('id = '.$id)->find();
    $this->ajaxReturn($data);
  }

}