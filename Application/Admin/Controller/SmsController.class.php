<?php
/**
 * 短信平台控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;


class SmsController extends AdminController{
  
  private $event;
  
  public function _initialize(){
    parent::_initialize();
    $this->event = A('Sms', 'Event');
  }

  /**
   * 首页设置
   */
  public function index(){
    $data = $this->event->getUser();
    $this->checkError($data);
    $this->data = $data;
    $this->meta_title = '短信平台';
    $this->display();
  }
  
  /**
   * 修改账户信息
   */
  public function setUser(){
    $data = $this->event->setUser();
    $this->checkError($data);
    $this->success('设置成功！');
  }

  /**
   * 模板列表
   */
  public function listsTpl(){
    $lists = $this->event->listsTpl();
    $this->lists = $lists;
    $this->meta_title = '模板';
    $this->display();
  }
  
  /**
   * 添加模板
   */
  public function updateTpl($tpl_id = 0){
    if(IS_POST){
      $data = $this->event->updateTpl();
      $this->checkError($data);
      $this->success($tpl_id > 0 ? '模板修改成功' : '添加成功！');
    }else{
      $data = array(
        'tpl_id' => $tpl_id,
        'tpl_content' => get_ypsms_content($tpl_id)
      );
      $this->data = $data;
      $this->display();
    }
  }
  
  /**
   * 删除模板
   */
  public function removeTpl($tpl_id = 0){
    $data = $this->event->removeTpl($tpl_id);
    $this->checkError($data);
    $this->success('模板删除成功');
  }
  
  
  /**
   * 清空缓存
   */
  public function clearCache($name = ''){
    if(empty($name)){
      $this->error('请指定缓存名！');
    }
    $name_array = explode(',', $name);
    foreach($name_array as $n){
      S($n, null);
    }
    $this->success('缓存已清除！');
  }
  
  /**
   * 短信发送记录
   */
  public function listsSend($keywords = '', $status = -2){
    $where = array();
    if(!empty($keywords)){
      $where['mobile|content|ip'] = array('LIKE', '%'.$keywords.'%');
    }
    if(in_array($status, array(0, 1))){
      $where['validate_status'] = $status;
    }
    $lists = $this->lists('Sms', $where);
    int_to_string($lists, array('validate_status' => array( 
      0 => '<span class="text-cancel">待验证</span>',
      1 => '<span class="text-success">已验证</span>'
      )));
    $this->lists = $lists;
    $this->meta_title = '短信记录';
    $this->display();
  }

  /**
   * 错误返回处理
   */
  protected function checkError($data = array()){
    if($data['code'] != 0){
      $msg_detail = $data['detail'] ? '<div style="font-size:12px;margin-top:10px;">'.$data['detail'].'</div>' : '';
      $this->error($data['msg'].$msg_detail);
    }
  }

}
