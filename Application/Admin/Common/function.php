<?php
/**
 * 后台公共文件
 * 主要定义后台公共函数库
 */

/* 解析列表定义规则 */
function get_list_field($data, $grid){

  //获取当前字段数据
  foreach($grid['field'] as $field){
    $array = explode('|', $field);
    $temp = $data[$array[0]];
    //函数支持
    if(isset($array[1])){
      $temp = call_user_func($array[1], $temp);
    }
    $data2[$array[0]] = $temp;
  }
  if(!empty($grid['format'])){
    $value = preg_replace_callback('/\[([a-z_]+)\]/', function($match) use($data2){
      return $data2[$match[1]];
    }, $grid['format']);
  }else{
    $value = implode(' ', $data2);
  }

  //链接支持
  if('title' == $grid['field'][0] && '目录' == $data['type']){
    //目录类型自动设置子文档列表链接
    $grid['href'] = '[LIST]';
  }
  if(!empty($grid['href'])){
    $links = explode(',', $grid['href']);
    foreach($links as $link){
      $array = explode('|', $link);
      $href = $array[0];
      if(preg_match('/^\[([a-z_]+)\]$/', $href, $matches)){
        $val[] = $data2[$matches[1]];
      }else{
        $show = isset($array[1]) ? $array[1] : $value;
        //替换系统特殊字符串
        $href = str_replace(
            array('[DELETE]', '[EDIT]', '[LIST]'), array('setstatus?status=-1&ids=[id]',
          'edit?id=[id]&model=[model_id]&cate_id=[category_id]',
          'index?pid=[id]&model=[model_id]&cate_id=[category_id]'), $href);

        //替换数据变量
        $href = preg_replace_callback('/\[([a-z_]+)\]/', function($match) use($data){
          return $data[$match[1]];
        }, $href);

        $val[] = '<a href="'.U($href).'">'.$show.'</a>';
      }
    }
    $value = implode(' ', $val);
  }
  return $value;
}

/* 解析插件数据列表定义规则 */

function get_addonlist_field($data, $grid, $addon){
  //获取当前字段数据
  foreach($grid['field'] as $field){
    $array = explode('|', $field);
    $temp = $data[$array[0]];
    //函数支持
    if(isset($array[1])){
      $temp = call_user_func($array[1], $temp);
    }
    $data2[$array[0]] = $temp;
  }
  if(!empty($grid['format'])){
    $value = preg_replace_callback('/\[([a-z_]+)\]/', function($match) use($data2){
      return $data2[$match[1]];
    }, $grid['format']);
  }else{
    $value = implode(' ', $data2);
  }

  //链接支持
  if(!empty($grid['href'])){
    $links = explode(',', $grid['href']);
    foreach($links as $link){
      $array = explode('|', $link);
      $href = $array[0];
      if(preg_match('/^\[([a-z_]+)\]$/', $href, $matches)){
        $val[] = $data2[$matches[1]];
      }else{
        $show = isset($array[1]) ? $array[1] : $value;
        //替换系统特殊字符串
        $href = str_replace(
            array('[DELETE]', '[EDIT]', '[ADDON]'), array('del?ids=[id]&name=[ADDON]', 'edit?id=[id]&name=[ADDON]', $addon), $href);

        //替换数据变量
        $href = preg_replace_callback('/\[([a-z_]+)\]/', function($match) use($data){
          return $data[$match[1]];
        }, $href);

        $val[] = '<a href="'.U($href).'">'.$show.'</a>';
      }
    }
    $value = implode(' ', $val);
  }
  return $value;
}

/**
 * 获取对应状态的文字信息
 * @param int $status
 * @return string 状态文字 ，false 未获取到
 * @author huajie <banhuajie@163.com>
 */
function get_status_title($status = null){
  if(!isset($status)){
    return false;
  }
  switch($status){
    case -1 : return '已删除';
      break;
    case 0 : return '禁用';
      break;
    case 1 : return '正常';
      break;
    case 2 : return '待审核';
      break;
    default : return false;
      break;
  }
}

//获取数据的状态操作
function show_status_op($status){
  switch($status){
    case 0 : return '启用';
      break;
    case 1 : return '禁用';
      break;
    case 2 : return '审核';
      break;
    default : return false;
      break;
  }
}

/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type = 0){
  $list = C('CONFIG_TYPE_LIST');
  return $list[$type];
}

