<?php
/**
 * 属性值模型
 * @author Max.Yu <max@jipu.com>
 */
namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;

class ItemExtendModel extends RelationModel {

	/**
	 * 定义关联
	 * @var array
	 * @author Max.Yu <max@jipu.com>
	 */
	protected $_link = array(
		'property'=> array(
			'mapping_type'	=> self::BELONGS_TO,
			'mapping_name'	=> 'property',
			'class_name'	=> 'ItemProperty',
			'foreign_key'	=> 'prp_id',
			//'mapping_fields'=> 'type,cname,ename,sort,formtype',
			'mapping_order'	=> 'sort asc',
			'as_fields'	=> 'type,cname,ename,sort,formtype',
		),
	);

	/**
	 * 获取属性值与属性项的关联列表
	 * @author Max.Yu <max@jipu.com>
	*/
	public function lists($where){
		$lists = $this->where($where)->relation('property')->select();
		return $lists;
	}

}
