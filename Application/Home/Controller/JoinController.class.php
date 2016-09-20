<?php

namespace Home\Controller;

class JoinController extends HomeController{
    
    
    public function _initialize(){
        parent::_initialize();
        //跳过验证登录
        $jump_loginlist = array('detail','addCookie','getSpecifiction');
        if(!in_array(ACTION_NAME, $jump_loginlist)){
            parent::login();
        }
    }
    /**
     * 拼团详情页面
     */
    public function detail(){
        $id = I('get.id');
        empty($id) && $this->error('请选择商品！');
        //获取商品数据
        $data = D('Join')->detail($id);
        
        if(empty($data)){
            $this->error(D('Join')->getError(), U('/'));
        }elseif($data['status'] <= 0){
            $this->error('该商品已下架！', U('/'));
        }
        //获取商品评价数据
        $data['comment'] = D('ItemComment')->lists(array('item_id' => $id, 'status' => array('gt', 0)));
        
        //获取商品评价数量
        if($data['comment']){
            $data['comment_total'] = count($data['comment']);
            //获取商品评价回复
            foreach($data['comment'] as $k => $v){
                $data['comment'][$k]['reply'] = D('ItemComment')->detail(array('pid' => $v['id']));
            }
        }else{
            $data['comment_total'] = 0;
        }
        
        
        //获取左侧相关商品列表：当前分类下的按销量排序
        if($data['cid_1']){
            $map_relative = array(
                'cid_1' => $data['cid_1'],
                'id' => array('NEQ', $data['id']),
                'buynum' => array('GT', 0),
            );
            $data['relative'] = D('Item')->lists($map_relative, 'id, name, price, mprice, thumb, buynum', 'buynum DESC', 6);
        }
        
        //更新浏览次数
        M('Item')->where(array('id' => $id))->setInc('viewnum');
        
        //记录当前页URL地址Cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        
        //商品详情懒加载替换
        $data['intro'] = img_lazy_replace($data['intro']);
        
        $share = array(
            'title' => $data['name'].'&yen;'.$data['price'],
            'desc' => $data['summary'],
            'img_url' => SITE_URL.$data['cover_path'],
            'link' => SITE_URL.U('Join/detail', array('id' => $data['id'], 'sdp_secret' => SHOP_SECRET))
        );
        //dump(json_decode($data['property']['spc_data'],true));exit;
        $this->data = $data;
        $this->meta_share = $share;
        $this->meta_title = $data['name'];
        $this->display();
        
        
    }
    
    
    
