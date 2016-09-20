<?php
/**
 * 系统公共库文件
 * 主要定义系统公共函数库
 */

/**
 * OneThink常量定义
 */
const ONETHINK_VERSION = '3.0.15091115';
const ONETHINK_ADDON_PATH = './Addons/';

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login(){
  $user = session('user_auth');
  if(empty($user)){
    return 0;
  }else{
    return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
  }
}


/**
 * 返回秒杀缓存目录
 * @return string
 */
function invoice_html_path(){
    $p = DIRECTORY_SEPARATOR;
    $file=str_replace($p.'Application'.$p.'Common'.$p.'Common','',dirname(__FILE__)).$p.'html'.$p;
    return $file;
}


/**
 * 去重函数
 * @return string
 */
function getUniqure($arr){
    return array_flip(array_flip($arr));
}

/**
 * 记录日志
 * @param string  $path     文件名称
 * @param unknown $data     数据
 * @param unknown $reback   备注
 */
function set_log($path='',$data, $reback){
  $p = DIRECTORY_SEPARATOR;
  $file=str_replace($p.'Application'.$p.'Common'.$p.'Common','',dirname(__FILE__)).$p.'log'.$p.$path.'.txt';
  $content=file_get_contents($file);
  $content.=date('Y-m-d H:i:s',time())."\n";
  $content.=$reback."\n";
  $content.=var_export($data,true)."\n\r";
  file_put_contents($file,$content);
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_administrator($uid = null){
  $uid = is_null($uid) ? is_login() : $uid;
  return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}

/**
 * 检测当前用户是否为开发者：不受权限认证约束
 * @return boolean true-开发者，false-非开发者
 * @author Max.Yu <max@jipu.com>
 */
function is_developer(){
  $user_auth = session('user_auth');
  if(empty($user_auth)){
    return false;
  }else{
    return C('DEVELOP_MODE') && ($user_auth['email'] === C('DEVELOP_MODE_CONFIG.EMAIL'));
  }
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param string $str  要分割的字符串
 * @param string $glue 分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ','){
  return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param array  $arr  要连接的数组
 * @param string $glue 分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ','){
  return implode($glue, $arr);
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true){
  if(function_exists("mb_substr")){
    $slice = mb_substr($str, $start, $length, $charset);
  }elseif(function_exists('iconv_substr')){
    $slice = iconv_substr($str, $start, $length, $charset);
    if(false === $slice){
      $slice = '';
    }
  }else{
    $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("", array_slice($match[0], $start, $length));
  }
  return $suffix ? $slice.'...' : $slice;
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_encrypt($data, $key = '', $expire = 0){
  $key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
  $data = base64_encode($data);
  $x = 0;
  $len = strlen($data);
  $l = strlen($key);
  $char = '';

  for($i = 0; $i < $len; $i++){
    if($x == $l)
      $x = 0;
    $char .= substr($key, $x, 1);
    $x++;
  }

  $str = sprintf('%010d', $expire ? $expire + time() : 0);

  for($i = 0; $i < $len; $i++){
    $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
  }
  return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
}

/**
 * 系统解密方法
 * @param string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param string $key  加密密钥
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_decrypt($data, $key = ''){
  $key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
  $data = str_replace(array('-', '_'), array('+', '/'), $data);
  $mod4 = strlen($data) % 4;
  if($mod4){
    $data .= substr('====', $mod4);
  }
  $data = base64_decode($data);
  $expire = substr($data, 0, 10);
  $data = substr($data, 10);

  if($expire > 0 && $expire < time()){
    return '';
  }
  $x = 0;
  $len = strlen($data);
  $l = strlen($key);
  $char = $str = '';

  for($i = 0; $i < $len; $i++){
    if($x == $l)
      $x = 0;
    $char .= substr($key, $x, 1);
    $x++;
  }

  for($i = 0; $i < $len; $i++){
    if(ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))){
      $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
    }else{
      $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
    }
  }
  return base64_decode($str);
}

/**
 * 系统非常规MD5加密方法，加密key已写死，采用配置文件中的DATA_AUTH_KEY
 * @param string $str 要加密的字符串
 * @return string
 */
function think_ucenter_md5($str, $key = ''){
  return '' === $str ? '' : md5(sha1($str).C('DATA_AUTH_KEY'));
}

/**
 * 数据签名认证
 * @param array $data 被认证的数据
 * @return string 签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data){
  //数据类型检测
  if(!is_array($data)){
    $data = (array) $data;
  }
  ksort($data); //排序
  $code = http_build_query($data); //url编码并生成query字符串
  $sign = sha1($code); //生成签名
  return $sign;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc'){
  if(is_array($list)){
    $refer = $resultSet = array();
    foreach($list as $i => $data)
      $refer[$i] = &$data[$field];
    switch($sortby){
      case 'asc': // 正向排序
        asort($refer);
        break;
      case 'desc':// 逆向排序
        arsort($refer);
        break;
      case 'nat': // 自然排序
        natcasesort($refer);
        break;
    }
    foreach($refer as $key => $val)
      $resultSet[] = &$list[$key];
    return $resultSet;
  }
  return false;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0){
  //创建Tree
  $tree = array();
  if(is_array($list)){
    //创建基于主键的数组引用
    $refer = array();
    foreach($list as $key => $data){
      $refer[$data[$pk]] = & $list[$key];
    }
    foreach($list as $key => $data){
      //判断是否存在parent
      $parentId = $data[$pid];
      if($root == $parentId){
        $tree[] = & $list[$key];
      }else{
        if(isset($refer[$parentId])){
          $parent = & $refer[$parentId];
          $parent[$child][] = & $list[$key];
        }
      }
    }
  }
  return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 * @param array $tree  原来的树
 * @param string $child 孩子节点的键
 * @param string $order 排序显示的键，一般是主键 升序排列
 * @param array $list 过渡用的中间数组，
 * @return array 返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array()){
  if(is_array($tree)){
    foreach($tree as $key => $value){
      $reffer = $value;
      if(isset($reffer[$child])){
        unset($reffer[$child]);
        tree_to_list($value[$child], $child, $order, $list);
      }
      $list[] = $reffer;
    }
    $list = list_sort_by($list, $order, $sortby = 'asc');
  }
  return $list;
}

/**
 * 格式化字节大小
 * @param number $size 字节数
 * @param string $delimiter 数字和单位分隔符
 * @return string 格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = ''){
  $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
  for($i = 0; $size >= 1024 && $i < 5; $i++)
    $size /= 1024;
  return round($size, 2).$delimiter.$units[$i];
}

/**
 * 设置跳转页面URL
 * 使用函数再次封装，方便以后选择不同的存储方式（目前使用cookie存储）
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function set_redirect_url($url){
  cookie('redirect_url', $url);
}

/**
 * 获取跳转页面URL
 * @return string 跳转页URL
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_redirect_url(){
  $url = cookie('redirect_url');
  return empty($url) ? __APP__ : $url;
}

/**
 * 处理插件钩子
 * @param string $hook 钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook, $params = array()){
  \Think\Hook::listen($hook, $params);
}

/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name){
  $class = "Addons\\{$name}\\{$name}Addon";
  return $class;
}

/**
 * 获取插件类的配置文件数组
 * @param string $name 插件名
 */
function get_addon_config($name){
  $class = get_addon_class($name);
  if(class_exists($class)){
    $addon = new $class();
    return $addon->getConfig();
  }else{
    return array();
  }
}

/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function addons_url($url, $param = array()){
  $url = parse_url($url);
  $case = C('URL_CASE_INSENSITIVE');
  $addons = $case ? parse_name($url['scheme']) : $url['scheme'];
  $controller = $case ? parse_name($url['host']) : $url['host'];
  $action = trim($case ? strtolower($url['path']) : $url['path'], '/');

  //解析URL带的参数
  if(isset($url['query'])){
    parse_str($url['query'], $query);
    $param = array_merge($query, $param);
  }

  //基础参数
  $params = array(
    '_addons' => $addons,
    '_controller' => $controller,
    '_action' => $action,
  );
  $params = array_merge($params, $param); //添加额外参数

  return U('Addons/execute', $params);
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author huajie <banhuajie@163.com>
 */
function time_format($time = NULL, $format = 'Y-m-d H:i'){
  $time = empty($time) ? NOW_TIME : intval($time);
  return date($format, $time);
}

/**
 * 根据用户ID获取用户名
 * @param integer $uid 用户ID
 * @return string       用户名
 */
function get_username($uid = 0){
  static $list;
  if(!($uid && is_numeric($uid))){ //获取当前登录用户名
    return session('user_auth.nickname') ? session('user_auth.nickname') : get_hidden_mobile(session('user_auth.mobile'));
  }

  //获取缓存数据
  if(empty($list)){
    $list = S('sys_active_user_list');
  }

  //查找用户信息
  $key = "u{$uid}";
  if(isset($list[$key])){ //已缓存，直接使用
    $name = $list[$key];
  }else{ //调用接口获取用户信息
    $user_api = new Common\Api\UserApi();
    $info = $user_api->info($uid);
    if($info && isset($info['username'])){
      $name = $list[$key] = $info['username'];
      //缓存用户
      $count = count($list);
      $max = C('USER_MAX_CACHE');
      while($count-- > $max){
        array_shift($list);
      }
      S('sys_active_user_list', $list);
    }else{
      $name = '';
    }
  }
  return $name;
}
/**
 * 根据用户ID推广用户订单数
 * @param integer $uid 用户ID
 * @return string       用户昵称
 */
function get_unionorder($uid = 0){
  return M('Distributlog')->where('uid='.$uid)->count();
}
/**
 * 根据用户ID获取用户昵称
 * @param integer $uid 用户ID
 * @return string       用户昵称
 */
function get_nickname($uid = 0){

  static $list;
  if(!($uid && is_numeric($uid))){ //获取当前登录用户名
    return session('user_auth.nickname') ? session('user_auth.nickname') : get_hidden_mobile(session('user_auth.mobile'));
  }

  //获取缓存数据
  if(empty($list)){
    $list = S('sys_user_nickname_list');
  }
  //查找用户信息
  $key = "u{$uid}";
  if(isset($list[$key])){ //已缓存，直接使用
    $name = $list[$key];
  }else{ //调用接口获取用户信息
    $info = M('Member')->field('nickname')->find($uid);

    if($info !== false && $info['nickname']){
      $nickname = $info['nickname'];
      $name = $list[$key] = $nickname;
      //缓存用户
      $count = count($list);
      $max = C('USER_MAX_CACHE');
      while($count-- > $max){
        array_shift($list);
      }
      S('sys_user_nickname_list', $list);
    }else{
      $user = M('User')->field('username, mobile')->find($uid);
      $name = $user ? ($user['username'] ? $user['username'] : $user['mobile']) : '';
    }
  }
  return get_hidden_mobile($name);
}

/**
 * 根据用户ID获取用户头像
 * @param integer $uid 用户ID
 * @return string 用户头像
 */
function get_avatar($uid = 0){
  static $list;
  if(!($uid && is_numeric($uid))){ //获取当前登录用户名
    return session('user_auth.avatar');
  }

  //获取缓存数据
  if(empty($list)){
    $list = S('sys_user_avatar_list');
  }

  //查找用户信息
  $key = "u{$uid}";
  if(isset($list[$key])){ //已缓存，直接使用
    $result = $list[$key];
  }else{ //调用接口获取用户信息
    $info = M('Member')->field('avatar')->find($uid);
    if($info !== false && $info['avatar']){
      $avatar = $info['avatar'];
      $result = $list[$key] = $avatar;
      //缓存用户
      $count = count($list);
      $max = C('USER_MAX_CACHE');
      while($count-- > $max){
        array_shift($list);
      }
      S('sys_user_avatar_list', $list);
    }else{
      $result = '';
    }
  }
  return $result;
}

/**
 * 获取分类信息并缓存分类
 * @param integer $id    分类ID
 * @param string  $field 要获取的字段名
 * @return string         分类信息
 */
function get_category($id, $field = null){
  static $list;

  //非法分类ID
  if(empty($id) || !is_numeric($id)){
    return '';
  }

  //读取缓存数据
  if(empty($list)){
    $list = S('sys_category_list');
  }

  //获取分类名称
  if(!isset($list[$id])){
    $model = M('ArticleCategory');
    $cate = $model->find($id);
    if(!$cate || 1 != $cate['status']){ //不存在分类，或分类被禁用
      return '';
    }
    $list[$id] = $cate;
    S('sys_article_category_list', $list); //更新缓存
  }
  return is_null($field) ? $list[$id] : $list[$id][$field];
}

/**
 * 根据ID获取分类名称
 * @param int $cid 分类ID
 */
function get_category_name($cid){
  if(empty($cid)){
    return '';
  }
  if(is_array($cid)){
    foreach($cid as $v){
      $new_cids[$v] = array('id' => $v, 'name' => get_category($v, 'name'));
    }
    return $new_cids;
  }else{
    return get_category($cid, 'name');
  }
}

/**
 * 根据ID获取分类名称
 */
function get_category_title($id){
  return get_category($id, 'title');
}

/**
 * 获取顶级模型信息
 */
function get_top_model($model_id = null){
  $map = array('status' => 1, 'extend' => 0);
  if(!is_null($model_id)){
    $map['id'] = array('neq', $model_id);
  }
  $model = M('Model')->where($map)->field(true)->select();
  foreach($model as $value){
    $list[$value['id']] = $value;
  }
  return $list;
}

/**
 * 获取文档模型信息
 * @param integer $id 模型ID
 * @param string $field 模型字段
 * @return array
 */
function get_document_model($id = null, $field = null){
  static $list;

  //非法分类ID
  if(!(is_numeric($id) || is_null($id))){
    return '';
  }

  //读取缓存数据
  if(empty($list)){
    $list = S('DOCUMENT_MODEL_LIST');
  }

  //获取模型名称
  if(empty($list)){
    $map = array('status' => 1, 'extend' => 1);
    $model = M('Model')->where($map)->field(true)->select();
    foreach($model as $value){
      $list[$value['id']] = $value;
    }
    S('DOCUMENT_MODEL_LIST', $list); //更新缓存
  }

  //根据条件返回数据
  if(is_null($id)){
    return $list;
  }elseif(is_null($field)){
    return $list[$id];
  }else{
    return $list[$id][$field];
  }
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param string $record_id 触发行为的记录ids
 * @param int $user_id 执行行为的用户id
 * @param string $callback 行为回调函数
 * @return boolean
 * @author huajie <banhuajie@163.com>
 * 20150528 justin 增加行为回调函数
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null, $callback = null){

  //参数检查
  if(empty($action) || empty($model) || empty($record_id)){
    return '参数不能为空';
  }
  if(empty($user_id)){
    $user_id = is_login();
  }

  //查询行为,判断是否执行
  $action_info = M('Action')->getByName($action);
  if($action_info['status'] != 1){
    return '该行为被禁用或删除';
  }

  //插入行为日志
  $data['action_id'] = $action_info['id'];
  $data['user_id'] = $user_id;
  $data['action_ip'] = ip2long(get_client_ip());
  $data['model'] = $model;
  $data['record_id'] = $record_id;
  $data['create_time'] = NOW_TIME;

  //解析日志规则,生成日志备注
  if(!empty($action_info['log'])){
    if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
      $log['user'] = $user_id;
      $log['record'] = $record_id;
      $log['model'] = $model;
      $log['time'] = NOW_TIME;
      $log['data'] = array('user' => $user_id, 'model' => $model, 'record' => $record_id, 'time' => NOW_TIME);
      foreach($match[1] as $value){
        $param = explode('|', $value);
        if(isset($param[1])){
          $replace[] = call_user_func($param[1], $log[$param[0]]);
        }else{
          $replace[] = $log[$param[0]];
        }
      }
      $data['remark'] = str_replace($match[0], $replace, $action_info['log']);
    }else{
      $data['remark'] = $action_info['log'];
    }
  }else{
    //未定义日志规则，记录操作url
    $data['remark'] = '操作url：'.$_SERVER['REQUEST_URI'];
  }

  $logid = M('ActionLog')->add($data);

  if(!empty($action_info['rule'])){
    //解析行为
    $rules = parse_action($action, $user_id);
    //执行行为
    $res = execute_action($rules, $action_info['id'], $user_id,$logid);
    //执行行为回调
    if($res && function_exists($callback)){
      $callback($user_id);
    }
  }
}

/**
 * 用户注册行为(user_reg)回调，记录新用户奖励日志
 * @param int $uid UID
 * @return void 
 * @author justin <justin@jipu.com>
 */
function user_reg_callback($uid = null){
  if($uid){
    $finance_data = array(
      'uid' => $uid,
      'type' => 'website',
      'amount' => 5,
      'flow' => 'in',
      'create_time' => NOW_TIME,
      'memo' => '新注册用户奖励'
    );
    M('Finance')->add($finance_data);
  }
}

/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author huajie <banhuajie@163.com>
 */
function parse_action($action = null, $self){
  if(empty($action)){
    return false;
  }

  //参数支持id或者name
  if(is_numeric($action)){
    $map = array('id' => $action);
  }else{
    $map = array('name' => $action);
  }

  //查询行为信息
  $info = M('Action')->where($map)->find();
  if(!$info || $info['status'] != 1){
    return false;
  }

  //解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
  $rules = $info['rule'];
  $rules = str_replace('{$self}', $self, $rules);
  $rules = str_replace('{$time}', NOW_TIME,$rules);
  $rules = explode(';', $rules);
  $return = array();
  foreach($rules as $key => &$rule){
    $rule=trim($rule);
    $rule = explode('|', $rule);
    foreach($rule as $k => $fields){
      $field = empty($fields) ? array() : explode(':', $fields);
      if(!empty($field)){
        $return[$key][$field[0]] = $field[1];
      }
    }
    //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
    if(!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])){
      unset($return[$key]['cycle'], $return[$key]['max']);
    }
  }
  return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $user_id 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author huajie <banhuajie@163.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null ,$logid = null){
  if(!$rules || empty($action_id) || empty($user_id) || empty($logid)){
    return false;
  }
  $return = true;
  foreach($rules as $rule){
    //检查执行周期
    $map = array('action_id' => $action_id, 'user_id' => $user_id , 'exc_status' => 1);
    $time = ($action_id == 1 ) ? strtotime(date('Y-m-d'))  : NOW_TIME - intval($rule['cycle']) * 3600 ;
    $map['create_time'] = array('gt', $time);
    $exec_count = M('ActionLog')->where($map)->count();
    $rule['max']=empty($rule['max'])?0:intval($rule['max']);
    if($exec_count >= $rule['max']){
      continue;
    }
    //执行数据库操作
    $Model = M(ucfirst($rule['table']));
    if($rule['method']=='add'){
      $field=explode(',',$rule['field']);
      $value=explode(',',$rule['value']);
      $row=array();
      for($i=0;$i<count($field);$i++){
        $row[$field[$i]]=$value[$i];
      }
      if(!empty($row)){
        if($Model->add($row)){
          $data['exc_status'] =1 ;
          M('ActionLog')->where('id ='.$logid)->save($data);
        }
      }else{
        $return = false;
      }
    }else{
      $field = $rule['field'];
      $res = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));
      if(!$res){
        $return = false;
      }
    }
  }
  return $return;
}

