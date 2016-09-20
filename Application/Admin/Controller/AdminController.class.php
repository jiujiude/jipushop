<?php
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

namespace Admin\Controller;

use Common\Controller\BaseController;
use Admin\Model\AuthRuleModel;
use Admin\Model\AuthGroupModel;

class AdminController extends BaseController {

  protected $meta = array(
    'UserGroup' => '会员等级',
    'DeliveryTpl' => '运费模板',
    'Ship' => '发货单',
    'Area' => '配送区域',
    'ItemComment' => '商品评价',
    'Item' => '商品',
    'Withdraw' => '提现',
    'Shop' => '店铺',
    'Supplier' => '供应商',
    'Union' => '推广联盟',
    'Manjian' => '满减',
    'SecondPieces' => '第二件折扣',
    'BuySend' => '买送',
    'Seckill' => '秒杀',
    'Message' => '站内消息'
  );
  
  /**
   * 后台控制器初始化
   */
  protected function _initialize(){
    parent::_initialize();
    // 获取当前用户ID
    if(defined('UID')) return ;
    define('UID', is_login());
    if(!UID){// 还没登录 跳转到登录页面
      $this->redirect('Public/login');
    }
    /* 读取数据库中的配置 */
    $config = S('DB_CONFIG_DATA');
    if(!$config){
      $config = api('Config/lists');
      S('DB_CONFIG_DATA', $config);
    }
    C($config); //添加配置
    
    //是否供应商
    $where_supplier = array(
      'uid' => UID,
      'group_id' => C('SUPPLIER_GROUP_ID')
    );
    define('IS_SUPPLIER', M('AuthGroupAccess')->where($where_supplier)->find() ? true : false);
    // 是否开发者
    define('IS_ROOT', is_developer());
    if(!IS_ROOT && C('ADMIN_ALLOW_IP')){
      // 检查IP地址访问
      if(!in_array(get_client_ip(),explode(',',C('ADMIN_ALLOW_IP')))){
        $this->error('403:禁止访问');
      }
    }
    // 检测系统权限
    if(!IS_ROOT){
      $access = $this->accessControl();
      if(false === $access){
        $this->error('403:禁止访问');
      }elseif(null === $access){
        //检测访问权限
        $rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
        if(!$this->checkRule($rule, array('in', '1,2'))){
          $this->error('未授权访问!');
        }else{
          // 检测分类及内容有关的各项动态权限
          $dynamic = $this->checkDynamic();
          if(false === $dynamic){
            $this->error('未授权访问!');
          }
        }
      }
    }
    $menus = $this->getMenus();
    
    //供应商身份登录菜单处理
    if(IS_SUPPLIER){
      $this->assign('IS_SUPPLIER', IS_SUPPLIER);
      $menus = A('Supplier', 'Event')->menusInit($menus);
    }
    $this->assign('__MENU__', $menus);
  }
  
  /**
   * 单表查询通用列表
   * @param  array $where 查询条件
   * @return array 内容列表
   * @author Justin <justin@jipu.com>
   */
  function index($where = null){
    !$where && $where['status'] = array('egt', 0);
    $lists = $this->lists(CONTROLLER_NAME, $where);
    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    int_to_string($lists, array('status' => array('0' => '<span class="text-cancel">已禁用</span>', '1' => '<span class="text-success">正常</span>')));
    //display前置操作
    method_exists($this, '_before_index_display') && $this->_before_index_display($lists);
    $this->lists = $lists;
    $this->meta_title = $this->meta[CONTROLLER_NAME].'列表';
    $this->display();
  }
  
  /**
   * 单表查询通用添加方法
   * @author Justin <justin@jipu.com>
   * @version 2015070111
   */
  function add(){
    $this->meta_title = '新增'.$this->meta[CONTROLLER_NAME];
    file_exists(T()) ? $this->display() : $this->display('edit');
  }
  
  /**
   * 单表查询通用编辑方法
   * @param string $model 模型名字
   * @author Justin <justin@jipu.com>
   */
  function edit($model = CONTROLLER_NAME){
    $id = I('request.id');
    if(empty($id)){
      $this->error('参数不能为空！');
    }
    $this->data = D($model)->detail($id);
    $this->meta_title = '编辑'.$this->meta[CONTROLLER_NAME];
    //display前置操作
    method_exists($this, '_before_edit_display') && $this->_before_edit_display();
    $this->display();
  }
  
