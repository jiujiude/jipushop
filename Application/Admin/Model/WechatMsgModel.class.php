<?php
/**
 * 微信消息模型
 * @version 2015061610
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Think\Model;

class WechatMsgModel extends Model{

	/**
	 * 自动完成规则
	 */
	protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
	);

  /**
   * 获取一条数据详情
   * @author Max.Yu <max@jipu.com>
  */
  public function detail($map){
    if(!$map){
      return false;
    }
    $data = $this->where($map)->find();
    if($data['attach']){
      $coverArr = get_cover($data['attach']);
      $data['attach_url'] = $coverArr['path'];
    }
    return ;
  }

	/**
	 * 新增或更新一条数据
	 * @param array $data 手动传入的数据
	 * @return boolean fasle 失败，int 成功 返回完整的数据
	 * @author Max.Yu <max@jipu.com>
	 */
	public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    $result = ($data['id']) ? $this->save() : $this->add();
    return $data;
	}

	/**
   * 删除数据
   * @return true 删除成功， false 删除失败
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($ids){
    $map['id'] = array('IN', $ids);
    $res = $this->where($map)->delete();
    return $res;
  }

}
