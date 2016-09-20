<?php
/**
 * 前台文章控制器
 * @version 2015052015
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

class ArticleController extends HomeController{

  /**
   * 文章列表
   * @author Max.Yu <max@jipu.com>
   */
  public function lists(){
    if(I('get.cid')){
      $this->category_name = D('ArticleCategory')->getNameByCid(I('get.cid'));
      $where['cid_1'] = I('get.cid');
    }
    $this->category_name ? $this->meta_title = $this->category_name : $this->meta_title = '文章列表';
    //设置每页读取记录条数
    C('LIST_ROWS', 12);
    $where['status'] = 1;
    $lists = parent::lists('Article', $where);
    if($lists){
      foreach($lists as $key => &$value){
        $value['images_src'] = get_cover($value['images'], 'path');
      }
    }
    $this->lists = $lists;
    IS_AJAX ? $this->display('articleList') : $this->display();
  }
  
  /**
   * 文章详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($id = 0){
    $article_model = D('Article');
    $where['id'] = $id;
    $data = $article_model->detail($where);
    !$data && $this->error('对不起，您访问的文章不存在！');
    
    //跳转
    if($data['cid'] == C('ARTICLE_JUMP_CID')){
      redirect($data['to_url']);
    }
    
    $data['category_name'] = D('ArticleCategory')->getNameByCid($data['cid_1']);

    $category= D('ArticleCategory')->where(array('pid'=>3))->select();
    foreach($category as $k=>$v){
      $category[$k]['article']=$article_model->where(array('cid'=>$v['id']))->select();
    }

    $data['images_src'] = get_cover($data['images'], 'path');
    //阅读次数
    $article_model->where(array('id' => $id))->setInc('view');
    $active_css[$id] = 'class="active"';
    $share = array(
      'title' => $data['title'],
      'desc' => $data['description'],
      'img_url' => SITE_URL.$data['images_src'],
      'link' => SITE_URL.U('Article/detail', array('id' => $data['id']))
    );
    $this->meta_share = $share;
    $this->data = $data;
    $this->active_css = $active_css;
    $this->category=$category;
    $this->display();
  }
  
}
