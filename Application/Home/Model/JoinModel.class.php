<?php
namespace Home\Model;

use Think\Model;
/**
 * 拼团model
 */

class JoinModel extends Model{
    
    
    /**
     * 获取详情页数据
     * @param int    $item_id   商品id
     * @param string $field     查询字段
     * @return array
     */
    public function detail( $item_id , $field=true ){
        $info = M('Item')->field($field)->find($item_id);
        
        $jMap['item_id'] = array('eq',$item_id);
        $jMap['stime'] = array('elt',NOW_TIME);
        $jMap['etime'] = array('gt',NOW_TIME);
        $jMap['status'] = array('eq',1);
        $join = M('join_item')->where($jMap)->find();
        
        if(!(is_array($info) || 1 !== $info['status'])){
            $this->error = '商品已下架或已删除！';
            return false;
        }
        
        if(empty($join) || ($join['status'] != 1)){
            $this->error = '活动已结束！';
            return false;
        }
        
        //获取封面图片路径
        if($info['thumb']){
            $info['cover_path'] = get_cover($info['thumb'], 'path');
        }
        //获取产品图片路径数组
        if($info['images']){
            $info['images_list'] = get_images_info($info['images'], 'id, path');
        }
        
        //获取拼团的商品属性相关的所有数据（属性项，属性值，规格-库存-价格配置数据）
        $info['property'] = $this->getPropertyAll($item_id);
        $info['mprice'] = $info['price'];
        //拼团价格
        $info['price'] = $join['price'];
        //拼团库存
        $info['stock'] = $join['stock'];
        
        //获取运费
        $delivery_id = $info['delivery_id'];
        if($delivery_id){
            $info['delivery'] = M('DeliveryTpl')->where('id = '.$delivery_id)->find();
        }
        
        //获取收藏状态
        $info['is_fav'] = is_fav(UID, $info['id']);
        
        //商品介绍HMTL处理
        $info['intro'] = html_entity_decode($info['intro']);
        $info['join'] = $join;
        
        return $info;
        
    }
    
    
    
