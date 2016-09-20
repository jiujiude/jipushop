<?php
/**
 * 后台优惠券发放控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class CouponUserController extends AdminController {

  /**
   * 优惠券发放列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index() {
    //记录当前列表页的Cookie
    Cookie('__forward__',$_SERVER['REQUEST_URI']);
    if(I('get.id'))$where['coupon_id']=I('get.id');
    // 获取优惠券列表
    $prefix   = C('DB_PREFIX');
    $list = $this->lists(M()->table($prefix.'coupon_user cu')->join($prefix.'coupon_num cn on cu.id=cn.cn_couponuser_id'), $where, '`id` DESC', null, $field);
    $cid_arr = get_sub_by_key($list, 'coupon_id');
    $cid_arr = array_unique($cid_arr);
    $cids = implode(',', $cid_arr);
    if($cids){
      $cmap['id'] = array('IN', $cids);
      $coupon_list = $this->lists(M('Coupon'), $cmap, '`id` DESC', null, $field);

      if($coupon_list){
        foreach($coupon_list as $key => $value) {
          $coupon_arr[$value['id']] = $value;
        }
      }
      foreach($list as $key => &$value) {
        $value['coupon'] = $coupon_arr[$value['coupon_id']];
      }
    }
      

    //模板输出变量赋值
    $this->assign('list', $list);
    $this->meta_title = '优惠券列表';
    $this->display();
  }

  /**
   * 添加数据
   * @author Max.Yu <max@jipu.com>
   */
  public function add() {
    $cid = I('request.id');
    if(empty($cid)){
      $this->error('请选择要发放的优惠券!');
    }
    $where['id'] = $cid;
    $data = D('Coupon')->detail($where);

    /*模板输出变量赋*/
    $this->assign('data', $data);
    $this->meta_title = '发放优惠券';
    $this->display();
  }

  /**
   * 更新优惠券发放信息
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    $coupon_id = I('post.coupon_id');
    $uids = I('post.uids');
    if(empty($coupon_id)){
      $this->error('优惠券ID不能为空！');
    }

    if(empty($uids)){
      $this->error('发放用户ID不能为空！');
    }

    $res = D('CouponUser')->updateByUids($coupon_id, $uids);
    if($res){
      $jump_url = U('index');
      $this->success('发放成功！', $jump_url);
    }else{
      $this->error('发放失败！');
    }
  }

  /**
   * 删除数据
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');
    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }

    $map['id'] = array('in', $ids);
    if(M('CouponUser')->where($map)->delete()){
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }

  /**
   * 优惠券发放
   * @author Max.Yu <max@jipu.com>
   */
  public function send(){
    $cid = I('request.id');
    if(empty($cid)){
      $this->error('请选择要发放的优惠券!');
    }
    if(IS_POST){
      $uids = I('post.uids');
      echo $uids;
      if(empty($cid)){
        $this->error('请填写要发放的用户uid!');
      }
      $res = D('CouponUser')->update();
      if(!$res){
        $this->error('发放失败!');
      }else{
        $this->success('发放成功!');
      }
    }else{
      $data['cid'] = $cid;
      $data['coupon'] = D('CouponUser')->detail($cid);
      $this->assign('data', $data);
      $this->meta_title = '发放优惠券';
      $this->display();
    }
  }
}
