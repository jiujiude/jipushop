<?php
/**
 * 后台图片控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Admin\Model\AuthGroupModel;
use Admin\Model\PictureModel;
use Think\Page;

class PictureController extends AdminController {

  /**
   * 按图片ID删除图片
   * @author Max.Yu <max@jipu.com>
   */
  public function delPic($pid = null){
    $result = array('status'=>1, 'info'=>'图片删除成功！');

    // 验证参数的合法性
    if(empty($pid)){
      $result = array('status'=>0, 'info'=>'参数不能为空！');
      $this->ajaxReturn($result, 'JSON');
    }else{
      if(!is_numeric($pid)){
        $result = array('status'=>0, 'info'=>'参数错误！');
        $this->ajaxReturn($result, 'JSON');
      }
    }

    // 实例化图片模型
    $picture_model = D('Picture');

    if($picture_model->delById($pid)){
      $this->ajaxReturn($result, 'JSON');
    }else{
      $result = array('status'=>0, 'info'=>'图片删除失败！');
      $this->ajaxReturn($result, 'JSON');
    }
  }

}