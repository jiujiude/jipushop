<?php
/**
 * 商品模型
 * @version 2015010714
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Model;

use Admin\Model\AuthGroupModel;
use Admin\Model\PicPictureModel;

class ItemModel extends AdminModel{

  /**
   * 自动验证规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_validate = array(
    array('cid_1', array(0,''), '请选择分类', self::MUST_VALIDATE, 'NOTIN', self::MODEL_BOTH),
    array('name', 'require', '商品名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('name', '1,80', '商品名称长度不能超过80个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    array('price', 'require', '商品价格不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('intro', 'require', '商品描述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    array('create_time', '/^\d{4,4}-\d{1,2}-\d{1,2}(\s\d{1,2}:\d{1,2}(:\d{1,2})?)?$/', '日期格式不合法,请使用"年-月-日 时:分"格式,全部为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_BOTH),
    array('sdp', '_checkSdp', '分销返现值只能是数字，且不能大于商品价格', self::EXISTS_VALIDATE, 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Max.Yu <max@jipu.com>
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('images', 'getImages', self::MODEL_BOTH, 'callback'),
    array('is_hot', 'getHotStatus', self::MODEL_BOTH, 'callback'),
    array('is_new', 'getNewStatus', self::MODEL_BOTH, 'callback'),
    array('is_promote', 'getPromoteStatus', self::MODEL_BOTH, 'callback'),
    array('is_recommend', 'getRecommendStatus', self::MODEL_BOTH, 'callback'),
    array('create_time', 'getCreateTime', self::MODEL_BOTH, 'callback'),
    array('update_time', NOW_TIME, self::MODEL_BOTH),
  );

  /**
   * 获取列表
   * @param  integer  $category 分类ID
   * @param  string   $order    排序规则
   * @param  integer  $status   状态
   * @param  boolean  $count    是否返回总数
   * @param  string   $field    字段 true-所有字段
   * @param  string   $limit    分页参数
   * @param  array    $map      查询条件参数
   * @return array              文档列表
   * @author huajie <banhuajie@163.com>
   */
  public function lists($category, $order = '`is_top` DESC, `sort` ASC', $status = 1, $field = true, $limit = '10', $map = array()){
    $map = array_merge($this->listMap($category, $status), $map);
    $result = $this->field($field)->where($map)->order($order)->limit($limit)->select();
    return $result;
  }

  /**
   * ajax方式获取商品列表
   * @param array $map 查询条件参数
   * @param string $field 字段 true-所有字段
   * @param string $order 排序规则
   * @param string $limit 分页参数
   * @return array 商品列表
   * @author Max.Yu <max@jipu.com>
   */
  public function listsAjax($map = array(), $field = true, $order = '`is_top` DESC, `sort` ASC', $limit = '5', $page = 1){
    $lists = $this->field($field)->where($map)->cache(false)->order($order)->limit($limit)->page($page)->select();
    if($lists && is_array($lists)){
      foreach($lists as $key => &$value){
        if($value['thumb']){
          $coverArr = get_cover($value['thumb']);
          $value['cover_path_tiny'] = get_image_thumb($coverArr['path'], 100, 100);
        }
      }
    }
    $total = $this->where($map)->count();
    $totalPages = ceil($total / $limit);
    $result['total'] = $total;
    $result['lists'] = $lists;
    $result['totalPages'] = $totalPages;
    return $result;
  }

  /**
   * 计算列表总数
   * @param  number  $category 分类ID
   * @param  integer $status   状态
   * @return integer           总数
   */
  public function listCount($category, $status = 1, $map = array()){
    $map = array_merge($this->listMap($category, $status), $map);
    return $this->where($map)->count('id');
  }

  /**
   * 获取详情页数据
   * @param  integer $id 文档ID
   * @return array       详细数据
   */
  public function detail($id){
    /* 获取基础数据 */
    $info = $this->field(true)->find($id);
    if(!(is_array($info) || 1 !== $info['status'])){
      $this->error = '商品被禁用或已删除！';
      return false;
    }
    return $info;
  }

  /**
   * 新增或更新一个商品
   * @param array  $data 手动传入的数据
   * @return boolean fasle 失败 ， int  成功 返回完整的数据
   * @author huajie <banhuajie@163.com>
   */
  public function update($data = null){
    //另存为新商品
    if(1 == I('post.save_as_new_item')){
      unset($_POST['id']);
    }
    //获取数据对象
    $data = $this->create($data);
    
    //去除图片id字符串两端多余的逗号，解决图片显示不出来的BUG
    $data['images'] && $data['images'] = trim($data['images'], ',');
    if(empty($data)){
      return false;
    }
    $data['thumb'] = explode(',', $data['images'])[0];
    //规格修改确认
    //$update_spec = I('post.update_spec', 0);
    //添加或新增基础内容
    if(empty($data['id'])){ //新增数据
      $id = $item_id = $this->add($data); //添加基础内容
      if(!$item_id){
        $this->error = '新增商品出错！';
        return false;
      }else{
        //保存属性
        $this->saveProperty($item_id, false);

        //保存规格
        $this->saveSpecifiction($item_id, false);

        //生成商品图片缩略图
        $this->createThumb($data['images']);
      }
    }else{ //更新数据
      $orderData = $this->where(array('id'=>$data['id']))->find();
      $status = $this->save($data); //更新基础内容
      $item_id = $data['id'];
      if(false === $status){
        $this->error = '更新商品出错！';
        return false;
      }else{
        //保存属性
        $this->saveProperty($item_id, true);
        //修改购物车中的供应商信息
        if(isset($data['supplier_id']) && $data['supplier_id'] != $orderData['supplier_id']){
            $cartMap['supplier_id'] = $orderData['supplier_id'];
            M('Cart')->where($cartMap)->save(array('supplier_id'=>$data['supplier_id']));
        }
        

        //保存规格组合配置数据
        $this->saveSpecifiction($item_id, true);

        //生成商品图片缩略图
        $this->createThumb($data['images']);
      }
    }

    /*判断并生产商品二维码*/
    $qrcode = C('QRCODE_CONFIG.rootPath').$item_id.'/'.$item_id.'.png';
    if(!file_exists($qrcode)){
     get_qrcode($item_id);
    }
   
    //记录行为
    action_log('update_item', 'item', $data['id'] ? $data['id'] : $id, UID);
    //行为记录
    //if($id){
    // action_log('add_item', 'item', $id, UID);
    //}
    //内容添加或更新完成
    return $data;
  }

  /**
   * 保存商品属性
   * @author Max.Yu <max@jipu.com>
   */
  public function saveProperty($item_id = null, $is_edit = false){
    if(empty($item_id)){
      return false;
    }

    //实例化商品扩展属性模型
    $ItemExtend = M('ItemExtend');

    //获取属性值数组
    $property = I('post.property');

    //获取属性值图片配置数组
    $pic = I('post.pic');

    //生成属性值数组
    foreach($property as $key => $val){
      $infoItem = array(
      'property'  => $val,
      'pic'   => $pic[$key],
      );

      if(is_array($infoItem)){
        $info = serialize($infoItem);
      }

      if(!empty($val)){
        $propertyItem[] = array(
          'item_id' => $item_id,
          'prp_id'  => $key,
          'info'    => $info,
        );
      }
    }
    //清空旧的属性数据
    if($is_edit){
      $ItemExtend->where('item_id='.$item_id)->delete();
    }

    //属性数据批量写入
    if(!empty($propertyItem)){
      $ItemExtend->addAll($propertyItem);
    }
  }

  /**
   * 保存商品规格组合对应的价格与数量
   * @author Max.Yu <max@jipu.com>
   */
  public function saveSpecifiction($item_id = null, $is_edit = false){
    if(empty($item_id)){
      return false;
    }

    //实例化商品规格模型
    $ItemSpecifiction = M('ItemSpecifiction');

    //获取价格数组
    $spc_price = $_POST['spc_price'];

    //获取库存数量数组
    $spc_quantity = $_POST['spc_quantity'];

    //生成规格组合值数组
    $total_quantity = 0;
    foreach($spc_price as $key => $val){
      if(!empty($val)){
        $spcItem[] = array(
          'item_id' => $item_id,
          'spc_code'  => $key,
          'price'   => $val,
          'quantity'  => $spc_quantity[$key],
        );

        //计算总库存
        $total_quantity = $total_quantity + $spc_quantity[$key];
      }
    }

    //清空旧的规格数据
    if($is_edit){
      $result = $ItemSpecifiction->where(array('item_id'=>$item_id))->delete();
      if($result){
        //更新总库存
        $this->updateStock($item_id, $total_quantity);
      }
    }

    //规格组合信息批量写入
    if(!empty($spcItem)){
      if($ItemSpecifiction->addAll($spcItem)){
        //更新总库存
        $this->updateStock($item_id, $total_quantity);
      }
    }
  }

  /**
   * 更新总库存
   * @param integer $item_id(商品ID)
   * @param interger $stock(总库存数量)
   * return 
   */
  public function updateStock($item_id = null, $stock = 0){
    if($item_id){
      return D('Item')->where(array('id'=>$item_id))->setField('stock', $stock);
    }
  }
  
  /**
   * 生成商品图片缩略图
   * @author Max.Yu <max@jipu.com>
   */
  public function createThumb($images = null){
    if(empty($images)){
      return false;
    }

    //实例化图片模型
    $picture_model = D('Picture');

    //获取图片路径数组
    $path_array = $picture_model->getPathByIds($images);

    //获取商品图片缩略图规格配置
    $thumb_size = C('UPLOAD_PIC_THUMB_SIZE.ITEM_PIC');

    //开始生成缩略图
    if($path_array && is_array($path_array)){
      foreach($path_array as $path){
        if($thumb_size && is_array($thumb_size)){
          foreach($thumb_size as $size){
            get_image_thumb($path, $size['WIDTH'], $size['HEIGHT']);
          }
        }else{
          break;
        }
      }
    }
  }

  /**
   * 删除商品图片以及缩略图
   * @author Max.Yu <max@jipu.com>
   */
  public function delPic($images = null){
    if(empty($images)){
      return false;
    }

    //实例化图片模型
    $picture_model = D('Picture');

    //获取商品图片缩略图规格配置
    $thumb_size = C('UPLOAD_PIC_THUMB_SIZE.ITEM_PIC');

    //开始删除图片
    return $picture_model->delByIds($images, $thumb_size);
  }

  /**
   * 获取数据状态
   * @return integer 数据状态
   */
  protected function getStatus(){
    $id = I('post.id');
    $cate = I('post.category_id');
    if(empty($id)){ //新增
      $status = 1;
    }else{        //更新
      $status = $this->getFieldById($id, 'status');
      //编辑草稿改变状态
      if($status == 3){
        $status = 1;
      }
    }
    return $status;
  }

  /**
   * 获取根节点id
   * @return integer 数据id
   * @author huajie <banhuajie@163.com>
   */
  protected function getRoot(){
    $pid = I('post.pid');
    if($pid == 0){
      return 0;
    }
    $p_root = $this->getFieldById($pid, 'root');
    return $p_root == 0 ? $pid : $p_root;
  }

  /**
   * 创建时间不写则取当前时间
   * @return int 时间戳
   * @author huajie <banhuajie@163.com>
   */
  protected function getCreateTime(){
    $create_time = I('post.create_time');
    return $create_time ? strtotime($create_time) : NOW_TIME;
  }


  protected function getImages(){
    $images = I('post.images');
    $thumb = I('post.thumb');
    if($images && is_array($images)){
      foreach($images as $key => $value){
        if($value == $thumb){
          unset($images[$key]);
        }
      }
      return $thumb.','.arr2str($images);
    }else{
      return $images;
    }
  }

  /**
   *
   * @return status 状态
   * @author huajie <banhuajie@163.com>
   */
  protected function getHotStatus(){
    return I('post.is_hot') ? I('post.is_hot') : 0;
  }

  /**
   *
   * @return status 状态
   * @author huajie <banhuajie@163.com>
   */
  protected function getNewStatus(){
    return I('post.is_new') ? I('post.is_new') : 0;
  }

  /**
   *
   * @return status 状态
   * @author huajie <banhuajie@163.com>
   */
  protected function getPromoteStatus(){
    return I('post.is_promote') ? I('post.is_promote') : 0;
  }

  /**
   *
   * @return status 状态
   * @author huajie <banhuajie@163.com>
   */
  protected function getRecommendStatus(){
    return I('post.is_recommend') ? I('post.is_recommend') : 0;
  }

  /**
   * 判断是否上传图片
   * @return int 时间戳
   * @author huajie <banhuajie@163.com>
   */
  protected function getImageStatus(){
    $thumb = I('post.thumb');
    return ($thumb > 0) ? 1 : 0;
  }

  /**
   * 验证分类是否允许发布内容
   * @param  integer $id 分类ID
   * @return boolean     true-允许发布内容，false-不允许发布内容
   */
  public function checkCategory($id){
    $publish = get_category($id, 'allow_publish');
    return $publish ? true : false;
  }

  /**
   * 检测分类是否绑定了指定模型
   * @param  array $info 模型ID和分类ID数组
   * @return boolean     true-绑定了模型，false-未绑定模型
   */
  protected function checkModel($info){
    $model = get_category($info['category_id'], 'model');
    return in_array($info['model_id'], $model);
  }

  /**
   * 获取扩展模型对象
   * @param  integer $model 模型编号
   * @return object         模型对象
   */
  private function logic($model){
    return D(get_document_model($model, 'name'), 'Logic');
  }

  /**
   * 设置where查询条件
   * @param  number  $category 分类ID
   * @param  number  $pos      推荐位
   * @param  integer $status   状态
   * @return array             查询条件
   */
  private function listMap($category, $status = 1, $pos = null){
    /* 设置状态 */
    $map = array('status' => $status);

    /* 设置分类 */
    if(!is_null($category)){
      if(is_numeric($category)){
        $map['category_id'] = $category;
      }else{
        $map['category_id'] = array('in', str2arr($category));
      }
    }

    /* 设置推荐位 */
    if(is_numeric($pos)){
      $map[] = "position & {$pos} = {$pos}";
    }

    return $map;
  }

  /**
   * 检查标识是否已存在(只需在同一根节点下不重复)
   * @param string $name
   * @return true无重复，false已存在
   * @author huajie <banhuajie@163.com>
   */
  protected function checkName(){
    $name = I('post.name');
    $pid = I('post.pid', 0);
    $id = I('post.id', 0);

    //获取根节点
    if($pid == 0){
      $root = 0;
    }else{
      $root = $this->getFieldById($pid, 'root');
      $root = $root == 0 ? $pid : $root;
    }

    $map = array('root'=>$root, 'name'=>$name, 'id'=>array('neq',$id));
    $res = $this->where($map)->getField('id');
    if($res){
      return false;
    }
    return true;
  }

  /**
   * 生成不重复的name标识
   * @author huajie <banhuajie@163.com>
   */
  private function generateName(){
    $str = 'abcdefghijklmnopqrstuvwxyz0123456789';  //源字符串
    $min = 10;
    $max = 39;
    $name = false;
    while (true){
      $length = rand($min, $max); //生成的标识长度
      $name = substr(str_shuffle(substr($str,0,26)), 0, 1); //第一个字母
      $name .= substr(str_shuffle($str), 0, $length);
      //检查是否已存在
      $res = $this->getFieldByName($name, 'id');
      if(!$res){
        break;
      }
    }
    return $name;
  }

  /**
   * 生成推荐位的值
   * @return number 推荐位
   * @author huajie <banhuajie@163.com>
   */
  protected function getPosition(){
    $position = I('post.position');
    if(!is_array($position)){
      return 0;
    }else{
      $pos = 0;
      foreach($position as $key=>$value){
        $pos += $value;   //将各个推荐位的值相加
      }
      return $pos;
    }
  }

  /**
   * 删除状态为-1的数据
   * @return true 删除成功， false 删除失败
   * @author huajie <banhuajie@163.com>
   */
  public function remove($ids){
    $where['id'] = array('IN',$ids);

    //获取图片ID字符串数组
    //$images = $this->where($where)->getField('images', true);

    //删除商品数据
    $res = $this->where($where)->delete();

    //   if($res){
    //     //删除图片（含缩略图）
    //     if($images && is_array($images)){
    //       foreach($images as $imgs){
    //         $this->delPic($imgs);
    //       }
    //     }
    //   }
    return $res;
  }

  /**
   * 获取链接id
   * @return int 链接对应的id
   * @author huajie <banhuajie@163.com>
   */
  protected function getLink(){
    $link = I('post.link_id');
    if(empty($link)){
      return 0;
    } else if(is_numeric($link)){
      return $link;
    }
    $res = D('Url')->update(array('url'=>$link));
    return $res['id'];
  }

  /**
   * 保存为草稿
   * @return array 完整的数据， false 保存出错
   * @author huajie <banhuajie@163.com>
   */
  public function autoSave(){
    $post = I('post.');

    /* 检查文档类型是否符合要求 */
    $res = $this->checkDocumentType( I('type'), I('pid') );
    if(!$res['status']){
      $this->error = $res['info'];
      return false;
    }

    //触发自动保存的字段
    $save_list = array('name','title','description','position','link_id','cover_id','deadline','create_time','content');
    foreach($save_list as $value){
      if(!empty($post[$value])){
        $if_save = true;
        break;
      }
    }

    if(!$if_save){
      $this->error = '您未填写任何内容';
      return false;
    }

    //重置自动验证
    $this->_validate = array(
    array('name', '/^[a-zA-Z]\w{0,39}$/', '文档标识不合法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
    array('title', '1,80', '标题长度不能超过80个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
    array('description', '1,140', '简介长度不能超过140个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
    array('category_id', 'require', '分类不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    array('category_id', 'checkCategory', '该分类不允许发布内容', self::EXISTS_VALIDATE , 'callback', self::MODEL_UPDATE),
    array('deadline', '/^\d{4,4}-\d{1,2}-\d{1,2}(\s\d{1,2}:\d{1,2}(:\d{1,2})?)?$/', '日期格式不合法,请使用"年-月-日 时:分"格式,全部为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_BOTH),
    array('create_time', '/^\d{4,4}-\d{1,2}-\d{1,2}(\s\d{1,2}:\d{1,2}(:\d{1,2})?)?$/', '日期格式不合法,请使用"年-月-日 时:分"格式,全部为数字', self::VALUE_VALIDATE  , 'regex', self::MODEL_BOTH),
    );
    $this->_auto[] = array('status', '3', self::MODEL_BOTH);

    if(!($data = $this->create())){
      return false;
    }

    /* 添加或新增基础内容 */
    if(empty($data['id'])){ //新增数据
      $id = $this->add(); //添加基础内容
      if(!$id){
        $this->error = '新增基础内容出错！';
        return false;
      }
      $data['id'] = $id;
    }else{ //更新数据
      $status = $this->save(); //更新基础内容
      if(false === $status){
        $this->error = '更新基础内容出错！';
        return false;
      }
    }

    /* 添加或新增扩展内容 */
    $logic = $this->logic($data['model_id']);
    if(!$logic->autoSave($id)){
      if(isset($id)){ //新增失败，删除基础数据
        $this->delete($id);
      }
      $this->error = $logic->getError();
      return false;
    }

    //内容添加或更新完成
    return $data;
  }

  /**
   * 获取目录列表
   * @param intger $pid 目录的根节点
   * @return boolean
   * @author huajie <banhuajie@163.com>
   */
  public function getDirectoryList($pid = null){
    if(empty($pid)){
      return false;
    }
    $tree = S('sys_directory_tree');
    if(empty($tree)){
      $res = $this->getChild($pid);
      S('sys_directory_tree', $tree);
    }
    return $res;
  }

  /**
   * 递归查询子文档
   * @param intger $pid
   * @return array: 子文档数组
   * @author huajie <banhuajie@163.com>
   */
  private function getChild($pid){
    $tree = array();
    $map = array('status'=>1,'type'=>1);
    if(is_array($pid)){
      $map['pid'] = array('in', implode(',', $pid));
    }else{
      $map['pid'] = $pid;
    }
    $child = $this->where($map)->field('id,name,title,pid')->order('level DESC,id DESC')->select();
    if(!empty($child)){
      foreach($child as $key=>$value){
        $pids[] = $value['id'];
      }
      $tree = array_merge($child, $this->getChild($pids));
    }
    return $tree;
  }

  /**
   * 检查指定文档下面子文档的类型
   * @param intger $type 子文档类型
   * @param intger $pid 父文档类型
   * @return array 键值：status=>是否允许（0,1），'info'=>提示信息
   * @author huajie <banhuajie@163.com>
   */
  public function checkDocumentType($type = null, $pid = null){
    $res = array('status'=>1, 'info'=>'');
    if(empty($type)){
      return array('status'=>0, 'info'=>'文档类型不能为空');
    }
    if(empty($pid)){
      return $res;
    }
    //查询父文档的类型
    if(is_numeric($pid)){
      $ptype = $this->getFieldById($pid, 'type');
    }else{
      $ptype = $this->getFieldByName($pid, 'type');
    }
    //父文档为目录时
    if($ptype == 1){
      return $res;
    }
    //父文档为主题时
    if($ptype == 2){
      if($type != 3){
        return array('status'=>0, 'info'=>'主题下面只允许添加段落');
      }else{
        return $res;
      }
    }
    //父文档为段落时
    if($ptype == 3){
      return array('status'=>0, 'info'=>'段落下面不允许再添加子内容');
    }
    return array('status'=>0, 'info'=>'父文档类型不正确');
  }
  
  /**
  * 更新搜索关键字索引
  * @version 2015071115
  * @author Justin
  */
  function updateSearchIndex(){
    $model = M('Search');
    //清除无效记录
    $model->where("keyword = '' or result=''")->delete();//execute("delete from `jipu_search` where keyword = '' or result='';");
    
    $lists = $model->select();
    foreach($lists as $v){
      $where['name'] = array('LIKE', '%'.$v['keyword'].'%');
      //查询商品表结果集
      $lists_item = M('Item')->field('id')->where($where)->select();
      $item_ids = get_sub_by_key($lists_item, 'id', '', true);
      //搜索结果加入索引
      $data = array(
        'id' => $v['id'],
        'result' => $item_ids,
      );
      $model->save($data);
    }
    return true;
  }
  
  /**
  * 验证分销值是否合理
  * @version 2015080517
  * @author Justin
  */
  function _checkSdp(){
    if(C('SDP_IS_OPEN')){
      //获取商品价格
      $price = I('post.spc_price') ? min(I('post.spc_price')) : I('post.price') ;
      $sdp_type = I('post.sdp_type');
      $sdp = I('post.sdp');
      if(1 == $sdp_type){
        //比例
        if($sdp > 100){
          return false;
        }
      }else{
        if(number_format($sdp, 2) > $price){
          return false;
        }
      }
    }
    return true;
  }
}
