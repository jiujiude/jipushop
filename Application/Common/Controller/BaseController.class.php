<?php
/**
 * 项目公用控制器
 * @version 20150521
 * @author Justin <justin@jipu.com>
 */

namespace Common\Controller;

use Think\Controller;

class BaseController extends Controller{
  
  protected function _initialize(){
    header("Content-Type: text/html; charset=UTF-8");
    
    //阻止可能被盗用的cookie（不能被js获取，但是依旧会带着请求）
    ini_set("session.cookie_httponly", 1); 
    
  }

  /**
   * 通用分页列表数据集获取方法
   *
   *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
   *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
   *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
   *
   * @param sting|Model  $model   模型名或模型实例
   * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
   * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
   *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
   *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
   *
   * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
   * @param array $limit 每页显示数
   * @param array $base 基本的查询条件
   * @author 朱亚杰 <xcoolcc@gmail.com>
   * @version 2015070110 Justin 增加默认model值
   * @return array|false
   * 返回数据集
   */
  function lists($model = CONTROLLER_NAME, $where = array(), $order = '', $field = true, $limit = 10, $base = array('status' => array('egt', -1))){
    return A('Home/Page', 'Event')->lists($model, $where, $order, $limit, $base, $field);
  }
  
}