<?php
/**
 * 后台首页控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class IndexController extends AdminController {

  /**
   * 后台首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    
    $this->meta_title = '管理首页';
    $this->display();
  }

  /**
   * 清空系统缓存
   * @author Max.Yu <max@jipu.com>
   * @version 2015070110 Justin 增加清理字段缓存
   */
  public function cleancache(){
    
    $cahce_dirs = RUNTIME_PATH;
    //模板缓存
    $this->rmdirr($cahce_dirs);
    
    //字段缓存
    $_fields_dirs = DATA_PATH.'_fields/';
    $this->rmdirr($_fields_dirs);
    @mkdir($_fields_dirs, 0777, true);
    
    $this->meta_title = '缓存清理';
    $this->display();
  }
/**
   * 清空系统缓存
   * @author sean
   * 
   */
  public function getcleancache(){
     
    $cahce_dirs = RUNTIME_PATH;
    //模板缓存
    $this->rmdirr($cahce_dirs);
    
    //字段缓存
    $_fields_dirs = DATA_PATH.'_fields/';
    $this->rmdirr($_fields_dirs);
    @mkdir($_fields_dirs, 0777, true);
  }
  /**
   * 清空系统缓存文件
   * @author Max.Yu <max@jipu.com>
   */
  private function rmdirr($dirname){
    if(!file_exists($dirname)){
      return false;
    }
    if(is_file($dirname) || is_link($dirname)){
      return unlink($dirname);
    }
    $dir = dir($dirname);
    if($dir){
      while(false !== $entry = $dir->read()){
        if($entry == '.' || $entry == '..' || substr($entry, 0, 9) == 'no-clear:'){
          continue;
        }
        $this->rmdirr($dirname.DIRECTORY_SEPARATOR.$entry);
      }
    }
    $dir->close();
    return true;
  }

}
