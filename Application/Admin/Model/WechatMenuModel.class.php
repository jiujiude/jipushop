<?php
/**
 * 微信自定义菜单模型
 * @version 2015061610
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Think\Model;

class WechatMenuModel extends Model{

	/**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('event', 'eventSave', self::MODEL_BOTH, 'callback'),
  );

	/**
	 * 新增或更新一条数据
	 * @param array  $data 手动传入的数据
	 * @return boolean fasle 失败，int 成功 返回完整的数据
	 * @author Max.Yu <max@jipu.com>
	 */
	public function update($data = null){
    //自定义回复消息也是事件类型
    $_POST['type'] == 'text' && $_POST['type'] = 'click';
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    if($data['id']){
      $this->save();
    }else{
      $this->add();
    }
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

	/**
	 * 获取TOKEN
	 * @param  number $id 段落ID
	 * @return number     总数
	 */
	public function getToken(){
		return C('WECHAT_TOKEN');
	}
  
  /**
   * 事件处理
   */
  protected function eventSave(){
    $event = I('post.event');
    if(I('post.type') != 'click' || empty($event)){
      return '';
    }
    $event_data = session('tmp_'.$event);
    empty($event_data) && $event_data = array();
    $module = M('WechatEvent');
    $line = $module->where(array('key'=> $event))->find();
    $save_data = array(
      'key' => $event,
      'data' => json_encode($event_data),
       //关联自定义回复关键字
      'keyword' => I('post.keyword')
    );
    if($line){
      $res = $module->where('id='.$line['id'])->save($save_data);
    }else{
      $res = $module->add($save_data);
    }
    session('tmp_'.$event, null);
    return $event;
  }
}
