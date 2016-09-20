<?php
/**
 * 后台广告控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class AdvertiseController extends AdminController{

  public function index($tid = 0){
    $this->setData();
    $where = array();
    $tid > 0 && $where['tid'] = $tid;
    $lists = $this->lists(D('Advertise'), $where,'`status` desc, `tid` desc, `sort` asc');
    $type = C('ADVERTISE_TYPE');
    if($lists){
      foreach($lists as $key => &$value) {
        $value['tid_text'] = $type[$value['tid']];
        $value['src'] = get_cover($value['image'], 'path');
      }
    }
    //记录当前列表页的cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    $this->assign('lists', $lists);
    $this->meta_title = '广告管理';
    $this->display();
  }

  /**
   * 添加广告
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    //获取广告规格约定配置
    $this->assign('size_conversion', C('UPLOAD_PIC_SIZE_CONVRNTION.AD_PIC'));

    $this->setData();
    $this->meta_title = '新增广告';
    $this->display();
  }

  /**
   * 修改广告
   * @author Max.Yu <max@jipu.com>
   */
  public function edit(){
    $this->setData();
    $id = I('get.id', '');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    /*获取一条记录的详细数据*/
    $data = D('Advertise')->detail(array('id' => $id));
    if(!$data){
      $this->error('广告不存在！');
    }

    /*根据广告类型获取专场类型*/
    $map['type'] = $data['type'];
    $data['activity'] = D('Activity')->where($map)->select();

    //获取广告规格约定配置
    $this->assign('size_conversion', C('UPLOAD_PIC_SIZE_CONVRNTION.AD_PIC'));

    $this->meta_title = '修改广告';
    $this->assign('data', $data);
    $this->display();
  }

  /**
   * 删除广告
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    /*参数过滤*/
    $ids = I('param.ids');
    if(empty($ids)){
      $this->error('请选择要删除的广告');
    }
    $res = D('Advertise')->remove($ids);
    if($res !== false){
      $this->success('广告删除成功！');
    }else{
      $this->error('广告删除失败！');
    }
  }

  public function getActivityLink($type){
    $map['type'] = $type;
    $activity = D('Activity')->where($map)->select();
    if($activity){
      foreach($activity as $key => $value){
        $result[$value['id']]['name'] = $value['name'];
        $result[$value['id']]['link'] = ($value['type'] == 1) ? SITE_URL.U('Mobile/Activity/detail', array('id' => $value['id'])) : SITE_URL.U('Home/Activity/detail', array('id' => $value['id']));
      }
    }
    $this->ajaxReturn($result);
  }

  /**
   * 获取专场列表和广告分类
   * @author Max.Yu <max@jipu.com>
   */
  private function setData(){
    $type = C('ADVERTISE_TYPE');
    $upload_info = C('UPLOAD_PIC_SIZE_CONVRNTION.AD_PIC');
    $activity = $this->lists(M('Activity'), '', '`id` DESC', null, 'id, name, alais');
    if($type){
      foreach($type as $key => &$value){
        $type_list[$key]['tid'] = $key;
        $type_list[$key]['name'] = $value;
        $type_list[$key]['info'] = $upload_info[$key];
      }
    }
    $this->assign('type_list', $type_list);
    $this->assign('activity', $activity);
  }

  /**
   * 删除广告图片以及缩略图
   * @author Max.Yu <max@jipu.com>
   */
  public function delPic($pid = null){
    $result = array('status'=>1, 'info'=>'图片删除成功！');

    //验证参数的合法性
    if(empty($pid)){
      $result = array('status'=>0, 'info'=>'参数不能为空！');
      $this->ajaxReturn($result, 'JSON');
    }else{
      if(!is_numeric($pid)){
        $result = array('status'=>0, 'info'=>'参数错误！');
        $this->ajaxReturn($result, 'JSON');
      }
    }

    //实例化图片模型
    $picture_model = D('Picture');

    //获取广告图片缩略图规格配置
    $thumb_size = C('UPLOAD_PIC_THUMB_SIZE.AD_PIC');

    if($picture_model->delById($pid, $thumb_size)){
      $this->ajaxReturn($result, 'JSON');
    }else{
      $result = array('status'=>0, 'info'=>'图片删除失败！');
      $this->ajaxReturn($result, 'JSON');
    }
  }

}