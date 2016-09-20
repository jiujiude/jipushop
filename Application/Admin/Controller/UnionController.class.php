<?php
/**
 * 推广联盟控制器
 * @version 2015091511
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Controller;

class UnionController extends AdminController{
  
  function index($where = null){
    $type = I('get.type');
    $type && ($where['type'] = $type);
    parent::index($where);
  }
  
  function _before_index_display(& $users){
    $d = M('Distribution') ;
    $psd = 0;
    foreach($users as $k=> $v){
      
      $users[$k]['one']   = $d->where( array('oneagents' => $v['uid']))->count();
      $users[$k]['two']   = $d->where( array('twoagents' => $v['uid']))->count();
      $users[$k]['three'] = $d->where( array('threeagents' => $v['uid']))->count();
      $all = $d->where('oneagents ='.$v['uid'].' or twoagents ='.$v['uid'].' or threeagents ='.$v['uid'])->getField('user_id' ,true);
      $users[$k]['money']  = M('Finance')->where( "type='union_order' and uid = '".$v['uid']."'")->sum('amount');
      $users[$k]['smoney']  = M('Finance')->where( "type='union_order' and status=0 and uid = '".$v['uid']."'")->sum('amount');
      if(!empty($all)){
        $where['uid'] = array('in' ,$all) ;
        $users[$k]['orders'] = M('Distributlog')->where($where)->count('id');
      }
      
    }
    return $users;
  }
  
  /**
   * 预览二维码页面
   */
  public function detail($qrcode_url){
    $this->qrcode_url = $qrcode_url;
    $this->display();
    return;
    
//    $this->qrcode_id = $qrcode_id;
//    if($qrcode_id){
//      $data_url = M('Union')->getFieldById($qrcode_id, 'qrcode_url');
//      if(I('show') == 'qrcode'){
//        vendor('Qrcode.Phpqrcode');
//        $QRcode = new \Vendor\QRcode();
//        $QRcode->png($data_url, false, 'D', 5, 1);
//      }else{
//        $this->data_url = $data_url;
//        $this->display();
//      }
//    }else{
//      $this->error('参数错误');
//    }
    
  }
  public function config(){
    if(IS_POST){
      unset($_POST['formhash']);
      if( (!empty($_POST['DIS_CONDITION']['REQUEST3']) && (!is_numeric($_POST['DIS_CONDITION']['REQUEST3']) ) ) || (!empty($_POST['DIS_CONDITION']['REQUEST4']) && (!is_numeric($_POST['DIS_CONDITION']['REQUEST4']) ) ) ){
        $this->error('请使用数字');
      }
      $Config = M('Distributconfig');
      foreach($_POST as $k => $v){
        unset($where) ;unset($data);
        if(is_array($v)){
          foreach($v as $kk => $vv){
            $where['name'] = $k ;
            $where['lname']= $kk;
            $data['value'] = $vv;
            $Config->where($where)->save($data);
          }
        }else{
          $where['name'] = $k ;
          $data['value'] = $v;
          $Config->where($where)->save($data);
        }
      }
      R('Index/getcleancache');
      FF('config.cash' ,$_POST,CONF_PATH) ? $this->success('保存成功'): $this->error('保存失败');
    } 
    $info = M('Distributconfig')->select();
    foreach($info as $k => $v){
      if(!empty($v['lname'])){
        $result[$v['name']][$v['lname']] = $v['value'] ;
      }else{
        $result[$v['name']] = $v['value'];
      }
    }
    $this->assign('data' ,$result );
    $this->display();
  }
  /**
   * 推广用户列表
   * @return [type] [description]
   */
  public function userlist(){ 
    if(IS_POST){
      $name = I('keywords');
      $name && ($where['link_name|link_mobile'] = array('like' , "%$name%"));
    }
    parent::index($where);
  }

  /**
   * 代理列表
   */
  function agents(){
    $type = I('type', 1 ,'int');
    $uid  = I('uid');
    if(!empty($type) && !empty($uid)){
      switch ($type) {
        case 2:
          $field = 'a.twoagents=' ;
          break;
        case 3:
          $field = 'a.threeagents=' ;
          break;
        default:
          $field = 'a.oneagents=' ;
          break;
      }
      $data = M('Distribution')->alias('a')->join(' LEFT JOIN __USER__ b on a.user_id=b.id')->where($field.$uid)->field('a.user_id,b.username,b.mobile')->select();

      $this->data = $data ;
      $this->display();
    }else{
      $this->error('错误~错误');
    } 
    
  }

}

