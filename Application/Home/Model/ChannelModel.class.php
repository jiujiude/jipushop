<?php
/**
 * 导航模型
 * @version 2014102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class ChannelModel extends Model{

	/**
	 * 获取导航列表，支持多级导航
	 * @param boolean $field 要列出的字段
	 * @return array 导航树
	 * @author Max.Yu <max@jipu.com>
	 */
	public function lists($field = true){
		$map = array('status' => 1);
		$list = $this->field($field)->where($map)->order('sort')->select();

		return list_to_tree($list, 'id', 'pid', '_');
	}

}
