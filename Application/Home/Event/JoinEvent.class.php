<?php

/**
 * 拼团
 * @author ezhu <ezhu@jipukeji.com>
 */
namespace Home\Event;

class JoinEvent {
    
    private $error = '';
    
    
    /**
     * 获取错误提示
     * @return string
     */
    public function getError(){
        return $this->error;
    }
    
    
    /**
     * 查看是否是在拼团活动当中，不包含判断库存
     * @param number $itemId
     * @return boolean
     */
    public function isJoin($itemId=0){
        if(empty($itemId)) return false;
        $map[] = 'FIND_IN_SET('.$itemId.', item_ids)';
        $map['stime'] = array('elt',NOW_TIME);
        $map['etime'] = array('gt',NOW_TIME);
        $map['status'] = array('eq',1);
        $rst = M('join')->where($map)->find();
        return $rst;
    }
    
    
    /**
     * 获取参加拼团活动的商品的id
     * @return multitype:
     */
    public function getItemIds(){
        $map['stime'] = array('elt',NOW_TIME);
        $map['etime'] = array('gt',NOW_TIME);
        $map['status'] = array('eq',1);
        $list = M('join')->field('item_ids')->where($map)->select();
        $ids = array();
        if(!empty($list)){
            foreach ($list as $key=>$val){
                $ids = getUniqure(array_merge($ids , explode(',',$val['item_ids'])));
            }
        }
        return $ids;
    }
    
    
    /**
     * 统计订单商品价格信息
     * @param int    $item_ids
     */
    function doCount(){
        $result = array(
                'total_num' => 0,
                'total_quantity' => 0,
                'total_price' => 0.00,
                'supplier' => array()
        );
        
        //直从cookie中获取接购买的商品信息
        $cart_items = array(unserialize(cookie('__joinnow__')));
        
        //计算重量，计算运费
        foreach($cart_items as $item){
            $supp_id = $item['supplier_id'];
            if(!isset($result['supplier'][$supp_id])){
                $result['supplier'][$supp_id] = array(
                        'total_num' => 0,
                        'total_quantity' => 0,
                        'total_weight' => 0,
                        'total_price' => 0,
                        'delivery' => array()
                );
            }
            //商品种类数
            $result['supplier'][$supp_id]['total_num'] ++;
            //数量
            $result['supplier'][$supp_id]['total_quantity'] += $item['quantity'];
            //重量
            $result['supplier'][$supp_id]['total_weight'] += $item['weight'] * $item['quantity'];
            //商品价格
            $price = D('Join')->getItemPrice($item['item_id'], $item['item_code'],$item['active']);
            //运费
            $result['supplier'][$supp_id]['total_price'] += $price * $item['quantity'];
        }
        //订单商品种类数
        $result['total_num'] = count($cart_items);
        //订单商品数量
        $result['total_quantity'] = array_sum(array_column($result['supplier'], 'total_quantity'));
        //订单总价格
        $result['total_price'] = sprintf('%.2f', array_sum(array_column($result['supplier'], 'total_price')));
        //订单总重量
        $result['total_weight'] = sprintf('%.2f', array_sum(array_column($result['supplier'], 'total_weight')));
        //按供应商计算数据
        foreach($result['supplier'] as $supp_id => &$supp){
            //供应商总价格格式化
            $supp['total_price'] = sprintf('%.2f', $supp['total_price']);
            //最低免运费额度
            $free_amount = get_supplier_free_amount($supp_id);
            if($free_amount > 0 && $supp['total_price'] < $free_amount){
                $tpl_list = M('DeliveryTpl')->where(array('status' => 1, 'supplier_id' => $supp_id))->order('sort asc')->select();
                foreach($tpl_list as $line){
                    $arr = array(
                            'id' => $line['id'],
                            'name' => $line['name'],
                            'company' => $line['company'],
                            'price_type' => $line['price_type'],
                            'send_date' => $line['send_date'],
                            'price' => $line['express_postage'],
                    );
                    if($line['price_type'] == 1){ //按件数计费
                        if($supp['total_quantity'] <= $line['express_start']){
                            $arr['price'] = $line['express_postage'];
                        }else{
                            $plus_w = $supp['total_quantity'] - $line['express_start'];
                            $arr['price'] = number_format($plus_w * $line['express_postageplus'] + $line['express_postage'], 2);
                        }
                    }else if($line['price_type'] == 2){ //按重量计费
                        if($supp['total_weight'] <= $line['express_start']){
                            $arr['price'] = $line['express_postage'];
                        }else{
                            $plus_w = $supp['total_weight'] - $line['express_start'];
                            $arr['price'] = ceil($plus_w) * $line['express_postageplus'] + $line['express_postage'];
                        }
                    }
                    $arr['price'] = sprintf('%.2f', $arr['price']);
                    $supp['delivery'][$arr['id']] = $arr;
                }
            }
        }
        return $result;
    }
    
    
    /**
     * 计算总价
     * 暂时未使用
     */
    public function getOrderTotalAmount($data){
        //计算订单金额
        $total_amount = sprintf('%.2f', $data['total_price'] + $data['delivery_fee']);
        $data['total_amount'] = $total_amount ? : 0.00;
        return $data;
    }
    
    
    /**
     * 检测拼团订单
     * @param string $order_id   订单id
     * @param string $order_sn   订单编号
     * @return \Think\mixed
     */
    public function checkOrder($order_id='' , $order_sn=''){
        if(empty($order_sn) && empty($order_id)){
            $this->error = '订单ID不能为空！';
            return false;
        }elseif(!empty($order_id) && is_nan($order_id)){
            $this->error = '非法订单ID！';
            return false;
        }
        //获取订单数据
        $where = array();
        $order_id && $where['id'] = $order_id;
        $order_sn && $where['join_sn'] = $order_sn;
        $order = M('join_order')->where($where)->find();
        
        if($order){
            $order_id = $order['id'];
            if(empty($order['join_sn'])){
                $this->error = '订单编号不能为空！';
                return false;
            }
            if($order['status'] != 0){
                $this->error = '该订单不可付款！';
                return false;
            }
            //检测订单中商品的当前库存是否大于等于客户订单中购买的数量
            if(!$this->checkItemStock($order['item_id'],$order['item_code'],$order['total_quantity'])){
                $this->error = '您购买的商品库存不足！';
                return false;
            }
            //检测订单中商品的限购情况
            if(!$this->checkItemQuota($order['item_id'],$order['total_quantity'])){
                $this->error = '商品超过了限购数量！';
                return false;
            }
            return $order;
        }else{
            $this->error = '订单不存在！';
            return false;
        }
    }
    
    
    
