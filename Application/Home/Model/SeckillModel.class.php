<?php

namespace Home\Model;
use Think\Model;
use Admin\Model\SeckillModel;

class SeckillModel extends Model{
    
    
    /**
     * 秒杀商品下单
     * @param array $data 商品信息
     */
    public function update($data){
        //检测商品信息
        if(!$this->_checkOrderData()){
            return false;
        }
        //检测用户数据
        $data = $this->create($data);
        if(!$data){
            return false;
        }
        //支付id
        $payment_id = D('Payment')->update($data);
        $data['payment_id'] = $payment_id;
        
        //根据购物车商品规格，重新获取商品价格
        $seckillEvent = A('Seckill', 'Event');
        $countData = $seckillEvent->getCount();
        
        //运费
        $countData['supplier'] = (array)$countData['supplier'];
        $delivery_data = A('Order', 'Event')->getDeliveryAmount($countData['supplier']);
        foreach($delivery_data as $k => $v){
            $data['delivery_fee'] += $v['amount'];
        }
        $data['delivery_data'] = $delivery_data;
        $data['total_price'] = $countData['total_price'];
        $data['total_quantity'] = $countData['total_quantity'];
        $data['total_weight'] = $countData['total_weight'];
        
        
        if($data['total_price'] == 0){
            M('Payment')->delete($payment_id);
            return false;
        }
        $result = $order_id = M('Order')->add($data);
        //更新order_item表
        if($result){
            //立即购买从cookie中取商品数据
            $buynow_item = unserialize(cookie('__buynow__'));
            if($buynow_item){
                unset($buynow_item['delivery']);
                unset($buynow_item['cover_path']);
                unset($buynow_item['subAmount']);
            }
            $items = array($buynow_item);
        
            if($items){
                $supp_ids = array();
                //买送-获取赠品
                $_arr = array();
                //分销返现钱
                $sdp_cashback = 0;
                foreach($items as $key => &$value){
                    $sdp_cashback += D('SdpRecord')->getCashBackAmount($value['item_id'], $value['price'], $value['quantity']);
                    $item_data = M('Item')->field('supplier_id')->find($value['item_id']);
                    $value['order_id'] = $order_id;
                    $value['spec'] = serialize($value['spec']);
                    $value['price'] = $this->getItemPrice($value['item_id'], $value['item_code']);
                    $value['supplier_id'] = $item_data['supplier_id'];
        
                    $supp_ids[] = $value['supplier_id'];
                }
        
        
                foreach($items as $v){
                    M('OrderItem')->add($v);
                }
        
                //M('OrderItem')->addAll($items);
                 
                //更新订单表的item_ids和总数量
                $new_data = array(
                        'total_quantity' => $data['total_quantity'],
                        'item_ids' => arr2str(array_unique(explode(',', $data['item_ids']))),
                        'supplier_ids' => arr2str(array_unique($supp_ids))
                );
                M('Order')->where('id='.$order_id)->save($new_data);
        
                //删除cookie商品数据
                cookie('__buynow__', null);
                //更新统计
                D('Order')->updateCount();
            }
        }
        return $order_id;
    }
    
    
    
    /**
     * 检测订单数据是否合法
     * @author Justin <justin@jipu.com>
     */
    private function _checkOrderData(){
        $item_ids = I('post.item_ids');
        !cookie('__buynow__') && $this->error = '已超时请重新下单！';
        //TODO:从redis中获取秒杀商品信息
        
        //是否秒杀已过期
        
        
        //检测库存
        if(!$this->checkItemStock($item_ids, $buynow == 1)){
            $this->error = '库存不足导致下单失败！';
            return false;
        }
        //检测限购
        if(!$this->checkItemQuota($item_ids, $buynow == 1)){
            $this->error = '部分商品超过限购数量导致下单失败！';
            return false;
        }
        return true;
    }
    
    
    
}