    /**
     * 拼团商品添加cookie中
     */
    public function addCookie(){
        
        //判断用户是否登录，未登录则放入cookie
        $item_code = I('item_code');
        $code = I('code');
        $item_id = I('item_id');
        $quantity = intval(I('quantity'));
        $price = I('price');
        $active = I('active'); //活动id，参团时需要
        $specifiction = $this->getSpecifiction();
        $JoinModel = D('Join');
        
        //限购数量
        $quota_num = get_quota_num($item_id);
        if($quota_num < $quantity){
            $this->error('超出限购数量');
        }
        
        $item = D('Item')->detail($item_id);
        $buynow_data = array(
                'active'  => $active,
                'item_id' => $item_id,
                'item_code' => $item_code,
                'code' => $code,
                'supplier_id' => $item['supplier_id'],
                'name' => $item['name'],
                'number' => $item['number'],
                'spec' => $specifiction,
                'price' => $price,
                'thumb' => $item['thumb'],
                'quantity' => $quantity,
                'weight' => $item['weight'],
                'cover_path' => get_cover($item['thumb'], 'path'),
                'subAmount' => sprintf("%.2f", $quantity * $price),
                //库存
                'stock' => $JoinModel->getItemStock($item_id, $item_code),
        );
        if(!empty($_POST['jorderid'])){
            $buynow_data['jorderid'] = I('jorderid');
        }
        Cookie('__joinnow__', serialize($buynow_data));
        //立即购买 跳转到下单页面
        Cookie('__forward__', U('Join/order'));
        $result = array(
                'status' => 1,
                'total' => $quantity
        );
        $this->ajaxReturn($result);
        
    }
    
    
    /**
     * 用户下单前，确认订单
     */
    public function order(){
        //购物车cookie加入
        /* $cart = cookie('__cart__');
        if($cart){
            D('Cart')->addCartCookie();
        } */
        $map['uid'] = UID;
        $data = array();
        
        //直接购买
        $buynow_cookie = cookie('__joinnow__');
        if(empty($buynow_cookie)){
            redirect(U('Member/index'));
        }
        $data['items'] = array(unserialize($buynow_cookie));
        if(!empty($data['items'][0]['jorderid'])){
            $this->join_id = $data['items'][0]['jorderid'] ;
        }
        $item_ids_arr = get_sub_by_key($data['items'], 'item_id');
        $item_ids = arr2str($item_ids_arr);
        //商品按供应商分组
        if($data['items']){
            $data['items'] = D('Cart')->formatBySupplier($data['items']);
        }
        
        $order_event = A('Join', 'Event');
        //获取当前用户购物车统计信息
        $order_count = $order_event->doCount();
        
        //商品列表为空，跳回购物车
        if(empty($data['items'])){
            $this->redirect('Cart/index');
        }
        
        //获取当前用户收货地址信息
        $receiver = D('Receiver')->lists($map);
        //获取默认收货地址
        $receiver_id = I('get.receiver_id');
        if($receiver_id){
            $data['default_receiver'] = D('Receiver')->detail(array('id' => $receiver_id));
        }else{
            $dmap = array(
                    'uid' => UID,
                    'is_default' => 1,
            );
            $has_default = D('Receiver')->detail($dmap);
            $data['default_receiver'] = $has_default ? $has_default : $receiver[0];
        }
        
        $this->item_ids = $item_ids;
        $this->receiver = $receiver;
        $this->order_count = $order_count;
        $this->data = $data;
        $this->buynow = 1;
        $this->meta_title = '确认订单';
        $this->display();
    }
    
    
    /**
     * 拼团下单
     */
    public function add(){
        $Model = D('Join');
        $result = $Model->addHandle();
        if($result){
            $this->success('订单已生成，请付款', U('Join/preview', array('order_id' => $result)));
        }else{
            $this->error($Model->getError() ? $Model->getError() : '订单提交失败！', U('/'));
        }
    }
    
    
    /**
     * 订单预览
     * @param $order_sn 订单编号
     * @param $order_id 订单ID
     * @author Max.Yu <max@jipu.com>
     */
    public function preview(){
        $order_id = I('get.order_id');
        $order_sn = I('get.order_sn');
        
        if(empty($order_sn) && empty($order_id)){
            $this->error('订单编号不能为空！');
        }
        if($order_sn){
            $map['join_sn'] = $order_sn;
        }
        if($order_id){
            $map['id'] = $order_id;
        }
        
        $data = D('Join')->orderDetail($map);
        if(empty($data)){
            $this->error('订单不存在！');
        }
        if($data['status'] != 0){
            $this->error('该订单不可付款！');
        }
        
        if(is_weixin()){
            $this->assign('weixin',1);
        }
        $this->data = $data;
        $this->meta_title = '订单已生成';
        $this->display();
    }
    
    
    /**
     * 拼团订单查看页面
     */
    public function join(){
        $id = I('id',0,'int');
        if($id < 1){
            $this->error(' OH!no ~~~未找到你的团购~');
        }
        $where['a.id'] = I('id'  , 0,'int');
        $where['b.etime'] = array('gt' , time());
        $result = M('join_list')->alias('a')->join('LEFT JOIN __JOIN_ITEM__ b on a.item_id=b.item_id' )->join(' LEFT JOIN __JOIN_ORDER__ c on a.reg_uid=c.uid ')->join(' LEFT JOIN __ITEM__ d on d.id=a.item_id')->where($where)->join(' LEFT JOIN __ORDER_SHIP__ e on e.payment_id=c.payment_id')->field('a.*,b.*,c.*,d.thumb,d.price,d.id as gid,a.stime as hdstime,e.ship_area , b.etime as hdetime,a.id as join_id,b.price as hdprice,a.status as hdstatus')->find();
        if(!$result){
            $this->error('活动已结束');
        }
        if(strpos($result['join_uids'], ',' )){
            $temp = explode(',', $result['join_uids']) ; 
            $nums = count($temp);
        }else{
            $nums = 1 ; 
        }
        if(!empty($result['ship_area'])){
           $temp = explode(' ' ,$result['ship_area']);
           $result['ship_area'] = $temp[0].' '.$temp[1];
        }
        $timeout = array(
            'start_time' => $result['hdstime'] ,
            'expire_time' => $result['hdetime']
        );
        // var_dump($result);die;
        $this->goods = R('Join/joinlist' , array($result['item_id']));
        $this->timeout = $timeout;
        $this->nums = $nums;
        $this->assign('data' , $result);
        $this->display();
    }
    
    
    /**
     * 团购活动列表
     */
    function joinlist( $goods_id ){
        $where = array(
            'a.etime'     => array('gt' , time()),
            'a.status'    => 1,
            'a.item_id'   => array('neq' , $goods_id),
        );
        return M('Join_item')->alias('a')->join(' __ITEM__ b on a.item_id=b.id' )->where($where)->field('a.*,b.thumb,b.name as goods_name')->select();
    }
    /**
     * 获取前端用户选择的规格数组数据
     * @author Max.Yu <max@jipu.com>
     */
    public function getSpecifiction(){
        $specifiction_key = I('key');
        $specifiction_val = I('val');
        $specifiction_code = I('code');
        $specifiction = array();

        foreach($specifiction_code as $code){
            $specifiction[$code]['key'] = $specifiction_key[$code];
            $specifiction[$code]['val'] = $specifiction_val[$code];
        }
        return $specifiction;
    }
    
    
    /**
     * 拼团列表
     */
    public function joinOrder(){
        $type = I('get.type','all');
        switch($type){
            case 'all' :
                $map['o.uid'] = UID;
                break;
            case 'reg' :
                $map['j.reg_uid'] = UID;
                break;
            case 'join' :
                $map[] = 'find_in_set('.UID.', j.join_uids)';
                $map['j.reg_uid'] = array('neq',UID);
                break;
            case 'fail' :
                $map['o.uid'] = UID;
                $map['j.status']  = 0;
                break;
        }
        $map['i.status'] = 1;
        $fields = 'j.* ,j.status as jstatus, o.join_sn,o.paytime,o.total_amount,o.status,i.name,i.summary,i.thumb,s.ship_area,s.ship_address,o.ctime as otime ,o.item_id,o.id,j.id as jid ,o.ltime';
        $limit = 10;
        $order = 'o.id desc';
        $prefix  = C('DB_PREFIX');
        $Jtable  = $prefix.'join_list';
        $Otable  = $prefix.'join_order';
        $Itable  = $prefix.'item';
        $Stable  = $prefix.'order_ship';
        $model    = M()->table( $Otable.' o' )->join ( 'LEFT JOIN '.$Jtable.' j ON j.id=o.join_id' )
                       ->join('LEFT JOIN '.$Itable.' i ON i.id = o.item_id')
                       ->join('LEFT JOIN '.$Stable.' s ON s.payment_id = o.payment_id');
        $map['o.status'] = array('gt' ,'-1');
        $lists = A('Page', 'Event')->lists($model, $map, $order, $limit ,NULL,$fields);
        foreach($lists as $k => $v){
            $lists[$k]['out'] = ($v['ltime'] < time() && $v['paytime'] == 0 && $v['ltime'] > 0) ? 1 : '';
            $lists[$k]['web'] = ( $v['jstatus'] == 0 && $v['status'] == 1 ) ? 'http://'.$_SERVER['HTTP_HOST'].'/join/join/id/'.$v['jid'].'.html' : '';
        }
        $this->web = 'http://'.$_SERVER['HTTP_HOST'] ;
        $tab[$type] = 'class="active"';
        $this->assign('tab',$tab);
        $this->assign('list',$lists);
        $this->display();
        
    } 
    