/**
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group = 0){
  $list = C('CONFIG_GROUP_LIST');
  return $group ? $list[$group] : '';
}

/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map  映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @author 朱亚杰 <zhuyajie@topthink.net>
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data, $map = array('status' => array(1 => '<span class="text-success">正常</span>', -1 => '删除', 0 => '<span class="text-cancel">禁用</span>', 2 => '未审核', 3 => '草稿'))){
  if($data === false || $data === null){
    return $data;
  }
  $data = (array) $data;
  foreach($data as $key => $row){
    foreach($map as $col => $pair){
      if(isset($row[$col]) && isset($pair[$row[$col]])){
        $data[$key][$col.'_text'] = $pair[$row[$col]];
      }
    }
  }
  return $data;
}

/**
 * 动态扩展左侧菜单,base.html里用到
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
function extra_menu($extra_menu, &$base_menu){
  foreach($extra_menu as $key => $group){
    if(isset($base_menu['child'][$key])){
      $base_menu['child'][$key] = array_merge($base_menu['child'][$key], $group);
    }else{
      $base_menu['child'][$key] = $group;
    }
  }
}

/**
 * 获取参数的所有父级分类
 * @param int $cid 分类id
 * @return array 参数分类和父类的信息集合
 * @author huajie <banhuajie@163.com>
 */
function get_parent_category($cid){
  if(empty($cid)){
    return false;
  }
  $cates = M('Category')->where(array('status' => 1))->field('id,title,pid')->order('sort')->select();
  $child = get_category($cid); //获取参数分类的信息
  $pid = $child['pid'];
  $temp = array();
  $res[] = $child;
  while(true){
    foreach($cates as $key => $cate){
      if($cate['id'] == $pid){
        $pid = $cate['pid'];
        array_unshift($res, $cate); //将父分类插入到数组第一个元素前
      }
    }
    if($pid == 0){
      break;
    }
  }
  return $res;
}

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
  $verify = new \Think\Verify();
  return $verify->check($code, $id);
}

//分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string){
  $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
  if(strpos($string, ':')){
    $value = array();
    foreach($array as $val){
      list($k, $v) = explode(':', $val);
      $value[$k] = $v;
    }
  }else{
    $value = $array;
  }
  return $value;
}

//分析枚举类型字段值 格式 a:名称1,b:名称2
//暂时和 parse_config_attr功能相同
//但请不要互相使用，后期会调整
function parse_field_attr($string){
  if(0 === strpos($string, ':')){
    //采用函数定义
    return eval('return '.substr($string, 1).';');
  }elseif(0 === strpos($string, '[')){
    //支持读取配置参数（必须是数组类型）
    return C(substr($string, 1, -1));
  }

  $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
  if(strpos($string, ':')){
    $value = array();
    foreach($array as $val){
      list($k, $v) = explode(':', $val);
      $value[$k] = $v;
    }
  }else{
    $value = $array;
  }
  return $value;
}

/**
 * 获取行为数据
 * @param string $id 行为id
 * @param string $field 需要获取的字段
 * @author huajie <banhuajie@163.com>
 */
function get_action($id = null, $field = null){
  if(empty($id) && !is_numeric($id)){
    return false;
  }
  $list = S('action_list');
  if(empty($list[$id])){
    $map = array('status' => array('gt', -1), 'id' => $id);
    $list[$id] = M('Action')->where($map)->field(true)->find();
  }
  return empty($field) ? $list[$id] : $list[$id][$field];
}

/**
 * 获取行为类型
 * @param intger $type 类型
 * @param bool $all 是否返回全部类型
 * @author huajie <banhuajie@163.com>
 */
function get_action_type($type, $all = false){
  $list = array(
    1 => '系统',
    2 => '用户',
  );
  if($all){
    return $list;
  }
  return $list[$type];
}

/**
 * 获取商品的状态操作
 * @author Max.Yu <max@jipu.com>
 */
function get_item_status_title($status = null){
  if(!isset($status)){
    return false;
  }

  switch($status){
    case 1 : return '上架';
      break;
    case 0 : return '下架';
      break;
    default : return false;
      break;
  }
}

/**
 * 生成订单号
 * 格式：2014022710050578
 * Gets a prefixed unique identifier based on the current time in microseconds.
 * @author Max.Yu <max@jipu.com>
 */