  /**
  * 单表查询通用更新方法
  * @param string $model 模型名字
  * @author Justin <justin@jipu.com>
  */
  function update($model = CONTROLLER_NAME){
    if(IS_POST){
      $model = D($model);
      if(false !== $model->update()){
        $this->success('操作成功！', Cookie('__forward__'));
      }else{
        $error = $model->getError();
        $this->error(empty($error) ? '未知错误！' : $error);
      }
    }else{
      $this->redirect('index');
    }
  }
  
  /**
   * 更新部分数据
   * @author Max.Yu <max@jipu.com>
   */
  public function updateField($model = CONTROLLER_NAME){
    $res = D($model)->updateField();
    if(!$res){
      $this->error($model->getError());
    }else{
      $this->success('更新成功', Cookie('__forward__'));
    }
  }
  
  /**
   * 权限检测
   * @param string  $rule    检测的规则
   * @param string  $mode    check模式
   * @return boolean
   * @author 朱亚杰  <xcoolcc@gmail.com>
   */
  final protected function checkRule($rule, $type = AuthRuleModel::RULE_URL, $mode = 'url'){
    static $Auth = null;
    if (!$Auth){
      $Auth = new \Think\Auth();
    }
    if(!$Auth->check($rule,UID,$type,$mode)){
      return false;
    }
    return true;
  }

  /**
   * 检测是否是需要动态判断的权限
   * @return boolean|null
   *      返回true则表示当前访问有权限
   *      返回false则表示当前访问无权限
   *      返回null，则表示权限不明
   *
   * @author 朱亚杰  <xcoolcc@gmail.com>
   */
  protected function checkDynamic(){}


  /**
   * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
   *
   * @return boolean|null  返回值必须使用 `===` 进行判断
   *
   *   返回 **false**, 不允许任何人访问(超管除外)
   *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
   *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
   * @author 朱亚杰  <xcoolcc@gmail.com>
   */
  final protected function accessControl(){
    $allow = C('ALLOW_VISIT');
    $deny  = C('DENY_VISIT');
    $check = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
    if(!empty($deny)  && in_array_case($check,$deny)){
      return false;//非超管禁止访问deny中的方法
    }
    if(!empty($allow) && in_array_case($check,$allow)){
      return true;
    }
    return null;//需要检测节点权限
  }

  /**
   * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
   *
   * @param string $model 模型名称,供M函数使用的参数
   * @param array  $data  修改的数据
   * @param array  $where 查询时的where()方法的参数
   * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
   *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
   *
   * @author 朱亚杰  <zhuyajie@topthink.net>
   */
  final protected function editRow ( $model ,$data, $where , $msg){
    $id    = array_unique((array)I('id',0));
    $id    = is_array($id) ? implode(',',$id) : $id;
    //如存在id字段，则加入该条件
    $fields = M($model)->getDbFields();
    if(in_array('id',$fields) && !empty($id)){
      $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
    }

    $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
    if(M($model)->where($where)->save($data)!==false){
      //记录行为
      action_log('update_'.$model, $model, $id, UID);
      R('Index/getcleancache');
      $this->success($msg['success'],$msg['url'],$msg['ajax']);
    }else{
      $this->error($msg['error'],$msg['url'],$msg['ajax']);
    }
  }

