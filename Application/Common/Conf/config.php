<?php
/**
 * 系统配置文件模板
 * @version 2014040714
 * @author Max.Yu <max@jipu.com>
 */

//定义URL通用的URL
define('URL_CALLBACK', 'http://'.SITE_DOMAIN.U('User/callback').'&type=');

/**
 * 系统配文件
 * 所有系统级别的配置
 */
$config = array(
  /**
   * 模块相关配置
   */
  'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
  'DEFAULT_MODULE'     => 'Home',
  'MODULE_DENY_LIST'   => array('Common', 'Install'),
  'MODULE_ALLOW_LIST'  => array('Home', 'Admin'),

  /**
   * 系统数据加密设置
   */
  'DATA_AUTH_KEY' => '<rlvXL^B3YM~u2%|7]m9$IG_o)ADFNd:j*"J5zh&', //默认数据加密KEY

  /**
   * 用户相关设置
   */
  'USER_MAX_CACHE'     => 1000, //最大缓存用户数
  'USER_ADMINISTRATOR' => 1, //管理员用户ID

  /**
   * URL配置
   */
  'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
  'URL_MODEL'            => 2, //URL模式
  'VAR_URL_PARAMS'       => '', //PATHINFO URL参数变量
  'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

  /**
   * 全局过滤配置
   */
  'DEFAULT_FILTER' => '', //全局过滤函数

  /**
   * 数据库配置
   */
  'DB_TYPE'   => 'mysql', //数据库类型
  'DB_HOST'   => '127.0.0.1', //服务器地址 内网地址：rm-2ze7358lvu42gtnyx.mysql.rds.aliyuncs.com 外网地址：rm-2ze7358lvu42gtnyxo.mysql.rds.aliyuncs.com
  'DB_NAME'   => 'ccyishe_2016', //数据库名
  'DB_USER'   => 'root', //用户名ccyishe
  'DB_PWD'    => '!3@4#a$1%^b&1*()_+-=',  //密码
  'DB_PORT'   => '3306', //端口
  'DB_PREFIX' => 'ccys_', //数据库表前缀


  /**
   * 二维码存放相关配置
   */
  'QRCODE_CONFIG' => array(
    'exts'     => 'png', //文件后缀
    'rootPath' => './Uploads/QRcode/', //保存根路径
  ),

  
  /**
   * 微信通过接口获取授权  域名配置
   */
  'WEXIN_API_AUTH_URL' => '[]',
  
  /**
   * 是否从自定义接口获取微信用户信息
   */
  'WECHAT_USERINFO_BY_API' => false,
  
  /**
   * 邮件配置
   */
  'THINK_EMAIL' => array(
    'SMTP_HOST' => 'smtp.163.com', //SMTP服务器
    'SMTP_PORT' => '465', //SMTP服务器端口
    'SMTP_USER' => '[]', //SMTP服务器用户名
    'SMTP_PASS' => '', //SMTP服务器密码
    'FROM_EMAIL' => '[]', //发件人EMAIL
    'FROM_NAME' => '[]', //发件人名称
    'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
    'REPLY_NAME' => '', //回复名称（留空则为发件人名称）
  ),

  /**
   * 上传图片尺寸约定
   */
  'UPLOAD_PIC_SIZE_CONVRNTION' => array(
    //广告图片
    'AD_PIC' => array(
      1 => '宽度为：700px，高度为：350px', //首页商品调用广告
      2 => '宽度为：720px，高度为：320px', //首页中部横幅广告
      3 => '宽度为：720px，高度为：320px', //首页底部横幅广告
    ),
    //专题图片
    'TOPIC_PIC' => array(
      'BG_PIC' => '宽度为：720px，高度为：320px',  //专题背景图片
    ),
  ),

  /**
   * 上传图片缩略图规格配置（后台上传图片的时候将根据以下配置生成对应的缩略图规格图片供前端调用）
   */
  'UPLOAD_PIC_THUMB_SIZE' => array(
    //商品图片
    'ITEM_PIC' => array(
      array(
        'WIDTH' => 400,
        'HEIGHT' => 400,
      ),
      array(
        'WIDTH' => 220,
        'HEIGHT' => 220,
      ),
      array(
        'WIDTH' => 100,
        'HEIGHT' => 100,
      ),
    ),
    //广告图片
    'AD_PIC' => array(
      'INDEX_SLIDE' => array(
        array(
          'WIDTH' => 720,
          'HEIGHT' => 320,
        ),
        array(
          'WIDTH' => 100,
          'HEIGHT' => 40,
        ),
      ),
      'INDEX_MIDDLE_BANNER' => array(
        array(
          'WIDTH' => 720,
          'HEIGHT' => 160,
        ),
        array(
          'WIDTH' => 100,
          'HEIGHT' => 40,
        ),
      ),
      'INDEX_BOTTOM_BANNER' => array(
        array(
          'WIDTH' => 720,
          'HEIGHT' => 160,
        ),
        array(
          'WIDTH' => 100,
          'HEIGHT' => 40,
        ),
      ),                  
    ),
    //专题图片
    'TOPIC_PIC' => array(
      array(
        'WIDTH' => 720,
        'HEIGHT' => 320,
      ),
      array(
        'WIDTH' => 120,
        'HEIGHT' => 53,
      ),
      array(
        'WIDTH' => 70,
        'HEIGHT' => 30,
      ),
    ),
  ),
);


$data_config = include(CONF_PATH.'/config.cash.php');
return @array_merge($config,(array)$data_config);
