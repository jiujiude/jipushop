<?php
/**
 * 分销控制器
 * @version 2015080610
 * @author Justin <justin@jipu.com>
 */

namespace Home\Controller;

class SdpController extends HomeController {
  
  protected function _initialize(){
    //记录当前页URL地址Cookie，点击我的登录完成后跳转至个人中心
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    parent::_initialize();
    //判断是否登录
    parent::login();
    $this->assign('user', $this->user);
    $this->assign('member', $this->member);
  }

  /**
   * 收入明细
   * @author Justin <justin@jipu.com>
   */
  public function record(){
    $this->lists = A('Sdp', 'Event')->getSdpRecordList();
    $this->meta_title = '收入明细';
    $this->display(IS_AJAX ? 'recordList' : null);
  }

  /**
   * 收入明细详情
   * @author Justin <justin@jipu.com>
   */
  public function recordDetail(){
    $this->meta_title = '收入明细详情';
    $this->display();
  }

  /**
   * 检测是否绑定提现账户
   * @param int $uid 用户id，默认UID
   * @return int 1有0无
   * @version 2015080709
   * @author Justin <justin@jipu.com>
   */
  function checkBind($uid = UID){
    $this->ajaxReturn(D('UserAccount')->checkBind($uid));
  }
  
}