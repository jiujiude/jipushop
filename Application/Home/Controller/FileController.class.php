<?php
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 * @version 2014091618
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class FileController extends HomeController {

  /**
   * 文件上传
   * @author Max.Yu <max@jipu.com>
   */
  public function upload(){
    $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
    /* 调用文件上传组件上传文件 */
    $File = D('File');
    $file_driver = C('DOWNLOAD_UPLOAD_DRIVER');
    $info = $File->upload(
      $_FILES,
      C('DOWNLOAD_UPLOAD'),
      C('DOWNLOAD_UPLOAD_DRIVER'),
      C("UPLOAD_{$file_driver}_CONFIG")
    );

    //记录附件信息
    if($info){
      $return['data'] = think_encrypt(json_encode($info['download']));
    } else {
      $return['status'] = 0;
      $return['info']   = $File->getError();
    }

    //返回JSON数据
    $this->ajaxReturn($return);
  }

  /**
   * 文件下载
   * @author Max.Yu <max@jipu.com>
   */
  public function download($id = null){
    if(empty($id) || !is_numeric($id)){
      $this->error('参数错误！');
    }

    $logic = D('Download', 'Logic');
    if(!$logic->download($id)){
      $this->error($logic->getError());
    }
  }
  
  /**
   * 上传图片
   * @author Max.Yu <max@jipu.com>
   */
  public function uploadPicture(){
    //TODO: 用户登录检测
    //返回标准数据
    $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');

    //调用文件上传组件上传文件
    $Picture = D('Picture');
    $info = $Picture->upload($_FILES, C('PICTURE_UPLOAD'));

    //记录图片信息
    if($info){
      $return['status'] = 1;
      $return = array_merge($info['download'], $return);
    } else {
      $return['status'] = 0;
      $return['info']   = $Picture->getError();
    }

    //返回JSON数据
    $this->ajaxReturn($return);
  }
}
