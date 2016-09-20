<?php
/**
* 提现控制器
* @author Justin
*/

namespace Admin\Controller;

class WithdrawController extends AdminController{
  
  function index($type = null){
    if(I('get.keywords')){
      $where['name|account'] = array('like', '%'.I('get.keywords').'%');
    }
    if(I('get.status')){
      $where['status'] = I('get.status');
    }
    if(I('get.uid')){
      $where['uid'] = I('get.uid');
    }
    
    $this->status = get_withdraw_text();
    
    $this->lists = $this->lists('Common/WithdrawView', $where);
    //导出
    ('export' == $type) && $this->export($this->lists);
    
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->meta_title = $this->meta[CONTROLLER_NAME].'列表';
    $this->display();
  }
  
  function _before_edit(){
    $this->status = get_withdraw_text();
  }
  
  /**
  * 状态改变时间
  * @author Justin
  */
  function _before_update(){
    $status = I('post.status');
    switch($status){
      case '101':
        $_POST['admin_refuse_time'] = NOW_TIME;
        break;
      case '200':
        $_POST['bank_time'] = NOW_TIME;
        break;
      case '201':
        $_POST['bank_refuse_time'] = NOW_TIME;
        break;
      case '300':
        $_POST['success_time'] = NOW_TIME;
        break;
    }
  }
  
  /**
  * 导出excel
  * @author Justin
  */
  function export($lists){
    //文件名
    $filename = "提现汇总表";
    //表头
    $headArr = array('提现用户', '提现账户类型', '提现人名字', '提现金额', '提现人账户', '申请时间', '状态');
    foreach($lists as $k=>$v){
      $data[] = array(
        'uid' => get_username($v['uid']),
        'type' => $v['type'] == 'alipay' ? get_user_account_text($v['type']) : C('BANK_LISTS.'.$v['bankname']), 
        'name' => $v['name'], 
        'amount' => $v['amount'], 
        'account' => ' '.$v['account'], 
        'create_time' => time_format($v['create_time']), 
        'status' => strip_tags(get_withdraw_text($v['status'])), 
      );
    }
    //调用Excel文件生成并导出函数
    createExcel($filename, $headArr, $data);
  }
  
}

