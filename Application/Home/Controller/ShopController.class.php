<?php
/**
 * 店铺控制器
 * @version 2015080610
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class ShopController extends HomeController{

  protected function _initialize(){
    //记录当前页URL地址Cookie，点击我的登录完成后跳转至个人中心
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    parent::_initialize();
    //跳过验证登录
    $jump_loginlist = array('detail');
    if(!in_array(ACTION_NAME, $jump_loginlist)){
      parent::login();
    }
    $this->assign('user', $this->user);
    $this->assign('member', $this->member);
  }

  /**
   * 店铺详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($uid = 0){
    $data = M('Shop')->where(array('uid' => $uid, 'status' => 1))->find();
    if($data){
      //记录分销店铺sdp_uid到SESSION
      session('sdp_uid', $data['uid']);
      //商品列表
      $where = array(
        'status' => 1,
        'id' => array('IN', $data['item_ids']),
        'sdp' => array('gt', 0)
      );
      
      $order = "INSTR('{$data['item_ids']}', id)";
      $lists = $this->lists('Item', $where, $order, '*', 40);
      foreach($lists as &$value){
        if($value['thumb']){
          $value['cover_path'] = get_cover($value['thumb'], 'path');
        }
      }
      $this->lists = $lists;
      $this->data = $data;
      $this->meta_title = $data['name'] ? $data['name'] : '无名店铺';
      $share = array(
        'title' => $this->meta_title,
        'desc' => $data['intro'] ? $data['intro'] : C('WEB_SITE_DESCRIPTION'),
        'img_url' => $data['logo'] ? SITE_URL.$data['logo'] : null,
        'link' => SITE_URL.U('Shop/detail', array('uid' => $uid))
      );
      $this->meta_share = $share;
    }else{
      $this->error('不存在的店铺！');
    } 
    $this->display(IS_AJAX ? 'Item/itemList' : null);
  }

  /**
   * 店铺管理
   * @author Max.Yu <max@jipu.com>
   */
  public function manage(){
    if(IS_POST){
      $res = D('Shop')->update();
      if($res){
        $this->success('店铺信息设置成功！',U('Shop/item'));
      }else{
        $this->error(D('Shop')->getError());
      }
    }else{
      $data = M('Shop')->getByUid(UID);
      if($data['status'] != 1){
        $this->redirect('Shop/add');
      }
      $this->data = $data;
    }
    $this->meta_title = '店铺设置';
    $this->display();
  }

  /**
   * 商品管理
   * @author Max.Yu <max@jipu.com>
   */
  public function item(){
    $shop_data = M('Shop')->where(array('uid' => UID, 'status' => 1))->find();
    $where = array(
      'id' => array('IN', $shop_data['item_ids']),
      'status' => 1,
    );
    $lists = $this->lists('Item', $where, 'id desc', 'id, name, price, images, thumb, sdp_type, sdp', 24);
    $lists = A('Item', 'Event')->formatList($lists);
    if($shop_data){
      $shop_data['items'] = explode(',', $shop_data['item_ids']);
    }

    $this->shop_data = $shop_data;
    $this->lists = $lists;
    $this->meta_title = '商品管理';
    $this->display(IS_AJAX ? 'itemList' : null);
  }

  /**
   * 选择商品
   * @author Max.Yu <max@jipu.com>
   */
  public function selectItem(){
    $shop_data = M('Shop')->where(array('uid' => UID, 'status' => 1))->find();
    $where = array(
      'status' => 1,
      'sdp' => array('gt', 0)
    );
    $lists = $this->lists('Item', $where, 'id desc', 'id, name, price, images, thumb, sdp_type, sdp', 24);
    $lists = A('Item', 'Event')->formatList($lists);
    if($shop_data){
      $shop_data['items'] = explode(',', $shop_data['item_ids']);
    }
    $this->shop_data = $shop_data;
    $this->lists = $lists;
    $this->meta_title = '选择商品';
    $this->display(IS_AJAX ? 'itemList' : null);
  }

  /**
   * 添加商品到店铺
   * @author Max.Yu <max@jipu.com>
   */
  public function addItem(){
    $itemid = I('post.itemid', 0);
    if(empty($itemid)){
      $this->error('非法请求！');
    }
    $shop_model = D('Shop');
    $res = $shop_model->addItem($itemid);
    if($res){
      $this->success('成功添加到您的店铺！');
    }else{
      $this->error($shop_model->getError());
    }
  }

  /**
   * 删除店铺商品
   * @author Max.Yu <max@jipu.com>
   */
  public function removeItem(){
    $itemid = I('post.itemid', 0);
    if(empty($itemid)){
      $this->error('非法请求！');
    }
    $shop_model = D('Shop');
    $res = $shop_model->removeItem($itemid);
    if($res){
      $this->success('删除成功！');
    }else{
      $this->error($shop_model->getError());
    }
  }

  /**
   * 订单管理
   * @author Max.Yu <max@jipu.com>
   */
  public function order($uid = 0){
    $prefix= C('DB_PREFIX');
    $model = M()->table($prefix."order_item a")->join($prefix."order b on b.id= a.order_id")->join($prefix."shop c on c.secret=a.sdp_code")->join($prefix."finance d on b.id=d.order_id");
    $where  = array(
      'c.uid'       => UID ,
      'b.o_status'  => 202 ,
    );
    $field = 'b.order_sn,a.item_id,b.create_time,a.price,a.item_code,d.amount,a.thumb,b.id,a.name' ;
    $lists = A('Page', 'Event')->lists($model, $where, $order, 6 ,'', $field);
    if($lists){
      foreach($lists as $k => $v){
            //不存在则为封面图片
        $lists[$k]['cover_path'] =  get_cover($v['thumb'], 'path');
      }
    }
    $this->lists = $lists;
    $this->meta_title = '订单管理';
    $this->display(IS_AJAX ? 'orderList' : null);
  }

  /**
   * 店铺统计
   * @author Max.Yu <max@jipu.com>
   */
  public function stat($type = 'days'){
    if($type == 'days'){
      $stat_data = D('Shop')->getMonthData(); //获取月数据
    }else{
      $stat_data = D('Shop')->getYearData(); //获取年数据
    }
    $this->data = $stat_data;
    $this->type = $type;
    $this->meta_title = '店铺统计';
    $this->display();
  }
  
  /**
   * 开店引导
   * @author Max.Yu <max@jipu.com>
   */
  public function guide(){
    $shop = M('Shop')->getByUid(UID);
    if($shop){
      $this->redirect('Shop/add');
    }
    $this->meta_title = '分销店铺';
    $this->display();
  }
  
  /**
   * 提交开店申请
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    $shop_secret = SHOP_SECRET;
    if(!empty($shop_secret)){
      $this->redirect('Member/sdp');
    }
    if(IS_POST){
      if(D('Shop')->update() !== false){
        $this->success('提交成功，等待审核！');
      }else{
        $this->error(D('Shop')->getError());
      }
    }else{
      
      $shop = M('Shop')->getByUid(UID);
      $this->audit_data = unserialize($shop['audit_data']);
      $this->shop = $shop;
      $this->meta_title = '提交申请信息';
      $this->display();
    }
  }

}
