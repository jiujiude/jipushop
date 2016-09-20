<?php
/**
 * 后台邀请控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class InviteController extends AdminController{

  /**
   * 邀请首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //实例化消息模板模型
    $model_invite = M('Invite');

    //定义查询条件
    $where = array();

    //查询关键词
    if(isset($keywords)){
      $where['_string'] = '(invite_uid like "%'.$keywords.'%")  OR (invite_code like "%'.$keywords.'%")';
    }

    //按条件查询结果并分页
    $field = 'invite_uid, count(id) as count, sum(reward_amount) as reward';
    $where['reward_status'] = 1;
    $count = $model_invite->where($where)->field('id')->group('invite_uid')->select();
    $total = count($count);
    $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
    $page = new \Think\Page($total, $listRows, $REQUEST);
    if($total > $listRows){
      $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
    }
    $p = $page->show();
    $this->assign('_page', $p? $p: '');
    $this->assign('_total', $total);
    $options['limit'] = $page->firstRow.','.$page->listRows;
    $model_invite->setProperty('options', $options);
    $list = $model_invite->where($where)->field($field)->order('reward DESC')->group('invite_uid')->select();
    
    //模板输出变量赋值
    $this->assign('_list', $list);
    $this->assign('keywords', $keywords);
    $this->meta_title = '邀请列表';
    $this->display();
  }

  /**
   * 邀请详情
   * @author Max.Yu <max@jipu.com>
   */
  public function view($invite_uid = null){
    $where['invite_uid'] = $invite_uid;
    $list = $this->lists('Invite', $where);
    $this->assign('_list', $list);
    $this->meta_title = '邀请详情';
    $this->display();
  }
}