  /**
   * 禁用条目
   * @param string $model 模型名称,供D函数使用的参数
   * @param array  $where 查询时的 where()方法的参数
   * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
   *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
   *
   * @author 朱亚杰  <zhuyajie@topthink.net>
   */
  protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
    $data    =  array('status' => 0);
    $this->editRow( $model , $data, $where, $msg);
  }

  /**
   * 恢复条目
   * @param string $model 模型名称,供D函数使用的参数
   * @param array  $where 查询时的where()方法的参数
   * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
   *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
   *
   * @author 朱亚杰  <zhuyajie@topthink.net>
   */
  protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
    $data    =  array('status' => 1);
    $this->editRow(   $model , $data, $where, $msg);
  }

  /**
   * 还原条目
   * @param string $model 模型名称,供D函数使用的参数
   * @param array  $where 查询时的where()方法的参数
   * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
   *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
   * @author huajie  <banhuajie@163.com>
   */
  protected function restore (  $model , $where = array() , $msg = array( 'success'=>'状态还原成功！', 'error'=>'状态还原失败！')){
    $data    = array('status' => 1);
    $where   = array_merge(array('status' => -1),$where);
    $this->editRow(   $model , $data, $where, $msg);
  }

  /**
   * 条目假删除
   * @param string $model 模型名称,供D函数使用的参数
   * @param array  $where 查询时的where()方法的参数
   * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
   *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
   *
   * @author 朱亚杰  <zhuyajie@topthink.net>
   */
  protected function delete ($model , $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！')) {
    $data['status'] = -1;
    $this->editRow($model, $data, $where, $msg);
  }

  /**
   * 设置一条或者多条数据的状态
   */
  public function setStatus($Model=CONTROLLER_NAME){

    $ids = I('request.ids');
    $status = I('request.status');
    if(empty($ids)){
      $this->error('请选择要操作的数据');
    }

    $map['id'] = array('in',$ids);
    switch ($status){
      case -1 :
        if($Model == 'Union'){
          $sid = M('Union')->where('id ='.$ids)->getField('uid');
          M('Distribution')->where('user_id='.$sid)->delete();
          M('Union')->where('id ='.$ids)->delete() ;
          $data['form_union_status'] = 0;
          M('User')->where('id='.$sid)->save($data)? $this->success('更新成功！'): $this->error('更新失败！');
        }else{
          $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));
        }
        
        break;
      case 0  :
        if($Model == 'Union'){
          $sid = M('Union')->where('id ='.$ids)->getField('uid');
          $data['form_union_status'] = 0;
          M('User')->where('id='.$sid)->save($data);
        }
        $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));
        break;
      case 1  :
        if($Model == 'Union'){
          $sid = M('Union')->where('id ='.$ids)->getField('uid');
          $data['form_union_status'] = 1;
          M('User')->where('id='.$sid)->save($data);
        }
        $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));
        break;
      default :
        $this->error('参数错误');
        break;
    }
  }

  /**
   * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
   * @author 朱亚杰  <xcoolcc@gmail.com>
   */
  final public function getMenus($controller=CONTROLLER_NAME){
    $menus = session('ADMIN_MENU_LIST.'.$controller);
    
    if(empty($menus) || empty($menus['child'])){
      // 获取主菜单
      $where['pid']   =   0;
      $where['hide']  =   0;
      if(!C('DEVELOP_MODE')){ // 是否开发者模式
        $where['is_dev']    =   0;
      }
      $menus['main'] = M('Menu')->where($where)->order('sort asc')->field('id,title,url')->select();
      $menus['child'] = array(); //设置子节点
      foreach($menus['main'] as $key => $item) {
        // 判断主菜单权限
        if(!IS_ROOT && !$this->checkRule(strtolower(MODULE_NAME.'/'.$item['url']),AuthRuleModel::RULE_MAIN,null)){
          unset($menus['main'][$key]);
          continue;//继续循环
        }
        if(strtolower(CONTROLLER_NAME.'/'.ACTION_NAME)  == strtolower($item['url'])){
          $menus['main'][$key]['class']='current';
        }
      }
      // 查找当前子菜单
      $pid = M('Menu')->where("pid !=0 AND url like '{$controller}/".ACTION_NAME."%'")->getField('pid');

      // 修复一处bug，使用精确查询，防止下级菜单中有相同名称的情况导致侧边栏错乱
      // Max.Yu 2015-01-03 18:41:28
      // $pid = M('Menu')->where("pid !=0 AND url = '{$controller}/".ACTION_NAME."'")->getField('pid');
      if($pid){
        // 查找当前主菜单
        $nav = M('Menu')->find($pid);
        if($nav['pid']){
          $nav = M('Menu')->find($nav['pid']);
        }
        foreach($menus['main'] as $key => $item) {
          // 获取当前主菜单的子菜单项
          if($item['id'] == $nav['id']){
            $menus['main'][$key]['class']='current';
            //生成child树
            $groups = M('Menu')->where(array('group'=>array('neq',''),'pid' =>$item['id']))->distinct(true)->getField("group",true);
            //获取二级分类的合法url
            $where          =   array();
            $where['pid']   =   $item['id'];
            $where['hide']  =   0;
            if(!C('DEVELOP_MODE')){ // 是否开发者模式
              $where['is_dev']    =   0;
            }
            $second_urls = M('Menu')->where($where)->getField('id,url');

            if(!IS_ROOT){
              // 检测菜单权限
              $to_check_urls = array();
              foreach($second_urls as $key=>$to_check_url) {
                if(stripos($to_check_url,MODULE_NAME)!==0){
                  $rule = MODULE_NAME.'/'.$to_check_url;
                }else{
                  $rule = $to_check_url;
                }
                if($this->checkRule($rule, AuthRuleModel::RULE_URL,null))
                  $to_check_urls[] = $to_check_url;
              }
            }
            // 按照分组生成子菜单树
            foreach($groups as $g) {
              $map = array('group'=>$g);
              if(isset($to_check_urls)){
                if(empty($to_check_urls)){
                  // 没有任何权限
                  continue;
                }else{
                  $map['url'] = array('in', $to_check_urls);
                }
              }
              $map['pid']     =   $item['id'];
              $map['hide']    =   0;
              if(!C('DEVELOP_MODE')){ // 是否开发者模式
                $map['is_dev']  =   0;
              }
              $menuList = M('Menu')->where($map)->field('id,pid,title,url,tip')->order('sort asc')->select();
              $menus['child'][$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
            }
          }
        }
      }
      session('ADMIN_MENU_LIST.'.$controller,$menus);
    }
    return $menus;
  }

  /**
   * 返回后台节点数据
   * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
   * @retrun array
   *
   * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
   *
   * @author 朱亚杰 <xcoolcc@gmail.com>
   */
  final protected function returnNodes($tree = true){
    static $tree_nodes = array();
    if($tree && !empty($tree_nodes[(int)$tree])){
      return $tree_nodes[$tree];
    }
    if((int)$tree){
      $list = M('Menu')->field('id,pid,title,url,tip,hide')->order('sort asc')->select();
      foreach($list as $key => $value) {
        if(stripos($value['url'],MODULE_NAME)!==0){
          $list[$key]['url'] = MODULE_NAME.'/'.$value['url'];
        }
      }
      $nodes = list_to_tree($list,$pk='id',$pid='pid',$child='operator',$root=0);
      foreach($nodes as $key => $value) {
        if(!empty($value['operator'])){
          $nodes[$key]['child'] = $value['operator'];
          unset($nodes[$key]['operator']);
        }
      }
    }else{
      $nodes = M('Menu')->field('title,url,tip,pid')->order('sort asc')->select();
      foreach($nodes as $key => $value) {
        if(stripos($value['url'],MODULE_NAME)!==0){
          $nodes[$key]['url'] = MODULE_NAME.'/'.$value['url'];
        }
      }
    }
    $tree_nodes[(int)$tree]   = $nodes;
    return $nodes;
  }

  /**
   * 处理文档列表显示
   * @param array $list 列表数据
   * @param integer $model_id 模型id
   */
  protected function parseDocumentList($list, $model_id=null){
    $model_id = $model_id ? $model_id : 1;
    $attrList = get_model_attribute($model_id,false,'id,name,type,extra');
    // 对列表数据进行显示处理
    if(is_array($list)){
      foreach($list as $k=>$data){
        foreach($data as $key=>$val){
          if(isset($attrList[$key])){
            $extra      =   $attrList[$key]['extra'];
            $type       =   $attrList[$key]['type'];
            if('select'== $type || 'checkbox' == $type || 'radio' == $type || 'bool' == $type) {
              // 枚举/多选/单选/布尔型
              $options    =   parse_field_attr($extra);
              if($options && array_key_exists($val,$options)) {
                $data[$key]    =   $options[$val];
              }
            }elseif('date'==$type){ // 日期型
              $data[$key]    =   date('Y-m-d',$val);
            }elseif('datetime' == $type){ // 时间型
              $data[$key]    =   date('Y-m-d H:i',$val);
            }
          }
        }
        $data['model_id'] = $model_id;
        $list[$k]   =   $data;
      }
    }
    return $list;
  }

  /**
   * 设置url排序参数
   */
  protected function setListOrder(){
    $_field = I('request._field');
    $_order = (I('request._order') == 'desc') ? 'asc' : 'desc';
    $_order_icon_str = ($_order == 'desc') ? 'icon-up' : 'icon-down';
    $_order_icon[$_field] = '<i class="icon '.$_order_icon_str.'"></i>';
    $_order_icon_show = !I('request._order') ? '<i class="icon icon-order"></i>' : '';
    $this->assign('_order', $_order);
    $this->assign('_order_icon', $_order_icon);
    $this->assign('_order_icon_show', $_order_icon_show);
  }
  
  /**
   * 设置单个字段值
   * @param string $model 控制器名称|数据表名称
   * @return json 数据更新结果
   * @author Max.Yu <max@jipu.com>
   */
  public function setFieldValue($model = CONTROLLER_NAME){
    $id = I('post.id', '');
    $field = I('post.field', '');
    $value = I('post.value', '');
    if(empty($id) || empty($field)){
      $this->error('参数不完整！');
    }
    if(!check_form_hash()){
      $this->error('非法数据提交！');
    }
    $res = M($model)->where(array('id'=>$id))->setField($field, $value);
    if($res){
      $this->success('更新成功！');
    }else{
      $this->error('更新失败！');
    }
  }
}
