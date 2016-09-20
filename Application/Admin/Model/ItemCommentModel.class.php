<?php
/**
 * 商品评价模型
 * @version 2015051010 
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

class ItemCommentModel extends AdminModel{

  private $type = 1; //1回复2增加评价

  protected $_validate = array(
    array('nickname','_checkNickname','昵称不能为空', self::MUST_VALIDATE, 'callback'),
    array('content', 'require', '评价内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
  );
  
  protected $_auto = array(
    array('create_time', '_getCreateTime', self::MODEL_BOTH, 'callback'),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
    array('uid', '_getUid', self::MODEL_BOTH, 'callback'),
  );
  
  function __construct($name = '', $tablePrefix = '', $connection = ''){
    parent::__construct($name, $tablePrefix, $connection);
    $this->type = I('post.item_id') ? 2 : 1;
  }
  
  function _checkNickname(){
    if((2 == $this->type) && !I('post.nickname')){
      return false;
    }
    return true;
  }
  
  function _getUid(){
    if(1 == $this->type){
      return UID;
    }elseif(2 == $this->type){
      return 0;
    }
  }
  
  function _getCreateTime(){
    return strtotime(I('post.create_time') ? I('post.create_time') : 'now');
  }
  
}
