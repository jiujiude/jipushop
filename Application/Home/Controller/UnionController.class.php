<?php
/**
 * 推广联盟控制器
 * @version 2015091814
 * @author Justin <justin@jipu.com>
 */

namespace Home\Controller;

class UnionController extends HomeController {
  
  //判断是否开通
  protected $is_open;
  
  protected function _initialize(){
    //记录当前页URL地址Cookie，点击我的登录完成后跳转至个人中心
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    parent::_initialize();
    //判断是否登录
    parent::login();
    $data_union = M('Union')->getByUid(UID);
    $this->data_union = $data_union ? $data_union : false;
    
    if($data_union['id']>0 && empty($data_union['qrcode_url'])){
      D('Admin/Union')->_after_insert($data_union);
    }
  }
  
  /**
   * 联盟首页
   * @author Justin <justin@jipu.com>
   */
  function index(){
    if(C('DIS_START') != 1){
      $this->error('功能暂时关闭，敬请期待...');
    }
    $this->data = A('Union', 'Event')->getCountData('Subscribe', UID, I('get.start_time'), I('get.end_time'));
    $counts['one'] = M('Distribution')->where('oneagents  ='.UID)->count();
    $counts['two'] = M('Distribution')->where('twoagents  ='.UID)->count();
    $counts['three'] = M('Distribution')->where('threeagents='.UID)->count();
    $this->assign('counts' , $counts);
    $this->meta_title = '推广联盟';
    $this->display();
  }
  
  /**
   * 申请开通
   * @author Justin <justin@jipu.com>
   */
  function add(){
    //判断是否存在
    if(!$this->data_union){
      $nickname = get_nickname(UID);
      $data = array(
        'uid' => UID,
        'type' => 2,
        'name' => $nickname,
        'link_name' => $nickname,
        'link_mobile' => M('User')->getFieldById(UID, 'mobile')
      );
      D('Admin/Union')->update($data);
    }
    //   if(D('Admin/Union')->update($data)){
    //     $this->success('开通成功!');
    //   } 
    // }else{
    //   $this->error('开通失败!');
    // }
  }
  
  /**
   * 显示二维码
   * @author Justin <justin@jipu.com>
   */
  function detail($qrcode_url = ''){
    $this->qrcode_url = $qrcode_url;
    $this->display();
  }
  
  /**
   * 订单统计
   * @author Justin <justin@jipu.com>
   */
  function order(){
    //非桌面商家跳回关注统计页面
    if($this->data_union['type'] != 1){
      $this->redirect('Member/union');
    }
    $this->data = A('Union', 'Event')->getCountData('Order', UID, I('get.start_time'), I('get.end_time'));
    $this->display();
  }

}
