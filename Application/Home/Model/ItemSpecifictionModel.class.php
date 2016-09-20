<?php
/**
 * 商品属性规格-库存-价格数据模型
 * @version 2014060812
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class ItemSpecifictionModel extends Model {

	/**
	 * 根据条件查询规格-库存-价格信息
	 * @param array $where 查询条件参数
	 * @param string $field 查询返回字段
	 * @param string $order 排序规则
	 * @param string $limit 分页参数
	 * @return array 返回数据列表
	 * @author Max.Yu <max@jipu.com>
	 */
	public function lists($where = null, $field = '*', $order = null, $limit = null){
		$returnInfo = array();

		//查询数据
		$returnInfo = $this->where($where)->order($order)->field($field)->select();

		return $returnInfo;
	}
	
	/**
	 * 查询商品的最高价格
	 * @param array $item_id 商品ID
	 * @return string 返回最高价格
	 * @author Max.Yu <max@jipu.com>
	 */
	public function getMaxPrice($item_id = null){
		$returnInfo = 0;
		if($item_id){
			$max_price = $this->where('item_id=' . $item_id)->max('price');
			if($max_price){
				$returnInfo = $max_price;
			}
		}
		return $returnInfo;
	}

	/**
	 * 查询商品的低高价格
	 * @param array $item_id 商品ID
	 * @return string 返回最低价格
	 * @author Max.Yu <max@jipu.com>
	 */
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
