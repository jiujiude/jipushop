<?php
/**
 * 前台优惠券领取控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;
use Think\Page;

class CouponUserController extends HomeController {

  private $CouponUser;

  public function _initialize(){
    parent::_initialize();
    //用户登录验证
    parent::login();
    //实例化收藏模型
    $this->CouponUser = D('CouponUser');
  }

  /**
   * 添加数据
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    $id = I('post.id');
    if(empty($id)){
      $this->error('优惠券不能为空');
    }
    $id_arr = explode(',', $id);

    if(is_array($id_arr)){
      foreach($id_arr as $v){
        $coupon=M('Coupon')->find($v);
        //判断是否领完
        if(M('CouponUser')->where(array('coupon_id'=>$v))->count()<$coupon['num'] || empty($coupon['num'])){
          $data = array(
              'uid' => UID,
              'coupon_id' => $v
          );
          $add[] = D('CouponUser')->update($data);
        }
      }
    }
    if(array_sum($add) > 0){
      $result['status'] = 1;
      $result['info'] = $id;
    }else{
      $result['status'] = 0;
    }
    //更新数据
    $this->ajaxReturn($result);
  }

}