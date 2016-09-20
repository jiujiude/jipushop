<?php
/**
 * 后台商品属性控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Think\Page;

class ItemPropertyController extends AdminController {
  private $formtype;

  public function _initialize(){
    $config = array(
      'formtype'    => array(
        'input'   => '文本框',
        'textarea'  => '文本域',
        'select'  => '下拉框',
        'radio'   => '单选框',
        'checkbox'  => '复选框',
        'color'   => '颜色复选',
        'image'   => '图片复选'
      ),
      'formtype_spc'=> array(
        'checkbox'  => '复选框',
        'color'   => '颜色复选',
        'image'   => '图片复选'
      )
    );

    $this->formtype = $config;
    parent:: _initialize();
  }

  /**
   * 商品属性列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    //实例化商品属性模型
    $ItemProperty = M('ItemProperty');
    
    //属性归类
    $type = I('request.type');
    if(empty($type)){
      $type = 'attribute';
    }

    //属性归类：属性
    if ($type == 'attribute') {
      $type_name = '属性';
      $where['type'] = $type;
    }

    //属性归类：参数
    if($type == 'parameter'){
      $type_name = '参数';
      $where['type'] = $type;
    }

    //属性归类：规格
    if($type == 'specification'){
      $type_name = '规格';
      $where['type'] = $type;
    }

    //查询条件初始化
    $where['user_id'] = UID;

    //按条件查询结果并分页
    $list = $this->lists($ItemProperty, $where, 'cid asc, displayorder asc, id desc');

    $to_string = array(
      'isrequired'=>array(
        1=>'是',
        0=>'否'
      ),
      'formtype'=>array(
        'input'   => '文本框',
        'textarea'  => '文本域',
        'select'  => '下拉框',
        'radio'   => '单选框',
        'checkbox'  => '复选框',
        'color'   => '颜色复选',
        'image'   => '图片复选',
      ),
    );

    int_to_string($list, $to_string);

    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->assign('list', $list);
    $this->assign('type', $type);
    $this->assign('type_name', $type_name);
    $this->meta_title = '商品' . $type_name . '列表';
    $this->display();
  }

  /**
   * 新增商品属性
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    $formtype = $this->formtype['formtype'];

    //属性归类
    $type = I('request.type');
    if(empty($type)){
      $type = 'attribute';
    }

    //属性归类：属性
    if ($type == 'attribute') {
      $type_name = '属性';
      $where['type'] = $type;
    }

    //属性归类：参数
    if($type == 'parameter'){
      $type_name = '参数';
      $where['type'] = $type;
    }

    //属性归类：规格
    if($type == 'specification'){
      $type_name = '规格';
      $where['type'] = $type;
      $formtype = $this->formtype['formtype_spc'];
    }

    if(IS_POST){
      $ItemProperty = D('ItemProperty');
      $data = $ItemProperty->create();
      if($data){
        $id = $ItemProperty->add();
        if($id){
          //保存属性选项值
          $this->saveOption($id, false);
          
          //记录行为
          if($type == 'attribute'){
            action_log('update_item_attribute', 'ItemProperty', $id, UID);
          }elseif($type == 'specification'){
            action_log('update_item_specification', 'ItemProperty', $id, UID);
          }
          
          $jump_url = I('post.jump_url');
          if(empty($jump_url)){
            $this->success('新增成功', Cookie('__forward__'));
          }else{
            $this->success('新增成功', $jump_url);
          }
        }else{
          $this->error('新增失败');
        }
      }else{
        $this->error($ItemProperty->getError());
      }
    }else{
      $this->assign('type', $type);
      $this->assign('type_name', $type_name);
      $this->assign('formtype', $formtype);
      $this->meta_title = '新增商品'. $type_name;
      $this->display('edit');
    }
  }

  /**
   * 编辑商品属性
   * @author Max.Yu <max@jipu.com>
   */
  public function edit($id = 0){
    $formtype = $this->formtype['formtype'];

    //属性归类
    $type = I('request.type');
    if(empty($type)){
      $type = 'attribute';
    }

    //属性归类：属性
    if ($type == 'attribute') {
      $type_name = '属性';
      $where['type'] = $type;
    }

    //属性归类：参数
    if($type == 'parameter'){
      $type_name = '参数';
      $where['type'] = $type;
    }

    //属性归类：规格
    if($type == 'specification'){
      $type_name = '规格';
      $where['type'] = $type;
      $formtype = $this->formtype['formtype_spc'];
    }

    if(IS_POST){
      $ItemProperty = D('ItemProperty');
      $data = $ItemProperty->create();
      if($data){
        if($ItemProperty->save($data)!== false){
          //保存属性选项值
          $this->saveOption($data['id'], true);

     
          //记录行为
          if($type == 'attribute'){
            action_log('update_item_attribute', 'ItemProperty', $id, UID);
          }elseif($type == 'specification'){
            action_log('update_item_specification', 'ItemProperty', $id, UID);
          }
          $this->success('更新成功', Cookie('__forward__'));
        }else{
          $this->error('更新失败');
        }
      }else{
        $this->error($ItemProperty->getError());
      }
    }else{
      $info = array();
      $option = array();

      /* 获取数据 */
      $info = M('ItemProperty')->find($id);

      if(false === $info){
        $this->error('获取商品' . $type_name . '信息错误');
      }

      //获取属性选项值
      $option = $this->getOption($info['id']);

      foreach($option as $op){
        $optItem[] = array(
          'code'      => $op['code'],
          'prp_id'    => $op['prp_id'],
          'option'    => $op['option'],
          'color'     => $op['color'],
          'pic'     => $op['pic'],
          'path'      => get_cover($op['pic'], 'path'),
          'sort'      => $op['sort'],
        );
      }

      $option = json_encode($optItem);

      $this->assign('type', $type);
      $this->assign('type_name', $type_name);
      $this->assign('formtype', $formtype);
      $this->assign('info', $info);
      $this->assign('option', $option);

      $this->meta_title = '编辑商品' . $type_name;
      $this->display();
    }
  }

  /**
   * 保存商品属性选项值信息
   * @author Max.Yu <max@jipu.com>
   */
  public function saveOption($prp_id = null, $is_edit = false){
    if(empty($prp_id)){
      return false;
    }

    // 实例化商品属性选项值模型
    $PropertyOption = M('PropertyOption');

    // 获取属性值编码数组
    $code = I('post.code');

    // 获取属性值数组
    $option = I('post.option');

    // 获取颜色值数组
    $color = I('post.color');

    // 获取图片数组
    $pic = I('post.pic');

    // 获取排序数组
    $sort = I('post.sort');

    // 生成属性选项值数组
    foreach($option as $key => $val){
      if(!empty($val)){
        if(empty($code[$key])){
          $code_ = build_unique_code(6, $key);
        }else{
          $code_ = $code[$key];
        }

        $optItem[] = array(
          'code'    => $code_,
          'prp_id'  => $prp_id,
          'option'  => $val,
          'pic'     => $pic[$key],
          'color'   => $color[$key],
          'sort'    => $sort[$key],
        );
      }
    }

    // 清空旧的属性选项值数据
    if($is_edit){
      $PropertyOption->where('prp_id='.$prp_id)->delete();
    }
    
    // 属性选项值批量写入
    if(!empty($optItem)){
      $PropertyOption->addAll($optItem);
    }
  }

  /**
   * 输出商品属性选项值
   * @author Max.Yu <max@jipu.com>
   */
  public function getOption($prp_id){
    $returnInfo = array();

    //实例化属性选项模型
    $PropertyOption = M('PropertyOption');

    //定义返回或者操作的字段
    $field = '*';

    //查询条件初始化
    $where['prp_id'] = $prp_id;

    //获取属性选项值列表
    $returnInfo = $PropertyOption->where($where)->order('sort asc')->field($field)->select();

    return $returnInfo;
  }

  /**
   * 输出商品属性值
   * @author Max.Yu <max@jipu.com>
   */
  public function getValue($item_id = null, $prp_id = null, $value_type = 'property', $property_type = 'attribute', $formtype = null){
    $returnInfo = '';

    if(empty($item_id)){
      return $returnInfo;
    }

    //实例化属性值模型
    $ItemExtend = M('ItemExtend');

    //定义返回或者操作的字段
    $field = 'info';

    //查询条件初始化
    $where['item_id'] = $item_id;
    $where['prp_id'] = $prp_id;

    //获取属性值
    $item_extend = $ItemExtend->where($where)->field($field)->find();
    //print_r($item_extend);

    if($item_extend){
      //属性值反序列化
      $item_extend_ = unserialize($item_extend['info']);
      //print_r($item_extend_);

      if($item_extend_){
        $tmp_array = $item_extend_[$value_type];
        //print_r($tmp_array);
        if(is_array($tmp_array)){
          if($property_type === 'specification'){
            if($value_type === 'pic'){
              $returnInfo = implode(',', $tmp_array);
            }else{
              foreach($tmp_array as $tmp) {
                $returnInfo = implode(',', $tmp);
              }
            }
          } elseif($property_type === 'attribute'){
            if($formtype === 'input' || $formtype === 'textarea' || $formtype === 'select'){
              $returnInfo = implode(',', $tmp_array);
            }else{
              foreach($tmp_array as $tmp) {
                $returnInfo = implode(',', $tmp);
              }
            }
            //print_r($returnInfo);
          }
        }
      }
    }
    //print_r($returnInfo);
    //exit();
    return $returnInfo;
  }

  /**
   * 输出图片配置数据
   * @author Max.Yu <max@jipu.com>
   */
  public function getPictureConfigData($item_id = null, $prp_id = null, $code = null){
    $returnInfo = '';

    if(empty($item_id)){
      return false;
    }

    //实例化属性值模型
    $ItemExtend = M('ItemExtend');

    //定义返回或者操作的字段
    $field = 'info';

    //查询条件初始化
    $where['item_id'] = $item_id;
    $where['prp_id'] = $prp_id;

    //获取属性值
    $item_extend = $ItemExtend->where($where)->field($field)->find();
    //var_dump($item_extend);

    if($item_extend){
      //属性值反序列化
      $item_extend = unserialize($item_extend['info']);
      //var_dump($item_extend);
      if($item_extend){
        $color_array = $item_extend['property']['color'];
        //var_dump($color_array);
        foreach($color_array as $k => $v) {
          $color[] = $v;
        }
        //var_dump($color);

        $pic = $item_extend['pic'];
        //var_dump($pic);

        foreach($color as $k => $v) {
          if(!empty($pic)){
            //var_dump($k);
            if(!empty($pic[$k])){
              $retuninfo[$v]['pic_id'] = $pic[$k];
              $retuninfo[$v]['pic_path'] = get_cover($pic[$k], 'path');
            }
          }
        }
      }
    }
    //var_dump($retuninfo);
    //var_dump(json_encode($retuninfo));
    //exit();
    echo json_encode($retuninfo);
  }

  /**
   * 查看商品属性
   * @author Max.Yu <max@jipu.com>
   */
  public function view($id = 0){
    $info = array();

    /* 获取数据 */
    $info = M('ItemProperty')->find($id);

    if(false === $info){
      $this->error('获取收货信息错误');
    }
    $this->assign('info', $info);
    $this->meta_title = '查看商品属性信息';
    $this->display();
  }

  /**
   * 图片上传
   * @author Max.Yu <max@jipu.com>
   */
  public function upload(){
    if(IS_POST){
      /* 调用文件上传组件上传文件 */
      $Picture = D('Picture');
      $pic_driver = C('PICTURE_UPLOAD_DRIVER');
      $info = $Picture->upload(
      $_FILES,
      C('PICTURE_UPLOAD'),
      C('PICTURE_UPLOAD_DRIVER'),
      C("UPLOAD_{$pic_driver}_CONFIG")
      );
      echo json_encode($info);
      //      var_dump(C('PICTURE_UPLOAD'));
      //      var_dump($info);

      //      $upload = new \Think\Upload();  // 实例化上传类
      //      $upload->maxSize = 3145728 ;  // 设置附件上传大小
      //      $upload->exts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
      //      $upload->rootPath = './Uploads/'; // 设置附件上传根目录
      //      $upload->savePath = 'Picture/'; // 设置附件上传（子）目录
      //
      //      // 上传文件
      //      $info = $upload->upload();
      //      if(!$info) {// 上传错误提示错误信息
      //        $this->error($upload->getError());
      //      }else{// 上传成功
      //        //$this->success('上传成功！');
      //        var_dump($info);
      //      }
    }else{
      $this->display();
    }
  }

  /**
   * 删除商品属性（物理删除）
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');

    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }

    $map['id'] = array('in',$ids);
    $map_opt['prp_id'] = array('in',$ids);
    if(M('ItemProperty')->where($map)->delete()){
      //删除属性选项值
      M('PropertyOption')->where($map_opt)->delete();

      //记录行为
      if($type == 'attribute'){
        action_log('update_item_attribute', 'ItemProperty', $id, UID);
      }elseif($type == 'specification'){
        action_log('update_item_specification', 'ItemProperty', $id, UID);
      }
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }

  /**
   * 输出商品属性，供前端AJAX调用
   * @author Max.Yu <max@jipu.com>
   */
  public function getProperty($item_id = null, $type = null, $cid_1 = null, $cid_2 = null, $cid_3 = null){
    if(empty($type)){
      $type = 'attribute';
    }

    $where['type'] = $type;

    if(!empty($cid_1)){
      $cid[0] = $cid_1;
    }

    if(!empty($cid_2)){
      $cid[1] = $cid_2;
    }

    if(!empty($cid_3)){
      $cid[2] = $cid_3;
    }

    $where['cid'] = array('in',$cid);

    $property = M('ItemProperty')->where($where)->field('id, cname')->order('cid asc, displayorder asc, id asc')->select();
    $retuninfo['property'] = $property;

    if(!empty($item_id)){
      $map['item_id'] = $item_id;
      $property_value = M('ItemExtend')->where($map)->order('id asc')->select();
      $retuninfo['property_value'] = $property_value;
    }

    echo json_encode($retuninfo);
  }

  /**
   * 输出商品属性，供前端AJAX调用
   * @author Max.Yu <max@jipu.com>
   */
  public function getProp($item_id = null, $type = null, $cid_1 = null, $cid_2 = null, $cid_3 = null){
    if(empty($type)){
      $type = 'attribute';
    }

    $where['type'] = $type;

    if(!empty($cid_1)){
      $cid[0] = $cid_1;
    }

    if(!empty($cid_2)){
      $cid[1] = $cid_2;
    }

    if(!empty($cid_3)){
      $cid[2] = $cid_3;
    }

    $where['cid'] = array('in',$cid);

    //获取属性列表
    $property_list = M('ItemProperty')->where($where)->field(true)->order('displayorder asc, id asc')->select();
    //拼接属性名称，属性选项配置，属性值数组
    if($property_list){
      foreach($property_list as $list) {
        $property_all[$list['id']]['property'] = $list;
        $property_all[$list['id']]['option'] = $this->getOption($list['id']);
        $property_all[$list['id']]['value'] = $this->getValue($item_id, $list['id'], 'property', $type, $list['formtype']);
        $property_all[$list['id']]['pic'] = $this->getValue($item_id, $list['id'], 'pic', $type, $list['formtype']);
      }
    }

    //获取规格组合数据
    $spc_data = null;
    if(!empty($item_id)){
      $spc_data = M('ItemSpecifiction')->where('item_id = '.$item_id)->select();
      if(!empty($item_id)){
        $spc_data = json_encode($spc_data);
      }
    }
    $this->assign('item_id', $item_id);
    $this->assign('type', $type);
    $this->assign('property_all', $property_all);
    $this->assign('spc_data', $spc_data);
    $this->display();
  }

  /**
   * 输出商品属性，供前端AJAX调用
   * @author Max.Yu <max@jipu.com>
   */
  public function getProp_old($item_id = null, $type = null, $cid_1 = null, $cid_2 = null, $cid_3 = null){
    if(empty($type)){
      $type = 'attribute';
    }

    $where['type'] = $type;

    if(!empty($cid_1)){
      $cid[0] = $cid_1;
    }

    if(!empty($cid_2)){
      $cid[1] = $cid_2;
    }

    if(!empty($cid_3)){
      $cid[2] = $cid_3;
    }

    $where['cid'] = array('in',$cid);

    $property_list = M('ItemProperty')->where($where)->field(true)->order('cid asc, displayorder asc, id asc')->select();

    if(!empty($item_id)){
      $map['item_id'] = $item_id;
      $property_value = M('ItemExtend')->where($map)->order('id asc')->select();
      if($property_value && is_array($property_value)){
        foreach($property_value as $key => &$value){
          $property_value_arr[$value['prp_id']] = $value;
          $property_value_arr[$value['prp_id']]['info_arr'] = unserialize($value['info']);
          /*处理规格、颜色*/
          if($property_value_arr[$value['prp_id']]['info_arr']['color'] && is_array($property_value_arr[$value['prp_id']]['info_arr']['color'])){
            foreach($property_value_arr[$value['prp_id']]['info_arr']['color'] as $k => $v){
              $property_value_arr[$value['prp_id']]['info_value'][$k]['name'] = $v;
              if($property_value_arr[$value['prp_id']]['info_arr']['price']){
                $property_value_arr[$value['prp_id']]['info_value'][$k]['price'] = $property_value_arr[$value['prp_id']]['info_arr']['price'][$k];
              }
              /*库存*/
              if($property_value_arr[$value['prp_id']]['info_arr']['stock']){
                $property_value_arr[$value['prp_id']]['info_value'][$k]['stock'] = $property_value_arr[$value['prp_id']]['info_arr']['stock'][$k];
              }
              /*商家编号*/
              if($property_value_arr[$value['prp_id']]['info_arr']['number']){
                $property_value_arr[$value['prp_id']]['info_value'][$k]['number'] = $property_value_arr[$value['prp_id']]['info_arr']['number'][$k];
              }
            }
          }
        }
      }
    }

    if($property_list){
      foreach($property_list as $key => &$value) {
        $property[$value['id']] = $value;
        $property[$value['id']]['property_value'] = $property_value_arr[$value['id']]['info_arr'][$value['ename']];
        if($property[$value['id']]['property_value'] && is_array($property[$value['id']]['property_value'])){
          foreach($property[$value['id']]['property_value'] as $k => $v){
            $property[$value['id']]['property_key_arr'][] = $k;
            /*处理select、radio，直接显示value TODO：*/
            $property[$value['id']]['property_val'] = $v;
          }
          $property[$value['id']]['property_keys'] = implode(',', $property[$value['id']]['property_key_arr']);

        }
        // $property[$value['id']]['property_data'] = $property_value_arr[$value['id']]['info_value'];
      }
    }

    $data['property'] = $property;
    $data['type'] = $type;
    $this->assign('data', $data);
    $this->display();
  }

  /**
   * 把商品表里的属性值转存储到属性值表里
   * @author Max.Yu <max@jipu.com>
   */
  public function inputProperty(){
    $ItemInfo = array();

    //实例化商品模型
    $Item = M('Item');

    //定义返回或者操作的字段
    $field = 'id, flavor, alcohol';

    //查询条件初始化
    $where['status'] = 1;

    //获取商品列表
    $ItemInfo = $Item->where($where)->order('id asc')->field($field)->select();

    // 实例化商品扩展属性模型
    $ItemExtend = M('ItemExtend');

    // 新增属性数据
    foreach($ItemInfo as $item){
      $propertyItem[] = array(
      'item_id' => $item['id'],
      'prp_id'  => 1,
      'info'    => $item['flavor'],
      );

      $propertyItem[] = array(
      'item_id' => $item['id'],
      'prp_id'  => 2,
      'info'    => $item['alcohol'],
      );
    }

    //属性数据批量写入
    $ItemExtend->addAll($propertyItem);
  }
}