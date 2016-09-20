<?php

/**
 * 秒杀控制器
 * @author ezhu <ezhu@jipukeji.com>
 */

namespace Home\Controller;
use Think\Cache;

class SeckillController extends HomeController{
    
    
    protected function _initialize(){
        //记录当前页URL地址Cookie，点击我的登录完成后跳转至个人中心
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        parent::_initialize();
        //判断是否登录
        parent::login();
    }
    
    
  /**
   * 秒杀商品详情页
   */
  public function detail(){
    //验证参数的合法性
    $id = I('id');
    if(!(is_numeric($id) && $id > 0)){
      $this->error('参数非法！');
    }
    $redis = Cache::getInstance('Redis');
    $data = $redis->get('invoice_'.$id);
    
    /* if(empty($data) || $data['status'] <= 0){
        $this->error('活动已结束！', '/');
    } */
    $path = invoice_html_path();
    if($data['item_stock'] > 0 && $data['etime'] > NOW_TIME){
        $file = $path.'success_'.$data['id'];
    }else{
        $file = $path.'lock_'.$data['id'];
    }
    $file = $path.'success_19.html'; //测试
    if(!is_file($file)) $this->error('活动已结束！','/');
    $this->display($file);
  }
    
    
    
    /**
     * 确认订单
     */
    public function order(){
        //从cookie中获取当前用户秒杀商品统计信息
        $seckillEvent = A('Seckill', 'Event');
        $data = $seckillEvent->getCount();
        
        //商品列表为空，跳回购物车
        if(empty($data)){
            $this->redirect('Cart/index');
        }
        $map['uid'] = UID;

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
        
        $this->receiver = $receiver;
        $this->data = $data;
        $this->buynow = 1;
        $this->meta_title = '确认订单';
        $this->display();
    }
    
    
    /**
     * 秒杀用户下单
     */
    public function add(){
        $result = D('Order')->addSeckill();
        if($result){
            $this->success('订单已生成，请付款', U('Order/preview', array('order_id' => $result)));
        }else{
            $this->error($this->Order->getError() ? $this->Order->getError() : '订单提交失败！', U('/'));
        }
    }
    
    
    /**
     * 生成秒杀html缓存文件
     */
    public function tpl(){
        //验证参数的合法性
        $id = I('id');
        if(!(is_numeric($id) && $id > 0)){
            $this->error('参数非法！');
        }
    
        //实例化数据模型
        $item_model = D('Item');
        //获取商品数据
        $data = $item_model->detail($id);
    
        if(empty($data)){
            $this->error('商品不存在！', U('/'));
        }elseif($data['status'] <= 0){
            $this->error('该商品已下架！', U('/'));
        }
        //如果有规格，库存为规格的
        $data['property']['stock'] && $data['stock'] = $data['property']['stock'];
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
    
        //秒杀
        $data['seckill'] = A('Item', 'Event')->getSeckill($id);
    
        //商品详情懒加载替换
        $data['intro'] = img_lazy_replace($data['intro']);
    
        $share = array(
                'title' => $data['name'].'&yen;'.$data['price'],
                'desc' => $data['summary'],
                'img_url' => SITE_URL.$data['cover_path'],
                'link' => SITE_URL.U('Item/detail', array('id' => $data['id'], 'sdp_secret' => SHOP_SECRET))
        );
        $this->data = $data;
        $this->meta_share = $share;
        $this->meta_title = $data['name'];
        T('Home@Seckill/detail');invoice_html_path();
        $this->buildHtml('success_'.$id,invoice_html_path(),T('Home@Seckill/detail'));
    }
    
    
}