    /**
     * 检测订单中商品的当前库存是否大于等于客户订单中购买的数量
     * @param int      $item_id    商品id
     * @param string   $item_code  商品编码（规格编码）
     * @param int      $num        订单商品数量
     * @return boolean
     */
    public function checkItemStock($item_id,$item_code,$num){
        //遍历订单商品，逐个检测库存
        if($item_id && $num){
            $item_model = D('JoinItem');
            $item_spc_model = D('JoinItemSpec');
            //判断是否配置了规格分库存
            $quantity = $item_spc_model->where(array('item_id' => $item_id))->getFieldBySpcCode($item_code, 'quantity');
            if($quantity){
                if($quantity < $num){
                    return false;
                }
            }else{
                $quantity = $item_model->getFieldByItemId($item_id, 'stock');
                if($quantity < $num){
                    return false;
                }
            }
            return true;
        }
    }
    
    
    /**
     * 检测订单限购
     * @param int $item_id  商品id
     * @param int $num      订单商品数
     * @return boolean
     */
    public function checkItemQuota($item_id,$num){
        $quota_num = get_quota_num($item_id);
        if($quota_num < $num){
            return false;
        }
        return true;
    }
    
    
    /**
     * 支付完成回调各种逻辑处理
     * @param unknown $order
     */
    public function payCallBack($order){
        //更新订单商品库存和销量
        $rst = $this->updteStockAndBuy($order['id']);
        
        
        //判断拼团状态
        if($rst && $this->satisfy($order['join_id'], $order['item_id'])){
            //TODO:  下单操作
            //生成订单   
        }
        $this->checkjoin($order['join_id']);
        //订单支付后通知
        //$this->notice($order);
    }
    /**
     * 检查是否满足团购成功
     */
    public function checkjoin($join_id){

        $join = M('Join_list')->alias('a')->join(' LEFT JOIN __JOIN_ITEM__ b on a.item_id=b.item_id')->where('a.id ='.$join_id.' and a.status =0')->field('a.*,b.join_num')->find();
        if(!empty($join)){
            if(strpos($join['join_uids'] ,',')){
                $joins = explode(',' , $join['join_uids']);
                $count = count($joins);
            }else{
                $count = 1 ;
            }
            if( $count >= $join['join_num']){
                if($this->createorder($join_id)){    
                    $saveData['status'] = 1;
                    return M('join_list')->where(array('id'=>$join_id))->save($saveData);
                }
            }
        }
        return false;
    }
    /**
     * 生成订单
     * @return [type] [description]
     */
    public function createorder($join_id){
        $info = M('join_order')->alias('a')->join('__PAYMENT__ b on a.payment_id=b.id')->field('a.* , b.payment_type , b.create_time as paytime')->where('a.join_id = '.$join_id)->select();
        if(!empty($info[0]['item_id'])){
            $goods = M('Item')->where('id ='.$info[0]['item_id'])->find();
            $model = M('Order');
            foreach($info as $k => $v){
                $model->startTrans();   
                $data = array(
                    'supplier_ids'  => $goods['supplier_id'] ,
                    'uid'           => $v['uid'] ,
                    'sdp_uid'       => 0,
                    'item_ids'      => $v['item_id'] ,
                    'delivery_id'   => $v['delivery_id'] ,
                    'order_sn'      => $v['join_sn'] ,
                    'o_status'      => 200 ,
                    'payment_id'    => $v['payment_id'] ,
                    'payment_type'  => $v['payment_type'] ,
                    'invoice_need'  => 0,
                    'finance_amount'=> $v['finance_amount'] ,
                    'total_amount'  => $v['total_amount'],
                    'total_price'   => $v['total_price'],
                    'total_quantity'=> $v['total_quantity'],
                    // 'total_weight'  => $v['total_weight'],
                    'delivery_fee'  => $v['delivery_fee'],
                    'memo'          => $v['memo'],
                    'order_from'    => 1,
                    'create_time'   => time(),
                    'update_time'   => time(),
                    'payment_time'  => $v['paytime'],
                    'status'        => 1,
                    'order_type'    => 2 ,
                );
                if($result = $model->add($data)){
                    $order['supplier_id'] = $v['supplier_id'];
                    $order['order_id']    = $result;
                    $order['item_id']     = $v['item_id'];
                    $order['name']   = $goods['name'];
                    $order['number']      = $goods['number'];
                    $order['thumb']       = $goods['thumb'];
                    $order['spec']        = $v['spec'];
                    $order['price']       = $v['total_amount'];
                    $order['quantity']    = $v['total_quantity'];
                    // $order['weight']      = $v['total_amount'];
                    $last =M('OrderItem')->add($order);
                    if($last ){
                        M('Join_order')->where('id = '.$v['id'])->save(array('order_id' => $last));
                        $model->commit();
                    }else{
                        $model->rollback();
                    }
                }
            }
            M('Join_list')->where('id ='.$join_id)->setField('status' , 1 );
        }
        return true;
    }
    public function updteStockAndBuy($order_id){
        if(empty($order_id)){
            return false;
        }
        //获取订单项目数据
        $where['id'] = $order_id;
        $field = 'item_id, item_code, total_quantity';
        $orderData = M('join_order')->where($where)->field($field)->find();
        //检测订单中商品的当前库存是否大于等于客户订单中购买的数量
        if(!$this->checkItemStock($orderData['item_id'],$orderData['item_code'],$orderData['total_quantity'])){
            return false;
        }
        
        if($orderData){
            $item_model = M('Item');
            $join_model = M('JoinItem');
            
            //更新活动库存
            M('join_item_spec')->where(array('spc_code' => $orderData['item_code']))->setDec('quantity',$orderData['total_quantity']);
            //更新活动总库存
            $join_model->where(array('item_id' => $orderData['item_id']))->setDec('stock',$orderData['total_quantity']);
            
            //更新规格分库存
            M('ItemSpecifiction')->where(array('spc_code' => $orderData['item_code']))->setDec('quantity', $orderData['total_quantity']);
            //更新总库存
            $item_model->where(array('id' => $orderData['item_id']))->setDec('stock', $orderData['total_quantity']);
            
            //更新购买数量
            $item_model->where(array('id' => $orderData['item_id']))->setInc('buynum', $orderData['total_quantity']);
            return true;
        }
    }
    
    
    /**
     * 查看拼团是否满足下单条件
     * @param int $join_id  前台拼团活动id
     * @param int $item_id  商品id
     * @return boolean
     */
    public function satisfy($join_id,$item_id){
        $userCount = D('Join')->JoinCount($join_id);
        $num = M('join_list')->where(array('item_id'=>$item_id))->find();
        if($userCount == $num['num']){
            return true;
        }else{
            return false;
        }
    }
    
    
}