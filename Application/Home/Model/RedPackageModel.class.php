<?php
/**
 * 红包数据模型
 * @version 2015102014
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Model;

use Think\Model;

class RedPackageModel extends Model{

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT)
  );

  /**
   * 获取充值详情
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @return array 详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($map, $field = true){
    $info = $this->field($field)->where($map)->find();
    //完成状态监测
    $info['is_finished'] = $info['number'] == $info['send_number'];
    
    //手气最佳
    $info['is_finished'] && $info['user_amount_max'] = M('RedPackageRecord')->where(array('red_package_id'=>$info['id']))->order('amount desc')->getField('amount');
    //分享信息
    $info['meta_share'] = array(
      'title' => $info['share_title'],
      'desc' => $info['share_desc'],
      'img_url' => SITE_URL.__IMG__.'/red-package-share.png?'.NOW_TIME,
      'link' => SITE_URL.U('RedPackage/detail', array('_code' => $info['code']))
    );
    //检测是否使用手气最佳提示
    if(round($info['user_amount_max']* $info['number'] - $info['amount'], 2) == 0){
      unset($info['user_amount_max']);
    }
    return $info;
  }

}
