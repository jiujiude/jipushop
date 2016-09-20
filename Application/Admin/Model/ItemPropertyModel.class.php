<?php
/*
 * 商品属性模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Think\Model;

class ItemPropertyModel extends Model {
	/**
	 * 自动验证规则
	 */
	protected $_validate = array(
	array('cid', 'require', '请选择需要绑定的分类', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  	array('cname', 'require', '名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  	array('ename', 'require', '标识不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
	);

	/*
	 * 自动完成规则
	 */
	protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
	);

	/*
	 * 根据条件查询属性配置信息
	 * @author Max.Yu <max@jipu.com>
	 */
	public function getItemProperty($type){
		$info = array();

		//筛选条件
		if(isset($type)){
			$where['type'] = $type;
		}

		//定义返回或者操作的字段
		$field = '*';

		//定义排序条件
		$order = 'displayorder asc, id desc';

		//查询属性项目
		$info = $this->where($where)->order($order)->field($field)->select();
	
		return $info;
	}
}