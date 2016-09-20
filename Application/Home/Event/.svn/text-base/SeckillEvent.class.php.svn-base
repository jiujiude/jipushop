<?php
namespace Home\Event;

class SeckillEvent{
    
    
    /**
     * 统计秒杀商品费用
     * @param int $itemId
     */
    public function getCount(){
        //获取购物车或者直从cookie中获取接购买的商品信息
        $item = array(unserialize(cookie('__buynow__')));
        $data = $item[0];
        $supp_id = $data['supplier_id'] ? : 0;
        
        //商品种类数
        $result['total_num'] =1;
        //数量
        $result['total_quantity'] = $data['quantity'];
        //重量
        $result['total_weight'] = $data['weight'] * $data['quantity'];
        //根据商品id和规格获取商品价格
        $price = D('Order')->getItemPrice($data['item_id'], $data['item_code'],$isKill=true);
        $result['total_price'] = $price * $data['quantity'];
        //小计
        $result['subAmount'] = $result['total_price'];
        //供应商
        $result['supplier_name'] = get_supplier_text($supp_id);

        //最低免运费额度
        $free_amount = get_supplier_free_amount($supp_id);
        if($free_amount > 0 && $result['total_price'] < $free_amount){
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
                    if($result['total_quantity'] <= $line['express_start']){
                        $arr['price'] = $line['express_postage'];
                    }else{
                        $plus_w = $result['total_quantity'] - $line['express_start'];
                        $arr['price'] = number_format($plus_w * $line['express_postageplus'] + $line['express_postage'], 2);
                    }
                }else if($line['price_type'] == 2){ //按重量计费
                    if($result['total_weight'] <= $line['express_start']){
                        $arr['price'] = $line['express_postage'];
                    }else{
                        $plus_w = $result['total_weight'] - $line['express_start'];
                        $arr['price'] = ceil($plus_w) * $line['express_postageplus'] + $line['express_postage'];
                    }
                }
                $arr['price'] = sprintf('%.2f', $arr['price']);
                $result['delivery'][$arr['id']] = $arr;
            }
        }
        $result = array_merge($data,$result);
        return $result;
    }
    
    
    /**
     * 获取秒杀信息
     * @param int $itemId    商品id
     * @param int $code      秒杀规格
     * @return \Think\mixed
     */
    public function getInfo($itemId,$code){
        $map['status'] = 1;
        $map['item_id'] = intval($itemId);
        $map['item_spc'] = $code;
        $data = M('seckill_item')->field('id',true)->where($map)->find();
        return $data;
    }
    
    
    /**
     * 获取商品详细信息
     */
    public function itemDetail($info){
        //获取封面图片路径
        if($info['thumb']){
            $info['cover_path'] = get_cover($info['thumb'], 'path');
        }
        
        //获取产品图片路径数组
        if($info['images']){
            $info['images_list'] = get_images_info($info['images'], 'id, path');
        }
        
        //获取此商品参加秒杀的规格
        $seckill_idKeys = array();
        $seckill = A('Item','Event')->seckillData($item_id);
        if(!empty($seckill)){
            $seckill_idKeys = array_column($seckill, null , 'item_id');
        }
        
        //获取商品属性相关的所有数据（属性项，属性值，规格-库存-价格配置数据）
        $info['property'] = $this->getPropertyAll($item_id,$seckill_idKeys);
        
        //查询最高价
        $max_price = D('ItemSpecifiction')->getMaxPrice($item_id);
        
        //查询最低价
        $min_price = D('ItemSpecifiction')->getMinPrice($item_id);
        
        //设置价格区间
        if($max_price && $min_price && $max_price!==$min_price){
            $info['price'] = $min_price . '~' . $max_price;
        }
        
        //获取运费
        $delivery_id = $info['delivery_id'];
        if($delivery_id){
            $info['delivery'] = M('DeliveryTpl')->where('id = '.$delivery_id)->find();
        }
        
        //商品介绍HMTL处理
        $info['intro'] = html_entity_decode($info['intro']);
        
        return $info;
    }
    
}