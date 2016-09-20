<?php
/**
 * 商品模型
 * @version 2014060812
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class ItemModel extends Model{

  /**
   * 获取商品列表
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @param string $order 排序规则
   * @param string $limit 分页参数
   * @return array 商品列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map = array(), $field = true, $order = '`is_top` DESC, `sort` ASC', $limit = '10'){
    !$map['status'] && $map['status'] = 1;
    $list = $this->where($map)->cache(true)->field($field)->order($order)->limit($limit)->select();
    if($list && is_array($list)){
      foreach ($list as $key => &$value){
        if($value['thumb']){
          $value['cover_path'] = get_cover($value['thumb'], 'path');
        }
        $value['discount'] = sprintf("%.1f", $value['price'] / $value['mprice'] * 10);
        if(!$value['buynum']){
          $value['buynum'] = 0;
        }
      }
    }
    return $list;
  }

  /**
   * 根据商品分类获取商品列表
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @param string $order 排序规则
   * @param string $limit 分页参数
   * @return array 商品列表
   * @author Max.Yu <max@jipu.com>
   */
  public function listsByCategory(){
    $lists = D('ItemCategory')->where('pid = 0')->order('`sort` ASC')->select();
    if($lists){
      foreach($lists as $key => &$value){
        $where['cid_1'] = $value['id'];
        $value['item_list'] = $this->lists($where, true, '`sort` ASC');
      }
    }
    return $lists;
  }

  /**
   * 计算列表总数
   * @param array $map 查询条件参数
   * @return integer 总数
   */
  public function listCount($map){
    return $this->where($map)->count('id');
  }

  /**
   * 获取商品列表-分页方式
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @param integer $page 页码
   * @param string $limit 分页参数
   * @param string $order 排序规则
   * @return array 文档列表
   * @author Max.Yu <max@jipu.com>
   */
  public function listsPage($map = array(), $field = true, $page = 1, $limit = '10', $order = '`id` DESC'){
    $map['status'] = 1;
    $list = $this->field($field)->where($map)->order($order)->limit($limit)->page($page)->select();
    if($list && is_array($list)){
      foreach ($list as $key => &$value){
        if($value['thumb']){
          $value['cover_path'] = get_cover($value['thumb'], 'path');
          $value['cover_src'] = get_image_thumb($value['cover_path'], 220, 220);
        }
        $value['discount'] = sprintf("%.1f", $value['price'] / $value['mprice'] * 10);
        if(!$value['buynum']){
          $value['buynum'] = 0;
        }
      }
    }
    return $list;
  }

  /**
   * 根据商品id获取列表
   * @param arr || str $ids 商品ID，可为数组或逗号分隔的字符串
   * @param string $order 排序规则
   * @param boolean $count 是否返回总数
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 商品列表
   * @author Max.Yu <max@jipu.com>
   */
  public function listsById($ids, $field = true, $order = '`id` DESC', $limit = '10'){
    $itemIds = (is_array($ids)) ? arr2str($ids) : $ids;
    $map['id'] = array('IN', $itemIds);
    $map['status'] = 1;
    $lists = $this->field($field)->where($map)->order($order)->limit($limit)->select();

    //获取商品缩略图
    if($lists && is_array($lists)){
      foreach ($lists as $key => &$value){
        if($value['thumb']){
          $value['cover_path'] = get_cover($value['thumb'], 'path');
        }
      }
    }
    return $lists;
  }

  /**
   * 获取详情页数据
   * @param integer $item_id 商品id
   * @param integer $field 查询字段
   * @param integer $item_code 当前选中的规格code
   * @return array 详细数据
   */
  public function detail($item_id, $field = true,$item_code=''){
    //获取基础数据
    $info = $this->field($field)->find($item_id);
    if(!(is_array($info) || 1 !== $info['status'])){
      $this->error = '商品已下架或已删除！';
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

    //获取商品相关的优惠券
    $coupons = D('Coupon')->where('is_expire = 0')->select();
    if($coupons){
      foreach($coupons as $key => &$value){
        //判断当前登录用户是否已经领取过
        $value['is_get_coupon'] = is_get_coupon(UID, $value['id']);
        //全场通用优惠券
        if(!$value['items']){
          $info['coupons'][] = $value;
        }else{
          $item_arr = explode(',', $value['items']);
          if(in_array($item_id, $item_arr)){
            $info['coupons'][] = $value;
          }
        }
      }
    }

    //获取收藏状态
    $info['is_fav'] = is_fav(UID, $info['id']);

    //商品介绍HMTL处理
    $info['intro'] = html_entity_decode($info['intro']);

    return $info;
  }

  /**
   * 获取所有优惠券包含的商品
   * @author Max.Yu <max@jipu.com>
   */
  public function getCouponItems(){
    $coupons = $this->where('status = 1')->select();
    if($coupons){
      foreach($coupons as $key => $value){
        $couponsIds .= $value['items'].',';
      }
    }
    $couponsIds = explode(',', trim($couponsIds, ','));
    $couponsIds = array_unique($couponsIds);
    return $couponsIds;
  }

  /**
   * 格式化商品列表数据，deal封面和折扣
   * @author Max.Yu <max@jipu.com>
   */
  private function formatItems($lists){
    if($lists && is_array($lists)){
      foreach ($lists as $key => &$value){
        if($value['thumb']){
          $value['cover_path'] = get_cover($value['thumb'], 'path');
        }
        $value['discount'] = sprintf("%.1f", $value['price']/$value['mprice']*10);
        if(!$value['buycount']){
          $value['buycount'] = 0;
        }
      }
    }
    return $lists;
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
   * 获取商品属性选项配置值
   * @author Max.Yu <max@jipu.com>
   */
  public function getOptionValue($item_info = null, $formtype = null){
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
              $option = $this->getOptionByCode($v);

              if($formtype === 'color'){
                $option['item_pic'] = $pic_path[$i];
                $i++;
              }

              $returnInfo[] = $option;
            }
          } else{
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
   * 获取商品属性相关的所有数据（属性项，属性值，规格-库存-价格，最高价与最低价）
   * 替换掉秒杀的规格价格
   * @author Max.Yu <max@jipu.com>
   */
  function getPropertyAll($item_id = null,$seckill_idKeys=array()){
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
          $val['info'] = $this->getOptionValue(unserialize($val['info']), $val['formtype']);
          $property_specifiction[] = $val;
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
    $ItemSpecifiction = D('ItemSpecifiction');
    $lists = $ItemSpecifiction->lists(array('item_id' => $item_id));
    if($lists && is_array($lists)){
      foreach ($lists as $key => $val){
        $val['is_kill'] = 0;
        if($seckill_idKeys && $seckill_idKeys[$item_id]['item_spc'] == $val['spc_code']){
            $val['price'] = $seckill_idKeys[$item_id]['item_price'];
            $val['quantity'] = $seckill_idKeys[$item_id]['item_stock'];
            $val['is_kill'] = 1;
        }
        //为了避免出现负数
        $val['quantity'] = ($val['quantity'] >=0 )? $val['quantity'] : 0;
        $stock += $val['quantity'];
        $spc_data[$val['spc_code']] = $val;
      }
      $spc_data = json_encode($spc_data);
    }
    $returnInfo['spc_data'] = $spc_data;
    $returnInfo['stock'] = $stock;
    return $returnInfo;
  }
  
  /**
   * 根据商品ID获取商品名称
   * @param $item_ids 商品ID集合，以逗号隔开的字符串形式
   * @return string 商品名称结合，以逗号隔开
   * @author Max.Yu <max@jipu.com>
  */
  public function getItemNames($item_ids){
    $item_names = '';
    $where['id'] = array('IN', $item_ids);
    $lists = $this->field('name')->where($where)->select();

    if($lists && is_array($lists)){
      foreach ($lists as $key => $value){
        $item_names = $item_names . $value['name'] . '，';
      }
    }
    return $item_names;
  }

  /**
   * 根据商品ID获取商品名称
   * @param $item_ids 商品ID集合，以逗号隔开的字符串形式
   * @return string 商品名称，只返回第一个商品名称
   * @author Max.Yu <max@jipu.com>
  */
  public function getItemNamesForPay($item_ids){
    $item_names = '';
    $id_array = explode(",", $item_ids);
    if($id_array && is_array($id_array)){
      $first_id = $id_array[0];
      $item = $this->where(array('id'=>$first_id))->field('name')->find();
      if($item){
        $item_names = $item['name'];
        if(count($id_array) > 1){
          $item_names = $item_names . ' 等多件商品';
        }
      }
    }
    return $item_names;
  }

}