function build_order_sn(){
  return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/**
 * 按时间精确到毫秒+uniqid生成SN号
 * 格式：2014022710050578
 * Gets a prefixed unique identifier based on the current time in microseconds.
 * @author Max.Yu <max@jipu.com>
 */
function build_sn(){
  return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/**
 * 生成商品序列号
 * 格式：DD931A971596273B
 * @author Max.Yu <max@jipu.com>
 */
function build_goods_sn($len){
  $uniqid = strtoupper(md5(uniqid()));
  $uniqid = substr($uniqid, 0, $len);
  return $uniqid;
}

/**
 * 生成礼品卡卡号
 * 格式：DD931A971596273B
 * @author Max.Yu <max@jipu.com>
 */
function build_card_num($len, $i){
  $card_num = md5(uniqid().$i);
  $card_num = substr($card_num, 0, $len);
  return $card_num;
}

/**
 * 生成礼品卡密码
 * 格式：DD931A971596273B
 * @author Max.Yu <max@jipu.com>
 */
function build_card_pwd($len, $i){
  $card_pwd = md5(uniqid().$i);
  $card_pwd = substr($card_pwd, 0, $len);
  return $card_pwd;
}

/**
 * 生成唯一标识码
 * 格式：DD931A971596273B
 * @author Max.Yu <max@jipu.com>
 */
function build_unique_code($len, $i){
  $unique_code = md5(uniqid().$i);
  $unique_code = substr($unique_code, 0, $len);
  return $unique_code;
}

/**
 * 获取订单项目信息
 * @author Max.Yu <max@jipu.com>
 */
function get_order_item($order_id){
  $order_item = array();
  if(empty($order_id) || is_nan($order_id)){
    return false;
  }

  //实例化订单项目模型
  $order_model = M('OrderItem');

  //定义返回或者操作的字段
  $field = '*, (price * quantity) as subtotal';

  //查询条件初始化
  $where['order_id'] = array('in', $order_id);

  //获取订单项目列表
  $order_item = $order_model->where($where)->order('id asc')->field($field)->select();

  foreach($order_item as $key => &$value){
    $value['spec'] = custom_unserialize($value['spec'], 1, '，');
    //规格图片
    $pic = M('PropertyOption')->getFieldByCode($value['item_code'], 'pic');
    //不存在则为封面图片
    $pic && $value['thumb'] = $pic;
  }

  return $order_item;
}

/**
 * 统计订单商品项目总数量（total_quantity）
 * @author Max.Yu <max@jipu.com>
 */
function count_order_item($order_id){
  $result = array(
    'total_quantity' => 0,
  );

  if(empty($order_id) || is_nan($order_id)){
    return $result;
  }

  //实例化订单项目模型
  $order_item_model = M('OrderItem');

  //定义返回或者操作的字段
  $field = 'SUM(quantity) AS total_quantity';

  //查询条件初始化
  $where['order_id'] = array('in', $order_id);;

  //获取订单数量统计信息
  $data = $order_item_model->where($where)->field($field)->select();
  if($data[0]['total_quantity']){
    $result['total_quantity'] = $data[0]['total_quantity'];
  }
  return $result;
}

/**
 * 生成商品/优惠券等唯一编号
 * 格式：1404306C49
 * @author Max.Yu <max@jipu.com>
 */
function create_uniqid_sn($len){
  $uniqid = strtoupper(md5(uniqid()));
  $uniqid = substr($uniqid, 0, $len);
  return date('Ymd').$uniqid;
}

/* * ****************************** */
/* 以下方法检查是否使用，清理 */
/* * ******************************* */

/*
 * 获取待申请发票订单明细
 * @author Max.Yu <max@jipu.com>
 */

function getOrderItemForInvoice($order_id){
  $orderItemInfo = array();
  $returnInfo = null;

  if(empty($order_id) || is_numeric($order_id)){
    return $returnInfo;
  }

  //实例化订单项目模型
  $OrderItem = M('OrderItem');

  //定义返回或者操作的字段
  $field = 'id, order_id, name, spec, price, quantity';

  //查询条件初始化
  $where['order_id'] = $order_id;

  //获取订单项目列表
  $orderItemInfo = $OrderItem->where($where)->order('id desc')->field($field)->select();

  foreach($orderItemInfo as $item){
    //更新商品库存数
    $returnInfo = $returnInfo.'<p id=order_'.$item['order_id'].'>';
    //$returnInfo = $returnInfo.'<input class="itemids" type="checkbox" name="itemids[]" value="'.$item['id'].'" /> ';
    $returnInfo = $returnInfo.$item['name'].'（'.custom_unserialize($item['spec'], 2, '，').'） &nbsp;&nbsp;单价：'.$item['price'].' &nbsp;&nbsp;数量：'.$item['quantity'];
    $returnInfo = $returnInfo.'</p>';
  }

  return $returnInfo;
}

/*
 * 多值存储格式化，为转数组做准备
 * @author Max.Yu <max@jipu.com>
 */

function format_for_arr($data){
  $returnInfo = '';

  //设置搜索回车换行符
  $search = array("\r\n", "\n", "\r");

  //回车换行符替换成英文逗号","，方便转换成数组
  $returnInfo = str_replace($search, ',', $data);

  //中文逗号"，"替换成英文逗号","，方便转换成数组
  $returnInfo = str_replace('，', ',', $returnInfo);

  //处理回车换行产生的双逗号
  $returnInfo = str_replace(',,', ',', $returnInfo);

  //去除空数组
  $returnInfo = arr2str(array_filter(str2arr($returnInfo)));

  return $returnInfo;
}

function json_encode_cn($data){
  $data = json_encode($data);
  return preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H*', '$1'));", $data);
}

/*
 * 获取商品分类下拉列表
 * @author Max.Yu <max@jipu.com>
 */

function getCategorySelect(){
  $orderItemInfo = array();
  $returnInfo = null;

  //实例化商品分类模型
  $ItemCategory = M('ItemCategory');

  //查询条件初始化
  $where['status'] = 1;
  $where['pid'] = 0;

  //定义返回或者操作的字段
  $field = 'id, pid, name';

  //定义排序条件
  $order = 'sort asc, id asc';

  //获取一级分类列表
  $firstCat = $ItemCategory->where($where)->order($order)->field($field)->select();

  $returnInfo = $returnInfo.'<select name="cid">';
  foreach($firstCat as $cat_1){
    $returnInfo = $returnInfo.'<option value="'.$cat_1['id'].'">'.$cat_1['name'].'</option>';

    //获取二级分类列表
    $where['pid'] = $cat_1['id'];
    $secondCat = $ItemCategory->where($where)->order($order)->field($field)->select();
    if(!empty($secondCat)){
      foreach($secondCat as $cat_2){
        $returnInfo = $returnInfo.'<option value="'.$cat_2['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;|—'.$cat_2['name'].'</option>';

        //获取三级分类列表
        $where['pid'] = $cat_2['id'];
        $thirdCat = $ItemCategory->where($where)->order($order)->field($field)->select();
        if(!empty($thirdCat)){
          foreach($thirdCat as $cat_3){
            $returnInfo = $returnInfo.'<option value="'.$cat_3['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|—'.$cat_3['name'].'</option>';
          }
        }
      }
    }
  }
  $returnInfo = $returnInfo.'</select>';

  return $returnInfo;
}

/*
 * 获取商品分类名称
 * @author Max.Yu <max@jipu.com>
 */

function getCategoryNameById($cid = null){
  if(empty($cid)){
    return false;
  }

  $categoryName = M('ItemCategory')->where('id='.$cid)->getField('name');
  return $categoryName;
}

/*
 * 格式化属性值为英文逗号隔开的字符串
 * @author Max.Yu <max@jipu.com>
 */

function formatPropertyValue($property_value = null, $type = null){
  $return_info = '';

  if($property_value){
    $return_info = $property_value[$type];

    if(is_array($return_info)){
      $return_info = implode(',', $return_info);
    }
  }

  return $return_info;
}

/**
 * 利用PHPExcel生成excel文件
 * @param string  $fileName 文件名
 * @param array $headArr  表头
 * @param array $data   导出数据
 * @author Max.Yu <max@jipu.com>
 * 备注：导出数据二维数组的元素个数需与表头数组的元素个数一致
 */
function createExcel($fileName, $headArr, $data, $widthArr = null){
  //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
  import("Org.PHPExcel.PHPExcel", '', '.php');
  import("Org.PHPExcel.PHPExcel.Writer.Excel5", '', '.php');
  import("Org.PHPExcel.PHPExcel.IOFactory", '', '.php');
  import("Org.PHPExcel.PHPExcel.Style.Alignment", '', '.php');

  //对数据进行检验
  if(empty($data) || !is_array($data)){
    die("data must be a array");
  }

  //检查文件名
  if(empty($fileName)){
    exit;
  }

  $date = date("Y_m_d", time());
  $fileName .= "_{$date}.xls";

  //创建PHPExcel对象，注意不能少了"\"
  $objPHPExcel = new \PHPExcel();

  //$objProps = $objPHPExcel->getProperties();
  //设置表头行高
  $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);

  //设置表头
  $key = ord("A");
  foreach($headArr as $v){
    $column = chr($key);

    //设置字体：加粗
    $objPHPExcel->getActiveSheet()->getStyle($column.'1')->getFont()->setBold(true);

    //设置列宽
    $width = (isset($widthArr) && isset($widthArr[$column])) ? intval($widthArr[$column]) : 20;

    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($width);

    //设置水平对齐：居左
    $objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal('left');

    //设置垂直对齐：居中
    $objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setVertical('center');
    $objPHPExcel->getActiveSheet()->getStyle($column.'1')->getAlignment()->setVertical('center');

    //设置表头单元格字符
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.'1', $v);

    $key += 1;
  }

  //准备导出写入的数据
  $rownum = 2;
  $objActSheet = $objPHPExcel->getActiveSheet();
  foreach($data as $key => $rows){ //行写入
    //设置行高
    $objPHPExcel->getActiveSheet()->getRowDimension($rownum)->setRowHeight(28);

    $span = ord("A");
    foreach($rows as $keyName => $value){//列写入
      $j = chr($span);
      $objActSheet->setCellValue($j.$rownum, $value);
      $span++;
    }
    $rownum++;
  }

  //处理文件名中文乱码问题
  //$fileName = iconv("utf-8", "gb2312", $fileName);
  //sheet命名
  $objPHPExcel->getActiveSheet()->setTitle($fileName);

  //设置活动单元表
  $objPHPExcel->setActiveSheetIndex(0);

  //设置HTTP报头
  header('Content-Type: application/vnd.ms-excel');
  header("Content-Disposition: attachment;filename=\"$fileName\"");
  header('Cache-Control: max-age=0');

  //写入导出数据
  $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

  //生成的excel文件通过浏览器下载
  $objWriter->save('php://output');
  exit;
}

/**
 * 根据商品ID获取商品名称
 * @param  integer $id  商品id
 * @return string       商品名称
 * @author Justin <justin@jipu.com>
 */
function get_item_name($id){
  if($id){
    return M('item')->where('id='.$id)->getField('name');
  }
}

/**
 * 获取商品评价回复
 * @param  integer $id  商品评价id
 * @author Justin <justin@jipu.com>
 */
function get_item_comment_reply($id = null){
  if($id){
    return M('ItemComment')->where('pid='.$id)->find();
  }
}

/**
 * 判断区域是否有下级
 * @param  integer $pid  区域id
 * @return integer 0无 1有 
 * @version 2015070611
 * @author Justin <justin@jipu.com>
 */
function check_area_child($pid = 0){
  if($pid){
    return M('Area')->field('id')->where('pid='.$pid)->find() ? 1 : 0;
  }
}

/**
 * 获取前台用户组人数
 */
function getUserGroupCount($group_id = 0){
  return M('User')->where(array('group_id' => $group_id))->count();
}

/**
 * 获取供应商商品数量
 */
function get_supplier_item_count($supplier_id = 0){
  return M('Item')->where(array('supplier_id' => $supplier_id, 'status' => array('egt', 0)))->count();
}

/**
 * 获取推广联盟总关注数和总订单数
 */
function get_union_count($type = 'Subscribe', $uid, $start_time = null, $end_time = null){
  if($uid){
    $data = A('Home/Union', 'Event')->getCountData($type, $uid, $start_time, $end_time);
    return $data['total'] ? $data['total'] : 0;
  }
}

/**
 * 获取字段值
 */
function get_field($id = 0, $model, $field = 'name'){
  if($id && $model){
    return M($model)->where(array('id' => $id))->getField($field);
  }
}
function join_count($id){
  return M('Join_order')->where('join_id='.$id)->count();
}
function unionsbyid($uid){
  return M('Distributlog')->where('uid='.$uid)->count();
}