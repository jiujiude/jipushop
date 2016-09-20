<?php
/**
 * 收货人模型
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class ReceiverModel extends Model{

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('area', 'getAreaName', self::MODEL_BOTH, 'callback'),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取收货信息列表
   * @param array $map 查询条件参数
   * @param string $order 排序规则
   * @param string $field 字段 true-所有字段
   * @param string $limit 分页参数
   * @return array 列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists($map = array(), $order = '`is_default` DESC, `create_time` DESC', $field = true, $limit = '10'){
    $list = $this->field($field)->where($map)->limit($limit)->order($order)->select();
    return $list;
  }

  /**
   * 获取订单详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(!(is_array($info) || $info['status'] !== 1)){
      $this->error = '收货信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新收货信息
   * @param array $data 订单数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    if(empty($data['id'])){
      $where_data = $data;
      unset($where_data['create_time']);
      unset($where_data['update_time']);
      $id = $this->where($where_data)->getField('id');
      if($id > 0){
        $this->id = $_POST['id'] = $data['id'] = $id;
      }
    }
    //添加或更新数据
    $result = ($data['id']) ? $this->save() : $this->add();
    //更新默认地址状态
    $receiver_id = ($data['id']) ? $data['id'] : $result;
    if($data['is_default'] == 1){
      $this->updateDefault($receiver_id);
    }
    //更新统计
    if($result){
      $this->updateCount();
    }
    return $receiver_id;
  }

  /**
   * 删除收货数据
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

  /**
   * 更新收货信息默认状态
   * @param $receiver_id 默认的收货地址ID
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  protected function updateDefault($receiver_id){
    $where['id'] = array('neq', $receiver_id);
    $data['is_default'] = 0;
    return $this->where($where)->save($data);
  }

  /**
   * 更新当前用户收货人统计数据
   * @return array 更新结果
   * @author Max.Yu <max@jipu.com>
   */
  protected function updateCount(){
    $map['uid'] = UID;
    $count = $this->where($map)->count();
    $result = D('Usercount')->setKeyValue(UID, 'receiver_count', $count);
    return $result;
  }

  /**
   * 根据地区id获取地址名称
   * @return string 地址名称
   * @author Max.Yu <max@jipu.com>
   */
  protected function getAreaName(){
    $province = I('post.province');
    $district = I('post.district');
    $city = I('post.city');
    $map['id'] = array('IN', $province.','.$district.','.$city);
    $result = M('area')->where($map)->select();
    return $result[0]['title'].' '.$result[1]['title'].' '.$result[2]['title'];
  }

}
