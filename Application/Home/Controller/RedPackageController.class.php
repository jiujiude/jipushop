<?php

/**
 * 红包控制器
 * @version 15121610
 * @author Justin <justin@jipu.com>
 */

namespace Home\Controller;

class RedPackageController extends HomeController{

  /**
   * 抢红包页面
   */
  public function detail($_code = ''){

    $data = D('RedPackage')->detail(array('status' => 1, 'code' => $_code));
    if(is_mobile() && is_weixin()){
      if(empty($data)){
        $this->error('红包不存在！', U('/'));
      }
      $event = A('RedPackage', 'Event');
      $openid = $event->getOpenId();
      $data['self_record'] = $event->getRecord($data['id'], $openid);
      $is_subscribe = is_subscribe($openid);
      if($data['self_record'] && $is_subscribe){
        $this->redirect('open', array('_code' => $_code));
      }else{
        if(strtotime($data['expire_time'])<time()){
          $this->error('红包已过期！', U('/'));
        }
        $data['subscribe_status'] = $is_subscribe;
        $this->meta_title = '现金红包';
        $this->data = $data;
        $this->meta_share = $data['meta_share'];
        $this->display();
      }
    }else{
      $this->error('请通过手机端微信打开页面！');
    }
  }

  /**
   * 红包打开页面
   */
  public function open($_code = '', $onlyshow = 0){
    if(!(is_mobile() && is_weixin())){
      $this->error('请通过手机端微信打开页面！');
    }
    $event = A('RedPackage', 'Event');
    $data = D('RedPackage')->detail(array('status' => 1, 'code' => $_code));
    if(empty($data)){
      $this->error('红包不存在！', U('/'));
    }
    $openid = session('red_package_openid');
    $data['self_record'] = $event->getRecord($data['id'], $openid);
    //未领取
    if(empty($data['self_record']) && $onlyshow == 0){
      $userinfo = is_subscribe($openid, true);
      if(empty($openid) || !is_array($userinfo)){
        $this->redirect('detail', array('_code' => $_code));
      }
      $get_data = $event->open($data, $userinfo);
      if($get_data['status'] == 0){
        $this->error('红包打开失败！', U('detail', array('_code' => $_code)));
      }else{
        $this->redirect('open', array('_code' => $_code));
      }
    }else{
      $where = array(
        'red_package_id' => $data['id'],
      );
      $lists = $this->lists('RedPackageRecord', $where, 'id desc', true, 20);
      $this->lists = $lists;
      $this->data = $data;
      $this->meta_share = $data['meta_share'];
      $this->meta_title = '红包详情';
      $this->display(IS_AJAX ? 'recordList' : null);
    }
  }

  /**
   * 红包说明
   */
  public function info($_code = ''){
      $this->display();
  }

}