//基于数组创建目录和文件
function create_dir_or_files($files){
  foreach($files as $key => $value){
    if(substr($value, -1) == '/'){
      mkdir($value);
    }else{
      @file_put_contents($value, '');
    }
  }
}

if(!function_exists('array_column')){

  function array_column(array $input, $columnKey, $indexKey = null){
    $result = array();
    if(null === $indexKey){
      if(null === $columnKey){
        $result = array_values($input);
      }else{
        foreach($input as $row){
          $result[] = $row[$columnKey];
        }
      }
    }else{
      if(null === $columnKey){
        foreach($input as $row){
          $result[$row[$indexKey]] = $row;
        }
      }else{
        foreach($input as $row){
          $result[$row[$indexKey]] = $row[$columnKey];
        }
      }
    }
    return $result;
  }

}

/**
 * 获取表名（不含表前缀）
 * @param string $model_id
 * @return string 表名
 * @author huajie <banhuajie@163.com>
 */
function get_table_name($model_id = null){
  if(empty($model_id)){
    return false;
  }
  $Model = M('Model');
  $name = '';
  $info = $Model->getById($model_id);
  if($info['extend'] != 0){
    $name = $Model->getFieldById($info['extend'], 'name').'_';
  }
  $name .= $info['name'];
  return $name;
}

