<?php
/**
 * 商品分类Widget
 * 用于调用商品分类
 * @version 2014122411
 * @author Max.Yu <max@jipu.com>
 */
 
namespace Home\Widget;

use Home\Model\ItemCategoryModel;

class ItemCategoryWidget extends BaseWidget{

  /**
   * 调用分类树导航菜单
   * @author Max.Yu <max@jipu.com>
   */
  public function category($params = array()){
    //实例化数据模型
    $item_category_model = D('ItemCategory');

    $where = $params['where']?$params['where']:array();
    $field = $params['fields']?$params['fields']:true;
    $order = $params['order']?$params['order']:'`sort` ASC, `id` DESC';
    $limit = $params['limit']?$params['limit']:10;
    //获取商品分类数据
    $list = $item_category_model->lists($where,$field,$order,$limit);
    
    //模板输出变量赋值
    $this->list = $list;
    if($params['display']){
      $this->display($params['display']);
    }else{
      $this->display('Widget/itemCategory');
    }
  }
}