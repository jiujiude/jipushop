<?php
/**
 * 供应商控制器
 * @version 2015082016
 * @author Justin <justin@jipu.com>
 */

namespace Home\Controller;

class SupplierController extends HomeController{
  
  private $data = null;
  
  function _initialize(){
    parent::_initialize();
    $key = I('key');
    !$key && $this->error('非法访问');
    $this->data = M('Supplier')->field('supplier_id')->getByKey($key);
    !$this->data && $this->error('非法访问');
  }
  
  /**
   * 首页
   * @author Justin <justin@jipu.com>
   */
  function index(){
    $type = I('get.type');
    if($type == 'unship'){
      $where['o_status'] = 200;
    }elseif($type == 'unreceive'){
      $where['o_status'] = 201;
    }else{
      $where['o_status'] = array('egt', 200);
    }
    $where['supplier_ids'] = $this->data['supplier_id'];
    $this->lists = D('Order')->listsItem($where);
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->meta_title = '供应商订单列表';
    $this->display();
  }

  /**
   * 详情
   * @author Justin <justin@jipu.com>
   */
  function detail(){
    $map['id'] = I('get.id');
    $map['supplier_ids'] = $this->data['supplier_id'];
    $data = D('Order')->detail($map);
    if(empty($data['items'])){
      $this->error('订单不存在！');
    }
    $this->assign('data', $data);
    $this->meta_title = '供应商订单详情';
    $this->display();
  }
  
  /**
   * 发货
   * @author Justin <justin@jipu.com>
   */
  function update(){
    if(IS_POST){
      $_POST['supplier_id'] = $this->data['supplier_id'];
      $model = D('Admin/Ship');
      if(false !== $model->update()){
        $this->success('操作成功！', Cookie('__forward__'));
      }else{
        $error = $model->getError();
        $this->error(empty($error) ? '未知错误！' : $error);
      }
    }else{
      $this->redirect('index');
    }
  }

}
