<?php
/**
 * 前台专题控制器
 * @version 2014092214
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class ActivityController extends HomeController {

  /**
   * 专题首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index($display = null) {
    //获取页面传递参数
    $tid = I('tid');

    //实例化数据模型
    $activity_model = M('Activity');

    //获取专题数据
    $where = array(
      'status' => 1,
    );
    if(!empty($tid) && is_numeric($tid)){
      $where['tid'] = $tid;
      $data['active'][$tid] = 'active';
    }
    $order = array(
      'create_time' => 'DESC',
    );
    $limit = 20;
    $field = true;
    $data['list'] = $this->lists($activity_model, $where, $order, $field, $limit, null);

    //记录当前页URL地址Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->assign('data', $data);
    $this->meta_title = '优惠活动专题';
    if($display){
      $this->display();
    }else{
      $this->display($display);
    }
  }
  
  /**
   * 专题详情页
   * @author Max.Yu <max@jipu.com>
   */
  public function detail(){
    //获取页面传递参数
    $id = I('get.id');

    //验证参数的合法性
    if(empty($id)){
      $this->error('参数不能为空！');
    }else{
      if(!is_numeric($id)){
        $this->error('参数错误！');
      }
    }
    //拼团产品id
    $joinIds = A('Join','Event')->getItemIds();

    //实例化数据模型
    $activity_model = D('Activity');

    //获取专题数据以及专题绑定的商品数据
    $data = $activity_model->detail($id);
    $theme = $this->themeTpl($data['theme']);

    //记录当前页URL地址Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->data = $data;
    $this->id = $id;
    $this->assign('theme',$theme);
    $this->meta_title = $data['name'];
    $this->joinId = $joinIds;
    if(is_mobile()){
        $tpl = ($data['theme'] != 'default') ? $data['theme'] : 'detail';
        $this->display($tpl);
    }else{
        $this->display('detail');
    }
  }
  
  
  /**
   * 获取专题模板
   * @param unknown $theme
   * @return string
   */
  public function themeTpl($theme){
      $tpl = ($theme != 'default') ? 'Activity/'.$theme : 'Activity/itemList';
      return $tpl;
  }
}
