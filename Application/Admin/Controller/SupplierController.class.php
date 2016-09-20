<?php
/**
 * 供应商控制器
 * @version 2015082011
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Controller;

class SupplierController extends AdminController{
  
  function _before_index(){
    if(IS_SUPPLIER){
      $this->redirect('edit', array('id' => UID));
    }
  }
  
  function _before_add(){
    //供应商
    $lists_supplier = D('AuthGroup')->memberInGroup(C('SUPPLIER_GROUP_ID'));
    foreach($lists_supplier as $k => $v){
      if(M('Supplier')->getFieldBySupplierId($v['uid'], 'id')){
        unset($lists_supplier[$k]);
      }
    }
    if($lists_supplier){
      $this->lists_supplier = $lists_supplier;
    }else{
      $this->error('请在供应商组中添加供应商用户！');
    }
  }
  
  function _before_edit(){
    if(IS_SUPPLIER){
      //获取供应商信息id
      $_REQUEST['id'] = M('Supplier')->getFieldBySupplierId(UID, 'id');
      Cookie('__forward__', $_SERVER['REQUEST_URI']);
    }
  }
  
  /**
   * 预览二维码页面
   */
  public function detail(){
    $key = I('get.key', 0);
    $this->key = $key;
    if($key){
      $data_url = SITE_URL.U('/Supplier/index', array('key' => $key));
      if(I('show') == 'qrcode'){
        vendor('Qrcode.Phpqrcode');
        $QRcode = new \Vendor\QRcode();
        $QRcode->png($data_url, false, 'D', 5, 1);
      }else{
        $this->data_url = $data_url;
        $this->display();
      }
    }else{
      $this->error('参数错误');
    }
  }
  
}