    /**
     * 获取秒杀商品属性相关的所有数据（属性项，属性值，规格-库存-价格，最高价与最低价）
     * 
     * @param string $item_id          商品id
     * @return array
     */
    private function getPropertyAll($item_id = null){
        
        //拼团后台选择规格
        $propVal = array();
        $field = 'spc_code,quantity,first_price,join_price';
        $spc_info = M('join_item_spec')->where('item_id = '.$item_id)->field($field)->select();
        if($spc_info){
            foreach ($spc_info as $key=>$val){
                $propVal = array_merge($propVal,explode('-',$val['spc_code']));
            }
        }
        
        $returnInfo = array();
        //获取该商品的属性值和关联的属性项信息（关联模型不能用从表的字段排序，用原生sql代替）
        $prefix = C('DB_PREFIX');
        $l_table = $prefix.'item_extend';
        $r_table = $prefix.'item_property';
        $field = 'b.type, b.cname, b.ename, b.displayorder, b.formtype, a.*';
        $order = 'order by b.displayorder asc, b.id asc';
        $sql = 'select '.$field .' from '.$l_table.' a, '.$r_table.' b where a.item_id = '.$item_id.' AND a.prp_id = b.id '.$order;
        $property = M()->query($sql);
    
        $property_specifiction = array();    //属性：规格
        $property_attribute = array();        //属性：参数
    
        //分离出规格和参数数组
        if($property && is_array($property)){
            foreach ($property as $key => &$val) {
                if($val['type']=='specification'){
                    !empty($propVal) && $val['info'] = $this->getOptionValue(unserialize($val['info']), $val['formtype'],$propVal);
                    if(isset($val['info']) && !empty($val['info'])){
                        $property_specifiction[] = $val;
                    }
                    
                } elseif ($val['type']=='attribute'){
                    $val['info'] = $this->getOptionValue(unserialize($val['info']), $val['formtype']);
                    $property_attribute[] = $val;
                }
            }
        }
        $returnInfo['property_specifiction'] = $property_specifiction;
        $returnInfo['property_attribute'] = $property_attribute;
    
        //获取规格-库存-价格组合数据
        $spc_data = null;
        $stock = 0;
        $lists = M('JoinItemSpec')->where(array('item_id' => $item_id))->field(true)->select();
        if($lists && is_array($lists)){
            foreach ($lists as $key => $val){
                //为了避免出现负数
                $val['quantity'] = ($val['quantity'] >=0 )? $val['quantity'] : 0;
                $stock += $val['quantity'];
                $spc_data[$val['spc_code']] = $val;
            }
            $spc_data = json_encode($spc_data);
        }
        $returnInfo['spc_data'] = $spc_data;
        $returnInfo['stock'] = $stock;
    
        //dump($returnInfo);exit;
        return $returnInfo;
    }
    
    
    /**
     * 获取商品属性选项配置值
     * @author Max.Yu <max@jipu.com>
     */
    public function getOptionValue($item_info = null, $formtype = null , $propVal = array()){
        $returnInfo = array();
        $pic_path = array();
    
        if($item_info && is_array($item_info)){
            if($formtype === 'color'){
                $pic = $item_info['pic'];
    
                if($pic && is_array($pic)){
                    foreach($pic as $k => $v) {
                        if($v){
                            $pic_path[] = get_cover($v, 'path');
                        } else{
                            $pic_path[] = null;
                        }
                    }
                }
            }
    
            $property = $item_info['property'];
            if($property && is_array($property)){
                foreach($property as $key => $val) {
                    if($val && is_array($val)){
                        $i = 0;
                        foreach($val as $k => $v) {
                            if(in_array($v,$propVal)){
                                $option = $this->getOptionByCode($v);
                                if($formtype === 'color'){
                                    $option['item_pic'] = $pic_path[$i];
                                    $i++;
                                }
                                $returnInfo[] = $option;
                            }
                            
                        }
                    } else if(in_array($val,$propVal)){
                        if($formtype === 'input' || $formtype === 'textarea'){
                            $returnInfo[] = $val;
                        }else{
                            $returnInfo[] = $this->getOptionByCode($val);
                        }
                    }
                }
            }
        }
        return $returnInfo;
    }
    
    
    /**
     * 输出商品属性选项值
     * @author Max.Yu <max@jipu.com>
     */
    public function getOptionByCode($code){
    
        $returnInfo = array();
    
        //实例化属性选项模型
        $PropertyOption = M('PropertyOption');
    
        //定义返回或者操作的字段
        $field = '*';
    
        //查询条件初始化
        $where['code'] = $code;
    
        //获取属性选项值列表
        $returnInfo = $PropertyOption->where($where)->field($field)->find();
    
        return $returnInfo;
    }
    
    
    /**
     * 检测商品
     * @return boolean
     */
    private function _checkOrderData(){
        
        !cookie('__joinnow__') && $this->error = '已超时请重新下单！';
        $joinItem = unserialize(cookie('__joinnow__'));
        
        //检测库存
        $item_stock = $this->getItemStock($joinItem['item_id'], $joinItem['item_code']);
        if($joinItem['quantity'] > $item_stock){
            $this->error = '商品库存不足导致下单失败！';
            return false;
        }
        
        //检测限购
        $quota_num = get_quota_num($joinItem['item_id']);
        if($quota_num < $joinItem['quantity']){
            $this->error = '商品超过限购数量导致下单失败！';
            return false;
        }
        return true;
    }
    
    
    /**
     * 用户下单操作
     */
    public function addHandle(){
        $join_id = I('post.join_id','');
        $reg_uid = I('post.reg_uid','');
        $item_id = I('post.item_ids');
        $receiver_id = I('post.receiver_id');
        
        if(empty($item_id)){
            $this->error = '订单商品不能为空！';
            return false;
        }
        
        if(empty($receiver_id)){
            $this->error = '请您选择收货地址！';
            return false;
        }
        
        if(!$this->_checkOrderData()){
            return false;
        }
        $time = time();
        $iteMap['status'] = array('eq',1);
        $iteMap['item_id'] = array('eq',$item_id);
        $iteMap['stime'] = array('elt',$time);
        $iteMap['etime'] = array('gt',$time);
        
        $joinItem = M('join_item')->where($iteMap)->find();
        
        if(!$joinItem){
            $this->error = '活动已结束或商品已下架';
            return false;
        }
        //支付id
        $payment_id = D('Payment')->update();
        
        //收货地址信息
        if(!$this->_orderShipUpdate($payment_id)){
            return false;
        }
        
        $joinData = array();
        $joinData['uid'] = UID;
        $joinData['item_id'] = $item_id;
        $joinModel = M('join_list');
        
        //参团
        if(!empty($join_id)){
            //查看团是否是正常状态的
            $listMapp['id'] = $join_id;
            $listMapp['status'] = 0;
            // $listMapp['reg_uid'] = $reg_uid;
            // $listMapp['item_id'] = $item_id;
            $list = $joinModel->where($listMapp)->find();
            if(!$list){
                $this->error = '拼团不存在！';
                return false;
            }
           
        }
        
        //拼团订单数据
        $data = array();
        $data['ltime'] = $joinItem['etime'] ;
        $data['join_id'] = $join_id ;
        $data['join_sn'] = create_order_sn();
        $data['uid']     = UID;
        $data['item_id'] = $item_id;
        $data['payment_id'] = $payment_id;
        
        //cookie中获取商品
        $join_item = unserialize(cookie('__joinnow__'));
        if($join_item){
            unset($join_item['delivery']);
            unset($join_item['cover_path']);
        }
        
        //根据购物车商品规格，重新获取商品价格
        $order_count = A('Join', 'Event')->doCount($item_id);
        //运费
        $delivery_data = A('Order', 'Event')->getDeliveryAmount($order_count['supplier']);
        $data['delivery_id'] = $delivery_data[0]['delivery_id'];
        $data['delivery_fee'] = $delivery_data[0]['amount'];
        
        $data['total_price'] = $order_count['total_price'];
        
        $data['total_quantity'] = $order_count['total_quantity'];
        $data['weight'] = $order_count['total_weight'];
        $data['memo'] = I('memo');
        
        //TODO:从cookie中获取供应商
        $supplier = M('Item')->field('supplier_id')->find($item_id);
        $data['supplier_id'] = $supplier['supplier_id'];
        
        $data['item_code'] = $join_item['item_code'];
        $data['item_name'] = $join_item['name'];
        $data['spec'] = serialize($join_item['spec']);
        $data['thumb'] = $join_item['thumb'];
        //账户余额使用金额（拼团订单暂时不支持余额支付）
        $data['finance_amount'] = '';
        //第三方支付总额
        $data['total_amount'] = sprintf('%.2f', $data['total_price'] + $data['delivery_fee']);
        $data['sub_total'] = $join_item['subAmount'];
        $data['ctime']     = time();
        //更新join_order表
        $rst = M('join_order')->add($data);
        if($rst){
            cookie('__joinnow__', null);
            //更新统计
            //$this->updateCount();
        }else{
            $rst = false;
            $this->error = '拼团订单提交失败！';
        }
        return $rst;
    }
    
    
    /**
     * 根据商品id获取拼团库存
     * @param int     $item_id
     * @param string  $item_code
     */
    function getItemStock($item_id, $item_code){
        if(empty($item_id) || empty($item_code)){
            return false;
        }
        $spec_map = array(
                'item_id' => $item_id,
                'spc_code' => $item_code
        );
        $check_spec = M('join_item_spec')->where($spec_map)->find();
        if($check_spec){
            $result = $check_spec['quantity'];
        }else{
            $item_map = array(
                    'id' => $item_id,
            );
            $result = M('join_item')->where($item_map)->getField('stock');
        }
        return $result;
    }
    
    
    
    
    
