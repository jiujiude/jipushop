<?php
/**
 * 后台商品控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Admin\Model\AuthGroupModel;
use Admin\Model\ItemPropertyModel;
use Think\Page;

class ItemController extends AdminController {

  /**
   * 商品列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index($status = null, $keywords = null, $cid_1 = null, $cid_2 = null, $cid_3 = null, $stock = null) {
    $this->cid_1 = D('itemCategory')->getTree(0,'id,pid,name');
    
    // 搜索条件
    if($cid_1){
       $where['cid_1'] = $cid_1;
       $this->cid_2 = D('itemCategory')->getTree($cid_1,'id,pid,name');
    }
    if($cid_2){
       $where['cid_2'] = $cid_2;
       $this->cid_3 = D('itemCategory')->getTree($cid_2,'id,pid,name');
    }
    if($cid_3){
       $where['cid_3'] = $cid_3;
    }
    if(in_array($status,array('0','1'))){
      $where['status'] = $status;
    }else{
      $where['status'] = array('in', '0,1');
    }
    if(!empty($keywords)){
      $where['_string'] = '(name like "%'.$keywords.'%")  OR (number like "%'.$keywords.'")';
    }
    if(isset($stock)){
       $where['stock'] = $stock;
    }
    
    $supplier_id = I('get.supplier_id', 'all');
    if(IS_SUPPLIER)$supplier_id=UID;

    if(($supplier_id != 'all') && $supplier_id >= 0){
      $where['supplier_id'] = $supplier_id;
    }
    
    
    //$order = '`is_top` DESC, `sort` ASC';
    //获取秒杀产品
    $this->is_seckill = I('get.is_seckill');
    
    $seckill_lists = $this->lists('Seckill', array('status' => 1));
    if($seckill_lists){
      $seckill_item_ids = array_column($seckill_lists, 'item_ids');
      $seckill_item_ids = array_unique(str2arr(arr2str($seckill_item_ids)));
    }else{
      unset($_GET['is_seckill']);
    }
    
    if((1 == I('get.is_seckill')) && $seckill_item_ids){
      $where['id'] = array('in', $seckill_item_ids);
    }
    // 获取商品列表
    $list = $this->lists(M('Item'), $where);
    
    if($seckill_item_ids){
      foreach($list as &$v){
        if(in_array($v['id'], $seckill_item_ids)){
          $v['is_seckill'] = 1;
        }
      }
    }
    
    int_to_string($list, $map = array('status' => array(0 => '上架', -1 => '删除',1 => '下架', 2 => '草稿')));
    
    //供应商
    $this->lists_supplier = D('AuthGroup')->memberInGroup(C('SUPPLIER_GROUP_ID'));
    
    //记录当前列表页的Cookie
    Cookie('__forward__',$_SERVER['REQUEST_URI']);

    // 增加排序
    $this->setListOrder();
    
    //模板输出变量赋值
    $this->assign('list', $list);
    $this->assign('status', $status);
    $this->meta_title = '商品列表';
    $this->display();
  }

  /**
   * 选择商品分类
   * @author Max.Yu <max@jipu.com>
   */
  public function select() {
    $this->display();
  }
  
  function _before_add(){
    //供应商
    $this->lists_supplier = D('AuthGroup')->memberInGroup(C('SUPPLIER_GROUP_ID'));
  }
  
  function _before_edit(){
    self::_before_add();
  }
  
  /**
   * 商品编辑页面初始化
   * @author Max.Yu <max@jipu.com>
   */
  public function edit(){
    $id = I('get.id', '');
    if(empty($id)){
      $this->error('参数不能为空！');
    }

    /*获取一条记录的详细数据*/
    $Item = D('Item');
    $data = $Item->detail($id);
    if(!$data){
      $this->error($Item->getError());
    }
    $this->assign('s', UID);
    $this->assign('data', $data);
    $this->meta_title = '编辑商品';
    $this->display();
  }

  /**
   * 数据更新（新增&修改）
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    $res = D('Item')->update();
    if(!$res){
      $this->error(D('Item')->getError());
    }else{
      $this->success($res['id'] ? '更新成功' : '新增成功', Cookie('__forward__'));
    }
  }

  /**
   * 设置一条或者多条商品的状态
   */
  public function setStatus(){
    $ids = I('request.ids');
    if(empty($ids)){
      $this->error('请选择要操作的商品');
    }

    $Model = 'Item';
    $status = I('request.status');
    $map['id'] = array('in',$ids);
    switch ($status){
      case -1 :
        $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));
        break;
      case 0  :
        $this->forbid($Model, $map, array('success'=>'下架成功','error'=>'下架失败'));
        break;
      case 1  :
        $this->resume($Model, $map, array('success'=>'上架成功','error'=>'上架失败'));
        break;
      default :
        $this->error('参数错误');
        break;
    }
  }

  /**
   * 查看商品
   * @author Max.Yu <max@jipu.com>
   */
  public function view(){
    $id = I('get.id', '');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    /* 获取数据 */
    $data = D('Item')->detail($id);
    $this->assign('data', $data);
    $this->meta_title = '查看商品信息';
    $this->display();
  }

  /**
   * 回收站
   * @author Max.Yu <max@jipu.com>
   */
  public function recycle() {
    $where['status'] = -1;
    $list = $this->lists(M('Item'), $where, 'id desc', null, $field);
    //记录当前列表页的Cookie
    Cookie('__forward__',$_SERVER['REQUEST_URI']);
    $this->assign('list', $list);
    $this->meta_title = '商品回收站';
    $this->display();
  }

  /**
   * 从回收站还原被删除的数据
   * @author Max.Yu <max@jipu.com>
   */
  public function permit(){
    /*参数过滤*/
    $ids = I('param.ids');
    if(empty($ids)){
      $this->error('请选择要操作的商品');
    }

    /*拼接参数并修改状态*/
    $Model = 'Item';
    $map = array();
    if(is_array($ids)){
      $map['id'] = array('in', $ids);
    }elseif(is_numeric($ids)){
      $map['id'] = $ids;
    }
    $this->restore($Model, $map);
  }

  /**
   * 清空回收站
   * @author Max.Yu <max@jipu.com>
   */
  public function clear(){
    /*参数过滤*/
    $ids = I('param.ids');
    if(empty($ids)){
      $this->error('请选择要操作的商品');
    }
    $res = D('Item')->remove($ids);
    if($res !== false){
      //记录行为
      action_log('clear_item_recycle', 'item', $ids, UID);
      $this->success('清空回收站成功！');
    }else{
      $this->error('清空回收站失败！');
    }
  }

  /**
   * 删除商品图片
   * @author Max.Yu <max@jipu.com>
   */
  public function delPic($pid = null, $item_id = null, $thumb = null, $images = null){

    $result = array('status'=>1, 'info'=>'图片删除成功！');

    // 验证参数的合法性
    if(empty($pid)){
      $result = array('status'=>0, 'info'=>'参数不能为空！');
      $this->ajaxReturn($result, 'JSON');
    }else{
      if(!is_numeric($pid)){
        $result = array('status'=>0, 'info'=>'参数错误！');
        $this->ajaxReturn($result, 'JSON');
      }
    }

    // 实例化图片模型
    $picture_model = D('Picture');

    // 获取商品图片缩略图规格配置
    $thumb_size = C('UPLOAD_PIC_THUMB_SIZE.ITEM_PIC');

    if($picture_model->delById($pid, $thumb_size)){
      if($item_id && is_numeric($item_id)){
        // 更新商品图片数据
        $data['id'] = $item_id;
        $data['thumb'] = $thumb;
        $data['images'] = $images;
        if(M('Item')->save($data)){
          $this->ajaxReturn($result, 'JSON');
        }else{
          $result = array('status'=>0, 'info'=>'图片删除失败！');
          $this->ajaxReturn($result, 'JSON');
        }
      }
    }else{
      $result = array('status'=>0, 'info'=>'图片删除失败！');
      $this->ajaxReturn($result, 'JSON');
    }

    $this->ajaxReturn($result, 'JSON');
  }

  /**
   * 批量生成二维码
   * @author Max.Yu <max@jipu.com>
   */
  public function createQrcode(){
    $where['status'] = 1;
    $items = M('Item')->where($where)->getField('id', true);

    if($items && is_array($items)){
      foreach($items as $item){
        get_qrcode($item, 0, 2.6, 1);
      }
    }
    //记录行为
    action_log('create_item_qrcode', 'item', 'all', UID);
    $this->meta_title = '二维码批量生成';
    $this->display('qrcode');
  }
  
  
  /**
   * 移动商品类别
   * @author Justin 2015.5.12 <9801836@qq.com>
   */
  function itemMove($ids = null,$cid_1 = null,$cid_2 = null,$cid_3 = null){
    if(IS_POST){
      if($ids && $cid_1){
        $where['id'] = array('in',$ids);
        unset($_POST['ids']);
        $m = M('item');
        $m->create();
        $m->where($where)->save() ? $this->success("移动成功!",U('index')) : $this->error("移动失败!",U('index'));
      }else{
        $this->error("请选择商品以及新分类!",U('index'));
      }
    }else{
      $this->display();
    }
  }
  
  /**
  * 更新搜索关键字索引
  * @version 2015071115
  * @author Justin
  */
  function updateSearchIndex(){
    $model = D('Item');
    $model->updateSearchIndex() && $this->success("更新搜索关键字索引成功!", U('index'));
  }
  
}
