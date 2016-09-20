<?php
/**
 * 后台文章控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

class ArticleController extends AdminController{

  private $has_category;
  public function _initialize(){
    parent::_initialize();
    $chk_category = M('ArticleCategory')->count();
    $this->has_category = $chk_category ? 1 : 0;
    $this->assign('has_category', $this->has_category);
  }

  /**
   * 文章首页
   * @author Max.Yu <max@jipu.com>
   */
  public function index($keywords = '', $uid = 0){
    //实例化文章模型
    $ac = D('ArticleCategory');
    $where = array(
      'status' => array('gt', -1)
    );
    //关键词过滤
    $keywords && $where['title|description|content'] = array('LIKE','%'.$keywords.'%');
    //获取列表
    $list = $this->lists('Article', $where, '`id` DESC');
    //记录返回地址
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    //模板输出变量赋值
    $this->assign('list', $list);
    $this->meta_title = '文章列表';
    $this->display();
  }

  /**
   * 文章回收站
   * @author Max.Yu <max@jipu.com>
   */
  public function recyle() {
    $where['status'] = -1;
    $list = $this->lists('Article', $where, 'id desc');
    //记录当前列表页的Cookie
    Cookie('__forward__',$_SERVER['REQUEST_URI']);
    $this->assign('list', $list);
    $this->meta_title = '文章回收站';
    $this->display();
  }

  /**
   * 添加记录
   * @author Max.Yu <max@jipu.com>
   */
  public function add() {
    $this->meta_title = '添加文章';
    $this->display('edit');
  }

  /**
   * 编辑记录
   * @author Max.Yu <max@jipu.com>
   */
  public function edit($id = 0){
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    /*获取一条记录的详细数据*/
    $article_model = D('Article');
    $map['id'] = $id;
    $data = $article_model->detail($map);
    if(!$data){
      $this->error($article_model->getError());
    }
    $this->assign('data', $data);
    $this->meta_title = '编辑文章';
    $this->display();
  }

  /**
   * 更新记录
   * @author Max.Yu <max@jipu.com>
   */
  public function update(){
    //只保存最后一级分类
    foreach(I('post.category_id') as $v){
      if($v){
        $_POST['category_id'] = $v;
      }
    }
    $res = D('Article')->update();
    if(!$res){
      $this->error(D('Article')->getError());
    }else{
      $this->success($res['id'] ? '更新成功' : '新增成功', Cookie('__forward__'));
    }
  }

  /**
   * 删除记录
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');
    if(empty($ids)){
      $this->error('请选择要删除的数据!');
    }
    $map['id'] = array('in', $ids);
    if(M('Article')->where($map)->delete()){
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }

  /**
   * 更新单个字段
   */
  public function updateField($id){
    $field = I('post.field', '');
    $value = I('post.value', '');
    if($field){
      $res = M('Article')->where('id='.$id)->setField($field, $value);
      if($res){
        $this->success('修改成功');
      }else{
        $this->error('修改失败');
      }
    }else{
      $this->error('参数错误');
    }
  }
}