    /**
     * 根据商品id获取价格
     * @param int $item_id 商品id
     * @param string $item_code 商品编码
     * @param string $active 活动id
     * @return array 更新结果
     * @author Max.Yu <max@jipu.com>
     */
    function getItemPrice($item_id, $item_code,$active){
        if(empty($item_id) || empty($item_code)){
            return false;
        }
    
        $spec_map = array(
            'item_id' => $item_id,
            'spc_code' => $item_code
        );
        $check_spec = M('join_item_spec')->where($spec_map)->find();
        if($check_spec){
            $result = $active ? $check_spec['join_price'] : $check_spec['first_price'];
        }else{
            $item_map = array(
                'item_id' => $item_id,
            );
            $priceData = M('join_item')->where($item_map)->find();
            $result = $active ? $priceData['join_price'] : $priceData['first_price'];
        }
    
        return $result;
    }
    
    
    /**
     * 写入订单收货信息
     * @author Justin <justin@jipu.com>
     */
    private function _orderShipUpdate($payment_id){
        $receiver_id = I('post.receiver_id');
        //获取收货人信息
        $receiver = D('Receiver')->detail(array('id' => $receiver_id));
        //过滤收货地址
        if(!M('Area')->find($receiver['city'])){
            $this->error = '您所选的收货地址无法送达，请重新选择！';
            return false;
        }
        $data['ship_uname'] = $receiver['name'];
        $data['ship_mobile'] = $receiver['mobile'];
        $data['ship_phone'] = $receiver['phone'];
        $data['ship_province'] = $receiver['province'];
        $data['ship_district'] = $receiver['district'];
        $data['ship_city'] = $receiver['city'];
        $data['ship_area'] = $receiver['area'];
        $data['ship_address'] = $receiver['address'];
        $data['ship_zipcode'] = $receiver['zipcode'];
    
        $order_ship_data = $data;
        $order_ship_data['payment_id'] = $payment_id;
        D('OrderShip')->update($order_ship_data);
        return true;
    }
    
    
    /**
     * 
     * @param unknown $map
     * @param string $field
     * @return boolean|unknown
     */
    public function orderDetail($map, $field = true){
        $info = M('join_order')->field($field)->where($map)->find();
        if(!(is_array($info) || $info['status'] !== 1)){
            $this->error = '订单信息不存在！';
            return false;
        }
        
        //收货人信息
        $info['ship'] = M('OrderShip')->getByPaymentId($info['payment_id']);
//         //支付信息
//         $info['payment'] = M('Payment')->getById($info['payment_id']);
//         //支付方式
//         $info['payment_type_text'] = $info['payment'] ? get_payment_type_text($info['payment']): '';
        return $info;
    }
    
    
    /**
     * 获取某个拼团活动参加人数
     * @param int $id   拼团活动id
     */
    public function JoinCount($id){
        $map['join_id'] = $id;
        $map['status'] = 1;
        $joinCount = M('join_order')->where($map)->count();
        return $joinCount;
    }
    
    
    public function getMaxPrice($item_id = null){
    	$returnInfo = 0;
    	if($item_id){
    		$max_price = M('join_item_spec')->where('item_id=' . $item_id)->max('price');
    		if($max_price){
    			$returnInfo = $max_price;
    		}
    	}
    	return $returnInfo;
    }
    
    
    public function getMinPrice($item_id = null){
    	$returnInfo = 0;
    	if($item_id){
    		$min_price = $this->where('item_id=' . $item_id)->min('price');
    		if($min_price){
    			$returnInfo = $min_price;
    		}
    	}
    	return $returnInfo;
    }
    
    
}
