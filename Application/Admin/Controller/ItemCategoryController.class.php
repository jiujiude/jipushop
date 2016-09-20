<?php
/**
 * 商品分类管理控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class ItemCategoryController extends AdminController {

  /**
   * 分类管理列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    $tree = D('ItemCategory')->getTree(0,'id,pid,name,ename,sort,is_display,status');
    $this->assign('tree', $tree);
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    C('_SYS_GET_CATEGORY_TREE_', true); //标记系统获取分类树模板
    $this->meta_title = '商品分类管理';
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
    $Category = D('ItemCategory');

    if(IS_POST){ //提交表单
      $_POST['trait']=','.implode(',',$_POST['trait']).',';
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

      $trait=C('ITEM_CATEGORY_TRAIT');
      $this->assign('trait',      $trait);

      $this->assign('info',       $info);
      $this->assign('category',   $cate);
      $this->meta_title = '编辑商品分类';
      $this->display();
    }
  }

  /* 新增分类 */
  public function add($pid = 0){
    $Category = D('ItemCategory');

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
      $this->meta_title = '新增商品分类';
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
    $child = M('ItemCategory')->where(array('pid'=>$cate_id))->field('id')->select();
    if(!empty($child)){
      $this->error('请先删除该分类下的子分类');
    }

    //判断该分类下有没有商品
    $item_list = M('Item')->where(array('category_id'=>$cate_id))->field('id')->select();
    if(!empty($document_list)){
      $this->error('请先删除该分类下的商品（包含回收站）');
    }

    //删除该分类信息
    $res = M('ItemCategory')->delete($cate_id);
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

    //获取分类
    $map = array('status'=>1, 'id'=>array('neq', $from));
    $list = M('ItemCategory')->where($map)->field('id,name')->select();

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
    $res = M('ItemCategory')->where(array('id'=>$from))->setField('pid', $to);
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
    $Model = M('ItemCategory');

    $cid=array('cid_1','cid_2','cid_3');
    $toArr=$this->cateParents($to);
    $fromArr=$this->cateParents($from);
    for($i=0;$i<count($cid);$i++){
      if($toArr[$i])$toValue[$cid[$i]]=$toArr[$i];
      if($fromArr[$i])$fromWhere[$cid[$i]]=$fromArr[$i];
    }

    //合并分类
    if(M('Item')->where($fromWhere)->find()){
      $res = M('Item')->where($fromWhere)->save($toValue);
    }else{
      $res = true;
    }
    if($res){
      //删除被合并的分类
      $Model->delete($from);
      $this->success('合并分类成功！', U('index'));
    }else{
      $this->error('合并分类失败！');
    }

  }
  private function cateParents($id){
    $pid=M('ItemCategory')->getFieldById($id,'pid');
    if($pid){
      return array_merge($this->cateParents($pid),array($id));
    }else{
      return array($id);
    }
  }
}
