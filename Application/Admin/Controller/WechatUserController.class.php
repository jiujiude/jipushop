<?php
/**
 * 后台微信用户控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Common\Api\UserApi;
use Org\Wechat\WechatAuth;

class WechatUserController extends AdminController{

  /**
   * 微信粉丝首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    $token = C('WECHAT_TOKEN');
    $appid = C('WECHAT_APPID');
    $secret = C('WECHAT_SECRET');
    if(!$appid || !$secret){
      $this->error('请您先配置微信app_id和secret！');
    }
    // 加载微信SDK
    $wechat = new WechatAuth($appid, $secret, $token);
    $access_token = $wechat->getAccessToken();
    // 获取微信粉丝列表
    $userlist = $wechat->userGet();
    if($userlist['errcode'] == '45009'){
      $data['msg'] = '抱歉，今天的微信API调用次数已超过了限制额度，请明天再试。';
    }
    $wechat_count = D('WechatUser')->count();
    $data['surplus'] = $userlist['total'] - $wechat_count;
    if($data['surplus'] <= 0){
      $data['surplus'] = 0;
    }
    $list = $this->lists('WechatUser', $map, 'subscribe_time DESC');
    int_to_string($list, $map = array(
      'sex' => array(1 => '男', 2 => '女')
    ));
    $this->assign('data', $data);
    $this->assign('list', $list);
    $this->meta_title = '微信粉丝管理';
    $this->display();
  }

  /**
   * 同步微信粉丝
   * @author Max.Yu <max@jipu.com>
   */
  public function getUser(){
    $token = C('WECHAT_TOKEN');
    $appid = C('WECHAT_APPID');
    $secret = C('WECHAT_SECRET');
    if(!$appid || !$secret){
      $this->error('请您先配置微信app_id和secret！');
    }
    // 加载微信SDK
    $wechat = new WechatAuth($appid, $secret, $token);
    $access_token = $wechat->getAccessToken();
    // 获取微信粉丝列表
    $userlist = $wechat->userGet();

    $id = I('request.id');
    // 获取已同步粉丝列表
    $wechat_user = D('WechatUser')->lists();
    $wechat_count = D('WechatUser')->count();

    if($userlist['total'] <= $wechat_count){
      $this->error('没有新粉丝需要同步', U('WechatUser/index'));
    }else{
      $surplus = $userlist['total'] - $wechat_count;
      if(!$id){
        // 获取起始id
        $start = ($wechat_count == 0) ? 0 : $wechat_count + 1;
        $openid = $userlist['data']['openid'][$start];
        $next = $start + 1;
      }else{
        $openid = $userlist['data']['openid'][$id];
        $next = $id + 1;
      }
      $errMsg = '同步失败';
      //检测用户信息是否已存在
      if(!M('WechatUser')->getFieldByOpenid($openid,'id')){
        $userinfo = $wechat->userInfo($openid);
        $userinfo['openid'] && $add = D('WechatUser')->add($userinfo);
      }else{
        $errMsg = '当前用户已存在';
      }
      if(!$userinfo){
        $next = $next + 2;
      }
      $next_url = U('WechatUser/getUser', array('id' => $next));
      if($add){
        $this->success('成功同步一位粉丝，继续同步下一位，剩余'.$surplus.'位', $next_url);
      }else{
        redirect($next_url);
        //$this->error($errMsg, $next_url);
      }
    }
  }
  
  
  /**
  * 去掉重复粉丝
  * @author Justin 2015.5.12 <9801836@qq.com>
  */
  function removeDuplicate(){
    //思路：找出最小id放入临时表，删除id不在临时表里面的数据
    $sql = 'create table `tmp_table` (id int unsigned not null primary key);insert into `tmp_table` (select min(id) from jipu_wechat_user group by openid);delete from jipu_wechat_user where id not in (select * from tmp_table);drop table `tmp_table`;';
    $m = new \Think\Model();
    $m->execute($sql);
    $this->success('去重成功', U('WechatUser/index'));
  }
  
}