      /**
   * 设置团购订单状态
   * @author Justin <justin@jipu.com>
   */
  public function setStatus($joinorder_id = null, $type = 'cancel'){
    if(IS_POST){
      if(empty($joinorder_id)){
        $this->error('订单ID不能为空！');
      }
      $text = array(
        'cancel' => '取消',
        'recycle' => '删除',
        'delete' => '删除',
        'restore' => '还原'
      );
      switch($type){
        // case 'cancel' :
        //   $res = M('Order')->where(array('id' => $joinorder_id, 'uid' => UID))->setField('o_status', -1);
        //   break;
        case 'recycle' :
          $new_status = -1;
          break;
        // case 'delete' :
        //   $new_status = 3;
        //   break;
        // case 'restore' :
        //   $new_status = 1;
        //   break;
      }
      $new_status && $res = M('join_order')->where(array('id' => $joinorder_id, 'uid' => UID))->setField('status', $new_status);
      if($res){
        $this->success('订单'.$text[$type].'成功！');
      }else{
        $this->error('订单'.($text[$type] ? $text[$type] : '处理').'失败！');
      }
    }else{
      $this->error('非法请求');
    }
  }
    /**
     * 拼团订单详情
     */
    public function orderDetail(){
        $order_sn = I('order_sn','');
        $order_sn || $this->error('拼团订单号不能为空');
        
        $map['o.join_sn'] = array('eq',$order_sn);
        $map['o.uid'] = array('eq',UID);
        
        $fields = 'j.status as j_status,o.item_id,o.item_name,o.item_code,o.thumb ,o.total_price,o.total_quantity,o.delivery_fee, o.ctime,o.payment_id , o.join_sn,o.paytime,o.memo,o.total_amount,o.status,s.ship_uname,s.ship_mobile,s.ship_area,s.ship_address';
        
        $prefix  = C('DB_PREFIX');
        $Jtable  = $prefix.'join_list';
        $Otable  = $prefix.'join_order';
        $Stable  = $prefix.'order_ship';
        $model    = M()->table( $Jtable.' j' )->join ( ' INNER JOIN '.$Otable.' o ON j.id=o.join_id' )
        ->join('LEFT JOIN '.$Stable.' s ON s.payment_id = o.payment_id');
        
        $data = $model->where($map)->field($fields)->find();
        $data['price'] = D('Join')->getItemPrice($data['item_id'],$data['item_code']);
        $data['memo'] = unserialize($data['memo']);
//         sql();
//         dump($data);
        $this->assign('data',$data);
        $this->display();
    }
    
    
    /**
     * PC端微信支付
     * @author ezhu <ezhu@jipukeji.com>
     */
    public function weixinPayCode($order_id = 0, $get_status = 0){
        $data = M('JoinOrder')->where(array('id' => $order_id, 'uid' => UID))->find();
        if($data['status'] == 1){
            $this->success('微信支付成功！', U('Join/orderDetail', array('order_sn' => $data['join_sn'])));
        }elseif(IS_AJAX && $get_status == 1){
            $this->ajaxReturn(array('status' => 0));
            //未支付
        }else{
            //订单号为空或无登录下单非法数据请求
            if(UID==0 && !in_array($order_id, session('new_order'))){
                $this->error('非法请求', U('Join/joinOrder'));
            }else{
                $page_url = U('Api/qrcode');
                $pay_url = U('Pay/joinPay/payment_type/wechatpay/', array('order_id' => $order_id));
                $data['qrcode_src'] = $page_url . (strpos($page_url, '?') > 0 ? '&' : '?') . 'sec_code=H&data='.urlencode(SITE_URL.$pay_url);
                $this->data = $data;
                $this->display();
            }
        }
    }
}