/**
 * 获取属性信息并缓存
 * @param integer $id 属性ID
 * @param string $field 要获取的字段名
 * @return string 属性信息
 */
function get_model_attribute($model_id, $group = true, $fields = true){
  static $list;

  //非法ID
  if(empty($model_id) || !is_numeric($model_id)){
    return '';
  }

  //获取属性
  if(!isset($list[$model_id])){
    $map = array('model_id' => $model_id);
    $extend = M('Model')->getFieldById($model_id, 'extend');

    if($extend){
      $map = array('model_id' => array("in", array($model_id, $extend)));
    }
    $info = M('Attribute')->where($map)->field($fields)->select();
    $list[$model_id] = $info;
  }

  $attr = array();
  if($group){
    foreach($list[$model_id] as $value){
      $attr[$value['id']] = $value;
    }
    $model = M("Model")->field("field_sort,attribute_list,attribute_alias")->find($model_id);
    $attribute = explode(",", $model['attribute_list']);
    if(empty($model['field_sort'])){ //未排序
      $group = array(1 => array_merge($attr));
    }else{
      $group = json_decode($model['field_sort'], true);

      $keys = array_keys($group);
      foreach($group as &$value){
        foreach($value as $key => $val){
          $value[$key] = $attr[$val];
          unset($attr[$val]);
        }
      }

      if(!empty($attr)){
        foreach($attr as $key => $val){
          if(!in_array($val['id'], $attribute)){
            unset($attr[$key]);
          }
        }
        $group[$keys[0]] = array_merge($group[$keys[0]], $attr);
      }
    }
    if(!empty($model['attribute_alias'])){
      $alias = preg_split('/[;\r\n]+/s', $model['attribute_alias']);
      $fields = array();
      foreach($alias as &$value){
        $val = explode(':', $value);
        $fields[$val[0]] = $val[1];
      }
      foreach($group as &$value){
        foreach($value as $key => $val){
          if(!empty($fields[$val['name']])){
            $value[$key]['title'] = $fields[$val['name']];
          }
        }
      }
    }
    $attr = $group;
  }else{
    foreach($list[$model_id] as $value){
      $attr[$value['name']] = $value;
    }
  }
  return $attr;
}

/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param string  $name 格式[模块名]/接口名/方法名
 * @param array|string $vars 参数
 */
function api($name, $vars = array()){
  $array = explode('/', $name);
  $method = array_pop($array);
  $classname = array_pop($array);
  $module = $array ? array_pop($array) : 'Common';
  $callback = $module.'\\Api\\'.$classname.'Api::'.$method;
  if(is_string($vars)){
    parse_str($vars, $vars);
  }
  return call_user_func_array($callback, $vars);
}

/**
 * 根据条件字段获取指定表的数据
 * @param mixed $value 条件，可用常量或者数组
 * @param string $condition 条件字段
 * @param string $field 需要返回的字段，不传则返回整个数据
 * @param string $table 需要查询的表
 * @author huajie <banhuajie@163.com>
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null){
  if(empty($value) || empty($table)){
    return false;
  }

  //拼接参数
  $map[$condition] = $value;
  $info = M(ucfirst($table))->where($map);
  if(empty($field)){
    $info = $info->field(true)->find();
  }else{
    $info = $info->getField($field);
  }
  return $info;
}

/**
 * 获取链接信息
 * @param int $link_id
 * @param string $field
 * @return 完整的链接信息或者某一字段
 * @author huajie <banhuajie@163.com>
 */
function get_link($link_id = null, $field = 'url'){
  $link = '';
  if(empty($link_id)){
    return $link;
  }
  $link = M('Url')->getById($link_id);
  if(empty($field)){
    return $link;
  }else{
    return $link[$field];
  }
}

