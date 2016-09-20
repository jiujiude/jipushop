<?php
/**
 * 文章模型
 * @version 2015010714
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;
use Think\Model;

class ArticleModel extends Model {
 /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('cid', 'number', '请选择文章分类', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('title', 'require', '请填写文章标题', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    //array('content', 'require', '请填写文章内容', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('uid', UID, self::MODEL_INSERT),
    array('category', '_getCategoryName', self::MODEL_BOTH, 'callback'),
    array('status', 1, self::MODEL_INSERT),
    array('create_time', NOW_TIME, self::MODEL_INSERT),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  public function lists($map, $order = '`id` DESC, `create_time` DESC', $field = true, $limit = '10'){
    $result = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    return $result;
  }

  /**
   * 获取一条记录详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    if(empty($info) || $info['status'] == -1){
      $this->error = '信息不存在！';
      return false;
    }
    return $info;
  }

  /**
   * 更新记录
   * @param array $data 更新数据
   * @return boolean 更新状态
   * @author Max.Yu <max@jipu.com>
   */
  public function update($data = null){
    $data = $this->create($data);
    if(!$data){
      return false;
    }
    /* 添加或更新数据 */
    if(empty($data['id'])){
      $res = $this->add($data);
    }else{
      $res = $this->save($data);
    }
    return $data;
  }

  /**
   * 删除记录
   * @return true 删除成功，false 删除失败
   * @author Max.Yu <max@jipu.com>
   */
  public function remove($ids){
    $map['id'] = array('IN', $ids);
    $res = $this->where($map)->delete();
    return $res;
  }
  
  /**
   * 获取分类名称
   */
  protected function _getCategoryName(){
    $cid = I('post.cid', 0);
    $names = get_category_name(get_parent_cid($cid));
    $cid_names = array_column($names, 'name');
    return implode('/', $cid_names);
  }
}