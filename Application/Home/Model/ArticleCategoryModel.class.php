<?php
/**
 * 类别模型
 * @version 2015061115
 * @author Justin.Wang <justin@jipu.com>
 */

namespace Home\Model;
use Think\Model;

class ArticleCategoryModel extends Model{
  
  /**
  * 根据cid返回文章分类名称
  * @version 2015061115
  * @author Justin <justin@jipu.com>
  */
  function getNameByCid($cid = 0){
    if($cid){
      return M('ArticleCategory')->getFieldById($cid, 'name');
    }
  }
  
}
