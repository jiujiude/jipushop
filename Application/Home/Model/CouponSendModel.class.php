<?php
/**
 * 优惠券模型
 * @version 20141010
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Model\RelationModel;

class CouponSendModel extends RelationModel {

	protected $_link = array(
    'Coupon'=> array(
      'mapping_type' => self::BELONGS_TO,
      'class_name' => 'Coupon',
      'foreign_key' => 'cid'
    ),
 	);

	/**
	 * 获取用户优惠券列表
	 * @author Max.Yu <max@jipu.com>
	 */
	public function lists($map){
		$lists = $this->where($map)->relation('Coupon')->select();
		return $lists;
	}

	/**
   * 更新优惠券信息
   * @param array $data 优惠券数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
  	//已优惠券则不添加
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    if(is_fav(UID, $data['fid'])){
    	return false;
    }
    $result = ($data['id']) ? $this->save() : $this->add();
    //更新统计
		if($result){
    	$this->updateCount();
		}
    return $result;
  }

	/**
	 * 删除优惠券数据
	 * @param array $map 查询条件
	 * @return boolean 删除结果
	 * @author Max.Yu <max@jipu.com>
	 */
	public function remove($map){
		$result = $this->where($map)->delete();
		//更新统计
		if($result){
    	$this->updateCount();
		}
		return $result;
	}

}
