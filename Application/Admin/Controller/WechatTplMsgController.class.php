<?php
/**
 * 微信模板消息控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

namespace Admin\Controller;

class WechatTplMsgController extends AdminController{
  
  public function _initialize(){
    parent::_initialize();
    //模板消息配置
    $this->tpl_msg = require_once THINK_PATH.'Library/Org/Wechat/TplMsg/TplMsg.config.php';
  }
  
  /**
   * 模板列表
   */
  public function index(){
    
    //记录当前列表页的Cookie
    Cookie('__forward__',$_SERVER['REQUEST_URI']);
    $this->meta_title = '微信模板消息列表';
    $this->tpl_msg = A('WechatTplMsg', 'Event')->formatDataList($this->tpl_msg);
    $this->display();
  }
  
  /**
   * 模板编辑
   */
  public function edit($type = '', $tpl_key = ''){
    if(empty($type) || empty($tpl_key)){
      $this->error('请指定编辑的模板信息！');
    }
    $where = array(
      'type' => $type,
      'tpl_key' => $tpl_key
    );
    $this->data = M('WechatTplMsg')->where($where)->find();
    $this->tpl_data = $this->tpl_msg[$type][$tpl_key];
    $this->meta_title = '编辑微信模板';
    $this->display();
  }
  
  /**
   * 模板信息保存
   */
  public function update(){
    $res = D('WechatTplMsg')->update();
    if($res){
      $this->success('微信模板信息保存成功！', cookie('__forward__'));
    }else{
      $this->error(D('WechatTplMsg')->getError());
    }
  }
}

  