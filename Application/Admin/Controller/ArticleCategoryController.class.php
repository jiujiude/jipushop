<?php
/**
 * 文章分类管理控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class ArticleCategoryController extends AdminController {

  /**
   * 分类管理列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    $tree = D('ArticleCategory')->getTree(0,'id, pid, name, ename, sort, is_display, status');
    $this->assign('tree', $tree);
    C('_SYS_GET_CATEGORY_TREE_', true); //标记系统获取分类树模板
    $this->meta_title = '分类管理';
    $this->display();
  }

  /**
   * 显示分类树，仅支持内部调
   * @param  array $tree 分类树
   * @author Max.Yu <max@jipu.com>
   */
  public function tree($tree = null){
    C('_SYS_GET_CATEGORY_TREE_') || $this->_empty();
    $this->assign('tree', $tree);
    $this->display('tree');
  }

  /* 编辑分类 */
  public function edit($id = null, $pid = 0){
    $Category = D('ArticleCategory');

    if(IS_POST){ //提交表单
      if(false !== $Category->update()){
        $this->success('编辑成功！', U('index'));
      }else{
        $error = $Category->getError();
        $this->error(empty($error) ? '未知错误！' : $error);
      }
    }else{
      $cate = '';
      if($pid){
        /* 获取上级分类信息 */
        $cate = $Category->info($pid, 'id,name,ename,status');
        if(!($cate && 1 == $cate['status'])){
          $this->error('指定的上级分类不存在或被禁用！');
        }
      }

      /* 获取分类信息 */
      $info = $id ? $Category->info($id) : '';

      $this->assign('info',       $info);
      $this->assign('category',   $cate);
      $this->meta_title = '编辑分类';
      $this->display();
    }
  }

  /* 新增分类 */
  public function add($pid = 0){
    
    $Category = D('ArticleCategory');

    if(IS_POST){ //提交表单
      if(false !== $Category->update()){
        $this->success('新增成功！', U('index'));
      }else{
        $error = $Category->getError();
        $this->error(empty($error) ? '未知错误！' : $error);
      }
    }else{
      $cate = array();
      if($pid){
        /* 获取上级分类信息 */
        $cate = $Category->info($pid, 'id,name,ename,status');
        if(!($cate && 1 == $cate['status'])){
          $this->error('指定的上级分类不存在或被禁用！');
        }
      }

      /* 获取分类信息 */
      $this->assign('category', $cate);
      $this->meta_title = '新增分类';
      $this->display('edit');
    }
  }

  /**
   * 删除一个分类
   * @author Max.Yu <max@jipu.com>
   */
  public function remove(){
    $cate_id = I('id');
    if(empty($cate_id)){
      $this->error('参数错误!');
    }

    //判断该分类下有没有子分类，有则不允许删除
    $child = M('ArticleCategory')->where(array('pid'=>$cate_id))->field('id')->select();
    if(!empty($child)){
      $this->error('请先删除该分类下的子分类');
    }

    //判断该分类下有没有文章
    $item_list = M('Article')->where(array('category_id'=>$cate_id))->field('id')->select();
    if(!empty($document_list)){
      $this->error('请先删除该分类下的文章（包含回收站）');
    }

    //删除该分类信息
    $res = M('ArticleCategory')->delete($cate_id);
    if($res !== false){
      //记录行为
      action_log('update_category', 'category', $cate_id, UID);
      $this->success('删除分类成功！');
    }else{
      $this->error('删除分类失败！');
    }
  }

  /**
   * 操作分类初始化
   * @param string $type
   * @author Max.Yu <max@jipu.com>
   */
  public function operate($type = 'move'){
    //检查操作参数
    if(strcmp($type, 'move') == 0){
      $operate = '移动';
    }elseif(strcmp($type, 'merge') == 0){
      $operate = '合并';
    }else{
      $this->error('参数错误！');
    }
    $from = intval(I('get.from'));
    empty($from) && $this->error('参数错误！');
    $info = D('ArticleCategory')->info($from);
    //获取分类
    $map = array('status'=>1, 'id'=>array('neq', $from));
    $list = M('ArticleCategory')->where($map)->field('id,name')->select();

    $this->assign('type', $type);
    $this->assign('operate', $operate);
    $this->assign('from', $from);
    $this->assign('list', $list);
    $this->meta_title = $operate.'分类';
    $this->display();
  }

  /**
   * 移动分类
   * @author Max.Yu <max@jipu.com>
   */
  public function move(){
    $to = I('post.to');
    $from = I('post.from');
    $res = M('ArticleCategory')->where(array('id'=>$from))->setField('pid', $to);
    if($res !== false){
      $this->success('分类移动成功！', U('index'));
    }else{
      $this->error('分类移动失败！');
    }
  }

  /**
   * 合并分类
   * @author Max.Yu <max@jipu.com>
   */
  public function merge(){
    $to = I('post.to');
    $from = I('post.from');
    $Model = M('ArticleCategory');

    //合并分类
    $res = M('Article')->where(array('category_id'=>$from))->setField('category_id', $to);

    if($res){
      //删除被合并的分类
      $Model->delete($from);
      $this->success('合并分类成功！', U('index'));
    }else{
      $this->error('合并分类失败！');
    }

  }
}