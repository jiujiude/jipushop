<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.jipushop.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ezhu <ezhu@jipukeji.com>
// +----------------------------------------------------------------------

namespace Addons\Notice;
use Common\Controller\Addon;

/**
 * 弹窗插件
 * @author ezhu <ezhu@jipukeji.com>
 */

  class NoticeAddon extends Addon{

    public $info = array(
      'name'=>'Notice',
      'title'=>'消息提示',
      'description'=>'用于提示用户信息',
      'status'=>1,
      'author'=>'jipushop',
      'version'=>'0.1'
    );

    public function install(){
      return true;
    }

    public function uninstall(){
      return true;
    }

    /**
     * 提示钩子
     * @param array()
     */
    public function noticeMsg($data){
      $conf = array_merge($this->getConfig(),$data);
      $this->assign('conf', $conf);
      $this->display('content');
    }
  }