/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = null){
  if(empty($cover_id)){
    return false;
  }
  $picture = M('Picture')->where(array('status' => 1))->getById($cover_id);
  if($field == 'path'){
    if(!empty($picture['url'])){
      $picture['path'] = $picture['url'];
    }else{
      $picture['path'] = __ROOT__.$picture['path'];
    }
  }
  return empty($field) ? $picture : $picture[$field];
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 * @param number $pos 推荐位的值
 * @param number $contain 指定推荐位
 * @return boolean true 包含 ， false 不包含
 * @author huajie <banhuajie@163.com>
 */
function check_document_position($pos = 0, $contain = 0){
  if(empty($pos) || empty($contain)){
    return false;
  }

  //将两个参数进行按位与运算，不为0则表示$contain属于$pos
  $res = $pos & $contain;
  if($res !== 0){
    return true;
  }else{
    return false;
  }
}

/**
 * 获取数据的所有子孙数据的id值
 * @author 朱亚杰 <xcoolcc@gmail.com>
 */
function get_stemma($pids, Model &$model, $field = 'id'){
  $collection = array();

  //非空判断
  if(empty($pids)){
    return $collection;
  }

  if(is_array($pids)){
    $pids = trim(implode(',', $pids), ',');
  }
  $result = $model->field($field)->where(array('pid' => array('IN', (string) $pids)))->select();
  $child_ids = array_column((array) $result, 'id');

  while(!empty($child_ids)){
    $collection = array_merge($collection, $result);
    $result = $model->field($field)->where(array('pid' => array('IN', $child_ids)))->select();
    $child_ids = array_column((array) $result, 'id');
  }
  return $collection;
}

/**
 * 验证分类是否允许发布内容
 * @param integer $id 分类ID
 * @return boolean true-允许发布内容，false-不允许发布内容
 */
function check_category($id){
  if(is_array($id)){
    $id['type'] = !empty($id['type']) ? $id['type'] : 2;
    $type = get_category($id['category_id'], 'type');
    $type = explode(",", $type);
    return in_array($id['type'], $type);
  }else{
    $publish = get_category($id, 'allow_publish');
    return $publish ? true : false;
  }
}

/**
 * 检测分类是否绑定了指定模型
 * @param array $info 模型ID和分类ID数组
 * @return boolean true-绑定了模型，false-未绑定模型
 */
function check_category_model($info){
  $cate = get_category($info['category_id']);
  $array = explode(',', $info['pid'] ? $cate['model_sub'] : $cate['model']);
  return in_array($info['model_id'], $array);
}

/**
 ********二次开发增加的一些方法，需要调整**********
 */

function sql($obj = ''){
  echo $obj ? $obj->getLastSql() : M()->getLastSql();
}

function p($val, $print_r = 0){
  header("Content-Type: text/html; charset=UTF-8");
  ($print_r == 0) ? dump($val) : print_r($val);
}

function dd($val, $print_r = 0){
  p($val, $print_r);
  sql();
  die(1);
}

/**
 * 获取多张图片详情
 * @param array|string $images_ids 是数组的转换为字符串
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author Max.Yu <max@jipu.com>
 */
function get_images_info($images_ids, $field = null){
  if(empty($images_ids)){
    return false;
  }

  if(is_array($images_ids)){
    $images_ids = implode(',', $images_ids);
  }

  $map['status'] = 1;
  $map['id'] = array('IN', $images_ids);
  if($images_ids){
    $result = M('Picture')->where($map)->field($field)->order('FIELD(`id`, '.$images_ids.')')->select();
  }
  return $result;
}

/**
 * 取一个二维数组中的每个数组的固定的键的值来形成一个新的一维数组或字符串
 *
 * @param $pArray 一个二维数组
 * @param $pKey 数组的键的名称
 * @return 返回新的一维数组或字符串
 */
function get_sub_by_key($pArray, $pKey = '', $pCondition = '', $is_string = false){
  $result = array();
  if(is_array($pArray)){
    foreach($pArray as $temp_array){
      if(is_object($temp_array)){
        $temp_array = (array) $temp_array;
      }
      if(("" != $pCondition && $temp_array[$pCondition[0]] == $pCondition[1]) || "" == $pCondition){
        $result[] = ("" == $pKey) ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : "";
      }
    }
    if($is_string){
      $result = implode(',', $result);
    }
    return $result;
  }else{
    return false;
  }
}

/**
 * 将毫秒数格式化为剩余时间
 * @param int $second
 * @return string 完整的时间显示
 * @author Max.Yu <max@jipu.com>
 */
function time2str($second){
  $hour = floor($second / 3600);
  $second = $second % 3600; //除去整小时之后剩余时间并保留2位
  $minute = str_pad(floor($second / 60), 2, 0, STR_PAD_LEFT);
  $second = str_pad($second % 60, 2, 0, STR_PAD_LEFT); //除去整分钟之后剩余时间并保留2位
  return $hour.':'.$minute.':'.$second;
}

/**
 * 按时间精确到毫秒+uniqid生成SN号
 * 格式：2014022710050578
 * Gets a prefixed unique identifier based on the current time in microseconds.
 * @author Max.Yu <max@jipu.com>
 */
function create_sn(){
  return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/**
 * 检测是否为正确的手机号码
 * @author Max.Yu <max@jipu.com>
 */
function is_mobile_number($mobile){
  return preg_match("/1[34578]{1}\d{9}$/", $mobile);
}

/**
 * 检查是否是以手机浏览器进入(IN_MOBILE)
 * @author Max.Yu <max@jipu.com>
 */
function is_mobile(){
  //如果有HTTP_X_WAP_PROFILE则一定是移动设备
  if(isset($_SERVER['HTTP_X_WAP_PROFILE'])){
    return true;
  }
  //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
  if(isset($_SERVER['HTTP_VIA'])){
    //找不到为flase,否则为true
    if(stristr($_SERVER['HTTP_VIA'], "wap")){
      return true;
    }
  }
  //判断手机发送的客户端标志,兼容性有待提高
  if(isset($_SERVER['HTTP_USER_AGENT'])){
    $clientkeywords = array(
      'nokia',
      'sony',
      'ericsson',
      'mot',
      'samsung',
      'htc',
      'sgh',
      'lg',
      'sharp',
      'sie-',
      'philips',
      'panasonic',
      'alcatel',
      'lenovo',
      'iphone',
      'ipod',
      'blackberry',
      'meizu',
      'android',
      'netfront',
      'symbian',
      'ucweb',
      'windowsce',
      'palm',
      'operamini',
      'operamobi',
      'openwave',
      'nexusone',
      'cldc',
      'midp',
      'wap',
      'mobile',
      'phone',
    );
    //从HTTP_USER_AGENT中查找手机浏览器的关键字
    if(preg_match("/(".implode('|', $clientkeywords).")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
      return true;
    }
  }
  //协议法，因为有可能不准确，放到最后判断
  if(isset($_SERVER['HTTP_ACCEPT'])){
    //如果只支持wml并且不支持html那一定是移动设备
    //如果支持wml和html但是wml在html之前则是移动设备
    if((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
      return true;
    }
  }
  return false;
}

/**
 * 判断是否是在微信浏览器里，根据UA
 * @author Max.Yu <max@jipu.com>
 */
function is_weixin(){
  $agent = $_SERVER['HTTP_USER_AGENT'];
  if(!strpos($agent, 'icroMessenger')){
    return false;
  }
  return true;
}

/**
 * 添加微信日志
 * @author Max.Yu <max@jipu.com>
 */
function add_wechat_log($data, $data_post = ''){
  $log['cTime'] = time();
  $log['cTime_format'] = date('Y-m-d H:i:s', $log['cTime']);
  $log['data'] = is_array($data) ? serialize($data) : $data;
  $log['data_post'] = $data_post;
  M('wechat_log')->add($log);
}

/**
 * 获取插件的配置数组
 * @author Max.Yu <max@jipu.com>
 */
function getAddonConfig($name){
  static $_config = array();
  if(isset($_config[$name])){
    return $_config[$name];
  }

  $config = array();

  $token = get_token();
  if(!empty($token)){
    $map['token'] = $token;
    $addon_config = M('member_public')->where($map)->getField('addon_config');
    $addon_config = json_decode($addon_config, true);
    if(isset($addon_config[$name])){
      $config = $addon_config[$name];
      unset($map['token']);
    }
  }

  if(empty($config)){
    $map['name'] = $name;
    $map['status'] = 1;
    $config = M('Addons')->where($map)->getField('config');
    $config = json_decode($config, true);
  }

  if(!$config){
    $temp_arr = include_once ONETHINK_ADDON_PATH.$name.'/config.php';
    foreach($temp_arr as $key => $value){
      $config[$key] = $temp_arr[$key]['value'];
    }
  }
  $_config[$name] = $config;
  return $config;
}

/**
 * 获取图片缩略图
 * @param $image_src 原图路径
 * @param $width 缩略图宽度
 * @param $height 缩略图高度
 * @param $type 裁剪类型 <http://document.thinkphp.cn/manual_3_2.html#image>
 * @param $x 裁剪位置x坐标
 * @param $y 裁剪位置y坐标
 * @return 缩略图路径
 * @author Max.Yu <max@jipu.com>
 */
function get_image_thumb($image_src, $width, $height, $type = 3, $x = 0, $y = 0){
  if(!$image_src){
    return false;
  }
  $image_path = null;
  //去掉路径开头的“/”，否则通过is_file判断文件是否存在会失效
  if(substr($image_src, 0, 1) == '/'){
    $image_path = substr($image_src, 1, strlen($image_src));
  }
  if(is_file($image_path)){
    $image_path = str_replace('.', '_'.$width.'x'.$height.'.', $image_src);
    if(!file_exists('./'.$image_path)){
      $image = new \Think\Image();
      $image->open('./'.$image_src);
      //$image->crop($width, $height, 590, 0)->thumb($width, $height, $type)->save('./'.$image_path, null, 100);
      //从固定位置裁剪图片 2015-08-25 17:43 by Max
      if($type == 6){
        $image->crop($width, $height, $x, $y)->save('./'.$image_path, null, 100);
      }else{
        $image->thumb($width, $height, $type)->save('./'.$image_path, null, 100);
      }
    }
    return $image_path;
  }else{
    //原图不存在
    return $image_path;
  }
}

/**
 * 判断是否收藏该商品
 * @author Max.Yu <max@jipu.com>
 */
function is_fav($uid, $fid){
  if(empty($uid) || empty($fid)){
    return false;
  }
  $map['uid'] = $uid;
  $map['fid'] = $fid;
  $fav = D('Fav')->where($map)->count();
  return ($fav) ? 1 : 0;
}

/**
 * 判断是否领取优惠券
 * @author Max.Yu <max@jipu.com>
 */
function is_get_coupon($uid = null, $coupon_id = null){
  //验证参数的合法性
  if(empty($uid) || empty($coupon_id)){
    return false;
  }else{
    if((!is_numeric($uid)) || (!is_numeric($coupon_id))){
      return false;
    }
  }
  //实例化数据模型
  $coupon_user_model = D('CouponUser');
  return $coupon_user_model->is_get($uid, $coupon_id);
}

/**
 * 判断商品是否已评价
 * @author Max.Yu <max@jipu.com>
 */
function is_comment($uid, $item_id, $order_id){
  if(empty($uid) || empty($item_id) || empty($order_id)){
    return false;
  }
  $map['uid'] = $uid;
  $map['item_id'] = $item_id;
  $map['order_id'] = $order_id;
  $result = D('ItemComment')->where($map)->count();
  return $result;
}

/**
 * 获取字符串的第一个首字母
 * @author Max.Yu <max@jipu.com>
 */
function get_first_letter($s0){
  $firstchar_ord = ord(strtoupper($s0{0}));
  if($firstchar_ord >= 65 and $firstchar_ord <= 91){
    return strtoupper($s0{0});
  }
  if($firstchar_ord >= 48 and $firstchar_ord <= 57){
    return '#';
  }
  $s = iconv("UTF-8", "gb2312", $s0);
  $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
  if($asc >= -20319 and $asc <= -20284)
    return "A";
  if($asc >= -20283 and $asc <= -19776)
    return "B";
  if($asc >= -19775 and $asc <= -19219)
    return "C";
  if($asc >= -19218 and $asc <= -18711)
    return "D";
  if($asc >= -18710 and $asc <= -18527)
    return "E";
  if($asc >= -18526 and $asc <= -18240)
    return "F";
  if($asc >= -18239 and $asc <= -17923)
    return "G";
  if($asc >= -17922 and $asc <= -17418)
    return "H";
  if($asc >= -17417 and $asc <= -16475)
    return "J";
  if($asc >= -16474 and $asc <= -16213)
    return "K";
  if($asc >= -16212 and $asc <= -15641)
    return "L";
  if($asc >= -15640 and $asc <= -15166)
    return "M";
  if($asc >= -15165 and $asc <= -14923)
    return "N";
  if($asc >= -14922 and $asc <= -14915)
    return "O";
  if($asc >= -14914 and $asc <= -14631)
    return "P";
  if($asc >= -14630 and $asc <= -14150)
    return "Q";
  if($asc >= -14149 and $asc <= -14091)
    return "R";
  if($asc >= -14090 and $asc <= -13319)
    return "S";
  if($asc >= -13318 and $asc <= -12839)
    return "T";
  if($asc >= -12838 and $asc <= -12557)
    return "W";
  if($asc >= -12556 and $asc <= -11848)
    return "X";
  if($asc >= -11847 and $asc <= -11056)
    return "Y";
  if($asc >= -11055 and $asc <= -10247)
    return "Z";
  return '#';
}

/**
 * 判断是否过期
 * @param expire_time 过期日期（时间戳）
 * @author Max.Yu <max@jipu.com>
 */
function is_expire($expire_time){
  $now_time = NOW_TIME;

  if(($expire_time + 86400 - $now_time) < 0){
    return true;
  }else{
    return false;
  }
}

/**
 * 计算多维数组的深度（维数）
 * @param $array  数组
 * @author Max.Yu <max@jipu.com>
 */
function array_depth($array){
  if($array && is_array($array)){
    return 0;
  }

  $max_depth = 1;

  foreach($array as $value){
    if(is_array($value)){
      $depth = array_depth($value) + 1;
      if($depth > $max_depth){
        $max_depth = $depth;
      }
    }
  }

  return $max_depth;
}

/**
 * 生成商品二维码 
 * @param $type 1 商品 2 微信带参数的二维码
 * @author Max.Yu <max@jipu.com>
 * @version 2015091909 Justin
 */
function get_qrcode($item_id, $level = 'H', $size = 10, $margin = 2, $type = 1){
  if(!$item_id){
    return false;
  }

  vendor('Qrcode.Phpqrcode');
  $path = C('QRCODE_CONFIG.rootPath');

  switch($type){
    case 1:
      //商品二维码
      $data = SITE_URL.U('/Item/detail?id='.$item_id);
      $filename = $path.'Item/'.$item_id.'.png';
      break;
    case 2:
      //微信带参数的二维码
      $data = $item_id;
      $filename = $path.'WechatQrcode/'.md5($item_id).'.png';
      break;
  }

  if(!file_exists($filename)){
    mkdir(dirname($filename), 0777, true);
    $QRcode = new \Vendor\QRcode();
    $QRcode->png($data, $filename, $level, $size, $margin);
  }
  return $filename;
}

/**
 * 系统邮件发送函数
 * @param string $to 接收邮件者邮箱
 * @param string $tpl 邮件模板名称
 * @param array $subject 邮件标题数据
 * @param array $data 邮件内容数据
 * @param string $attachment 附件列表
 * @return boolean
 * @author Max.Yu <max@jipu.com>
 */
function send_email($to, $tpl, $subject = array(), $data = array(), $attachment = null){
  return false;
  // 获取邮件模板
  $map_tpl = array(
    'name' => $tpl,
    'status' => 1
  );
  $notify_tpl = D('NotifyTpl')->detail($map_tpl, 'name, subject, content');
  if(!$notify_tpl){
    return false;
  }
  if($subject && is_array($subject)){
    foreach($subject as $key => $value){
      $notify_tpl['subject'] = str_replace('{['.$key.']}', $value, $notify_tpl['subject']);
    }
  }
  if($data && is_array($data)){
    foreach($data as $key => $value){
      $notify_tpl['content'] = str_replace('{['.$key.']}', $value, $notify_tpl['content']);
    }
  }
  $subject = $notify_tpl['subject'];
  $content = $notify_tpl['content'];
  //从PHPMailer目录导入Phpmailer.php类文件
  $config = C('THINK_EMAIL');
  vendor('PHPMailer.Phpmailer');
  //邮件模板
  $site_tel = C('WEB_SITE_TEL');
  $site_logo = SITE_URL.'/Public/Home/images/logo.png';
  $body = '<style>a.btn-email,a.btn-email:link,a.btn-email:visited{margin:10px 0;background:#c41921;padding:10px 20px;color:#fff;width:120px;text-align:center;text-decoration:none;font-size:16px;}</style><div style="padding:10px;width:540px;border:#c41921 solid 2px;margin:0 auto"><div style="color:#bbb;overflow:hidden;zoom:1"><div style="overflow:hidden;position:relative;padding-bottom:5px;border-bottom:1px solid #ddd;"><a href="'.SITE_URL.'"><img style="border:0 none" src="'.$site_logo.'"></a></div></div><div style="background:#fff;padding:0;min-height:240px;position:relative"><div style="margin:0;font-size:14px;line-height:24px;">'.$content.'<p>如有任何疑问，请联系客服，客服热线：'.$site_tel.'。</p></div></div><div style="border-top:1px dashed #ddd;background:#fff;"><div style="line-height:18px;"><p style="margin:0;padding:10px 0 0 0;color:#999;font-size:12px">您之所以收到这封邮件，是因为您曾经注册成为我们的用户。<br>本邮件由系统自动发出，请勿直接回复！<br>如果您有任何疑问或建议，请联系我们。</p></div></div></div>';
  $mail = new \Vendor\PHPMailer(); //PHPMailer对象
  $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
  $mail->IsSMTP();  //设定使用SMTP服务
  $mail->SMTPDebug = 0; //关闭SMTP调试功能
  //1 = errors and messages
  //2 = messages only
  $mail->SMTPAuth = true; //启用 SMTP 验证功能
  $mail->SMTPSecure = 'ssl'; //使用安全协议
  $mail->Host = $config['SMTP_HOST']; //SMTP 服务器
  $mail->Port = $config['SMTP_PORT']; //SMTP服务器的端口号
  $mail->Username = $config['SMTP_USER']; //SMTP服务器用户名
  $mail->Password = $config['SMTP_PASS']; //SMTP服务器密码
  $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
  $replyEmail = $config['REPLY_EMAIL'] ? $config['REPLY_EMAIL'] : $config['FROM_EMAIL'];
  $replyName = $config['REPLY_NAME'] ? $config['REPLY_NAME'] : $config['FROM_NAME'];
  $mail->AddReplyTo($replyEmail, $replyName);
  $mail->Subject = $subject;
  $mail->MsgHTML($body);
  $mail->AddAddress($to, $name);
  if(is_array($attachment)){ //添加附件
    foreach($attachment as $file){
      is_file($file) && $mail->AddAttachment($file);
    }
  }
  return $mail->Send() ? true : $mail->ErrorInfo;
}

/**
 * 跳转到指定的邮箱
 * @param type $mail
 * @return string
 * @author Max.Yu <max@jipu.com>
 */
function goto_email($mail){
  $mail_arr = explode('@', $mail);
  $t = strtolower($mail_arr[1]);
  if($t == '163.com'){
    return 'mail.163.com';
  }else if($t == 'vip.163.com'){
    return 'vip.163.com';
  }else if($t == '126.com'){
    return 'mail.126.com';
  }else if($t == 'qq.com' || $t == 'vip.qq.com' || $t == 'foxmail.com'){
    return 'mail.qq.com';
  }else if($t == 'gmail.com'){
    return 'mail.google.com';
  }else if($t == 'sohu.com'){
    return 'mail.sohu.com';
  }else if($t == 'tom.com'){
    return 'mail.tom.com';
  }else if($t == 'vip.sina.com'){
    return 'vip.sina.com';
  }else if($t == 'sina.com.cn' || $t == 'sina.com'){
    return 'mail.sina.com.cn';
  }else if($t == 'tom.com'){
    return 'mail.tom.com';
  }else if($t == 'yahoo.com.cn' || $t == 'yahoo.cn'){
    return 'mail.cn.yahoo.com';
  }else if($t == 'tom.com'){
    return 'mail.tom.com';
  }else if($t == 'yeah.net'){
    return 'www.yeah.net';
  }else if($t == '21cn.com'){
    return 'mail.21cn.com';
  }else if($t == 'hotmail.com'){
    return 'www.hotmail.com';
  }else if($t == 'sogou.com'){
    return 'mail.sogou.com';
  }else if($t == '188.com'){
    return 'www.188.com';
  }else if($t == '139.com'){
    return 'mail.10086.cn';
  }else if($t == '189.cn'){
    return 'webmail15.189.cn/webmail';
  }else if($t == 'wo.com.cn'){
    return 'mail.wo.com.cn/smsmail';
  }else if($t == '139.com'){
    return 'mail.10086.cn';
  }else{
    return '';
  }
}

/**
 * 获取隐藏中间四位的手机号码
 * @param string $mobile
 * @return string
 * @author Max.Yu <max@jipu.com>
 */
function get_hidden_mobile($mobile){
  if($mobile){
    return preg_replace("/(1\d{1,4})\d\d\d\d(\d{3,4})/", "\$1****\$2", $mobile);
  }
}

/**
 * 反序列化字符串为“key:val”形式或者“va1,v2,v3”逗号隔开的形式字符串
 * @param string $str 被格式化的字符串
 * @param int $type 格式化形式：1->key:val，2->va1,v2,v3
 * @param string $glue 分割符
 * @return string 返回值
 * @author Max.Yu <max@jipu.com>
 */
function custom_unserialize($str, $type = 1, $glue = ' '){
  $return_info = '';

  if(empty($str)){
    return $return_info;
  }

  $str_arr = unserialize($str);
  $str_arr_len = count($str_arr);
  $counter = 1;

  if($str_arr && is_array($str_arr)){
    foreach($str_arr as $item){
      if($type == 1){
        $return_info = $return_info.$item['key'].":".$item['val'].$glue;
      }elseif($type == 2){
        $return_info = $return_info.$item['val'];
        if($counter < $str_arr_len){
          $return_info = $return_info.$glue;
        }
        $counter++;
      }
    }
  }

  return rtrim($return_info, $glue);
}

/**
 * IP-获取用户IP
 * @return string
 * @author Max.Yu <max@jipu.com>
 */
function get_ip(){
  $realip = null;
  if(isset($_SERVER)){
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else if(isset($_SERVER['HTTP_CLIENT_IP'])){
      $realip = $_SERVER['HTTP_CLIENT_IP'];
    }else{
      $realip = $_SERVER['REMOTE_ADDR'];
    }
  }else{
    if(getenv('HTTP_X_FORWARDED_FOR')){
      $realip = getenv('HTTP_X_FORWARDED_FOR');
    }else if(getenv('HTTP_CLIENT_IP')){
      $realip = getenv('HTTP_CLIENT_IP');
    }else{
      $realip = getenv('REMOTE_ADDR');
    }
  }
  //处理多层代理的情况
  if(false !== strpos($realip, ',')){
    $realip = reset(explode(',', $realip));
  }
  //IP地址合法验证
  $realip = filter_var($realip, FILTER_VALIDATE_IP, null);
  if(false === $realip){
    return '0.0.0.0'; //unknown
  }
  return $realip;
}

/**
 * 时间格式转化，多少分钟以前
 * @param string $time
 * @return string
 * @author Max.Yu <max@jipu.com>
 */
function format_date($time){
  $t = time() - $time;
  $f = array(
    '31536000' => '年',
    '2592000' => '个月',
    '604800' => '星期',
    '86400' => '天',
    '3600' => '小时',
    '60' => '分钟',
    '1' => '秒'
  );
  if($t == 0){
    return '刚刚';
  }
  foreach($f as $k => $v){
    if(0 != $c = floor($t / (int) $k)){
      return $c.$v.'前';
    }
  }
}


/**
 * 时间格式转化，拼团时间限制
 * @param string $time
 * @return string
 * @author Max.Yu <max@jipu.com>
 */
function getFormat($time){
    $f = array(
            '43200' => '个月',
            '10080' => '星期',
            '1440' => '天',
            '60' => '小时',
            '1' => '分钟'
    );
    $str = '';
    foreach($f as $k => $v){
        $value = ($time / (int) $k);
        $c = floor($value);
        if(0 != $c){
            $str .= $c.$v.'&nbsp';
            $float = round($value - $c,2);
            if($float != 0){
                $time = round($float*(int)$k);
            }else{
                $time = 0;
            }
        }
    }
    return $str;
}

/**
 * 生成防重复提交识别串
 * @author Max.Yu <max@jipu.com>
 */
function create_form_hash(){
  $ip = get_ip();
  $path = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  $FORMHASH = md5(md5($ip).session_id().$path);
  $FORMHASH_ARR = session('FORMHASH');
  if(empty($FORMHASH_ARR)){
    $FORMHASH_ARR = array($FORMHASH);
  }else{
    if(!in_array($FORMHASH, $FORMHASH_ARR)){
      $FORMHASH_ARR[] = $FORMHASH;
    }
  }
  session('FORMHASH', $FORMHASH_ARR);
  return $FORMHASH;
}

/**
 * 检测防重复提交串
 * @author Max.Yu <max@jipu.com>
 */
function check_form_hash(){
  $ip = get_ip();
  $hash = I('request.formhash');
  $form_path = $_SERVER['HTTP_REFERER'];
  $HASH_ = md5(md5($ip).session_id().$form_path);
  $FORMHASH_ARR = session('FORMHASH');
  if($hash == $HASH_ && in_array($HASH_, $FORMHASH_ARR)){
    return true;
  }else{
    return false;
  }
}

/**
 * 获取随机字符串
 * @return string
 * @author Max.Yu <max@jipu.com>
 */
function get_randstr($length = 16, $type = 0, $addstr = ''){
  $chars_a = '0123456789';
  $chars_b = 'abcdefghijklmnopqrstuvwxyz';
  $chars = $type == 0 ? ($chars_a.$chars_b) : ($type == 1 ? $chars_a : $chars_b);
  $chars.= $addstr;
  $max = strlen($chars) - 1;
  for($i = 0; $i < $length; $i++){
    $randstr .= $chars[mt_rand(0, $max)];
  }
  return $randstr;
}

/**
 * 截取字符串
 * @param string $str
 * @param int $length 字符串的长度
 * @param string $ext 忽略部分显示内容
 * @return string $output 返回截取后的字符串
 * @author Max.Yu <max@jipu.com>
 */
function get_short($str, $length = 40, $ext = '…'){
  $str = htmlspecialchars($str);
  $str = strip_tags($str);
  $str = htmlspecialchars_decode($str);
  $strlenth = 0;
  $out = '';
  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
  foreach($match[0] as $v){
    preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $v, $matchs);
    if(!empty($matchs[0])){
      $strlenth += 1;
    }elseif(is_numeric($v)){
      $strlenth += 0.5;    //字符字节长度比例 汉字为1
    }else{
      $strlenth += 0.5;    //字符字节长度比例 汉字为1
    }

    if($strlenth > $length){
      $output .= $ext;
      break;
    }

    $output .= $v;
  }
  return $output;
}

/**
 * 获取前台微信JSConfig数据
 * @param string $do 请求动作，多个通过逗号,隔开
 * @author Max.Yu <max@jipu.com>
 */
function get_jsapi_config($do = ''){
  if(empty($do)){
    return json_encode(array());
  }
  $app_id = C('WECHAT_APPID');
  $app_secret = C('WECHAT_SECRET');
  $payEvent = A('Pay', 'Event');

  if(!$app_id || !$app_secret){
    $error = array();
    return json_encode($error);
  }

  //获取js票据
  $auth = new \Org\Wechat\WechatAuth($app_id, $app_secret);
  $ticket = $auth->getJsapiTicket();
  //页面所需参数
  $wxconf = C('WECHATPAY');
  $data_config = array(
    'timestamp' => time(), //10位时间戳
    'nonceStr' => md5(uniqid(time(), true)), //随机字符串
    'jsapiticket' => $ticket['ticket'],
    'appKey' => $wxconf['app_key'],
    'mchId' => $wxconf['mch_id'],
  );
  $configSign = $payEvent->getConfigSign($data_config);
  //jsapi-config
  $config = array(
    'debug' => false,
    'appId' => $app_id,
    'timestamp' => $data_config['timestamp'],
    'nonceStr' => $data_config['nonceStr'],
    'signature' => $configSign,
    'jsApiList' => explode(',', $do)
  );
  //返回需要的参数
  return json_encode($config);
}

/**
 * 获取/解析邀请码
 * @param string $code 邀请码或UID
 * @return string 返回UID或邀请码
 * @author Max.Yu <max@jipu.com>
 */
function invite_code($code){
  if(empty($code)){
    return '';
  }
  if(is_numeric($code)){
    $reg_time = M('User')->getFieldById($code, 'reg_time');
    return base_convert('1'.($reg_time % 10E3).$code, 10, 36);
  }else{
    return substr(base_convert($code, 36, 10), 5);
  }
}

/**
 * 读取文件
 * @author Max.Yu <max@jipu.com>
 */
function read_file($url, $timeout = 10, $refer = ''){
  if(extension_loaded('curl')){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $refer && curl_setopt($ch, CURLOPT_REFERER, $refer);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
    $info = curl_exec($ch);
    curl_close($ch);
  }else{
    $info = file_get_contents($url);
  }
  return $info;
}

/**
 * 获取文件夹内容列表
 * @author Max.Yu <max@jipu.com>
 */
function get_dir_list($path){
  $fn = scandir($path);
  $list = array();
  foreach($fn as $v){
    if(substr($v, 0, 1) != '.'){
      $n = $path.(substr($path, -1, 1) == '/' ? '' : '/').$v;
      if(is_dir($n)){
        $li = get_dir_list($n);
        $list = array_merge($list, $li);
      }else{
        $list[] = $path.'/'.$v;
      }
    }
  }
  return $list;
}

/**
 * 获取用户前台用户组名
 * @param int $group_id 组名id
 * @return string 前台用户组名
 * @version 2015061614
 * @author Justin <justin@jipu.com>
 */
function get_group_name($group_id = 0){
  if($group_id){
    return M('UserGroup')->getFieldById($group_id, 'title');
  }
}

/**
 * 获取用户前台用户组描述
 * @param int $group_id 组名id
 * @return string 前台用户组描述
 * @version 2015061614
 * @author Justin <justin@jipu.com>
 */
function get_group_description($group_id = 0){
  if($group_id){
    return M('UserGroup')->getFieldById($group_id, 'description');
  }
}

/**
 * 根据模板返回短信内容
 * @param int $tpl_id 短信模板ID
 * @param array $param 需要匹配的数据 （不传则返回模板内容）
 * @return string 组装后的字符串
 * @author Max.Yu <max@jipu.com>
 */
function get_ypsms_content($tpl_id = 0, $param = array()){
  $content = '';
  if($tpl_id > 0){
    $param = array_merge(array('company' => C('YUNPIAN_COMPANY')), $param);
    $cache_name = 'yunpian_sms_tpl_'.$tpl_id;
    if(S($cache_name) == false){
      $sms = new Org\SMSYunpian\Sms();
      $res = $sms->doit('tpl/get', array('tpl_id' => $tpl_id));
      if($res['code'] == 0 && $res['template']['check_status'] == 'SUCCESS'){
        $content = $res['template']['tpl_content'];
        S($cache_name, $content);
      }
    }else{
      $content = S($cache_name);
    }
    foreach($param as $k => $v){
      $content = str_replace("#{$k}#", $v, $content);
    }
  }
  return $content;
}

/**
 * 下发模板短信
 * @param mobileNo $mobile 手机号码 暂不支持0开头的小灵通号码
 * @param int $tpl_id 在后台短信中心的模板id
 * @param array $param 短信模板中替换的参数
 * @return statusArray 状态数组 code 为 200处理成功 300处理失败，msg对应返回描述 
 * @author Max.Yu <max@jipu.com>
 */
function send_yptpl_sms($mobile = '', $tpl_id = 0, $param = array()){
  $return_data = array('code' => 300, 'msg' => '');
  if(!preg_match("/1[34578]{1}\d{9}$/", $mobile)){
    $return_data['msg'] = '参数:手机号码非法';
    return $return_data;
  }
  //如果传入的模板ID为字符串，将其对应处理为系统配置的短信模板映射值
  if(is_string($tpl_id))$tpl_id=intval(C('SMS_TPL_ID.'.$tpl_id));
  if($tpl_id <= 0){
    $return_data['msg'] = '参数:请指定短信模板ID';
    return $return_data;
  }
  $content = get_ypsms_content($tpl_id, $param); //获取需要发送的短信内容
  if($content){
    if(C('SMS_YUNPIAN.SEND_TEST') == true){ //短信测试模式
      $res = array('code' => 0);
      return array('code' => 300, 'msg' =>C('SMS_YUNPIAN.SEND_TEST'));
    }else{
      $sms = new Org\SMSYunpian\Sms();
      $res = $sms->doit('sms/send', array('mobile' => $mobile, 'text' => $content));
    }
    if($res['code'] == 0){
      $return_data['code'] = 200;
      $sms_data = array(
        'mobile' => $mobile,
        'tpl_id' => $tpl_id,
        'content' => $content,
        'code' => $param['code'],
        'ip' => get_ip(),
        'create_time' => NOW_TIME
      );
      $sms_id = M('Sms')->add($sms_data);
      $return_data['create_time'] = NOW_TIME;
      $return_data['msg'] = $sms_id > 0 ? '短信发送成功' : '发送成功，记录失败';
    }else{
      $return_data = array('code' => 300, 'msg' => $res['msg']);
    }
  }else{
    $return_data['msg'] = '没有获取到短信内容';
  }
  return $return_data;
}

/**
 * 下发验证码短信
 * @param mobileNo $mobile 手机号码 暂不支持0开头的小灵通号码
 * @param string $type 验证码模板
 * @return statusArray 状态数组 code 为 200处理成功 300处理失败，msg对应返回描述 
 * @author Max.Yu <max@jipu.com>
 */
function send_ypcode_sms($mobile = '', $tpl_id = 0, $param = array()){
  $return_data = array('code' => 300, 'msg' => '');
  $map = array(
    'mobile' => $mobile,
    'tpl_id' => $tpl_id,
    'create_time' => array('gt', time() - C('RAND_CODE_INTERVEL')),
    'validate_status' => 0
  );
  //查询是否已经发送
  $codeline = M('Sms')->where($map)->find();
  if($codeline){
    $return_data['msg'] = '验证码已发送，请勿重复请求';
  }else{
    $where = array(
      'ip' => get_ip(),
      'mobile' => $mobile,
      'tpl_id' => $tpl_id,
      'validate_status' => 0,
      'create_time' => array('gt', strtotime(date('Y-m-d'))),
    );
    $find_number = M('Sms')->where($where)->count();
    //信用额度-当日未验证该模板信息条数
    if($find_number >= 3){
      $return_data['msg'] = '今天验证码信用额度已用完';
    }else{
      // 生成加密后的6位手机验证码
      $code = get_randstr(6, 1);
      $param['code'] = $code;
      $return_data = send_yptpl_sms($mobile, $tpl_id, $param);
    }
  }
  return $return_data;
}

/**
 * 检测短信验证码有效性（验证通过后，不可重复检测）
 * @param string $mobile 手机号码  
 * @param int $tpl_id 验证码类型
 * @param string $code 用户输入的验证码
 * @return boolean 验证状态
 * @author Max.Yu <max@jipu.com>
 */
function check_ypsms_code($mobile = '', $tpl_id = 0, $code = ''){
  $map = array(
    'mobile' => $mobile,
    'tpl_id' => $tpl_id,
    'code' => $code,
    'create_time' => array('gt', time() - 5 * 60),
    'validate_status' => 0
  );
  $line = M('Sms')->where($map)->find();
  if($line){
    $save_data = array(
      'validate_status' => 1,
      'validate_time' => time()
    );
    $res = M('Sms')->where(array('id' => $line['id']))->save($save_data);
    if($res){
      return true;
    }
  }
  return false;
}

/**
 * 获取余额操作类型名称
 * @param string $finance_type 客房评价值
 * @return string 余额操作类型名称
 * @version 2015070417
 * @author Justin <justin@jipu.com>
 */
function get_finance_type_name($finance_type = null){
  $finance_type_name = array(
    'invite_reward' => '邀请奖励',
    'redpackage' => '抢到的红包',
    'refund' => '订单退款',
    'sdp_order' => '分销订单',
    'sdp_refund' => '分销订单退款',
    'union_order' => '推广联盟订单返现',
    'union_subscribe' => '推广联盟关注',
    'website' => '站内消费',
    'website_deduct' => '网站协议扣款',
    'website_rechange' => '网站后台充值',
    'withdraw' => '余额提现',
    'withdraw_refuse_cashback' => '提现失败返款'
  );
  return $finance_type ? $finance_type_name[$finance_type] : $finance_type_name;
}

function get_accountcost_type_name($finance_type = null){
  $finance_type_name = array(
    '充值' => '充值',
    '消费' => '消费',
    '退款' => '退款',
  );
  return $finance_type ? $finance_type_name[$finance_type] : $finance_type_name;
}

/**
 * 获取父级分类信息
 * @param int $cid 分类ID
 * @return array 父级分类数组
 * @author Max.Yu <max@jipu.com>
 */
function get_parent_cid($cid = '', $type = 'ArticleCategory'){
  if(empty($cid)){
    return array();
  }
  $cid = is_numeric($cid) ? array($cid) : $cid;
  $pid = M($type)->getFieldById($cid[0], 'pid');
  $pid = intval($pid);
  if($pid > 0){
    $cid = array_merge(array($pid), $cid);
    return get_parent_cid($cid, $type);
  }else{
    return $cid;
  }
}

/**
 * 获取支付方式文字
 * @param string $type 支付方式
 * @return string|array 支付方式或者支付方式数组
 * @author Justin
 */
function get_payment_type_text($type = null){
  $payment_type = array(
    'alipay' => '支付宝',
    'alipaywap' => '手机支付宝',
    'bankpay' => '网银',
    'wechatpay' => '微信',
    'crowdfunding' => '众筹'
  );
  return $type ? $payment_type[$type] : $payment_type;
}

/**
 * 获取用户账户类型文字
 * @param string $type 账户类型
 * @return string 账户类型文字
 * @author Justin
 */
function get_user_account_text($type = null){
  $user_account_type = array(
    'alipay' => '支付宝',
    'bankcard' => '银行卡',
  );
  return $user_account_type[$type];
}

/**
 * 隐藏邮箱中的部分字符
 * @param string $email
 * @return string
 * @author Justin
 */
function get_hidden_email($email){
  if($email){
    $email_array = explode('@', $email);
    return substr_replace($email_array[0], '<em class="star">****</em>', 2).'@'.$email_array[1];
  }
}

/**
 * 隐藏银行卡号中的部分字符(留后四位)
 * @param string $bankcard
 * @return string
 * @author Justin
 */
function get_hidden_bankcard($bankcard){
  if($bankcard){
    return '<em class="star">**** **** **** </em>'.substr($bankcard, -4);
  }
}

/**
 * 隐藏支付宝账户中的部分字符
 * @param string $account 支付宝账户
 * @return string
 * @author Justin
 */
function get_hidden_alipay($account){
  //判断支付宝账户类型
  if(1 == checkUserType($account)){
    return get_hidden_email($account);
  }else{
    return get_hidden_mobile($account);
  }
}

/**
 * 判断用户名是邮箱还是手机
 * @return int 1-邮箱，2-手机
 */
function checkUserType($username){
  if(!$username){
    return false;
  }
  return strstr($username, '@') ? 1 : 2;
}

/**
 * 获取指定订单返现金额
 * @param int $order_id 订单ID
 * @return amount 返现金额
 * @author Max.Yu <max@jipu.com>
 */
function get_cashback_amount($order_id = 0){
  $amount_line = M('SdpRecord')->field('sum(`cashback_amount`) as amount')->where(array('order_id' => $order_id))->find();
  return $amount_line['amount'] ? $amount_line['amount'] : 0;
}

/**
 * 获取提现状态文字
 * @param int $status 提现状态
 * @return string 提现状态文字
 * @author Justin
 */
function get_withdraw_text($status = null){
  $withdraw_status = array(
  //'-1' => '已取消',
    '100' => '<span class="text-danger">等待管理员审核</span>',
    '101' => '<span class="text-danger">管理员拒绝</span>',
    '200' => '已提交银行处理',
    '201' => '<span class="text-danger">银行拒绝</span>',
    '300' => '<span class="text-success">提现成功</span>',
  );

  return $status ? $withdraw_status[$status] : $withdraw_status;
}

/**
 * 获取店铺名称
 * @param int $uid 用户id
 * @return string 店铺名称
 * @author Justin
 */
function get_shop_name($uid = 0){
  $name = M('Shop')->getFieldByUid($uid, 'name');
  return $name ? $name : '尚未设置店名';
}

/**
 * 获取自定义图文消息内容列表
 */
function get_itmlist($itm){
  if(empty($itm)){
    return array();
  }
  $return_data = array();

  foreach($itm as $v){
    $id_ = explode(':', $v);
    //文章
    if($id_[0] == 'article'){
      $art = M('Article')->find($id_[1]);
      $img_path = get_cover($art['images'], 'path');
      $data = array(
        'title' => $art['title'],
        'path' => SITE_URL.U('Article/detail', array('id' => $art['id'])),
        'img' => $img_path ? (SITE_URL.'/'.$img_path) : '',
        'description' => $art['description'],
        'key' => $v,
      );
    }
    //商品
    if($id_[0] == 'item'){
      $art = M('Item')->find($id_[1]);
      $img_path = get_cover($art['images'], 'path');
      $data = array(
        'title' => $art['name'],
        'path' => SITE_URL.U('Item/detail', array('id' => $art['id'])),
        'img' => $img_path ? (SITE_URL.'/'.$img_path) : '',
        'description' => $art['summary'],
        'key' => $v,
      );
    }
    $data && $return_data[] = $data;
  }

  return $return_data;
}

/**
 * 获取商品图片
 */
function get_item_images($item_id = 0){
  $item_images = M('Item')->getFieldById($item_id, 'images');
  return $item_images ? $item_images : '';
}

/**
 * 获取商品基本信息
 * @param int $item_id 商品ID
 * @param string $key_name 键名
 * @return array OR string item信息数组或指定字段值
 * @author Max.Yu <max@jipu.com>
 */
function get_item_info($item_id = 0, $key_name = ''){
  $info = array();
  $info = M('Item')->field('id, supplier_id, name, thumb, subname, price')->getById($item_id);
  $info['thumb'] && $info['thumb'] = get_cover($info['thumb'], 'path');
  return $key_name ? $info[$key_name] : $info;
}

/**
 * 获取供应商文字
 * @author Justin
 */
function get_supplier_text($supplier_id = 0){
  $nickname = get_nickname($supplier_id) ? get_nickname($supplier_id) : get_username($supplier_id);
  $supplier_name = M('Supplier')->getFieldBySupplierId($supplier_id, 'name');
  return $supplier_name ? $supplier_name : $nickname;
}

/**
 * 生成订单编号
 * @return string 订单编号
 * @author Max.Yu <max@jipu.com>
 */
function create_order_sn(){
  return date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/**
 * 获取支付单ID对应的商品总价格、运费总价格
 * @param int $payment_id 支付单ID
 * @param string $type （1、total_price 商品总价格 ，2、delivery_fee 运费总价格）
 * @author Max.Yu <max@jipu.com>
 */
function get_count_payment_price($payment_id = 0, $type = 'total_price'){
  $line = M('Order')->field('sum('.$type.') price')->where('payment_id='.$payment_id)->find();
  return $line['price'] ? $line['price'] : 0;
}

/**
 * 获取订单状态对应文字
 * @param int $o_status 订单状态值 | 可为空 获取整个状态数组
 * @return string 状态文字 | array 整个数组
 */
function get_o_status_text($o_status){
  $o_status_text = array(
    0 => '<span class="text-warning">待付款</span>',
    -1 => '<span class="text-cancel">交易取消</span>',
    200 => '<span class="text-success">已付款</span>',
    201 => '<span>已发货，待买家收货</span>',
    202 => '<span class="text-success">交易成功</span>',
    300 => '<span class="text-danger">申请退款</span>',
    301 => '<span class="text-danger">待买家退货</span>',
    302 => '<span class="text-danger">已退货</span>',
    303 => '<span class="text-cancel">已退款，交易关闭</span>',
    404 => '<span class="text-cancel">系统取消订单</span>',
    405 => '<span class="text-cancel">退款驳回</span>',
  );
  return $o_status ? $o_status_text[$o_status] : $o_status_text;
}

/**
 * 识别文本中的链接、邮箱、ftp自动加链接
 * @param string $str 字符串文本
 * @author Max.Yu <max@jipu.com> Copy
 */
function text2links($str = ''){
  if($str == '' or ! preg_match('/(http|www\.|@)/i', $str)){
    return $str;
  }
  $lines = explode("\n", $str);
  $new_text = '';
  while(list($k, $l) = each($lines)){
    $l = preg_replace("/([ \t]|^)www\./i", "\\1http://www.", $l);
    $l = preg_replace("/([ \t]|^)ftp\./i", "\\1ftp://ftp.", $l);
    $l = preg_replace("/ (http:\/\/[^ )!]+)/i", "<a href=\"\\1\" target='_blank'>\\1</a>", $l);
    $l = preg_replace("/ (https:\/\/[^ )!]+)/i", "<a href=\"\\1\" target='_blank'>\\1</a>", $l);
    $l = preg_replace("/ (ftp:\/\/[^ )!]+)/i", "<a href=\"\\1\" target='_blank'>\\1</a>", $l);
    $l = preg_replace("/ ([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)+))/i", "<a href=\"mailto:\\1\">\\1</a>", $l);
    $new_text .= $l."\n";
  }
  return $new_text;
}

/**
 * 字符串分段删除
 * 例：$string = '171,172,173,174,181,178,179,175,176,180,0'; 从中删除 174 返回字符串$string
 * @param string|array $value 需要移除的值
 * @param string $string 原数据值
 * @return string 更新后的数据值
 * @author Max.Yu <max@jipu.com>
 */
function str_remove($value, $string){
  $old_array = explode(',', $string);
  $new_array = array();
  $values = explode(',', $value);
  foreach($old_array as $v){
    !in_array($v, $values) && $new_array[] = $v;
  }
  return implode(',', $new_array);
}

/**
 * 字符串追加至头部
 * 例：$string = '171,172'; 追加值 36 返回字符串$string为 '36,171,172'
 * @param string|array $value 需要追加的值
 * @param string $string 原数据值
 * @return string 更新后的数据值
 * @author Max.Yu <max@jipu.com>
 */
function str_add($value, $string){
  return trim((is_array($value) ? implode(',', $value) : $value).','.$string, ',');
}

/**
 * 检测微信是否关注
 * @param string $open_id 用户微信OpenId
 * @author Max.Yu <max@jipu.com>
 */
function is_subscribe($open_id = '', $get_info = false){
  if(empty($open_id)){
    $open_id = A('Pay', 'Event')->getOpenId();
  }
  $wechat = new \Org\Wechat\WechatAuth(C('WECHAT_APPID'), C('WECHAT_SECRET'), C('WECHAT_TOKEN'));
  $userinfo = $wechat->userInfo($open_id);
  return ($get_info && $userinfo['subscribe'] == 1) ? $userinfo : ($userinfo['subscribe'] != 0);
}

/**
 * 调用APIX接口
 * @author Max.Yu <max@jipu.com>
 */
function get_apix_data($action = 'express/delivery', $query_data = array(), $apix_key = ''){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://a.apix.cn/apixlife/'.$action.'?'.http_build_query($query_data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      'accept: application/json', 'apix-key: '.$apix_key, 'content-type: application/json'
    ),
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  if($response){
    return json_decode($response, 1);
  }else{
    return array('error_code' => -1, 'message' => $err);
  }
}

/**
 * 保存图片到本地
 * @author Max.Yu <max@jipu.com>
 */
function save_images($url, $type = 'avatar'){
  $info = read_file($url);
  $file = '/Uploads/'.ucfirst($type).'/'.date('Y/md').'/'.md5($url).'.png';
  mkdir('.'.dirname($file), 0777, true);
  if(strlen($info) > 100){
    if(file_put_contents('.'.$file, $info)){
      return $file;
    }
  }
  return $url;
}
 /**
 /**
   * 获取未付款的订单的备注信息
   */
  function handlememo( $memo ){
   if(!empty($memo)){
      $temp = unserialize($memo);
      foreach($temp as $k => $v){
        $name = $k == 0 ? C('WEB_SITE_TITLE') : get_supplier_text($k);
        $data[$name] = $v;
      }
      $data = array_filter($data);
      return $data;
    }
    return false;
}
  /**
   * 获取供应商名字
   * @return [type] [description]
   */
  function getsupplier($sid){
    $name = M('Supplier')->where('id= '.$sid)->getField('name');
    return $name ? $name : '自营';
  }