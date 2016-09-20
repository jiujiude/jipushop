<?php
/**
 * 前台收藏控制器
 * @version 2014100714
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class FavController extends HomeController {
  
  public function _initialize(){
    parent::_initialize();
    //用户登录验证
    parent::login();
    //实例化收藏模型
    $this->Fav = D('Fav');
  }

  /**
   * 加入收藏
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    $id = I('post.id');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    //获取数据
    $data = array(
      'type' => 'item',
      'fid' => $id
    );
    $add = $this->Fav->update($data);
    if($add){
      $this->success('已放入您的收藏夹！');
    }else{
      $this->error('收藏失败了！');
    }
  }

  /**
   * 删除收藏
   * @author Max.Yu <max@jipu.com>
   */
  public function remove(){
    $id = I('post.id');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    //获取数据
    $map = array(
      'type' => 'item',
      'fid' => $id
    );
    $remove = $this->Fav->remove($map);
    if($remove){
      $this->success('移除成功！');
    }else{
      $this->error('移除失败了！');
    }
  }

}
