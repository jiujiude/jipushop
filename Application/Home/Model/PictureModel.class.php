<?php
/**
 * 图片模型
 * 负责图片的上传
 * @version 2014102014
 * @author Justin <justin@jipu.com>
 */

namespace Home\Model;

use Think\Model;
use Think\Upload;

class PictureModel extends Model{

  /**
   * 自动完成
   * @var array
   */
  protected $_auto = array(
  array('status', 1, self::MODEL_INSERT),
  array('create_time', NOW_TIME, self::MODEL_INSERT),
  );

  /**
   * 文件上传
   * @param array $files 要上传的文件列表（通常是$_FILES数组）
   * @param array $setting 文件上传配置
   * @param string $driver 上传驱动名称
   * @param array  $config 上传驱动配置
   * @return array 文件上传成功后的信息
   */
  public function upload($files, $setting, $driver = 'Local', $config = null){
    //上传文件
    //根据MD5和HASH码检测当前上传的文件是否已存在
    $setting['callback'] = array($this, 'isFile');
    //清除数据库存在但本地不存在的数据
    $setting['removeTrash'] = array($this, 'removeTrash');
    $Upload = new Upload($setting, $driver, $config);
    $info = $Upload->upload($files);

    if($info){ //文件上传成功，记录文件信息
      foreach ($info as $key => &$value){
        //已经存在文件记录
        if(isset($value['id']) && is_numeric($value['id'])){
          continue;
        }

        //记录文件信息
        $value['path'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename'];	//在模板里的url路径
        if($this->create($value) && ($id = $this->add())){
          $value['id'] = $id;
        } else {
          //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
          unset($info[$key]);
        }
      }
      return $info; //文件上传成功
    } else {
      $this->error = $Upload->getError();
      return false;
    }
  }

  /**
   * 下载指定文件
   * @param number $root 文件存储根目录
   * @param integer $id 文件ID
   * @param string $args 回调函数参数
   * @return boolean false-下载失败，否则输出下载文件
   */
  public function download($root, $id, $callback = null, $args = null){
    //获取下载文件信息
    $file = $this->find($id);
    if(!$file){
      $this->error = '不存在该文件！';
      return false;
    }

    //下载文件
    switch ($file['location']){
      case 0: //下载本地文件
        $file['rootpath'] = $root;
        return $this->downLocalFile($file, $callback, $args);
      case 1: //TODO: 下载远程FTP文件
        break;
      default:
        $this->error = '不支持的文件存储类型！';
        return false;
    }
  }

  /**
   * 检测当前上传的文件是否已经存在
   * @param array $file 文件上传数组
   * @return boolean 文件信息， false - 不存在该文件
   */
  public function isFile($file){
    if(empty($file['md5'])){
      throw new \Exception('缺少参数:md5');
    }
    //查找文件
    $map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
    return $this->field(true)->where($map)->find();
  }

  /**
   * 下载本地文件
   * @param array $file 文件信息数组
   * @param callable $callback 下载回调函数，一般用于增加下载次数
   * @param string $args 回调函数参数
   * @return boolean 下载失败返回false
   */
  private function downLocalFile($file, $callback = null, $args = null){
    if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){
      //调用回调函数新增下载数
      is_callable($callback) && call_user_func($callback, $args);

      //执行下载 //TODO: 大文件断点续传
      header("Content-Description: File Transfer");
      header('Content-type: ' . $file['type']);
      header('Content-Length:' . $file['size']);
      if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])){ //for IE
        header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
      } else {
        header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
      }
      readfile($file['rootpath'].$file['savepath'].$file['savename']);
      exit;
    } else {
      $this->error = '文件已被删除！';
      return false;
    }
  }

  /**
   * 清除数据库存在但本地不存在的数据
   * @param $data
   */
  public function removeTrash($data){
    $this->where(array('id'=>$data['id'],))->delete();
  }

  /**
   * 获取图片路径
   * @param $id
   */
  public function getPathById($id){
    $path = $this->where(array('id'=>$id,))->getField('path');

    if($path){
      return $path;
    }else{
      return false;
    }
  }

  /**
   * 获取图片路径数组
   * @param $ids array
   */
  public function getPathByIds($ids){
    $where['id'] = array('IN', $ids);
    $path = $this->where($where)->getField('path', true);

    if($path){
      return $path;
    }else{
      return false;
    }
  }

  /**
   * 根据图片ID删除图片原图以及缩略图
   * @param $id int 图片ID
   * @param $thumb_size array 缩略图尺寸规格
   */
  public function delById($id, $thumb_size){
    $path = null;  //图片路径

    if($id && is_numeric($id)){
      //获取图片路径
      $path = $this->getPathById($id);

      //删除图片数据
      $result = $this->where(array('id'=>$id,))->delete();

      //删除图片文件
      if($result && $path){
        $this->delByPath($path, $thumb_size);
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

  /**
   * 根据图片ID字符串数组删除图片原图以及缩略图
   * @param $ids array  图片ID字符串数组
   * @param $thumb_size  array 缩略图尺寸规格
   */
  public function delByIds($ids, $thumb_size){
    $paths = null;  //图片路径数组

    if($ids){
      //获取图片路径
      $paths = $this->getPathByIds($ids);

      //删除图片数据
      $where['id'] = array('IN', $ids);
      $result = $this->where($where)->delete();

      //删除图片文件
      if($result && $paths){
        foreach ($paths as $path){
          $this->delByPath($path, $thumb_size);
        }
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

  /**
   * 根据图片路径删除图片原图以及缩略图
   * @param $path string  图片路径
   * @param $thumb_size  array 缩略图尺寸规格
   * @return boolean
   */
  public function delByPath($path, $thumb_size){
    if(empty($path)){
      return false;
    }

    if(substr($path,0,1)=='/'){//去掉路径开头的“/”，否则删除会失败
      $path = substr($path,1,strlen($path));
    }

    if(is_file($path)){
      if(unlink($path)){
        //删除缩略图
        if($thumb_size && is_array($thumb_size)){
          foreach ($thumb_size as $size){
            $thumb_path = str_replace('.', '_'.$size['WIDTH'].'x'.$size['HEIGHT'].'.', $path);
            unlink($thumb_path);
          }
        }
      }else{
        //删除失败
        return false;
      }
    }else{
      //图片不存在
      return false;
    }
  }

}
