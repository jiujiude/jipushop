<?php
/**
 * 红包控制器
 * @version 20102011513
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class RedPackageController extends AdminController{

  /**
   * 发红包
   */
  public function add(){
    $this->meta_title = '发红包';
    $data = array(
      'expire_time' => date('Y-m-d H:i:s', time() + 86400 * 2),
      'info' => '恭喜发财，大吉大利！'
    );
    $this->data = $data;
    $this->display('edit');
  }
  
  
  /**
   * 红包二维码展示
   * @author Max.Yu <max@jipu.com>
   */
  public function qrcode($id = 0, $code = ''){
    // 二维码图片路径
    $data['qrdata'] = SITE_URL.U('/RedPackage/detail?_code='.$code);
    $this->data = $data;
    $this->display();
  }
  
  /**
   * 红包二维码展示
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($id = 0){
    $data = M('RedPackage')->find($id);
    $this->data = $data;
    $this->meta_title = '红包详情';
    //领取记录
    $lists = $this->lists('RedPackageRecord', array('red_package_id' => $data['id']), 'id desc', true, 20);
    $this->lists = $lists;
    $this->setListOrder();
    $this->display();
  }

}
