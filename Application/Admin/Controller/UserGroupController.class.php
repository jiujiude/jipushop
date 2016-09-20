<?php
/**
 * 会员等级控制器
 * @version 2015061618
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Controller;

class UserGroupController extends AdminController{
  
  /**
   * 成员授权列表
   * @version 2015061710
   * @author Justin <justin@jipu.com>
   */
  function user($group_id = 0){
    $where = array(
        'status' => 1,
        'group_id' => $group_id,
      );
    $this->lists = int_to_string($this->lists('User', $where));
    //p($this->lists);
    //获取会员等级
    $this->lists_user_group = D('UserGroup')->getUserGroup();
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->meta_title = '成员授权';
    $this->display();
  }
  
  /**
   * 解除授权
   * @version 2015061710
   * @author Justin <justin@jipu.com>
   */
  function removeFromGroup(){
    I('get.id') && M('User')->where(array('id' => I('get.id')))->setField('group_id', 0);
    $this->success('解除授权成功！', Cookie('__forward__'));
  }
  
  /**
   * 增加成员授权 
   * @version 2015061710
   * @author Justin <justin@jipu.com>
   */
  function addToGroup(){
    $uids = I('post.uids');
    !$uids && $this->error('请选择用户！');
    $where['id'] = array('in', $uids);
    M('User')->where($where)->setField('group_id', I('post.group_id'));
    $this->success('增加授权成功！', Cookie('__forward__'));
  }

}
