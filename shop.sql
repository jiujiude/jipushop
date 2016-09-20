/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-07-25 14:01:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for jipu_action
-- ----------------------------
DROP TABLE IF EXISTS `jipu_action`;
CREATE TABLE `jipu_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text COMMENT '行为规则',
  `log` text COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

-- ----------------------------
-- Records of jipu_action
-- ----------------------------
INSERT INTO `jipu_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;\r\ntable:ScoreLog|method:add|field:uid,type,amount,memo,create_time|value:{$self},in,10,每日登录奖励,{$time}|cycle:24|max:1;', '[user|get_nickname]在[time|time_format]登录了后台', '1', '1', '1461029548');
INSERT INTO `jipu_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', '2', '0', '1380173180');
INSERT INTO `jipu_action` VALUES ('3', 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', '2', '1', '1383285646');
INSERT INTO `jipu_action` VALUES ('4', 'add_document', '发表文档', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', '[user|get_nickname]在[time|time_format]发表了一篇文章。\r\n表[model]，记录编号[record]。', '2', '0', '1386139726');
INSERT INTO `jipu_action` VALUES ('5', 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', '2', '0', '1383285551');
INSERT INTO `jipu_action` VALUES ('6', 'update_config', '更新配置', '新增或修改或删除配置', '', '', '2', '1', '1466995702');
INSERT INTO `jipu_action` VALUES ('7', 'update_model', '更新模型', '新增或修改模型', '', '', '1', '1', '1383295057');
INSERT INTO `jipu_action` VALUES ('8', 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', '1', '1', '1383295963');
INSERT INTO `jipu_action` VALUES ('9', 'update_channel', '更新导航', '新增或修改或删除导航', '', '', '1', '1', '1383296301');
INSERT INTO `jipu_action` VALUES ('10', 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', '1', '1', '1383296392');
INSERT INTO `jipu_action` VALUES ('11', 'update_category', '更新分类', '新增或修改或删除分类', '', '', '1', '1', '1383296765');
INSERT INTO `jipu_action` VALUES ('12', 'update_item', '更新商品', '新增，修改，删除，上架，下架商品', '', '', '1', '1', '1431058373');
INSERT INTO `jipu_action` VALUES ('13', 'update_item_category', '更新产品分类', '更新产品分类', '', '', '1', '1', '1431058423');
INSERT INTO `jipu_action` VALUES ('14', 'update_item_attribute', '更新商品属性', '更新商品属性', '', '', '1', '1', '1431058465');
INSERT INTO `jipu_action` VALUES ('15', 'update_item_specification', '更新商品规格', '更新商品规格', '', '', '1', '1', '1431058517');
INSERT INTO `jipu_action` VALUES ('16', 'clear_item_recycle', '清空商品回收站', '清空商品回收站', '', '', '1', '1', '1431058557');
INSERT INTO `jipu_action` VALUES ('17', 'permit_item_recycle', '还原商品回收站的商品', '还原商品回收站的商品', '', '', '1', '1', '1431065809');
INSERT INTO `jipu_action` VALUES ('18', 'create_item_qrcode', '批量生成商品二维码', '批量生成商品二维码', '', '', '1', '1', '1431065829');
INSERT INTO `jipu_action` VALUES ('19', 'update_order', '更新订单', '修改订单价格', '', '', '1', '1', '1431065851');
INSERT INTO `jipu_action` VALUES ('20', 'cancel_order_admin', '后台管理员取消订单', '后台管理员取消订单', '', '', '1', '1', '1431065866');
INSERT INTO `jipu_action` VALUES ('21', 'add_order_ship', '订单发货', '订单发货', '', '', '1', '1', '1431065882');
INSERT INTO `jipu_action` VALUES ('22', 'del_order_recycle', '删除回收站订单', '彻底删除回收站中的订单', '', '', '1', '1', '1431065900');
INSERT INTO `jipu_action` VALUES ('23', 'refund_order_alipay', '支付宝批量退款', '支付宝批量退款', '', '', '1', '1', '1431065916');
INSERT INTO `jipu_action` VALUES ('24', 'refund_order', '单笔退款', '单笔退款', '', '', '1', '1', '1431065929');
INSERT INTO `jipu_action` VALUES ('25', 'update_delivery', '更新运费模板', '添加、编辑、删除运费模板', '', '', '1', '1', '1431065950');
INSERT INTO `jipu_action` VALUES ('26', 'update_redpacket', '更新红包', '添加、编辑、删除红包', '', '', '1', '1', '1431065967');
INSERT INTO `jipu_action` VALUES ('27', 'update_coupon', '更新优惠券', '添加、编辑、删除优惠券', '', '', '1', '1', '1431065985');
INSERT INTO `jipu_action` VALUES ('28', 'update_card', '更新礼品卡', '添加、编辑、删除礼品卡', '', '', '1', '1', '1431066004');
INSERT INTO `jipu_action` VALUES ('29', 'update_activity', '更新专场', '添加、编辑、删除专场', '', '', '1', '1', '1431066026');
INSERT INTO `jipu_action` VALUES ('30', 'update_advertise', '更新广告', '添加、编辑、删除广告', '', '', '1', '1', '1431066044');
INSERT INTO `jipu_action` VALUES ('31', 'update_finance', '更新账户余额', '修改用户账户余额', '', '', '1', '1', '1431066062');
INSERT INTO `jipu_action` VALUES ('32', 'update_score', '更新积分', '修改用户积分', '', '', '1', '1', '1431066079');
INSERT INTO `jipu_action` VALUES ('33', 'update_user', '更新用户信息', '修改用户信息', '', '', '1', '1', '1431066096');
INSERT INTO `jipu_action` VALUES ('34', 'update_auth_manger', '用户组授权', '修改用户组授权', '', '', '1', '1', '1431066115');
INSERT INTO `jipu_action` VALUES ('35', 'user_reg', '用户注册', '用户注册送5元余额', 'table:member|field:finance|condition:uid={$self}|rule:5|cycle:24|max:1;', '', '1', '1', '1432777748');
INSERT INTO `jipu_action` VALUES ('36', 'recycle_user', '回收站-回收用户', '回收站回收用户', '', '', '2', '1', '1466996097');

-- ----------------------------
-- Table structure for jipu_action_log
-- ----------------------------
DROP TABLE IF EXISTS `jipu_action_log`;
CREATE TABLE `jipu_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of jipu_action_log
-- ----------------------------
INSERT INTO `jipu_action_log` VALUES ('1', '1', '1', '2130706433', 'member', '1', '管理员在2016-07-25 12:05登录了后台', '1', '1469419549');
INSERT INTO `jipu_action_log` VALUES ('2', '1', '1', '2130706433', 'member', '1', '管理员在2016-07-25 12:07登录了后台', '1', '1469419658');
INSERT INTO `jipu_action_log` VALUES ('3', '10', '1', '2130706433', 'Menu', '0', '操作url：/Admin/Menu/del/id/353.html?formhash=11c32cdb5825d054842d03b6460807b4', '1', '1469419876');
INSERT INTO `jipu_action_log` VALUES ('4', '1', '1', '2130706433', 'member', '1', '管理员在2016-07-25 12:12登录了后台', '1', '1469419928');
INSERT INTO `jipu_action_log` VALUES ('5', '34', '1', '2130706433', 'authManager', '12', '操作url：/Admin/AuthManager/addToGroup.html', '1', '1469421504');
INSERT INTO `jipu_action_log` VALUES ('6', '1', '1', '2130706433', 'member', '1', '管理员在2016-07-25 12:39登录了后台', '1', '1469421599');
INSERT INTO `jipu_action_log` VALUES ('7', '34', '1', '2130706433', 'authManager', '12', '操作url：/Admin/AuthManager/addToGroup.html', '1', '1469421659');
INSERT INTO `jipu_action_log` VALUES ('8', '1', '2', '2130706433', 'member', '2', 'shop在2016-07-25 12:42登录了后台', '1', '1469421732');
INSERT INTO `jipu_action_log` VALUES ('9', '1', '2', '2130706433', 'member', '2', 'shop在2016-07-25 12:45登录了后台', '1', '1469421924');
INSERT INTO `jipu_action_log` VALUES ('10', '6', '2', '2130706433', 'config', '75', '操作url：/Admin/Config/edit.html', '1', '1469425268');

-- ----------------------------
-- Table structure for jipu_activity
-- ----------------------------
DROP TABLE IF EXISTS `jipu_activity`;
CREATE TABLE `jipu_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `tid` smallint(4) NOT NULL DEFAULT '0' COMMENT '专场/专场分类ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '专场/专场类别：1-手机版，2-电脑版',
  `letter` varchar(20) NOT NULL COMMENT '首字母',
  `name` varchar(255) NOT NULL COMMENT '专场名称',
  `alais` varchar(255) NOT NULL COMMENT '专场标识',
  `discount` varchar(50) NOT NULL COMMENT '专场折扣',
  `keywords` varchar(255) NOT NULL COMMENT '页面关键词，用于SEO',
  `description` varchar(255) NOT NULL COMMENT '页面描述，用于SEO',
  `background` int(10) NOT NULL COMMENT '专场背景图片',
  `backcolor` varchar(7) DEFAULT NULL,
  `src` varchar(255) DEFAULT '' COMMENT '背景图片路径',
  `theme` varchar(50) NOT NULL DEFAULT 'default' COMMENT '专场模板',
  `items` varchar(255) NOT NULL DEFAULT '0' COMMENT '商品列表',
  `content` text NOT NULL COMMENT '专场内容',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶，0-不置顶，大于0数字越大越靠前',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐，0-不置顶，大于0数字越大越靠前',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '专场状态：1-可用，0-不可用',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专场/专场表';

-- ----------------------------
-- Records of jipu_activity
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_addons
-- ----------------------------
DROP TABLE IF EXISTS `jipu_addons`;
CREATE TABLE `jipu_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of jipu_addons
-- ----------------------------
INSERT INTO `jipu_addons` VALUES ('1', 'UploadImages', '图片批量上传', '图片批量上传插件', '1', 'null', 'Max', '0.2', '1420297767', '0');
INSERT INTO `jipu_addons` VALUES ('2', 'ItemSelect', '弹窗选择商品', '弹窗选择商品', '1', 'null', 'Max', '0.1', '1420297797', '0');
INSERT INTO `jipu_addons` VALUES ('3', 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1420297804', '0');
INSERT INTO `jipu_addons` VALUES ('4', 'CategorySelect', '商品分类三级联动', '商品分类三级联动', '1', 'null', 'Jacky.Liu', '0.1', '1420297813', '0');
INSERT INTO `jipu_addons` VALUES ('5', 'AreaSelect', '地区三级联动', '省市县地区三级联动', '1', 'null', 'Max', '0.1', '1420297818', '0');
INSERT INTO `jipu_addons` VALUES ('6', 'UserGuide', 'JipuShop操作帮助', 'JipuShop用户操作快速上手', '1', '{\"title\":\"\\u5feb\\u901f\\u4e0a\\u624b\",\"width\":\"4\",\"display\":\"1\"}', 'Max', '0.1', '1420961143', '0');
INSERT INTO `jipu_addons` VALUES ('7', 'SiteStat', '站点统计信息', '统计站点的基础信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"4\",\"display\":\"1\"}', 'thinkphp', '0.1', '1421060247', '0');
INSERT INTO `jipu_addons` VALUES ('8', 'Edit', '编辑器', '百度UMEditor', '1', 'null', '庾文辉', '0.1', '1421111168', '0');
INSERT INTO `jipu_addons` VALUES ('9', 'WeixinPay', '微信支付', '微信支付插件', '1', 'null', 'chunkuan', '0.1', '1421980492', '0');
INSERT INTO `jipu_addons` VALUES ('10', 'Advertise', '广告', '用于调用广告', '1', 'null', 'Max', '0.1', '1429061205', '1');
INSERT INTO `jipu_addons` VALUES ('11', 'ItemSel', '商品选择器', '通用型商品选择器', '1', 'null', 'chunkuan', '0.1', '1429061205', '0');
INSERT INTO `jipu_addons` VALUES ('17', 'UserSel', '用户选择器', '通用型用户选择器', '1', 'null', 'Justin', '0.1', '1434353460', '0');
INSERT INTO `jipu_addons` VALUES ('18', 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"1\",\"editor_wysiwyg\":1,\"editor_height\":\"300px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1455520014', '0');
INSERT INTO `jipu_addons` VALUES ('19', 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', '1', '{\"comment_type\":\"1\",\"comment_uid_youyan\":\"90040\",\"comment_short_name_duoshuo\":\"\",\"comment_form_pos_duoshuo\":\"buttom\",\"comment_data_list_duoshuo\":\"10\",\"comment_data_order_duoshuo\":\"asc\"}', 'thinkphp', '0.1', '1455680027', '0');
INSERT INTO `jipu_addons` VALUES ('20', 'Upload', '附加上传', '用于上传图片、文件的插件', '1', '{\"random\":\"1\"}', 'Max.Yu', '1.0', '1455680033', '0');
INSERT INTO `jipu_addons` VALUES ('21', 'Attachment', '附件', '用于文档模型上传附件', '1', 'null', 'thinkphp', '0.1', '1455680038', '1');
INSERT INTO `jipu_addons` VALUES ('22', 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1455700482', '0');
INSERT INTO `jipu_addons` VALUES ('23', 'ActivitySelect', '专场选择', '商品专场选择，后期增加搜索支持', '1', 'null', 'Max.Yu', '1.0', '1455700489', '0');
INSERT INTO `jipu_addons` VALUES ('24', 'ImageTextMsg', '图文消息内容选择器', '微信推送内容选择器', '1', 'null', 'Max.Yu', '1.0', '1460105107', '0');
INSERT INTO `jipu_addons` VALUES ('25', 'ArticleCategory', '文章分类联动', '文章分类联动', '1', 'null', 'Max.Yu', '1.0', '1460105519', '0');
INSERT INTO `jipu_addons` VALUES ('26', 'DevTeam', '开发团队信息', '开发团队成员信息', '1', '{\"title\":\"OneThink\\u5f00\\u53d1\\u56e2\\u961f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1462463781', '0');
INSERT INTO `jipu_addons` VALUES ('28', 'Language', '简繁体转换', '简繁体转换', '1', '{\"lang\":\"0\",\"display\":\"1\"}', 'Jipushop', '0.1', '1465293682', '0');
INSERT INTO `jipu_addons` VALUES ('29', 'MobileNav', 'wap端导航', 'wap端头部返回上一层页面', '1', '{\"display\":\"1\"}', 'Jipushop', '0.1', '1466577751', '0');
INSERT INTO `jipu_addons` VALUES ('30', 'Notice', '消息提示', '用于提示用户信息', '1', '{\"notice_type\":\"1\",\"notice_status\":\"0\",\"notice_text\":\"\\u6d4b\\u8bd5\\u6570\\u636e\\uff0c\\u8bf7\\u52ff\\u8d2d\\u4e70\\uff01\",\"notice_title\":\"\\u63d0\\u793a\\u4fe1\\u606f\",\"time\":\"3600000\"}', 'jipushop', '0.1', '1467182643', '0');

-- ----------------------------
-- Table structure for jipu_advertise
-- ----------------------------
DROP TABLE IF EXISTS `jipu_advertise`;
CREATE TABLE `jipu_advertise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `tid` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '广告位置：1-首页顶部幻灯，2-首页中部横幅，3-首页底部横幅',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '广告标题',
  `image` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '广告图片id',
  `src` varchar(255) NOT NULL DEFAULT '' COMMENT '广告图片路径',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '广告链接地址',
  `sort` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '广告排序',
  `remark` text NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:启用,0:未启用',
  `update_time` int(10) DEFAULT '0' COMMENT '当前时间戳',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_advertise
-- ----------------------------
INSERT INTO `jipu_advertise` VALUES ('1', '1', '限时秒杀', '769', '', 'http://www.jipushop.com/Item/detail/id/37.html', '3', '', '1', '1461293265');
INSERT INTO `jipu_advertise` VALUES ('2', '1', '微商城体验', '779', '', 'http://jipushop.com', '1', '', '1', '1461398706');
INSERT INTO `jipu_advertise` VALUES ('3', '1', '买送营销', '759', '', 'http://www.jipushop.com/Item/detail/id/129.html', '4', '', '1', '1461235802');
INSERT INTO `jipu_advertise` VALUES ('4', '1', '红包及优惠券领取', '771', '', 'http://www.jipushop.com/Coupon/index.html', '1', '', '1', '1461300841');
INSERT INTO `jipu_advertise` VALUES ('5', '1', '第二件折扣', '770', '', 'http://www.jipushop.com/Item/detail/id/125.html', '2', '', '1', '1461293509');
INSERT INTO `jipu_advertise` VALUES ('6', '2', '专题活动', '704', '', 'http://jipushop.com/Activity/detail/id/1.html', '0', '便宜装美家', '1', '1466651564');
INSERT INTO `jipu_advertise` VALUES ('7', '2', '智能家具', '702', '', 'http://jipushop.com/Item/search/cid/2.html', '0', '超越科技，改变未来', '1', '1458895716');
INSERT INTO `jipu_advertise` VALUES ('8', '2', '进口美食', '705', '', 'http://jipushop.com/Item/search/cid/3.html', '0', '进口美食，五折封顶', '1', '1459996851');
INSERT INTO `jipu_advertise` VALUES ('9', '2', '当季鲜果', '706', '', 'http://jipushop.com/Item/search/cid/5.html', '0', '新鲜水果，抢先品尝', '1', '1458895950');
INSERT INTO `jipu_advertise` VALUES ('10', '2', '风尚美妆', '703', '', 'http://jipushop.com/Item/search/cid/26.html', '0', '整妆换新，玩转魅力', '1', '1458896183');
INSERT INTO `jipu_advertise` VALUES ('11', '2', '品质四件套', '707', '', 'http://jipushop.com/Item/search/cid/4.html', '0', '全棉纯色四件套', '1', '1458896830');
INSERT INTO `jipu_advertise` VALUES ('13', '3', '母亲节', '790', '', '#', '0', '', '0', '1462690916');

-- ----------------------------
-- Table structure for jipu_area
-- ----------------------------
DROP TABLE IF EXISTS `jipu_area`;
CREATE TABLE `jipu_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(255) NOT NULL COMMENT '地区名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级地区ID',
  `sort` int(11) NOT NULL COMMENT '排序值',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=910008 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_area
-- ----------------------------
INSERT INTO `jipu_area` VALUES ('110000', '北京市', '0', '1');
INSERT INTO `jipu_area` VALUES ('110100', '北京市', '110000', '1');
INSERT INTO `jipu_area` VALUES ('110101', '东城区', '110100', '1');
INSERT INTO `jipu_area` VALUES ('110102', '西城区', '110100', '2');
INSERT INTO `jipu_area` VALUES ('110103', '崇文区', '110100', '3');
INSERT INTO `jipu_area` VALUES ('110104', '宣武区', '110100', '4');
INSERT INTO `jipu_area` VALUES ('110105', '朝阳区', '110100', '5');
INSERT INTO `jipu_area` VALUES ('110106', '丰台区', '110100', '6');
INSERT INTO `jipu_area` VALUES ('110107', '石景山区', '110100', '7');
INSERT INTO `jipu_area` VALUES ('110108', '海淀区', '110100', '8');
INSERT INTO `jipu_area` VALUES ('110109', '门头沟区', '110100', '9');
INSERT INTO `jipu_area` VALUES ('110111', '房山区', '110100', '10');
INSERT INTO `jipu_area` VALUES ('110112', '通州区', '110100', '11');
INSERT INTO `jipu_area` VALUES ('110113', '顺义区', '110100', '12');
INSERT INTO `jipu_area` VALUES ('110114', '昌平区', '110100', '13');
INSERT INTO `jipu_area` VALUES ('110115', '大兴区', '110100', '14');
INSERT INTO `jipu_area` VALUES ('110116', '怀柔区', '110100', '15');
INSERT INTO `jipu_area` VALUES ('110117', '平谷区', '110100', '16');
INSERT INTO `jipu_area` VALUES ('110228', '密云县', '110200', '1');
INSERT INTO `jipu_area` VALUES ('110229', '延庆县', '110200', '2');
INSERT INTO `jipu_area` VALUES ('120000', '天津市', '0', '2');
INSERT INTO `jipu_area` VALUES ('120100', '市辖区', '120000', '1');
INSERT INTO `jipu_area` VALUES ('120101', '和平区', '120100', '1');
INSERT INTO `jipu_area` VALUES ('120102', '河东区', '120100', '2');
INSERT INTO `jipu_area` VALUES ('120103', '河西区', '120100', '3');
INSERT INTO `jipu_area` VALUES ('120104', '南开区', '120100', '4');
INSERT INTO `jipu_area` VALUES ('120105', '河北区', '120100', '5');
INSERT INTO `jipu_area` VALUES ('120106', '红桥区', '120100', '6');
INSERT INTO `jipu_area` VALUES ('120107', '塘沽区', '120100', '7');
INSERT INTO `jipu_area` VALUES ('120108', '汉沽区', '120100', '8');
INSERT INTO `jipu_area` VALUES ('120109', '大港区', '120100', '9');
INSERT INTO `jipu_area` VALUES ('120110', '东丽区', '120100', '10');
INSERT INTO `jipu_area` VALUES ('120111', '西青区', '120100', '11');
INSERT INTO `jipu_area` VALUES ('120112', '津南区', '120100', '12');
INSERT INTO `jipu_area` VALUES ('120113', '北辰区', '120100', '13');
INSERT INTO `jipu_area` VALUES ('120114', '武清区', '120100', '14');
INSERT INTO `jipu_area` VALUES ('120115', '宝坻区', '120100', '15');
INSERT INTO `jipu_area` VALUES ('120200', '县', '120000', '2');
INSERT INTO `jipu_area` VALUES ('120221', '宁河县', '120200', '1');
INSERT INTO `jipu_area` VALUES ('120223', '静海县', '120200', '2');
INSERT INTO `jipu_area` VALUES ('120225', '蓟　县', '120200', '3');
INSERT INTO `jipu_area` VALUES ('130000', '河北省', '0', '3');
INSERT INTO `jipu_area` VALUES ('130100', '石家庄市', '130000', '1');
INSERT INTO `jipu_area` VALUES ('130101', '市辖区', '130100', '1');
INSERT INTO `jipu_area` VALUES ('130102', '长安区', '130100', '2');
INSERT INTO `jipu_area` VALUES ('130103', '桥东区', '130100', '3');
INSERT INTO `jipu_area` VALUES ('130104', '桥西区', '130100', '4');
INSERT INTO `jipu_area` VALUES ('130105', '新华区', '130100', '5');
INSERT INTO `jipu_area` VALUES ('130107', '井陉矿区', '130100', '6');
INSERT INTO `jipu_area` VALUES ('130108', '裕华区', '130100', '7');
INSERT INTO `jipu_area` VALUES ('130121', '井陉县', '130100', '8');
INSERT INTO `jipu_area` VALUES ('130123', '正定县', '130100', '9');
INSERT INTO `jipu_area` VALUES ('130124', '栾城县', '130100', '10');
INSERT INTO `jipu_area` VALUES ('130125', '行唐县', '130100', '11');
INSERT INTO `jipu_area` VALUES ('130126', '灵寿县', '130100', '12');
INSERT INTO `jipu_area` VALUES ('130127', '高邑县', '130100', '13');
INSERT INTO `jipu_area` VALUES ('130128', '深泽县', '130100', '14');
INSERT INTO `jipu_area` VALUES ('130129', '赞皇县', '130100', '15');
INSERT INTO `jipu_area` VALUES ('130130', '无极县', '130100', '16');
INSERT INTO `jipu_area` VALUES ('130131', '平山县', '130100', '17');
INSERT INTO `jipu_area` VALUES ('130132', '元氏县', '130100', '18');
INSERT INTO `jipu_area` VALUES ('130133', '赵　县', '130100', '19');
INSERT INTO `jipu_area` VALUES ('130181', '辛集市', '130100', '20');
INSERT INTO `jipu_area` VALUES ('130182', '藁城市', '130100', '21');
INSERT INTO `jipu_area` VALUES ('130183', '晋州市', '130100', '22');
INSERT INTO `jipu_area` VALUES ('130184', '新乐市', '130100', '23');
INSERT INTO `jipu_area` VALUES ('130185', '鹿泉市', '130100', '24');
INSERT INTO `jipu_area` VALUES ('130200', '唐山市', '130000', '2');
INSERT INTO `jipu_area` VALUES ('130201', '市辖区', '130200', '1');
INSERT INTO `jipu_area` VALUES ('130202', '路南区', '130200', '2');
INSERT INTO `jipu_area` VALUES ('130203', '路北区', '130200', '3');
INSERT INTO `jipu_area` VALUES ('130204', '古冶区', '130200', '4');
INSERT INTO `jipu_area` VALUES ('130205', '开平区', '130200', '5');
INSERT INTO `jipu_area` VALUES ('130207', '丰南区', '130200', '6');
INSERT INTO `jipu_area` VALUES ('130208', '丰润区', '130200', '7');
INSERT INTO `jipu_area` VALUES ('130223', '滦　县', '130200', '8');
INSERT INTO `jipu_area` VALUES ('130224', '滦南县', '130200', '9');
INSERT INTO `jipu_area` VALUES ('130225', '乐亭县', '130200', '10');
INSERT INTO `jipu_area` VALUES ('130227', '迁西县', '130200', '11');
INSERT INTO `jipu_area` VALUES ('130229', '玉田县', '130200', '12');
INSERT INTO `jipu_area` VALUES ('130230', '唐海县', '130200', '13');
INSERT INTO `jipu_area` VALUES ('130281', '遵化市', '130200', '14');
INSERT INTO `jipu_area` VALUES ('130283', '迁安市', '130200', '15');
INSERT INTO `jipu_area` VALUES ('130300', '秦皇岛市', '130000', '3');
INSERT INTO `jipu_area` VALUES ('130301', '市辖区', '130300', '1');
INSERT INTO `jipu_area` VALUES ('130302', '海港区', '130300', '2');
INSERT INTO `jipu_area` VALUES ('130303', '山海关区', '130300', '3');
INSERT INTO `jipu_area` VALUES ('130304', '北戴河区', '130300', '4');
INSERT INTO `jipu_area` VALUES ('130321', '青龙满族自治县', '130300', '5');
INSERT INTO `jipu_area` VALUES ('130322', '昌黎县', '130300', '6');
INSERT INTO `jipu_area` VALUES ('130323', '抚宁县', '130300', '7');
INSERT INTO `jipu_area` VALUES ('130324', '卢龙县', '130300', '8');
INSERT INTO `jipu_area` VALUES ('130400', '邯郸市', '130000', '4');
INSERT INTO `jipu_area` VALUES ('130401', '市辖区', '130400', '1');
INSERT INTO `jipu_area` VALUES ('130402', '邯山区', '130400', '2');
INSERT INTO `jipu_area` VALUES ('130403', '丛台区', '130400', '3');
INSERT INTO `jipu_area` VALUES ('130404', '复兴区', '130400', '4');
INSERT INTO `jipu_area` VALUES ('130406', '峰峰矿区', '130400', '5');
INSERT INTO `jipu_area` VALUES ('130421', '邯郸县', '130400', '6');
INSERT INTO `jipu_area` VALUES ('130423', '临漳县', '130400', '7');
INSERT INTO `jipu_area` VALUES ('130424', '成安县', '130400', '8');
INSERT INTO `jipu_area` VALUES ('130425', '大名县', '130400', '9');
INSERT INTO `jipu_area` VALUES ('130426', '涉　县', '130400', '10');
INSERT INTO `jipu_area` VALUES ('130427', '磁　县', '130400', '11');
INSERT INTO `jipu_area` VALUES ('130428', '肥乡县', '130400', '12');
INSERT INTO `jipu_area` VALUES ('130429', '永年县', '130400', '13');
INSERT INTO `jipu_area` VALUES ('130430', '邱　县', '130400', '14');
INSERT INTO `jipu_area` VALUES ('130431', '鸡泽县', '130400', '15');
INSERT INTO `jipu_area` VALUES ('130432', '广平县', '130400', '16');
INSERT INTO `jipu_area` VALUES ('130433', '馆陶县', '130400', '17');
INSERT INTO `jipu_area` VALUES ('130434', '魏　县', '130400', '18');
INSERT INTO `jipu_area` VALUES ('130435', '曲周县', '130400', '19');
INSERT INTO `jipu_area` VALUES ('130481', '武安市', '130400', '20');
INSERT INTO `jipu_area` VALUES ('130500', '邢台市', '130000', '5');
INSERT INTO `jipu_area` VALUES ('130501', '市辖区', '130500', '1');
INSERT INTO `jipu_area` VALUES ('130502', '桥东区', '130500', '2');
INSERT INTO `jipu_area` VALUES ('130503', '桥西区', '130500', '3');
INSERT INTO `jipu_area` VALUES ('130521', '邢台县', '130500', '4');
INSERT INTO `jipu_area` VALUES ('130522', '临城县', '130500', '5');
INSERT INTO `jipu_area` VALUES ('130523', '内丘县', '130500', '6');
INSERT INTO `jipu_area` VALUES ('130524', '柏乡县', '130500', '7');
INSERT INTO `jipu_area` VALUES ('130525', '隆尧县', '130500', '8');
INSERT INTO `jipu_area` VALUES ('130526', '任　县', '130500', '9');
INSERT INTO `jipu_area` VALUES ('130527', '南和县', '130500', '10');
INSERT INTO `jipu_area` VALUES ('130528', '宁晋县', '130500', '11');
INSERT INTO `jipu_area` VALUES ('130529', '巨鹿县', '130500', '12');
INSERT INTO `jipu_area` VALUES ('130530', '新河县', '130500', '13');
INSERT INTO `jipu_area` VALUES ('130531', '广宗县', '130500', '14');
INSERT INTO `jipu_area` VALUES ('130532', '平乡县', '130500', '15');
INSERT INTO `jipu_area` VALUES ('130533', '威　县', '130500', '16');
INSERT INTO `jipu_area` VALUES ('130534', '清河县', '130500', '17');
INSERT INTO `jipu_area` VALUES ('130535', '临西县', '130500', '18');
INSERT INTO `jipu_area` VALUES ('130581', '南宫市', '130500', '19');
INSERT INTO `jipu_area` VALUES ('130582', '沙河市', '130500', '20');
INSERT INTO `jipu_area` VALUES ('130600', '保定市', '130000', '6');
INSERT INTO `jipu_area` VALUES ('130601', '市辖区', '130600', '1');
INSERT INTO `jipu_area` VALUES ('130602', '新市区', '130600', '2');
INSERT INTO `jipu_area` VALUES ('130603', '北市区', '130600', '3');
INSERT INTO `jipu_area` VALUES ('130604', '南市区', '130600', '4');
INSERT INTO `jipu_area` VALUES ('130621', '满城县', '130600', '5');
INSERT INTO `jipu_area` VALUES ('130622', '清苑县', '130600', '6');
INSERT INTO `jipu_area` VALUES ('130623', '涞水县', '130600', '7');
INSERT INTO `jipu_area` VALUES ('130624', '阜平县', '130600', '8');
INSERT INTO `jipu_area` VALUES ('130625', '徐水县', '130600', '9');
INSERT INTO `jipu_area` VALUES ('130626', '定兴县', '130600', '10');
INSERT INTO `jipu_area` VALUES ('130627', '唐　县', '130600', '11');
INSERT INTO `jipu_area` VALUES ('130628', '高阳县', '130600', '12');
INSERT INTO `jipu_area` VALUES ('130629', '容城县', '130600', '13');
INSERT INTO `jipu_area` VALUES ('130630', '涞源县', '130600', '14');
INSERT INTO `jipu_area` VALUES ('130631', '望都县', '130600', '15');
INSERT INTO `jipu_area` VALUES ('130632', '安新县', '130600', '16');
INSERT INTO `jipu_area` VALUES ('130633', '易　县', '130600', '17');
INSERT INTO `jipu_area` VALUES ('130634', '曲阳县', '130600', '18');
INSERT INTO `jipu_area` VALUES ('130635', '蠡　县', '130600', '19');
INSERT INTO `jipu_area` VALUES ('130636', '顺平县', '130600', '20');
INSERT INTO `jipu_area` VALUES ('130637', '博野县', '130600', '21');
INSERT INTO `jipu_area` VALUES ('130638', '雄　县', '130600', '22');
INSERT INTO `jipu_area` VALUES ('130681', '涿州市', '130600', '23');
INSERT INTO `jipu_area` VALUES ('130682', '定州市', '130600', '24');
INSERT INTO `jipu_area` VALUES ('130683', '安国市', '130600', '25');
INSERT INTO `jipu_area` VALUES ('130684', '高碑店市', '130600', '26');
INSERT INTO `jipu_area` VALUES ('130700', '张家口市', '130000', '7');
INSERT INTO `jipu_area` VALUES ('130701', '市辖区', '130700', '1');
INSERT INTO `jipu_area` VALUES ('130702', '桥东区', '130700', '2');
INSERT INTO `jipu_area` VALUES ('130703', '桥西区', '130700', '3');
INSERT INTO `jipu_area` VALUES ('130705', '宣化区', '130700', '4');
INSERT INTO `jipu_area` VALUES ('130706', '下花园区', '130700', '5');
INSERT INTO `jipu_area` VALUES ('130721', '宣化县', '130700', '6');
INSERT INTO `jipu_area` VALUES ('130722', '张北县', '130700', '7');
INSERT INTO `jipu_area` VALUES ('130723', '康保县', '130700', '8');
INSERT INTO `jipu_area` VALUES ('130724', '沽源县', '130700', '9');
INSERT INTO `jipu_area` VALUES ('130725', '尚义县', '130700', '10');
INSERT INTO `jipu_area` VALUES ('130726', '蔚　县', '130700', '11');
INSERT INTO `jipu_area` VALUES ('130727', '阳原县', '130700', '12');
INSERT INTO `jipu_area` VALUES ('130728', '怀安县', '130700', '13');
INSERT INTO `jipu_area` VALUES ('130729', '万全县', '130700', '14');
INSERT INTO `jipu_area` VALUES ('130730', '怀来县', '130700', '15');
INSERT INTO `jipu_area` VALUES ('130731', '涿鹿县', '130700', '16');
INSERT INTO `jipu_area` VALUES ('130732', '赤城县', '130700', '17');
INSERT INTO `jipu_area` VALUES ('130733', '崇礼县', '130700', '18');
INSERT INTO `jipu_area` VALUES ('130800', '承德市', '130000', '8');
INSERT INTO `jipu_area` VALUES ('130801', '市辖区', '130800', '1');
INSERT INTO `jipu_area` VALUES ('130802', '双桥区', '130800', '2');
INSERT INTO `jipu_area` VALUES ('130803', '双滦区', '130800', '3');
INSERT INTO `jipu_area` VALUES ('130804', '鹰手营子矿区', '130800', '4');
INSERT INTO `jipu_area` VALUES ('130821', '承德县', '130800', '5');
INSERT INTO `jipu_area` VALUES ('130822', '兴隆县', '130800', '6');
INSERT INTO `jipu_area` VALUES ('130823', '平泉县', '130800', '7');
INSERT INTO `jipu_area` VALUES ('130824', '滦平县', '130800', '8');
INSERT INTO `jipu_area` VALUES ('130825', '隆化县', '130800', '9');
INSERT INTO `jipu_area` VALUES ('130826', '丰宁满族自治县', '130800', '10');
INSERT INTO `jipu_area` VALUES ('130827', '宽城满族自治县', '130800', '11');
INSERT INTO `jipu_area` VALUES ('130828', '围场满族蒙古族自治县', '130800', '12');
INSERT INTO `jipu_area` VALUES ('130900', '沧州市', '130000', '9');
INSERT INTO `jipu_area` VALUES ('130901', '市辖区', '130900', '1');
INSERT INTO `jipu_area` VALUES ('130902', '新华区', '130900', '2');
INSERT INTO `jipu_area` VALUES ('130903', '运河区', '130900', '3');
INSERT INTO `jipu_area` VALUES ('130921', '沧　县', '130900', '4');
INSERT INTO `jipu_area` VALUES ('130922', '青　县', '130900', '5');
INSERT INTO `jipu_area` VALUES ('130923', '东光县', '130900', '6');
INSERT INTO `jipu_area` VALUES ('130924', '海兴县', '130900', '7');
INSERT INTO `jipu_area` VALUES ('130925', '盐山县', '130900', '8');
INSERT INTO `jipu_area` VALUES ('130926', '肃宁县', '130900', '9');
INSERT INTO `jipu_area` VALUES ('130927', '南皮县', '130900', '10');
INSERT INTO `jipu_area` VALUES ('130928', '吴桥县', '130900', '11');
INSERT INTO `jipu_area` VALUES ('130929', '献　县', '130900', '12');
INSERT INTO `jipu_area` VALUES ('130930', '孟村回族自治县', '130900', '13');
INSERT INTO `jipu_area` VALUES ('130981', '泊头市', '130900', '14');
INSERT INTO `jipu_area` VALUES ('130982', '任丘市', '130900', '15');
INSERT INTO `jipu_area` VALUES ('130983', '黄骅市', '130900', '16');
INSERT INTO `jipu_area` VALUES ('130984', '河间市', '130900', '17');
INSERT INTO `jipu_area` VALUES ('131000', '廊坊市', '130000', '10');
INSERT INTO `jipu_area` VALUES ('131001', '市辖区', '131000', '1');
INSERT INTO `jipu_area` VALUES ('131002', '安次区', '131000', '2');
INSERT INTO `jipu_area` VALUES ('131003', '广阳区', '131000', '3');
INSERT INTO `jipu_area` VALUES ('131022', '固安县', '131000', '4');
INSERT INTO `jipu_area` VALUES ('131023', '永清县', '131000', '5');
INSERT INTO `jipu_area` VALUES ('131024', '香河县', '131000', '6');
INSERT INTO `jipu_area` VALUES ('131025', '大城县', '131000', '7');
INSERT INTO `jipu_area` VALUES ('131026', '文安县', '131000', '8');
INSERT INTO `jipu_area` VALUES ('131028', '大厂回族自治县', '131000', '9');
INSERT INTO `jipu_area` VALUES ('131081', '霸州市', '131000', '10');
INSERT INTO `jipu_area` VALUES ('131082', '三河市', '131000', '11');
INSERT INTO `jipu_area` VALUES ('131100', '衡水市', '130000', '11');
INSERT INTO `jipu_area` VALUES ('131101', '市辖区', '131100', '1');
INSERT INTO `jipu_area` VALUES ('131102', '桃城区', '131100', '2');
INSERT INTO `jipu_area` VALUES ('131121', '枣强县', '131100', '3');
INSERT INTO `jipu_area` VALUES ('131122', '武邑县', '131100', '4');
INSERT INTO `jipu_area` VALUES ('131123', '武强县', '131100', '5');
INSERT INTO `jipu_area` VALUES ('131124', '饶阳县', '131100', '6');
INSERT INTO `jipu_area` VALUES ('131125', '安平县', '131100', '7');
INSERT INTO `jipu_area` VALUES ('131126', '故城县', '131100', '8');
INSERT INTO `jipu_area` VALUES ('131127', '景　县', '131100', '9');
INSERT INTO `jipu_area` VALUES ('131128', '阜城县', '131100', '10');
INSERT INTO `jipu_area` VALUES ('131181', '冀州市', '131100', '11');
INSERT INTO `jipu_area` VALUES ('131182', '深州市', '131100', '12');
INSERT INTO `jipu_area` VALUES ('140000', '山西省', '0', '4');
INSERT INTO `jipu_area` VALUES ('140100', '太原市', '140000', '1');
INSERT INTO `jipu_area` VALUES ('140101', '市辖区', '140100', '1');
INSERT INTO `jipu_area` VALUES ('140105', '小店区', '140100', '2');
INSERT INTO `jipu_area` VALUES ('140106', '迎泽区', '140100', '3');
INSERT INTO `jipu_area` VALUES ('140107', '杏花岭区', '140100', '4');
INSERT INTO `jipu_area` VALUES ('140108', '尖草坪区', '140100', '5');
INSERT INTO `jipu_area` VALUES ('140109', '万柏林区', '140100', '6');
INSERT INTO `jipu_area` VALUES ('140110', '晋源区', '140100', '7');
INSERT INTO `jipu_area` VALUES ('140121', '清徐县', '140100', '8');
INSERT INTO `jipu_area` VALUES ('140122', '阳曲县', '140100', '9');
INSERT INTO `jipu_area` VALUES ('140123', '娄烦县', '140100', '10');
INSERT INTO `jipu_area` VALUES ('140181', '古交市', '140100', '11');
INSERT INTO `jipu_area` VALUES ('140200', '大同市', '140000', '2');
INSERT INTO `jipu_area` VALUES ('140201', '市辖区', '140200', '1');
INSERT INTO `jipu_area` VALUES ('140202', '城　区', '140200', '2');
INSERT INTO `jipu_area` VALUES ('140203', '矿　区', '140200', '3');
INSERT INTO `jipu_area` VALUES ('140211', '南郊区', '140200', '4');
INSERT INTO `jipu_area` VALUES ('140212', '新荣区', '140200', '5');
INSERT INTO `jipu_area` VALUES ('140221', '阳高县', '140200', '6');
INSERT INTO `jipu_area` VALUES ('140222', '天镇县', '140200', '7');
INSERT INTO `jipu_area` VALUES ('140223', '广灵县', '140200', '8');
INSERT INTO `jipu_area` VALUES ('140224', '灵丘县', '140200', '9');
INSERT INTO `jipu_area` VALUES ('140225', '浑源县', '140200', '10');
INSERT INTO `jipu_area` VALUES ('140226', '左云县', '140200', '11');
INSERT INTO `jipu_area` VALUES ('140227', '大同县', '140200', '12');
INSERT INTO `jipu_area` VALUES ('140300', '阳泉市', '140000', '3');
INSERT INTO `jipu_area` VALUES ('140301', '市辖区', '140300', '1');
INSERT INTO `jipu_area` VALUES ('140302', '城　区', '140300', '2');
INSERT INTO `jipu_area` VALUES ('140303', '矿　区', '140300', '3');
INSERT INTO `jipu_area` VALUES ('140311', '郊　区', '140300', '4');
INSERT INTO `jipu_area` VALUES ('140321', '平定县', '140300', '5');
INSERT INTO `jipu_area` VALUES ('140322', '盂　县', '140300', '6');
INSERT INTO `jipu_area` VALUES ('140400', '长治市', '140000', '4');
INSERT INTO `jipu_area` VALUES ('140401', '市辖区', '140400', '1');
INSERT INTO `jipu_area` VALUES ('140402', '城　区', '140400', '2');
INSERT INTO `jipu_area` VALUES ('140411', '郊　区', '140400', '3');
INSERT INTO `jipu_area` VALUES ('140421', '长治县', '140400', '4');
INSERT INTO `jipu_area` VALUES ('140423', '襄垣县', '140400', '5');
INSERT INTO `jipu_area` VALUES ('140424', '屯留县', '140400', '6');
INSERT INTO `jipu_area` VALUES ('140425', '平顺县', '140400', '7');
INSERT INTO `jipu_area` VALUES ('140426', '黎城县', '140400', '8');
INSERT INTO `jipu_area` VALUES ('140427', '壶关县', '140400', '9');
INSERT INTO `jipu_area` VALUES ('140428', '长子县', '140400', '10');
INSERT INTO `jipu_area` VALUES ('140429', '武乡县', '140400', '11');
INSERT INTO `jipu_area` VALUES ('140430', '沁　县', '140400', '12');
INSERT INTO `jipu_area` VALUES ('140431', '沁源县', '140400', '13');
INSERT INTO `jipu_area` VALUES ('140481', '潞城市', '140400', '14');
INSERT INTO `jipu_area` VALUES ('140500', '晋城市', '140000', '5');
INSERT INTO `jipu_area` VALUES ('140501', '市辖区', '140500', '1');
INSERT INTO `jipu_area` VALUES ('140502', '城　区', '140500', '2');
INSERT INTO `jipu_area` VALUES ('140521', '沁水县', '140500', '3');
INSERT INTO `jipu_area` VALUES ('140522', '阳城县', '140500', '4');
INSERT INTO `jipu_area` VALUES ('140524', '陵川县', '140500', '5');
INSERT INTO `jipu_area` VALUES ('140525', '泽州县', '140500', '6');
INSERT INTO `jipu_area` VALUES ('140581', '高平市', '140500', '7');
INSERT INTO `jipu_area` VALUES ('140600', '朔州市', '140000', '6');
INSERT INTO `jipu_area` VALUES ('140601', '市辖区', '140600', '1');
INSERT INTO `jipu_area` VALUES ('140602', '朔城区', '140600', '2');
INSERT INTO `jipu_area` VALUES ('140603', '平鲁区', '140600', '3');
INSERT INTO `jipu_area` VALUES ('140621', '山阴县', '140600', '4');
INSERT INTO `jipu_area` VALUES ('140622', '应　县', '140600', '5');
INSERT INTO `jipu_area` VALUES ('140623', '右玉县', '140600', '6');
INSERT INTO `jipu_area` VALUES ('140624', '怀仁县', '140600', '7');
INSERT INTO `jipu_area` VALUES ('140700', '晋中市', '140000', '7');
INSERT INTO `jipu_area` VALUES ('140701', '市辖区', '140700', '1');
INSERT INTO `jipu_area` VALUES ('140702', '榆次区', '140700', '2');
INSERT INTO `jipu_area` VALUES ('140721', '榆社县', '140700', '3');
INSERT INTO `jipu_area` VALUES ('140722', '左权县', '140700', '4');
INSERT INTO `jipu_area` VALUES ('140723', '和顺县', '140700', '5');
INSERT INTO `jipu_area` VALUES ('140724', '昔阳县', '140700', '6');
INSERT INTO `jipu_area` VALUES ('140725', '寿阳县', '140700', '7');
INSERT INTO `jipu_area` VALUES ('140726', '太谷县', '140700', '8');
INSERT INTO `jipu_area` VALUES ('140727', '祁　县', '140700', '9');
INSERT INTO `jipu_area` VALUES ('140728', '平遥县', '140700', '10');
INSERT INTO `jipu_area` VALUES ('140729', '灵石县', '140700', '11');
INSERT INTO `jipu_area` VALUES ('140781', '介休市', '140700', '12');
INSERT INTO `jipu_area` VALUES ('140800', '运城市', '140000', '8');
INSERT INTO `jipu_area` VALUES ('140801', '市辖区', '140800', '1');
INSERT INTO `jipu_area` VALUES ('140802', '盐湖区', '140800', '2');
INSERT INTO `jipu_area` VALUES ('140821', '临猗县', '140800', '3');
INSERT INTO `jipu_area` VALUES ('140822', '万荣县', '140800', '4');
INSERT INTO `jipu_area` VALUES ('140823', '闻喜县', '140800', '5');
INSERT INTO `jipu_area` VALUES ('140824', '稷山县', '140800', '6');
INSERT INTO `jipu_area` VALUES ('140825', '新绛县', '140800', '7');
INSERT INTO `jipu_area` VALUES ('140826', '绛　县', '140800', '8');
INSERT INTO `jipu_area` VALUES ('140827', '垣曲县', '140800', '9');
INSERT INTO `jipu_area` VALUES ('140828', '夏　县', '140800', '10');
INSERT INTO `jipu_area` VALUES ('140829', '平陆县', '140800', '11');
INSERT INTO `jipu_area` VALUES ('140830', '芮城县', '140800', '12');
INSERT INTO `jipu_area` VALUES ('140881', '永济市', '140800', '13');
INSERT INTO `jipu_area` VALUES ('140882', '河津市', '140800', '14');
INSERT INTO `jipu_area` VALUES ('140900', '忻州市', '140000', '9');
INSERT INTO `jipu_area` VALUES ('140901', '市辖区', '140900', '1');
INSERT INTO `jipu_area` VALUES ('140902', '忻府区', '140900', '2');
INSERT INTO `jipu_area` VALUES ('140921', '定襄县', '140900', '3');
INSERT INTO `jipu_area` VALUES ('140922', '五台县', '140900', '4');
INSERT INTO `jipu_area` VALUES ('140923', '代　县', '140900', '5');
INSERT INTO `jipu_area` VALUES ('140924', '繁峙县', '140900', '6');
INSERT INTO `jipu_area` VALUES ('140925', '宁武县', '140900', '7');
INSERT INTO `jipu_area` VALUES ('140926', '静乐县', '140900', '8');
INSERT INTO `jipu_area` VALUES ('140927', '神池县', '140900', '9');
INSERT INTO `jipu_area` VALUES ('140928', '五寨县', '140900', '10');
INSERT INTO `jipu_area` VALUES ('140929', '岢岚县', '140900', '11');
INSERT INTO `jipu_area` VALUES ('140930', '河曲县', '140900', '12');
INSERT INTO `jipu_area` VALUES ('140931', '保德县', '140900', '13');
INSERT INTO `jipu_area` VALUES ('140932', '偏关县', '140900', '14');
INSERT INTO `jipu_area` VALUES ('140981', '原平市', '140900', '15');
INSERT INTO `jipu_area` VALUES ('141000', '临汾市', '140000', '10');
INSERT INTO `jipu_area` VALUES ('141001', '市辖区', '141000', '1');
INSERT INTO `jipu_area` VALUES ('141002', '尧都区', '141000', '2');
INSERT INTO `jipu_area` VALUES ('141021', '曲沃县', '141000', '3');
INSERT INTO `jipu_area` VALUES ('141022', '翼城县', '141000', '4');
INSERT INTO `jipu_area` VALUES ('141023', '襄汾县', '141000', '5');
INSERT INTO `jipu_area` VALUES ('141024', '洪洞县', '141000', '6');
INSERT INTO `jipu_area` VALUES ('141025', '古　县', '141000', '7');
INSERT INTO `jipu_area` VALUES ('141026', '安泽县', '141000', '8');
INSERT INTO `jipu_area` VALUES ('141027', '浮山县', '141000', '9');
INSERT INTO `jipu_area` VALUES ('141028', '吉　县', '141000', '10');
INSERT INTO `jipu_area` VALUES ('141029', '乡宁县', '141000', '11');
INSERT INTO `jipu_area` VALUES ('141030', '大宁县', '141000', '12');
INSERT INTO `jipu_area` VALUES ('141031', '隰　县', '141000', '13');
INSERT INTO `jipu_area` VALUES ('141032', '永和县', '141000', '14');
INSERT INTO `jipu_area` VALUES ('141033', '蒲　县', '141000', '15');
INSERT INTO `jipu_area` VALUES ('141034', '汾西县', '141000', '16');
INSERT INTO `jipu_area` VALUES ('141081', '侯马市', '141000', '17');
INSERT INTO `jipu_area` VALUES ('141082', '霍州市', '141000', '18');
INSERT INTO `jipu_area` VALUES ('141100', '吕梁市', '140000', '11');
INSERT INTO `jipu_area` VALUES ('141101', '市辖区', '141100', '1');
INSERT INTO `jipu_area` VALUES ('141102', '离石区', '141100', '2');
INSERT INTO `jipu_area` VALUES ('141121', '文水县', '141100', '3');
INSERT INTO `jipu_area` VALUES ('141122', '交城县', '141100', '4');
INSERT INTO `jipu_area` VALUES ('141123', '兴　县', '141100', '5');
INSERT INTO `jipu_area` VALUES ('141124', '临　县', '141100', '6');
INSERT INTO `jipu_area` VALUES ('141125', '柳林县', '141100', '7');
INSERT INTO `jipu_area` VALUES ('141126', '石楼县', '141100', '8');
INSERT INTO `jipu_area` VALUES ('141127', '岚　县', '141100', '9');
INSERT INTO `jipu_area` VALUES ('141128', '方山县', '141100', '10');
INSERT INTO `jipu_area` VALUES ('141129', '中阳县', '141100', '11');
INSERT INTO `jipu_area` VALUES ('141130', '交口县', '141100', '12');
INSERT INTO `jipu_area` VALUES ('141181', '孝义市', '141100', '13');
INSERT INTO `jipu_area` VALUES ('141182', '汾阳市', '141100', '14');
INSERT INTO `jipu_area` VALUES ('150000', '内蒙古', '0', '5');
INSERT INTO `jipu_area` VALUES ('150100', '呼和浩特市', '150000', '1');
INSERT INTO `jipu_area` VALUES ('150101', '市辖区', '150100', '1');
INSERT INTO `jipu_area` VALUES ('150102', '新城区', '150100', '2');
INSERT INTO `jipu_area` VALUES ('150103', '回民区', '150100', '3');
INSERT INTO `jipu_area` VALUES ('150104', '玉泉区', '150100', '4');
INSERT INTO `jipu_area` VALUES ('150105', '赛罕区', '150100', '5');
INSERT INTO `jipu_area` VALUES ('150121', '土默特左旗', '150100', '6');
INSERT INTO `jipu_area` VALUES ('150122', '托克托县', '150100', '7');
INSERT INTO `jipu_area` VALUES ('150123', '和林格尔县', '150100', '8');
INSERT INTO `jipu_area` VALUES ('150124', '清水河县', '150100', '9');
INSERT INTO `jipu_area` VALUES ('150125', '武川县', '150100', '10');
INSERT INTO `jipu_area` VALUES ('150200', '包头市', '150000', '2');
INSERT INTO `jipu_area` VALUES ('150201', '市辖区', '150200', '1');
INSERT INTO `jipu_area` VALUES ('150202', '东河区', '150200', '2');
INSERT INTO `jipu_area` VALUES ('150203', '昆都仑区', '150200', '3');
INSERT INTO `jipu_area` VALUES ('150204', '青山区', '150200', '4');
INSERT INTO `jipu_area` VALUES ('150205', '石拐区', '150200', '5');
INSERT INTO `jipu_area` VALUES ('150206', '白云矿区', '150200', '6');
INSERT INTO `jipu_area` VALUES ('150207', '九原区', '150200', '7');
INSERT INTO `jipu_area` VALUES ('150221', '土默特右旗', '150200', '8');
INSERT INTO `jipu_area` VALUES ('150222', '固阳县', '150200', '9');
INSERT INTO `jipu_area` VALUES ('150223', '达尔罕茂明安联合旗', '150200', '10');
INSERT INTO `jipu_area` VALUES ('150300', '乌海市', '150000', '3');
INSERT INTO `jipu_area` VALUES ('150301', '市辖区', '150300', '1');
INSERT INTO `jipu_area` VALUES ('150302', '海勃湾区', '150300', '2');
INSERT INTO `jipu_area` VALUES ('150303', '海南区', '150300', '3');
INSERT INTO `jipu_area` VALUES ('150304', '乌达区', '150300', '4');
INSERT INTO `jipu_area` VALUES ('150400', '赤峰市', '150000', '4');
INSERT INTO `jipu_area` VALUES ('150401', '市辖区', '150400', '1');
INSERT INTO `jipu_area` VALUES ('150402', '红山区', '150400', '2');
INSERT INTO `jipu_area` VALUES ('150403', '元宝山区', '150400', '3');
INSERT INTO `jipu_area` VALUES ('150404', '松山区', '150400', '4');
INSERT INTO `jipu_area` VALUES ('150421', '阿鲁科尔沁旗', '150400', '5');
INSERT INTO `jipu_area` VALUES ('150422', '巴林左旗', '150400', '6');
INSERT INTO `jipu_area` VALUES ('150423', '巴林右旗', '150400', '7');
INSERT INTO `jipu_area` VALUES ('150424', '林西县', '150400', '8');
INSERT INTO `jipu_area` VALUES ('150425', '克什克腾旗', '150400', '9');
INSERT INTO `jipu_area` VALUES ('150426', '翁牛特旗', '150400', '10');
INSERT INTO `jipu_area` VALUES ('150428', '喀喇沁旗', '150400', '11');
INSERT INTO `jipu_area` VALUES ('150429', '宁城县', '150400', '12');
INSERT INTO `jipu_area` VALUES ('150430', '敖汉旗', '150400', '13');
INSERT INTO `jipu_area` VALUES ('150500', '通辽市', '150000', '5');
INSERT INTO `jipu_area` VALUES ('150501', '市辖区', '150500', '1');
INSERT INTO `jipu_area` VALUES ('150502', '科尔沁区', '150500', '2');
INSERT INTO `jipu_area` VALUES ('150521', '科尔沁左翼中旗', '150500', '3');
INSERT INTO `jipu_area` VALUES ('150522', '科尔沁左翼后旗', '150500', '4');
INSERT INTO `jipu_area` VALUES ('150523', '开鲁县', '150500', '5');
INSERT INTO `jipu_area` VALUES ('150524', '库伦旗', '150500', '6');
INSERT INTO `jipu_area` VALUES ('150525', '奈曼旗', '150500', '7');
INSERT INTO `jipu_area` VALUES ('150526', '扎鲁特旗', '150500', '8');
INSERT INTO `jipu_area` VALUES ('150581', '霍林郭勒市', '150500', '9');
INSERT INTO `jipu_area` VALUES ('150600', '鄂尔多斯市', '150000', '6');
INSERT INTO `jipu_area` VALUES ('150602', '东胜区', '150600', '1');
INSERT INTO `jipu_area` VALUES ('150621', '达拉特旗', '150600', '2');
INSERT INTO `jipu_area` VALUES ('150622', '准格尔旗', '150600', '3');
INSERT INTO `jipu_area` VALUES ('150623', '鄂托克前旗', '150600', '4');
INSERT INTO `jipu_area` VALUES ('150624', '鄂托克旗', '150600', '5');
INSERT INTO `jipu_area` VALUES ('150625', '杭锦旗', '150600', '6');
INSERT INTO `jipu_area` VALUES ('150626', '乌审旗', '150600', '7');
INSERT INTO `jipu_area` VALUES ('150627', '伊金霍洛旗', '150600', '8');
INSERT INTO `jipu_area` VALUES ('150700', '呼伦贝尔市', '150000', '7');
INSERT INTO `jipu_area` VALUES ('150701', '市辖区', '150700', '1');
INSERT INTO `jipu_area` VALUES ('150702', '海拉尔区', '150700', '2');
INSERT INTO `jipu_area` VALUES ('150721', '阿荣旗', '150700', '3');
INSERT INTO `jipu_area` VALUES ('150722', '莫力达瓦达斡尔族自治旗', '150700', '4');
INSERT INTO `jipu_area` VALUES ('150723', '鄂伦春自治旗', '150700', '5');
INSERT INTO `jipu_area` VALUES ('150724', '鄂温克族自治旗', '150700', '6');
INSERT INTO `jipu_area` VALUES ('150725', '陈巴尔虎旗', '150700', '7');
INSERT INTO `jipu_area` VALUES ('150726', '新巴尔虎左旗', '150700', '8');
INSERT INTO `jipu_area` VALUES ('150727', '新巴尔虎右旗', '150700', '9');
INSERT INTO `jipu_area` VALUES ('150781', '满洲里市', '150700', '10');
INSERT INTO `jipu_area` VALUES ('150782', '牙克石市', '150700', '11');
INSERT INTO `jipu_area` VALUES ('150783', '扎兰屯市', '150700', '12');
INSERT INTO `jipu_area` VALUES ('150784', '额尔古纳市', '150700', '13');
INSERT INTO `jipu_area` VALUES ('150785', '根河市', '150700', '14');
INSERT INTO `jipu_area` VALUES ('150800', '巴彦淖尔市', '150000', '8');
INSERT INTO `jipu_area` VALUES ('150801', '市辖区', '150800', '1');
INSERT INTO `jipu_area` VALUES ('150802', '临河区', '150800', '2');
INSERT INTO `jipu_area` VALUES ('150821', '五原县', '150800', '3');
INSERT INTO `jipu_area` VALUES ('150822', '磴口县', '150800', '4');
INSERT INTO `jipu_area` VALUES ('150823', '乌拉特前旗', '150800', '5');
INSERT INTO `jipu_area` VALUES ('150824', '乌拉特中旗', '150800', '6');
INSERT INTO `jipu_area` VALUES ('150825', '乌拉特后旗', '150800', '7');
INSERT INTO `jipu_area` VALUES ('150826', '杭锦后旗', '150800', '8');
INSERT INTO `jipu_area` VALUES ('150900', '乌兰察布市', '150000', '9');
INSERT INTO `jipu_area` VALUES ('150901', '市辖区', '150900', '1');
INSERT INTO `jipu_area` VALUES ('150902', '集宁区', '150900', '2');
INSERT INTO `jipu_area` VALUES ('150921', '卓资县', '150900', '3');
INSERT INTO `jipu_area` VALUES ('150922', '化德县', '150900', '4');
INSERT INTO `jipu_area` VALUES ('150923', '商都县', '150900', '5');
INSERT INTO `jipu_area` VALUES ('150924', '兴和县', '150900', '6');
INSERT INTO `jipu_area` VALUES ('150925', '凉城县', '150900', '7');
INSERT INTO `jipu_area` VALUES ('150926', '察哈尔右翼前旗', '150900', '8');
INSERT INTO `jipu_area` VALUES ('150927', '察哈尔右翼中旗', '150900', '9');
INSERT INTO `jipu_area` VALUES ('150928', '察哈尔右翼后旗', '150900', '10');
INSERT INTO `jipu_area` VALUES ('150929', '四子王旗', '150900', '11');
INSERT INTO `jipu_area` VALUES ('150981', '丰镇市', '150900', '12');
INSERT INTO `jipu_area` VALUES ('152200', '兴安盟', '150000', '10');
INSERT INTO `jipu_area` VALUES ('152201', '乌兰浩特市', '152200', '1');
INSERT INTO `jipu_area` VALUES ('152202', '阿尔山市', '152200', '2');
INSERT INTO `jipu_area` VALUES ('152221', '科尔沁右翼前旗', '152200', '3');
INSERT INTO `jipu_area` VALUES ('152222', '科尔沁右翼中旗', '152200', '4');
INSERT INTO `jipu_area` VALUES ('152223', '扎赉特旗', '152200', '5');
INSERT INTO `jipu_area` VALUES ('152224', '突泉县', '152200', '6');
INSERT INTO `jipu_area` VALUES ('152500', '锡林郭勒盟', '150000', '11');
INSERT INTO `jipu_area` VALUES ('152501', '二连浩特市', '152500', '1');
INSERT INTO `jipu_area` VALUES ('152502', '锡林浩特市', '152500', '2');
INSERT INTO `jipu_area` VALUES ('152522', '阿巴嘎旗', '152500', '3');
INSERT INTO `jipu_area` VALUES ('152523', '苏尼特左旗', '152500', '4');
INSERT INTO `jipu_area` VALUES ('152524', '苏尼特右旗', '152500', '5');
INSERT INTO `jipu_area` VALUES ('152525', '东乌珠穆沁旗', '152500', '6');
INSERT INTO `jipu_area` VALUES ('152526', '西乌珠穆沁旗', '152500', '7');
INSERT INTO `jipu_area` VALUES ('152527', '太仆寺旗', '152500', '8');
INSERT INTO `jipu_area` VALUES ('152528', '镶黄旗', '152500', '9');
INSERT INTO `jipu_area` VALUES ('152529', '正镶白旗', '152500', '10');
INSERT INTO `jipu_area` VALUES ('152530', '正蓝旗', '152500', '11');
INSERT INTO `jipu_area` VALUES ('152531', '多伦县', '152500', '12');
INSERT INTO `jipu_area` VALUES ('152900', '阿拉善盟', '150000', '12');
INSERT INTO `jipu_area` VALUES ('152921', '阿拉善左旗', '152900', '1');
INSERT INTO `jipu_area` VALUES ('152922', '阿拉善右旗', '152900', '2');
INSERT INTO `jipu_area` VALUES ('152923', '额济纳旗', '152900', '3');
INSERT INTO `jipu_area` VALUES ('210000', '辽宁省', '0', '6');
INSERT INTO `jipu_area` VALUES ('210100', '沈阳市', '210000', '1');
INSERT INTO `jipu_area` VALUES ('210101', '市辖区', '210100', '1');
INSERT INTO `jipu_area` VALUES ('210102', '和平区', '210100', '2');
INSERT INTO `jipu_area` VALUES ('210103', '沈河区', '210100', '3');
INSERT INTO `jipu_area` VALUES ('210104', '大东区', '210100', '4');
INSERT INTO `jipu_area` VALUES ('210105', '皇姑区', '210100', '5');
INSERT INTO `jipu_area` VALUES ('210106', '铁西区', '210100', '6');
INSERT INTO `jipu_area` VALUES ('210111', '苏家屯区', '210100', '7');
INSERT INTO `jipu_area` VALUES ('210112', '东陵区', '210100', '8');
INSERT INTO `jipu_area` VALUES ('210113', '新城子区', '210100', '9');
INSERT INTO `jipu_area` VALUES ('210114', '于洪区', '210100', '10');
INSERT INTO `jipu_area` VALUES ('210122', '辽中县', '210100', '11');
INSERT INTO `jipu_area` VALUES ('210123', '康平县', '210100', '12');
INSERT INTO `jipu_area` VALUES ('210124', '法库县', '210100', '13');
INSERT INTO `jipu_area` VALUES ('210181', '新民市', '210100', '14');
INSERT INTO `jipu_area` VALUES ('210200', '大连市', '210000', '2');
INSERT INTO `jipu_area` VALUES ('210201', '市辖区', '210200', '1');
INSERT INTO `jipu_area` VALUES ('210202', '中山区', '210200', '2');
INSERT INTO `jipu_area` VALUES ('210203', '西岗区', '210200', '3');
INSERT INTO `jipu_area` VALUES ('210204', '沙河口区', '210200', '4');
INSERT INTO `jipu_area` VALUES ('210211', '甘井子区', '210200', '5');
INSERT INTO `jipu_area` VALUES ('210212', '旅顺口区', '210200', '6');
INSERT INTO `jipu_area` VALUES ('210213', '金州区', '210200', '7');
INSERT INTO `jipu_area` VALUES ('210224', '长海县', '210200', '8');
INSERT INTO `jipu_area` VALUES ('210281', '瓦房店市', '210200', '9');
INSERT INTO `jipu_area` VALUES ('210282', '普兰店市', '210200', '10');
INSERT INTO `jipu_area` VALUES ('210283', '庄河市', '210200', '11');
INSERT INTO `jipu_area` VALUES ('210300', '鞍山市', '210000', '3');
INSERT INTO `jipu_area` VALUES ('210301', '市辖区', '210300', '1');
INSERT INTO `jipu_area` VALUES ('210302', '铁东区', '210300', '2');
INSERT INTO `jipu_area` VALUES ('210303', '铁西区', '210300', '3');
INSERT INTO `jipu_area` VALUES ('210304', '立山区', '210300', '4');
INSERT INTO `jipu_area` VALUES ('210311', '千山区', '210300', '5');
INSERT INTO `jipu_area` VALUES ('210321', '台安县', '210300', '6');
INSERT INTO `jipu_area` VALUES ('210323', '岫岩满族自治县', '210300', '7');
INSERT INTO `jipu_area` VALUES ('210381', '海城市', '210300', '8');
INSERT INTO `jipu_area` VALUES ('210400', '抚顺市', '210000', '4');
INSERT INTO `jipu_area` VALUES ('210401', '市辖区', '210400', '1');
INSERT INTO `jipu_area` VALUES ('210402', '新抚区', '210400', '2');
INSERT INTO `jipu_area` VALUES ('210403', '东洲区', '210400', '3');
INSERT INTO `jipu_area` VALUES ('210404', '望花区', '210400', '4');
INSERT INTO `jipu_area` VALUES ('210411', '顺城区', '210400', '5');
INSERT INTO `jipu_area` VALUES ('210421', '抚顺县', '210400', '6');
INSERT INTO `jipu_area` VALUES ('210422', '新宾满族自治县', '210400', '7');
INSERT INTO `jipu_area` VALUES ('210423', '清原满族自治县', '210400', '8');
INSERT INTO `jipu_area` VALUES ('210500', '本溪市', '210000', '5');
INSERT INTO `jipu_area` VALUES ('210501', '市辖区', '210500', '1');
INSERT INTO `jipu_area` VALUES ('210502', '平山区', '210500', '2');
INSERT INTO `jipu_area` VALUES ('210503', '溪湖区', '210500', '3');
INSERT INTO `jipu_area` VALUES ('210504', '明山区', '210500', '4');
INSERT INTO `jipu_area` VALUES ('210505', '南芬区', '210500', '5');
INSERT INTO `jipu_area` VALUES ('210521', '本溪满族自治县', '210500', '6');
INSERT INTO `jipu_area` VALUES ('210522', '桓仁满族自治县', '210500', '7');
INSERT INTO `jipu_area` VALUES ('210600', '丹东市', '210000', '6');
INSERT INTO `jipu_area` VALUES ('210601', '市辖区', '210600', '1');
INSERT INTO `jipu_area` VALUES ('210602', '元宝区', '210600', '2');
INSERT INTO `jipu_area` VALUES ('210603', '振兴区', '210600', '3');
INSERT INTO `jipu_area` VALUES ('210604', '振安区', '210600', '4');
INSERT INTO `jipu_area` VALUES ('210624', '宽甸满族自治县', '210600', '5');
INSERT INTO `jipu_area` VALUES ('210681', '东港市', '210600', '6');
INSERT INTO `jipu_area` VALUES ('210682', '凤城市', '210600', '7');
INSERT INTO `jipu_area` VALUES ('210700', '锦州市', '210000', '7');
INSERT INTO `jipu_area` VALUES ('210701', '市辖区', '210700', '1');
INSERT INTO `jipu_area` VALUES ('210702', '古塔区', '210700', '2');
INSERT INTO `jipu_area` VALUES ('210703', '凌河区', '210700', '3');
INSERT INTO `jipu_area` VALUES ('210711', '太和区', '210700', '4');
INSERT INTO `jipu_area` VALUES ('210726', '黑山县', '210700', '5');
INSERT INTO `jipu_area` VALUES ('210727', '义　县', '210700', '6');
INSERT INTO `jipu_area` VALUES ('210781', '凌海市', '210700', '7');
INSERT INTO `jipu_area` VALUES ('210782', '北宁市', '210700', '8');
INSERT INTO `jipu_area` VALUES ('210800', '营口市', '210000', '8');
INSERT INTO `jipu_area` VALUES ('210801', '市辖区', '210800', '1');
INSERT INTO `jipu_area` VALUES ('210802', '站前区', '210800', '2');
INSERT INTO `jipu_area` VALUES ('210803', '西市区', '210800', '3');
INSERT INTO `jipu_area` VALUES ('210804', '鲅鱼圈区', '210800', '4');
INSERT INTO `jipu_area` VALUES ('210811', '老边区', '210800', '5');
INSERT INTO `jipu_area` VALUES ('210881', '盖州市', '210800', '6');
INSERT INTO `jipu_area` VALUES ('210882', '大石桥市', '210800', '7');
INSERT INTO `jipu_area` VALUES ('210900', '阜新市', '210000', '9');
INSERT INTO `jipu_area` VALUES ('210901', '市辖区', '210900', '1');
INSERT INTO `jipu_area` VALUES ('210902', '海州区', '210900', '2');
INSERT INTO `jipu_area` VALUES ('210903', '新邱区', '210900', '3');
INSERT INTO `jipu_area` VALUES ('210904', '太平区', '210900', '4');
INSERT INTO `jipu_area` VALUES ('210905', '清河门区', '210900', '5');
INSERT INTO `jipu_area` VALUES ('210911', '细河区', '210900', '6');
INSERT INTO `jipu_area` VALUES ('210921', '阜新蒙古族自治县', '210900', '7');
INSERT INTO `jipu_area` VALUES ('210922', '彰武县', '210900', '8');
INSERT INTO `jipu_area` VALUES ('211000', '辽阳市', '210000', '10');
INSERT INTO `jipu_area` VALUES ('211001', '市辖区', '211000', '1');
INSERT INTO `jipu_area` VALUES ('211002', '白塔区', '211000', '2');
INSERT INTO `jipu_area` VALUES ('211003', '文圣区', '211000', '3');
INSERT INTO `jipu_area` VALUES ('211004', '宏伟区', '211000', '4');
INSERT INTO `jipu_area` VALUES ('211005', '弓长岭区', '211000', '5');
INSERT INTO `jipu_area` VALUES ('211011', '太子河区', '211000', '6');
INSERT INTO `jipu_area` VALUES ('211021', '辽阳县', '211000', '7');
INSERT INTO `jipu_area` VALUES ('211081', '灯塔市', '211000', '8');
INSERT INTO `jipu_area` VALUES ('211100', '盘锦市', '210000', '11');
INSERT INTO `jipu_area` VALUES ('211101', '市辖区', '211100', '1');
INSERT INTO `jipu_area` VALUES ('211102', '双台子区', '211100', '2');
INSERT INTO `jipu_area` VALUES ('211103', '兴隆台区', '211100', '3');
INSERT INTO `jipu_area` VALUES ('211121', '大洼县', '211100', '4');
INSERT INTO `jipu_area` VALUES ('211122', '盘山县', '211100', '5');
INSERT INTO `jipu_area` VALUES ('211200', '铁岭市', '210000', '12');
INSERT INTO `jipu_area` VALUES ('211201', '市辖区', '211200', '1');
INSERT INTO `jipu_area` VALUES ('211202', '银州区', '211200', '2');
INSERT INTO `jipu_area` VALUES ('211204', '清河区', '211200', '3');
INSERT INTO `jipu_area` VALUES ('211221', '铁岭县', '211200', '4');
INSERT INTO `jipu_area` VALUES ('211223', '西丰县', '211200', '5');
INSERT INTO `jipu_area` VALUES ('211224', '昌图县', '211200', '6');
INSERT INTO `jipu_area` VALUES ('211281', '调兵山市', '211200', '7');
INSERT INTO `jipu_area` VALUES ('211282', '开原市', '211200', '8');
INSERT INTO `jipu_area` VALUES ('211300', '朝阳市', '210000', '13');
INSERT INTO `jipu_area` VALUES ('211301', '市辖区', '211300', '1');
INSERT INTO `jipu_area` VALUES ('211302', '双塔区', '211300', '2');
INSERT INTO `jipu_area` VALUES ('211303', '龙城区', '211300', '3');
INSERT INTO `jipu_area` VALUES ('211321', '朝阳县', '211300', '4');
INSERT INTO `jipu_area` VALUES ('211322', '建平县', '211300', '5');
INSERT INTO `jipu_area` VALUES ('211324', '喀喇沁左翼蒙古族自治县', '211300', '6');
INSERT INTO `jipu_area` VALUES ('211381', '北票市', '211300', '7');
INSERT INTO `jipu_area` VALUES ('211382', '凌源市', '211300', '8');
INSERT INTO `jipu_area` VALUES ('211400', '葫芦岛市', '210000', '14');
INSERT INTO `jipu_area` VALUES ('211401', '市辖区', '211400', '1');
INSERT INTO `jipu_area` VALUES ('211402', '连山区', '211400', '2');
INSERT INTO `jipu_area` VALUES ('211403', '龙港区', '211400', '3');
INSERT INTO `jipu_area` VALUES ('211404', '南票区', '211400', '4');
INSERT INTO `jipu_area` VALUES ('211421', '绥中县', '211400', '5');
INSERT INTO `jipu_area` VALUES ('211422', '建昌县', '211400', '6');
INSERT INTO `jipu_area` VALUES ('211481', '兴城市', '211400', '7');
INSERT INTO `jipu_area` VALUES ('220000', '吉林省', '0', '7');
INSERT INTO `jipu_area` VALUES ('220100', '长春市', '220000', '1');
INSERT INTO `jipu_area` VALUES ('220101', '市辖区', '220100', '1');
INSERT INTO `jipu_area` VALUES ('220102', '南关区', '220100', '2');
INSERT INTO `jipu_area` VALUES ('220103', '宽城区', '220100', '3');
INSERT INTO `jipu_area` VALUES ('220104', '朝阳区', '220100', '4');
INSERT INTO `jipu_area` VALUES ('220105', '二道区', '220100', '5');
INSERT INTO `jipu_area` VALUES ('220106', '绿园区', '220100', '6');
INSERT INTO `jipu_area` VALUES ('220112', '双阳区', '220100', '7');
INSERT INTO `jipu_area` VALUES ('220122', '农安县', '220100', '8');
INSERT INTO `jipu_area` VALUES ('220181', '九台市', '220100', '9');
INSERT INTO `jipu_area` VALUES ('220182', '榆树市', '220100', '10');
INSERT INTO `jipu_area` VALUES ('220183', '德惠市', '220100', '11');
INSERT INTO `jipu_area` VALUES ('220200', '吉林市', '220000', '2');
INSERT INTO `jipu_area` VALUES ('220201', '市辖区', '220200', '1');
INSERT INTO `jipu_area` VALUES ('220202', '昌邑区', '220200', '2');
INSERT INTO `jipu_area` VALUES ('220203', '龙潭区', '220200', '3');
INSERT INTO `jipu_area` VALUES ('220204', '船营区', '220200', '4');
INSERT INTO `jipu_area` VALUES ('220211', '丰满区', '220200', '5');
INSERT INTO `jipu_area` VALUES ('220221', '永吉县', '220200', '6');
INSERT INTO `jipu_area` VALUES ('220281', '蛟河市', '220200', '7');
INSERT INTO `jipu_area` VALUES ('220282', '桦甸市', '220200', '8');
INSERT INTO `jipu_area` VALUES ('220283', '舒兰市', '220200', '9');
INSERT INTO `jipu_area` VALUES ('220284', '磐石市', '220200', '10');
INSERT INTO `jipu_area` VALUES ('220300', '四平市', '220000', '3');
INSERT INTO `jipu_area` VALUES ('220301', '市辖区', '220300', '1');
INSERT INTO `jipu_area` VALUES ('220302', '铁西区', '220300', '2');
INSERT INTO `jipu_area` VALUES ('220303', '铁东区', '220300', '3');
INSERT INTO `jipu_area` VALUES ('220322', '梨树县', '220300', '4');
INSERT INTO `jipu_area` VALUES ('220323', '伊通满族自治县', '220300', '5');
INSERT INTO `jipu_area` VALUES ('220381', '公主岭市', '220300', '6');
INSERT INTO `jipu_area` VALUES ('220382', '双辽市', '220300', '7');
INSERT INTO `jipu_area` VALUES ('220400', '辽源市', '220000', '4');
INSERT INTO `jipu_area` VALUES ('220401', '市辖区', '220400', '1');
INSERT INTO `jipu_area` VALUES ('220402', '龙山区', '220400', '2');
INSERT INTO `jipu_area` VALUES ('220403', '西安区', '220400', '3');
INSERT INTO `jipu_area` VALUES ('220421', '东丰县', '220400', '4');
INSERT INTO `jipu_area` VALUES ('220422', '东辽县', '220400', '5');
INSERT INTO `jipu_area` VALUES ('220500', '通化市', '220000', '5');
INSERT INTO `jipu_area` VALUES ('220501', '市辖区', '220500', '1');
INSERT INTO `jipu_area` VALUES ('220502', '东昌区', '220500', '2');
INSERT INTO `jipu_area` VALUES ('220503', '二道江区', '220500', '3');
INSERT INTO `jipu_area` VALUES ('220521', '通化县', '220500', '4');
INSERT INTO `jipu_area` VALUES ('220523', '辉南县', '220500', '5');
INSERT INTO `jipu_area` VALUES ('220524', '柳河县', '220500', '6');
INSERT INTO `jipu_area` VALUES ('220581', '梅河口市', '220500', '7');
INSERT INTO `jipu_area` VALUES ('220582', '集安市', '220500', '8');
INSERT INTO `jipu_area` VALUES ('220600', '白山市', '220000', '6');
INSERT INTO `jipu_area` VALUES ('220601', '市辖区', '220600', '1');
INSERT INTO `jipu_area` VALUES ('220602', '八道江区', '220600', '2');
INSERT INTO `jipu_area` VALUES ('220621', '抚松县', '220600', '3');
INSERT INTO `jipu_area` VALUES ('220622', '靖宇县', '220600', '4');
INSERT INTO `jipu_area` VALUES ('220623', '长白朝鲜族自治县', '220600', '5');
INSERT INTO `jipu_area` VALUES ('220625', '江源县', '220600', '6');
INSERT INTO `jipu_area` VALUES ('220681', '临江市', '220600', '7');
INSERT INTO `jipu_area` VALUES ('220700', '松原市', '220000', '7');
INSERT INTO `jipu_area` VALUES ('220701', '市辖区', '220700', '1');
INSERT INTO `jipu_area` VALUES ('220702', '宁江区', '220700', '2');
INSERT INTO `jipu_area` VALUES ('220721', '前郭尔罗斯蒙古族自治县', '220700', '3');
INSERT INTO `jipu_area` VALUES ('220722', '长岭县', '220700', '4');
INSERT INTO `jipu_area` VALUES ('220723', '乾安县', '220700', '5');
INSERT INTO `jipu_area` VALUES ('220724', '扶余县', '220700', '6');
INSERT INTO `jipu_area` VALUES ('220800', '白城市', '220000', '8');
INSERT INTO `jipu_area` VALUES ('220801', '市辖区', '220800', '1');
INSERT INTO `jipu_area` VALUES ('220802', '洮北区', '220800', '2');
INSERT INTO `jipu_area` VALUES ('220821', '镇赉县', '220800', '3');
INSERT INTO `jipu_area` VALUES ('220822', '通榆县', '220800', '4');
INSERT INTO `jipu_area` VALUES ('220881', '洮南市', '220800', '5');
INSERT INTO `jipu_area` VALUES ('220882', '大安市', '220800', '6');
INSERT INTO `jipu_area` VALUES ('222400', '延边朝鲜族自治州', '220000', '9');
INSERT INTO `jipu_area` VALUES ('222401', '延吉市', '222400', '1');
INSERT INTO `jipu_area` VALUES ('222402', '图们市', '222400', '2');
INSERT INTO `jipu_area` VALUES ('222403', '敦化市', '222400', '3');
INSERT INTO `jipu_area` VALUES ('222404', '珲春市', '222400', '4');
INSERT INTO `jipu_area` VALUES ('222405', '龙井市', '222400', '5');
INSERT INTO `jipu_area` VALUES ('222406', '和龙市', '222400', '6');
INSERT INTO `jipu_area` VALUES ('222424', '汪清县', '222400', '7');
INSERT INTO `jipu_area` VALUES ('222426', '安图县', '222400', '8');
INSERT INTO `jipu_area` VALUES ('230000', '黑龙江', '0', '8');
INSERT INTO `jipu_area` VALUES ('230100', '哈尔滨市', '230000', '1');
INSERT INTO `jipu_area` VALUES ('230101', '市辖区', '230100', '1');
INSERT INTO `jipu_area` VALUES ('230102', '道里区', '230100', '2');
INSERT INTO `jipu_area` VALUES ('230103', '南岗区', '230100', '3');
INSERT INTO `jipu_area` VALUES ('230104', '道外区', '230100', '4');
INSERT INTO `jipu_area` VALUES ('230106', '香坊区', '230100', '5');
INSERT INTO `jipu_area` VALUES ('230107', '动力区', '230100', '6');
INSERT INTO `jipu_area` VALUES ('230108', '平房区', '230100', '7');
INSERT INTO `jipu_area` VALUES ('230109', '松北区', '230100', '8');
INSERT INTO `jipu_area` VALUES ('230111', '呼兰区', '230100', '9');
INSERT INTO `jipu_area` VALUES ('230123', '依兰县', '230100', '10');
INSERT INTO `jipu_area` VALUES ('230124', '方正县', '230100', '11');
INSERT INTO `jipu_area` VALUES ('230125', '宾　县', '230100', '12');
INSERT INTO `jipu_area` VALUES ('230126', '巴彦县', '230100', '13');
INSERT INTO `jipu_area` VALUES ('230127', '木兰县', '230100', '14');
INSERT INTO `jipu_area` VALUES ('230128', '通河县', '230100', '15');
INSERT INTO `jipu_area` VALUES ('230129', '延寿县', '230100', '16');
INSERT INTO `jipu_area` VALUES ('230181', '阿城市', '230100', '17');
INSERT INTO `jipu_area` VALUES ('230182', '双城市', '230100', '18');
INSERT INTO `jipu_area` VALUES ('230183', '尚志市', '230100', '19');
INSERT INTO `jipu_area` VALUES ('230184', '五常市', '230100', '20');
INSERT INTO `jipu_area` VALUES ('230200', '齐齐哈尔市', '230000', '2');
INSERT INTO `jipu_area` VALUES ('230201', '市辖区', '230200', '1');
INSERT INTO `jipu_area` VALUES ('230202', '龙沙区', '230200', '2');
INSERT INTO `jipu_area` VALUES ('230203', '建华区', '230200', '3');
INSERT INTO `jipu_area` VALUES ('230204', '铁锋区', '230200', '4');
INSERT INTO `jipu_area` VALUES ('230205', '昂昂溪区', '230200', '5');
INSERT INTO `jipu_area` VALUES ('230206', '富拉尔基区', '230200', '6');
INSERT INTO `jipu_area` VALUES ('230207', '碾子山区', '230200', '7');
INSERT INTO `jipu_area` VALUES ('230208', '梅里斯达斡尔族区', '230200', '8');
INSERT INTO `jipu_area` VALUES ('230221', '龙江县', '230200', '9');
INSERT INTO `jipu_area` VALUES ('230223', '依安县', '230200', '10');
INSERT INTO `jipu_area` VALUES ('230224', '泰来县', '230200', '11');
INSERT INTO `jipu_area` VALUES ('230225', '甘南县', '230200', '12');
INSERT INTO `jipu_area` VALUES ('230227', '富裕县', '230200', '13');
INSERT INTO `jipu_area` VALUES ('230229', '克山县', '230200', '14');
INSERT INTO `jipu_area` VALUES ('230230', '克东县', '230200', '15');
INSERT INTO `jipu_area` VALUES ('230231', '拜泉县', '230200', '16');
INSERT INTO `jipu_area` VALUES ('230281', '讷河市', '230200', '17');
INSERT INTO `jipu_area` VALUES ('230300', '鸡西市', '230000', '3');
INSERT INTO `jipu_area` VALUES ('230301', '市辖区', '230300', '1');
INSERT INTO `jipu_area` VALUES ('230302', '鸡冠区', '230300', '2');
INSERT INTO `jipu_area` VALUES ('230303', '恒山区', '230300', '3');
INSERT INTO `jipu_area` VALUES ('230304', '滴道区', '230300', '4');
INSERT INTO `jipu_area` VALUES ('230305', '梨树区', '230300', '5');
INSERT INTO `jipu_area` VALUES ('230306', '城子河区', '230300', '6');
INSERT INTO `jipu_area` VALUES ('230307', '麻山区', '230300', '7');
INSERT INTO `jipu_area` VALUES ('230321', '鸡东县', '230300', '8');
INSERT INTO `jipu_area` VALUES ('230381', '虎林市', '230300', '9');
INSERT INTO `jipu_area` VALUES ('230382', '密山市', '230300', '10');
INSERT INTO `jipu_area` VALUES ('230400', '鹤岗市', '230000', '4');
INSERT INTO `jipu_area` VALUES ('230401', '市辖区', '230400', '1');
INSERT INTO `jipu_area` VALUES ('230402', '向阳区', '230400', '2');
INSERT INTO `jipu_area` VALUES ('230403', '工农区', '230400', '3');
INSERT INTO `jipu_area` VALUES ('230404', '南山区', '230400', '4');
INSERT INTO `jipu_area` VALUES ('230405', '兴安区', '230400', '5');
INSERT INTO `jipu_area` VALUES ('230406', '东山区', '230400', '6');
INSERT INTO `jipu_area` VALUES ('230407', '兴山区', '230400', '7');
INSERT INTO `jipu_area` VALUES ('230421', '萝北县', '230400', '8');
INSERT INTO `jipu_area` VALUES ('230422', '绥滨县', '230400', '9');
INSERT INTO `jipu_area` VALUES ('230500', '双鸭山市', '230000', '5');
INSERT INTO `jipu_area` VALUES ('230501', '市辖区', '230500', '1');
INSERT INTO `jipu_area` VALUES ('230502', '尖山区', '230500', '2');
INSERT INTO `jipu_area` VALUES ('230503', '岭东区', '230500', '3');
INSERT INTO `jipu_area` VALUES ('230505', '四方台区', '230500', '4');
INSERT INTO `jipu_area` VALUES ('230506', '宝山区', '230500', '5');
INSERT INTO `jipu_area` VALUES ('230521', '集贤县', '230500', '6');
INSERT INTO `jipu_area` VALUES ('230522', '友谊县', '230500', '7');
INSERT INTO `jipu_area` VALUES ('230523', '宝清县', '230500', '8');
INSERT INTO `jipu_area` VALUES ('230524', '饶河县', '230500', '9');
INSERT INTO `jipu_area` VALUES ('230600', '大庆市', '230000', '6');
INSERT INTO `jipu_area` VALUES ('230601', '市辖区', '230600', '1');
INSERT INTO `jipu_area` VALUES ('230602', '萨尔图区', '230600', '2');
INSERT INTO `jipu_area` VALUES ('230603', '龙凤区', '230600', '3');
INSERT INTO `jipu_area` VALUES ('230604', '让胡路区', '230600', '4');
INSERT INTO `jipu_area` VALUES ('230605', '红岗区', '230600', '5');
INSERT INTO `jipu_area` VALUES ('230606', '大同区', '230600', '6');
INSERT INTO `jipu_area` VALUES ('230621', '肇州县', '230600', '7');
INSERT INTO `jipu_area` VALUES ('230622', '肇源县', '230600', '8');
INSERT INTO `jipu_area` VALUES ('230623', '林甸县', '230600', '9');
INSERT INTO `jipu_area` VALUES ('230624', '杜尔伯特蒙古族自治县', '230600', '10');
INSERT INTO `jipu_area` VALUES ('230700', '伊春市', '230000', '7');
INSERT INTO `jipu_area` VALUES ('230701', '市辖区', '230700', '1');
INSERT INTO `jipu_area` VALUES ('230702', '伊春区', '230700', '2');
INSERT INTO `jipu_area` VALUES ('230703', '南岔区', '230700', '3');
INSERT INTO `jipu_area` VALUES ('230704', '友好区', '230700', '4');
INSERT INTO `jipu_area` VALUES ('230705', '西林区', '230700', '5');
INSERT INTO `jipu_area` VALUES ('230706', '翠峦区', '230700', '6');
INSERT INTO `jipu_area` VALUES ('230707', '新青区', '230700', '7');
INSERT INTO `jipu_area` VALUES ('230708', '美溪区', '230700', '8');
INSERT INTO `jipu_area` VALUES ('230709', '金山屯区', '230700', '9');
INSERT INTO `jipu_area` VALUES ('230710', '五营区', '230700', '10');
INSERT INTO `jipu_area` VALUES ('230711', '乌马河区', '230700', '11');
INSERT INTO `jipu_area` VALUES ('230712', '汤旺河区', '230700', '12');
INSERT INTO `jipu_area` VALUES ('230713', '带岭区', '230700', '13');
INSERT INTO `jipu_area` VALUES ('230714', '乌伊岭区', '230700', '14');
INSERT INTO `jipu_area` VALUES ('230715', '红星区', '230700', '15');
INSERT INTO `jipu_area` VALUES ('230716', '上甘岭区', '230700', '16');
INSERT INTO `jipu_area` VALUES ('230722', '嘉荫县', '230700', '17');
INSERT INTO `jipu_area` VALUES ('230781', '铁力市', '230700', '18');
INSERT INTO `jipu_area` VALUES ('230800', '佳木斯市', '230000', '8');
INSERT INTO `jipu_area` VALUES ('230801', '市辖区', '230800', '1');
INSERT INTO `jipu_area` VALUES ('230802', '永红区', '230800', '2');
INSERT INTO `jipu_area` VALUES ('230803', '向阳区', '230800', '3');
INSERT INTO `jipu_area` VALUES ('230804', '前进区', '230800', '4');
INSERT INTO `jipu_area` VALUES ('230805', '东风区', '230800', '5');
INSERT INTO `jipu_area` VALUES ('230811', '郊　区', '230800', '6');
INSERT INTO `jipu_area` VALUES ('230822', '桦南县', '230800', '7');
INSERT INTO `jipu_area` VALUES ('230826', '桦川县', '230800', '8');
INSERT INTO `jipu_area` VALUES ('230828', '汤原县', '230800', '9');
INSERT INTO `jipu_area` VALUES ('230833', '抚远县', '230800', '10');
INSERT INTO `jipu_area` VALUES ('230881', '同江市', '230800', '11');
INSERT INTO `jipu_area` VALUES ('230882', '富锦市', '230800', '12');
INSERT INTO `jipu_area` VALUES ('230900', '七台河市', '230000', '9');
INSERT INTO `jipu_area` VALUES ('230901', '市辖区', '230900', '1');
INSERT INTO `jipu_area` VALUES ('230902', '新兴区', '230900', '2');
INSERT INTO `jipu_area` VALUES ('230903', '桃山区', '230900', '3');
INSERT INTO `jipu_area` VALUES ('230904', '茄子河区', '230900', '4');
INSERT INTO `jipu_area` VALUES ('230921', '勃利县', '230900', '5');
INSERT INTO `jipu_area` VALUES ('231000', '牡丹江市', '230000', '10');
INSERT INTO `jipu_area` VALUES ('231001', '市辖区', '231000', '1');
INSERT INTO `jipu_area` VALUES ('231002', '东安区', '231000', '2');
INSERT INTO `jipu_area` VALUES ('231003', '阳明区', '231000', '3');
INSERT INTO `jipu_area` VALUES ('231004', '爱民区', '231000', '4');
INSERT INTO `jipu_area` VALUES ('231005', '西安区', '231000', '5');
INSERT INTO `jipu_area` VALUES ('231024', '东宁县', '231000', '6');
INSERT INTO `jipu_area` VALUES ('231025', '林口县', '231000', '7');
INSERT INTO `jipu_area` VALUES ('231081', '绥芬河市', '231000', '8');
INSERT INTO `jipu_area` VALUES ('231083', '海林市', '231000', '9');
INSERT INTO `jipu_area` VALUES ('231084', '宁安市', '231000', '10');
INSERT INTO `jipu_area` VALUES ('231085', '穆棱市', '231000', '11');
INSERT INTO `jipu_area` VALUES ('231100', '黑河市', '230000', '11');
INSERT INTO `jipu_area` VALUES ('231101', '市辖区', '231100', '1');
INSERT INTO `jipu_area` VALUES ('231102', '爱辉区', '231100', '2');
INSERT INTO `jipu_area` VALUES ('231121', '嫩江县', '231100', '3');
INSERT INTO `jipu_area` VALUES ('231123', '逊克县', '231100', '4');
INSERT INTO `jipu_area` VALUES ('231124', '孙吴县', '231100', '5');
INSERT INTO `jipu_area` VALUES ('231181', '北安市', '231100', '6');
INSERT INTO `jipu_area` VALUES ('231182', '五大连池市', '231100', '7');
INSERT INTO `jipu_area` VALUES ('231200', '绥化市', '230000', '12');
INSERT INTO `jipu_area` VALUES ('231201', '市辖区', '231200', '1');
INSERT INTO `jipu_area` VALUES ('231202', '北林区', '231200', '2');
INSERT INTO `jipu_area` VALUES ('231221', '望奎县', '231200', '3');
INSERT INTO `jipu_area` VALUES ('231222', '兰西县', '231200', '4');
INSERT INTO `jipu_area` VALUES ('231223', '青冈县', '231200', '5');
INSERT INTO `jipu_area` VALUES ('231224', '庆安县', '231200', '6');
INSERT INTO `jipu_area` VALUES ('231225', '明水县', '231200', '7');
INSERT INTO `jipu_area` VALUES ('231226', '绥棱县', '231200', '8');
INSERT INTO `jipu_area` VALUES ('231281', '安达市', '231200', '9');
INSERT INTO `jipu_area` VALUES ('231282', '肇东市', '231200', '10');
INSERT INTO `jipu_area` VALUES ('231283', '海伦市', '231200', '11');
INSERT INTO `jipu_area` VALUES ('232700', '大兴安岭地区', '230000', '13');
INSERT INTO `jipu_area` VALUES ('232721', '呼玛县', '232700', '1');
INSERT INTO `jipu_area` VALUES ('232722', '塔河县', '232700', '2');
INSERT INTO `jipu_area` VALUES ('232723', '漠河县', '232700', '3');
INSERT INTO `jipu_area` VALUES ('310000', '上海市', '0', '9');
INSERT INTO `jipu_area` VALUES ('310100', '市辖区', '310000', '1');
INSERT INTO `jipu_area` VALUES ('310101', '黄浦区', '310100', '1');
INSERT INTO `jipu_area` VALUES ('310103', '卢湾区', '310100', '2');
INSERT INTO `jipu_area` VALUES ('310104', '徐汇区', '310100', '3');
INSERT INTO `jipu_area` VALUES ('310105', '长宁区', '310100', '4');
INSERT INTO `jipu_area` VALUES ('310106', '静安区', '310100', '5');
INSERT INTO `jipu_area` VALUES ('310107', '普陀区', '310100', '6');
INSERT INTO `jipu_area` VALUES ('310108', '闸北区', '310100', '7');
INSERT INTO `jipu_area` VALUES ('310109', '虹口区', '310100', '8');
INSERT INTO `jipu_area` VALUES ('310110', '杨浦区', '310100', '9');
INSERT INTO `jipu_area` VALUES ('310112', '闵行区', '310100', '10');
INSERT INTO `jipu_area` VALUES ('310113', '宝山区', '310100', '11');
INSERT INTO `jipu_area` VALUES ('310114', '嘉定区', '310100', '12');
INSERT INTO `jipu_area` VALUES ('310115', '浦东新区', '310100', '13');
INSERT INTO `jipu_area` VALUES ('310116', '金山区', '310100', '14');
INSERT INTO `jipu_area` VALUES ('310117', '松江区', '310100', '15');
INSERT INTO `jipu_area` VALUES ('310118', '青浦区', '310100', '16');
INSERT INTO `jipu_area` VALUES ('310119', '南汇区', '310100', '17');
INSERT INTO `jipu_area` VALUES ('310120', '奉贤区', '310100', '18');
INSERT INTO `jipu_area` VALUES ('310200', '县', '310000', '2');
INSERT INTO `jipu_area` VALUES ('310230', '崇明县', '310200', '1');
INSERT INTO `jipu_area` VALUES ('320000', '江苏省', '0', '10');
INSERT INTO `jipu_area` VALUES ('320100', '南京市', '320000', '1');
INSERT INTO `jipu_area` VALUES ('320101', '市辖区', '320100', '1');
INSERT INTO `jipu_area` VALUES ('320102', '玄武区', '320100', '2');
INSERT INTO `jipu_area` VALUES ('320103', '白下区', '320100', '3');
INSERT INTO `jipu_area` VALUES ('320104', '秦淮区', '320100', '4');
INSERT INTO `jipu_area` VALUES ('320105', '建邺区', '320100', '5');
INSERT INTO `jipu_area` VALUES ('320106', '鼓楼区', '320100', '6');
INSERT INTO `jipu_area` VALUES ('320107', '下关区', '320100', '7');
INSERT INTO `jipu_area` VALUES ('320111', '浦口区', '320100', '8');
INSERT INTO `jipu_area` VALUES ('320113', '栖霞区', '320100', '9');
INSERT INTO `jipu_area` VALUES ('320114', '雨花台区', '320100', '10');
INSERT INTO `jipu_area` VALUES ('320115', '江宁区', '320100', '11');
INSERT INTO `jipu_area` VALUES ('320116', '六合区', '320100', '12');
INSERT INTO `jipu_area` VALUES ('320124', '溧水县', '320100', '13');
INSERT INTO `jipu_area` VALUES ('320125', '高淳县', '320100', '14');
INSERT INTO `jipu_area` VALUES ('320200', '无锡市', '320000', '2');
INSERT INTO `jipu_area` VALUES ('320201', '市辖区', '320200', '1');
INSERT INTO `jipu_area` VALUES ('320202', '崇安区', '320200', '2');
INSERT INTO `jipu_area` VALUES ('320203', '南长区', '320200', '3');
INSERT INTO `jipu_area` VALUES ('320204', '北塘区', '320200', '4');
INSERT INTO `jipu_area` VALUES ('320205', '锡山区', '320200', '5');
INSERT INTO `jipu_area` VALUES ('320206', '惠山区', '320200', '6');
INSERT INTO `jipu_area` VALUES ('320211', '滨湖区', '320200', '7');
INSERT INTO `jipu_area` VALUES ('320281', '江阴市', '320200', '8');
INSERT INTO `jipu_area` VALUES ('320282', '宜兴市', '320200', '9');
INSERT INTO `jipu_area` VALUES ('320300', '徐州市', '320000', '3');
INSERT INTO `jipu_area` VALUES ('320301', '市辖区', '320300', '1');
INSERT INTO `jipu_area` VALUES ('320302', '鼓楼区', '320300', '2');
INSERT INTO `jipu_area` VALUES ('320303', '云龙区', '320300', '3');
INSERT INTO `jipu_area` VALUES ('320304', '九里区', '320300', '4');
INSERT INTO `jipu_area` VALUES ('320305', '贾汪区', '320300', '5');
INSERT INTO `jipu_area` VALUES ('320311', '泉山区', '320300', '6');
INSERT INTO `jipu_area` VALUES ('320321', '丰　县', '320300', '7');
INSERT INTO `jipu_area` VALUES ('320322', '沛　县', '320300', '8');
INSERT INTO `jipu_area` VALUES ('320323', '铜山县', '320300', '9');
INSERT INTO `jipu_area` VALUES ('320324', '睢宁县', '320300', '10');
INSERT INTO `jipu_area` VALUES ('320381', '新沂市', '320300', '11');
INSERT INTO `jipu_area` VALUES ('320382', '邳州市', '320300', '12');
INSERT INTO `jipu_area` VALUES ('320400', '常州市', '320000', '4');
INSERT INTO `jipu_area` VALUES ('320401', '市辖区', '320400', '1');
INSERT INTO `jipu_area` VALUES ('320402', '天宁区', '320400', '2');
INSERT INTO `jipu_area` VALUES ('320404', '钟楼区', '320400', '3');
INSERT INTO `jipu_area` VALUES ('320405', '戚墅堰区', '320400', '4');
INSERT INTO `jipu_area` VALUES ('320411', '新北区', '320400', '5');
INSERT INTO `jipu_area` VALUES ('320412', '武进区', '320400', '6');
INSERT INTO `jipu_area` VALUES ('320481', '溧阳市', '320400', '7');
INSERT INTO `jipu_area` VALUES ('320482', '金坛市', '320400', '8');
INSERT INTO `jipu_area` VALUES ('320500', '苏州市', '320000', '5');
INSERT INTO `jipu_area` VALUES ('320501', '市辖区', '320500', '1');
INSERT INTO `jipu_area` VALUES ('320502', '沧浪区', '320500', '2');
INSERT INTO `jipu_area` VALUES ('320503', '平江区', '320500', '3');
INSERT INTO `jipu_area` VALUES ('320504', '金阊区', '320500', '4');
INSERT INTO `jipu_area` VALUES ('320505', '虎丘区', '320500', '5');
INSERT INTO `jipu_area` VALUES ('320506', '吴中区', '320500', '6');
INSERT INTO `jipu_area` VALUES ('320507', '相城区', '320500', '7');
INSERT INTO `jipu_area` VALUES ('320581', '常熟市', '320500', '8');
INSERT INTO `jipu_area` VALUES ('320582', '张家港市', '320500', '9');
INSERT INTO `jipu_area` VALUES ('320583', '昆山市', '320500', '10');
INSERT INTO `jipu_area` VALUES ('320584', '吴江市', '320500', '11');
INSERT INTO `jipu_area` VALUES ('320585', '太仓市', '320500', '12');
INSERT INTO `jipu_area` VALUES ('320600', '南通市', '320000', '6');
INSERT INTO `jipu_area` VALUES ('320601', '市辖区', '320600', '1');
INSERT INTO `jipu_area` VALUES ('320602', '崇川区', '320600', '2');
INSERT INTO `jipu_area` VALUES ('320611', '港闸区', '320600', '3');
INSERT INTO `jipu_area` VALUES ('320621', '海安县', '320600', '4');
INSERT INTO `jipu_area` VALUES ('320623', '如东县', '320600', '5');
INSERT INTO `jipu_area` VALUES ('320681', '启东市', '320600', '6');
INSERT INTO `jipu_area` VALUES ('320682', '如皋市', '320600', '7');
INSERT INTO `jipu_area` VALUES ('320683', '通州市', '320600', '8');
INSERT INTO `jipu_area` VALUES ('320684', '海门市', '320600', '9');
INSERT INTO `jipu_area` VALUES ('320700', '连云港市', '320000', '7');
INSERT INTO `jipu_area` VALUES ('320701', '市辖区', '320700', '1');
INSERT INTO `jipu_area` VALUES ('320703', '连云区', '320700', '2');
INSERT INTO `jipu_area` VALUES ('320705', '新浦区', '320700', '3');
INSERT INTO `jipu_area` VALUES ('320706', '海州区', '320700', '4');
INSERT INTO `jipu_area` VALUES ('320721', '赣榆县', '320700', '5');
INSERT INTO `jipu_area` VALUES ('320722', '东海县', '320700', '6');
INSERT INTO `jipu_area` VALUES ('320723', '灌云县', '320700', '7');
INSERT INTO `jipu_area` VALUES ('320724', '灌南县', '320700', '8');
INSERT INTO `jipu_area` VALUES ('320800', '淮安市', '320000', '8');
INSERT INTO `jipu_area` VALUES ('320801', '市辖区', '320800', '1');
INSERT INTO `jipu_area` VALUES ('320802', '清河区', '320800', '2');
INSERT INTO `jipu_area` VALUES ('320803', '楚州区', '320800', '3');
INSERT INTO `jipu_area` VALUES ('320804', '淮阴区', '320800', '4');
INSERT INTO `jipu_area` VALUES ('320811', '清浦区', '320800', '5');
INSERT INTO `jipu_area` VALUES ('320826', '涟水县', '320800', '6');
INSERT INTO `jipu_area` VALUES ('320829', '洪泽县', '320800', '7');
INSERT INTO `jipu_area` VALUES ('320830', '盱眙县', '320800', '8');
INSERT INTO `jipu_area` VALUES ('320831', '金湖县', '320800', '9');
INSERT INTO `jipu_area` VALUES ('320900', '盐城市', '320000', '9');
INSERT INTO `jipu_area` VALUES ('320901', '市辖区', '320900', '1');
INSERT INTO `jipu_area` VALUES ('320902', '亭湖区', '320900', '2');
INSERT INTO `jipu_area` VALUES ('320903', '盐都区', '320900', '3');
INSERT INTO `jipu_area` VALUES ('320921', '响水县', '320900', '4');
INSERT INTO `jipu_area` VALUES ('320922', '滨海县', '320900', '5');
INSERT INTO `jipu_area` VALUES ('320923', '阜宁县', '320900', '6');
INSERT INTO `jipu_area` VALUES ('320924', '射阳县', '320900', '7');
INSERT INTO `jipu_area` VALUES ('320925', '建湖县', '320900', '8');
INSERT INTO `jipu_area` VALUES ('320981', '东台市', '320900', '9');
INSERT INTO `jipu_area` VALUES ('320982', '大丰市', '320900', '10');
INSERT INTO `jipu_area` VALUES ('321000', '扬州市', '320000', '10');
INSERT INTO `jipu_area` VALUES ('321001', '市辖区', '321000', '1');
INSERT INTO `jipu_area` VALUES ('321002', '广陵区', '321000', '2');
INSERT INTO `jipu_area` VALUES ('321003', '邗江区', '321000', '3');
INSERT INTO `jipu_area` VALUES ('321011', '郊　区', '321000', '4');
INSERT INTO `jipu_area` VALUES ('321023', '宝应县', '321000', '5');
INSERT INTO `jipu_area` VALUES ('321081', '仪征市', '321000', '6');
INSERT INTO `jipu_area` VALUES ('321084', '高邮市', '321000', '7');
INSERT INTO `jipu_area` VALUES ('321088', '江都市', '321000', '8');
INSERT INTO `jipu_area` VALUES ('321100', '镇江市', '320000', '11');
INSERT INTO `jipu_area` VALUES ('321101', '市辖区', '321100', '1');
INSERT INTO `jipu_area` VALUES ('321102', '京口区', '321100', '2');
INSERT INTO `jipu_area` VALUES ('321111', '润州区', '321100', '3');
INSERT INTO `jipu_area` VALUES ('321112', '丹徒区', '321100', '4');
INSERT INTO `jipu_area` VALUES ('321181', '丹阳市', '321100', '5');
INSERT INTO `jipu_area` VALUES ('321182', '扬中市', '321100', '6');
INSERT INTO `jipu_area` VALUES ('321183', '句容市', '321100', '7');
INSERT INTO `jipu_area` VALUES ('321200', '泰州市', '320000', '12');
INSERT INTO `jipu_area` VALUES ('321201', '市辖区', '321200', '1');
INSERT INTO `jipu_area` VALUES ('321202', '海陵区', '321200', '2');
INSERT INTO `jipu_area` VALUES ('321203', '高港区', '321200', '3');
INSERT INTO `jipu_area` VALUES ('321281', '兴化市', '321200', '4');
INSERT INTO `jipu_area` VALUES ('321282', '靖江市', '321200', '5');
INSERT INTO `jipu_area` VALUES ('321283', '泰兴市', '321200', '6');
INSERT INTO `jipu_area` VALUES ('321284', '姜堰市', '321200', '7');
INSERT INTO `jipu_area` VALUES ('321300', '宿迁市', '320000', '13');
INSERT INTO `jipu_area` VALUES ('321301', '市辖区', '321300', '1');
INSERT INTO `jipu_area` VALUES ('321302', '宿城区', '321300', '2');
INSERT INTO `jipu_area` VALUES ('321311', '宿豫区', '321300', '3');
INSERT INTO `jipu_area` VALUES ('321322', '沭阳县', '321300', '4');
INSERT INTO `jipu_area` VALUES ('321323', '泗阳县', '321300', '5');
INSERT INTO `jipu_area` VALUES ('321324', '泗洪县', '321300', '6');
INSERT INTO `jipu_area` VALUES ('330000', '浙江省', '0', '11');
INSERT INTO `jipu_area` VALUES ('330100', '杭州市', '330000', '1');
INSERT INTO `jipu_area` VALUES ('330101', '市辖区', '330100', '1');
INSERT INTO `jipu_area` VALUES ('330102', '上城区', '330100', '2');
INSERT INTO `jipu_area` VALUES ('330103', '下城区', '330100', '3');
INSERT INTO `jipu_area` VALUES ('330104', '江干区', '330100', '4');
INSERT INTO `jipu_area` VALUES ('330105', '拱墅区', '330100', '5');
INSERT INTO `jipu_area` VALUES ('330106', '西湖区', '330100', '6');
INSERT INTO `jipu_area` VALUES ('330108', '滨江区', '330100', '7');
INSERT INTO `jipu_area` VALUES ('330109', '萧山区', '330100', '8');
INSERT INTO `jipu_area` VALUES ('330110', '余杭区', '330100', '9');
INSERT INTO `jipu_area` VALUES ('330122', '桐庐县', '330100', '10');
INSERT INTO `jipu_area` VALUES ('330127', '淳安县', '330100', '11');
INSERT INTO `jipu_area` VALUES ('330182', '建德市', '330100', '12');
INSERT INTO `jipu_area` VALUES ('330183', '富阳市', '330100', '13');
INSERT INTO `jipu_area` VALUES ('330185', '临安市', '330100', '14');
INSERT INTO `jipu_area` VALUES ('330200', '宁波市', '330000', '2');
INSERT INTO `jipu_area` VALUES ('330201', '市辖区', '330200', '1');
INSERT INTO `jipu_area` VALUES ('330203', '海曙区', '330200', '2');
INSERT INTO `jipu_area` VALUES ('330204', '江东区', '330200', '3');
INSERT INTO `jipu_area` VALUES ('330205', '江北区', '330200', '4');
INSERT INTO `jipu_area` VALUES ('330206', '北仑区', '330200', '5');
INSERT INTO `jipu_area` VALUES ('330211', '镇海区', '330200', '6');
INSERT INTO `jipu_area` VALUES ('330212', '鄞州区', '330200', '7');
INSERT INTO `jipu_area` VALUES ('330225', '象山县', '330200', '8');
INSERT INTO `jipu_area` VALUES ('330226', '宁海县', '330200', '9');
INSERT INTO `jipu_area` VALUES ('330281', '余姚市', '330200', '10');
INSERT INTO `jipu_area` VALUES ('330282', '慈溪市', '330200', '11');
INSERT INTO `jipu_area` VALUES ('330283', '奉化市', '330200', '12');
INSERT INTO `jipu_area` VALUES ('330300', '温州市', '330000', '3');
INSERT INTO `jipu_area` VALUES ('330301', '市辖区', '330300', '1');
INSERT INTO `jipu_area` VALUES ('330302', '鹿城区', '330300', '2');
INSERT INTO `jipu_area` VALUES ('330303', '龙湾区', '330300', '3');
INSERT INTO `jipu_area` VALUES ('330304', '瓯海区', '330300', '4');
INSERT INTO `jipu_area` VALUES ('330322', '洞头县', '330300', '5');
INSERT INTO `jipu_area` VALUES ('330324', '永嘉县', '330300', '6');
INSERT INTO `jipu_area` VALUES ('330326', '平阳县', '330300', '7');
INSERT INTO `jipu_area` VALUES ('330327', '苍南县', '330300', '8');
INSERT INTO `jipu_area` VALUES ('330328', '文成县', '330300', '9');
INSERT INTO `jipu_area` VALUES ('330329', '泰顺县', '330300', '10');
INSERT INTO `jipu_area` VALUES ('330381', '瑞安市', '330300', '11');
INSERT INTO `jipu_area` VALUES ('330382', '乐清市', '330300', '12');
INSERT INTO `jipu_area` VALUES ('330400', '嘉兴市', '330000', '4');
INSERT INTO `jipu_area` VALUES ('330401', '市辖区', '330400', '1');
INSERT INTO `jipu_area` VALUES ('330402', '秀城区', '330400', '2');
INSERT INTO `jipu_area` VALUES ('330411', '秀洲区', '330400', '3');
INSERT INTO `jipu_area` VALUES ('330421', '嘉善县', '330400', '4');
INSERT INTO `jipu_area` VALUES ('330424', '海盐县', '330400', '5');
INSERT INTO `jipu_area` VALUES ('330481', '海宁市', '330400', '6');
INSERT INTO `jipu_area` VALUES ('330482', '平湖市', '330400', '7');
INSERT INTO `jipu_area` VALUES ('330483', '桐乡市', '330400', '8');
INSERT INTO `jipu_area` VALUES ('330500', '湖州市', '330000', '5');
INSERT INTO `jipu_area` VALUES ('330501', '市辖区', '330500', '1');
INSERT INTO `jipu_area` VALUES ('330502', '吴兴区', '330500', '2');
INSERT INTO `jipu_area` VALUES ('330503', '南浔区', '330500', '3');
INSERT INTO `jipu_area` VALUES ('330521', '德清县', '330500', '4');
INSERT INTO `jipu_area` VALUES ('330522', '长兴县', '330500', '5');
INSERT INTO `jipu_area` VALUES ('330523', '安吉县', '330500', '6');
INSERT INTO `jipu_area` VALUES ('330600', '绍兴市', '330000', '6');
INSERT INTO `jipu_area` VALUES ('330601', '市辖区', '330600', '1');
INSERT INTO `jipu_area` VALUES ('330602', '越城区', '330600', '2');
INSERT INTO `jipu_area` VALUES ('330621', '绍兴县', '330600', '3');
INSERT INTO `jipu_area` VALUES ('330624', '新昌县', '330600', '4');
INSERT INTO `jipu_area` VALUES ('330681', '诸暨市', '330600', '5');
INSERT INTO `jipu_area` VALUES ('330682', '上虞市', '330600', '6');
INSERT INTO `jipu_area` VALUES ('330683', '嵊州市', '330600', '7');
INSERT INTO `jipu_area` VALUES ('330700', '金华市', '330000', '7');
INSERT INTO `jipu_area` VALUES ('330701', '市辖区', '330700', '1');
INSERT INTO `jipu_area` VALUES ('330702', '婺城区', '330700', '2');
INSERT INTO `jipu_area` VALUES ('330703', '金东区', '330700', '3');
INSERT INTO `jipu_area` VALUES ('330723', '武义县', '330700', '4');
INSERT INTO `jipu_area` VALUES ('330726', '浦江县', '330700', '5');
INSERT INTO `jipu_area` VALUES ('330727', '磐安县', '330700', '6');
INSERT INTO `jipu_area` VALUES ('330781', '兰溪市', '330700', '7');
INSERT INTO `jipu_area` VALUES ('330782', '义乌市', '330700', '8');
INSERT INTO `jipu_area` VALUES ('330783', '东阳市', '330700', '9');
INSERT INTO `jipu_area` VALUES ('330784', '永康市', '330700', '10');
INSERT INTO `jipu_area` VALUES ('330800', '衢州市', '330000', '8');
INSERT INTO `jipu_area` VALUES ('330801', '市辖区', '330800', '1');
INSERT INTO `jipu_area` VALUES ('330802', '柯城区', '330800', '2');
INSERT INTO `jipu_area` VALUES ('330803', '衢江区', '330800', '3');
INSERT INTO `jipu_area` VALUES ('330822', '常山县', '330800', '4');
INSERT INTO `jipu_area` VALUES ('330824', '开化县', '330800', '5');
INSERT INTO `jipu_area` VALUES ('330825', '龙游县', '330800', '6');
INSERT INTO `jipu_area` VALUES ('330881', '江山市', '330800', '7');
INSERT INTO `jipu_area` VALUES ('330900', '舟山市', '330000', '9');
INSERT INTO `jipu_area` VALUES ('330901', '市辖区', '330900', '1');
INSERT INTO `jipu_area` VALUES ('330902', '定海区', '330900', '2');
INSERT INTO `jipu_area` VALUES ('330903', '普陀区', '330900', '3');
INSERT INTO `jipu_area` VALUES ('330921', '岱山县', '330900', '4');
INSERT INTO `jipu_area` VALUES ('330922', '嵊泗县', '330900', '5');
INSERT INTO `jipu_area` VALUES ('331000', '台州市', '330000', '10');
INSERT INTO `jipu_area` VALUES ('331001', '市辖区', '331000', '1');
INSERT INTO `jipu_area` VALUES ('331002', '椒江区', '331000', '2');
INSERT INTO `jipu_area` VALUES ('331003', '黄岩区', '331000', '3');
INSERT INTO `jipu_area` VALUES ('331004', '路桥区', '331000', '4');
INSERT INTO `jipu_area` VALUES ('331021', '玉环县', '331000', '5');
INSERT INTO `jipu_area` VALUES ('331022', '三门县', '331000', '6');
INSERT INTO `jipu_area` VALUES ('331023', '天台县', '331000', '7');
INSERT INTO `jipu_area` VALUES ('331024', '仙居县', '331000', '8');
INSERT INTO `jipu_area` VALUES ('331081', '温岭市', '331000', '9');
INSERT INTO `jipu_area` VALUES ('331082', '临海市', '331000', '10');
INSERT INTO `jipu_area` VALUES ('331100', '丽水市', '330000', '11');
INSERT INTO `jipu_area` VALUES ('331101', '市辖区', '331100', '1');
INSERT INTO `jipu_area` VALUES ('331102', '莲都区', '331100', '2');
INSERT INTO `jipu_area` VALUES ('331121', '青田县', '331100', '3');
INSERT INTO `jipu_area` VALUES ('331122', '缙云县', '331100', '4');
INSERT INTO `jipu_area` VALUES ('331123', '遂昌县', '331100', '5');
INSERT INTO `jipu_area` VALUES ('331124', '松阳县', '331100', '6');
INSERT INTO `jipu_area` VALUES ('331125', '云和县', '331100', '7');
INSERT INTO `jipu_area` VALUES ('331126', '庆元县', '331100', '8');
INSERT INTO `jipu_area` VALUES ('331127', '景宁畲族自治县', '331100', '9');
INSERT INTO `jipu_area` VALUES ('331181', '龙泉市', '331100', '10');
INSERT INTO `jipu_area` VALUES ('340000', '安徽省', '0', '12');
INSERT INTO `jipu_area` VALUES ('340100', '合肥市', '340000', '1');
INSERT INTO `jipu_area` VALUES ('340101', '市辖区', '340100', '1');
INSERT INTO `jipu_area` VALUES ('340102', '瑶海区', '340100', '2');
INSERT INTO `jipu_area` VALUES ('340103', '庐阳区', '340100', '3');
INSERT INTO `jipu_area` VALUES ('340104', '蜀山区', '340100', '4');
INSERT INTO `jipu_area` VALUES ('340111', '包河区', '340100', '5');
INSERT INTO `jipu_area` VALUES ('340121', '长丰县', '340100', '6');
INSERT INTO `jipu_area` VALUES ('340122', '肥东县', '340100', '7');
INSERT INTO `jipu_area` VALUES ('340123', '肥西县', '340100', '8');
INSERT INTO `jipu_area` VALUES ('340200', '芜湖市', '340000', '2');
INSERT INTO `jipu_area` VALUES ('340201', '市辖区', '340200', '1');
INSERT INTO `jipu_area` VALUES ('340202', '镜湖区', '340200', '2');
INSERT INTO `jipu_area` VALUES ('340203', '马塘区', '340200', '3');
INSERT INTO `jipu_area` VALUES ('340204', '新芜区', '340200', '4');
INSERT INTO `jipu_area` VALUES ('340207', '鸠江区', '340200', '5');
INSERT INTO `jipu_area` VALUES ('340221', '芜湖县', '340200', '6');
INSERT INTO `jipu_area` VALUES ('340222', '繁昌县', '340200', '7');
INSERT INTO `jipu_area` VALUES ('340223', '南陵县', '340200', '8');
INSERT INTO `jipu_area` VALUES ('340300', '蚌埠市', '340000', '3');
INSERT INTO `jipu_area` VALUES ('340301', '市辖区', '340300', '1');
INSERT INTO `jipu_area` VALUES ('340302', '龙子湖区', '340300', '2');
INSERT INTO `jipu_area` VALUES ('340303', '蚌山区', '340300', '3');
INSERT INTO `jipu_area` VALUES ('340304', '禹会区', '340300', '4');
INSERT INTO `jipu_area` VALUES ('340311', '淮上区', '340300', '5');
INSERT INTO `jipu_area` VALUES ('340321', '怀远县', '340300', '6');
INSERT INTO `jipu_area` VALUES ('340322', '五河县', '340300', '7');
INSERT INTO `jipu_area` VALUES ('340323', '固镇县', '340300', '8');
INSERT INTO `jipu_area` VALUES ('340400', '淮南市', '340000', '4');
INSERT INTO `jipu_area` VALUES ('340401', '市辖区', '340400', '1');
INSERT INTO `jipu_area` VALUES ('340402', '大通区', '340400', '2');
INSERT INTO `jipu_area` VALUES ('340403', '田家庵区', '340400', '3');
INSERT INTO `jipu_area` VALUES ('340404', '谢家集区', '340400', '4');
INSERT INTO `jipu_area` VALUES ('340405', '八公山区', '340400', '5');
INSERT INTO `jipu_area` VALUES ('340406', '潘集区', '340400', '6');
INSERT INTO `jipu_area` VALUES ('340421', '凤台县', '340400', '7');
INSERT INTO `jipu_area` VALUES ('340500', '马鞍山市', '340000', '5');
INSERT INTO `jipu_area` VALUES ('340501', '市辖区', '340500', '1');
INSERT INTO `jipu_area` VALUES ('340502', '金家庄区', '340500', '2');
INSERT INTO `jipu_area` VALUES ('340503', '花山区', '340500', '3');
INSERT INTO `jipu_area` VALUES ('340504', '雨山区', '340500', '4');
INSERT INTO `jipu_area` VALUES ('340521', '当涂县', '340500', '5');
INSERT INTO `jipu_area` VALUES ('340600', '淮北市', '340000', '6');
INSERT INTO `jipu_area` VALUES ('340601', '市辖区', '340600', '1');
INSERT INTO `jipu_area` VALUES ('340602', '杜集区', '340600', '2');
INSERT INTO `jipu_area` VALUES ('340603', '相山区', '340600', '3');
INSERT INTO `jipu_area` VALUES ('340604', '烈山区', '340600', '4');
INSERT INTO `jipu_area` VALUES ('340621', '濉溪县', '340600', '5');
INSERT INTO `jipu_area` VALUES ('340700', '铜陵市', '340000', '7');
INSERT INTO `jipu_area` VALUES ('340701', '市辖区', '340700', '1');
INSERT INTO `jipu_area` VALUES ('340702', '铜官山区', '340700', '2');
INSERT INTO `jipu_area` VALUES ('340703', '狮子山区', '340700', '3');
INSERT INTO `jipu_area` VALUES ('340711', '郊　区', '340700', '4');
INSERT INTO `jipu_area` VALUES ('340721', '铜陵县', '340700', '5');
INSERT INTO `jipu_area` VALUES ('340800', '安庆市', '340000', '8');
INSERT INTO `jipu_area` VALUES ('340801', '市辖区', '340800', '1');
INSERT INTO `jipu_area` VALUES ('340802', '迎江区', '340800', '2');
INSERT INTO `jipu_area` VALUES ('340803', '大观区', '340800', '3');
INSERT INTO `jipu_area` VALUES ('340811', '郊　区', '340800', '4');
INSERT INTO `jipu_area` VALUES ('340822', '怀宁县', '340800', '5');
INSERT INTO `jipu_area` VALUES ('340823', '枞阳县', '340800', '6');
INSERT INTO `jipu_area` VALUES ('340824', '潜山县', '340800', '7');
INSERT INTO `jipu_area` VALUES ('340825', '太湖县', '340800', '8');
INSERT INTO `jipu_area` VALUES ('340826', '宿松县', '340800', '9');
INSERT INTO `jipu_area` VALUES ('340827', '望江县', '340800', '10');
INSERT INTO `jipu_area` VALUES ('340828', '岳西县', '340800', '11');
INSERT INTO `jipu_area` VALUES ('340881', '桐城市', '340800', '12');
INSERT INTO `jipu_area` VALUES ('341000', '黄山市', '340000', '9');
INSERT INTO `jipu_area` VALUES ('341001', '市辖区', '341000', '1');
INSERT INTO `jipu_area` VALUES ('341002', '屯溪区', '341000', '2');
INSERT INTO `jipu_area` VALUES ('341003', '黄山区', '341000', '3');
INSERT INTO `jipu_area` VALUES ('341004', '徽州区', '341000', '4');
INSERT INTO `jipu_area` VALUES ('341021', '歙　县', '341000', '5');
INSERT INTO `jipu_area` VALUES ('341022', '休宁县', '341000', '6');
INSERT INTO `jipu_area` VALUES ('341023', '黟　县', '341000', '7');
INSERT INTO `jipu_area` VALUES ('341024', '祁门县', '341000', '8');
INSERT INTO `jipu_area` VALUES ('341100', '滁州市', '340000', '10');
INSERT INTO `jipu_area` VALUES ('341101', '市辖区', '341100', '1');
INSERT INTO `jipu_area` VALUES ('341102', '琅琊区', '341100', '2');
INSERT INTO `jipu_area` VALUES ('341103', '南谯区', '341100', '3');
INSERT INTO `jipu_area` VALUES ('341122', '来安县', '341100', '4');
INSERT INTO `jipu_area` VALUES ('341124', '全椒县', '341100', '5');
INSERT INTO `jipu_area` VALUES ('341125', '定远县', '341100', '6');
INSERT INTO `jipu_area` VALUES ('341126', '凤阳县', '341100', '7');
INSERT INTO `jipu_area` VALUES ('341181', '天长市', '341100', '8');
INSERT INTO `jipu_area` VALUES ('341182', '明光市', '341100', '9');
INSERT INTO `jipu_area` VALUES ('341200', '阜阳市', '340000', '11');
INSERT INTO `jipu_area` VALUES ('341201', '市辖区', '341200', '1');
INSERT INTO `jipu_area` VALUES ('341202', '颍州区', '341200', '2');
INSERT INTO `jipu_area` VALUES ('341203', '颍东区', '341200', '3');
INSERT INTO `jipu_area` VALUES ('341204', '颍泉区', '341200', '4');
INSERT INTO `jipu_area` VALUES ('341221', '临泉县', '341200', '5');
INSERT INTO `jipu_area` VALUES ('341222', '太和县', '341200', '6');
INSERT INTO `jipu_area` VALUES ('341225', '阜南县', '341200', '7');
INSERT INTO `jipu_area` VALUES ('341226', '颍上县', '341200', '8');
INSERT INTO `jipu_area` VALUES ('341282', '界首市', '341200', '9');
INSERT INTO `jipu_area` VALUES ('341300', '宿州市', '340000', '12');
INSERT INTO `jipu_area` VALUES ('341301', '市辖区', '341300', '1');
INSERT INTO `jipu_area` VALUES ('341302', '墉桥区', '341300', '2');
INSERT INTO `jipu_area` VALUES ('341321', '砀山县', '341300', '3');
INSERT INTO `jipu_area` VALUES ('341322', '萧　县', '341300', '4');
INSERT INTO `jipu_area` VALUES ('341323', '灵璧县', '341300', '5');
INSERT INTO `jipu_area` VALUES ('341324', '泗　县', '341300', '6');
INSERT INTO `jipu_area` VALUES ('341401', '庐江县', '340100', '9');
INSERT INTO `jipu_area` VALUES ('341402', '巢湖市', '340100', '10');
INSERT INTO `jipu_area` VALUES ('341422', '无为县', '340200', '9');
INSERT INTO `jipu_area` VALUES ('341423', '含山县', '340500', '6');
INSERT INTO `jipu_area` VALUES ('341424', '和　县', '340500', '7');
INSERT INTO `jipu_area` VALUES ('341500', '六安市', '340000', '13');
INSERT INTO `jipu_area` VALUES ('341501', '市辖区', '341500', '1');
INSERT INTO `jipu_area` VALUES ('341502', '金安区', '341500', '2');
INSERT INTO `jipu_area` VALUES ('341503', '裕安区', '341500', '3');
INSERT INTO `jipu_area` VALUES ('341521', '寿　县', '341500', '4');
INSERT INTO `jipu_area` VALUES ('341522', '霍邱县', '341500', '5');
INSERT INTO `jipu_area` VALUES ('341523', '舒城县', '341500', '6');
INSERT INTO `jipu_area` VALUES ('341524', '金寨县', '341500', '7');
INSERT INTO `jipu_area` VALUES ('341525', '霍山县', '341500', '8');
INSERT INTO `jipu_area` VALUES ('341600', '亳州市', '340000', '14');
INSERT INTO `jipu_area` VALUES ('341601', '市辖区', '341600', '1');
INSERT INTO `jipu_area` VALUES ('341602', '谯城区', '341600', '2');
INSERT INTO `jipu_area` VALUES ('341621', '涡阳县', '341600', '3');
INSERT INTO `jipu_area` VALUES ('341622', '蒙城县', '341600', '4');
INSERT INTO `jipu_area` VALUES ('341623', '利辛县', '341600', '5');
INSERT INTO `jipu_area` VALUES ('341700', '池州市', '340000', '15');
INSERT INTO `jipu_area` VALUES ('341701', '市辖区', '341700', '1');
INSERT INTO `jipu_area` VALUES ('341702', '贵池区', '341700', '2');
INSERT INTO `jipu_area` VALUES ('341721', '东至县', '341700', '3');
INSERT INTO `jipu_area` VALUES ('341722', '石台县', '341700', '4');
INSERT INTO `jipu_area` VALUES ('341723', '青阳县', '341700', '5');
INSERT INTO `jipu_area` VALUES ('341800', '宣城市', '340000', '16');
INSERT INTO `jipu_area` VALUES ('341801', '市辖区', '341800', '1');
INSERT INTO `jipu_area` VALUES ('341802', '宣州区', '341800', '2');
INSERT INTO `jipu_area` VALUES ('341821', '郎溪县', '341800', '3');
INSERT INTO `jipu_area` VALUES ('341822', '广德县', '341800', '4');
INSERT INTO `jipu_area` VALUES ('341823', '泾　县', '341800', '5');
INSERT INTO `jipu_area` VALUES ('341824', '绩溪县', '341800', '6');
INSERT INTO `jipu_area` VALUES ('341825', '旌德县', '341800', '7');
INSERT INTO `jipu_area` VALUES ('341881', '宁国市', '341800', '8');
INSERT INTO `jipu_area` VALUES ('350000', '福建省', '0', '13');
INSERT INTO `jipu_area` VALUES ('350100', '福州市', '350000', '1');
INSERT INTO `jipu_area` VALUES ('350101', '市辖区', '350100', '1');
INSERT INTO `jipu_area` VALUES ('350102', '鼓楼区', '350100', '2');
INSERT INTO `jipu_area` VALUES ('350103', '台江区', '350100', '3');
INSERT INTO `jipu_area` VALUES ('350104', '仓山区', '350100', '4');
INSERT INTO `jipu_area` VALUES ('350105', '马尾区', '350100', '5');
INSERT INTO `jipu_area` VALUES ('350111', '晋安区', '350100', '6');
INSERT INTO `jipu_area` VALUES ('350121', '闽侯县', '350100', '7');
INSERT INTO `jipu_area` VALUES ('350122', '连江县', '350100', '8');
INSERT INTO `jipu_area` VALUES ('350123', '罗源县', '350100', '9');
INSERT INTO `jipu_area` VALUES ('350124', '闽清县', '350100', '10');
INSERT INTO `jipu_area` VALUES ('350125', '永泰县', '350100', '11');
INSERT INTO `jipu_area` VALUES ('350128', '平潭县', '350100', '12');
INSERT INTO `jipu_area` VALUES ('350181', '福清市', '350100', '13');
INSERT INTO `jipu_area` VALUES ('350182', '长乐市', '350100', '14');
INSERT INTO `jipu_area` VALUES ('350200', '厦门市', '350000', '2');
INSERT INTO `jipu_area` VALUES ('350201', '市辖区', '350200', '1');
INSERT INTO `jipu_area` VALUES ('350203', '思明区', '350200', '2');
INSERT INTO `jipu_area` VALUES ('350205', '海沧区', '350200', '3');
INSERT INTO `jipu_area` VALUES ('350206', '湖里区', '350200', '4');
INSERT INTO `jipu_area` VALUES ('350211', '集美区', '350200', '5');
INSERT INTO `jipu_area` VALUES ('350212', '同安区', '350200', '6');
INSERT INTO `jipu_area` VALUES ('350213', '翔安区', '350200', '7');
INSERT INTO `jipu_area` VALUES ('350300', '莆田市', '350000', '3');
INSERT INTO `jipu_area` VALUES ('350301', '市辖区', '350300', '1');
INSERT INTO `jipu_area` VALUES ('350302', '城厢区', '350300', '2');
INSERT INTO `jipu_area` VALUES ('350303', '涵江区', '350300', '3');
INSERT INTO `jipu_area` VALUES ('350304', '荔城区', '350300', '4');
INSERT INTO `jipu_area` VALUES ('350305', '秀屿区', '350300', '5');
INSERT INTO `jipu_area` VALUES ('350322', '仙游县', '350300', '6');
INSERT INTO `jipu_area` VALUES ('350400', '三明市', '350000', '4');
INSERT INTO `jipu_area` VALUES ('350401', '市辖区', '350400', '1');
INSERT INTO `jipu_area` VALUES ('350402', '梅列区', '350400', '2');
INSERT INTO `jipu_area` VALUES ('350403', '三元区', '350400', '3');
INSERT INTO `jipu_area` VALUES ('350421', '明溪县', '350400', '4');
INSERT INTO `jipu_area` VALUES ('350423', '清流县', '350400', '5');
INSERT INTO `jipu_area` VALUES ('350424', '宁化县', '350400', '6');
INSERT INTO `jipu_area` VALUES ('350425', '大田县', '350400', '7');
INSERT INTO `jipu_area` VALUES ('350426', '尤溪县', '350400', '8');
INSERT INTO `jipu_area` VALUES ('350427', '沙　县', '350400', '9');
INSERT INTO `jipu_area` VALUES ('350428', '将乐县', '350400', '10');
INSERT INTO `jipu_area` VALUES ('350429', '泰宁县', '350400', '11');
INSERT INTO `jipu_area` VALUES ('350430', '建宁县', '350400', '12');
INSERT INTO `jipu_area` VALUES ('350481', '永安市', '350400', '13');
INSERT INTO `jipu_area` VALUES ('350500', '泉州市', '350000', '5');
INSERT INTO `jipu_area` VALUES ('350501', '市辖区', '350500', '1');
INSERT INTO `jipu_area` VALUES ('350502', '鲤城区', '350500', '2');
INSERT INTO `jipu_area` VALUES ('350503', '丰泽区', '350500', '3');
INSERT INTO `jipu_area` VALUES ('350504', '洛江区', '350500', '4');
INSERT INTO `jipu_area` VALUES ('350505', '泉港区', '350500', '5');
INSERT INTO `jipu_area` VALUES ('350521', '惠安县', '350500', '6');
INSERT INTO `jipu_area` VALUES ('350524', '安溪县', '350500', '7');
INSERT INTO `jipu_area` VALUES ('350525', '永春县', '350500', '8');
INSERT INTO `jipu_area` VALUES ('350526', '德化县', '350500', '9');
INSERT INTO `jipu_area` VALUES ('350527', '金门县', '350500', '10');
INSERT INTO `jipu_area` VALUES ('350581', '石狮市', '350500', '11');
INSERT INTO `jipu_area` VALUES ('350582', '晋江市', '350500', '12');
INSERT INTO `jipu_area` VALUES ('350583', '南安市', '350500', '13');
INSERT INTO `jipu_area` VALUES ('350600', '漳州市', '350000', '6');
INSERT INTO `jipu_area` VALUES ('350601', '市辖区', '350600', '1');
INSERT INTO `jipu_area` VALUES ('350602', '芗城区', '350600', '2');
INSERT INTO `jipu_area` VALUES ('350603', '龙文区', '350600', '3');
INSERT INTO `jipu_area` VALUES ('350622', '云霄县', '350600', '4');
INSERT INTO `jipu_area` VALUES ('350623', '漳浦县', '350600', '5');
INSERT INTO `jipu_area` VALUES ('350624', '诏安县', '350600', '6');
INSERT INTO `jipu_area` VALUES ('350625', '长泰县', '350600', '7');
INSERT INTO `jipu_area` VALUES ('350626', '东山县', '350600', '8');
INSERT INTO `jipu_area` VALUES ('350627', '南靖县', '350600', '9');
INSERT INTO `jipu_area` VALUES ('350628', '平和县', '350600', '10');
INSERT INTO `jipu_area` VALUES ('350629', '华安县', '350600', '11');
INSERT INTO `jipu_area` VALUES ('350681', '龙海市', '350600', '12');
INSERT INTO `jipu_area` VALUES ('350700', '南平市', '350000', '7');
INSERT INTO `jipu_area` VALUES ('350701', '市辖区', '350700', '1');
INSERT INTO `jipu_area` VALUES ('350702', '延平区', '350700', '2');
INSERT INTO `jipu_area` VALUES ('350721', '顺昌县', '350700', '3');
INSERT INTO `jipu_area` VALUES ('350722', '浦城县', '350700', '4');
INSERT INTO `jipu_area` VALUES ('350723', '光泽县', '350700', '5');
INSERT INTO `jipu_area` VALUES ('350724', '松溪县', '350700', '6');
INSERT INTO `jipu_area` VALUES ('350725', '政和县', '350700', '7');
INSERT INTO `jipu_area` VALUES ('350781', '邵武市', '350700', '8');
INSERT INTO `jipu_area` VALUES ('350782', '武夷山市', '350700', '9');
INSERT INTO `jipu_area` VALUES ('350783', '建瓯市', '350700', '10');
INSERT INTO `jipu_area` VALUES ('350784', '建阳市', '350700', '11');
INSERT INTO `jipu_area` VALUES ('350800', '龙岩市', '350000', '8');
INSERT INTO `jipu_area` VALUES ('350801', '市辖区', '350800', '1');
INSERT INTO `jipu_area` VALUES ('350802', '新罗区', '350800', '2');
INSERT INTO `jipu_area` VALUES ('350821', '长汀县', '350800', '3');
INSERT INTO `jipu_area` VALUES ('350822', '永定县', '350800', '4');
INSERT INTO `jipu_area` VALUES ('350823', '上杭县', '350800', '5');
INSERT INTO `jipu_area` VALUES ('350824', '武平县', '350800', '6');
INSERT INTO `jipu_area` VALUES ('350825', '连城县', '350800', '7');
INSERT INTO `jipu_area` VALUES ('350881', '漳平市', '350800', '8');
INSERT INTO `jipu_area` VALUES ('350900', '宁德市', '350000', '9');
INSERT INTO `jipu_area` VALUES ('350901', '市辖区', '350900', '1');
INSERT INTO `jipu_area` VALUES ('350902', '蕉城区', '350900', '2');
INSERT INTO `jipu_area` VALUES ('350921', '霞浦县', '350900', '3');
INSERT INTO `jipu_area` VALUES ('350922', '古田县', '350900', '4');
INSERT INTO `jipu_area` VALUES ('350923', '屏南县', '350900', '5');
INSERT INTO `jipu_area` VALUES ('350924', '寿宁县', '350900', '6');
INSERT INTO `jipu_area` VALUES ('350925', '周宁县', '350900', '7');
INSERT INTO `jipu_area` VALUES ('350926', '柘荣县', '350900', '8');
INSERT INTO `jipu_area` VALUES ('350981', '福安市', '350900', '9');
INSERT INTO `jipu_area` VALUES ('350982', '福鼎市', '350900', '10');
INSERT INTO `jipu_area` VALUES ('360000', '江西省', '0', '14');
INSERT INTO `jipu_area` VALUES ('360100', '南昌市', '360000', '1');
INSERT INTO `jipu_area` VALUES ('360101', '市辖区', '360100', '1');
INSERT INTO `jipu_area` VALUES ('360102', '东湖区', '360100', '2');
INSERT INTO `jipu_area` VALUES ('360103', '西湖区', '360100', '3');
INSERT INTO `jipu_area` VALUES ('360104', '青云谱区', '360100', '4');
INSERT INTO `jipu_area` VALUES ('360105', '湾里区', '360100', '5');
INSERT INTO `jipu_area` VALUES ('360111', '青山湖区', '360100', '6');
INSERT INTO `jipu_area` VALUES ('360121', '南昌县', '360100', '7');
INSERT INTO `jipu_area` VALUES ('360122', '新建县', '360100', '8');
INSERT INTO `jipu_area` VALUES ('360123', '安义县', '360100', '9');
INSERT INTO `jipu_area` VALUES ('360124', '进贤县', '360100', '10');
INSERT INTO `jipu_area` VALUES ('360200', '景德镇市', '360000', '2');
INSERT INTO `jipu_area` VALUES ('360201', '市辖区', '360200', '1');
INSERT INTO `jipu_area` VALUES ('360202', '昌江区', '360200', '2');
INSERT INTO `jipu_area` VALUES ('360203', '珠山区', '360200', '3');
INSERT INTO `jipu_area` VALUES ('360222', '浮梁县', '360200', '4');
INSERT INTO `jipu_area` VALUES ('360281', '乐平市', '360200', '5');
INSERT INTO `jipu_area` VALUES ('360300', '萍乡市', '360000', '3');
INSERT INTO `jipu_area` VALUES ('360301', '市辖区', '360300', '1');
INSERT INTO `jipu_area` VALUES ('360302', '安源区', '360300', '2');
INSERT INTO `jipu_area` VALUES ('360313', '湘东区', '360300', '3');
INSERT INTO `jipu_area` VALUES ('360321', '莲花县', '360300', '4');
INSERT INTO `jipu_area` VALUES ('360322', '上栗县', '360300', '5');
INSERT INTO `jipu_area` VALUES ('360323', '芦溪县', '360300', '6');
INSERT INTO `jipu_area` VALUES ('360400', '九江市', '360000', '4');
INSERT INTO `jipu_area` VALUES ('360401', '市辖区', '360400', '1');
INSERT INTO `jipu_area` VALUES ('360402', '庐山区', '360400', '2');
INSERT INTO `jipu_area` VALUES ('360403', '浔阳区', '360400', '3');
INSERT INTO `jipu_area` VALUES ('360421', '九江县', '360400', '4');
INSERT INTO `jipu_area` VALUES ('360423', '武宁县', '360400', '5');
INSERT INTO `jipu_area` VALUES ('360424', '修水县', '360400', '6');
INSERT INTO `jipu_area` VALUES ('360425', '永修县', '360400', '7');
INSERT INTO `jipu_area` VALUES ('360426', '德安县', '360400', '8');
INSERT INTO `jipu_area` VALUES ('360427', '星子县', '360400', '9');
INSERT INTO `jipu_area` VALUES ('360428', '都昌县', '360400', '10');
INSERT INTO `jipu_area` VALUES ('360429', '湖口县', '360400', '11');
INSERT INTO `jipu_area` VALUES ('360430', '彭泽县', '360400', '12');
INSERT INTO `jipu_area` VALUES ('360481', '瑞昌市', '360400', '13');
INSERT INTO `jipu_area` VALUES ('360500', '新余市', '360000', '5');
INSERT INTO `jipu_area` VALUES ('360501', '市辖区', '360500', '1');
INSERT INTO `jipu_area` VALUES ('360502', '渝水区', '360500', '2');
INSERT INTO `jipu_area` VALUES ('360521', '分宜县', '360500', '3');
INSERT INTO `jipu_area` VALUES ('360600', '鹰潭市', '360000', '6');
INSERT INTO `jipu_area` VALUES ('360601', '市辖区', '360600', '1');
INSERT INTO `jipu_area` VALUES ('360602', '月湖区', '360600', '2');
INSERT INTO `jipu_area` VALUES ('360622', '余江县', '360600', '3');
INSERT INTO `jipu_area` VALUES ('360681', '贵溪市', '360600', '4');
INSERT INTO `jipu_area` VALUES ('360700', '赣州市', '360000', '7');
INSERT INTO `jipu_area` VALUES ('360701', '市辖区', '360700', '1');
INSERT INTO `jipu_area` VALUES ('360702', '章贡区', '360700', '2');
INSERT INTO `jipu_area` VALUES ('360721', '赣　县', '360700', '3');
INSERT INTO `jipu_area` VALUES ('360722', '信丰县', '360700', '4');
INSERT INTO `jipu_area` VALUES ('360723', '大余县', '360700', '5');
INSERT INTO `jipu_area` VALUES ('360724', '上犹县', '360700', '6');
INSERT INTO `jipu_area` VALUES ('360725', '崇义县', '360700', '7');
INSERT INTO `jipu_area` VALUES ('360726', '安远县', '360700', '8');
INSERT INTO `jipu_area` VALUES ('360727', '龙南县', '360700', '9');
INSERT INTO `jipu_area` VALUES ('360728', '定南县', '360700', '10');
INSERT INTO `jipu_area` VALUES ('360729', '全南县', '360700', '11');
INSERT INTO `jipu_area` VALUES ('360730', '宁都县', '360700', '12');
INSERT INTO `jipu_area` VALUES ('360731', '于都县', '360700', '13');
INSERT INTO `jipu_area` VALUES ('360732', '兴国县', '360700', '14');
INSERT INTO `jipu_area` VALUES ('360733', '会昌县', '360700', '15');
INSERT INTO `jipu_area` VALUES ('360734', '寻乌县', '360700', '16');
INSERT INTO `jipu_area` VALUES ('360735', '石城县', '360700', '17');
INSERT INTO `jipu_area` VALUES ('360781', '瑞金市', '360700', '18');
INSERT INTO `jipu_area` VALUES ('360782', '南康市', '360700', '19');
INSERT INTO `jipu_area` VALUES ('360800', '吉安市', '360000', '8');
INSERT INTO `jipu_area` VALUES ('360801', '市辖区', '360800', '1');
INSERT INTO `jipu_area` VALUES ('360802', '吉州区', '360800', '2');
INSERT INTO `jipu_area` VALUES ('360803', '青原区', '360800', '3');
INSERT INTO `jipu_area` VALUES ('360821', '吉安县', '360800', '4');
INSERT INTO `jipu_area` VALUES ('360822', '吉水县', '360800', '5');
INSERT INTO `jipu_area` VALUES ('360823', '峡江县', '360800', '6');
INSERT INTO `jipu_area` VALUES ('360824', '新干县', '360800', '7');
INSERT INTO `jipu_area` VALUES ('360825', '永丰县', '360800', '8');
INSERT INTO `jipu_area` VALUES ('360826', '泰和县', '360800', '9');
INSERT INTO `jipu_area` VALUES ('360827', '遂川县', '360800', '10');
INSERT INTO `jipu_area` VALUES ('360828', '万安县', '360800', '11');
INSERT INTO `jipu_area` VALUES ('360829', '安福县', '360800', '12');
INSERT INTO `jipu_area` VALUES ('360830', '永新县', '360800', '13');
INSERT INTO `jipu_area` VALUES ('360881', '井冈山市', '360800', '14');
INSERT INTO `jipu_area` VALUES ('360900', '宜春市', '360000', '9');
INSERT INTO `jipu_area` VALUES ('360901', '市辖区', '360900', '1');
INSERT INTO `jipu_area` VALUES ('360902', '袁州区', '360900', '2');
INSERT INTO `jipu_area` VALUES ('360921', '奉新县', '360900', '3');
INSERT INTO `jipu_area` VALUES ('360922', '万载县', '360900', '4');
INSERT INTO `jipu_area` VALUES ('360923', '上高县', '360900', '5');
INSERT INTO `jipu_area` VALUES ('360924', '宜丰县', '360900', '6');
INSERT INTO `jipu_area` VALUES ('360925', '靖安县', '360900', '7');
INSERT INTO `jipu_area` VALUES ('360926', '铜鼓县', '360900', '8');
INSERT INTO `jipu_area` VALUES ('360981', '丰城市', '360900', '9');
INSERT INTO `jipu_area` VALUES ('360982', '樟树市', '360900', '10');
INSERT INTO `jipu_area` VALUES ('360983', '高安市', '360900', '11');
INSERT INTO `jipu_area` VALUES ('361000', '抚州市', '360000', '10');
INSERT INTO `jipu_area` VALUES ('361001', '市辖区', '361000', '1');
INSERT INTO `jipu_area` VALUES ('361002', '临川区', '361000', '2');
INSERT INTO `jipu_area` VALUES ('361021', '南城县', '361000', '3');
INSERT INTO `jipu_area` VALUES ('361022', '黎川县', '361000', '4');
INSERT INTO `jipu_area` VALUES ('361023', '南丰县', '361000', '5');
INSERT INTO `jipu_area` VALUES ('361024', '崇仁县', '361000', '6');
INSERT INTO `jipu_area` VALUES ('361025', '乐安县', '361000', '7');
INSERT INTO `jipu_area` VALUES ('361026', '宜黄县', '361000', '8');
INSERT INTO `jipu_area` VALUES ('361027', '金溪县', '361000', '9');
INSERT INTO `jipu_area` VALUES ('361028', '资溪县', '361000', '10');
INSERT INTO `jipu_area` VALUES ('361029', '东乡县', '361000', '11');
INSERT INTO `jipu_area` VALUES ('361030', '广昌县', '361000', '12');
INSERT INTO `jipu_area` VALUES ('361100', '上饶市', '360000', '11');
INSERT INTO `jipu_area` VALUES ('361101', '市辖区', '361100', '1');
INSERT INTO `jipu_area` VALUES ('361102', '信州区', '361100', '2');
INSERT INTO `jipu_area` VALUES ('361121', '上饶县', '361100', '3');
INSERT INTO `jipu_area` VALUES ('361122', '广丰县', '361100', '4');
INSERT INTO `jipu_area` VALUES ('361123', '玉山县', '361100', '5');
INSERT INTO `jipu_area` VALUES ('361124', '铅山县', '361100', '6');
INSERT INTO `jipu_area` VALUES ('361125', '横峰县', '361100', '7');
INSERT INTO `jipu_area` VALUES ('361126', '弋阳县', '361100', '8');
INSERT INTO `jipu_area` VALUES ('361127', '余干县', '361100', '9');
INSERT INTO `jipu_area` VALUES ('361128', '鄱阳县', '361100', '10');
INSERT INTO `jipu_area` VALUES ('361129', '万年县', '361100', '11');
INSERT INTO `jipu_area` VALUES ('361130', '婺源县', '361100', '12');
INSERT INTO `jipu_area` VALUES ('361181', '德兴市', '361100', '13');
INSERT INTO `jipu_area` VALUES ('370000', '山东省', '0', '15');
INSERT INTO `jipu_area` VALUES ('370100', '济南市', '370000', '1');
INSERT INTO `jipu_area` VALUES ('370101', '市辖区', '370100', '1');
INSERT INTO `jipu_area` VALUES ('370102', '历下区', '370100', '2');
INSERT INTO `jipu_area` VALUES ('370103', '市中区', '370100', '3');
INSERT INTO `jipu_area` VALUES ('370104', '槐荫区', '370100', '4');
INSERT INTO `jipu_area` VALUES ('370105', '天桥区', '370100', '5');
INSERT INTO `jipu_area` VALUES ('370112', '历城区', '370100', '6');
INSERT INTO `jipu_area` VALUES ('370113', '长清区', '370100', '7');
INSERT INTO `jipu_area` VALUES ('370124', '平阴县', '370100', '8');
INSERT INTO `jipu_area` VALUES ('370125', '济阳县', '370100', '9');
INSERT INTO `jipu_area` VALUES ('370126', '商河县', '370100', '10');
INSERT INTO `jipu_area` VALUES ('370181', '章丘市', '370100', '11');
INSERT INTO `jipu_area` VALUES ('370200', '青岛市', '370000', '2');
INSERT INTO `jipu_area` VALUES ('370201', '市辖区', '370200', '1');
INSERT INTO `jipu_area` VALUES ('370202', '市南区', '370200', '2');
INSERT INTO `jipu_area` VALUES ('370203', '市北区', '370200', '3');
INSERT INTO `jipu_area` VALUES ('370205', '四方区', '370200', '4');
INSERT INTO `jipu_area` VALUES ('370211', '黄岛区', '370200', '5');
INSERT INTO `jipu_area` VALUES ('370212', '崂山区', '370200', '6');
INSERT INTO `jipu_area` VALUES ('370213', '李沧区', '370200', '7');
INSERT INTO `jipu_area` VALUES ('370214', '城阳区', '370200', '8');
INSERT INTO `jipu_area` VALUES ('370281', '胶州市', '370200', '9');
INSERT INTO `jipu_area` VALUES ('370282', '即墨市', '370200', '10');
INSERT INTO `jipu_area` VALUES ('370283', '平度市', '370200', '11');
INSERT INTO `jipu_area` VALUES ('370284', '胶南市', '370200', '12');
INSERT INTO `jipu_area` VALUES ('370285', '莱西市', '370200', '13');
INSERT INTO `jipu_area` VALUES ('370300', '淄博市', '370000', '3');
INSERT INTO `jipu_area` VALUES ('370301', '市辖区', '370300', '1');
INSERT INTO `jipu_area` VALUES ('370302', '淄川区', '370300', '2');
INSERT INTO `jipu_area` VALUES ('370303', '张店区', '370300', '3');
INSERT INTO `jipu_area` VALUES ('370304', '博山区', '370300', '4');
INSERT INTO `jipu_area` VALUES ('370305', '临淄区', '370300', '5');
INSERT INTO `jipu_area` VALUES ('370306', '周村区', '370300', '6');
INSERT INTO `jipu_area` VALUES ('370321', '桓台县', '370300', '7');
INSERT INTO `jipu_area` VALUES ('370322', '高青县', '370300', '8');
INSERT INTO `jipu_area` VALUES ('370323', '沂源县', '370300', '9');
INSERT INTO `jipu_area` VALUES ('370400', '枣庄市', '370000', '4');
INSERT INTO `jipu_area` VALUES ('370401', '市辖区', '370400', '1');
INSERT INTO `jipu_area` VALUES ('370402', '市中区', '370400', '2');
INSERT INTO `jipu_area` VALUES ('370403', '薛城区', '370400', '3');
INSERT INTO `jipu_area` VALUES ('370404', '峄城区', '370400', '4');
INSERT INTO `jipu_area` VALUES ('370405', '台儿庄区', '370400', '5');
INSERT INTO `jipu_area` VALUES ('370406', '山亭区', '370400', '6');
INSERT INTO `jipu_area` VALUES ('370481', '滕州市', '370400', '7');
INSERT INTO `jipu_area` VALUES ('370500', '东营市', '370000', '5');
INSERT INTO `jipu_area` VALUES ('370501', '市辖区', '370500', '1');
INSERT INTO `jipu_area` VALUES ('370502', '东营区', '370500', '2');
INSERT INTO `jipu_area` VALUES ('370503', '河口区', '370500', '3');
INSERT INTO `jipu_area` VALUES ('370521', '垦利县', '370500', '4');
INSERT INTO `jipu_area` VALUES ('370522', '利津县', '370500', '5');
INSERT INTO `jipu_area` VALUES ('370523', '广饶县', '370500', '6');
INSERT INTO `jipu_area` VALUES ('370600', '烟台市', '370000', '6');
INSERT INTO `jipu_area` VALUES ('370601', '市辖区', '370600', '1');
INSERT INTO `jipu_area` VALUES ('370602', '芝罘区', '370600', '2');
INSERT INTO `jipu_area` VALUES ('370611', '福山区', '370600', '3');
INSERT INTO `jipu_area` VALUES ('370612', '牟平区', '370600', '4');
INSERT INTO `jipu_area` VALUES ('370613', '莱山区', '370600', '5');
INSERT INTO `jipu_area` VALUES ('370634', '长岛县', '370600', '6');
INSERT INTO `jipu_area` VALUES ('370681', '龙口市', '370600', '7');
INSERT INTO `jipu_area` VALUES ('370682', '莱阳市', '370600', '8');
INSERT INTO `jipu_area` VALUES ('370683', '莱州市', '370600', '9');
INSERT INTO `jipu_area` VALUES ('370684', '蓬莱市', '370600', '10');
INSERT INTO `jipu_area` VALUES ('370685', '招远市', '370600', '11');
INSERT INTO `jipu_area` VALUES ('370686', '栖霞市', '370600', '12');
INSERT INTO `jipu_area` VALUES ('370687', '海阳市', '370600', '13');
INSERT INTO `jipu_area` VALUES ('370700', '潍坊市', '370000', '7');
INSERT INTO `jipu_area` VALUES ('370701', '市辖区', '370700', '1');
INSERT INTO `jipu_area` VALUES ('370702', '潍城区', '370700', '2');
INSERT INTO `jipu_area` VALUES ('370703', '寒亭区', '370700', '3');
INSERT INTO `jipu_area` VALUES ('370704', '坊子区', '370700', '4');
INSERT INTO `jipu_area` VALUES ('370705', '奎文区', '370700', '5');
INSERT INTO `jipu_area` VALUES ('370724', '临朐县', '370700', '6');
INSERT INTO `jipu_area` VALUES ('370725', '昌乐县', '370700', '7');
INSERT INTO `jipu_area` VALUES ('370781', '青州市', '370700', '8');
INSERT INTO `jipu_area` VALUES ('370782', '诸城市', '370700', '9');
INSERT INTO `jipu_area` VALUES ('370783', '寿光市', '370700', '10');
INSERT INTO `jipu_area` VALUES ('370784', '安丘市', '370700', '11');
INSERT INTO `jipu_area` VALUES ('370785', '高密市', '370700', '12');
INSERT INTO `jipu_area` VALUES ('370786', '昌邑市', '370700', '13');
INSERT INTO `jipu_area` VALUES ('370800', '济宁市', '370000', '8');
INSERT INTO `jipu_area` VALUES ('370801', '市辖区', '370800', '1');
INSERT INTO `jipu_area` VALUES ('370802', '市中区', '370800', '2');
INSERT INTO `jipu_area` VALUES ('370811', '任城区', '370800', '3');
INSERT INTO `jipu_area` VALUES ('370826', '微山县', '370800', '4');
INSERT INTO `jipu_area` VALUES ('370827', '鱼台县', '370800', '5');
INSERT INTO `jipu_area` VALUES ('370828', '金乡县', '370800', '6');
INSERT INTO `jipu_area` VALUES ('370829', '嘉祥县', '370800', '7');
INSERT INTO `jipu_area` VALUES ('370830', '汶上县', '370800', '8');
INSERT INTO `jipu_area` VALUES ('370831', '泗水县', '370800', '9');
INSERT INTO `jipu_area` VALUES ('370832', '梁山县', '370800', '10');
INSERT INTO `jipu_area` VALUES ('370881', '曲阜市', '370800', '11');
INSERT INTO `jipu_area` VALUES ('370882', '兖州市', '370800', '12');
INSERT INTO `jipu_area` VALUES ('370883', '邹城市', '370800', '13');
INSERT INTO `jipu_area` VALUES ('370900', '泰安市', '370000', '9');
INSERT INTO `jipu_area` VALUES ('370901', '市辖区', '370900', '1');
INSERT INTO `jipu_area` VALUES ('370902', '泰山区', '370900', '2');
INSERT INTO `jipu_area` VALUES ('370903', '岱岳区', '370900', '3');
INSERT INTO `jipu_area` VALUES ('370921', '宁阳县', '370900', '4');
INSERT INTO `jipu_area` VALUES ('370923', '东平县', '370900', '5');
INSERT INTO `jipu_area` VALUES ('370982', '新泰市', '370900', '6');
INSERT INTO `jipu_area` VALUES ('370983', '肥城市', '370900', '7');
INSERT INTO `jipu_area` VALUES ('371000', '威海市', '370000', '10');
INSERT INTO `jipu_area` VALUES ('371001', '市辖区', '371000', '1');
INSERT INTO `jipu_area` VALUES ('371002', '环翠区', '371000', '2');
INSERT INTO `jipu_area` VALUES ('371081', '文登市', '371000', '3');
INSERT INTO `jipu_area` VALUES ('371082', '荣成市', '371000', '4');
INSERT INTO `jipu_area` VALUES ('371083', '乳山市', '371000', '5');
INSERT INTO `jipu_area` VALUES ('371100', '日照市', '370000', '11');
INSERT INTO `jipu_area` VALUES ('371101', '市辖区', '371100', '1');
INSERT INTO `jipu_area` VALUES ('371102', '东港区', '371100', '2');
INSERT INTO `jipu_area` VALUES ('371103', '岚山区', '371100', '3');
INSERT INTO `jipu_area` VALUES ('371121', '五莲县', '371100', '4');
INSERT INTO `jipu_area` VALUES ('371122', '莒　县', '371100', '5');
INSERT INTO `jipu_area` VALUES ('371200', '莱芜市', '370000', '12');
INSERT INTO `jipu_area` VALUES ('371201', '市辖区', '371200', '1');
INSERT INTO `jipu_area` VALUES ('371202', '莱城区', '371200', '2');
INSERT INTO `jipu_area` VALUES ('371203', '钢城区', '371200', '3');
INSERT INTO `jipu_area` VALUES ('371300', '临沂市', '370000', '13');
INSERT INTO `jipu_area` VALUES ('371301', '市辖区', '371300', '1');
INSERT INTO `jipu_area` VALUES ('371302', '兰山区', '371300', '2');
INSERT INTO `jipu_area` VALUES ('371311', '罗庄区', '371300', '3');
INSERT INTO `jipu_area` VALUES ('371312', '河东区', '371300', '4');
INSERT INTO `jipu_area` VALUES ('371321', '沂南县', '371300', '5');
INSERT INTO `jipu_area` VALUES ('371322', '郯城县', '371300', '6');
INSERT INTO `jipu_area` VALUES ('371323', '沂水县', '371300', '7');
INSERT INTO `jipu_area` VALUES ('371324', '苍山县', '371300', '8');
INSERT INTO `jipu_area` VALUES ('371325', '费　县', '371300', '9');
INSERT INTO `jipu_area` VALUES ('371326', '平邑县', '371300', '10');
INSERT INTO `jipu_area` VALUES ('371327', '莒南县', '371300', '11');
INSERT INTO `jipu_area` VALUES ('371328', '蒙阴县', '371300', '12');
INSERT INTO `jipu_area` VALUES ('371329', '临沭县', '371300', '13');
INSERT INTO `jipu_area` VALUES ('371400', '德州市', '370000', '14');
INSERT INTO `jipu_area` VALUES ('371401', '市辖区', '371400', '1');
INSERT INTO `jipu_area` VALUES ('371402', '德城区', '371400', '2');
INSERT INTO `jipu_area` VALUES ('371421', '陵　县', '371400', '3');
INSERT INTO `jipu_area` VALUES ('371422', '宁津县', '371400', '4');
INSERT INTO `jipu_area` VALUES ('371423', '庆云县', '371400', '5');
INSERT INTO `jipu_area` VALUES ('371424', '临邑县', '371400', '6');
INSERT INTO `jipu_area` VALUES ('371425', '齐河县', '371400', '7');
INSERT INTO `jipu_area` VALUES ('371426', '平原县', '371400', '8');
INSERT INTO `jipu_area` VALUES ('371427', '夏津县', '371400', '9');
INSERT INTO `jipu_area` VALUES ('371428', '武城县', '371400', '10');
INSERT INTO `jipu_area` VALUES ('371481', '乐陵市', '371400', '11');
INSERT INTO `jipu_area` VALUES ('371482', '禹城市', '371400', '12');
INSERT INTO `jipu_area` VALUES ('371500', '聊城市', '370000', '15');
INSERT INTO `jipu_area` VALUES ('371501', '市辖区', '371500', '1');
INSERT INTO `jipu_area` VALUES ('371502', '东昌府区', '371500', '2');
INSERT INTO `jipu_area` VALUES ('371521', '阳谷县', '371500', '3');
INSERT INTO `jipu_area` VALUES ('371522', '莘　县', '371500', '4');
INSERT INTO `jipu_area` VALUES ('371523', '茌平县', '371500', '5');
INSERT INTO `jipu_area` VALUES ('371524', '东阿县', '371500', '6');
INSERT INTO `jipu_area` VALUES ('371525', '冠　县', '371500', '7');
INSERT INTO `jipu_area` VALUES ('371526', '高唐县', '371500', '8');
INSERT INTO `jipu_area` VALUES ('371581', '临清市', '371500', '9');
INSERT INTO `jipu_area` VALUES ('371600', '滨州市', '370000', '16');
INSERT INTO `jipu_area` VALUES ('371601', '市辖区', '371600', '1');
INSERT INTO `jipu_area` VALUES ('371602', '滨城区', '371600', '2');
INSERT INTO `jipu_area` VALUES ('371621', '惠民县', '371600', '3');
INSERT INTO `jipu_area` VALUES ('371622', '阳信县', '371600', '4');
INSERT INTO `jipu_area` VALUES ('371623', '无棣县', '371600', '5');
INSERT INTO `jipu_area` VALUES ('371624', '沾化县', '371600', '6');
INSERT INTO `jipu_area` VALUES ('371625', '博兴县', '371600', '7');
INSERT INTO `jipu_area` VALUES ('371626', '邹平县', '371600', '8');
INSERT INTO `jipu_area` VALUES ('371700', '菏泽市', '370000', '17');
INSERT INTO `jipu_area` VALUES ('371701', '市辖区', '371700', '1');
INSERT INTO `jipu_area` VALUES ('371702', '牡丹区', '371700', '2');
INSERT INTO `jipu_area` VALUES ('371721', '曹　县', '371700', '3');
INSERT INTO `jipu_area` VALUES ('371722', '单　县', '371700', '4');
INSERT INTO `jipu_area` VALUES ('371723', '成武县', '371700', '5');
INSERT INTO `jipu_area` VALUES ('371724', '巨野县', '371700', '6');
INSERT INTO `jipu_area` VALUES ('371725', '郓城县', '371700', '7');
INSERT INTO `jipu_area` VALUES ('371726', '鄄城县', '371700', '8');
INSERT INTO `jipu_area` VALUES ('371727', '定陶县', '371700', '9');
INSERT INTO `jipu_area` VALUES ('371728', '东明县', '371700', '10');
INSERT INTO `jipu_area` VALUES ('410000', '河南省', '0', '16');
INSERT INTO `jipu_area` VALUES ('410100', '郑州市', '410000', '1');
INSERT INTO `jipu_area` VALUES ('410101', '市辖区', '410100', '1');
INSERT INTO `jipu_area` VALUES ('410102', '中原区', '410100', '2');
INSERT INTO `jipu_area` VALUES ('410103', '二七区', '410100', '3');
INSERT INTO `jipu_area` VALUES ('410104', '管城回族区', '410100', '4');
INSERT INTO `jipu_area` VALUES ('410105', '金水区', '410100', '5');
INSERT INTO `jipu_area` VALUES ('410106', '上街区', '410100', '6');
INSERT INTO `jipu_area` VALUES ('410108', '邙山区', '410100', '7');
INSERT INTO `jipu_area` VALUES ('410122', '中牟县', '410100', '8');
INSERT INTO `jipu_area` VALUES ('410181', '巩义市', '410100', '9');
INSERT INTO `jipu_area` VALUES ('410182', '荥阳市', '410100', '10');
INSERT INTO `jipu_area` VALUES ('410183', '新密市', '410100', '11');
INSERT INTO `jipu_area` VALUES ('410184', '新郑市', '410100', '12');
INSERT INTO `jipu_area` VALUES ('410185', '登封市', '410100', '13');
INSERT INTO `jipu_area` VALUES ('410200', '开封市', '410000', '2');
INSERT INTO `jipu_area` VALUES ('410201', '市辖区', '410200', '1');
INSERT INTO `jipu_area` VALUES ('410202', '龙亭区', '410200', '2');
INSERT INTO `jipu_area` VALUES ('410203', '顺河回族区', '410200', '3');
INSERT INTO `jipu_area` VALUES ('410204', '鼓楼区', '410200', '4');
INSERT INTO `jipu_area` VALUES ('410205', '南关区', '410200', '5');
INSERT INTO `jipu_area` VALUES ('410211', '郊　区', '410200', '6');
INSERT INTO `jipu_area` VALUES ('410221', '杞　县', '410200', '7');
INSERT INTO `jipu_area` VALUES ('410222', '通许县', '410200', '8');
INSERT INTO `jipu_area` VALUES ('410223', '尉氏县', '410200', '9');
INSERT INTO `jipu_area` VALUES ('410224', '开封县', '410200', '10');
INSERT INTO `jipu_area` VALUES ('410225', '兰考县', '410200', '11');
INSERT INTO `jipu_area` VALUES ('410300', '洛阳市', '410000', '3');
INSERT INTO `jipu_area` VALUES ('410301', '市辖区', '410300', '1');
INSERT INTO `jipu_area` VALUES ('410302', '老城区', '410300', '2');
INSERT INTO `jipu_area` VALUES ('410303', '西工区', '410300', '3');
INSERT INTO `jipu_area` VALUES ('410304', '廛河回族区', '410300', '4');
INSERT INTO `jipu_area` VALUES ('410305', '涧西区', '410300', '5');
INSERT INTO `jipu_area` VALUES ('410306', '吉利区', '410300', '6');
INSERT INTO `jipu_area` VALUES ('410307', '洛龙区', '410300', '7');
INSERT INTO `jipu_area` VALUES ('410322', '孟津县', '410300', '8');
INSERT INTO `jipu_area` VALUES ('410323', '新安县', '410300', '9');
INSERT INTO `jipu_area` VALUES ('410324', '栾川县', '410300', '10');
INSERT INTO `jipu_area` VALUES ('410325', '嵩　县', '410300', '11');
INSERT INTO `jipu_area` VALUES ('410326', '汝阳县', '410300', '12');
INSERT INTO `jipu_area` VALUES ('410327', '宜阳县', '410300', '13');
INSERT INTO `jipu_area` VALUES ('410328', '洛宁县', '410300', '14');
INSERT INTO `jipu_area` VALUES ('410329', '伊川县', '410300', '15');
INSERT INTO `jipu_area` VALUES ('410381', '偃师市', '410300', '16');
INSERT INTO `jipu_area` VALUES ('410400', '平顶山市', '410000', '4');
INSERT INTO `jipu_area` VALUES ('410401', '市辖区', '410400', '1');
INSERT INTO `jipu_area` VALUES ('410402', '新华区', '410400', '2');
INSERT INTO `jipu_area` VALUES ('410403', '卫东区', '410400', '3');
INSERT INTO `jipu_area` VALUES ('410404', '石龙区', '410400', '4');
INSERT INTO `jipu_area` VALUES ('410411', '湛河区', '410400', '5');
INSERT INTO `jipu_area` VALUES ('410421', '宝丰县', '410400', '6');
INSERT INTO `jipu_area` VALUES ('410422', '叶　县', '410400', '7');
INSERT INTO `jipu_area` VALUES ('410423', '鲁山县', '410400', '8');
INSERT INTO `jipu_area` VALUES ('410425', '郏　县', '410400', '9');
INSERT INTO `jipu_area` VALUES ('410481', '舞钢市', '410400', '10');
INSERT INTO `jipu_area` VALUES ('410482', '汝州市', '410400', '11');
INSERT INTO `jipu_area` VALUES ('410500', '安阳市', '410000', '5');
INSERT INTO `jipu_area` VALUES ('410501', '市辖区', '410500', '1');
INSERT INTO `jipu_area` VALUES ('410502', '文峰区', '410500', '2');
INSERT INTO `jipu_area` VALUES ('410503', '北关区', '410500', '3');
INSERT INTO `jipu_area` VALUES ('410505', '殷都区', '410500', '4');
INSERT INTO `jipu_area` VALUES ('410506', '龙安区', '410500', '5');
INSERT INTO `jipu_area` VALUES ('410522', '安阳县', '410500', '6');
INSERT INTO `jipu_area` VALUES ('410523', '汤阴县', '410500', '7');
INSERT INTO `jipu_area` VALUES ('410526', '滑　县', '410500', '8');
INSERT INTO `jipu_area` VALUES ('410527', '内黄县', '410500', '9');
INSERT INTO `jipu_area` VALUES ('410581', '林州市', '410500', '10');
INSERT INTO `jipu_area` VALUES ('410600', '鹤壁市', '410000', '6');
INSERT INTO `jipu_area` VALUES ('410601', '市辖区', '410600', '1');
INSERT INTO `jipu_area` VALUES ('410602', '鹤山区', '410600', '2');
INSERT INTO `jipu_area` VALUES ('410603', '山城区', '410600', '3');
INSERT INTO `jipu_area` VALUES ('410611', '淇滨区', '410600', '4');
INSERT INTO `jipu_area` VALUES ('410621', '浚　县', '410600', '5');
INSERT INTO `jipu_area` VALUES ('410622', '淇　县', '410600', '6');
INSERT INTO `jipu_area` VALUES ('410700', '新乡市', '410000', '7');
INSERT INTO `jipu_area` VALUES ('410701', '市辖区', '410700', '1');
INSERT INTO `jipu_area` VALUES ('410702', '红旗区', '410700', '2');
INSERT INTO `jipu_area` VALUES ('410703', '卫滨区', '410700', '3');
INSERT INTO `jipu_area` VALUES ('410704', '凤泉区', '410700', '4');
INSERT INTO `jipu_area` VALUES ('410711', '牧野区', '410700', '5');
INSERT INTO `jipu_area` VALUES ('410721', '新乡县', '410700', '6');
INSERT INTO `jipu_area` VALUES ('410724', '获嘉县', '410700', '7');
INSERT INTO `jipu_area` VALUES ('410725', '原阳县', '410700', '8');
INSERT INTO `jipu_area` VALUES ('410726', '延津县', '410700', '9');
INSERT INTO `jipu_area` VALUES ('410727', '封丘县', '410700', '10');
INSERT INTO `jipu_area` VALUES ('410728', '长垣县', '410700', '11');
INSERT INTO `jipu_area` VALUES ('410781', '卫辉市', '410700', '12');
INSERT INTO `jipu_area` VALUES ('410782', '辉县市', '410700', '13');
INSERT INTO `jipu_area` VALUES ('410800', '焦作市', '410000', '8');
INSERT INTO `jipu_area` VALUES ('410801', '市辖区', '410800', '1');
INSERT INTO `jipu_area` VALUES ('410802', '解放区', '410800', '2');
INSERT INTO `jipu_area` VALUES ('410803', '中站区', '410800', '3');
INSERT INTO `jipu_area` VALUES ('410804', '马村区', '410800', '4');
INSERT INTO `jipu_area` VALUES ('410811', '山阳区', '410800', '5');
INSERT INTO `jipu_area` VALUES ('410821', '修武县', '410800', '6');
INSERT INTO `jipu_area` VALUES ('410822', '博爱县', '410800', '7');
INSERT INTO `jipu_area` VALUES ('410823', '武陟县', '410800', '8');
INSERT INTO `jipu_area` VALUES ('410825', '温　县', '410800', '9');
INSERT INTO `jipu_area` VALUES ('410881', '济源市', '410800', '10');
INSERT INTO `jipu_area` VALUES ('410882', '沁阳市', '410800', '11');
INSERT INTO `jipu_area` VALUES ('410883', '孟州市', '410800', '12');
INSERT INTO `jipu_area` VALUES ('410900', '濮阳市', '410000', '9');
INSERT INTO `jipu_area` VALUES ('410901', '市辖区', '410900', '1');
INSERT INTO `jipu_area` VALUES ('410902', '华龙区', '410900', '2');
INSERT INTO `jipu_area` VALUES ('410922', '清丰县', '410900', '3');
INSERT INTO `jipu_area` VALUES ('410923', '南乐县', '410900', '4');
INSERT INTO `jipu_area` VALUES ('410926', '范　县', '410900', '5');
INSERT INTO `jipu_area` VALUES ('410927', '台前县', '410900', '6');
INSERT INTO `jipu_area` VALUES ('410928', '濮阳县', '410900', '7');
INSERT INTO `jipu_area` VALUES ('411000', '许昌市', '410000', '10');
INSERT INTO `jipu_area` VALUES ('411001', '市辖区', '411000', '1');
INSERT INTO `jipu_area` VALUES ('411002', '魏都区', '411000', '2');
INSERT INTO `jipu_area` VALUES ('411023', '许昌县', '411000', '3');
INSERT INTO `jipu_area` VALUES ('411024', '鄢陵县', '411000', '4');
INSERT INTO `jipu_area` VALUES ('411025', '襄城县', '411000', '5');
INSERT INTO `jipu_area` VALUES ('411081', '禹州市', '411000', '6');
INSERT INTO `jipu_area` VALUES ('411082', '长葛市', '411000', '7');
INSERT INTO `jipu_area` VALUES ('411100', '漯河市', '410000', '11');
INSERT INTO `jipu_area` VALUES ('411101', '市辖区', '411100', '1');
INSERT INTO `jipu_area` VALUES ('411102', '源汇区', '411100', '2');
INSERT INTO `jipu_area` VALUES ('411103', '郾城区', '411100', '3');
INSERT INTO `jipu_area` VALUES ('411104', '召陵区', '411100', '4');
INSERT INTO `jipu_area` VALUES ('411121', '舞阳县', '411100', '5');
INSERT INTO `jipu_area` VALUES ('411122', '临颍县', '411100', '6');
INSERT INTO `jipu_area` VALUES ('411200', '三门峡市', '410000', '12');
INSERT INTO `jipu_area` VALUES ('411201', '市辖区', '411200', '1');
INSERT INTO `jipu_area` VALUES ('411202', '湖滨区', '411200', '2');
INSERT INTO `jipu_area` VALUES ('411221', '渑池县', '411200', '3');
INSERT INTO `jipu_area` VALUES ('411222', '陕　县', '411200', '4');
INSERT INTO `jipu_area` VALUES ('411224', '卢氏县', '411200', '5');
INSERT INTO `jipu_area` VALUES ('411281', '义马市', '411200', '6');
INSERT INTO `jipu_area` VALUES ('411282', '灵宝市', '411200', '7');
INSERT INTO `jipu_area` VALUES ('411300', '南阳市', '410000', '13');
INSERT INTO `jipu_area` VALUES ('411301', '市辖区', '411300', '1');
INSERT INTO `jipu_area` VALUES ('411302', '宛城区', '411300', '2');
INSERT INTO `jipu_area` VALUES ('411303', '卧龙区', '411300', '3');
INSERT INTO `jipu_area` VALUES ('411321', '南召县', '411300', '4');
INSERT INTO `jipu_area` VALUES ('411322', '方城县', '411300', '5');
INSERT INTO `jipu_area` VALUES ('411323', '西峡县', '411300', '6');
INSERT INTO `jipu_area` VALUES ('411324', '镇平县', '411300', '7');
INSERT INTO `jipu_area` VALUES ('411325', '内乡县', '411300', '8');
INSERT INTO `jipu_area` VALUES ('411326', '淅川县', '411300', '9');
INSERT INTO `jipu_area` VALUES ('411327', '社旗县', '411300', '10');
INSERT INTO `jipu_area` VALUES ('411328', '唐河县', '411300', '11');
INSERT INTO `jipu_area` VALUES ('411329', '新野县', '411300', '12');
INSERT INTO `jipu_area` VALUES ('411330', '桐柏县', '411300', '13');
INSERT INTO `jipu_area` VALUES ('411381', '邓州市', '411300', '14');
INSERT INTO `jipu_area` VALUES ('411400', '商丘市', '410000', '14');
INSERT INTO `jipu_area` VALUES ('411401', '市辖区', '411400', '1');
INSERT INTO `jipu_area` VALUES ('411402', '梁园区', '411400', '2');
INSERT INTO `jipu_area` VALUES ('411403', '睢阳区', '411400', '3');
INSERT INTO `jipu_area` VALUES ('411421', '民权县', '411400', '4');
INSERT INTO `jipu_area` VALUES ('411422', '睢　县', '411400', '5');
INSERT INTO `jipu_area` VALUES ('411423', '宁陵县', '411400', '6');
INSERT INTO `jipu_area` VALUES ('411424', '柘城县', '411400', '7');
INSERT INTO `jipu_area` VALUES ('411425', '虞城县', '411400', '8');
INSERT INTO `jipu_area` VALUES ('411426', '夏邑县', '411400', '9');
INSERT INTO `jipu_area` VALUES ('411481', '永城市', '411400', '10');
INSERT INTO `jipu_area` VALUES ('411500', '信阳市', '410000', '15');
INSERT INTO `jipu_area` VALUES ('411501', '市辖区', '411500', '1');
INSERT INTO `jipu_area` VALUES ('411502', '师河区', '411500', '2');
INSERT INTO `jipu_area` VALUES ('411503', '平桥区', '411500', '3');
INSERT INTO `jipu_area` VALUES ('411521', '罗山县', '411500', '4');
INSERT INTO `jipu_area` VALUES ('411522', '光山县', '411500', '5');
INSERT INTO `jipu_area` VALUES ('411523', '新　县', '411500', '6');
INSERT INTO `jipu_area` VALUES ('411524', '商城县', '411500', '7');
INSERT INTO `jipu_area` VALUES ('411525', '固始县', '411500', '8');
INSERT INTO `jipu_area` VALUES ('411526', '潢川县', '411500', '9');
INSERT INTO `jipu_area` VALUES ('411527', '淮滨县', '411500', '10');
INSERT INTO `jipu_area` VALUES ('411528', '息　县', '411500', '11');
INSERT INTO `jipu_area` VALUES ('411600', '周口市', '410000', '16');
INSERT INTO `jipu_area` VALUES ('411601', '市辖区', '411600', '1');
INSERT INTO `jipu_area` VALUES ('411602', '川汇区', '411600', '2');
INSERT INTO `jipu_area` VALUES ('411621', '扶沟县', '411600', '3');
INSERT INTO `jipu_area` VALUES ('411622', '西华县', '411600', '4');
INSERT INTO `jipu_area` VALUES ('411623', '商水县', '411600', '5');
INSERT INTO `jipu_area` VALUES ('411624', '沈丘县', '411600', '6');
INSERT INTO `jipu_area` VALUES ('411625', '郸城县', '411600', '7');
INSERT INTO `jipu_area` VALUES ('411626', '淮阳县', '411600', '8');
INSERT INTO `jipu_area` VALUES ('411627', '太康县', '411600', '9');
INSERT INTO `jipu_area` VALUES ('411628', '鹿邑县', '411600', '10');
INSERT INTO `jipu_area` VALUES ('411681', '项城市', '411600', '11');
INSERT INTO `jipu_area` VALUES ('411700', '驻马店市', '410000', '17');
INSERT INTO `jipu_area` VALUES ('411701', '市辖区', '411700', '1');
INSERT INTO `jipu_area` VALUES ('411702', '驿城区', '411700', '2');
INSERT INTO `jipu_area` VALUES ('411721', '西平县', '411700', '3');
INSERT INTO `jipu_area` VALUES ('411722', '上蔡县', '411700', '4');
INSERT INTO `jipu_area` VALUES ('411723', '平舆县', '411700', '5');
INSERT INTO `jipu_area` VALUES ('411724', '正阳县', '411700', '6');
INSERT INTO `jipu_area` VALUES ('411725', '确山县', '411700', '7');
INSERT INTO `jipu_area` VALUES ('411726', '泌阳县', '411700', '8');
INSERT INTO `jipu_area` VALUES ('411727', '汝南县', '411700', '9');
INSERT INTO `jipu_area` VALUES ('411728', '遂平县', '411700', '10');
INSERT INTO `jipu_area` VALUES ('411729', '新蔡县', '411700', '11');
INSERT INTO `jipu_area` VALUES ('420000', '湖北省', '0', '17');
INSERT INTO `jipu_area` VALUES ('420100', '武汉市', '420000', '1');
INSERT INTO `jipu_area` VALUES ('420101', '市辖区', '420100', '1');
INSERT INTO `jipu_area` VALUES ('420102', '江岸区', '420100', '2');
INSERT INTO `jipu_area` VALUES ('420103', '江汉区', '420100', '3');
INSERT INTO `jipu_area` VALUES ('420104', '乔口区', '420100', '4');
INSERT INTO `jipu_area` VALUES ('420105', '汉阳区', '420100', '5');
INSERT INTO `jipu_area` VALUES ('420106', '武昌区', '420100', '6');
INSERT INTO `jipu_area` VALUES ('420107', '青山区', '420100', '7');
INSERT INTO `jipu_area` VALUES ('420111', '洪山区', '420100', '8');
INSERT INTO `jipu_area` VALUES ('420112', '东西湖区', '420100', '9');
INSERT INTO `jipu_area` VALUES ('420113', '汉南区', '420100', '10');
INSERT INTO `jipu_area` VALUES ('420114', '蔡甸区', '420100', '11');
INSERT INTO `jipu_area` VALUES ('420115', '江夏区', '420100', '12');
INSERT INTO `jipu_area` VALUES ('420116', '黄陂区', '420100', '13');
INSERT INTO `jipu_area` VALUES ('420117', '新洲区', '420100', '14');
INSERT INTO `jipu_area` VALUES ('420200', '黄石市', '420000', '2');
INSERT INTO `jipu_area` VALUES ('420201', '市辖区', '420200', '1');
INSERT INTO `jipu_area` VALUES ('420202', '黄石港区', '420200', '2');
INSERT INTO `jipu_area` VALUES ('420203', '西塞山区', '420200', '3');
INSERT INTO `jipu_area` VALUES ('420204', '下陆区', '420200', '4');
INSERT INTO `jipu_area` VALUES ('420205', '铁山区', '420200', '5');
INSERT INTO `jipu_area` VALUES ('420222', '阳新县', '420200', '6');
INSERT INTO `jipu_area` VALUES ('420281', '大冶市', '420200', '7');
INSERT INTO `jipu_area` VALUES ('420300', '十堰市', '420000', '3');
INSERT INTO `jipu_area` VALUES ('420301', '市辖区', '420300', '1');
INSERT INTO `jipu_area` VALUES ('420302', '茅箭区', '420300', '2');
INSERT INTO `jipu_area` VALUES ('420303', '张湾区', '420300', '3');
INSERT INTO `jipu_area` VALUES ('420321', '郧　县', '420300', '4');
INSERT INTO `jipu_area` VALUES ('420322', '郧西县', '420300', '5');
INSERT INTO `jipu_area` VALUES ('420323', '竹山县', '420300', '6');
INSERT INTO `jipu_area` VALUES ('420324', '竹溪县', '420300', '7');
INSERT INTO `jipu_area` VALUES ('420325', '房　县', '420300', '8');
INSERT INTO `jipu_area` VALUES ('420381', '丹江口市', '420300', '9');
INSERT INTO `jipu_area` VALUES ('420500', '宜昌市', '420000', '4');
INSERT INTO `jipu_area` VALUES ('420501', '市辖区', '420500', '1');
INSERT INTO `jipu_area` VALUES ('420502', '西陵区', '420500', '2');
INSERT INTO `jipu_area` VALUES ('420503', '伍家岗区', '420500', '3');
INSERT INTO `jipu_area` VALUES ('420504', '点军区', '420500', '4');
INSERT INTO `jipu_area` VALUES ('420505', '猇亭区', '420500', '5');
INSERT INTO `jipu_area` VALUES ('420506', '夷陵区', '420500', '6');
INSERT INTO `jipu_area` VALUES ('420525', '远安县', '420500', '7');
INSERT INTO `jipu_area` VALUES ('420526', '兴山县', '420500', '8');
INSERT INTO `jipu_area` VALUES ('420527', '秭归县', '420500', '9');
INSERT INTO `jipu_area` VALUES ('420528', '长阳土家族自治县', '420500', '10');
INSERT INTO `jipu_area` VALUES ('420529', '五峰土家族自治县', '420500', '11');
INSERT INTO `jipu_area` VALUES ('420581', '宜都市', '420500', '12');
INSERT INTO `jipu_area` VALUES ('420582', '当阳市', '420500', '13');
INSERT INTO `jipu_area` VALUES ('420583', '枝江市', '420500', '14');
INSERT INTO `jipu_area` VALUES ('420600', '襄樊市', '420000', '5');
INSERT INTO `jipu_area` VALUES ('420601', '市辖区', '420600', '1');
INSERT INTO `jipu_area` VALUES ('420602', '襄城区', '420600', '2');
INSERT INTO `jipu_area` VALUES ('420606', '樊城区', '420600', '3');
INSERT INTO `jipu_area` VALUES ('420607', '襄阳区', '420600', '4');
INSERT INTO `jipu_area` VALUES ('420624', '南漳县', '420600', '5');
INSERT INTO `jipu_area` VALUES ('420625', '谷城县', '420600', '6');
INSERT INTO `jipu_area` VALUES ('420626', '保康县', '420600', '7');
INSERT INTO `jipu_area` VALUES ('420682', '老河口市', '420600', '8');
INSERT INTO `jipu_area` VALUES ('420683', '枣阳市', '420600', '9');
INSERT INTO `jipu_area` VALUES ('420684', '宜城市', '420600', '10');
INSERT INTO `jipu_area` VALUES ('420700', '鄂州市', '420000', '6');
INSERT INTO `jipu_area` VALUES ('420701', '市辖区', '420700', '1');
INSERT INTO `jipu_area` VALUES ('420702', '梁子湖区', '420700', '2');
INSERT INTO `jipu_area` VALUES ('420703', '华容区', '420700', '3');
INSERT INTO `jipu_area` VALUES ('420704', '鄂城区', '420700', '4');
INSERT INTO `jipu_area` VALUES ('420800', '荆门市', '420000', '7');
INSERT INTO `jipu_area` VALUES ('420801', '市辖区', '420800', '1');
INSERT INTO `jipu_area` VALUES ('420802', '东宝区', '420800', '2');
INSERT INTO `jipu_area` VALUES ('420804', '掇刀区', '420800', '3');
INSERT INTO `jipu_area` VALUES ('420821', '京山县', '420800', '4');
INSERT INTO `jipu_area` VALUES ('420822', '沙洋县', '420800', '5');
INSERT INTO `jipu_area` VALUES ('420881', '钟祥市', '420800', '6');
INSERT INTO `jipu_area` VALUES ('420900', '孝感市', '420000', '8');
INSERT INTO `jipu_area` VALUES ('420901', '市辖区', '420900', '1');
INSERT INTO `jipu_area` VALUES ('420902', '孝南区', '420900', '2');
INSERT INTO `jipu_area` VALUES ('420921', '孝昌县', '420900', '3');
INSERT INTO `jipu_area` VALUES ('420922', '大悟县', '420900', '4');
INSERT INTO `jipu_area` VALUES ('420923', '云梦县', '420900', '5');
INSERT INTO `jipu_area` VALUES ('420981', '应城市', '420900', '6');
INSERT INTO `jipu_area` VALUES ('420982', '安陆市', '420900', '7');
INSERT INTO `jipu_area` VALUES ('420984', '汉川市', '420900', '8');
INSERT INTO `jipu_area` VALUES ('421000', '荆州市', '420000', '9');
INSERT INTO `jipu_area` VALUES ('421001', '市辖区', '421000', '1');
INSERT INTO `jipu_area` VALUES ('421002', '沙市区', '421000', '2');
INSERT INTO `jipu_area` VALUES ('421003', '荆州区', '421000', '3');
INSERT INTO `jipu_area` VALUES ('421022', '公安县', '421000', '4');
INSERT INTO `jipu_area` VALUES ('421023', '监利县', '421000', '5');
INSERT INTO `jipu_area` VALUES ('421024', '江陵县', '421000', '6');
INSERT INTO `jipu_area` VALUES ('421081', '石首市', '421000', '7');
INSERT INTO `jipu_area` VALUES ('421083', '洪湖市', '421000', '8');
INSERT INTO `jipu_area` VALUES ('421087', '松滋市', '421000', '9');
INSERT INTO `jipu_area` VALUES ('421100', '黄冈市', '420000', '10');
INSERT INTO `jipu_area` VALUES ('421101', '市辖区', '421100', '1');
INSERT INTO `jipu_area` VALUES ('421102', '黄州区', '421100', '2');
INSERT INTO `jipu_area` VALUES ('421121', '团风县', '421100', '3');
INSERT INTO `jipu_area` VALUES ('421122', '红安县', '421100', '4');
INSERT INTO `jipu_area` VALUES ('421123', '罗田县', '421100', '5');
INSERT INTO `jipu_area` VALUES ('421124', '英山县', '421100', '6');
INSERT INTO `jipu_area` VALUES ('421125', '浠水县', '421100', '7');
INSERT INTO `jipu_area` VALUES ('421126', '蕲春县', '421100', '8');
INSERT INTO `jipu_area` VALUES ('421127', '黄梅县', '421100', '9');
INSERT INTO `jipu_area` VALUES ('421181', '麻城市', '421100', '10');
INSERT INTO `jipu_area` VALUES ('421182', '武穴市', '421100', '11');
INSERT INTO `jipu_area` VALUES ('421200', '咸宁市', '420000', '11');
INSERT INTO `jipu_area` VALUES ('421201', '市辖区', '421200', '1');
INSERT INTO `jipu_area` VALUES ('421202', '咸安区', '421200', '2');
INSERT INTO `jipu_area` VALUES ('421221', '嘉鱼县', '421200', '3');
INSERT INTO `jipu_area` VALUES ('421222', '通城县', '421200', '4');
INSERT INTO `jipu_area` VALUES ('421223', '崇阳县', '421200', '5');
INSERT INTO `jipu_area` VALUES ('421224', '通山县', '421200', '6');
INSERT INTO `jipu_area` VALUES ('421281', '赤壁市', '421200', '7');
INSERT INTO `jipu_area` VALUES ('421300', '随州市', '420000', '12');
INSERT INTO `jipu_area` VALUES ('421301', '市辖区', '421300', '1');
INSERT INTO `jipu_area` VALUES ('421302', '曾都区', '421300', '2');
INSERT INTO `jipu_area` VALUES ('421381', '广水市', '421300', '3');
INSERT INTO `jipu_area` VALUES ('422800', '恩施土家族苗族自治州', '420000', '13');
INSERT INTO `jipu_area` VALUES ('422801', '恩施市', '422800', '1');
INSERT INTO `jipu_area` VALUES ('422802', '利川市', '422800', '2');
INSERT INTO `jipu_area` VALUES ('422822', '建始县', '422800', '3');
INSERT INTO `jipu_area` VALUES ('422823', '巴东县', '422800', '4');
INSERT INTO `jipu_area` VALUES ('422825', '宣恩县', '422800', '5');
INSERT INTO `jipu_area` VALUES ('422826', '咸丰县', '422800', '6');
INSERT INTO `jipu_area` VALUES ('422827', '来凤县', '422800', '7');
INSERT INTO `jipu_area` VALUES ('422828', '鹤峰县', '422800', '8');
INSERT INTO `jipu_area` VALUES ('429000', '省直辖行政单位', '420000', '14');
INSERT INTO `jipu_area` VALUES ('429004', '仙桃市', '429000', '1');
INSERT INTO `jipu_area` VALUES ('429005', '潜江市', '429000', '2');
INSERT INTO `jipu_area` VALUES ('429006', '天门市', '429000', '3');
INSERT INTO `jipu_area` VALUES ('429021', '神农架林区', '429000', '4');
INSERT INTO `jipu_area` VALUES ('430000', '湖南省', '0', '18');
INSERT INTO `jipu_area` VALUES ('430100', '长沙市', '430000', '1');
INSERT INTO `jipu_area` VALUES ('430101', '市辖区', '430100', '1');
INSERT INTO `jipu_area` VALUES ('430102', '芙蓉区', '430100', '2');
INSERT INTO `jipu_area` VALUES ('430103', '天心区', '430100', '3');
INSERT INTO `jipu_area` VALUES ('430104', '岳麓区', '430100', '4');
INSERT INTO `jipu_area` VALUES ('430105', '开福区', '430100', '5');
INSERT INTO `jipu_area` VALUES ('430111', '雨花区', '430100', '6');
INSERT INTO `jipu_area` VALUES ('430121', '长沙县', '430100', '7');
INSERT INTO `jipu_area` VALUES ('430122', '望城县', '430100', '8');
INSERT INTO `jipu_area` VALUES ('430124', '宁乡县', '430100', '9');
INSERT INTO `jipu_area` VALUES ('430181', '浏阳市', '430100', '10');
INSERT INTO `jipu_area` VALUES ('430200', '株洲市', '430000', '2');
INSERT INTO `jipu_area` VALUES ('430201', '市辖区', '430200', '1');
INSERT INTO `jipu_area` VALUES ('430202', '荷塘区', '430200', '2');
INSERT INTO `jipu_area` VALUES ('430203', '芦淞区', '430200', '3');
INSERT INTO `jipu_area` VALUES ('430204', '石峰区', '430200', '4');
INSERT INTO `jipu_area` VALUES ('430211', '天元区', '430200', '5');
INSERT INTO `jipu_area` VALUES ('430221', '株洲县', '430200', '6');
INSERT INTO `jipu_area` VALUES ('430223', '攸　县', '430200', '7');
INSERT INTO `jipu_area` VALUES ('430224', '茶陵县', '430200', '8');
INSERT INTO `jipu_area` VALUES ('430225', '炎陵县', '430200', '9');
INSERT INTO `jipu_area` VALUES ('430281', '醴陵市', '430200', '10');
INSERT INTO `jipu_area` VALUES ('430300', '湘潭市', '430000', '3');
INSERT INTO `jipu_area` VALUES ('430301', '市辖区', '430300', '1');
INSERT INTO `jipu_area` VALUES ('430302', '雨湖区', '430300', '2');
INSERT INTO `jipu_area` VALUES ('430304', '岳塘区', '430300', '3');
INSERT INTO `jipu_area` VALUES ('430321', '湘潭县', '430300', '4');
INSERT INTO `jipu_area` VALUES ('430381', '湘乡市', '430300', '5');
INSERT INTO `jipu_area` VALUES ('430382', '韶山市', '430300', '6');
INSERT INTO `jipu_area` VALUES ('430400', '衡阳市', '430000', '4');
INSERT INTO `jipu_area` VALUES ('430401', '市辖区', '430400', '1');
INSERT INTO `jipu_area` VALUES ('430405', '珠晖区', '430400', '2');
INSERT INTO `jipu_area` VALUES ('430406', '雁峰区', '430400', '3');
INSERT INTO `jipu_area` VALUES ('430407', '石鼓区', '430400', '4');
INSERT INTO `jipu_area` VALUES ('430408', '蒸湘区', '430400', '5');
INSERT INTO `jipu_area` VALUES ('430412', '南岳区', '430400', '6');
INSERT INTO `jipu_area` VALUES ('430421', '衡阳县', '430400', '7');
INSERT INTO `jipu_area` VALUES ('430422', '衡南县', '430400', '8');
INSERT INTO `jipu_area` VALUES ('430423', '衡山县', '430400', '9');
INSERT INTO `jipu_area` VALUES ('430424', '衡东县', '430400', '10');
INSERT INTO `jipu_area` VALUES ('430426', '祁东县', '430400', '11');
INSERT INTO `jipu_area` VALUES ('430481', '耒阳市', '430400', '12');
INSERT INTO `jipu_area` VALUES ('430482', '常宁市', '430400', '13');
INSERT INTO `jipu_area` VALUES ('430500', '邵阳市', '430000', '5');
INSERT INTO `jipu_area` VALUES ('430501', '市辖区', '430500', '1');
INSERT INTO `jipu_area` VALUES ('430502', '双清区', '430500', '2');
INSERT INTO `jipu_area` VALUES ('430503', '大祥区', '430500', '3');
INSERT INTO `jipu_area` VALUES ('430511', '北塔区', '430500', '4');
INSERT INTO `jipu_area` VALUES ('430521', '邵东县', '430500', '5');
INSERT INTO `jipu_area` VALUES ('430522', '新邵县', '430500', '6');
INSERT INTO `jipu_area` VALUES ('430523', '邵阳县', '430500', '7');
INSERT INTO `jipu_area` VALUES ('430524', '隆回县', '430500', '8');
INSERT INTO `jipu_area` VALUES ('430525', '洞口县', '430500', '9');
INSERT INTO `jipu_area` VALUES ('430527', '绥宁县', '430500', '10');
INSERT INTO `jipu_area` VALUES ('430528', '新宁县', '430500', '11');
INSERT INTO `jipu_area` VALUES ('430529', '城步苗族自治县', '430500', '12');
INSERT INTO `jipu_area` VALUES ('430581', '武冈市', '430500', '13');
INSERT INTO `jipu_area` VALUES ('430600', '岳阳市', '430000', '6');
INSERT INTO `jipu_area` VALUES ('430601', '市辖区', '430600', '1');
INSERT INTO `jipu_area` VALUES ('430602', '岳阳楼区', '430600', '2');
INSERT INTO `jipu_area` VALUES ('430603', '云溪区', '430600', '3');
INSERT INTO `jipu_area` VALUES ('430611', '君山区', '430600', '4');
INSERT INTO `jipu_area` VALUES ('430621', '岳阳县', '430600', '5');
INSERT INTO `jipu_area` VALUES ('430623', '华容县', '430600', '6');
INSERT INTO `jipu_area` VALUES ('430624', '湘阴县', '430600', '7');
INSERT INTO `jipu_area` VALUES ('430626', '平江县', '430600', '8');
INSERT INTO `jipu_area` VALUES ('430681', '汨罗市', '430600', '9');
INSERT INTO `jipu_area` VALUES ('430682', '临湘市', '430600', '10');
INSERT INTO `jipu_area` VALUES ('430700', '常德市', '430000', '7');
INSERT INTO `jipu_area` VALUES ('430701', '市辖区', '430700', '1');
INSERT INTO `jipu_area` VALUES ('430702', '武陵区', '430700', '2');
INSERT INTO `jipu_area` VALUES ('430703', '鼎城区', '430700', '3');
INSERT INTO `jipu_area` VALUES ('430721', '安乡县', '430700', '4');
INSERT INTO `jipu_area` VALUES ('430722', '汉寿县', '430700', '5');
INSERT INTO `jipu_area` VALUES ('430723', '澧　县', '430700', '6');
INSERT INTO `jipu_area` VALUES ('430724', '临澧县', '430700', '7');
INSERT INTO `jipu_area` VALUES ('430725', '桃源县', '430700', '8');
INSERT INTO `jipu_area` VALUES ('430726', '石门县', '430700', '9');
INSERT INTO `jipu_area` VALUES ('430781', '津市市', '430700', '10');
INSERT INTO `jipu_area` VALUES ('430800', '张家界市', '430000', '8');
INSERT INTO `jipu_area` VALUES ('430801', '市辖区', '430800', '1');
INSERT INTO `jipu_area` VALUES ('430802', '永定区', '430800', '2');
INSERT INTO `jipu_area` VALUES ('430811', '武陵源区', '430800', '3');
INSERT INTO `jipu_area` VALUES ('430821', '慈利县', '430800', '4');
INSERT INTO `jipu_area` VALUES ('430822', '桑植县', '430800', '5');
INSERT INTO `jipu_area` VALUES ('430900', '益阳市', '430000', '9');
INSERT INTO `jipu_area` VALUES ('430901', '市辖区', '430900', '1');
INSERT INTO `jipu_area` VALUES ('430902', '资阳区', '430900', '2');
INSERT INTO `jipu_area` VALUES ('430903', '赫山区', '430900', '3');
INSERT INTO `jipu_area` VALUES ('430921', '南　县', '430900', '4');
INSERT INTO `jipu_area` VALUES ('430922', '桃江县', '430900', '5');
INSERT INTO `jipu_area` VALUES ('430923', '安化县', '430900', '6');
INSERT INTO `jipu_area` VALUES ('430981', '沅江市', '430900', '7');
INSERT INTO `jipu_area` VALUES ('431000', '郴州市', '430000', '10');
INSERT INTO `jipu_area` VALUES ('431001', '市辖区', '431000', '1');
INSERT INTO `jipu_area` VALUES ('431002', '北湖区', '431000', '2');
INSERT INTO `jipu_area` VALUES ('431003', '苏仙区', '431000', '3');
INSERT INTO `jipu_area` VALUES ('431021', '桂阳县', '431000', '4');
INSERT INTO `jipu_area` VALUES ('431022', '宜章县', '431000', '5');
INSERT INTO `jipu_area` VALUES ('431023', '永兴县', '431000', '6');
INSERT INTO `jipu_area` VALUES ('431024', '嘉禾县', '431000', '7');
INSERT INTO `jipu_area` VALUES ('431025', '临武县', '431000', '8');
INSERT INTO `jipu_area` VALUES ('431026', '汝城县', '431000', '9');
INSERT INTO `jipu_area` VALUES ('431027', '桂东县', '431000', '10');
INSERT INTO `jipu_area` VALUES ('431028', '安仁县', '431000', '11');
INSERT INTO `jipu_area` VALUES ('431081', '资兴市', '431000', '12');
INSERT INTO `jipu_area` VALUES ('431100', '永州市', '430000', '11');
INSERT INTO `jipu_area` VALUES ('431101', '市辖区', '431100', '1');
INSERT INTO `jipu_area` VALUES ('431102', '芝山区', '431100', '2');
INSERT INTO `jipu_area` VALUES ('431103', '冷水滩区', '431100', '3');
INSERT INTO `jipu_area` VALUES ('431121', '祁阳县', '431100', '4');
INSERT INTO `jipu_area` VALUES ('431122', '东安县', '431100', '5');
INSERT INTO `jipu_area` VALUES ('431123', '双牌县', '431100', '6');
INSERT INTO `jipu_area` VALUES ('431124', '道　县', '431100', '7');
INSERT INTO `jipu_area` VALUES ('431125', '江永县', '431100', '8');
INSERT INTO `jipu_area` VALUES ('431126', '宁远县', '431100', '9');
INSERT INTO `jipu_area` VALUES ('431127', '蓝山县', '431100', '10');
INSERT INTO `jipu_area` VALUES ('431128', '新田县', '431100', '11');
INSERT INTO `jipu_area` VALUES ('431129', '江华瑶族自治县', '431100', '12');
INSERT INTO `jipu_area` VALUES ('431200', '怀化市', '430000', '12');
INSERT INTO `jipu_area` VALUES ('431201', '市辖区', '431200', '1');
INSERT INTO `jipu_area` VALUES ('431202', '鹤城区', '431200', '2');
INSERT INTO `jipu_area` VALUES ('431221', '中方县', '431200', '3');
INSERT INTO `jipu_area` VALUES ('431222', '沅陵县', '431200', '4');
INSERT INTO `jipu_area` VALUES ('431223', '辰溪县', '431200', '5');
INSERT INTO `jipu_area` VALUES ('431224', '溆浦县', '431200', '6');
INSERT INTO `jipu_area` VALUES ('431225', '会同县', '431200', '7');
INSERT INTO `jipu_area` VALUES ('431226', '麻阳苗族自治县', '431200', '8');
INSERT INTO `jipu_area` VALUES ('431227', '新晃侗族自治县', '431200', '9');
INSERT INTO `jipu_area` VALUES ('431228', '芷江侗族自治县', '431200', '10');
INSERT INTO `jipu_area` VALUES ('431229', '靖州苗族侗族自治县', '431200', '11');
INSERT INTO `jipu_area` VALUES ('431230', '通道侗族自治县', '431200', '12');
INSERT INTO `jipu_area` VALUES ('431281', '洪江市', '431200', '13');
INSERT INTO `jipu_area` VALUES ('431300', '娄底市', '430000', '13');
INSERT INTO `jipu_area` VALUES ('431301', '市辖区', '431300', '1');
INSERT INTO `jipu_area` VALUES ('431302', '娄星区', '431300', '2');
INSERT INTO `jipu_area` VALUES ('431321', '双峰县', '431300', '3');
INSERT INTO `jipu_area` VALUES ('431322', '新化县', '431300', '4');
INSERT INTO `jipu_area` VALUES ('431381', '冷水江市', '431300', '5');
INSERT INTO `jipu_area` VALUES ('431382', '涟源市', '431300', '6');
INSERT INTO `jipu_area` VALUES ('433100', '湘西土家族苗族自治州', '430000', '14');
INSERT INTO `jipu_area` VALUES ('433101', '吉首市', '433100', '1');
INSERT INTO `jipu_area` VALUES ('433122', '泸溪县', '433100', '2');
INSERT INTO `jipu_area` VALUES ('433123', '凤凰县', '433100', '3');
INSERT INTO `jipu_area` VALUES ('433124', '花垣县', '433100', '4');
INSERT INTO `jipu_area` VALUES ('433125', '保靖县', '433100', '5');
INSERT INTO `jipu_area` VALUES ('433126', '古丈县', '433100', '6');
INSERT INTO `jipu_area` VALUES ('433127', '永顺县', '433100', '7');
INSERT INTO `jipu_area` VALUES ('433130', '龙山县', '433100', '8');
INSERT INTO `jipu_area` VALUES ('440000', '广东省', '0', '19');
INSERT INTO `jipu_area` VALUES ('440100', '广州市', '440000', '1');
INSERT INTO `jipu_area` VALUES ('440101', '市辖区', '440100', '1');
INSERT INTO `jipu_area` VALUES ('440102', '东山区', '440100', '2');
INSERT INTO `jipu_area` VALUES ('440103', '荔湾区', '440100', '3');
INSERT INTO `jipu_area` VALUES ('440104', '越秀区', '440100', '4');
INSERT INTO `jipu_area` VALUES ('440105', '海珠区', '440100', '5');
INSERT INTO `jipu_area` VALUES ('440106', '天河区', '440100', '6');
INSERT INTO `jipu_area` VALUES ('440107', '芳村区', '440100', '7');
INSERT INTO `jipu_area` VALUES ('440111', '白云区', '440100', '8');
INSERT INTO `jipu_area` VALUES ('440112', '黄埔区', '440100', '9');
INSERT INTO `jipu_area` VALUES ('440113', '番禺区', '440100', '10');
INSERT INTO `jipu_area` VALUES ('440114', '花都区', '440100', '11');
INSERT INTO `jipu_area` VALUES ('440183', '增城市', '440100', '12');
INSERT INTO `jipu_area` VALUES ('440184', '从化市', '440100', '13');
INSERT INTO `jipu_area` VALUES ('440200', '韶关市', '440000', '2');
INSERT INTO `jipu_area` VALUES ('440201', '市辖区', '440200', '1');
INSERT INTO `jipu_area` VALUES ('440203', '武江区', '440200', '2');
INSERT INTO `jipu_area` VALUES ('440204', '浈江区', '440200', '3');
INSERT INTO `jipu_area` VALUES ('440205', '曲江区', '440200', '4');
INSERT INTO `jipu_area` VALUES ('440222', '始兴县', '440200', '5');
INSERT INTO `jipu_area` VALUES ('440224', '仁化县', '440200', '6');
INSERT INTO `jipu_area` VALUES ('440229', '翁源县', '440200', '7');
INSERT INTO `jipu_area` VALUES ('440232', '乳源瑶族自治县', '440200', '8');
INSERT INTO `jipu_area` VALUES ('440233', '新丰县', '440200', '9');
INSERT INTO `jipu_area` VALUES ('440281', '乐昌市', '440200', '10');
INSERT INTO `jipu_area` VALUES ('440282', '南雄市', '440200', '11');
INSERT INTO `jipu_area` VALUES ('440300', '深圳市', '440000', '3');
INSERT INTO `jipu_area` VALUES ('440301', '市辖区', '440300', '1');
INSERT INTO `jipu_area` VALUES ('440303', '罗湖区', '440300', '2');
INSERT INTO `jipu_area` VALUES ('440304', '福田区', '440300', '3');
INSERT INTO `jipu_area` VALUES ('440305', '南山区', '440300', '4');
INSERT INTO `jipu_area` VALUES ('440306', '宝安区', '440300', '5');
INSERT INTO `jipu_area` VALUES ('440307', '龙岗区', '440300', '6');
INSERT INTO `jipu_area` VALUES ('440308', '盐田区', '440300', '7');
INSERT INTO `jipu_area` VALUES ('440400', '珠海市', '440000', '4');
INSERT INTO `jipu_area` VALUES ('440401', '市辖区', '440400', '1');
INSERT INTO `jipu_area` VALUES ('440402', '香洲区', '440400', '2');
INSERT INTO `jipu_area` VALUES ('440403', '斗门区', '440400', '3');
INSERT INTO `jipu_area` VALUES ('440404', '金湾区', '440400', '4');
INSERT INTO `jipu_area` VALUES ('440500', '汕头市', '440000', '5');
INSERT INTO `jipu_area` VALUES ('440501', '市辖区', '440500', '1');
INSERT INTO `jipu_area` VALUES ('440507', '龙湖区', '440500', '2');
INSERT INTO `jipu_area` VALUES ('440511', '金平区', '440500', '3');
INSERT INTO `jipu_area` VALUES ('440512', '濠江区', '440500', '4');
INSERT INTO `jipu_area` VALUES ('440513', '潮阳区', '440500', '5');
INSERT INTO `jipu_area` VALUES ('440514', '潮南区', '440500', '6');
INSERT INTO `jipu_area` VALUES ('440515', '澄海区', '440500', '7');
INSERT INTO `jipu_area` VALUES ('440523', '南澳县', '440500', '8');
INSERT INTO `jipu_area` VALUES ('440600', '佛山市', '440000', '6');
INSERT INTO `jipu_area` VALUES ('440601', '市辖区', '440600', '1');
INSERT INTO `jipu_area` VALUES ('440604', '禅城区', '440600', '2');
INSERT INTO `jipu_area` VALUES ('440605', '南海区', '440600', '3');
INSERT INTO `jipu_area` VALUES ('440606', '顺德区', '440600', '4');
INSERT INTO `jipu_area` VALUES ('440607', '三水区', '440600', '5');
INSERT INTO `jipu_area` VALUES ('440608', '高明区', '440600', '6');
INSERT INTO `jipu_area` VALUES ('440700', '江门市', '440000', '7');
INSERT INTO `jipu_area` VALUES ('440701', '市辖区', '440700', '1');
INSERT INTO `jipu_area` VALUES ('440703', '蓬江区', '440700', '2');
INSERT INTO `jipu_area` VALUES ('440704', '江海区', '440700', '3');
INSERT INTO `jipu_area` VALUES ('440705', '新会区', '440700', '4');
INSERT INTO `jipu_area` VALUES ('440781', '台山市', '440700', '5');
INSERT INTO `jipu_area` VALUES ('440783', '开平市', '440700', '6');
INSERT INTO `jipu_area` VALUES ('440784', '鹤山市', '440700', '7');
INSERT INTO `jipu_area` VALUES ('440785', '恩平市', '440700', '8');
INSERT INTO `jipu_area` VALUES ('440800', '湛江市', '440000', '8');
INSERT INTO `jipu_area` VALUES ('440801', '市辖区', '440800', '1');
INSERT INTO `jipu_area` VALUES ('440802', '赤坎区', '440800', '2');
INSERT INTO `jipu_area` VALUES ('440803', '霞山区', '440800', '3');
INSERT INTO `jipu_area` VALUES ('440804', '坡头区', '440800', '4');
INSERT INTO `jipu_area` VALUES ('440811', '麻章区', '440800', '5');
INSERT INTO `jipu_area` VALUES ('440823', '遂溪县', '440800', '6');
INSERT INTO `jipu_area` VALUES ('440825', '徐闻县', '440800', '7');
INSERT INTO `jipu_area` VALUES ('440881', '廉江市', '440800', '8');
INSERT INTO `jipu_area` VALUES ('440882', '雷州市', '440800', '9');
INSERT INTO `jipu_area` VALUES ('440883', '吴川市', '440800', '10');
INSERT INTO `jipu_area` VALUES ('440900', '茂名市', '440000', '9');
INSERT INTO `jipu_area` VALUES ('440901', '市辖区', '440900', '1');
INSERT INTO `jipu_area` VALUES ('440902', '茂南区', '440900', '2');
INSERT INTO `jipu_area` VALUES ('440903', '茂港区', '440900', '3');
INSERT INTO `jipu_area` VALUES ('440923', '电白县', '440900', '4');
INSERT INTO `jipu_area` VALUES ('440981', '高州市', '440900', '5');
INSERT INTO `jipu_area` VALUES ('440982', '化州市', '440900', '6');
INSERT INTO `jipu_area` VALUES ('440983', '信宜市', '440900', '7');
INSERT INTO `jipu_area` VALUES ('441200', '肇庆市', '440000', '10');
INSERT INTO `jipu_area` VALUES ('441201', '市辖区', '441200', '1');
INSERT INTO `jipu_area` VALUES ('441202', '端州区', '441200', '2');
INSERT INTO `jipu_area` VALUES ('441203', '鼎湖区', '441200', '3');
INSERT INTO `jipu_area` VALUES ('441223', '广宁县', '441200', '4');
INSERT INTO `jipu_area` VALUES ('441224', '怀集县', '441200', '5');
INSERT INTO `jipu_area` VALUES ('441225', '封开县', '441200', '6');
INSERT INTO `jipu_area` VALUES ('441226', '德庆县', '441200', '7');
INSERT INTO `jipu_area` VALUES ('441283', '高要市', '441200', '8');
INSERT INTO `jipu_area` VALUES ('441284', '四会市', '441200', '9');
INSERT INTO `jipu_area` VALUES ('441300', '惠州市', '440000', '11');
INSERT INTO `jipu_area` VALUES ('441301', '市辖区', '441300', '1');
INSERT INTO `jipu_area` VALUES ('441302', '惠城区', '441300', '2');
INSERT INTO `jipu_area` VALUES ('441303', '惠阳区', '441300', '3');
INSERT INTO `jipu_area` VALUES ('441322', '博罗县', '441300', '4');
INSERT INTO `jipu_area` VALUES ('441323', '惠东县', '441300', '5');
INSERT INTO `jipu_area` VALUES ('441324', '龙门县', '441300', '6');
INSERT INTO `jipu_area` VALUES ('441400', '梅州市', '440000', '12');
INSERT INTO `jipu_area` VALUES ('441401', '市辖区', '441400', '1');
INSERT INTO `jipu_area` VALUES ('441402', '梅江区', '441400', '2');
INSERT INTO `jipu_area` VALUES ('441421', '梅　县', '441400', '3');
INSERT INTO `jipu_area` VALUES ('441422', '大埔县', '441400', '4');
INSERT INTO `jipu_area` VALUES ('441423', '丰顺县', '441400', '5');
INSERT INTO `jipu_area` VALUES ('441424', '五华县', '441400', '6');
INSERT INTO `jipu_area` VALUES ('441426', '平远县', '441400', '7');
INSERT INTO `jipu_area` VALUES ('441427', '蕉岭县', '441400', '8');
INSERT INTO `jipu_area` VALUES ('441481', '兴宁市', '441400', '9');
INSERT INTO `jipu_area` VALUES ('441500', '汕尾市', '440000', '13');
INSERT INTO `jipu_area` VALUES ('441501', '市辖区', '441500', '1');
INSERT INTO `jipu_area` VALUES ('441502', '城　区', '441500', '2');
INSERT INTO `jipu_area` VALUES ('441521', '海丰县', '441500', '3');
INSERT INTO `jipu_area` VALUES ('441523', '陆河县', '441500', '4');
INSERT INTO `jipu_area` VALUES ('441581', '陆丰市', '441500', '5');
INSERT INTO `jipu_area` VALUES ('441600', '河源市', '440000', '14');
INSERT INTO `jipu_area` VALUES ('441601', '市辖区', '441600', '1');
INSERT INTO `jipu_area` VALUES ('441602', '源城区', '441600', '2');
INSERT INTO `jipu_area` VALUES ('441621', '紫金县', '441600', '3');
INSERT INTO `jipu_area` VALUES ('441622', '龙川县', '441600', '4');
INSERT INTO `jipu_area` VALUES ('441623', '连平县', '441600', '5');
INSERT INTO `jipu_area` VALUES ('441624', '和平县', '441600', '6');
INSERT INTO `jipu_area` VALUES ('441625', '东源县', '441600', '7');
INSERT INTO `jipu_area` VALUES ('441700', '阳江市', '440000', '15');
INSERT INTO `jipu_area` VALUES ('441701', '市辖区', '441700', '1');
INSERT INTO `jipu_area` VALUES ('441702', '江城区', '441700', '2');
INSERT INTO `jipu_area` VALUES ('441721', '阳西县', '441700', '3');
INSERT INTO `jipu_area` VALUES ('441723', '阳东县', '441700', '4');
INSERT INTO `jipu_area` VALUES ('441781', '阳春市', '441700', '5');
INSERT INTO `jipu_area` VALUES ('441800', '清远市', '440000', '16');
INSERT INTO `jipu_area` VALUES ('441801', '市辖区', '441800', '1');
INSERT INTO `jipu_area` VALUES ('441802', '清城区', '441800', '2');
INSERT INTO `jipu_area` VALUES ('441821', '佛冈县', '441800', '3');
INSERT INTO `jipu_area` VALUES ('441823', '阳山县', '441800', '4');
INSERT INTO `jipu_area` VALUES ('441825', '连山壮族瑶族自治县', '441800', '5');
INSERT INTO `jipu_area` VALUES ('441826', '连南瑶族自治县', '441800', '6');
INSERT INTO `jipu_area` VALUES ('441827', '清新县', '441800', '7');
INSERT INTO `jipu_area` VALUES ('441881', '英德市', '441800', '8');
INSERT INTO `jipu_area` VALUES ('441882', '连州市', '441800', '9');
INSERT INTO `jipu_area` VALUES ('441900', '东莞市', '440000', '17');
INSERT INTO `jipu_area` VALUES ('442000', '中山市', '440000', '18');
INSERT INTO `jipu_area` VALUES ('445100', '潮州市', '440000', '19');
INSERT INTO `jipu_area` VALUES ('445101', '市辖区', '445100', '1');
INSERT INTO `jipu_area` VALUES ('445102', '湘桥区', '445100', '2');
INSERT INTO `jipu_area` VALUES ('445121', '潮安县', '445100', '3');
INSERT INTO `jipu_area` VALUES ('445122', '饶平县', '445100', '4');
INSERT INTO `jipu_area` VALUES ('445200', '揭阳市', '440000', '20');
INSERT INTO `jipu_area` VALUES ('445201', '市辖区', '445200', '1');
INSERT INTO `jipu_area` VALUES ('445202', '榕城区', '445200', '2');
INSERT INTO `jipu_area` VALUES ('445221', '揭东县', '445200', '3');
INSERT INTO `jipu_area` VALUES ('445222', '揭西县', '445200', '4');
INSERT INTO `jipu_area` VALUES ('445224', '惠来县', '445200', '5');
INSERT INTO `jipu_area` VALUES ('445281', '普宁市', '445200', '6');
INSERT INTO `jipu_area` VALUES ('445300', '云浮市', '440000', '21');
INSERT INTO `jipu_area` VALUES ('445301', '市辖区', '445300', '1');
INSERT INTO `jipu_area` VALUES ('445302', '云城区', '445300', '2');
INSERT INTO `jipu_area` VALUES ('445321', '新兴县', '445300', '3');
INSERT INTO `jipu_area` VALUES ('445322', '郁南县', '445300', '4');
INSERT INTO `jipu_area` VALUES ('445323', '云安县', '445300', '5');
INSERT INTO `jipu_area` VALUES ('445381', '罗定市', '445300', '6');
INSERT INTO `jipu_area` VALUES ('450000', '广西省', '0', '20');
INSERT INTO `jipu_area` VALUES ('450100', '南宁市', '450000', '1');
INSERT INTO `jipu_area` VALUES ('450101', '市辖区', '450100', '1');
INSERT INTO `jipu_area` VALUES ('450102', '兴宁区', '450100', '2');
INSERT INTO `jipu_area` VALUES ('450103', '青秀区', '450100', '3');
INSERT INTO `jipu_area` VALUES ('450105', '江南区', '450100', '4');
INSERT INTO `jipu_area` VALUES ('450107', '西乡塘区', '450100', '5');
INSERT INTO `jipu_area` VALUES ('450108', '良庆区', '450100', '6');
INSERT INTO `jipu_area` VALUES ('450109', '邕宁区', '450100', '7');
INSERT INTO `jipu_area` VALUES ('450122', '武鸣县', '450100', '8');
INSERT INTO `jipu_area` VALUES ('450123', '隆安县', '450100', '9');
INSERT INTO `jipu_area` VALUES ('450124', '马山县', '450100', '10');
INSERT INTO `jipu_area` VALUES ('450125', '上林县', '450100', '11');
INSERT INTO `jipu_area` VALUES ('450126', '宾阳县', '450100', '12');
INSERT INTO `jipu_area` VALUES ('450127', '横　县', '450100', '13');
INSERT INTO `jipu_area` VALUES ('450200', '柳州市', '450000', '2');
INSERT INTO `jipu_area` VALUES ('450201', '市辖区', '450200', '1');
INSERT INTO `jipu_area` VALUES ('450202', '城中区', '450200', '2');
INSERT INTO `jipu_area` VALUES ('450203', '鱼峰区', '450200', '3');
INSERT INTO `jipu_area` VALUES ('450204', '柳南区', '450200', '4');
INSERT INTO `jipu_area` VALUES ('450205', '柳北区', '450200', '5');
INSERT INTO `jipu_area` VALUES ('450221', '柳江县', '450200', '6');
INSERT INTO `jipu_area` VALUES ('450222', '柳城县', '450200', '7');
INSERT INTO `jipu_area` VALUES ('450223', '鹿寨县', '450200', '8');
INSERT INTO `jipu_area` VALUES ('450224', '融安县', '450200', '9');
INSERT INTO `jipu_area` VALUES ('450225', '融水苗族自治县', '450200', '10');
INSERT INTO `jipu_area` VALUES ('450226', '三江侗族自治县', '450200', '11');
INSERT INTO `jipu_area` VALUES ('450300', '桂林市', '450000', '3');
INSERT INTO `jipu_area` VALUES ('450301', '市辖区', '450300', '1');
INSERT INTO `jipu_area` VALUES ('450302', '秀峰区', '450300', '2');
INSERT INTO `jipu_area` VALUES ('450303', '叠彩区', '450300', '3');
INSERT INTO `jipu_area` VALUES ('450304', '象山区', '450300', '4');
INSERT INTO `jipu_area` VALUES ('450305', '七星区', '450300', '5');
INSERT INTO `jipu_area` VALUES ('450311', '雁山区', '450300', '6');
INSERT INTO `jipu_area` VALUES ('450321', '阳朔县', '450300', '7');
INSERT INTO `jipu_area` VALUES ('450322', '临桂县', '450300', '8');
INSERT INTO `jipu_area` VALUES ('450323', '灵川县', '450300', '9');
INSERT INTO `jipu_area` VALUES ('450324', '全州县', '450300', '10');
INSERT INTO `jipu_area` VALUES ('450325', '兴安县', '450300', '11');
INSERT INTO `jipu_area` VALUES ('450326', '永福县', '450300', '12');
INSERT INTO `jipu_area` VALUES ('450327', '灌阳县', '450300', '13');
INSERT INTO `jipu_area` VALUES ('450328', '龙胜各族自治县', '450300', '14');
INSERT INTO `jipu_area` VALUES ('450329', '资源县', '450300', '15');
INSERT INTO `jipu_area` VALUES ('450330', '平乐县', '450300', '16');
INSERT INTO `jipu_area` VALUES ('450331', '荔蒲县', '450300', '17');
INSERT INTO `jipu_area` VALUES ('450332', '恭城瑶族自治县', '450300', '18');
INSERT INTO `jipu_area` VALUES ('450400', '梧州市', '450000', '4');
INSERT INTO `jipu_area` VALUES ('450401', '市辖区', '450400', '1');
INSERT INTO `jipu_area` VALUES ('450403', '万秀区', '450400', '2');
INSERT INTO `jipu_area` VALUES ('450404', '蝶山区', '450400', '3');
INSERT INTO `jipu_area` VALUES ('450405', '长洲区', '450400', '4');
INSERT INTO `jipu_area` VALUES ('450421', '苍梧县', '450400', '5');
INSERT INTO `jipu_area` VALUES ('450422', '藤　县', '450400', '6');
INSERT INTO `jipu_area` VALUES ('450423', '蒙山县', '450400', '7');
INSERT INTO `jipu_area` VALUES ('450481', '岑溪市', '450400', '8');
INSERT INTO `jipu_area` VALUES ('450500', '北海市', '450000', '5');
INSERT INTO `jipu_area` VALUES ('450501', '市辖区', '450500', '1');
INSERT INTO `jipu_area` VALUES ('450502', '海城区', '450500', '2');
INSERT INTO `jipu_area` VALUES ('450503', '银海区', '450500', '3');
INSERT INTO `jipu_area` VALUES ('450512', '铁山港区', '450500', '4');
INSERT INTO `jipu_area` VALUES ('450521', '合浦县', '450500', '5');
INSERT INTO `jipu_area` VALUES ('450600', '防城港市', '450000', '6');
INSERT INTO `jipu_area` VALUES ('450601', '市辖区', '450600', '1');
INSERT INTO `jipu_area` VALUES ('450602', '港口区', '450600', '2');
INSERT INTO `jipu_area` VALUES ('450603', '防城区', '450600', '3');
INSERT INTO `jipu_area` VALUES ('450621', '上思县', '450600', '4');
INSERT INTO `jipu_area` VALUES ('450681', '东兴市', '450600', '5');
INSERT INTO `jipu_area` VALUES ('450700', '钦州市', '450000', '7');
INSERT INTO `jipu_area` VALUES ('450701', '市辖区', '450700', '1');
INSERT INTO `jipu_area` VALUES ('450702', '钦南区', '450700', '2');
INSERT INTO `jipu_area` VALUES ('450703', '钦北区', '450700', '3');
INSERT INTO `jipu_area` VALUES ('450721', '灵山县', '450700', '4');
INSERT INTO `jipu_area` VALUES ('450722', '浦北县', '450700', '5');
INSERT INTO `jipu_area` VALUES ('450800', '贵港市', '450000', '8');
INSERT INTO `jipu_area` VALUES ('450801', '市辖区', '450800', '1');
INSERT INTO `jipu_area` VALUES ('450802', '港北区', '450800', '2');
INSERT INTO `jipu_area` VALUES ('450803', '港南区', '450800', '3');
INSERT INTO `jipu_area` VALUES ('450804', '覃塘区', '450800', '4');
INSERT INTO `jipu_area` VALUES ('450821', '平南县', '450800', '5');
INSERT INTO `jipu_area` VALUES ('450881', '桂平市', '450800', '6');
INSERT INTO `jipu_area` VALUES ('450900', '玉林市', '450000', '9');
INSERT INTO `jipu_area` VALUES ('450901', '市辖区', '450900', '1');
INSERT INTO `jipu_area` VALUES ('450902', '玉州区', '450900', '2');
INSERT INTO `jipu_area` VALUES ('450921', '容　县', '450900', '3');
INSERT INTO `jipu_area` VALUES ('450922', '陆川县', '450900', '4');
INSERT INTO `jipu_area` VALUES ('450923', '博白县', '450900', '5');
INSERT INTO `jipu_area` VALUES ('450924', '兴业县', '450900', '6');
INSERT INTO `jipu_area` VALUES ('450981', '北流市', '450900', '7');
INSERT INTO `jipu_area` VALUES ('451000', '百色市', '450000', '10');
INSERT INTO `jipu_area` VALUES ('451001', '市辖区', '451000', '1');
INSERT INTO `jipu_area` VALUES ('451002', '右江区', '451000', '2');
INSERT INTO `jipu_area` VALUES ('451021', '田阳县', '451000', '3');
INSERT INTO `jipu_area` VALUES ('451022', '田东县', '451000', '4');
INSERT INTO `jipu_area` VALUES ('451023', '平果县', '451000', '5');
INSERT INTO `jipu_area` VALUES ('451024', '德保县', '451000', '6');
INSERT INTO `jipu_area` VALUES ('451025', '靖西县', '451000', '7');
INSERT INTO `jipu_area` VALUES ('451026', '那坡县', '451000', '8');
INSERT INTO `jipu_area` VALUES ('451027', '凌云县', '451000', '9');
INSERT INTO `jipu_area` VALUES ('451028', '乐业县', '451000', '10');
INSERT INTO `jipu_area` VALUES ('451029', '田林县', '451000', '11');
INSERT INTO `jipu_area` VALUES ('451030', '西林县', '451000', '12');
INSERT INTO `jipu_area` VALUES ('451031', '隆林各族自治县', '451000', '13');
INSERT INTO `jipu_area` VALUES ('451100', '贺州市', '450000', '11');
INSERT INTO `jipu_area` VALUES ('451101', '市辖区', '451100', '1');
INSERT INTO `jipu_area` VALUES ('451102', '八步区', '451100', '2');
INSERT INTO `jipu_area` VALUES ('451121', '昭平县', '451100', '3');
INSERT INTO `jipu_area` VALUES ('451122', '钟山县', '451100', '4');
INSERT INTO `jipu_area` VALUES ('451123', '富川瑶族自治县', '451100', '5');
INSERT INTO `jipu_area` VALUES ('451200', '河池市', '450000', '12');
INSERT INTO `jipu_area` VALUES ('451201', '市辖区', '451200', '1');
INSERT INTO `jipu_area` VALUES ('451202', '金城江区', '451200', '2');
INSERT INTO `jipu_area` VALUES ('451221', '南丹县', '451200', '3');
INSERT INTO `jipu_area` VALUES ('451222', '天峨县', '451200', '4');
INSERT INTO `jipu_area` VALUES ('451223', '凤山县', '451200', '5');
INSERT INTO `jipu_area` VALUES ('451224', '东兰县', '451200', '6');
INSERT INTO `jipu_area` VALUES ('451225', '罗城仫佬族自治县', '451200', '7');
INSERT INTO `jipu_area` VALUES ('451226', '环江毛南族自治县', '451200', '8');
INSERT INTO `jipu_area` VALUES ('451227', '巴马瑶族自治县', '451200', '9');
INSERT INTO `jipu_area` VALUES ('451228', '都安瑶族自治县', '451200', '10');
INSERT INTO `jipu_area` VALUES ('451229', '大化瑶族自治县', '451200', '11');
INSERT INTO `jipu_area` VALUES ('451281', '宜州市', '451200', '12');
INSERT INTO `jipu_area` VALUES ('451300', '来宾市', '450000', '13');
INSERT INTO `jipu_area` VALUES ('451301', '市辖区', '451300', '1');
INSERT INTO `jipu_area` VALUES ('451302', '兴宾区', '451300', '2');
INSERT INTO `jipu_area` VALUES ('451321', '忻城县', '451300', '3');
INSERT INTO `jipu_area` VALUES ('451322', '象州县', '451300', '4');
INSERT INTO `jipu_area` VALUES ('451323', '武宣县', '451300', '5');
INSERT INTO `jipu_area` VALUES ('451324', '金秀瑶族自治县', '451300', '6');
INSERT INTO `jipu_area` VALUES ('451381', '合山市', '451300', '7');
INSERT INTO `jipu_area` VALUES ('451400', '崇左市', '450000', '14');
INSERT INTO `jipu_area` VALUES ('451401', '市辖区', '451400', '1');
INSERT INTO `jipu_area` VALUES ('451402', '江洲区', '451400', '2');
INSERT INTO `jipu_area` VALUES ('451421', '扶绥县', '451400', '3');
INSERT INTO `jipu_area` VALUES ('451422', '宁明县', '451400', '4');
INSERT INTO `jipu_area` VALUES ('451423', '龙州县', '451400', '5');
INSERT INTO `jipu_area` VALUES ('451424', '大新县', '451400', '6');
INSERT INTO `jipu_area` VALUES ('451425', '天等县', '451400', '7');
INSERT INTO `jipu_area` VALUES ('451481', '凭祥市', '451400', '8');
INSERT INTO `jipu_area` VALUES ('460000', '海南省', '0', '21');
INSERT INTO `jipu_area` VALUES ('460100', '海口市', '460000', '1');
INSERT INTO `jipu_area` VALUES ('460101', '市辖区', '460100', '1');
INSERT INTO `jipu_area` VALUES ('460105', '秀英区', '460100', '2');
INSERT INTO `jipu_area` VALUES ('460106', '龙华区', '460100', '3');
INSERT INTO `jipu_area` VALUES ('460107', '琼山区', '460100', '4');
INSERT INTO `jipu_area` VALUES ('460108', '美兰区', '460100', '5');
INSERT INTO `jipu_area` VALUES ('460200', '三亚市', '460000', '2');
INSERT INTO `jipu_area` VALUES ('460201', '市辖区', '460200', '1');
INSERT INTO `jipu_area` VALUES ('469000', '省直辖县级行政单位', '460000', '3');
INSERT INTO `jipu_area` VALUES ('469001', '五指山市', '469000', '1');
INSERT INTO `jipu_area` VALUES ('469002', '琼海市', '469000', '2');
INSERT INTO `jipu_area` VALUES ('469003', '儋州市', '469000', '3');
INSERT INTO `jipu_area` VALUES ('469005', '文昌市', '469000', '4');
INSERT INTO `jipu_area` VALUES ('469006', '万宁市', '469000', '5');
INSERT INTO `jipu_area` VALUES ('469007', '东方市', '469000', '6');
INSERT INTO `jipu_area` VALUES ('469025', '定安县', '469000', '7');
INSERT INTO `jipu_area` VALUES ('469026', '屯昌县', '469000', '8');
INSERT INTO `jipu_area` VALUES ('469027', '澄迈县', '469000', '9');
INSERT INTO `jipu_area` VALUES ('469028', '临高县', '469000', '10');
INSERT INTO `jipu_area` VALUES ('469030', '白沙黎族自治县', '469000', '11');
INSERT INTO `jipu_area` VALUES ('469031', '昌江黎族自治县', '469000', '12');
INSERT INTO `jipu_area` VALUES ('469033', '乐东黎族自治县', '469000', '13');
INSERT INTO `jipu_area` VALUES ('469034', '陵水黎族自治县', '469000', '14');
INSERT INTO `jipu_area` VALUES ('469035', '保亭黎族苗族自治县', '469000', '15');
INSERT INTO `jipu_area` VALUES ('469036', '琼中黎族苗族自治县', '469000', '16');
INSERT INTO `jipu_area` VALUES ('469037', '西沙群岛', '469000', '17');
INSERT INTO `jipu_area` VALUES ('469038', '南沙群岛', '469000', '18');
INSERT INTO `jipu_area` VALUES ('469039', '中沙群岛的岛礁及其海域', '469000', '19');
INSERT INTO `jipu_area` VALUES ('500000', '重庆市', '0', '22');
INSERT INTO `jipu_area` VALUES ('500100', '市辖区', '500000', '1');
INSERT INTO `jipu_area` VALUES ('500101', '万州区', '500100', '1');
INSERT INTO `jipu_area` VALUES ('500102', '涪陵区', '500100', '2');
INSERT INTO `jipu_area` VALUES ('500103', '渝中区', '500100', '3');
INSERT INTO `jipu_area` VALUES ('500104', '大渡口区', '500100', '4');
INSERT INTO `jipu_area` VALUES ('500105', '江北区', '500100', '5');
INSERT INTO `jipu_area` VALUES ('500106', '沙坪坝区', '500100', '6');
INSERT INTO `jipu_area` VALUES ('500107', '九龙坡区', '500100', '7');
INSERT INTO `jipu_area` VALUES ('500108', '南岸区', '500100', '8');
INSERT INTO `jipu_area` VALUES ('500109', '北碚区', '500100', '9');
INSERT INTO `jipu_area` VALUES ('500110', '万盛区', '500100', '10');
INSERT INTO `jipu_area` VALUES ('500111', '双桥区', '500100', '11');
INSERT INTO `jipu_area` VALUES ('500112', '渝北区', '500100', '12');
INSERT INTO `jipu_area` VALUES ('500113', '巴南区', '500100', '13');
INSERT INTO `jipu_area` VALUES ('500114', '黔江区', '500100', '14');
INSERT INTO `jipu_area` VALUES ('500115', '长寿区', '500100', '15');
INSERT INTO `jipu_area` VALUES ('500200', '县', '500000', '2');
INSERT INTO `jipu_area` VALUES ('500222', '綦江县', '500200', '1');
INSERT INTO `jipu_area` VALUES ('500223', '潼南县', '500200', '2');
INSERT INTO `jipu_area` VALUES ('500224', '铜梁县', '500200', '3');
INSERT INTO `jipu_area` VALUES ('500225', '大足县', '500200', '4');
INSERT INTO `jipu_area` VALUES ('500226', '荣昌县', '500200', '5');
INSERT INTO `jipu_area` VALUES ('500227', '璧山县', '500200', '6');
INSERT INTO `jipu_area` VALUES ('500228', '梁平县', '500200', '7');
INSERT INTO `jipu_area` VALUES ('500229', '城口县', '500200', '8');
INSERT INTO `jipu_area` VALUES ('500230', '丰都县', '500200', '9');
INSERT INTO `jipu_area` VALUES ('500231', '垫江县', '500200', '10');
INSERT INTO `jipu_area` VALUES ('500232', '武隆县', '500200', '11');
INSERT INTO `jipu_area` VALUES ('500233', '忠　县', '500200', '12');
INSERT INTO `jipu_area` VALUES ('500234', '开　县', '500200', '13');
INSERT INTO `jipu_area` VALUES ('500235', '云阳县', '500200', '14');
INSERT INTO `jipu_area` VALUES ('500236', '奉节县', '500200', '15');
INSERT INTO `jipu_area` VALUES ('500237', '巫山县', '500200', '16');
INSERT INTO `jipu_area` VALUES ('500238', '巫溪县', '500200', '17');
INSERT INTO `jipu_area` VALUES ('500240', '石柱土家族自治县', '500200', '18');
INSERT INTO `jipu_area` VALUES ('500241', '秀山土家族苗族自治县', '500200', '19');
INSERT INTO `jipu_area` VALUES ('500242', '酉阳土家族苗族自治县', '500200', '20');
INSERT INTO `jipu_area` VALUES ('500243', '彭水苗族土家族自治县', '500200', '21');
INSERT INTO `jipu_area` VALUES ('500300', '市', '500000', '3');
INSERT INTO `jipu_area` VALUES ('500381', '江津市', '500300', '1');
INSERT INTO `jipu_area` VALUES ('500382', '合川市', '500300', '2');
INSERT INTO `jipu_area` VALUES ('500383', '永川市', '500300', '3');
INSERT INTO `jipu_area` VALUES ('500384', '南川市', '500300', '4');
INSERT INTO `jipu_area` VALUES ('510000', '四川省', '0', '23');
INSERT INTO `jipu_area` VALUES ('510100', '成都市', '510000', '1');
INSERT INTO `jipu_area` VALUES ('510101', '市辖区', '510100', '1');
INSERT INTO `jipu_area` VALUES ('510104', '锦江区', '510100', '2');
INSERT INTO `jipu_area` VALUES ('510105', '青羊区', '510100', '3');
INSERT INTO `jipu_area` VALUES ('510106', '金牛区', '510100', '4');
INSERT INTO `jipu_area` VALUES ('510107', '武侯区', '510100', '5');
INSERT INTO `jipu_area` VALUES ('510108', '成华区', '510100', '6');
INSERT INTO `jipu_area` VALUES ('510112', '龙泉驿区', '510100', '7');
INSERT INTO `jipu_area` VALUES ('510113', '青白江区', '510100', '8');
INSERT INTO `jipu_area` VALUES ('510114', '新都区', '510100', '9');
INSERT INTO `jipu_area` VALUES ('510115', '温江区', '510100', '10');
INSERT INTO `jipu_area` VALUES ('510121', '金堂县', '510100', '11');
INSERT INTO `jipu_area` VALUES ('510122', '双流县', '510100', '12');
INSERT INTO `jipu_area` VALUES ('510124', '郫　县', '510100', '13');
INSERT INTO `jipu_area` VALUES ('510129', '大邑县', '510100', '14');
INSERT INTO `jipu_area` VALUES ('510131', '蒲江县', '510100', '15');
INSERT INTO `jipu_area` VALUES ('510132', '新津县', '510100', '16');
INSERT INTO `jipu_area` VALUES ('510181', '都江堰市', '510100', '17');
INSERT INTO `jipu_area` VALUES ('510182', '彭州市', '510100', '18');
INSERT INTO `jipu_area` VALUES ('510183', '邛崃市', '510100', '19');
INSERT INTO `jipu_area` VALUES ('510184', '崇州市', '510100', '20');
INSERT INTO `jipu_area` VALUES ('510300', '自贡市', '510000', '2');
INSERT INTO `jipu_area` VALUES ('510301', '市辖区', '510300', '1');
INSERT INTO `jipu_area` VALUES ('510302', '自流井区', '510300', '2');
INSERT INTO `jipu_area` VALUES ('510303', '贡井区', '510300', '3');
INSERT INTO `jipu_area` VALUES ('510304', '大安区', '510300', '4');
INSERT INTO `jipu_area` VALUES ('510311', '沿滩区', '510300', '5');
INSERT INTO `jipu_area` VALUES ('510321', '荣　县', '510300', '6');
INSERT INTO `jipu_area` VALUES ('510322', '富顺县', '510300', '7');
INSERT INTO `jipu_area` VALUES ('510400', '攀枝花市', '510000', '3');
INSERT INTO `jipu_area` VALUES ('510401', '市辖区', '510400', '1');
INSERT INTO `jipu_area` VALUES ('510402', '东　区', '510400', '2');
INSERT INTO `jipu_area` VALUES ('510403', '西　区', '510400', '3');
INSERT INTO `jipu_area` VALUES ('510411', '仁和区', '510400', '4');
INSERT INTO `jipu_area` VALUES ('510421', '米易县', '510400', '5');
INSERT INTO `jipu_area` VALUES ('510422', '盐边县', '510400', '6');
INSERT INTO `jipu_area` VALUES ('510500', '泸州市', '510000', '4');
INSERT INTO `jipu_area` VALUES ('510501', '市辖区', '510500', '1');
INSERT INTO `jipu_area` VALUES ('510502', '江阳区', '510500', '2');
INSERT INTO `jipu_area` VALUES ('510503', '纳溪区', '510500', '3');
INSERT INTO `jipu_area` VALUES ('510504', '龙马潭区', '510500', '4');
INSERT INTO `jipu_area` VALUES ('510521', '泸　县', '510500', '5');
INSERT INTO `jipu_area` VALUES ('510522', '合江县', '510500', '6');
INSERT INTO `jipu_area` VALUES ('510524', '叙永县', '510500', '7');
INSERT INTO `jipu_area` VALUES ('510525', '古蔺县', '510500', '8');
INSERT INTO `jipu_area` VALUES ('510600', '德阳市', '510000', '5');
INSERT INTO `jipu_area` VALUES ('510601', '市辖区', '510600', '1');
INSERT INTO `jipu_area` VALUES ('510603', '旌阳区', '510600', '2');
INSERT INTO `jipu_area` VALUES ('510623', '中江县', '510600', '3');
INSERT INTO `jipu_area` VALUES ('510626', '罗江县', '510600', '4');
INSERT INTO `jipu_area` VALUES ('510681', '广汉市', '510600', '5');
INSERT INTO `jipu_area` VALUES ('510682', '什邡市', '510600', '6');
INSERT INTO `jipu_area` VALUES ('510683', '绵竹市', '510600', '7');
INSERT INTO `jipu_area` VALUES ('510700', '绵阳市', '510000', '6');
INSERT INTO `jipu_area` VALUES ('510701', '市辖区', '510700', '1');
INSERT INTO `jipu_area` VALUES ('510703', '涪城区', '510700', '2');
INSERT INTO `jipu_area` VALUES ('510704', '游仙区', '510700', '3');
INSERT INTO `jipu_area` VALUES ('510722', '三台县', '510700', '4');
INSERT INTO `jipu_area` VALUES ('510723', '盐亭县', '510700', '5');
INSERT INTO `jipu_area` VALUES ('510724', '安　县', '510700', '6');
INSERT INTO `jipu_area` VALUES ('510725', '梓潼县', '510700', '7');
INSERT INTO `jipu_area` VALUES ('510726', '北川羌族自治县', '510700', '8');
INSERT INTO `jipu_area` VALUES ('510727', '平武县', '510700', '9');
INSERT INTO `jipu_area` VALUES ('510781', '江油市', '510700', '10');
INSERT INTO `jipu_area` VALUES ('510800', '广元市', '510000', '7');
INSERT INTO `jipu_area` VALUES ('510801', '市辖区', '510800', '1');
INSERT INTO `jipu_area` VALUES ('510802', '市中区', '510800', '2');
INSERT INTO `jipu_area` VALUES ('510811', '元坝区', '510800', '3');
INSERT INTO `jipu_area` VALUES ('510812', '朝天区', '510800', '4');
INSERT INTO `jipu_area` VALUES ('510821', '旺苍县', '510800', '5');
INSERT INTO `jipu_area` VALUES ('510822', '青川县', '510800', '6');
INSERT INTO `jipu_area` VALUES ('510823', '剑阁县', '510800', '7');
INSERT INTO `jipu_area` VALUES ('510824', '苍溪县', '510800', '8');
INSERT INTO `jipu_area` VALUES ('510900', '遂宁市', '510000', '8');
INSERT INTO `jipu_area` VALUES ('510901', '市辖区', '510900', '1');
INSERT INTO `jipu_area` VALUES ('510903', '船山区', '510900', '2');
INSERT INTO `jipu_area` VALUES ('510904', '安居区', '510900', '3');
INSERT INTO `jipu_area` VALUES ('510921', '蓬溪县', '510900', '4');
INSERT INTO `jipu_area` VALUES ('510922', '射洪县', '510900', '5');
INSERT INTO `jipu_area` VALUES ('510923', '大英县', '510900', '6');
INSERT INTO `jipu_area` VALUES ('511000', '内江市', '510000', '9');
INSERT INTO `jipu_area` VALUES ('511001', '市辖区', '511000', '1');
INSERT INTO `jipu_area` VALUES ('511002', '市中区', '511000', '2');
INSERT INTO `jipu_area` VALUES ('511011', '东兴区', '511000', '3');
INSERT INTO `jipu_area` VALUES ('511024', '威远县', '511000', '4');
INSERT INTO `jipu_area` VALUES ('511025', '资中县', '511000', '5');
INSERT INTO `jipu_area` VALUES ('511028', '隆昌县', '511000', '6');
INSERT INTO `jipu_area` VALUES ('511100', '乐山市', '510000', '10');
INSERT INTO `jipu_area` VALUES ('511101', '市辖区', '511100', '1');
INSERT INTO `jipu_area` VALUES ('511102', '市中区', '511100', '2');
INSERT INTO `jipu_area` VALUES ('511111', '沙湾区', '511100', '3');
INSERT INTO `jipu_area` VALUES ('511112', '五通桥区', '511100', '4');
INSERT INTO `jipu_area` VALUES ('511113', '金口河区', '511100', '5');
INSERT INTO `jipu_area` VALUES ('511123', '犍为县', '511100', '6');
INSERT INTO `jipu_area` VALUES ('511124', '井研县', '511100', '7');
INSERT INTO `jipu_area` VALUES ('511126', '夹江县', '511100', '8');
INSERT INTO `jipu_area` VALUES ('511129', '沐川县', '511100', '9');
INSERT INTO `jipu_area` VALUES ('511132', '峨边彝族自治县', '511100', '10');
INSERT INTO `jipu_area` VALUES ('511133', '马边彝族自治县', '511100', '11');
INSERT INTO `jipu_area` VALUES ('511181', '峨眉山市', '511100', '12');
INSERT INTO `jipu_area` VALUES ('511300', '南充市', '510000', '11');
INSERT INTO `jipu_area` VALUES ('511301', '市辖区', '511300', '1');
INSERT INTO `jipu_area` VALUES ('511302', '顺庆区', '511300', '2');
INSERT INTO `jipu_area` VALUES ('511303', '高坪区', '511300', '3');
INSERT INTO `jipu_area` VALUES ('511304', '嘉陵区', '511300', '4');
INSERT INTO `jipu_area` VALUES ('511321', '南部县', '511300', '5');
INSERT INTO `jipu_area` VALUES ('511322', '营山县', '511300', '6');
INSERT INTO `jipu_area` VALUES ('511323', '蓬安县', '511300', '7');
INSERT INTO `jipu_area` VALUES ('511324', '仪陇县', '511300', '8');
INSERT INTO `jipu_area` VALUES ('511325', '西充县', '511300', '9');
INSERT INTO `jipu_area` VALUES ('511381', '阆中市', '511300', '10');
INSERT INTO `jipu_area` VALUES ('511400', '眉山市', '510000', '12');
INSERT INTO `jipu_area` VALUES ('511401', '市辖区', '511400', '1');
INSERT INTO `jipu_area` VALUES ('511402', '东坡区', '511400', '2');
INSERT INTO `jipu_area` VALUES ('511421', '仁寿县', '511400', '3');
INSERT INTO `jipu_area` VALUES ('511422', '彭山县', '511400', '4');
INSERT INTO `jipu_area` VALUES ('511423', '洪雅县', '511400', '5');
INSERT INTO `jipu_area` VALUES ('511424', '丹棱县', '511400', '6');
INSERT INTO `jipu_area` VALUES ('511425', '青神县', '511400', '7');
INSERT INTO `jipu_area` VALUES ('511500', '宜宾市', '510000', '13');
INSERT INTO `jipu_area` VALUES ('511501', '市辖区', '511500', '1');
INSERT INTO `jipu_area` VALUES ('511502', '翠屏区', '511500', '2');
INSERT INTO `jipu_area` VALUES ('511521', '宜宾县', '511500', '3');
INSERT INTO `jipu_area` VALUES ('511522', '南溪县', '511500', '4');
INSERT INTO `jipu_area` VALUES ('511523', '江安县', '511500', '5');
INSERT INTO `jipu_area` VALUES ('511524', '长宁县', '511500', '6');
INSERT INTO `jipu_area` VALUES ('511525', '高　县', '511500', '7');
INSERT INTO `jipu_area` VALUES ('511526', '珙　县', '511500', '8');
INSERT INTO `jipu_area` VALUES ('511527', '筠连县', '511500', '9');
INSERT INTO `jipu_area` VALUES ('511528', '兴文县', '511500', '10');
INSERT INTO `jipu_area` VALUES ('511529', '屏山县', '511500', '11');
INSERT INTO `jipu_area` VALUES ('511600', '广安市', '510000', '14');
INSERT INTO `jipu_area` VALUES ('511601', '市辖区', '511600', '1');
INSERT INTO `jipu_area` VALUES ('511602', '广安区', '511600', '2');
INSERT INTO `jipu_area` VALUES ('511621', '岳池县', '511600', '3');
INSERT INTO `jipu_area` VALUES ('511622', '武胜县', '511600', '4');
INSERT INTO `jipu_area` VALUES ('511623', '邻水县', '511600', '5');
INSERT INTO `jipu_area` VALUES ('511681', '华莹市', '511600', '6');
INSERT INTO `jipu_area` VALUES ('511700', '达州市', '510000', '15');
INSERT INTO `jipu_area` VALUES ('511701', '市辖区', '511700', '1');
INSERT INTO `jipu_area` VALUES ('511702', '通川区', '511700', '2');
INSERT INTO `jipu_area` VALUES ('511721', '达　县', '511700', '3');
INSERT INTO `jipu_area` VALUES ('511722', '宣汉县', '511700', '4');
INSERT INTO `jipu_area` VALUES ('511723', '开江县', '511700', '5');
INSERT INTO `jipu_area` VALUES ('511724', '大竹县', '511700', '6');
INSERT INTO `jipu_area` VALUES ('511725', '渠　县', '511700', '7');
INSERT INTO `jipu_area` VALUES ('511781', '万源市', '511700', '8');
INSERT INTO `jipu_area` VALUES ('511800', '雅安市', '510000', '16');
INSERT INTO `jipu_area` VALUES ('511801', '市辖区', '511800', '1');
INSERT INTO `jipu_area` VALUES ('511802', '雨城区', '511800', '2');
INSERT INTO `jipu_area` VALUES ('511821', '名山县', '511800', '3');
INSERT INTO `jipu_area` VALUES ('511822', '荥经县', '511800', '4');
INSERT INTO `jipu_area` VALUES ('511823', '汉源县', '511800', '5');
INSERT INTO `jipu_area` VALUES ('511824', '石棉县', '511800', '6');
INSERT INTO `jipu_area` VALUES ('511825', '天全县', '511800', '7');
INSERT INTO `jipu_area` VALUES ('511826', '芦山县', '511800', '8');
INSERT INTO `jipu_area` VALUES ('511827', '宝兴县', '511800', '9');
INSERT INTO `jipu_area` VALUES ('511900', '巴中市', '510000', '17');
INSERT INTO `jipu_area` VALUES ('511901', '市辖区', '511900', '1');
INSERT INTO `jipu_area` VALUES ('511902', '巴州区', '511900', '2');
INSERT INTO `jipu_area` VALUES ('511921', '通江县', '511900', '3');
INSERT INTO `jipu_area` VALUES ('511922', '南江县', '511900', '4');
INSERT INTO `jipu_area` VALUES ('511923', '平昌县', '511900', '5');
INSERT INTO `jipu_area` VALUES ('512000', '资阳市', '510000', '18');
INSERT INTO `jipu_area` VALUES ('512001', '市辖区', '512000', '1');
INSERT INTO `jipu_area` VALUES ('512002', '雁江区', '512000', '2');
INSERT INTO `jipu_area` VALUES ('512021', '安岳县', '512000', '3');
INSERT INTO `jipu_area` VALUES ('512022', '乐至县', '512000', '4');
INSERT INTO `jipu_area` VALUES ('512081', '简阳市', '512000', '5');
INSERT INTO `jipu_area` VALUES ('513200', '阿坝藏族羌族自治州', '510000', '19');
INSERT INTO `jipu_area` VALUES ('513221', '汶川县', '513200', '1');
INSERT INTO `jipu_area` VALUES ('513222', '理　县', '513200', '2');
INSERT INTO `jipu_area` VALUES ('513223', '茂　县', '513200', '3');
INSERT INTO `jipu_area` VALUES ('513224', '松潘县', '513200', '4');
INSERT INTO `jipu_area` VALUES ('513225', '九寨沟县', '513200', '5');
INSERT INTO `jipu_area` VALUES ('513226', '金川县', '513200', '6');
INSERT INTO `jipu_area` VALUES ('513227', '小金县', '513200', '7');
INSERT INTO `jipu_area` VALUES ('513228', '黑水县', '513200', '8');
INSERT INTO `jipu_area` VALUES ('513229', '马尔康县', '513200', '9');
INSERT INTO `jipu_area` VALUES ('513230', '壤塘县', '513200', '10');
INSERT INTO `jipu_area` VALUES ('513231', '阿坝县', '513200', '11');
INSERT INTO `jipu_area` VALUES ('513232', '若尔盖县', '513200', '12');
INSERT INTO `jipu_area` VALUES ('513233', '红原县', '513200', '13');
INSERT INTO `jipu_area` VALUES ('513300', '甘孜藏族自治州', '510000', '20');
INSERT INTO `jipu_area` VALUES ('513321', '康定县', '513300', '1');
INSERT INTO `jipu_area` VALUES ('513322', '泸定县', '513300', '2');
INSERT INTO `jipu_area` VALUES ('513323', '丹巴县', '513300', '3');
INSERT INTO `jipu_area` VALUES ('513324', '九龙县', '513300', '4');
INSERT INTO `jipu_area` VALUES ('513325', '雅江县', '513300', '5');
INSERT INTO `jipu_area` VALUES ('513326', '道孚县', '513300', '6');
INSERT INTO `jipu_area` VALUES ('513327', '炉霍县', '513300', '7');
INSERT INTO `jipu_area` VALUES ('513328', '甘孜县', '513300', '8');
INSERT INTO `jipu_area` VALUES ('513329', '新龙县', '513300', '9');
INSERT INTO `jipu_area` VALUES ('513330', '德格县', '513300', '10');
INSERT INTO `jipu_area` VALUES ('513331', '白玉县', '513300', '11');
INSERT INTO `jipu_area` VALUES ('513332', '石渠县', '513300', '12');
INSERT INTO `jipu_area` VALUES ('513333', '色达县', '513300', '13');
INSERT INTO `jipu_area` VALUES ('513334', '理塘县', '513300', '14');
INSERT INTO `jipu_area` VALUES ('513335', '巴塘县', '513300', '15');
INSERT INTO `jipu_area` VALUES ('513336', '乡城县', '513300', '16');
INSERT INTO `jipu_area` VALUES ('513337', '稻城县', '513300', '17');
INSERT INTO `jipu_area` VALUES ('513338', '得荣县', '513300', '18');
INSERT INTO `jipu_area` VALUES ('513400', '凉山彝族自治州', '510000', '21');
INSERT INTO `jipu_area` VALUES ('513401', '西昌市', '513400', '1');
INSERT INTO `jipu_area` VALUES ('513422', '木里藏族自治县', '513400', '2');
INSERT INTO `jipu_area` VALUES ('513423', '盐源县', '513400', '3');
INSERT INTO `jipu_area` VALUES ('513424', '德昌县', '513400', '4');
INSERT INTO `jipu_area` VALUES ('513425', '会理县', '513400', '5');
INSERT INTO `jipu_area` VALUES ('513426', '会东县', '513400', '6');
INSERT INTO `jipu_area` VALUES ('513427', '宁南县', '513400', '7');
INSERT INTO `jipu_area` VALUES ('513428', '普格县', '513400', '8');
INSERT INTO `jipu_area` VALUES ('513429', '布拖县', '513400', '9');
INSERT INTO `jipu_area` VALUES ('513430', '金阳县', '513400', '10');
INSERT INTO `jipu_area` VALUES ('513431', '昭觉县', '513400', '11');
INSERT INTO `jipu_area` VALUES ('513432', '喜德县', '513400', '12');
INSERT INTO `jipu_area` VALUES ('513433', '冕宁县', '513400', '13');
INSERT INTO `jipu_area` VALUES ('513434', '越西县', '513400', '14');
INSERT INTO `jipu_area` VALUES ('513435', '甘洛县', '513400', '15');
INSERT INTO `jipu_area` VALUES ('513436', '美姑县', '513400', '16');
INSERT INTO `jipu_area` VALUES ('513437', '雷波县', '513400', '17');
INSERT INTO `jipu_area` VALUES ('520000', '贵州省', '0', '24');
INSERT INTO `jipu_area` VALUES ('520100', '贵阳市', '520000', '1');
INSERT INTO `jipu_area` VALUES ('520101', '市辖区', '520100', '1');
INSERT INTO `jipu_area` VALUES ('520102', '南明区', '520100', '2');
INSERT INTO `jipu_area` VALUES ('520103', '云岩区', '520100', '3');
INSERT INTO `jipu_area` VALUES ('520111', '花溪区', '520100', '4');
INSERT INTO `jipu_area` VALUES ('520112', '乌当区', '520100', '5');
INSERT INTO `jipu_area` VALUES ('520113', '白云区', '520100', '6');
INSERT INTO `jipu_area` VALUES ('520114', '小河区', '520100', '7');
INSERT INTO `jipu_area` VALUES ('520121', '开阳县', '520100', '8');
INSERT INTO `jipu_area` VALUES ('520122', '息烽县', '520100', '9');
INSERT INTO `jipu_area` VALUES ('520123', '修文县', '520100', '10');
INSERT INTO `jipu_area` VALUES ('520181', '清镇市', '520100', '11');
INSERT INTO `jipu_area` VALUES ('520200', '六盘水市', '520000', '2');
INSERT INTO `jipu_area` VALUES ('520201', '钟山区', '520200', '1');
INSERT INTO `jipu_area` VALUES ('520203', '六枝特区', '520200', '2');
INSERT INTO `jipu_area` VALUES ('520221', '水城县', '520200', '3');
INSERT INTO `jipu_area` VALUES ('520222', '盘　县', '520200', '4');
INSERT INTO `jipu_area` VALUES ('520300', '遵义市', '520000', '3');
INSERT INTO `jipu_area` VALUES ('520301', '市辖区', '520300', '1');
INSERT INTO `jipu_area` VALUES ('520302', '红花岗区', '520300', '2');
INSERT INTO `jipu_area` VALUES ('520303', '汇川区', '520300', '3');
INSERT INTO `jipu_area` VALUES ('520321', '遵义县', '520300', '4');
INSERT INTO `jipu_area` VALUES ('520322', '桐梓县', '520300', '5');
INSERT INTO `jipu_area` VALUES ('520323', '绥阳县', '520300', '6');
INSERT INTO `jipu_area` VALUES ('520324', '正安县', '520300', '7');
INSERT INTO `jipu_area` VALUES ('520325', '道真仡佬族苗族自治县', '520300', '8');
INSERT INTO `jipu_area` VALUES ('520326', '务川仡佬族苗族自治县', '520300', '9');
INSERT INTO `jipu_area` VALUES ('520327', '凤冈县', '520300', '10');
INSERT INTO `jipu_area` VALUES ('520328', '湄潭县', '520300', '11');
INSERT INTO `jipu_area` VALUES ('520329', '余庆县', '520300', '12');
INSERT INTO `jipu_area` VALUES ('520330', '习水县', '520300', '13');
INSERT INTO `jipu_area` VALUES ('520381', '赤水市', '520300', '14');
INSERT INTO `jipu_area` VALUES ('520382', '仁怀市', '520300', '15');
INSERT INTO `jipu_area` VALUES ('520400', '安顺市', '520000', '4');
INSERT INTO `jipu_area` VALUES ('520401', '市辖区', '520400', '1');
INSERT INTO `jipu_area` VALUES ('520402', '西秀区', '520400', '2');
INSERT INTO `jipu_area` VALUES ('520421', '平坝县', '520400', '3');
INSERT INTO `jipu_area` VALUES ('520422', '普定县', '520400', '4');
INSERT INTO `jipu_area` VALUES ('520423', '镇宁布依族苗族自治县', '520400', '5');
INSERT INTO `jipu_area` VALUES ('520424', '关岭布依族苗族自治县', '520400', '6');
INSERT INTO `jipu_area` VALUES ('520425', '紫云苗族布依族自治县', '520400', '7');
INSERT INTO `jipu_area` VALUES ('522200', '铜仁地区', '520000', '5');
INSERT INTO `jipu_area` VALUES ('522201', '铜仁市', '522200', '1');
INSERT INTO `jipu_area` VALUES ('522222', '江口县', '522200', '2');
INSERT INTO `jipu_area` VALUES ('522223', '玉屏侗族自治县', '522200', '3');
INSERT INTO `jipu_area` VALUES ('522224', '石阡县', '522200', '4');
INSERT INTO `jipu_area` VALUES ('522225', '思南县', '522200', '5');
INSERT INTO `jipu_area` VALUES ('522226', '印江土家族苗族自治县', '522200', '6');
INSERT INTO `jipu_area` VALUES ('522227', '德江县', '522200', '7');
INSERT INTO `jipu_area` VALUES ('522228', '沿河土家族自治县', '522200', '8');
INSERT INTO `jipu_area` VALUES ('522229', '松桃苗族自治县', '522200', '9');
INSERT INTO `jipu_area` VALUES ('522230', '万山特区', '522200', '10');
INSERT INTO `jipu_area` VALUES ('522300', '黔西南布依族苗族自治州', '520000', '6');
INSERT INTO `jipu_area` VALUES ('522301', '兴义市', '522300', '1');
INSERT INTO `jipu_area` VALUES ('522322', '兴仁县', '522300', '2');
INSERT INTO `jipu_area` VALUES ('522323', '普安县', '522300', '3');
INSERT INTO `jipu_area` VALUES ('522324', '晴隆县', '522300', '4');
INSERT INTO `jipu_area` VALUES ('522325', '贞丰县', '522300', '5');
INSERT INTO `jipu_area` VALUES ('522326', '望谟县', '522300', '6');
INSERT INTO `jipu_area` VALUES ('522327', '册亨县', '522300', '7');
INSERT INTO `jipu_area` VALUES ('522328', '安龙县', '522300', '8');
INSERT INTO `jipu_area` VALUES ('522400', '毕节地区', '520000', '7');
INSERT INTO `jipu_area` VALUES ('522401', '毕节市', '522400', '1');
INSERT INTO `jipu_area` VALUES ('522422', '大方县', '522400', '2');
INSERT INTO `jipu_area` VALUES ('522423', '黔西县', '522400', '3');
INSERT INTO `jipu_area` VALUES ('522424', '金沙县', '522400', '4');
INSERT INTO `jipu_area` VALUES ('522425', '织金县', '522400', '5');
INSERT INTO `jipu_area` VALUES ('522426', '纳雍县', '522400', '6');
INSERT INTO `jipu_area` VALUES ('522427', '威宁彝族回族苗族自治县', '522400', '7');
INSERT INTO `jipu_area` VALUES ('522428', '赫章县', '522400', '8');
INSERT INTO `jipu_area` VALUES ('522600', '黔东南苗族侗族自治州', '520000', '8');
INSERT INTO `jipu_area` VALUES ('522601', '凯里市', '522600', '1');
INSERT INTO `jipu_area` VALUES ('522622', '黄平县', '522600', '2');
INSERT INTO `jipu_area` VALUES ('522623', '施秉县', '522600', '3');
INSERT INTO `jipu_area` VALUES ('522624', '三穗县', '522600', '4');
INSERT INTO `jipu_area` VALUES ('522625', '镇远县', '522600', '5');
INSERT INTO `jipu_area` VALUES ('522626', '岑巩县', '522600', '6');
INSERT INTO `jipu_area` VALUES ('522627', '天柱县', '522600', '7');
INSERT INTO `jipu_area` VALUES ('522628', '锦屏县', '522600', '8');
INSERT INTO `jipu_area` VALUES ('522629', '剑河县', '522600', '9');
INSERT INTO `jipu_area` VALUES ('522630', '台江县', '522600', '10');
INSERT INTO `jipu_area` VALUES ('522631', '黎平县', '522600', '11');
INSERT INTO `jipu_area` VALUES ('522632', '榕江县', '522600', '12');
INSERT INTO `jipu_area` VALUES ('522633', '从江县', '522600', '13');
INSERT INTO `jipu_area` VALUES ('522634', '雷山县', '522600', '14');
INSERT INTO `jipu_area` VALUES ('522635', '麻江县', '522600', '15');
INSERT INTO `jipu_area` VALUES ('522636', '丹寨县', '522600', '16');
INSERT INTO `jipu_area` VALUES ('522700', '黔南布依族苗族自治州', '520000', '9');
INSERT INTO `jipu_area` VALUES ('522701', '都匀市', '522700', '1');
INSERT INTO `jipu_area` VALUES ('522702', '福泉市', '522700', '2');
INSERT INTO `jipu_area` VALUES ('522722', '荔波县', '522700', '3');
INSERT INTO `jipu_area` VALUES ('522723', '贵定县', '522700', '4');
INSERT INTO `jipu_area` VALUES ('522725', '瓮安县', '522700', '5');
INSERT INTO `jipu_area` VALUES ('522726', '独山县', '522700', '6');
INSERT INTO `jipu_area` VALUES ('522727', '平塘县', '522700', '7');
INSERT INTO `jipu_area` VALUES ('522728', '罗甸县', '522700', '8');
INSERT INTO `jipu_area` VALUES ('522729', '长顺县', '522700', '9');
INSERT INTO `jipu_area` VALUES ('522730', '龙里县', '522700', '10');
INSERT INTO `jipu_area` VALUES ('522731', '惠水县', '522700', '11');
INSERT INTO `jipu_area` VALUES ('522732', '三都水族自治县', '522700', '12');
INSERT INTO `jipu_area` VALUES ('530000', '云南省', '0', '25');
INSERT INTO `jipu_area` VALUES ('530100', '昆明市', '530000', '1');
INSERT INTO `jipu_area` VALUES ('530101', '市辖区', '530100', '1');
INSERT INTO `jipu_area` VALUES ('530102', '五华区', '530100', '2');
INSERT INTO `jipu_area` VALUES ('530103', '盘龙区', '530100', '3');
INSERT INTO `jipu_area` VALUES ('530111', '官渡区', '530100', '4');
INSERT INTO `jipu_area` VALUES ('530112', '西山区', '530100', '5');
INSERT INTO `jipu_area` VALUES ('530113', '东川区', '530100', '6');
INSERT INTO `jipu_area` VALUES ('530121', '呈贡县', '530100', '7');
INSERT INTO `jipu_area` VALUES ('530122', '晋宁县', '530100', '8');
INSERT INTO `jipu_area` VALUES ('530124', '富民县', '530100', '9');
INSERT INTO `jipu_area` VALUES ('530125', '宜良县', '530100', '10');
INSERT INTO `jipu_area` VALUES ('530126', '石林彝族自治县', '530100', '11');
INSERT INTO `jipu_area` VALUES ('530127', '嵩明县', '530100', '12');
INSERT INTO `jipu_area` VALUES ('530128', '禄劝彝族苗族自治县', '530100', '13');
INSERT INTO `jipu_area` VALUES ('530129', '寻甸回族彝族自治县', '530100', '14');
INSERT INTO `jipu_area` VALUES ('530181', '安宁市', '530100', '15');
INSERT INTO `jipu_area` VALUES ('530300', '曲靖市', '530000', '2');
INSERT INTO `jipu_area` VALUES ('530301', '市辖区', '530300', '1');
INSERT INTO `jipu_area` VALUES ('530302', '麒麟区', '530300', '2');
INSERT INTO `jipu_area` VALUES ('530321', '马龙县', '530300', '3');
INSERT INTO `jipu_area` VALUES ('530322', '陆良县', '530300', '4');
INSERT INTO `jipu_area` VALUES ('530323', '师宗县', '530300', '5');
INSERT INTO `jipu_area` VALUES ('530324', '罗平县', '530300', '6');
INSERT INTO `jipu_area` VALUES ('530325', '富源县', '530300', '7');
INSERT INTO `jipu_area` VALUES ('530326', '会泽县', '530300', '8');
INSERT INTO `jipu_area` VALUES ('530328', '沾益县', '530300', '9');
INSERT INTO `jipu_area` VALUES ('530381', '宣威市', '530300', '10');
INSERT INTO `jipu_area` VALUES ('530400', '玉溪市', '530000', '3');
INSERT INTO `jipu_area` VALUES ('530401', '市辖区', '530400', '1');
INSERT INTO `jipu_area` VALUES ('530402', '红塔区', '530400', '2');
INSERT INTO `jipu_area` VALUES ('530421', '江川县', '530400', '3');
INSERT INTO `jipu_area` VALUES ('530422', '澄江县', '530400', '4');
INSERT INTO `jipu_area` VALUES ('530423', '通海县', '530400', '5');
INSERT INTO `jipu_area` VALUES ('530424', '华宁县', '530400', '6');
INSERT INTO `jipu_area` VALUES ('530425', '易门县', '530400', '7');
INSERT INTO `jipu_area` VALUES ('530426', '峨山彝族自治县', '530400', '8');
INSERT INTO `jipu_area` VALUES ('530427', '新平彝族傣族自治县', '530400', '9');
INSERT INTO `jipu_area` VALUES ('530428', '元江哈尼族彝族傣族自治县', '530400', '10');
INSERT INTO `jipu_area` VALUES ('530500', '保山市', '530000', '4');
INSERT INTO `jipu_area` VALUES ('530501', '市辖区', '530500', '1');
INSERT INTO `jipu_area` VALUES ('530502', '隆阳区', '530500', '2');
INSERT INTO `jipu_area` VALUES ('530521', '施甸县', '530500', '3');
INSERT INTO `jipu_area` VALUES ('530522', '腾冲县', '530500', '4');
INSERT INTO `jipu_area` VALUES ('530523', '龙陵县', '530500', '5');
INSERT INTO `jipu_area` VALUES ('530524', '昌宁县', '530500', '6');
INSERT INTO `jipu_area` VALUES ('530600', '昭通市', '530000', '5');
INSERT INTO `jipu_area` VALUES ('530601', '市辖区', '530600', '1');
INSERT INTO `jipu_area` VALUES ('530602', '昭阳区', '530600', '2');
INSERT INTO `jipu_area` VALUES ('530621', '鲁甸县', '530600', '3');
INSERT INTO `jipu_area` VALUES ('530622', '巧家县', '530600', '4');
INSERT INTO `jipu_area` VALUES ('530623', '盐津县', '530600', '5');
INSERT INTO `jipu_area` VALUES ('530624', '大关县', '530600', '6');
INSERT INTO `jipu_area` VALUES ('530625', '永善县', '530600', '7');
INSERT INTO `jipu_area` VALUES ('530626', '绥江县', '530600', '8');
INSERT INTO `jipu_area` VALUES ('530627', '镇雄县', '530600', '9');
INSERT INTO `jipu_area` VALUES ('530628', '彝良县', '530600', '10');
INSERT INTO `jipu_area` VALUES ('530629', '威信县', '530600', '11');
INSERT INTO `jipu_area` VALUES ('530630', '水富县', '530600', '12');
INSERT INTO `jipu_area` VALUES ('530700', '丽江市', '530000', '6');
INSERT INTO `jipu_area` VALUES ('530701', '市辖区', '530700', '1');
INSERT INTO `jipu_area` VALUES ('530702', '古城区', '530700', '2');
INSERT INTO `jipu_area` VALUES ('530721', '玉龙纳西族自治县', '530700', '3');
INSERT INTO `jipu_area` VALUES ('530722', '永胜县', '530700', '4');
INSERT INTO `jipu_area` VALUES ('530723', '华坪县', '530700', '5');
INSERT INTO `jipu_area` VALUES ('530724', '宁蒗彝族自治县', '530700', '6');
INSERT INTO `jipu_area` VALUES ('530800', '思茅市', '530000', '7');
INSERT INTO `jipu_area` VALUES ('530801', '市辖区', '530800', '1');
INSERT INTO `jipu_area` VALUES ('530802', '翠云区', '530800', '2');
INSERT INTO `jipu_area` VALUES ('530821', '普洱哈尼族彝族自治县', '530800', '3');
INSERT INTO `jipu_area` VALUES ('530822', '墨江哈尼族自治县', '530800', '4');
INSERT INTO `jipu_area` VALUES ('530823', '景东彝族自治县', '530800', '5');
INSERT INTO `jipu_area` VALUES ('530824', '景谷傣族彝族自治县', '530800', '6');
INSERT INTO `jipu_area` VALUES ('530825', '镇沅彝族哈尼族拉祜族自治县', '530800', '7');
INSERT INTO `jipu_area` VALUES ('530826', '江城哈尼族彝族自治县', '530800', '8');
INSERT INTO `jipu_area` VALUES ('530827', '孟连傣族拉祜族佤族自治县', '530800', '9');
INSERT INTO `jipu_area` VALUES ('530828', '澜沧拉祜族自治县', '530800', '10');
INSERT INTO `jipu_area` VALUES ('530829', '西盟佤族自治县', '530800', '11');
INSERT INTO `jipu_area` VALUES ('530900', '临沧市', '530000', '8');
INSERT INTO `jipu_area` VALUES ('530901', '市辖区', '530900', '1');
INSERT INTO `jipu_area` VALUES ('530902', '临翔区', '530900', '2');
INSERT INTO `jipu_area` VALUES ('530921', '凤庆县', '530900', '3');
INSERT INTO `jipu_area` VALUES ('530922', '云　县', '530900', '4');
INSERT INTO `jipu_area` VALUES ('530923', '永德县', '530900', '5');
INSERT INTO `jipu_area` VALUES ('530924', '镇康县', '530900', '6');
INSERT INTO `jipu_area` VALUES ('530925', '双江拉祜族佤族布朗族傣族自治县', '530900', '7');
INSERT INTO `jipu_area` VALUES ('530926', '耿马傣族佤族自治县', '530900', '8');
INSERT INTO `jipu_area` VALUES ('530927', '沧源佤族自治县', '530900', '9');
INSERT INTO `jipu_area` VALUES ('532300', '楚雄彝族自治州', '530000', '9');
INSERT INTO `jipu_area` VALUES ('532301', '楚雄市', '532300', '1');
INSERT INTO `jipu_area` VALUES ('532322', '双柏县', '532300', '2');
INSERT INTO `jipu_area` VALUES ('532323', '牟定县', '532300', '3');
INSERT INTO `jipu_area` VALUES ('532324', '南华县', '532300', '4');
INSERT INTO `jipu_area` VALUES ('532325', '姚安县', '532300', '5');
INSERT INTO `jipu_area` VALUES ('532326', '大姚县', '532300', '6');
INSERT INTO `jipu_area` VALUES ('532327', '永仁县', '532300', '7');
INSERT INTO `jipu_area` VALUES ('532328', '元谋县', '532300', '8');
INSERT INTO `jipu_area` VALUES ('532329', '武定县', '532300', '9');
INSERT INTO `jipu_area` VALUES ('532331', '禄丰县', '532300', '10');
INSERT INTO `jipu_area` VALUES ('532500', '红河哈尼族彝族自治州', '530000', '10');
INSERT INTO `jipu_area` VALUES ('532501', '个旧市', '532500', '1');
INSERT INTO `jipu_area` VALUES ('532502', '开远市', '532500', '2');
INSERT INTO `jipu_area` VALUES ('532522', '蒙自县', '532500', '3');
INSERT INTO `jipu_area` VALUES ('532523', '屏边苗族自治县', '532500', '4');
INSERT INTO `jipu_area` VALUES ('532524', '建水县', '532500', '5');
INSERT INTO `jipu_area` VALUES ('532525', '石屏县', '532500', '6');
INSERT INTO `jipu_area` VALUES ('532526', '弥勒县', '532500', '7');
INSERT INTO `jipu_area` VALUES ('532527', '泸西县', '532500', '8');
INSERT INTO `jipu_area` VALUES ('532528', '元阳县', '532500', '9');
INSERT INTO `jipu_area` VALUES ('532529', '红河县', '532500', '10');
INSERT INTO `jipu_area` VALUES ('532530', '金平苗族瑶族傣族自治县', '532500', '11');
INSERT INTO `jipu_area` VALUES ('532531', '绿春县', '532500', '12');
INSERT INTO `jipu_area` VALUES ('532532', '河口瑶族自治县', '532500', '13');
INSERT INTO `jipu_area` VALUES ('532600', '文山壮族苗族自治州', '530000', '11');
INSERT INTO `jipu_area` VALUES ('532621', '文山县', '532600', '1');
INSERT INTO `jipu_area` VALUES ('532622', '砚山县', '532600', '2');
INSERT INTO `jipu_area` VALUES ('532623', '西畴县', '532600', '3');
INSERT INTO `jipu_area` VALUES ('532624', '麻栗坡县', '532600', '4');
INSERT INTO `jipu_area` VALUES ('532625', '马关县', '532600', '5');
INSERT INTO `jipu_area` VALUES ('532626', '丘北县', '532600', '6');
INSERT INTO `jipu_area` VALUES ('532627', '广南县', '532600', '7');
INSERT INTO `jipu_area` VALUES ('532628', '富宁县', '532600', '8');
INSERT INTO `jipu_area` VALUES ('532800', '西双版纳傣族自治州', '530000', '12');
INSERT INTO `jipu_area` VALUES ('532801', '景洪市', '532800', '1');
INSERT INTO `jipu_area` VALUES ('532822', '勐海县', '532800', '2');
INSERT INTO `jipu_area` VALUES ('532823', '勐腊县', '532800', '3');
INSERT INTO `jipu_area` VALUES ('532900', '大理白族自治州', '530000', '13');
INSERT INTO `jipu_area` VALUES ('532901', '大理市', '532900', '1');
INSERT INTO `jipu_area` VALUES ('532922', '漾濞彝族自治县', '532900', '2');
INSERT INTO `jipu_area` VALUES ('532923', '祥云县', '532900', '3');
INSERT INTO `jipu_area` VALUES ('532924', '宾川县', '532900', '4');
INSERT INTO `jipu_area` VALUES ('532925', '弥渡县', '532900', '5');
INSERT INTO `jipu_area` VALUES ('532926', '南涧彝族自治县', '532900', '6');
INSERT INTO `jipu_area` VALUES ('532927', '巍山彝族回族自治县', '532900', '7');
INSERT INTO `jipu_area` VALUES ('532928', '永平县', '532900', '8');
INSERT INTO `jipu_area` VALUES ('532929', '云龙县', '532900', '9');
INSERT INTO `jipu_area` VALUES ('532930', '洱源县', '532900', '10');
INSERT INTO `jipu_area` VALUES ('532931', '剑川县', '532900', '11');
INSERT INTO `jipu_area` VALUES ('532932', '鹤庆县', '532900', '12');
INSERT INTO `jipu_area` VALUES ('533100', '德宏傣族景颇族自治州', '530000', '14');
INSERT INTO `jipu_area` VALUES ('533102', '瑞丽市', '533100', '1');
INSERT INTO `jipu_area` VALUES ('533103', '潞西市', '533100', '2');
INSERT INTO `jipu_area` VALUES ('533122', '梁河县', '533100', '3');
INSERT INTO `jipu_area` VALUES ('533123', '盈江县', '533100', '4');
INSERT INTO `jipu_area` VALUES ('533124', '陇川县', '533100', '5');
INSERT INTO `jipu_area` VALUES ('533300', '怒江傈僳族自治州', '530000', '15');
INSERT INTO `jipu_area` VALUES ('533321', '泸水县', '533300', '1');
INSERT INTO `jipu_area` VALUES ('533323', '福贡县', '533300', '2');
INSERT INTO `jipu_area` VALUES ('533324', '贡山独龙族怒族自治县', '533300', '3');
INSERT INTO `jipu_area` VALUES ('533325', '兰坪白族普米族自治县', '533300', '4');
INSERT INTO `jipu_area` VALUES ('533400', '迪庆藏族自治州', '530000', '16');
INSERT INTO `jipu_area` VALUES ('533421', '香格里拉县', '533400', '1');
INSERT INTO `jipu_area` VALUES ('533422', '德钦县', '533400', '2');
INSERT INTO `jipu_area` VALUES ('533423', '维西傈僳族自治县', '533400', '3');
INSERT INTO `jipu_area` VALUES ('540000', '西　藏', '0', '26');
INSERT INTO `jipu_area` VALUES ('540100', '拉萨市', '540000', '1');
INSERT INTO `jipu_area` VALUES ('540101', '市辖区', '540100', '1');
INSERT INTO `jipu_area` VALUES ('540102', '城关区', '540100', '2');
INSERT INTO `jipu_area` VALUES ('540121', '林周县', '540100', '3');
INSERT INTO `jipu_area` VALUES ('540122', '当雄县', '540100', '4');
INSERT INTO `jipu_area` VALUES ('540123', '尼木县', '540100', '5');
INSERT INTO `jipu_area` VALUES ('540124', '曲水县', '540100', '6');
INSERT INTO `jipu_area` VALUES ('540125', '堆龙德庆县', '540100', '7');
INSERT INTO `jipu_area` VALUES ('540126', '达孜县', '540100', '8');
INSERT INTO `jipu_area` VALUES ('540127', '墨竹工卡县', '540100', '9');
INSERT INTO `jipu_area` VALUES ('542100', '昌都地区', '540000', '2');
INSERT INTO `jipu_area` VALUES ('542121', '昌都县', '542100', '1');
INSERT INTO `jipu_area` VALUES ('542122', '江达县', '542100', '2');
INSERT INTO `jipu_area` VALUES ('542123', '贡觉县', '542100', '3');
INSERT INTO `jipu_area` VALUES ('542124', '类乌齐县', '542100', '4');
INSERT INTO `jipu_area` VALUES ('542125', '丁青县', '542100', '5');
INSERT INTO `jipu_area` VALUES ('542126', '察雅县', '542100', '6');
INSERT INTO `jipu_area` VALUES ('542127', '八宿县', '542100', '7');
INSERT INTO `jipu_area` VALUES ('542128', '左贡县', '542100', '8');
INSERT INTO `jipu_area` VALUES ('542129', '芒康县', '542100', '9');
INSERT INTO `jipu_area` VALUES ('542132', '洛隆县', '542100', '10');
INSERT INTO `jipu_area` VALUES ('542133', '边坝县', '542100', '11');
INSERT INTO `jipu_area` VALUES ('542200', '山南地区', '540000', '3');
INSERT INTO `jipu_area` VALUES ('542221', '乃东县', '542200', '1');
INSERT INTO `jipu_area` VALUES ('542222', '扎囊县', '542200', '2');
INSERT INTO `jipu_area` VALUES ('542223', '贡嘎县', '542200', '3');
INSERT INTO `jipu_area` VALUES ('542224', '桑日县', '542200', '4');
INSERT INTO `jipu_area` VALUES ('542225', '琼结县', '542200', '5');
INSERT INTO `jipu_area` VALUES ('542226', '曲松县', '542200', '6');
INSERT INTO `jipu_area` VALUES ('542227', '措美县', '542200', '7');
INSERT INTO `jipu_area` VALUES ('542228', '洛扎县', '542200', '8');
INSERT INTO `jipu_area` VALUES ('542229', '加查县', '542200', '9');
INSERT INTO `jipu_area` VALUES ('542231', '隆子县', '542200', '10');
INSERT INTO `jipu_area` VALUES ('542232', '错那县', '542200', '11');
INSERT INTO `jipu_area` VALUES ('542233', '浪卡子县', '542200', '12');
INSERT INTO `jipu_area` VALUES ('542300', '日喀则地区', '540000', '4');
INSERT INTO `jipu_area` VALUES ('542301', '日喀则市', '542300', '1');
INSERT INTO `jipu_area` VALUES ('542322', '南木林县', '542300', '2');
INSERT INTO `jipu_area` VALUES ('542323', '江孜县', '542300', '3');
INSERT INTO `jipu_area` VALUES ('542324', '定日县', '542300', '4');
INSERT INTO `jipu_area` VALUES ('542325', '萨迦县', '542300', '5');
INSERT INTO `jipu_area` VALUES ('542326', '拉孜县', '542300', '6');
INSERT INTO `jipu_area` VALUES ('542327', '昂仁县', '542300', '7');
INSERT INTO `jipu_area` VALUES ('542328', '谢通门县', '542300', '8');
INSERT INTO `jipu_area` VALUES ('542329', '白朗县', '542300', '9');
INSERT INTO `jipu_area` VALUES ('542330', '仁布县', '542300', '10');
INSERT INTO `jipu_area` VALUES ('542331', '康马县', '542300', '11');
INSERT INTO `jipu_area` VALUES ('542332', '定结县', '542300', '12');
INSERT INTO `jipu_area` VALUES ('542333', '仲巴县', '542300', '13');
INSERT INTO `jipu_area` VALUES ('542334', '亚东县', '542300', '14');
INSERT INTO `jipu_area` VALUES ('542335', '吉隆县', '542300', '15');
INSERT INTO `jipu_area` VALUES ('542336', '聂拉木县', '542300', '16');
INSERT INTO `jipu_area` VALUES ('542337', '萨嘎县', '542300', '17');
INSERT INTO `jipu_area` VALUES ('542338', '岗巴县', '542300', '18');
INSERT INTO `jipu_area` VALUES ('542400', '那曲地区', '540000', '5');
INSERT INTO `jipu_area` VALUES ('542421', '那曲县', '542400', '1');
INSERT INTO `jipu_area` VALUES ('542422', '嘉黎县', '542400', '2');
INSERT INTO `jipu_area` VALUES ('542423', '比如县', '542400', '3');
INSERT INTO `jipu_area` VALUES ('542424', '聂荣县', '542400', '4');
INSERT INTO `jipu_area` VALUES ('542425', '安多县', '542400', '5');
INSERT INTO `jipu_area` VALUES ('542426', '申扎县', '542400', '6');
INSERT INTO `jipu_area` VALUES ('542427', '索　县', '542400', '7');
INSERT INTO `jipu_area` VALUES ('542428', '班戈县', '542400', '8');
INSERT INTO `jipu_area` VALUES ('542429', '巴青县', '542400', '9');
INSERT INTO `jipu_area` VALUES ('542430', '尼玛县', '542400', '10');
INSERT INTO `jipu_area` VALUES ('542500', '阿里地区', '540000', '6');
INSERT INTO `jipu_area` VALUES ('542521', '普兰县', '542500', '1');
INSERT INTO `jipu_area` VALUES ('542522', '札达县', '542500', '2');
INSERT INTO `jipu_area` VALUES ('542523', '噶尔县', '542500', '3');
INSERT INTO `jipu_area` VALUES ('542524', '日土县', '542500', '4');
INSERT INTO `jipu_area` VALUES ('542525', '革吉县', '542500', '5');
INSERT INTO `jipu_area` VALUES ('542526', '改则县', '542500', '6');
INSERT INTO `jipu_area` VALUES ('542527', '措勤县', '542500', '7');
INSERT INTO `jipu_area` VALUES ('542600', '林芝地区', '540000', '7');
INSERT INTO `jipu_area` VALUES ('542621', '林芝县', '542600', '1');
INSERT INTO `jipu_area` VALUES ('542622', '工布江达县', '542600', '2');
INSERT INTO `jipu_area` VALUES ('542623', '米林县', '542600', '3');
INSERT INTO `jipu_area` VALUES ('542624', '墨脱县', '542600', '4');
INSERT INTO `jipu_area` VALUES ('542625', '波密县', '542600', '5');
INSERT INTO `jipu_area` VALUES ('542626', '察隅县', '542600', '6');
INSERT INTO `jipu_area` VALUES ('542627', '朗　县', '542600', '7');
INSERT INTO `jipu_area` VALUES ('610000', '陕西省', '0', '27');
INSERT INTO `jipu_area` VALUES ('610100', '西安市', '610000', '1');
INSERT INTO `jipu_area` VALUES ('610101', '市辖区', '610100', '1');
INSERT INTO `jipu_area` VALUES ('610102', '新城区', '610100', '2');
INSERT INTO `jipu_area` VALUES ('610103', '碑林区', '610100', '3');
INSERT INTO `jipu_area` VALUES ('610104', '莲湖区', '610100', '4');
INSERT INTO `jipu_area` VALUES ('610111', '灞桥区', '610100', '5');
INSERT INTO `jipu_area` VALUES ('610112', '未央区', '610100', '6');
INSERT INTO `jipu_area` VALUES ('610113', '雁塔区', '610100', '7');
INSERT INTO `jipu_area` VALUES ('610114', '阎良区', '610100', '8');
INSERT INTO `jipu_area` VALUES ('610115', '临潼区', '610100', '9');
INSERT INTO `jipu_area` VALUES ('610116', '长安区', '610100', '10');
INSERT INTO `jipu_area` VALUES ('610122', '蓝田县', '610100', '11');
INSERT INTO `jipu_area` VALUES ('610124', '周至县', '610100', '12');
INSERT INTO `jipu_area` VALUES ('610125', '户　县', '610100', '13');
INSERT INTO `jipu_area` VALUES ('610126', '高陵县', '610100', '14');
INSERT INTO `jipu_area` VALUES ('610200', '铜川市', '610000', '2');
INSERT INTO `jipu_area` VALUES ('610201', '市辖区', '610200', '1');
INSERT INTO `jipu_area` VALUES ('610202', '王益区', '610200', '2');
INSERT INTO `jipu_area` VALUES ('610203', '印台区', '610200', '3');
INSERT INTO `jipu_area` VALUES ('610204', '耀州区', '610200', '4');
INSERT INTO `jipu_area` VALUES ('610222', '宜君县', '610200', '5');
INSERT INTO `jipu_area` VALUES ('610300', '宝鸡市', '610000', '3');
INSERT INTO `jipu_area` VALUES ('610301', '市辖区', '610300', '1');
INSERT INTO `jipu_area` VALUES ('610302', '渭滨区', '610300', '2');
INSERT INTO `jipu_area` VALUES ('610303', '金台区', '610300', '3');
INSERT INTO `jipu_area` VALUES ('610304', '陈仓区', '610300', '4');
INSERT INTO `jipu_area` VALUES ('610322', '凤翔县', '610300', '5');
INSERT INTO `jipu_area` VALUES ('610323', '岐山县', '610300', '6');
INSERT INTO `jipu_area` VALUES ('610324', '扶风县', '610300', '7');
INSERT INTO `jipu_area` VALUES ('610326', '眉　县', '610300', '8');
INSERT INTO `jipu_area` VALUES ('610327', '陇　县', '610300', '9');
INSERT INTO `jipu_area` VALUES ('610328', '千阳县', '610300', '10');
INSERT INTO `jipu_area` VALUES ('610329', '麟游县', '610300', '11');
INSERT INTO `jipu_area` VALUES ('610330', '凤　县', '610300', '12');
INSERT INTO `jipu_area` VALUES ('610331', '太白县', '610300', '13');
INSERT INTO `jipu_area` VALUES ('610400', '咸阳市', '610000', '4');
INSERT INTO `jipu_area` VALUES ('610401', '市辖区', '610400', '1');
INSERT INTO `jipu_area` VALUES ('610402', '秦都区', '610400', '2');
INSERT INTO `jipu_area` VALUES ('610403', '杨凌区', '610400', '3');
INSERT INTO `jipu_area` VALUES ('610404', '渭城区', '610400', '4');
INSERT INTO `jipu_area` VALUES ('610422', '三原县', '610400', '5');
INSERT INTO `jipu_area` VALUES ('610423', '泾阳县', '610400', '6');
INSERT INTO `jipu_area` VALUES ('610424', '乾　县', '610400', '7');
INSERT INTO `jipu_area` VALUES ('610425', '礼泉县', '610400', '8');
INSERT INTO `jipu_area` VALUES ('610426', '永寿县', '610400', '9');
INSERT INTO `jipu_area` VALUES ('610427', '彬　县', '610400', '10');
INSERT INTO `jipu_area` VALUES ('610428', '长武县', '610400', '11');
INSERT INTO `jipu_area` VALUES ('610429', '旬邑县', '610400', '12');
INSERT INTO `jipu_area` VALUES ('610430', '淳化县', '610400', '13');
INSERT INTO `jipu_area` VALUES ('610431', '武功县', '610400', '14');
INSERT INTO `jipu_area` VALUES ('610481', '兴平市', '610400', '15');
INSERT INTO `jipu_area` VALUES ('610500', '渭南市', '610000', '5');
INSERT INTO `jipu_area` VALUES ('610501', '市辖区', '610500', '1');
INSERT INTO `jipu_area` VALUES ('610502', '临渭区', '610500', '2');
INSERT INTO `jipu_area` VALUES ('610521', '华　县', '610500', '3');
INSERT INTO `jipu_area` VALUES ('610522', '潼关县', '610500', '4');
INSERT INTO `jipu_area` VALUES ('610523', '大荔县', '610500', '5');
INSERT INTO `jipu_area` VALUES ('610524', '合阳县', '610500', '6');
INSERT INTO `jipu_area` VALUES ('610525', '澄城县', '610500', '7');
INSERT INTO `jipu_area` VALUES ('610526', '蒲城县', '610500', '8');
INSERT INTO `jipu_area` VALUES ('610527', '白水县', '610500', '9');
INSERT INTO `jipu_area` VALUES ('610528', '富平县', '610500', '10');
INSERT INTO `jipu_area` VALUES ('610581', '韩城市', '610500', '11');
INSERT INTO `jipu_area` VALUES ('610582', '华阴市', '610500', '12');
INSERT INTO `jipu_area` VALUES ('610600', '延安市', '610000', '6');
INSERT INTO `jipu_area` VALUES ('610601', '市辖区', '610600', '1');
INSERT INTO `jipu_area` VALUES ('610602', '宝塔区', '610600', '2');
INSERT INTO `jipu_area` VALUES ('610621', '延长县', '610600', '3');
INSERT INTO `jipu_area` VALUES ('610622', '延川县', '610600', '4');
INSERT INTO `jipu_area` VALUES ('610623', '子长县', '610600', '5');
INSERT INTO `jipu_area` VALUES ('610624', '安塞县', '610600', '6');
INSERT INTO `jipu_area` VALUES ('610625', '志丹县', '610600', '7');
INSERT INTO `jipu_area` VALUES ('610626', '吴旗县', '610600', '8');
INSERT INTO `jipu_area` VALUES ('610627', '甘泉县', '610600', '9');
INSERT INTO `jipu_area` VALUES ('610628', '富　县', '610600', '10');
INSERT INTO `jipu_area` VALUES ('610629', '洛川县', '610600', '11');
INSERT INTO `jipu_area` VALUES ('610630', '宜川县', '610600', '12');
INSERT INTO `jipu_area` VALUES ('610631', '黄龙县', '610600', '13');
INSERT INTO `jipu_area` VALUES ('610632', '黄陵县', '610600', '14');
INSERT INTO `jipu_area` VALUES ('610700', '汉中市', '610000', '7');
INSERT INTO `jipu_area` VALUES ('610701', '市辖区', '610700', '1');
INSERT INTO `jipu_area` VALUES ('610702', '汉台区', '610700', '2');
INSERT INTO `jipu_area` VALUES ('610721', '南郑县', '610700', '3');
INSERT INTO `jipu_area` VALUES ('610722', '城固县', '610700', '4');
INSERT INTO `jipu_area` VALUES ('610723', '洋　县', '610700', '5');
INSERT INTO `jipu_area` VALUES ('610724', '西乡县', '610700', '6');
INSERT INTO `jipu_area` VALUES ('610725', '勉　县', '610700', '7');
INSERT INTO `jipu_area` VALUES ('610726', '宁强县', '610700', '8');
INSERT INTO `jipu_area` VALUES ('610727', '略阳县', '610700', '9');
INSERT INTO `jipu_area` VALUES ('610728', '镇巴县', '610700', '10');
INSERT INTO `jipu_area` VALUES ('610729', '留坝县', '610700', '11');
INSERT INTO `jipu_area` VALUES ('610730', '佛坪县', '610700', '12');
INSERT INTO `jipu_area` VALUES ('610800', '榆林市', '610000', '8');
INSERT INTO `jipu_area` VALUES ('610801', '市辖区', '610800', '1');
INSERT INTO `jipu_area` VALUES ('610802', '榆阳区', '610800', '2');
INSERT INTO `jipu_area` VALUES ('610821', '神木县', '610800', '3');
INSERT INTO `jipu_area` VALUES ('610822', '府谷县', '610800', '4');
INSERT INTO `jipu_area` VALUES ('610823', '横山县', '610800', '5');
INSERT INTO `jipu_area` VALUES ('610824', '靖边县', '610800', '6');
INSERT INTO `jipu_area` VALUES ('610825', '定边县', '610800', '7');
INSERT INTO `jipu_area` VALUES ('610826', '绥德县', '610800', '8');
INSERT INTO `jipu_area` VALUES ('610827', '米脂县', '610800', '9');
INSERT INTO `jipu_area` VALUES ('610828', '佳　县', '610800', '10');
INSERT INTO `jipu_area` VALUES ('610829', '吴堡县', '610800', '11');
INSERT INTO `jipu_area` VALUES ('610830', '清涧县', '610800', '12');
INSERT INTO `jipu_area` VALUES ('610831', '子洲县', '610800', '13');
INSERT INTO `jipu_area` VALUES ('610900', '安康市', '610000', '9');
INSERT INTO `jipu_area` VALUES ('610901', '市辖区', '610900', '1');
INSERT INTO `jipu_area` VALUES ('610902', '汉滨区', '610900', '2');
INSERT INTO `jipu_area` VALUES ('610921', '汉阴县', '610900', '3');
INSERT INTO `jipu_area` VALUES ('610922', '石泉县', '610900', '4');
INSERT INTO `jipu_area` VALUES ('610923', '宁陕县', '610900', '5');
INSERT INTO `jipu_area` VALUES ('610924', '紫阳县', '610900', '6');
INSERT INTO `jipu_area` VALUES ('610925', '岚皋县', '610900', '7');
INSERT INTO `jipu_area` VALUES ('610926', '平利县', '610900', '8');
INSERT INTO `jipu_area` VALUES ('610927', '镇坪县', '610900', '9');
INSERT INTO `jipu_area` VALUES ('610928', '旬阳县', '610900', '10');
INSERT INTO `jipu_area` VALUES ('610929', '白河县', '610900', '11');
INSERT INTO `jipu_area` VALUES ('611000', '商洛市', '610000', '10');
INSERT INTO `jipu_area` VALUES ('611001', '市辖区', '611000', '1');
INSERT INTO `jipu_area` VALUES ('611002', '商州区', '611000', '2');
INSERT INTO `jipu_area` VALUES ('611021', '洛南县', '611000', '3');
INSERT INTO `jipu_area` VALUES ('611022', '丹凤县', '611000', '4');
INSERT INTO `jipu_area` VALUES ('611023', '商南县', '611000', '5');
INSERT INTO `jipu_area` VALUES ('611024', '山阳县', '611000', '6');
INSERT INTO `jipu_area` VALUES ('611025', '镇安县', '611000', '7');
INSERT INTO `jipu_area` VALUES ('611026', '柞水县', '611000', '8');
INSERT INTO `jipu_area` VALUES ('620000', '甘肃省', '0', '28');
INSERT INTO `jipu_area` VALUES ('620100', '兰州市', '620000', '1');
INSERT INTO `jipu_area` VALUES ('620101', '市辖区', '620100', '1');
INSERT INTO `jipu_area` VALUES ('620102', '城关区', '620100', '2');
INSERT INTO `jipu_area` VALUES ('620103', '七里河区', '620100', '3');
INSERT INTO `jipu_area` VALUES ('620104', '西固区', '620100', '4');
INSERT INTO `jipu_area` VALUES ('620105', '安宁区', '620100', '5');
INSERT INTO `jipu_area` VALUES ('620111', '红古区', '620100', '6');
INSERT INTO `jipu_area` VALUES ('620121', '永登县', '620100', '7');
INSERT INTO `jipu_area` VALUES ('620122', '皋兰县', '620100', '8');
INSERT INTO `jipu_area` VALUES ('620123', '榆中县', '620100', '9');
INSERT INTO `jipu_area` VALUES ('620200', '嘉峪关市', '620000', '2');
INSERT INTO `jipu_area` VALUES ('620201', '市辖区', '620200', '1');
INSERT INTO `jipu_area` VALUES ('620300', '金昌市', '620000', '3');
INSERT INTO `jipu_area` VALUES ('620301', '市辖区', '620300', '1');
INSERT INTO `jipu_area` VALUES ('620302', '金川区', '620300', '2');
INSERT INTO `jipu_area` VALUES ('620321', '永昌县', '620300', '3');
INSERT INTO `jipu_area` VALUES ('620400', '白银市', '620000', '4');
INSERT INTO `jipu_area` VALUES ('620401', '市辖区', '620400', '1');
INSERT INTO `jipu_area` VALUES ('620402', '白银区', '620400', '2');
INSERT INTO `jipu_area` VALUES ('620403', '平川区', '620400', '3');
INSERT INTO `jipu_area` VALUES ('620421', '靖远县', '620400', '4');
INSERT INTO `jipu_area` VALUES ('620422', '会宁县', '620400', '5');
INSERT INTO `jipu_area` VALUES ('620423', '景泰县', '620400', '6');
INSERT INTO `jipu_area` VALUES ('620500', '天水市', '620000', '5');
INSERT INTO `jipu_area` VALUES ('620501', '市辖区', '620500', '1');
INSERT INTO `jipu_area` VALUES ('620502', '秦城区', '620500', '2');
INSERT INTO `jipu_area` VALUES ('620503', '北道区', '620500', '3');
INSERT INTO `jipu_area` VALUES ('620521', '清水县', '620500', '4');
INSERT INTO `jipu_area` VALUES ('620522', '秦安县', '620500', '5');
INSERT INTO `jipu_area` VALUES ('620523', '甘谷县', '620500', '6');
INSERT INTO `jipu_area` VALUES ('620524', '武山县', '620500', '7');
INSERT INTO `jipu_area` VALUES ('620525', '张家川回族自治县', '620500', '8');
INSERT INTO `jipu_area` VALUES ('620600', '武威市', '620000', '6');
INSERT INTO `jipu_area` VALUES ('620601', '市辖区', '620600', '1');
INSERT INTO `jipu_area` VALUES ('620602', '凉州区', '620600', '2');
INSERT INTO `jipu_area` VALUES ('620621', '民勤县', '620600', '3');
INSERT INTO `jipu_area` VALUES ('620622', '古浪县', '620600', '4');
INSERT INTO `jipu_area` VALUES ('620623', '天祝藏族自治县', '620600', '5');
INSERT INTO `jipu_area` VALUES ('620700', '张掖市', '620000', '7');
INSERT INTO `jipu_area` VALUES ('620701', '市辖区', '620700', '1');
INSERT INTO `jipu_area` VALUES ('620702', '甘州区', '620700', '2');
INSERT INTO `jipu_area` VALUES ('620721', '肃南裕固族自治县', '620700', '3');
INSERT INTO `jipu_area` VALUES ('620722', '民乐县', '620700', '4');
INSERT INTO `jipu_area` VALUES ('620723', '临泽县', '620700', '5');
INSERT INTO `jipu_area` VALUES ('620724', '高台县', '620700', '6');
INSERT INTO `jipu_area` VALUES ('620725', '山丹县', '620700', '7');
INSERT INTO `jipu_area` VALUES ('620800', '平凉市', '620000', '8');
INSERT INTO `jipu_area` VALUES ('620801', '市辖区', '620800', '1');
INSERT INTO `jipu_area` VALUES ('620802', '崆峒区', '620800', '2');
INSERT INTO `jipu_area` VALUES ('620821', '泾川县', '620800', '3');
INSERT INTO `jipu_area` VALUES ('620822', '灵台县', '620800', '4');
INSERT INTO `jipu_area` VALUES ('620823', '崇信县', '620800', '5');
INSERT INTO `jipu_area` VALUES ('620824', '华亭县', '620800', '6');
INSERT INTO `jipu_area` VALUES ('620825', '庄浪县', '620800', '7');
INSERT INTO `jipu_area` VALUES ('620826', '静宁县', '620800', '8');
INSERT INTO `jipu_area` VALUES ('620900', '酒泉市', '620000', '9');
INSERT INTO `jipu_area` VALUES ('620901', '市辖区', '620900', '1');
INSERT INTO `jipu_area` VALUES ('620902', '肃州区', '620900', '2');
INSERT INTO `jipu_area` VALUES ('620921', '金塔县', '620900', '3');
INSERT INTO `jipu_area` VALUES ('620922', '安西县', '620900', '4');
INSERT INTO `jipu_area` VALUES ('620923', '肃北蒙古族自治县', '620900', '5');
INSERT INTO `jipu_area` VALUES ('620924', '阿克塞哈萨克族自治县', '620900', '6');
INSERT INTO `jipu_area` VALUES ('620981', '玉门市', '620900', '7');
INSERT INTO `jipu_area` VALUES ('620982', '敦煌市', '620900', '8');
INSERT INTO `jipu_area` VALUES ('621000', '庆阳市', '620000', '10');
INSERT INTO `jipu_area` VALUES ('621001', '市辖区', '621000', '1');
INSERT INTO `jipu_area` VALUES ('621002', '西峰区', '621000', '2');
INSERT INTO `jipu_area` VALUES ('621021', '庆城县', '621000', '3');
INSERT INTO `jipu_area` VALUES ('621022', '环　县', '621000', '4');
INSERT INTO `jipu_area` VALUES ('621023', '华池县', '621000', '5');
INSERT INTO `jipu_area` VALUES ('621024', '合水县', '621000', '6');
INSERT INTO `jipu_area` VALUES ('621025', '正宁县', '621000', '7');
INSERT INTO `jipu_area` VALUES ('621026', '宁　县', '621000', '8');
INSERT INTO `jipu_area` VALUES ('621027', '镇原县', '621000', '9');
INSERT INTO `jipu_area` VALUES ('621100', '定西市', '620000', '11');
INSERT INTO `jipu_area` VALUES ('621101', '市辖区', '621100', '1');
INSERT INTO `jipu_area` VALUES ('621102', '安定区', '621100', '2');
INSERT INTO `jipu_area` VALUES ('621121', '通渭县', '621100', '3');
INSERT INTO `jipu_area` VALUES ('621122', '陇西县', '621100', '4');
INSERT INTO `jipu_area` VALUES ('621123', '渭源县', '621100', '5');
INSERT INTO `jipu_area` VALUES ('621124', '临洮县', '621100', '6');
INSERT INTO `jipu_area` VALUES ('621125', '漳　县', '621100', '7');
INSERT INTO `jipu_area` VALUES ('621126', '岷　县', '621100', '8');
INSERT INTO `jipu_area` VALUES ('621200', '陇南市', '620000', '12');
INSERT INTO `jipu_area` VALUES ('621201', '市辖区', '621200', '1');
INSERT INTO `jipu_area` VALUES ('621202', '武都区', '621200', '2');
INSERT INTO `jipu_area` VALUES ('621221', '成　县', '621200', '3');
INSERT INTO `jipu_area` VALUES ('621222', '文　县', '621200', '4');
INSERT INTO `jipu_area` VALUES ('621223', '宕昌县', '621200', '5');
INSERT INTO `jipu_area` VALUES ('621224', '康　县', '621200', '6');
INSERT INTO `jipu_area` VALUES ('621225', '西和县', '621200', '7');
INSERT INTO `jipu_area` VALUES ('621226', '礼　县', '621200', '8');
INSERT INTO `jipu_area` VALUES ('621227', '徽　县', '621200', '9');
INSERT INTO `jipu_area` VALUES ('621228', '两当县', '621200', '10');
INSERT INTO `jipu_area` VALUES ('622900', '临夏回族自治州', '620000', '13');
INSERT INTO `jipu_area` VALUES ('622901', '临夏市', '622900', '1');
INSERT INTO `jipu_area` VALUES ('622921', '临夏县', '622900', '2');
INSERT INTO `jipu_area` VALUES ('622922', '康乐县', '622900', '3');
INSERT INTO `jipu_area` VALUES ('622923', '永靖县', '622900', '4');
INSERT INTO `jipu_area` VALUES ('622924', '广河县', '622900', '5');
INSERT INTO `jipu_area` VALUES ('622925', '和政县', '622900', '6');
INSERT INTO `jipu_area` VALUES ('622926', '东乡族自治县', '622900', '7');
INSERT INTO `jipu_area` VALUES ('622927', '积石山保安族东乡族撒拉族自治县', '622900', '8');
INSERT INTO `jipu_area` VALUES ('623000', '甘南藏族自治州', '620000', '14');
INSERT INTO `jipu_area` VALUES ('623001', '合作市', '623000', '1');
INSERT INTO `jipu_area` VALUES ('623021', '临潭县', '623000', '2');
INSERT INTO `jipu_area` VALUES ('623022', '卓尼县', '623000', '3');
INSERT INTO `jipu_area` VALUES ('623023', '舟曲县', '623000', '4');
INSERT INTO `jipu_area` VALUES ('623024', '迭部县', '623000', '5');
INSERT INTO `jipu_area` VALUES ('623025', '玛曲县', '623000', '6');
INSERT INTO `jipu_area` VALUES ('623026', '碌曲县', '623000', '7');
INSERT INTO `jipu_area` VALUES ('623027', '夏河县', '623000', '8');
INSERT INTO `jipu_area` VALUES ('630000', '青海省', '0', '29');
INSERT INTO `jipu_area` VALUES ('630100', '西宁市', '630000', '1');
INSERT INTO `jipu_area` VALUES ('630101', '市辖区', '630100', '1');
INSERT INTO `jipu_area` VALUES ('630102', '城东区', '630100', '2');
INSERT INTO `jipu_area` VALUES ('630103', '城中区', '630100', '3');
INSERT INTO `jipu_area` VALUES ('630104', '城西区', '630100', '4');
INSERT INTO `jipu_area` VALUES ('630105', '城北区', '630100', '5');
INSERT INTO `jipu_area` VALUES ('630121', '大通回族土族自治县', '630100', '6');
INSERT INTO `jipu_area` VALUES ('630122', '湟中县', '630100', '7');
INSERT INTO `jipu_area` VALUES ('630123', '湟源县', '630100', '8');
INSERT INTO `jipu_area` VALUES ('632100', '海东地区', '630000', '2');
INSERT INTO `jipu_area` VALUES ('632121', '平安县', '632100', '1');
INSERT INTO `jipu_area` VALUES ('632122', '民和回族土族自治县', '632100', '2');
INSERT INTO `jipu_area` VALUES ('632123', '乐都县', '632100', '3');
INSERT INTO `jipu_area` VALUES ('632126', '互助土族自治县', '632100', '4');
INSERT INTO `jipu_area` VALUES ('632127', '化隆回族自治县', '632100', '5');
INSERT INTO `jipu_area` VALUES ('632128', '循化撒拉族自治县', '632100', '6');
INSERT INTO `jipu_area` VALUES ('632200', '海北藏族自治州', '630000', '3');
INSERT INTO `jipu_area` VALUES ('632221', '门源回族自治县', '632200', '1');
INSERT INTO `jipu_area` VALUES ('632222', '祁连县', '632200', '2');
INSERT INTO `jipu_area` VALUES ('632223', '海晏县', '632200', '3');
INSERT INTO `jipu_area` VALUES ('632224', '刚察县', '632200', '4');
INSERT INTO `jipu_area` VALUES ('632300', '黄南藏族自治州', '630000', '4');
INSERT INTO `jipu_area` VALUES ('632321', '同仁县', '632300', '1');
INSERT INTO `jipu_area` VALUES ('632322', '尖扎县', '632300', '2');
INSERT INTO `jipu_area` VALUES ('632323', '泽库县', '632300', '3');
INSERT INTO `jipu_area` VALUES ('632324', '河南蒙古族自治县', '632300', '4');
INSERT INTO `jipu_area` VALUES ('632500', '海南藏族自治州', '630000', '5');
INSERT INTO `jipu_area` VALUES ('632521', '共和县', '632500', '1');
INSERT INTO `jipu_area` VALUES ('632522', '同德县', '632500', '2');
INSERT INTO `jipu_area` VALUES ('632523', '贵德县', '632500', '3');
INSERT INTO `jipu_area` VALUES ('632524', '兴海县', '632500', '4');
INSERT INTO `jipu_area` VALUES ('632525', '贵南县', '632500', '5');
INSERT INTO `jipu_area` VALUES ('632600', '果洛藏族自治州', '630000', '6');
INSERT INTO `jipu_area` VALUES ('632621', '玛沁县', '632600', '1');
INSERT INTO `jipu_area` VALUES ('632622', '班玛县', '632600', '2');
INSERT INTO `jipu_area` VALUES ('632623', '甘德县', '632600', '3');
INSERT INTO `jipu_area` VALUES ('632624', '达日县', '632600', '4');
INSERT INTO `jipu_area` VALUES ('632625', '久治县', '632600', '5');
INSERT INTO `jipu_area` VALUES ('632626', '玛多县', '632600', '6');
INSERT INTO `jipu_area` VALUES ('632700', '玉树藏族自治州', '630000', '7');
INSERT INTO `jipu_area` VALUES ('632721', '玉树县', '632700', '1');
INSERT INTO `jipu_area` VALUES ('632722', '杂多县', '632700', '2');
INSERT INTO `jipu_area` VALUES ('632723', '称多县', '632700', '3');
INSERT INTO `jipu_area` VALUES ('632724', '治多县', '632700', '4');
INSERT INTO `jipu_area` VALUES ('632725', '囊谦县', '632700', '5');
INSERT INTO `jipu_area` VALUES ('632726', '曲麻莱县', '632700', '6');
INSERT INTO `jipu_area` VALUES ('632800', '海西蒙古族藏族自治州', '630000', '8');
INSERT INTO `jipu_area` VALUES ('632801', '格尔木市', '632800', '1');
INSERT INTO `jipu_area` VALUES ('632802', '德令哈市', '632800', '2');
INSERT INTO `jipu_area` VALUES ('632821', '乌兰县', '632800', '3');
INSERT INTO `jipu_area` VALUES ('632822', '都兰县', '632800', '4');
INSERT INTO `jipu_area` VALUES ('632823', '天峻县', '632800', '5');
INSERT INTO `jipu_area` VALUES ('640000', '宁　夏', '0', '30');
INSERT INTO `jipu_area` VALUES ('640100', '银川市', '640000', '1');
INSERT INTO `jipu_area` VALUES ('640101', '市辖区', '640100', '1');
INSERT INTO `jipu_area` VALUES ('640104', '兴庆区', '640100', '2');
INSERT INTO `jipu_area` VALUES ('640105', '西夏区', '640100', '3');
INSERT INTO `jipu_area` VALUES ('640106', '金凤区', '640100', '4');
INSERT INTO `jipu_area` VALUES ('640121', '永宁县', '640100', '5');
INSERT INTO `jipu_area` VALUES ('640122', '贺兰县', '640100', '6');
INSERT INTO `jipu_area` VALUES ('640181', '灵武市', '640100', '7');
INSERT INTO `jipu_area` VALUES ('640200', '石嘴山市', '640000', '2');
INSERT INTO `jipu_area` VALUES ('640201', '市辖区', '640200', '1');
INSERT INTO `jipu_area` VALUES ('640202', '大武口区', '640200', '2');
INSERT INTO `jipu_area` VALUES ('640205', '惠农区', '640200', '3');
INSERT INTO `jipu_area` VALUES ('640221', '平罗县', '640200', '4');
INSERT INTO `jipu_area` VALUES ('640300', '吴忠市', '640000', '3');
INSERT INTO `jipu_area` VALUES ('640301', '市辖区', '640300', '1');
INSERT INTO `jipu_area` VALUES ('640302', '利通区', '640300', '2');
INSERT INTO `jipu_area` VALUES ('640323', '盐池县', '640300', '3');
INSERT INTO `jipu_area` VALUES ('640324', '同心县', '640300', '4');
INSERT INTO `jipu_area` VALUES ('640381', '青铜峡市', '640300', '5');
INSERT INTO `jipu_area` VALUES ('640400', '固原市', '640000', '4');
INSERT INTO `jipu_area` VALUES ('640401', '市辖区', '640400', '1');
INSERT INTO `jipu_area` VALUES ('640402', '原州区', '640400', '2');
INSERT INTO `jipu_area` VALUES ('640422', '西吉县', '640400', '3');
INSERT INTO `jipu_area` VALUES ('640423', '隆德县', '640400', '4');
INSERT INTO `jipu_area` VALUES ('640424', '泾源县', '640400', '5');
INSERT INTO `jipu_area` VALUES ('640425', '彭阳县', '640400', '6');
INSERT INTO `jipu_area` VALUES ('640500', '中卫市', '640000', '5');
INSERT INTO `jipu_area` VALUES ('640501', '市辖区', '640500', '1');
INSERT INTO `jipu_area` VALUES ('640502', '沙坡头区', '640500', '2');
INSERT INTO `jipu_area` VALUES ('640521', '中宁县', '640500', '3');
INSERT INTO `jipu_area` VALUES ('640522', '海原县', '640500', '4');
INSERT INTO `jipu_area` VALUES ('650000', '新　疆', '0', '31');
INSERT INTO `jipu_area` VALUES ('650100', '乌鲁木齐市', '650000', '1');
INSERT INTO `jipu_area` VALUES ('650101', '市辖区', '650100', '1');
INSERT INTO `jipu_area` VALUES ('650102', '天山区', '650100', '2');
INSERT INTO `jipu_area` VALUES ('650103', '沙依巴克区', '650100', '3');
INSERT INTO `jipu_area` VALUES ('650104', '新市区', '650100', '4');
INSERT INTO `jipu_area` VALUES ('650105', '水磨沟区', '650100', '5');
INSERT INTO `jipu_area` VALUES ('650106', '头屯河区', '650100', '6');
INSERT INTO `jipu_area` VALUES ('650107', '达坂城区', '650100', '7');
INSERT INTO `jipu_area` VALUES ('650108', '东山区', '650100', '8');
INSERT INTO `jipu_area` VALUES ('650121', '乌鲁木齐县', '650100', '9');
INSERT INTO `jipu_area` VALUES ('650200', '克拉玛依市', '650000', '2');
INSERT INTO `jipu_area` VALUES ('650201', '市辖区', '650200', '1');
INSERT INTO `jipu_area` VALUES ('650202', '独山子区', '650200', '2');
INSERT INTO `jipu_area` VALUES ('650203', '克拉玛依区', '650200', '3');
INSERT INTO `jipu_area` VALUES ('650204', '白碱滩区', '650200', '4');
INSERT INTO `jipu_area` VALUES ('650205', '乌尔禾区', '650200', '5');
INSERT INTO `jipu_area` VALUES ('652100', '吐鲁番地区', '650000', '3');
INSERT INTO `jipu_area` VALUES ('652101', '吐鲁番市', '652100', '1');
INSERT INTO `jipu_area` VALUES ('652122', '鄯善县', '652100', '2');
INSERT INTO `jipu_area` VALUES ('652123', '托克逊县', '652100', '3');
INSERT INTO `jipu_area` VALUES ('652200', '哈密地区', '650000', '4');
INSERT INTO `jipu_area` VALUES ('652201', '哈密市', '652200', '1');
INSERT INTO `jipu_area` VALUES ('652222', '巴里坤哈萨克自治县', '652200', '2');
INSERT INTO `jipu_area` VALUES ('652223', '伊吾县', '652200', '3');
INSERT INTO `jipu_area` VALUES ('652300', '昌吉回族自治州', '650000', '5');
INSERT INTO `jipu_area` VALUES ('652301', '昌吉市', '652300', '1');
INSERT INTO `jipu_area` VALUES ('652302', '阜康市', '652300', '2');
INSERT INTO `jipu_area` VALUES ('652303', '米泉市', '652300', '3');
INSERT INTO `jipu_area` VALUES ('652323', '呼图壁县', '652300', '4');
INSERT INTO `jipu_area` VALUES ('652324', '玛纳斯县', '652300', '5');
INSERT INTO `jipu_area` VALUES ('652325', '奇台县', '652300', '6');
INSERT INTO `jipu_area` VALUES ('652327', '吉木萨尔县', '652300', '7');
INSERT INTO `jipu_area` VALUES ('652328', '木垒哈萨克自治县', '652300', '8');
INSERT INTO `jipu_area` VALUES ('652700', '博尔塔拉蒙古自治州', '650000', '6');
INSERT INTO `jipu_area` VALUES ('652701', '博乐市', '652700', '1');
INSERT INTO `jipu_area` VALUES ('652722', '精河县', '652700', '2');
INSERT INTO `jipu_area` VALUES ('652723', '温泉县', '652700', '3');
INSERT INTO `jipu_area` VALUES ('652800', '巴音郭楞蒙古自治州', '650000', '7');
INSERT INTO `jipu_area` VALUES ('652801', '库尔勒市', '652800', '1');
INSERT INTO `jipu_area` VALUES ('652822', '轮台县', '652800', '2');
INSERT INTO `jipu_area` VALUES ('652823', '尉犁县', '652800', '3');
INSERT INTO `jipu_area` VALUES ('652824', '若羌县', '652800', '4');
INSERT INTO `jipu_area` VALUES ('652825', '且末县', '652800', '5');
INSERT INTO `jipu_area` VALUES ('652826', '焉耆回族自治县', '652800', '6');
INSERT INTO `jipu_area` VALUES ('652827', '和静县', '652800', '7');
INSERT INTO `jipu_area` VALUES ('652828', '和硕县', '652800', '8');
INSERT INTO `jipu_area` VALUES ('652829', '博湖县', '652800', '9');
INSERT INTO `jipu_area` VALUES ('652900', '阿克苏地区', '650000', '8');
INSERT INTO `jipu_area` VALUES ('652901', '阿克苏市', '652900', '1');
INSERT INTO `jipu_area` VALUES ('652922', '温宿县', '652900', '2');
INSERT INTO `jipu_area` VALUES ('652923', '库车县', '652900', '3');
INSERT INTO `jipu_area` VALUES ('652924', '沙雅县', '652900', '4');
INSERT INTO `jipu_area` VALUES ('652925', '新和县', '652900', '5');
INSERT INTO `jipu_area` VALUES ('652926', '拜城县', '652900', '6');
INSERT INTO `jipu_area` VALUES ('652927', '乌什县', '652900', '7');
INSERT INTO `jipu_area` VALUES ('652928', '阿瓦提县', '652900', '8');
INSERT INTO `jipu_area` VALUES ('652929', '柯坪县', '652900', '9');
INSERT INTO `jipu_area` VALUES ('653000', '克孜勒苏柯尔克孜自治州', '650000', '9');
INSERT INTO `jipu_area` VALUES ('653001', '阿图什市', '653000', '1');
INSERT INTO `jipu_area` VALUES ('653022', '阿克陶县', '653000', '2');
INSERT INTO `jipu_area` VALUES ('653023', '阿合奇县', '653000', '3');
INSERT INTO `jipu_area` VALUES ('653024', '乌恰县', '653000', '4');
INSERT INTO `jipu_area` VALUES ('653100', '喀什地区', '650000', '10');
INSERT INTO `jipu_area` VALUES ('653101', '喀什市', '653100', '1');
INSERT INTO `jipu_area` VALUES ('653121', '疏附县', '653100', '2');
INSERT INTO `jipu_area` VALUES ('653122', '疏勒县', '653100', '3');
INSERT INTO `jipu_area` VALUES ('653123', '英吉沙县', '653100', '4');
INSERT INTO `jipu_area` VALUES ('653124', '泽普县', '653100', '5');
INSERT INTO `jipu_area` VALUES ('653125', '莎车县', '653100', '6');
INSERT INTO `jipu_area` VALUES ('653126', '叶城县', '653100', '7');
INSERT INTO `jipu_area` VALUES ('653127', '麦盖提县', '653100', '8');
INSERT INTO `jipu_area` VALUES ('653128', '岳普湖县', '653100', '9');
INSERT INTO `jipu_area` VALUES ('653129', '伽师县', '653100', '10');
INSERT INTO `jipu_area` VALUES ('653130', '巴楚县', '653100', '11');
INSERT INTO `jipu_area` VALUES ('653131', '塔什库尔干塔吉克自治县', '653100', '12');
INSERT INTO `jipu_area` VALUES ('653200', '和田地区', '650000', '11');
INSERT INTO `jipu_area` VALUES ('653201', '和田市', '653200', '1');
INSERT INTO `jipu_area` VALUES ('653221', '和田县', '653200', '2');
INSERT INTO `jipu_area` VALUES ('653222', '墨玉县', '653200', '3');
INSERT INTO `jipu_area` VALUES ('653223', '皮山县', '653200', '4');
INSERT INTO `jipu_area` VALUES ('653224', '洛浦县', '653200', '5');
INSERT INTO `jipu_area` VALUES ('653225', '策勒县', '653200', '6');
INSERT INTO `jipu_area` VALUES ('653226', '于田县', '653200', '7');
INSERT INTO `jipu_area` VALUES ('653227', '民丰县', '653200', '8');
INSERT INTO `jipu_area` VALUES ('654000', '伊犁哈萨克自治州', '650000', '12');
INSERT INTO `jipu_area` VALUES ('654002', '伊宁市', '654000', '1');
INSERT INTO `jipu_area` VALUES ('654003', '奎屯市', '654000', '2');
INSERT INTO `jipu_area` VALUES ('654021', '伊宁县', '654000', '3');
INSERT INTO `jipu_area` VALUES ('654022', '察布查尔锡伯自治县', '654000', '4');
INSERT INTO `jipu_area` VALUES ('654023', '霍城县', '654000', '5');
INSERT INTO `jipu_area` VALUES ('654024', '巩留县', '654000', '6');
INSERT INTO `jipu_area` VALUES ('654025', '新源县', '654000', '7');
INSERT INTO `jipu_area` VALUES ('654026', '昭苏县', '654000', '8');
INSERT INTO `jipu_area` VALUES ('654027', '特克斯县', '654000', '9');
INSERT INTO `jipu_area` VALUES ('654028', '尼勒克县', '654000', '10');
INSERT INTO `jipu_area` VALUES ('654200', '塔城地区', '650000', '13');
INSERT INTO `jipu_area` VALUES ('654201', '塔城市', '654200', '1');
INSERT INTO `jipu_area` VALUES ('654202', '乌苏市', '654200', '2');
INSERT INTO `jipu_area` VALUES ('654221', '额敏县', '654200', '3');
INSERT INTO `jipu_area` VALUES ('654223', '沙湾县', '654200', '4');
INSERT INTO `jipu_area` VALUES ('654224', '托里县', '654200', '5');
INSERT INTO `jipu_area` VALUES ('654225', '裕民县', '654200', '6');
INSERT INTO `jipu_area` VALUES ('654226', '和布克赛尔蒙古自治县', '654200', '7');
INSERT INTO `jipu_area` VALUES ('654300', '阿勒泰地区', '650000', '14');
INSERT INTO `jipu_area` VALUES ('654301', '阿勒泰市', '654300', '1');
INSERT INTO `jipu_area` VALUES ('654321', '布尔津县', '654300', '2');
INSERT INTO `jipu_area` VALUES ('654322', '富蕴县', '654300', '3');
INSERT INTO `jipu_area` VALUES ('654323', '福海县', '654300', '4');
INSERT INTO `jipu_area` VALUES ('654324', '哈巴河县', '654300', '5');
INSERT INTO `jipu_area` VALUES ('654325', '青河县', '654300', '6');
INSERT INTO `jipu_area` VALUES ('654326', '吉木乃县', '654300', '7');
INSERT INTO `jipu_area` VALUES ('659000', '省直辖行政单位', '650000', '15');
INSERT INTO `jipu_area` VALUES ('659001', '石河子市', '659000', '1');
INSERT INTO `jipu_area` VALUES ('659002', '阿拉尔市', '659000', '2');
INSERT INTO `jipu_area` VALUES ('659003', '图木舒克市', '659000', '3');
INSERT INTO `jipu_area` VALUES ('659004', '五家渠市', '659000', '4');
INSERT INTO `jipu_area` VALUES ('710000', '台湾省', '0', '32');
INSERT INTO `jipu_area` VALUES ('710001', '台北市', '710000', '1');
INSERT INTO `jipu_area` VALUES ('710002', '台北县', '710001', '1');
INSERT INTO `jipu_area` VALUES ('710003', '基隆市', '710000', '2');
INSERT INTO `jipu_area` VALUES ('710004', '花莲县', '710003', '1');
INSERT INTO `jipu_area` VALUES ('810000', '香　港', '0', '33');
INSERT INTO `jipu_area` VALUES ('810001', '香港', '810000', '1');
INSERT INTO `jipu_area` VALUES ('810002', '中西区', '810001', '1');
INSERT INTO `jipu_area` VALUES ('810003', '九龙城区', '810001', '2');
INSERT INTO `jipu_area` VALUES ('810004', '南区', '810001', '3');
INSERT INTO `jipu_area` VALUES ('810005', '黄大仙区', '810001', '4');
INSERT INTO `jipu_area` VALUES ('810006', '油尖旺区', '810001', '5');
INSERT INTO `jipu_area` VALUES ('810007', '葵青区', '810001', '6');
INSERT INTO `jipu_area` VALUES ('810008', '西贡区', '810001', '7');
INSERT INTO `jipu_area` VALUES ('810009', '屯门区', '810001', '8');
INSERT INTO `jipu_area` VALUES ('810010', '荃湾区', '810001', '9');
INSERT INTO `jipu_area` VALUES ('810011', '东区', '810001', '10');
INSERT INTO `jipu_area` VALUES ('810012', '观塘区', '810001', '11');
INSERT INTO `jipu_area` VALUES ('810013', '深水步区', '810001', '12');
INSERT INTO `jipu_area` VALUES ('810014', '湾仔区', '810001', '13');
INSERT INTO `jipu_area` VALUES ('810015', '离岛区', '810001', '14');
INSERT INTO `jipu_area` VALUES ('810016', '北区', '810001', '15');
INSERT INTO `jipu_area` VALUES ('810017', '沙田区', '810001', '16');
INSERT INTO `jipu_area` VALUES ('810018', '大埔区', '810001', '17');
INSERT INTO `jipu_area` VALUES ('810019', '元朗区', '810001', '18');
INSERT INTO `jipu_area` VALUES ('820000', '澳　门', '0', '34');
INSERT INTO `jipu_area` VALUES ('820001', '澳门', '820000', '1');
INSERT INTO `jipu_area` VALUES ('820002', '澳门', '820001', '1');
INSERT INTO `jipu_area` VALUES ('910005', '中山市', '442000', '1');
INSERT INTO `jipu_area` VALUES ('910006', '东莞市', '441900', '1');

-- ----------------------------
-- Table structure for jipu_article
-- ----------------------------
DROP TABLE IF EXISTS `jipu_article`;
CREATE TABLE `jipu_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `cid` int(11) DEFAULT '0' COMMENT 'cid',
  `category` varchar(255) DEFAULT NULL COMMENT '分类',
  `images` varchar(80) DEFAULT '' COMMENT '图片',
  `description` varchar(600) DEFAULT '' COMMENT '描述',
  `content` mediumtext COMMENT '内容',
  `to_url` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `view` int(11) DEFAULT '0' COMMENT '展示次数',
  `is_topic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '头条（1是，0否）',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶（1是，0否）',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章内容表';

-- ----------------------------
-- Records of jipu_article
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_article_category
-- ----------------------------
DROP TABLE IF EXISTS `jipu_article_category`;
CREATE TABLE `jipu_article_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '父级分类ID',
  `name` char(100) DEFAULT '' COMMENT '分类名',
  `ename` char(100) DEFAULT '' COMMENT '英文',
  `images` int(10) NOT NULL COMMENT '分类图片',
  `meta_title` char(50) DEFAULT '' COMMENT '网页标题',
  `meta_keywords` varchar(255) DEFAULT '' COMMENT '网页关键字',
  `meta_description` varchar(255) DEFAULT '' COMMENT '网页描述',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1正常 -1已删除',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序',
  `is_display` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='文章分类表';

-- ----------------------------
-- Records of jipu_article_category
-- ----------------------------
INSERT INTO `jipu_article_category` VALUES ('1', '0', '吃货日志', 'CHRZ', '0', '', '美食  ', '美食推荐', '1', '0', '1', '1456903231', '1456903231');
INSERT INTO `jipu_article_category` VALUES ('2', '1', '火锅', 'HG', '0', '', '火锅', '火锅', '1', '0', '1', '1456903252', '1456903252');
INSERT INTO `jipu_article_category` VALUES ('3', '0', '帮助文档', 'about', '0', '', '', '', '1', '1', '1', '1457773544', '1457934660');
INSERT INTO `jipu_article_category` VALUES ('4', '3', '关于我们', 'GYWM', '0', '', '关于我们 极铺', '关于极铺的信息', '1', '0', '1', '1457934245', '1457934245');
INSERT INTO `jipu_article_category` VALUES ('5', '3', '购物保障', 'GWBZ', '0', '', '购物保障 极铺', '购物保障 极铺', '1', '0', '1', '1457934699', '1457934699');
INSERT INTO `jipu_article_category` VALUES ('6', '2', '阿道夫', '阿斯蒂芬', '0', '', '艾丝凡', '爱疯', '1', '0', '1', '1463038260', '1465539631');

-- ----------------------------
-- Table structure for jipu_attachment
-- ----------------------------
DROP TABLE IF EXISTS `jipu_attachment`;
CREATE TABLE `jipu_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of jipu_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_auth_extend
-- ----------------------------
DROP TABLE IF EXISTS `jipu_auth_extend`;
CREATE TABLE `jipu_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

-- ----------------------------
-- Records of jipu_auth_extend
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `jipu_auth_group`;
CREATE TABLE `jipu_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` text NOT NULL COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_auth_group
-- ----------------------------
INSERT INTO `jipu_auth_group` VALUES ('1', 'admin', '1', '平台总管理员组', '平台总管理员用户组', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,35,36,37,38,39,40,41,42,43,45,46,47,48,49,50,51,52,53,54,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,75,76,77,79,80,81,82,83,85,86,87,88,89,90,91,92,93,94,95,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,115,116,117,118,119,120,122,124,125,126,127,128,129,130,132,133,134,135,136,138,139,140,141,142,143,144,145,146,147,148,150,151,152,153,154,155,157,158,159,160,161,162,163,164,165,166,167,168,169,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300,301,302,303,304,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,323,324,325,326,327,328,329,330,331,332,333,334,335,336,337,338,339,340,341,342,343,344,345,346,347,348,349,350,354,358');
INSERT INTO `jipu_auth_group` VALUES ('12', 'admin', '1', '普通管理员', '普通管理员给其他管理员试用', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,35,36,37,38,39,40,41,42,43,45,46,47,48,49,50,54,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,75,76,77,79,80,81,82,83,85,86,87,88,89,90,94,95,98,99,100,101,102,103,104,105,107,108,109,110,111,112,113,115,116,117,118,119,120,122,124,125,126,127,132,133,134,135,136,138,139,140,141,142,143,144,145,146,147,148,150,151,152,153,155,157,158,159,160,161,162,163,164,165,166,167,168,169,171,172,173,174,176,177,178,179,180,181,182,183,184,185,186,187,188,190,191,192,193,194,195,196,197,199,200,201,202,203,205,206,207,209,210,211,212,213,214,216,217,218,220,221,223,224,225,226,227,228,229,230,231,232,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300,301,302,303,304,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,323,324,325,326,327,328,329,330,331,332,333,334,335,336,337,338,339,340,341,342,343,344,345,346,347,348,349,350,351,352,354,356,357,358,359');
INSERT INTO `jipu_auth_group` VALUES ('13', 'admin', '1', '供应商', '产品供应商用户组，只有在此添加了的用户才能成为供应商', '1', '');

-- ----------------------------
-- Table structure for jipu_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `jipu_auth_group_access`;
CREATE TABLE `jipu_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_auth_group_access
-- ----------------------------
INSERT INTO `jipu_auth_group_access` VALUES ('1', '1');
INSERT INTO `jipu_auth_group_access` VALUES ('2', '12');

-- ----------------------------
-- Table structure for jipu_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `jipu_auth_rule`;
CREATE TABLE `jipu_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=360 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_auth_rule
-- ----------------------------
INSERT INTO `jipu_auth_rule` VALUES ('1', 'admin', '1', 'Admin/Index/index', '快速开始', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('2', 'admin', '1', 'Admin/Index/cleancache', '缓存清理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('3', 'admin', '1', 'Admin/DeliveryTpl/index', '运费模板', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('4', 'admin', '1', 'Admin/DeliveryTpl/add', '添加模板', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('5', 'admin', '1', 'Admin/DeliveryTpl/update', '保存模板', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('6', 'admin', '1', 'Admin/DeliveryTpl/edit', '编辑模板', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('7', 'admin', '1', 'Admin/Action/edit', '查看行为日志', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('8', 'admin', '1', 'Admin/Action/remove', '删除行为日志', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('9', 'admin', '1', 'Admin/Action/clear', '清空行为日志', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('10', 'admin', '1', 'Admin/DeliveryTpl/ajaxList', 'Ajax获取列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('11', 'admin', '1', 'Admin/DeliveryTpl/ajaxDetail', 'Ajax获取内容', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('12', 'admin', '1', 'Admin/Order/updateField', '修改订单金额', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('13', 'admin', '1', 'Admin/User/rechangeAdd', '用户充值', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('14', 'admin', '1', 'Admin/Invite/view', '邀请记录', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('15', 'admin', '2', 'Admin/Index/index', '首页', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('16', 'admin', '1', 'Admin/Item/index', '商品列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('17', 'admin', '1', 'Admin/Item/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('18', 'admin', '1', 'Admin/ItemCategory/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('19', 'admin', '1', 'Admin/ItemProperty/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('20', 'admin', '1', 'Admin/ItemProperty/add?type=specification', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('21', 'admin', '1', 'Admin/Item/clear', '清空', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('22', 'admin', '1', 'Admin/Order/index', '订单列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('23', 'admin', '1', 'Admin/Order/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('24', 'admin', '1', 'Admin/Payment/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('25', 'admin', '1', 'Admin/Ship/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('26', 'admin', '1', 'Admin/Invoice/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('27', 'admin', '1', 'Admin/Promote/index', '营销工具', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('28', 'admin', '1', 'Admin/User/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('29', 'admin', '1', 'Admin/AuthManager/createGroup', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('30', 'admin', '1', 'Admin/WechatUser/getUser', '同步粉丝', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('31', 'admin', '1', 'Admin/User/addaction', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('32', 'admin', '1', 'Admin/Promote/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('33', 'admin', '1', 'Admin/Coupon/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('34', 'admin', '1', 'Admin/CouponUser/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('35', 'admin', '1', 'Admin/Card/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('36', 'admin', '1', 'Admin/Activity/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('37', 'admin', '1', 'Admin/Advertise/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('38', 'admin', '1', 'Admin/Article/index', '文章列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('39', 'admin', '1', 'Admin/Article/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('40', 'admin', '1', 'Admin/ArticleCategory/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('41', 'admin', '1', 'Admin/Article/clear', '清空', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('42', 'admin', '1', 'Admin/Config/group', '基本设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('43', 'admin', '1', 'Admin/Channel/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('44', 'admin', '1', 'Admin/NotifyTpl/add', '新增', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('45', 'admin', '1', 'Admin/Config/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('46', 'admin', '1', 'Admin/Menu/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('47', 'admin', '1', 'Admin/Database/export', '备份', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('48', 'admin', '1', 'Admin/Database/import', '恢复', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('49', 'admin', '1', 'Admin/WechatMsg/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('50', 'admin', '1', 'Admin/WechatMenu/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('51', 'admin', '1', 'Admin/Addons/index', '插件管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('52', 'admin', '1', 'Admin/Addons/create', '创建', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('53', 'admin', '1', 'Admin/Addons/existHook', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('54', 'admin', '1', 'Admin/Stat/index', '订单统计', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('55', 'admin', '1', 'Admin/Redpacket/add', '发红包', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('56', 'admin', '1', 'Admin/Refund/alipay', '支付宝批量退款', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('57', 'admin', '2', 'Admin/Item/index', '商品', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('58', 'admin', '1', 'Admin/ItemCategory/index', '商品分类', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('59', 'admin', '1', 'Admin/Item/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('60', 'admin', '1', 'Admin/ItemCategory/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('61', 'admin', '1', 'Admin/ItemProperty/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('62', 'admin', '1', 'Admin/ItemProperty/edit?type=specification', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('63', 'admin', '1', 'Admin/Item/permit', '还原', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('64', 'admin', '1', 'Admin/Payment/index', '收款单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('65', 'admin', '1', 'Admin/Order/setStatus', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('66', 'admin', '1', 'Admin/Payment/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('67', 'admin', '1', 'Admin/Ship/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('68', 'admin', '1', 'Admin/Invoice/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('69', 'admin', '1', 'Admin/User/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('70', 'admin', '1', 'Admin/AuthManager/editGroup', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('71', 'admin', '1', 'Admin/User/editaction', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('72', 'admin', '1', 'Admin/Promote/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('73', 'admin', '1', 'Admin/Coupon/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('74', 'admin', '1', 'Admin/CouponUser/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('75', 'admin', '1', 'Admin/Card/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('76', 'admin', '1', 'Admin/Activity/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('77', 'admin', '1', 'Admin/Advertise/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('78', 'admin', '1', 'Admin/Article/category', '文章分类', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('79', 'admin', '1', 'Admin/Article/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('80', 'admin', '1', 'Admin/ArticleCategory/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('81', 'admin', '1', 'Admin/Article/permit', '还原', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('82', 'admin', '1', 'Admin/Channel/index', '导航管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('83', 'admin', '1', 'Admin/Channel/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('84', 'admin', '1', 'Admin/NotifyTpl/edit', '编辑', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('85', 'admin', '1', 'Admin/Config/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('86', 'admin', '1', 'Admin/Menu/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('87', 'admin', '1', 'Admin/Database/optimize', '优化表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('88', 'admin', '1', 'Admin/Database/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('89', 'admin', '1', 'Admin/WechatMsg/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('90', 'admin', '1', 'Admin/WechatMenu/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('91', 'admin', '1', 'Admin/Addons/hooks', '钩子管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('92', 'admin', '1', 'Admin/Addons/checkForm', '检测创建', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('93', 'admin', '1', 'Admin/Addons/edithook', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('94', 'admin', '1', 'Admin/Stat/user', '用户统计', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('95', 'admin', '1', 'Admin/WechatUser/setStatus', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('96', 'admin', '1', 'Admin/Redpacket/index', '红包', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('97', 'admin', '1', 'Admin/Redpacket/receive', '红包领取', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('98', 'admin', '2', 'Admin/Order/index', '订单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('99', 'admin', '1', 'Admin/ItemProperty/index', '商品属性', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('100', 'admin', '1', 'Admin/Item/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('101', 'admin', '1', 'Admin/ItemCategory/remove', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('102', 'admin', '1', 'Admin/ItemProperty/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('103', 'admin', '1', 'Admin/ItemProperty/del?type=specification', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('104', 'admin', '1', 'Admin/Ship/index', '发货单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('105', 'admin', '1', 'Admin/Payment/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('106', 'admin', '1', 'Admin/Ship/del', '删除', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('107', 'admin', '1', 'Admin/Invoice/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('108', 'admin', '1', 'Admin/Coupon/index', '优惠券管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('109', 'admin', '1', 'Admin/User/changeStatus', '设置用户状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('110', 'admin', '1', 'Admin/AuthManager/writeGroup', '保存', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('111', 'admin', '1', 'Admin/User/saveAction', '保存', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('112', 'admin', '1', 'Admin/Promote/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('113', 'admin', '1', 'Admin/Coupon/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('114', 'admin', '1', 'Admin/CouponUser/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('115', 'admin', '1', 'Admin/Card/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('116', 'admin', '1', 'Admin/Activity/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('117', 'admin', '1', 'Admin/Advertise/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('118', 'admin', '1', 'Admin/Article/recyle', '回收站', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('119', 'admin', '1', 'Admin/Article/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('120', 'admin', '1', 'Admin/ArticleCategory/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('121', 'admin', '1', 'Admin/NotifyTpl/index', '消息模板管理', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('122', 'admin', '1', 'Admin/Channel/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('123', 'admin', '1', 'Admin/NotifyTpl/update', '更新', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('124', 'admin', '1', 'Admin/Config/save', '保存', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('125', 'admin', '1', 'Admin/Menu/import', '导入', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('126', 'admin', '1', 'Admin/Database/repair', '修复表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('127', 'admin', '1', 'Admin/WechatMsg/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('128', 'admin', '1', 'Admin/Addons/develop', '开发辅助', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('129', 'admin', '1', 'Admin/Addons/preview', '预览', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('130', 'admin', '1', 'Admin/Addons/updateHook', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('131', 'admin', '1', 'Admin/Redpacket/qrcode', '二维码', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('132', 'admin', '2', 'Admin/DeliveryTpl/index', '物流', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('133', 'admin', '1', 'Admin/ItemProperty/index?type=specification', '商品规格', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('134', 'admin', '1', 'Admin/Item/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('135', 'admin', '1', 'Admin/ItemCategory/move', '移动', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('136', 'admin', '1', 'Admin/Invoice/index', '发票管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('137', 'admin', '1', 'Admin/CouponSend/index', '优惠券发放', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('138', 'admin', '1', 'Admin/User/changeStatus?method=forbidUser', '禁用会员', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('139', 'admin', '1', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('140', 'admin', '1', 'Admin/User/setStatus', '变更行为状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('141', 'admin', '1', 'Admin/Coupon/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('142', 'admin', '1', 'Admin/Card/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('143', 'admin', '1', 'Admin/Activity/updateField', '局部更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('144', 'admin', '1', 'Admin/Advertise/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('145', 'admin', '1', 'Admin/Article/updateField', '局部更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('146', 'admin', '1', 'Admin/ArticleCategory/updateField', '更新局部', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('147', 'admin', '1', 'Admin/Config/index', '配置管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('148', 'admin', '1', 'Admin/Channel/sort', '排序', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('149', 'admin', '1', 'Admin/NotifyTpl/del', '删除', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('150', 'admin', '1', 'Admin/Config/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('151', 'admin', '1', 'Admin/Menu/sort', '排序', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('152', 'admin', '1', 'Admin/WechatMsg/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('153', 'admin', '1', 'Admin/WechatMenu/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('154', 'admin', '1', 'Admin/Addons/build', '快速生成插件', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('155', 'admin', '1', 'Admin/ItemProperty/view', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('156', 'admin', '1', 'Admin/Redpacket/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('157', 'admin', '1', 'Admin/Order/cancel', '取消订单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('158', 'admin', '2', 'Admin/Promote/index', '营销', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('159', 'admin', '1', 'Admin/Item/recycle', '回收站', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('160', 'admin', '1', 'Admin/Item/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('161', 'admin', '1', 'Admin/ItemCategory/merge', '合并', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('162', 'admin', '1', 'Admin/Card/index', '礼品卡管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('163', 'admin', '1', 'Admin/User/changeStatus?method=resumeUser', '启用会员', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('164', 'admin', '1', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('165', 'admin', '1', 'Admin/Coupon/setItems', '设置商品', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('166', 'admin', '1', 'Admin/Card/export', '导出', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('167', 'admin', '1', 'Admin/Activity/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('168', 'admin', '1', 'Admin/Advertise/delPic', '删除图片', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('169', 'admin', '1', 'Admin/Article/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('170', 'admin', '1', 'Admin/ArticleCategory/del', '删除', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('171', 'admin', '1', 'Admin/Menu/index', '菜单管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('172', 'admin', '1', 'Admin/Config/sort', '排序', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('173', 'admin', '1', 'Admin/Menu/del', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('174', 'admin', '1', 'Admin/WechatMenu/send', '生成自定义菜单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('175', 'admin', '1', 'Admin/Addons/config', '设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('176', 'admin', '1', 'Admin/Order/recycle', '回收站', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('177', 'admin', '1', 'Admin/ItemProperty/getValue', '输出属性值', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('178', 'admin', '1', 'Admin/Invite/index', '邀请', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('179', 'admin', '1', 'Admin/Refund/index', '退款管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('180', 'admin', '1', 'Admin/Order/refund', '处理退款', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('181', 'admin', '2', 'Admin/User/index', '用户', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('182', 'admin', '1', 'Admin/Item/updateField', '部分更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('183', 'admin', '1', 'Admin/ItemCategory/operate', '操作', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('184', 'admin', '1', 'Admin/Activity/index', '专题管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('185', 'admin', '1', 'Admin/User/changeStatus?method=deleteUser', '删除会员', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('186', 'admin', '1', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('187', 'admin', '1', 'Admin/Activity/delPic', '删除图片', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('188', 'admin', '1', 'Admin/WechatMenu/remove', '删除自定义菜单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('189', 'admin', '1', 'Admin/Addons/disable', '禁用', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('190', 'admin', '1', 'Admin/Coupon/setStatus', '设置状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('191', 'admin', '1', 'Admin/ItemProperty/getPictureConfigData', '输出图片配置数组', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('192', 'admin', '2', 'Admin/Article/index', '文章', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('193', 'admin', '1', 'Admin/Item/setStatus', '设置状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('194', 'admin', '1', 'Admin/ItemCategory/setStatus', '状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('195', 'admin', '1', 'Admin/Advertise/index', '广告管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('196', 'admin', '1', 'Admin/User/updatePassword', '修改密码', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('197', 'admin', '1', 'Admin/AuthManager/group', '分组授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('198', 'admin', '1', 'Admin/Addons/enable', '启用', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('199', 'admin', '1', 'Admin/ItemProperty/getOption', '获取选项值', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('200', 'admin', '1', 'Admin/Item/delPic', '删除图片', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('201', 'admin', '1', 'Admin/ItemCategory/other', '其他', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('202', 'admin', '1', 'Admin/User/updateNickname', '修改昵称', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('203', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('204', 'admin', '1', 'Admin/Addons/install', '安装', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('205', 'admin', '1', 'Admin/ItemProperty/saveOption', '保存选项值', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('206', 'admin', '1', 'Admin/Item/select', '选择分类', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('207', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('208', 'admin', '1', 'Admin/Addons/uninstall', '卸载', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('209', 'admin', '1', 'Admin/ItemProperty/upload', '上传图片', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('210', 'admin', '1', 'Admin/User/setValue', '清零', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('211', 'admin', '2', 'Admin/Stat/index', '统计', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('212', 'admin', '1', 'Admin/Item/other', '其他操作', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('213', 'admin', '1', 'Admin/User/index', '用户列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('214', 'admin', '1', 'Admin/AuthManager/addToGroup', '保存成员授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('215', 'admin', '1', 'Admin/Addons/saveconfig', '更新配置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('216', 'admin', '1', 'Admin/Item/createQrcode', '二维码批量生成', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('217', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '解除成员授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('218', 'admin', '1', 'Admin/Database/index?type=export', '备份数据库', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('219', 'admin', '1', 'Admin/Addons/adminList', '插件后台列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('220', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('221', 'admin', '1', 'Admin/Database/index?type=import', '还原数据库', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('222', 'admin', '1', 'Admin/Addons/execute', 'URL方式访问插件', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('223', 'admin', '1', 'Admin/AuthManager/addToCategory', '保存分类授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('224', 'admin', '1', 'Admin/AuthManager/index', '用户组管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('225', 'admin', '1', 'Admin/User/recharge', '用户充值管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('226', 'admin', '1', 'Admin/WechatMsg/index', '微信消息设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('227', 'admin', '1', 'Admin/WechatMenu/index', '自定义菜单设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('228', 'admin', '1', 'Admin/User/finance', '用户账户流水', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('229', 'admin', '1', 'Admin/WechatUser/index', '微信粉丝管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('230', 'admin', '1', 'Admin/User/action', '用户行为', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('231', 'admin', '1', 'Admin/Action/actionlog', '行为日志', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('232', 'admin', '2', 'Admin/Config/group', '系统', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('233', 'admin', '2', 'Admin/Addons/index', '扩展', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('234', 'admin', '1', 'Admin/Advertise/setStatus', '状态设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('235', 'admin', '1', 'Admin/Activity/setStatus', '状态设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('236', 'admin', '1', 'Admin/Channel/setStatus', '状态设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('237', 'admin', '1', 'Admin/Refund/deal', '单笔退款', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('238', 'admin', '1', 'Admin/Ship/update', '发货', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('239', 'admin', '1', 'Admin/UserGroup/removeFromGroup', '移除授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('240', 'admin', '1', 'Admin/UserGroup/addToGroup', '增加授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('241', 'admin', '1', 'Admin/UserGroup/user', '成员授权', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('242', 'admin', '1', 'Admin/UserGroup/setStatus', '更改状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('243', 'admin', '1', 'Admin/UserGroup/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('244', 'admin', '1', 'Admin/UserGroup/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('245', 'admin', '1', 'Admin/UserGroup/add', '添加', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('246', 'admin', '1', 'Admin/Sms/listsSend', '短信记录', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('247', 'admin', '1', 'Admin/Sms/clearCache', '清空缓存', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('248', 'admin', '1', 'Admin/Sms/removeTpl', '删除模板', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('249', 'admin', '1', 'Admin/Sms/updateTpl', '添加编辑模板', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('250', 'admin', '1', 'Admin/Sms/listsTpl', '模板列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('251', 'admin', '1', 'Admin/Sms/setUser', '修改账户信息', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('252', 'admin', '1', 'Admin/Area/remove', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('253', 'admin', '1', 'Admin/Area/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('254', 'admin', '1', 'Admin/Area/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('255', 'admin', '1', 'Admin/Area/add', '添加', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('256', 'admin', '1', 'Admin/ItemComment/reply', '评价回复', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('257', 'admin', '1', 'Admin/ItemComment/update', '回复评价提交', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('258', 'admin', '1', 'Admin/User/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('259', 'admin', '1', 'Admin/User/edit', '修改', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('260', 'admin', '1', 'Admin/Article/setStatus', '逻辑删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('261', 'admin', '1', 'Admin/Article/setFieldValue', '推荐设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('262', 'admin', '1', 'Admin/ArticleCategory/setStatus', '禁用启用', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('263', 'admin', '1', 'Admin/ArticleCategory/merge', '合并分类', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('264', 'admin', '1', 'Admin/ArticleCategory/move', '移动分类', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('265', 'admin', '1', 'Admin/ArticleCategory/operate', '移动合并初始化', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('266', 'admin', '1', 'Admin/DeliveryTpl/setStatus', '启用禁用', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('267', 'admin', '1', 'Admin/WechatTplMsg/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('268', 'admin', '1', 'Admin/WechatTplMsg/update', '保存', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('269', 'admin', '1', 'Admin/WechatTplMsg/setStatus', '状态设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('270', 'admin', '1', 'Admin/Withdraw/update', '处理提现', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('271', 'admin', '1', 'Admin/Shop/setStatus', '修改状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('272', 'admin', '1', 'Admin/Withdraw/edit', '提现页面', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('273', 'admin', '1', 'Admin/Menu/setFieldValue', '修改排序', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('274', 'admin', '1', 'Admin/Withdraw/export', '导出', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('275', 'admin', '1', 'Admin/ItemComment/add', '添加', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('276', 'admin', '1', 'Admin/ItemComment/setStatus', '设置状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('277', 'admin', '1', 'Admin/ItemComment/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('278', 'admin', '1', 'Admin/DeliveryTpl/setFieldValue', '更新字段', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('279', 'admin', '1', 'Admin/Supplier/index', '供应商信息', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('280', 'admin', '1', 'Admin/Supplier/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('281', 'admin', '1', 'Admin/Supplier/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('282', 'admin', '1', 'Admin/Supplier/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('283', 'admin', '1', 'Admin/Supplier/setStatus', '更改状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('284', 'admin', '1', 'Admin/Supplier/detail', '二维码详情', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('285', 'admin', '1', 'Admin/Supplier/setFieldValue', '更新字段', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('286', 'admin', '1', 'Admin/Payment/preview', '收款信息预览', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('287', 'admin', '1', 'Admin/Union/add', '增加', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('288', 'admin', '1', 'Admin/Union/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('289', 'admin', '1', 'Admin/Union/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('290', 'admin', '1', 'Admin/Union/detail', '查看二维码', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('291', 'admin', '1', 'Admin/Union/setStatus', '状态设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('292', 'admin', '1', 'Admin/Advertise/setFieldValue', '排序', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('293', 'admin', '1', 'Admin/Manjian/setStatus', '设置状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('294', 'admin', '1', 'Admin/Manjian/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('295', 'admin', '1', 'Admin/Manjian/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('296', 'admin', '1', 'Admin/Manjian/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('297', 'admin', '1', 'Admin/BuySend/setStatus', '启用禁用', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('298', 'admin', '1', 'Admin/BuySend/update', '保存', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('299', 'admin', '1', 'Admin/BuySend/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('300', 'admin', '1', 'Admin/BuySend/add', '添加', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('301', 'admin', '1', 'Admin/SecondPieces/setStatus', '更改状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('302', 'admin', '1', 'Admin/SecondPieces/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('303', 'admin', '1', 'Admin/SecondPieces/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('304', 'admin', '1', 'Admin/SecondPieces/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('305', 'admin', '1', 'Admin/Message/preview', '预览', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('306', 'admin', '1', 'Admin/Message/detail', '查看', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('307', 'admin', '1', 'Admin/Message/setStatus', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('308', 'admin', '1', 'Admin/Message/update', '发送消息', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('309', 'admin', '1', 'Admin/Seckill/setStatus', '更改状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('310', 'admin', '1', 'Admin/Seckill/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('311', 'admin', '1', 'Admin/Seckill/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('312', 'admin', '1', 'Admin/Seckill/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('313', 'admin', '1', 'Admin/Message/add', '新增发布', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('314', 'admin', '1', 'Admin/Fugou/add', '新增', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('315', 'admin', '1', 'Admin/Fugou/edit', '编辑', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('316', 'admin', '1', 'Admin/Fugou/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('317', 'admin', '1', 'Admin/Fugou/setStatus', '状态', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('318', 'admin', '1', 'Admin/Order/printItem', '打印清单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('319', 'admin', '1', 'Admin/Shop/detail', '详情', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('320', 'admin', '1', 'Admin/Shop/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('321', 'admin', '1', 'Admin/Item/setFieldValue', '更新字段值', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('322', 'admin', '1', 'Admin/Order/bestmart', '灵通打单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('323', 'admin', '1', 'Admin/RedPackage/add', '添加', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('324', 'admin', '1', 'Admin/RedPackage/update', '保存', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('325', 'admin', '1', 'Admin/RedPackage/setStatus', '禁用删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('326', 'admin', '1', 'Admin/RedPackage/qrcode', '二维码', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('327', 'admin', '1', 'Admin/RedPackage/detail', '详情', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('328', 'admin', '1', 'Admin/User/scoreAdd', '积分更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('329', 'admin', '1', 'Admin/Shop/index', '店铺列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('330', 'admin', '1', 'Admin/ArticleCategory/index', '文章分类', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('331', 'admin', '1', 'Admin/UserGroup/index', '会员等级', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('332', 'admin', '1', 'Admin/Stat/goods', '商品销量', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('333', 'admin', '1', 'Admin/RedPackage/index', '红包', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('334', 'admin', '1', 'Admin/WechatMenu/update', '更新', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('335', 'admin', '1', 'Admin/Withdraw/index', '提现列表', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('336', 'admin', '1', 'Admin/Stat/sdpAmount', '分销返现', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('337', 'admin', '1', 'Admin/ItemComment/index', '商品评价', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('338', 'admin', '1', 'Admin/Item/updateSearchIndex', '更新搜索索引', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('339', 'admin', '1', 'Admin/ArticleCategory/remove', '删除', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('340', 'admin', '1', 'Admin/Sms/index', '短信平台', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('341', 'admin', '1', 'Admin/User/score', '用户积分记录', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('342', 'admin', '1', 'Admin/Area/index', '配送区域', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('343', 'admin', '1', 'Admin/Union/index', '联盟管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('344', 'admin', '1', 'Admin/Manjian/index', '满减管理', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('345', 'admin', '1', 'Admin/SecondPieces/index', '第二件折扣', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('346', 'admin', '1', 'Admin/Message/index', '站内消息', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('347', 'admin', '1', 'Admin/BuySend/index', '买送', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('348', 'admin', '1', 'Admin/Seckill/index', '秒杀活动', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('349', 'admin', '1', 'Admin/Fugou/index', '老客户立减', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('350', 'admin', '1', 'Admin/WechatTplMsg/index', '模板消息设置', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('351', 'admin', '1', 'Admin/Crowdfunding/order', '众筹订单', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('352', 'admin', '1', 'Admin/Crowdfunding/user', '众筹用户', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('353', 'admin', '2', 'Admin/#', '我要分销', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('354', 'admin', '1', 'Admin/CouponUser/index', '优惠券发放记录', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('355', 'admin', '2', 'Admin/', '', '-1', '');
INSERT INTO `jipu_auth_rule` VALUES ('356', 'admin', '1', 'Admin/User/recycle', '用户回收站', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('357', 'admin', '1', 'Admin/Join/index', '拼团', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('358', 'admin', '1', 'Admin/User/accountcost', '用户现金流水', '1', '');
INSERT INTO `jipu_auth_rule` VALUES ('359', 'admin', '1', 'Admin/Ship/setStatus', '删除', '1', '');

-- ----------------------------
-- Table structure for jipu_buy_send
-- ----------------------------
DROP TABLE IF EXISTS `jipu_buy_send`;
CREATE TABLE `jipu_buy_send` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT '0' COMMENT '活动主商品',
  `min_num` smallint(6) DEFAULT '0' COMMENT '主商品最低购买数量',
  `send_item` varchar(255) DEFAULT '0' COMMENT '赠品信息',
  `start_time` int(10) DEFAULT '0' COMMENT '开始时间',
  `expire_time` int(10) DEFAULT '0' COMMENT '过期时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1正常 0已禁用 -1已删除）',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_buy_send
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_card
-- ----------------------------
DROP TABLE IF EXISTS `jipu_card`;
CREATE TABLE `jipu_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `number` varchar(255) NOT NULL DEFAULT '' COMMENT '卡号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '面值',
  `balance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '生成时间',
  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '截止时间',
  `bind_time` int(11) NOT NULL DEFAULT '0' COMMENT '绑定时间',
  `use_time` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间（第一次）',
  `is_expire` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否过期（0：未过期，1：已过期）',
  `is_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否绑定（0：未绑定，1：已绑定）',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用（0：未使用，1：已使用）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='礼品卡';

-- ----------------------------
-- Records of jipu_card
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_card_log
-- ----------------------------
DROP TABLE IF EXISTS `jipu_card_log`;
CREATE TABLE `jipu_card_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `card_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '礼品卡ID',
  `card_number` varchar(255) NOT NULL DEFAULT '' COMMENT '礼品卡卡号',
  `card_name` varchar(255) NOT NULL DEFAULT '' COMMENT '礼品卡名称',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '使用金额',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='礼品卡使用日志';

-- ----------------------------
-- Records of jipu_card_log
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_card_user
-- ----------------------------
DROP TABLE IF EXISTS `jipu_card_user`;
CREATE TABLE `jipu_card_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `card_id` int(11) NOT NULL DEFAULT '0' COMMENT '卡ID',
  `from` varchar(255) DEFAULT NULL COMMENT '来源（网上购买，线下购买，商家赠送，朋友赠送）',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '礼品卡状态：0-未使用，1-已使用',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `use_time` int(10) NOT NULL DEFAULT '0' COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的礼品卡';

-- ----------------------------
-- Records of jipu_card_user
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_cart
-- ----------------------------
DROP TABLE IF EXISTS `jipu_cart`;
CREATE TABLE `jipu_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `item_code` varchar(255) NOT NULL DEFAULT '' COMMENT '商品编码（未设置规格：用商品编码；已设置规格：用规格组合码）',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `item_pid` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类id',
  `supplier_id` int(11) DEFAULT '0' COMMENT '供应商ID',
  `number` varchar(255) NOT NULL DEFAULT '' COMMENT '商品编号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `spec` text NOT NULL COMMENT '商品规格组合信息',
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品重量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `thumb` smallint(5) NOT NULL COMMENT '商品图片',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sdp_code` char(32) DEFAULT '' COMMENT '分销商编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='购物车信息表';

-- ----------------------------
-- Records of jipu_cart
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_channel
-- ----------------------------
DROP TABLE IF EXISTS `jipu_channel`;
CREATE TABLE `jipu_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `brief` varchar(255) NOT NULL DEFAULT '' COMMENT '简述',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '导航图标',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `position` tinyint(3) NOT NULL DEFAULT '0' COMMENT '导航位置',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态（0：不显示，1：显示）',
  `target` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_channel
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_config
-- ----------------------------
DROP TABLE IF EXISTS `jipu_config`;
CREATE TABLE `jipu_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_config
-- ----------------------------
INSERT INTO `jipu_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '1', '', '网站标题前台显示标题', '1378898976', '1422244389', '1', '', '10', '0');
INSERT INTO `jipu_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '2', '网站描述', '1', '', '网站搜索引擎描述', '1378898976', '1422244440', '1', '', '20', '0');
INSERT INTO `jipu_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1378898976', '1422244395', '1', '', '30', '0');
INSERT INTO `jipu_config` VALUES ('4', 'WEB_SITE_CLOSE', '4', '站点运行状态', '1', '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', '1378898976', '1422244408', '1', '1', '70', '0');
INSERT INTO `jipu_config` VALUES ('9', 'CONFIG_TYPE_LIST', '3', '配置类型列表', '5', '', '主要用于数据解析和页面表单的生成', '1378898976', '1398999494', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片上传\r\n6:时间\r\n7:编辑器', '2', '0');
INSERT INTO `jipu_config` VALUES ('10', 'WEB_SITE_ICP', '1', '网站备案号', '1', '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', '1378900335', '1422244428', '1', '', '60', '0');
INSERT INTO `jipu_config` VALUES ('11', 'DOCUMENT_POSITION', '3', '文档推荐位', '3', '', '文档推荐位，推荐到多个位置KEY值相加即可', '1379053380', '1398999367', '1', '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', '8', '0');
INSERT INTO `jipu_config` VALUES ('12', 'DOCUMENT_DISPLAY', '3', '文档可见性', '3', '', '文章可见性仅影响前台显示，后台不收影响', '1379056370', '1398999377', '1', '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', '9', '0');
INSERT INTO `jipu_config` VALUES ('13', 'COLOR_STYLE', '4', '后台色系', '1', 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', '1379122533', '1422244434', '1', 'default_color', '80', '0');
INSERT INTO `jipu_config` VALUES ('20', 'CONFIG_GROUP_LIST', '3', '配置分组', '5', '', '配置分组', '1379228036', '1464161625', '1', '1:基本\r\n2:公众号\r\n3:内容\r\n4:用户\r\n5:系统\r\n6:支付', '4', '0');
INSERT INTO `jipu_config` VALUES ('21', 'HOOKS_TYPE', '3', '钩子的类型', '5', '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', '1379313397', '1398999542', '1', '1:视图\r\n2:控制器', '6', '0');
INSERT INTO `jipu_config` VALUES ('22', 'AUTH_CONFIG', '3', 'Auth配置', '5', '', '自定义Auth.class.php类配置', '1379409310', '1398999556', '1', 'AUTH_ON:1\r\nAUTH_TYPE:2', '8', '0');
INSERT INTO `jipu_config` VALUES ('23', 'OPEN_DRAFTBOX', '4', '是否开启草稿功能', '3', '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', '1379484332', '1398999307', '1', '1', '6', '0');
INSERT INTO `jipu_config` VALUES ('24', 'DRAFT_AOTOSAVE_INTERVAL', '0', '自动保存草稿时间', '3', '', '自动保存草稿的时间间隔，单位：秒', '1379484574', '1398999340', '1', '60', '7', '0');
INSERT INTO `jipu_config` VALUES ('25', 'LIST_ROWS', '0', '后台每页记录数', '3', '', '后台数据每页显示记录数', '1379503896', '1398999388', '1', '15', '10', '0');
INSERT INTO `jipu_config` VALUES ('26', 'USER_ALLOW_REGISTER', '4', '是否允许用户注册', '4', '0:关闭注册\r\n1:允许注册', '是否开放用户注册', '1379504487', '1398999654', '1', '1', '3', '0');
INSERT INTO `jipu_config` VALUES ('27', 'CODEMIRROR_THEME', '4', '预览插件的CodeMirror主题', '5', '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', '1379814385', '1398999521', '1', 'ambiance', '3', '0');
INSERT INTO `jipu_config` VALUES ('28', 'DATA_BACKUP_PATH', '1', '数据库备份根路径', '5', '', '路径必须以 / 结尾', '1381482411', '1398999535', '1', './Data/', '5', '0');
INSERT INTO `jipu_config` VALUES ('29', 'DATA_BACKUP_PART_SIZE', '0', '数据库备份卷大小', '5', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '1381482488', '1398999549', '1', '20971520', '7', '0');
INSERT INTO `jipu_config` VALUES ('30', 'DATA_BACKUP_COMPRESS', '4', '数据库备份文件是否启用压缩', '5', '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1398999565', '1', '1', '9', '0');
INSERT INTO `jipu_config` VALUES ('31', 'DATA_BACKUP_COMPRESS_LEVEL', '4', '数据库备份文件压缩级别', '5', '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1398999573', '1', '9', '10', '0');
INSERT INTO `jipu_config` VALUES ('33', 'ALLOW_VISIT', '3', '不受限控制器方法', '4', '', '', '1386644047', '1398999614', '1', '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture\r\n11:Addons/execute\r\n12:ItemProperty/getprop', '0', '1');
INSERT INTO `jipu_config` VALUES ('34', 'DENY_VISIT', '3', '超管专限控制器方法', '4', '', '仅超级管理员可访问的控制器方法', '1386644141', '1398999624', '1', '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', '0', '1');
INSERT INTO `jipu_config` VALUES ('35', 'REPLY_LIST_ROWS', '0', '回复列表每页条数', '3', '', '', '1386645376', '1398999289', '1', '10', '1', '0');
INSERT INTO `jipu_config` VALUES ('36', 'ADMIN_ALLOW_IP', '2', '后台允许访问IP', '5', '', '多个用逗号分隔，如果不配置表示不限制IP访问', '1387165454', '1398999586', '1', '', '55', '0');
INSERT INTO `jipu_config` VALUES ('37', 'SHOW_PAGE_TRACE', '4', '是否显示页面Trace', '5', '0:关闭\r\n1:开启', '是否显示页面Trace信息', '1387165685', '1398999444', '1', '0', '1', '1');
INSERT INTO `jipu_config` VALUES ('38', 'WECHAT_NAME', '1', '公众号名称', '2', '', '填写微信公众号名称', '1399000537', '1399000681', '1', '', '1', '0');
INSERT INTO `jipu_config` VALUES ('39', 'WECHAT_ID', '1', '公众号原始ID', '2', '', '填写微信公众号原始ID', '1399000623', '1399000700', '1', '', '2', '0');
INSERT INTO `jipu_config` VALUES ('40', 'WECHAT_NUMBER', '1', '微信号', '2', '', '例如：lentu123', '1399000793', '1399000820', '1', '', '3', '0');
INSERT INTO `jipu_config` VALUES ('41', 'WEICHAT_TYPE', '4', '公众号类型', '2', '1:订阅号\r\n2:服务号', '选择公众号类型', '1399000957', '1399001017', '1', '2', '4', '0');
INSERT INTO `jipu_config` VALUES ('42', 'WECHAT_APPID', '1', 'AppId', '2', '', '认证服务号的AppId', '1399001093', '1399106073', '1', '', '5', '0');
INSERT INTO `jipu_config` VALUES ('43', 'WECHAT_SECRET', '1', 'Secret', '2', '', '认证服务号的Secret', '1399001153', '1399001153', '1', '', '6', '0');
INSERT INTO `jipu_config` VALUES ('44', 'WECHAT_AVATAR_URL', '5', '公众号头像', '2', '', '请填写公众号头像', '1399001324', '1468983727', '1', '', '7', '0');
INSERT INTO `jipu_config` VALUES ('45', 'WECHAT_TOKEN', '1', '微信接口Token', '2', '', '请设置6-10位的接口token，支持数字、英文字母', '1399005521', '1399005521', '1', 'jipu', '9', '0');
INSERT INTO `jipu_config` VALUES ('46', 'ADVERTISE_TYPE', '3', '广告位配置', '3', '', '广告位配置', '1402218152', '1463067109', '1', '1:首页幻灯广告\r\n2:首页通栏小广告', '2', '0');
INSERT INTO `jipu_config` VALUES ('107', 'WEB_INVITE_LOGO', '5', '邀请LOGO上传', '3', '', '', '1465369658', '1465369658', '1', '', '120', '0');
INSERT INTO `jipu_config` VALUES ('47', 'WEB_SITE_TEL', '1', '客服热线', '1', '', '客服电话，显示在网站右上角', '1405958039', '1422244446', '1', '', '40', '0');
INSERT INTO `jipu_config` VALUES ('48', 'SALE_ACTIVITY_TYPE', '3', '专场类别', '5', '', '', '1405991958', '1405991958', '1', '', '0', '0');
INSERT INTO `jipu_config` VALUES ('49', 'SALE_ACTIVITY_THEME', '3', '专场模板配置', '5', '', '', '1405991989', '1405991989', '1', 'default:默认模板\r\ngreen:绿色\r\nblue:蓝色\r\ndark:灰色', '0', '0');
INSERT INTO `jipu_config` VALUES ('50', 'CHANNEL_POSITION', '3', '导航位置', '3', '', '导航所在页面位置', '1406302749', '1406302749', '1', '1:顶部导航\r\n2:栏目导航\r\n3:图标导航\r\n4:帮助导航\r\n5:底部导航', '3', '0');
INSERT INTO `jipu_config` VALUES ('51', 'SEARCH_KEYWORDS', '3', '搜索下拉建议', '3', '母婴\r\n手机\r\n尿不湿', '设置搜索框下拉建议内容', '1406957421', '1463066952', '1', '', '5', '0');
INSERT INTO `jipu_config` VALUES ('52', 'SEARCH_KEYWORDS_INPUT', '3', '推荐搜索关键词', '3', '', '设置搜索框内的推荐关键词', '1406957765', '1406957765', '1', '1:第二件半价\r\n2:分销商品\r\n3:活动', '4', '0');
INSERT INTO `jipu_config` VALUES ('53', 'USER_AUTO_LOGIN_DAYS', '0', '自动登录天数', '4', '', '设置用户自动登录天数', '1407139357', '1407139357', '1', '30', '1', '0');
INSERT INTO `jipu_config` VALUES ('54', 'COUPON_AMOUNT', '3', '优惠券面值', '5', '', '设置优惠券面值', '1409719476', '1409719476', '1', '10:10元\r\n20:20元\r\n30:30元\r\n40:40元\r\n50:50元\r\n100:100元', '0', '0');
INSERT INTO `jipu_config` VALUES ('55', 'RAND_CODE_INTERVEL', '0', '手机验证码间隔时间', '5', '', '再次发送手机验证码的间隔时间，单位：秒', '1410867144', '1426065586', '1', '60', '0', '0');
INSERT INTO `jipu_config` VALUES ('56', 'NOTIFY_TYPE', '3', '消息通知类型', '5', '', '消息通知类型配置', '1413357616', '1413357678', '1', '1:邮件\r\n2:短信', '0', '0');
INSERT INTO `jipu_config` VALUES ('57', 'DELIVERY_SEND_DATE', '3', '物流发货时间', '5', '', '', '1415194439', '1421199136', '1', '4:4小时\r\n8:8小时\r\n12:12小时\r\n16:16小时\r\n24:1天内\r\n48:2天内', '0', '0');
INSERT INTO `jipu_config` VALUES ('58', 'DELIVERY_FREE_AMOUNT', '0', '物流免运费限额', '5', '', '满多少元可免运费', '1421199184', '1421199184', '1', '60', '0', '0');
INSERT INTO `jipu_config` VALUES ('59', 'WEB_SITE_SATACODE', '2', '网站统计代码', '1', '', '放置百度、CNZZ等第三方统计代码', '1421207793', '1422244401', '1', '', '50', '0');
INSERT INTO `jipu_config` VALUES ('60', 'SYSTEM_MOBILE_NO', '1', '系统管理员手机号码', '1', '', '必要时发送短信通知管理员', '1422244523', '1422244694', '1', '', '45', '0');
INSERT INTO `jipu_config` VALUES ('61', 'NEWORDER_SMS_NOTIFY_ADMIN', '4', '新订单短信通知管理员', '1', '0:关闭,1:开启', '开启后，有新的订单，会短信通知管理员', '1422244751', '1422244751', '1', '1', '46', '0');
INSERT INTO `jipu_config` VALUES ('62', 'REGISTER_MOBILE_VALID', '4', '验证手机号', '4', '0:不需要\r\n1:需要', '用户在注册时，是否需要验证真实性', '1423045111', '1423045111', '1', '0', '10', '0');
INSERT INTO `jipu_config` VALUES ('63', 'INVITE_REWARD_MONEY', '0', '邀请注册奖励金额（元）', '4', '', '用户邀请注册奖励', '1423473363', '1423473468', '1', '1.6', '11', '0');
INSERT INTO `jipu_config` VALUES ('64', 'CROWDFUNDING_EXPIRE_TIME', '4', '众筹截止时间', '5', '0:不限时间\r\n3:众筹3天\r\n7:众筹7天\r\n15:众筹15天\r\n30:众筹30天', '众筹订单截止时间，0为不限时间，不设截止日期', '1423553199', '1423553199', '1', '3', '0', '0');
INSERT INTO `jipu_config` VALUES ('65', 'REDPACKET_TIME', '0', '领红包时限', '5', '', '发出的红包，多长时限过期', '1423553262', '1423553262', '1', '3', '0', '0');
INSERT INTO `jipu_config` VALUES ('66', 'INVITE_MAX_PEOPLE', '0', '邀请注册返现每天限制最多人数', '4', '', '邀请注册返现每天限制最多人数', '1426664944', '1426664960', '1', '1', '20', '0');
INSERT INTO `jipu_config` VALUES ('67', 'MAX_CONFIRM_RECEIPT_DAY', '0', '自动确认收货时限', '5', '', '超出该天数，系统自动确认收货', '1429176188', '1429176188', '1', '7', '0', '0');
INSERT INTO `jipu_config` VALUES ('68', 'IS_SHOW_BUYNUM', '4', '是否显示商品购买数量', '5', '0:关闭,1:开启', '是否显示商品购买数量', '1426664944', '1426664960', '1', '1', '1', '0');
INSERT INTO `jipu_config` VALUES ('69', 'YUNPIAN_COMPANY', '1', '短信签名', '5', '', '', '1434534769', '1434534769', '1', '极铺科技', '0', '0');
INSERT INTO `jipu_config` VALUES ('70', 'SMS_TPL_ID', '3', '短信模板配置映射', '5', '', '', '1435743487', '1461897179', '1', 'CODE:1\r\nSAFE_CODE:2\r\nFIND_PASS_CODE:7\r\nREG_CODE:5\r\nHOUR_CODE:4\r\nNEWORDER_NOTIFY:1349565\r\nSENDORDER_NOTIFY:1349599\r\nCOMPLETEORDER_NOTIFY:12\r\nBACKORDER_NOTIFY:13\r\nACCEPTBACKORDER_NOTIFY:14', '0', '0');
INSERT INTO `jipu_config` VALUES ('71', 'WECHATTPLMSGCOLOR', '1', '微信模板消息颜色', '0', '', '微信模板消息颜色', '1438741974', '1438746276', '1', '#ed5b11', '0', '0');
INSERT INTO `jipu_config` VALUES ('72', 'BANK_LISTS', '3', '提现银行', '0', '', '', '1438914349', '1438917097', '1', 'ICBCB2C:中国工商银行\r\nCCB:中国建设银行\r\nABC:中国农业银行\r\nCMB:招商银行\r\nBOC-DEBIT:中国银行\r\nCOMM:交通银行\r\nPSBC-DEBIT:中国邮政储蓄银行\r\nGDB:广发银行\r\nSPDB:浦发银行\r\nSPABANK:平安银行\r\nCIB:兴业银行\r\nCMBC:中国民生银行\r\nSHBANK:上海银行\r\nBJRCB:北京农商银行\r\nHZCBB2C:杭州银行', '0', '0');
INSERT INTO `jipu_config` VALUES ('73', 'EXPRESS_LISTS', '3', '快递公司列表', '3', '', '', '1440382185', '1440382185', '1', '1:百世汇通\r\n2:申通\r\n3:韵达\r\n4:圆通\r\n5:中通\r\n6:EMS\r\n7:顺丰\r\n8:国通\r\n9:天天快递\r\n10:宅急送\r\n11:邮政小包\r\n12:全峰快递', '10', '0');
INSERT INTO `jipu_config` VALUES ('74', 'WEB_SITE_SUB_TITLE', '1', '网站副标题', '1', '', '显示在网站标题后面', '1441591921', '1441591952', '1', '', '2', '0');
INSERT INTO `jipu_config` VALUES ('75', 'SUPPLIER_GROUP_ID', '0', '供应商组ID', '0', '', '供应商组ID', '1441766372', '1469425268', '1', '13', '0', '0');
INSERT INTO `jipu_config` VALUES ('76', 'WECHAT_DEFAULT_TEXT', '2', '微信接收到消息默认返回内容', '2', '', '', '1445068181', '1445068320', '1', '您好，您的留言信息我们已经收到，我们会第一时间回复您，感谢您的支持！', '10', '0');
INSERT INTO `jipu_config` VALUES ('77', 'UNION_SUBSCRIBE_CASHBACK', '0', '推广联盟关注返现金额', '5', '', '单位：元', '1445220735', '1445220803', '1', '0.01', '11', '0');
INSERT INTO `jipu_config` VALUES ('78', 'UNION_ORDER_CASHBACK', '0', '推广联盟订单返现金额', '5', '', '单位：元', '1445220735', '1445220803', '1', '0.01', '13', '0');
INSERT INTO `jipu_config` VALUES ('79', 'WECHAT_INDEX_SHARE_TITLE', '1', '微信版首页分享标题', '1', '', '', '1446859095', '1446859095', '1', '', '12', '0');
INSERT INTO `jipu_config` VALUES ('80', 'WECHAT_INDEX_SHARE_DESC', '2', '微信版首页分享描述', '1', '', '', '1446859120', '1446859120', '1', '', '13', '0');
INSERT INTO `jipu_config` VALUES ('81', 'MEMBER_INDEX_SHARE_TITLE', '1', '微信个人中心首页分享标题', '1', '', '', '1447041122', '1447041122', '1', '', '15', '0');
INSERT INTO `jipu_config` VALUES ('82', 'MEMBER_INDEX_SHARE_DESC', '2', '微信个人中心首页分享描述', '1', '', '', '1447041159', '1447041159', '1', '', '17', '0');
INSERT INTO `jipu_config` VALUES ('83', 'ARTICLE_JUMP_CID', '0', '文章跳转分类', '0', '', '公众号跳转文章', '1449306546', '1463990444', '1', '1', '0', '0');
INSERT INTO `jipu_config` VALUES ('84', 'SCORE_EXCHANGE_STATUS', '4', '积分支付开关', '5', '0:关闭\r\n1:开启', '关闭后，积分不参与支付', '1451546156', '1463966729', '1', '1', '8', '0');
INSERT INTO `jipu_config` VALUES ('85', 'SMS_YUNPIAN', '3', '短信云盘设置', '5', '短信云盘设置', '', '1453969881', '1453969881', '1', 'APIKEY:\r\nSEND_TEST:', '0', '0');
INSERT INTO `jipu_config` VALUES ('86', 'SDP_IS_OPEN', '4', '推广员管理', '4', '0:关闭\r\n1:开启', '', '1456901438', '1463807650', '1', '1', '0', '0');
INSERT INTO `jipu_config` VALUES ('87', 'THINK_SDK_QQ', '3', '第三方登陆（QQ）', '4', '', '', '1458191402', '1458208080', '1', 'APP_KEY:\r\nAPP_SECRET:\r\nCALLBACK:\r\nAUTHORIZE:', '0', '0');
INSERT INTO `jipu_config` VALUES ('88', 'THINK_SDK_SINA', '3', '第三方登陆（微博）', '4', '', '', '1458208213', '1458208213', '1', 'APP_KEY:\r\nAPP_SECRET:\r\nCALLBACK:\r\nAUTHORIZE:', '0', '0');
INSERT INTO `jipu_config` VALUES ('89', 'ITEM_CATEGORY_TRAIT', '3', '商品分类推荐属性', '3', '', '', '1458542430', '1458542535', '1', '2:首页模块', '0', '0');
INSERT INTO `jipu_config` VALUES ('90', 'OPEN_WEIXIN_LOGIN', '4', '微信自动登陆', '4', '0:关闭\r\n1:开启', '需微信开放平台审枋通过后开启\r\n回调地址：host/User/wechatcallback.html', '1460004644', '1460345020', '1', '1', '0', '0');
INSERT INTO `jipu_config` VALUES ('91', 'CROWDFUNDING_SHEAR_CONTENT', '0', '众筹分享默认内容', '5', '', '', '1461292678', '1461292772', '1', '在家靠父母，出门靠朋友！', '0', '0');
INSERT INTO `jipu_config` VALUES ('92', 'WECHATPAY', '3', '微信支付', '6', '', '', '1464161729', '1464240642', '1', 'app_id:\r\napp_secret:\r\nmch_id:\r\napp_key:', '6', '0');
INSERT INTO `jipu_config` VALUES ('93', 'ALIPAYWAP', '3', '支付宝手机支付配置', '6', '', '', '1464161776', '1464161776', '1', 'email:\r\nkey:\r\npartner:', '3', '0');
INSERT INTO `jipu_config` VALUES ('94', 'BANKPAY', '3', '网银支付', '6', '', '网银支付配置', '1464161827', '1464240549', '1', 'email:\r\nkey:\r\npartner:', '11', '0');
INSERT INTO `jipu_config` VALUES ('95', 'ALIPAY', '3', '支付宝配置', '6', '', '支付宝支付账号等配置', '1464161864', '1464240766', '1', 'email:\r\nkey:\r\npartner:', '2', '0');
INSERT INTO `jipu_config` VALUES ('96', 'COPYRIGHT', '1', '版权信息', '1', '', '站点版权信息', '1464166680', '1464166680', '1', '', '0', '0');
INSERT INTO `jipu_config` VALUES ('97', 'LOGO', '5', '网站logo ', '1', '', '网站logo', '1464177681', '1464178499', '1', '', '100', '0');
INSERT INTO `jipu_config` VALUES ('98', 'WAP_LOGO', '5', '手机端logo', '1', '', '', '1464235512', '1464235512', '1', '', '0', '0');
INSERT INTO `jipu_config` VALUES ('99', 'BANKPAY_STATUS', '4', '网银支付状态', '6', '0:关闭\r\n1:开启', '网银支付开关', '1464240578', '1464240578', '1', '1', '9', '0');
INSERT INTO `jipu_config` VALUES ('100', 'WXPAY_STATUS', '4', '微信支付状态', '6', '0:关闭\r\n1:开启', '微信支付开关', '1464240721', '1464240721', '1', '1', '5', '0');
INSERT INTO `jipu_config` VALUES ('101', 'ALIPAY_STATUS', '4', '支付宝支付状态', '6', '0:关闭\r\n1:开启', '关闭和开启支付宝', '1464240818', '1464240818', '1', '1', '1', '0');
INSERT INTO `jipu_config` VALUES ('102', 'WX_QRCODE', '5', '公众号二维码', '1', '', '微信公众号二维码', '1464243835', '1464243835', '1', '', '99', '0');
INSERT INTO `jipu_config` VALUES ('103', 'WAP_QRCODE', '5', '3g版二维码', '1', '', '二维码图片', '1464243896', '1464243896', '1', '', '100', '0');
INSERT INTO `jipu_config` VALUES ('104', 'SERVE_QQ', '1', '售后服务QQ', '1', '', '', '1464253617', '1464253617', '1', '', '100', '0');
INSERT INTO `jipu_config` VALUES ('105', 'ASK_QQ', '1', '咨询QQ', '1', '', '', '1464253648', '1464253667', '1', '', '101', '0');
INSERT INTO `jipu_config` VALUES ('106', 'SCORE_EXCHANGE_NUMBER', '0', '积分抵扣', '6', '', '此处填写积分。比如填写100，说明用户100个积分抵扣1元人民币', '1464342634', '1464744897', '1', '100', '32', '0');
INSERT INTO `jipu_config` VALUES ('108', 'WEB_INVITE_TITLE', '1', '邀请标题', '3', '', '', '1465369694', '1465369707', '1', '邀请标题', '121', '0');
INSERT INTO `jipu_config` VALUES ('109', 'WEB_INVITE_DESC', '2', '邀请描述', '3', '', '', '1465369742', '1465369751', '1', '哈哈哈哈', '122', '0');
INSERT INTO `jipu_config` VALUES ('110', 'IS_RECEIPT', '4', '发票', '6', '0:关闭\r\n1:开启', '用户下单时，供用户选择是否需要发票', '1466489799', '1466489883', '1', '1', '33', '0');
INSERT INTO `jipu_config` VALUES ('111', 'AUTO_LOGIN', '4', '微信自动登录', '4', '0:关闭\r\n1:开启', '开启之后，首次微信登录的用户，系统自动创建账号', '1467095180', '1467095237', '1', '0', '30', '0');
INSERT INTO `jipu_config` VALUES ('112', 'INVOICE_TYPE', '3', '发票类型', '6', '', '多个请用“回车”键，换行分隔。发票类型不可随意改动', '1467614198', '1467614198', '1', '1:普通发票\r\n2:电子发票\r\n3:增值税发票', '35', '0');
INSERT INTO `jipu_config` VALUES ('113', 'INVOICE_CONTENT', '3', '发票内容', '6', '', '多个请用“回车”键，换行分隔', '1467644490', '1467644490', '1', '1:明细\r\n2:办公用品\r\n3:其他', '36', '0');
INSERT INTO `jipu_config` VALUES ('114', 'UNION_ORDER_RATEBACK', '0', '推广联盟订单返现比例', '5', '', '单位：%（优先采用百分比）', '1467701983', '1467701983', '1', '5', '12', '0');
INSERT INTO `jipu_config` VALUES ('115', 'JOIN_STATUS', '4', '拼团', '5', '0:关闭\r\n1:开启', '拼团开关', '1468320301', '1468320301', '1', '1', '0', '0');

-- ----------------------------
-- Table structure for jipu_coupon
-- ----------------------------
DROP TABLE IF EXISTS `jipu_coupon`;
CREATE TABLE `jipu_coupon` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `number` varchar(255) NOT NULL COMMENT '优惠券编号',
  `name` varchar(255) NOT NULL COMMENT '优惠券名称',
  `rule` varchar(255) NOT NULL COMMENT '使用规则',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠券金额',
  `items` varchar(255) NOT NULL DEFAULT '0' COMMENT '优惠券适用商品列表',
  `norm` int(10) NOT NULL DEFAULT '0' COMMENT '优惠券适用限额',
  `num` int(10) NOT NULL DEFAULT '100000' COMMENT '优惠券数量，默认100000张',
  `expire_time` int(10) NOT NULL COMMENT '过期时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_expire` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否过期',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '优惠券状态：1-可用，0-禁用',
  `is_show` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否前台显示，1为显示，0为不显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券表';

-- ----------------------------
-- Records of jipu_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_coupon_num
-- ----------------------------
DROP TABLE IF EXISTS `jipu_coupon_num`;
CREATE TABLE `jipu_coupon_num` (
  `cn_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `cn_couponuser_id` int(11) unsigned DEFAULT '0' COMMENT '用户领取记录ID',
  `cn_coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `is_get` tinyint(1) unsigned DEFAULT '0' COMMENT '是否被领取（0：未领取，1：已领取）',
  `coupon_num` varchar(64) NOT NULL DEFAULT '' COMMENT '优惠券编码',
  PRIMARY KEY (`cn_id`),
  UNIQUE KEY `num` (`coupon_num`) USING BTREE,
  KEY `coupon_id` (`cn_coupon_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券编号表';

-- ----------------------------
-- Records of jipu_coupon_num
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_coupon_user
-- ----------------------------
DROP TABLE IF EXISTS `jipu_coupon_user`;
CREATE TABLE `jipu_coupon_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `coupon_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠券状态：1-已使用，0-未使用',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '发放时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '废弃字段---更新时间',
  `use_time` int(10) NOT NULL DEFAULT '0' COMMENT '使用时间',
  `use_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用金额',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券发放表';

-- ----------------------------
-- Records of jipu_coupon_user
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_crowdfunding_order
-- ----------------------------
DROP TABLE IF EXISTS `jipu_crowdfunding_order`;
CREATE TABLE `jipu_crowdfunding_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL COMMENT '用户id',
  `order_id` int(11) NOT NULL COMMENT '参与众筹的订单id',
  `msg` char(100) DEFAULT NULL COMMENT '众筹描述',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '众筹开始时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `success` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:众筹没成功或进行中；1:众筹成功；2:众筹超时，余额转入个人帐户',
  `expire_time` int(10) unsigned DEFAULT NULL COMMENT '众筹结束时间',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '众筹状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='众筹订单表';

-- ----------------------------
-- Records of jipu_crowdfunding_order
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_crowdfunding_users
-- ----------------------------
DROP TABLE IF EXISTS `jipu_crowdfunding_users`;
CREATE TABLE `jipu_crowdfunding_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `crowdfunding_id` int(11) unsigned NOT NULL COMMENT '众筹id',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单id',
  `pay_id` varchar(255) NOT NULL COMMENT '参与众筹用户个人支付id',
  `open_id` char(32) NOT NULL COMMENT '微信用户id',
  `username` char(16) DEFAULT NULL COMMENT '参与用户姓名',
  `msg` char(60) DEFAULT NULL COMMENT '参与用户留言',
  `pay_money` decimal(10,2) NOT NULL COMMENT '支付的金额',
  `payment_type` varchar(255) NOT NULL COMMENT '支付方式',
  `payment_status` int(2) NOT NULL DEFAULT '0' COMMENT '支付状态（0：未支付，1：已支付）',
  `roll_out` char(3) NOT NULL DEFAULT 'in' COMMENT '众筹资金是否转出（out:转出；in:没转出）',
  `roll_out_time` int(10) unsigned NOT NULL COMMENT '众筹金额转出时间',
  `payment_return` varchar(2000) NOT NULL COMMENT '支付平台返回的数据',
  `payment_time` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `create_time` int(10) DEFAULT NULL COMMENT '参与时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `expire_time` int(10) DEFAULT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='参与众筹用户表';

-- ----------------------------
-- Records of jipu_crowdfunding_users
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_delivery_tpl
-- ----------------------------
DROP TABLE IF EXISTS `jipu_delivery_tpl`;
CREATE TABLE `jipu_delivery_tpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) DEFAULT '0' COMMENT '供应商ID',
  `supplier_id` int(11) DEFAULT '0' COMMENT '供应商ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '模板名称（调用模版时用）',
  `company` varchar(255) NOT NULL COMMENT '快递公司名称',
  `send_date` tinyint(2) NOT NULL COMMENT '发货时间',
  `price_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '计价方式（1->按件数，2->按重量）',
  `express_unit` char(10) NOT NULL COMMENT '计算单位',
  `express_start` smallint(4) NOT NULL DEFAULT '1' COMMENT '初始数量',
  `express_postage` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '初始价格',
  `express_plus` smallint(4) NOT NULL DEFAULT '1' COMMENT '增加N件',
  `express_postageplus` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '增加价格',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-禁用，1-启用，-1-逻辑删除',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='物流运费模版';

-- ----------------------------
-- Records of jipu_delivery_tpl
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_fav
-- ----------------------------
DROP TABLE IF EXISTS `jipu_fav`;
CREATE TABLE `jipu_fav` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户uid',
  `type` varchar(50) NOT NULL COMMENT '收藏类型',
  `fid` int(10) NOT NULL DEFAULT '0' COMMENT '收藏对应数据id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '发放时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏表';

-- ----------------------------
-- Records of jipu_fav
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_file
-- ----------------------------
DROP TABLE IF EXISTS `jipu_file`;
CREATE TABLE `jipu_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表';

-- ----------------------------
-- Records of jipu_file
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_finance
-- ----------------------------
DROP TABLE IF EXISTS `jipu_finance`;
CREATE TABLE `jipu_finance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '交易类型（alipay:支付宝、alipaywap:手机支付宝、bankpay:网银、wechatpay:微信、redpacket_receive:抢到的红包、redpacket_send:发出红包没抢完的余额、crowdfunding:众筹、website:站内、invite_reward:邀请奖励）',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '交易金额',
  `flow` varchar(10) NOT NULL DEFAULT '' COMMENT '资金流向（in：收入，out：支出）',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='帐户余额交易明细表';

-- ----------------------------
-- Records of jipu_finance
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_find_password
-- ----------------------------
DROP TABLE IF EXISTS `jipu_find_password`;
CREATE TABLE `jipu_find_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `email` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT '用户email',
  `code` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT '改密字符串',
  `is_used` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已使用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_find_password
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_fugou
-- ----------------------------
DROP TABLE IF EXISTS `jipu_fugou`;
CREATE TABLE `jipu_fugou` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `item_name` varchar(200) DEFAULT '' COMMENT '商品名称',
  `dis_price` decimal(6,2) DEFAULT '0.00' COMMENT '优惠金额',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1启用 0禁用-1已删除）',
  `create_time` int(10) DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) DEFAULT '0' COMMENT '修改日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_fugou
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_hooks
-- ----------------------------
DROP TABLE IF EXISTS `jipu_hooks`;
CREATE TABLE `jipu_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_hooks
-- ----------------------------
INSERT INTO `jipu_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', 'Language', '1');
INSERT INTO `jipu_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop', '1');
INSERT INTO `jipu_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', '', '1');
INSERT INTO `jipu_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', '', '1');
INSERT INTO `jipu_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', '', '1');
INSERT INTO `jipu_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', '', '1');
INSERT INTO `jipu_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', '', '1');
INSERT INTO `jipu_hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin', '1');
INSERT INTO `jipu_hooks` VALUES ('9', 'adminIndex', '首页小格子个性化显示', '1', '1421060479', 'SiteStat,UserGuide', '1');
INSERT INTO `jipu_hooks` VALUES ('10', 'topicComment', '评论提交方式扩展钩子。', '1', '1380163518', '', '1');
INSERT INTO `jipu_hooks` VALUES ('11', 'app_begin', '应用开始', '2', '1384481614', '', '1');
INSERT INTO `jipu_hooks` VALUES ('12', 'categorySelect', '商品分类选择', '1', '1420297722', 'CategorySelect', '1');
INSERT INTO `jipu_hooks` VALUES ('13', 'areaSelect', '地区三级选择', '1', '1420297736', 'AreaSelect', '1');
INSERT INTO `jipu_hooks` VALUES ('14', 'uploadImages', '批量上传图片', '1', '1420297747', 'UploadImages', '1');
INSERT INTO `jipu_hooks` VALUES ('15', 'advertise', '广告调用', '1', '1420297760', 'Advertise', '1');
INSERT INTO `jipu_hooks` VALUES ('16', 'itemSelect', '商品弹窗选择', '1', '1420297793', 'ItemSelect', '1');
INSERT INTO `jipu_hooks` VALUES ('17', 'weixinPay', '微信支付', '1', '1423729002', 'WeixinPay', '1');
INSERT INTO `jipu_hooks` VALUES ('18', 'itemSel', '商品选择器', '1', '1429611572', 'ItemSel', '1');
INSERT INTO `jipu_hooks` VALUES ('19', 'userSel', '用户通用选择器', '1', '1437100200', 'UserSel', '1');
INSERT INTO `jipu_hooks` VALUES ('20', 'imageTextMsg', '图文选择钩子', '1', '1434078410', 'ImageTextMsg', '1');
INSERT INTO `jipu_hooks` VALUES ('21', 'mobileTopNav', 'wap端头部导航', '1', '1466577743', 'MobileNav', '1');
INSERT INTO `jipu_hooks` VALUES ('22', 'noticeMsg', '用于提示用户信息', '1', '1467182632', 'Notice', '1');

-- ----------------------------
-- Table structure for jipu_invite
-- ----------------------------
DROP TABLE IF EXISTS `jipu_invite`;
CREATE TABLE `jipu_invite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invite_uid` int(11) DEFAULT '0' COMMENT '邀请用户uid',
  `invite_code` char(32) DEFAULT '' COMMENT '邀请码',
  `reg_uid` int(11) DEFAULT '0' COMMENT '被邀请用户uid',
  `reward_status` tinyint(1) DEFAULT '0' COMMENT '邀请奖励到账状态',
  `reward_amount` decimal(10,2) DEFAULT '0.00' COMMENT '奖励金额',
  `create_time` int(10) DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户邀请表';

-- ----------------------------
-- Records of jipu_invite
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_invoice
-- ----------------------------
DROP TABLE IF EXISTS `jipu_invoice`;
CREATE TABLE `jipu_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发票类型（1普通发票，2电子发票，3增值税发票）',
  `normal_title` varchar(64) DEFAULT '' COMMENT '普通发票抬头',
  `normal_content` int(11) unsigned DEFAULT '0' COMMENT '发票内容',
  `ele_title` varchar(64) DEFAULT '' COMMENT '电子发票抬头信息',
  `ele_content` int(11) unsigned DEFAULT '0' COMMENT '电子发票内容',
  `unit` varchar(64) DEFAULT '' COMMENT '增值税单位名称',
  `code` varchar(64) DEFAULT '' COMMENT '纳税人识别码',
  `address` varchar(64) DEFAULT '' COMMENT '注册地址',
  `tel` int(11) unsigned DEFAULT '0' COMMENT '注册电话',
  `bank` varchar(64) DEFAULT '' COMMENT '开户银行',
  `account` varchar(64) DEFAULT '' COMMENT '开户银行账号',
  `inc_content` int(11) unsigned DEFAULT '0' COMMENT '发票内容（后台定义）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户发票信息表';

-- ----------------------------
-- Records of jipu_invoice
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_item
-- ----------------------------
DROP TABLE IF EXISTS `jipu_item`;
CREATE TABLE `jipu_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '发布者UID',
  `supplier_id` int(11) DEFAULT '0' COMMENT '供应商ID',
  `cid_1` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品一级分类ID',
  `cid_2` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品二级分类ID',
  `cid_3` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品三级分类ID',
  `category` varchar(255) NOT NULL DEFAULT '' COMMENT '分类值存储',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `subname` varchar(50) NOT NULL COMMENT '副标题，用于显示促销特价包邮等信息',
  `summary` varchar(255) NOT NULL COMMENT '商品简介',
  `intro` text COMMENT '商品详情',
  `number` varchar(50) NOT NULL COMMENT '商品编号',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `mprice` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `stock` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '库存',
  `weight` decimal(8,3) NOT NULL DEFAULT '0.000' COMMENT '商品重量',
  `delivery_id` smallint(4) NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `credit` int(5) NOT NULL COMMENT '购物返积分数',
  `coupon` int(5) NOT NULL COMMENT '可使用优惠券金额',
  `tag` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `is_top` int(11) NOT NULL DEFAULT '0' COMMENT '是否为置顶商品，0:不置顶，大于0则置顶且数字越大越靠前',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为新品',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为热销',
  `is_promote` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为特价',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否首页推荐',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-下架，1-上架，-1-已删除',
  `is_set_image` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已设置图片',
  `is_lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否锁定，1为锁定',
  `thumb` int(10) NOT NULL DEFAULT '0' COMMENT '商品首页缩略图ID',
  `images` varchar(50) NOT NULL COMMENT '商品多张图片展示',
  `viewnum` int(10) NOT NULL DEFAULT '0' COMMENT '关注度',
  `favnum` int(10) NOT NULL DEFAULT '0' COMMENT '被收藏次数',
  `buynum` int(11) NOT NULL DEFAULT '0' COMMENT '购买次数',
  `sort` smallint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `sdp_type` smallint(1) DEFAULT NULL COMMENT '分销返现类型1比例0金额',
  `sdp` varchar(255) DEFAULT '0' COMMENT '分销返现值,type是1为比例否则为金额',
  `quota_hours` int(10) DEFAULT '0' COMMENT '限购周期（单位 小时）',
  `quota_num` smallint(6) DEFAULT '0' COMMENT '限购数量（0为不限）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  KEY `update_time` (`update_time`),
  KEY `viewnum` (`viewnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_item
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_item_category
-- ----------------------------
DROP TABLE IF EXISTS `jipu_item_category`;
CREATE TABLE `jipu_item_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `ename` varchar(50) NOT NULL DEFAULT '' COMMENT '分类标志（英文）',
  `iconbgc` varchar(10) DEFAULT NULL COMMENT '手机图标背景',
  `icon` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  `trait` varchar(20) DEFAULT NULL COMMENT '推荐标记',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `is_display` tinyint(1) DEFAULT '0' COMMENT '是否在前台显示（0：不显示，1：显示）',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO的网页关键字',
  `meta_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO的网页描述',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '数据状态（-1：逻辑删除，0：不可用，1：可用）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ename` (`ename`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分类表';

-- ----------------------------
-- Records of jipu_item_category
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_item_comment
-- ----------------------------
DROP TABLE IF EXISTS `jipu_item_comment`;
CREATE TABLE `jipu_item_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) DEFAULT '0' COMMENT '回复id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_id` int(11) DEFAULT '0' COMMENT '订单ID',
  `nickname` varchar(255) DEFAULT NULL COMMENT '用户昵称',
  `item_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `star_amount` tinyint(1) DEFAULT NULL COMMENT '评价星级',
  `image` int(10) DEFAULT NULL COMMENT '头像图片id',
  `content` text NOT NULL COMMENT '评价内容',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单评价表';

-- ----------------------------
-- Records of jipu_item_comment
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_item_extend
-- ----------------------------
DROP TABLE IF EXISTS `jipu_item_extend`;
CREATE TABLE `jipu_item_extend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `prp_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `info` text NOT NULL COMMENT '商品扩展属性内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品扩展表（属性，参数，规格等扩展信息）';

-- ----------------------------
-- Records of jipu_item_extend
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_item_property
-- ----------------------------
DROP TABLE IF EXISTS `jipu_item_property`;
CREATE TABLE `jipu_item_property` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '绑定分类ID',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '属性归类（商品属性：attribute，商品参数:parameter，商品规格：specification）',
  `group` varchar(255) NOT NULL DEFAULT '' COMMENT '属性分组',
  `cname` varchar(255) NOT NULL DEFAULT '' COMMENT '属性名称（中文）',
  `ename` varchar(255) NOT NULL DEFAULT '' COMMENT '属性名称（英文）',
  `datatype` varchar(255) NOT NULL DEFAULT '' COMMENT '属性数据类型（文本：txt，数字：num，日期：date，图片：pic）',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '规格图片',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '显示排序',
  `formtype` varchar(255) NOT NULL DEFAULT '' COMMENT '表单类型（文本：input，文本域：textarea，单选：radio，复选：checkbox，下拉菜单：select）',
  `formfillway` varchar(255) NOT NULL DEFAULT '' COMMENT '表单值填充途径',
  `formcheck` varchar(255) NOT NULL DEFAULT '' COMMENT '表单验证规则',
  `isrequired` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否必填，必选',
  `valuestore` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值存储（多值之间用逗号隔开）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品扩展属性表';

-- ----------------------------
-- Records of jipu_item_property
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_item_specifiction
-- ----------------------------
DROP TABLE IF EXISTS `jipu_item_specifiction`;
CREATE TABLE `jipu_item_specifiction` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `spc_code` varchar(255) NOT NULL DEFAULT '' COMMENT '商品规格组合编码（由商品ID，规格组合生成的唯一编码）',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品：规格-数量-价格关系表';

-- ----------------------------
-- Records of jipu_item_specifiction
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_join
-- ----------------------------
DROP TABLE IF EXISTS `jipu_join`;
CREATE TABLE `jipu_join` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `item_ids` text COMMENT '商品id（多个以逗号分隔）',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '活动名称',
  `stime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开团时间',
  `etime` int(11) DEFAULT '0' COMMENT '活动结束时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '活动状态，0-关闭，1-开启',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `ctime` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `limit` int(11) unsigned DEFAULT '0' COMMENT '限制团购时间（单位为分钟）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团活动表';

-- ----------------------------
-- Records of jipu_join
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_join_item_spec
-- ----------------------------
DROP TABLE IF EXISTS `jipu_join_item_spec`;
CREATE TABLE `jipu_join_item_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `spc_code` varchar(255) NOT NULL DEFAULT '' COMMENT '商品规格组合编码（由商品ID，规格组合生成的唯一编码）',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团商品：规格-数量-价格关系表';

-- ----------------------------
-- Records of jipu_join_item_spec
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_login
-- ----------------------------
DROP TABLE IF EXISTS `jipu_login`;
CREATE TABLE `jipu_login` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户UID',
  `type_uid` varchar(255) NOT NULL COMMENT '授权登陆用户名 第三方分配的appid',
  `type` char(80) NOT NULL COMMENT '登陆类型 qq|sina|taobao',
  `oauth_token` varchar(150) DEFAULT NULL COMMENT '授权账号',
  `oauth_token_secret` varchar(150) DEFAULT NULL COMMENT '授权密码',
  PRIMARY KEY (`login_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_login
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_manjian
-- ----------------------------
DROP TABLE IF EXISTS `jipu_manjian`;
CREATE TABLE `jipu_manjian` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) NOT NULL COMMENT '满减活动名称',
  `man` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '满多少金额',
  `jian` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '要减的金额',
  `start_time` int(10) NOT NULL COMMENT '开始时间',
  `expire_time` int(10) NOT NULL COMMENT '过期时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1-可用，0-禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='满减促销表';

-- ----------------------------
-- Records of jipu_manjian
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_member
-- ----------------------------
DROP TABLE IF EXISTS `jipu_member`;
CREATE TABLE `jipu_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `open_id` varchar(255) NOT NULL COMMENT '微信用户openid',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `finance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `avatar` varchar(255) NOT NULL COMMENT '头像地址',
  `payment_pwd` char(32) NOT NULL COMMENT '支付密码',
  `is_mobile_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机是否绑定',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `auto_login_token` varchar(255) NOT NULL COMMENT '自动登录token',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of jipu_member
-- ----------------------------
INSERT INTO `jipu_member` VALUES ('1', '', '管理员', '1', '0000-00-00', '', '0.00', '10', '', '', '0', '4', '0', '0', '2130706433', '1469421599', 'MDAwMDAwMDAwMJOmn6udkYHQm2NxdA', '1');
INSERT INTO `jipu_member` VALUES ('2', '', 'shop', '0', '0000-00-00', '', '0.00', '10', '', '', '0', '2', '0', '0', '2130706433', '1469421924', '', '1');

-- ----------------------------
-- Table structure for jipu_member_bind
-- ----------------------------
DROP TABLE IF EXISTS `jipu_member_bind`;
CREATE TABLE `jipu_member_bind` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '绑定类型：1-手机，2-邮箱',
  `account` varchar(50) NOT NULL COMMENT '用户绑定账号：手机或邮箱',
  `code` varchar(50) NOT NULL COMMENT '验证码',
  `has_used` tinyint(1) NOT NULL DEFAULT '0' COMMENT '验证码是否已经使用：1-已使用，0-未使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '生成时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `bind_time` int(11) NOT NULL DEFAULT '0' COMMENT '绑定时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户手机、邮件绑定表';

-- ----------------------------
-- Records of jipu_member_bind
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_menu
-- ----------------------------
DROP TABLE IF EXISTS `jipu_menu`;
CREATE TABLE `jipu_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=358 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_menu
-- ----------------------------
INSERT INTO `jipu_menu` VALUES ('1', '首页', '0', '1', 'Index/index', '0', '首页常用管理', '常用管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('3', '订单', '0', '3', 'Order/index', '0', '', '订单管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('4', '营销', '0', '5', 'Promote/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('5', '用户', '0', '6', 'User/index', '0', '', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('6', '文章', '0', '7', 'Article/index', '0', '', '文章管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('7', '系统', '0', '9', 'Config/group', '0', '', '系统设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('8', '扩展', '0', '10', 'Addons/index', '0', '', '扩展管理', '1', '1');
INSERT INTO `jipu_menu` VALUES ('9', '统计', '0', '8', 'Stat/index', '0', '', '统计列表', '0', '1');
INSERT INTO `jipu_menu` VALUES ('10', '物流', '0', '4', 'DeliveryTpl/index', '0', '', '物流管理', '1', '1');
INSERT INTO `jipu_menu` VALUES ('11', '快速开始', '1', '0', 'Index/index', '0', '', '常用管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('12', '缓存清理', '1', '0', 'Index/cleancache', '0', '清空系统缓存', '常用管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('13', '商品列表', '2', '1', 'Item/index', '0', '', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('14', '商品分类', '2', '2', 'ItemCategory/index', '0', '', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('15', '商品属性', '2', '3', 'ItemProperty/index', '0', '', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('16', '商品规格', '2', '4', 'ItemProperty/index?type=specification', '0', '商品规格管理', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('17', '回收站', '2', '5', 'Item/recycle', '0', '', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('18', '二维码批量生成', '2', '11', 'Item/createQrcode', '0', '', '商品二维码', '0', '1');
INSERT INTO `jipu_menu` VALUES ('19', '新增', '13', '1', 'Item/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('20', '编辑', '13', '2', 'Item/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('21', '删除', '13', '3', 'Item/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('22', '查看', '13', '4', 'Item/view', '0', '查看商品详情', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('23', '更新', '13', '5', 'Item/update', '0', '保存更新数据（新增&编辑）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('24', '部分更新', '13', '6', 'Item/updateField', '0', 'AJAX局部更新数据', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('25', '设置状态', '13', '7', 'Item/setStatus', '0', '设置商品状态（上架，下架等）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('26', '删除图片', '13', '8', 'Item/delPic', '0', '删除商品图片（物理删除）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('27', '选择分类', '13', '9', 'Item/select', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('28', '其他操作', '13', '10', 'Item/other', '1', '其他操作，预留', '', '1', '1');
INSERT INTO `jipu_menu` VALUES ('29', '新增', '14', '1', 'ItemCategory/add', '0', '新增商品分类', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('30', '编辑', '14', '2', 'ItemCategory/edit', '0', '编辑商品分类', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('31', '删除', '14', '3', 'ItemCategory/remove', '0', '删除商品分类', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('32', '移动', '14', '4', 'ItemCategory/move', '0', '移动商品分类', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('33', '合并', '14', '5', 'ItemCategory/merge', '0', '合并商品分类', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('34', '操作', '14', '6', 'ItemCategory/operate', '0', '操作分类初始化（移动&合并）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('35', '状态', '14', '7', 'ItemCategory/setStatus', '0', '设置分类状态（启用&禁用）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('36', '其他', '14', '8', 'ItemCategory/other', '1', '其他预留操作', '', '1', '1');
INSERT INTO `jipu_menu` VALUES ('37', '新增', '15', '1', 'ItemProperty/add', '0', '新增属性', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('38', '编辑', '15', '2', 'ItemProperty/edit', '0', '编辑属性', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('39', '删除', '15', '3', 'ItemProperty/del', '0', '删除属性', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('40', '新增', '16', '1', 'ItemProperty/add?type=specification', '0', '新增商品规格', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('41', '编辑', '16', '2', 'ItemProperty/edit?type=specification', '0', '编辑商品规格', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('42', '删除', '16', '3', 'ItemProperty/del?type=specification', '0', '删除商品规格', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('43', '清空', '17', '1', 'Item/clear', '0', '清空回收站，物理删除数据，删除后数据不能恢复', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('44', '还原', '17', '2', 'Item/permit', '0', '从回收站还原逻辑删除的商品数据', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('45', '订单列表', '3', '1', 'Order/index', '0', '查看和管理订单', '订单管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('46', '收款单', '3', '2', 'Payment/index', '0', '', '订单管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('47', '发货单', '3', '3', 'Ship/index', '0', '', '订单管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('48', '查看', '45', '1', 'Order/view', '0', '查看&处理订单', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('49', '删除', '45', '2', 'Order/setStatus', '0', '删除订单', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('50', '新增', '46', '1', 'Payment/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('51', '查看', '46', '2', 'Payment/view', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('52', '删除', '46', '3', 'Payment/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('53', '新增', '47', '1', 'Ship/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('54', '查看', '47', '2', 'Ship/view', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('55', '删除', '47', '3', 'Ship/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('56', '发票管理', '3', '4', 'Invoice/index', '1', '', '订单管理', '1', '1');
INSERT INTO `jipu_menu` VALUES ('57', '查看', '56', '1', 'Invoice/view', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('58', '编辑', '56', '2', 'Invoice/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('59', '删除', '56', '3', 'Invoice/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('60', '营销工具', '4', '1', 'Promote/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('61', '优惠券管理', '4', '3', 'Coupon/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('62', '优惠券发放记录', '61', '4', 'CouponUser/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('63', '礼品卡管理', '4', '5', 'Card/index', '0', '礼品卡生成与管理', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('64', '专题管理', '4', '6', 'Activity/index', '0', '专场添加、编辑、删除', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('65', '广告管理', '4', '7', 'Advertise/index', '0', '用于配置首页幻灯、Banner广告等', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('66', '用户列表', '5', '1', 'User/index', '0', '', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('67', '用户充值管理', '5', '4', 'User/recharge', '0', '用户预存款充值管理', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('68', '用户组管理', '5', '3', 'AuthManager/index', '0', '', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('69', '微信粉丝管理', '5', '7', 'WechatUser/index', '1', '查看微信公众平台粉丝', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('70', '用户行为', '5', '8', 'User/action', '0', '', '行为管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('71', '行为日志', '5', '9', 'Action/actionlog', '0', '', '行为管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('72', '新增', '66', '1', 'User/add', '0', '添加新用户', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('73', '查看', '66', '2', 'User/view', '0', '查看用户详情', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('74', '设置用户状态', '66', '3', 'User/changeStatus', '0', '设置用户状态*（禁用，启用，逻辑删除）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('75', '禁用会员', '66', '4', 'User/changeStatus?method=forbidUser', '0', '用户->用户信息中的禁用', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('76', '启用会员', '66', '5', 'User/changeStatus?method=resumeUser', '0', '用户->用户信息中的启用', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('77', '删除会员', '66', '6', 'User/changeStatus?method=deleteUser', '0', '用户->用户信息中的删除', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('78', '修改密码', '66', '7', 'User/updatePassword', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('79', '修改昵称', '66', '8', 'User/updateNickname', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('80', '新增', '68', '1', 'AuthManager/createGroup', '0', '创建新的用户组', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('81', '编辑', '68', '2', 'AuthManager/editGroup', '0', '编辑用户组名称和描述', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('82', '保存', '68', '3', 'AuthManager/writeGroup', '0', '新增和编辑用户组的\"保存\"按钮', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('83', '禁用', '68', '4', 'AuthManager/changeStatus?method=forbidGroup', '0', '禁用用户组', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('84', '恢复', '68', '5', 'AuthManager/changeStatus?method=resumeGroup', '0', '恢复已禁用的用户组', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('85', '删除', '68', '6', 'AuthManager/changeStatus?method=deleteGroup', '0', '删除用户组', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('86', '分组授权', '68', '7', 'AuthManager/group', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('87', '访问授权', '68', '8', 'AuthManager/access', '0', '\"后台 》 用户 》 权限管理\"列表页的\"访问授权\"操作按钮', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('88', '成员授权', '68', '9', 'AuthManager/user', '0', '\"后台 》 用户 》 权限管理\"列表页的\"成员授权\"操作按钮', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('89', '保存成员授权', '68', '10', 'AuthManager/addToGroup', '0', '\"用户信息\"列表页\"授权\"时的\"保存\"按钮和\"成员授权\"里右上角的\"添加\"按钮)', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('90', '解除成员授权', '68', '11', 'AuthManager/removeFromGroup', '0', '\"成员授权\"列表页内的解除授权操作按钮', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('91', '分类授权', '68', '12', 'AuthManager/category', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('93', '同步粉丝', '69', '1', 'WechatUser/getUser', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('92', '保存分类授权', '68', '13', 'AuthManager/addToCategory', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('94', '新增', '70', '1', 'User/addaction', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('95', '编辑', '70', '2', 'User/editaction', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('96', '保存', '70', '3', 'User/saveAction', '0', '\"用户 》用户行为\"保存编辑和新增的用户行为', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('97', '变更行为状态', '70', '4', 'User/setStatus', '0', '\"用户->用户行为\"中的启用,禁用和删除权限', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('98', '新增', '60', '1', 'Promote/add', '1', '增加促销工具', '', '1', '1');
INSERT INTO `jipu_menu` VALUES ('99', '编辑', '60', '2', 'Promote/edit', '0', '编辑促销工具信息', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('100', '删除', '60', '3', 'Promote/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('101', '新增', '61', '1', 'Coupon/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('102', '编辑', '61', '2', 'Coupon/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('103', '删除', '61', '3', 'Coupon/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('104', '更新', '61', '4', 'Coupon/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('105', '设置商品', '61', '5', 'Coupon/setItems', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('106', '新增', '62', '1', 'CouponUser/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('107', '更新', '62', '2', 'CouponUser/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('108', '删除', '62', '3', 'CouponUser/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('109', '新增', '63', '1', 'Card/add', '0', '新增礼品卡', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('110', '编辑', '63', '2', 'Card/edit', '0', '编辑礼品卡', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('111', '删除', '63', '3', 'Card/del', '0', '删除礼品卡', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('112', '查看', '63', '4', 'Card/view', '0', '查看礼品卡', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('113', '导出', '63', '5', 'Card/export', '0', '导出礼品卡到excel文件', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('114', '新增', '64', '1', 'Activity/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('115', '编辑', '64', '2', 'Activity/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('116', '更新', '64', '3', 'Activity/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('117', '局部更新', '64', '4', 'Activity/updateField', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('118', '删除', '64', '5', 'Activity/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('119', '删除图片', '64', '6', 'Activity/delPic', '0', '删除专题图片（物理删除）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('120', '新增', '65', '1', 'Advertise/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('121', '编辑', '65', '2', 'Advertise/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('122', '更新', '65', '3', 'Advertise/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('123', '删除', '65', '4', 'Advertise/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('124', '删除图片', '65', '5', 'Advertise/delPic', '0', '删除广告图片（物理删除）', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('125', '文章列表', '6', '1', 'Article/index', '0', '', '文章管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('126', '文章分类', '6', '2', 'ArticleCategory/index', '0', '', '文章管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('127', '回收站', '6', '3', 'Article/recyle', '0', '', '文章管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('128', '新增', '125', '1', 'Article/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('129', '编辑', '125', '2', 'Article/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('130', '更新', '125', '3', 'Article/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('131', '局部更新', '125', '4', 'Article/updateField', '0', 'AJAX局部更新数据', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('132', '删除', '125', '5', 'Article/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('133', '新增', '126', '1', 'ArticleCategory/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('134', '编辑', '126', '2', 'ArticleCategory/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('135', '更新', '126', '3', 'ArticleCategory/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('136', '更新局部', '126', '4', 'ArticleCategory/updateField', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('137', '删除', '126', '5', 'ArticleCategory/remove', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('138', '清空', '127', '1', 'Article/clear', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('139', '还原', '127', '2', 'Article/permit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('140', '基本设置', '7', '1', 'Config/group', '0', '', '系统设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('141', '导航管理', '7', '2', 'Channel/index', '0', '', '系统设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('282', '状态设置', '65', '0', 'Advertise/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('143', '配置管理', '7', '4', 'Config/index', '0', '', '系统设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('144', '菜单管理', '7', '5', 'Menu/index', '0', '', '系统设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('145', '备份数据库', '7', '11', 'Database/index?type=export', '0', '', '数据备份', '0', '1');
INSERT INTO `jipu_menu` VALUES ('146', '还原数据库', '7', '12', 'Database/index?type=import', '0', '', '数据备份', '0', '1');
INSERT INTO `jipu_menu` VALUES ('147', '微信消息设置', '7', '21', 'WechatMsg/index', '0', '', '微信公众平台设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('148', '自定义菜单设置', '7', '22', 'WechatMenu/index', '0', '', '微信公众平台设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('149', '新增', '141', '1', 'Channel/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('150', '编辑', '141', '2', 'Channel/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('151', '删除', '141', '3', 'Channel/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('152', '排序', '141', '4', 'Channel/sort', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('283', '状态设置', '64', '0', 'Activity/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('284', '状态设置', '141', '0', 'Channel/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('157', '新增', '143', '1', 'Config/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('158', '编辑', '143', '2', 'Config/edit', '0', '新增编辑和保存配置', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('159', '保存', '143', '3', 'Config/save', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('160', '删除', '143', '4', 'Config/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('161', '排序', '143', '5', 'Config/sort', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('162', '新增', '144', '1', 'Menu/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('163', '编辑', '144', '2', 'Menu/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('164', '导入', '144', '3', 'Menu/import', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('165', '排序', '144', '4', 'Menu/sort', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('166', '删除', '144', '5', 'Menu/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('167', '备份', '145', '1', 'Database/export', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('168', '优化表', '145', '2', 'Database/optimize', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('169', '修复表', '145', '3', 'Database/repair', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('170', '恢复', '146', '1', 'Database/import', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('171', '删除', '146', '2', 'Database/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('172', '新增', '147', '1', 'WechatMsg/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('173', '编辑', '147', '2', 'WechatMsg/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('174', '更新', '147', '3', 'WechatMsg/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('175', '删除', '147', '4', 'WechatMsg/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('176', '新增', '148', '1', 'WechatMenu/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('177', '编辑', '148', '2', 'WechatMenu/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('178', '更新', '148', '3', 'WechatMenu/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('179', '删除', '148', '4', 'WechatMenu/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('180', '生成自定义菜单', '148', '5', 'WechatMenu/send', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('181', '删除自定义菜单', '148', '6', 'WechatMenu/remove', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('182', '插件管理', '8', '1', 'Addons/index', '0', '', '扩展', '0', '1');
INSERT INTO `jipu_menu` VALUES ('183', '钩子管理', '8', '2', 'Addons/hooks', '0', '', '扩展', '0', '1');
INSERT INTO `jipu_menu` VALUES ('184', '开发辅助', '8', '3', 'Addons/develop', '0', '', '扩展', '0', '1');
INSERT INTO `jipu_menu` VALUES ('185', '创建', '182', '1', 'Addons/create', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('186', '检测创建', '182', '2', 'Addons/checkForm', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('187', '预览', '182', '3', 'Addons/preview', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('188', '快速生成插件', '182', '4', 'Addons/build', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('189', '设置', '182', '5', 'Addons/config', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('190', '禁用', '182', '6', 'Addons/disable', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('191', '启用', '182', '7', 'Addons/enable', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('192', '安装', '182', '8', 'Addons/install', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('193', '卸载', '182', '9', 'Addons/uninstall', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('194', '更新配置', '182', '10', 'Addons/saveconfig', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('195', '插件后台列表', '182', '11', 'Addons/adminList', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('196', 'URL方式访问插件', '182', '12', 'Addons/execute', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('197', '新增', '183', '1', 'Addons/existHook', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('198', '编辑', '183', '2', 'Addons/edithook', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('199', '更新', '183', '3', 'Addons/updateHook', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('200', '运费模板', '10', '0', 'DeliveryTpl/index', '0', '', '物流管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('201', '回收站', '3', '5', 'Order/recycle', '0', '', '订单管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('202', '订单统计', '9', '1', 'Stat/index', '0', '', '统计列表', '0', '1');
INSERT INTO `jipu_menu` VALUES ('203', '用户统计', '9', '2', 'Stat/user', '0', '', '统计列表', '0', '1');
INSERT INTO `jipu_menu` VALUES ('204', '添加模板', '200', '0', 'DeliveryTpl/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('205', '保存模板', '200', '0', 'DeliveryTpl/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('206', '编辑模板', '200', '0', 'DeliveryTpl/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('207', '查看行为日志', '71', '0', 'Action/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('208', '删除行为日志', '71', '0', 'Action/remove', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('209', '清空行为日志', '71', '0', 'Action/clear', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('210', '设置状态', '61', '6', 'Coupon/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('211', 'Ajax获取列表', '200', '0', 'DeliveryTpl/ajaxList', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('212', 'Ajax获取内容', '200', '0', 'DeliveryTpl/ajaxDetail', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('213', '查看', '16', '4', 'ItemProperty/view', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('214', '输出属性值', '16', '5', 'ItemProperty/getValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('215', '输出图片配置数组', '16', '6', 'ItemProperty/getPictureConfigData', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('216', '获取选项值', '16', '7', 'ItemProperty/getOption', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('217', '保存选项值', '16', '8', 'ItemProperty/saveOption', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('218', '上传图片', '16', '9', 'ItemProperty/upload', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('219', '修改订单金额', '45', '0', 'Order/updateField', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('220', '删除', '69', '2', 'WechatUser/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('349', '用户积分记录', '5', '6', 'User/score', '0', '', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('222', '发红包', '221', '1', 'Redpacket/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('223', '红包领取', '221', '2', 'Redpacket/receive', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('224', '二维码', '221', '3', 'Redpacket/qrcode', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('225', '删除', '221', '4', 'Redpacket/del', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('226', '邀请', '4', '5', 'Invite/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('227', '退款管理', '3', '5', 'Refund/index', '0', '', '订单管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('228', '取消订单', '45', '4', 'Order/cancel', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('229', '处理退款', '45', '5', 'Order/refund', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('230', '支付宝批量退款', '227', '1', 'Refund/alipay', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('231', '清零', '66', '9', 'User/setValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('232', '用户充值', '66', '0', 'User/rechangeAdd', '0', '给用户添加余额', '用户列表', '0', '1');
INSERT INTO `jipu_menu` VALUES ('233', '邀请记录', '226', '0', 'Invite/view', '0', '邀请', '邀请', '0', '1');
INSERT INTO `jipu_menu` VALUES ('234', '用户账户流水', '5', '5', 'User/finance', '0', '用户账户流水', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('252', '单笔退款', '227', '0', 'Refund/deal', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('263', '发货', '47', '0', 'Ship/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('250', '移除授权', '243', '0', 'UserGroup/removeFromGroup', '0', '', '会员等级', '0', '1');
INSERT INTO `jipu_menu` VALUES ('249', '增加授权', '243', '0', 'UserGroup/addToGroup', '0', '', '会员等级', '0', '1');
INSERT INTO `jipu_menu` VALUES ('248', '成员授权', '243', '0', 'UserGroup/user', '0', '', '会员等级', '0', '1');
INSERT INTO `jipu_menu` VALUES ('247', '更改状态', '243', '0', 'UserGroup/setStatus', '0', '', '会员等级', '0', '1');
INSERT INTO `jipu_menu` VALUES ('246', '更新', '243', '0', 'UserGroup/update', '0', '', '会员等级', '0', '1');
INSERT INTO `jipu_menu` VALUES ('245', '编辑', '243', '0', 'UserGroup/edit', '0', '', '会员等级', '0', '1');
INSERT INTO `jipu_menu` VALUES ('244', '添加', '243', '0', 'UserGroup/add', '0', '', '会员等级', '0', '1');
INSERT INTO `jipu_menu` VALUES ('243', '会员等级', '5', '2', 'UserGroup/index', '1', '', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('242', '短信记录', '236', '0', 'Sms/listsSend', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('241', '清空缓存', '236', '0', 'Sms/clearCache', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('240', '删除模板', '236', '0', 'Sms/removeTpl', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('239', '添加编辑模板', '236', '0', 'Sms/updateTpl', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('238', '模板列表', '236', '0', 'Sms/listsTpl', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('237', '修改账户信息', '236', '0', 'Sms/setUser', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('236', '短信平台', '7', '5', 'Sms/index', '0', '', '系统设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('235', '商品评价', '2', '4', 'ItemComment/index', '0', '商品管理', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('257', '删除', '253', '0', 'Area/remove', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('256', '更新', '253', '0', 'Area/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('255', '编辑', '253', '0', 'Area/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('254', '添加', '253', '0', 'Area/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('253', '配送区域', '7', '7', 'Area/index', '0', '', '系统设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('258', '评价回复', '235', '0', 'ItemComment/reply', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('260', '更新搜索索引', '2', '4', 'Item/updateSearchIndex', '0', '', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('259', '回复评价提交', '235', '0', 'ItemComment/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('262', '更新', '66', '0', 'User/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('261', '修改', '66', '0', 'User/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('264', '逻辑删除', '125', '0', 'Article/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('265', '推荐设置', '125', '0', 'Article/setFieldValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('266', '禁用启用', '126', '0', 'ArticleCategory/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('267', '合并分类', '126', '0', 'ArticleCategory/merge', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('268', '移动分类', '126', '0', 'ArticleCategory/move', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('269', '移动合并初始化', '126', '0', 'ArticleCategory/operate', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('270', '启用禁用', '200', '0', 'DeliveryTpl/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('271', '模板消息设置', '7', '25', 'WechatTplMsg/index', '0', '', '微信公众平台设置', '0', '1');
INSERT INTO `jipu_menu` VALUES ('272', '编辑', '271', '0', 'WechatTplMsg/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('273', '保存', '271', '0', 'WechatTplMsg/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('274', '状态设置', '271', '0', 'WechatTplMsg/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('281', '处理提现', '275', '0', 'Withdraw/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('280', '修改状态', '276', '0', 'Shop/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('279', '提现页面', '275', '0', 'Withdraw/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('278', '修改排序', '144', '0', 'Menu/setFieldValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('276', '店铺列表', '4', '1', 'Shop/index', '0', '', '分销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('275', '提现列表', '4', '3', 'Withdraw/index', '0', '', '分销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('285', '导出', '275', '0', 'Withdraw/export', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('286', '分销返现', '9', '3', 'Stat/sdpAmount', '0', '', '统计列表', '0', '1');
INSERT INTO `jipu_menu` VALUES ('287', '商品销量', '9', '2', 'Stat/goods', '0', '', '统计列表', '0', '1');
INSERT INTO `jipu_menu` VALUES ('288', '添加', '235', '0', 'ItemComment/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('290', '设置状态', '235', '0', 'ItemComment/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('289', '编辑', '235', '0', 'ItemComment/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('291', '更新字段', '200', '0', 'DeliveryTpl/setFieldValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('292', '供应商信息', '10', '0', 'Supplier/index', '0', '', '供应商信息', '0', '1');
INSERT INTO `jipu_menu` VALUES ('293', '新增', '292', '0', 'Supplier/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('294', '编辑', '292', '0', 'Supplier/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('295', '更新', '292', '0', 'Supplier/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('296', '更改状态', '292', '0', 'Supplier/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('297', '二维码详情', '292', '0', 'Supplier/detail', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('298', '更新字段', '292', '0', 'Supplier/setFieldValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('299', '收款信息预览', '46', '0', 'Payment/preview', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('300', '联盟管理', '4', '8', 'Union/index', '0', '', '推广联盟', '0', '1');
INSERT INTO `jipu_menu` VALUES ('301', '增加', '300', '0', 'Union/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('302', '编辑', '300', '0', 'Union/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('303', '更新', '300', '0', 'Union/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('304', '查看二维码', '300', '0', 'Union/detail', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('305', '状态设置', '300', '0', 'Union/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('306', '排序', '65', '0', 'Advertise/setFieldValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('311', '设置状态', '307', '0', 'Manjian/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('310', '更新', '307', '0', 'Manjian/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('309', '编辑', '307', '0', 'Manjian/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('308', '新增', '307', '0', 'Manjian/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('307', '满减管理', '4', '9', 'Manjian/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('321', '启用禁用', '313', '0', 'BuySend/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('320', '保存', '313', '0', 'BuySend/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('319', '编辑', '313', '0', 'BuySend/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('318', '添加', '313', '0', 'BuySend/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('317', '更改状态', '312', '0', 'SecondPieces/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('316', '更新', '312', '0', 'SecondPieces/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('315', '编辑', '312', '0', 'SecondPieces/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('314', '新增', '312', '0', 'SecondPieces/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('313', '买送', '4', '11', 'BuySend/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('312', '第二件折扣', '4', '10', 'SecondPieces/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('331', '预览', '323', '0', 'Message/preview', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('330', '查看', '323', '0', 'Message/detail', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('329', '删除', '323', '0', 'Message/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('328', '发送消息', '323', '0', 'Message/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('327', '更改状态', '322', '0', 'Seckill/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('326', '更新', '322', '0', 'Seckill/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('325', '编辑', '322', '0', 'Seckill/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('324', '新增', '322', '0', 'Seckill/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('323', '站内消息', '5', '10', 'Message/index', '0', '', '通知管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('322', '秒杀活动', '4', '12', 'Seckill/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('332', '新增发布', '323', '0', 'Message/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('333', '老客户立减', '4', '13', 'Fugou/index', '0', '老客户再次同一商品购买', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('334', '新增', '333', '0', 'Fugou/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('335', '编辑', '333', '0', 'Fugou/edit', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('336', '更新', '333', '0', 'Fugou/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('337', '状态', '333', '0', 'Fugou/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('338', '打印清单', '45', '0', 'Order/printItem', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('339', '详情', '276', '0', 'Shop/detail', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('340', '更新', '276', '0', 'Shop/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('341', '更新字段值', '13', '0', 'Item/setFieldValue', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('342', '灵通打单', '45', '0', 'Order/bestmart', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('343', '红包', '4', '2', 'RedPackage/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('344', '添加', '343', '0', 'RedPackage/add', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('345', '保存', '343', '0', 'RedPackage/update', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('346', '禁用删除', '343', '0', 'RedPackage/setStatus', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('347', '二维码', '343', '0', 'RedPackage/qrcode', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('348', '详情', '343', '0', 'RedPackage/detail', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('350', '积分更新', '66', '0', 'User/scoreAdd', '0', '', '', '0', '1');
INSERT INTO `jipu_menu` VALUES ('351', '众筹订单', '3', '0', 'Crowdfunding/order', '1', '暂停升级', '众筹管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('352', '众筹用户', '3', '0', 'Crowdfunding/user', '1', '暂停使用', '众筹管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('2', '商品', '0', '2', 'Item/index', '0', '', '商品管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('355', '用户回收站', '5', '21', 'User/recycle', '0', '防止用户误删除，数据恢复', '用户管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('356', '拼团', '4', '2', 'Join/index', '0', '', '促销管理', '0', '1');
INSERT INTO `jipu_menu` VALUES ('357', '用户现金流水', '5', '5', 'User/accountcost', '0', '用户现金流水', '用户管理', '0', '1');

-- ----------------------------
-- Table structure for jipu_message
-- ----------------------------
DROP TABLE IF EXISTS `jipu_message`;
CREATE TABLE `jipu_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '发布人Id',
  `to_uid` text COMMENT '接收人Id（如果为0则为全站通知）',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `status` tinyint(1) DEFAULT '1' COMMENT '通知状态（1正常、0已禁用、-1已删除）',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站内消息通知主表';

-- ----------------------------
-- Records of jipu_message
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_message_record
-- ----------------------------
DROP TABLE IF EXISTS `jipu_message_record`;
CREATE TABLE `jipu_message_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT '0' COMMENT '消息ID',
  `uid` int(11) DEFAULT '0' COMMENT '阅读用户ID',
  `read_time` int(10) DEFAULT '0' COMMENT '阅读时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1正常阅读、-1已删除）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站内消息阅读记录表';

-- ----------------------------
-- Records of jipu_message_record
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_mobile_bind
-- ----------------------------
DROP TABLE IF EXISTS `jipu_mobile_bind`;
CREATE TABLE `jipu_mobile_bind` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `mobile` varchar(50) NOT NULL COMMENT '用户手机号码',
  `randcode` varchar(50) NOT NULL COMMENT '验证码',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '生成时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `bind_time` int(11) NOT NULL DEFAULT '0' COMMENT '绑定时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='手机绑定';

-- ----------------------------
-- Records of jipu_mobile_bind
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_mobile_randcode
-- ----------------------------
DROP TABLE IF EXISTS `jipu_mobile_randcode`;
CREATE TABLE `jipu_mobile_randcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(50) DEFAULT '' COMMENT '手机号码',
  `type` char(30) DEFAULT '' COMMENT '请求类型',
  `randcode` varchar(50) DEFAULT '' COMMENT '验证码',
  `ip` varchar(50) DEFAULT '' COMMENT '发送请求的IP地址',
  `validate_status` tinyint(1) DEFAULT '0' COMMENT '验证状态 0 未验证 1已验证',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `validate_time` int(10) DEFAULT '0' COMMENT '验证通过时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='手机验证码表';

-- ----------------------------
-- Records of jipu_mobile_randcode
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_notice
-- ----------------------------
DROP TABLE IF EXISTS `jipu_notice`;
CREATE TABLE `jipu_notice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '发布人Id',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `status` tinyint(1) DEFAULT '1' COMMENT '公告状态',
  `add_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统公告表';

-- ----------------------------
-- Records of jipu_notice
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_notify_tpl
-- ----------------------------
DROP TABLE IF EXISTS `jipu_notify_tpl`;
CREATE TABLE `jipu_notify_tpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '模板名称（调用模版时用）',
  `summary` varchar(255) NOT NULL DEFAULT '' COMMENT '模板摘要',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '消息类型（1->邮件，2->短信）',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '消息主题',
  `content` text NOT NULL COMMENT '消息内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-禁用，1-启用，-1-逻辑删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息通知模版';

-- ----------------------------
-- Records of jipu_notify_tpl
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_order
-- ----------------------------
DROP TABLE IF EXISTS `jipu_order`;
CREATE TABLE `jipu_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `supplier_ids` varchar(200) DEFAULT '0' COMMENT '供应商ID集合',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sdp_uid` int(11) unsigned DEFAULT '0' COMMENT '分销用户id(已弃用)，是否分销订单，0-否，1-是',
  `item_ids` varchar(255) NOT NULL DEFAULT '0' COMMENT '订单产品ID集合，存储以逗号隔开',
  `delivery_id` int(11) DEFAULT '0' COMMENT '客户所选物流模板ID',
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `o_status` smallint(6) NOT NULL DEFAULT '0' COMMENT '订单状态（参考 http://t.cn/Rwl9SlU ）',
  `payment_id` int(11) DEFAULT '0' COMMENT '对应订单支付表ID',
  `payment_type` varchar(255) DEFAULT NULL,
  `invoice_need` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否需要开发票：0-否，1-是',
  `invoice_type` smallint(1) unsigned DEFAULT '1' COMMENT '发票类型：1-个人，2-公司',
  `invoice_title` varchar(255) DEFAULT '' COMMENT '发票抬头',
  `invoice_content` varchar(255) DEFAULT '' COMMENT '开票状态（0：未申请，1：出票中，2：已出票）',
  `finance_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额使用金额',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '第三方支付总额',
  `total_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品总价格',
  `total_quantity` int(11) NOT NULL DEFAULT '0' COMMENT '商品总数量',
  `total_weight` decimal(8,3) NOT NULL DEFAULT '0.000' COMMENT '商品总重量(单位:kg)',
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '配送费用',
  `memo` varchar(255) DEFAULT '' COMMENT '附言',
  `order_from` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单来源终端：1-PC，2-手机触屏，3-微信，4-平板，5-iOS，6-Android',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '修改日期',
  `payment_time` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单记录状态：1正常，-1已删除，2用户删除3用户永久删除',
  `shipping_time` int(10) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `complete_time` int(10) NOT NULL COMMENT '确认收货时间',
  `reship_info` varchar(255) NOT NULL DEFAULT '' COMMENT '退货物流信息',
  `is_packed` tinyint(1) DEFAULT '0' COMMENT '是否打包1已打包0未打包',
  `refuse_message` varchar(200) DEFAULT NULL COMMENT '拒接退款理由',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单表';

-- ----------------------------
-- Records of jipu_order
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_order_comment
-- ----------------------------
DROP TABLE IF EXISTS `jipu_order_comment`;
CREATE TABLE `jipu_order_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) DEFAULT '0' COMMENT '回复id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `item_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `star_amount` tinyint(1) DEFAULT NULL COMMENT '评价星级',
  `content` text NOT NULL COMMENT '评价内容',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单评价表';

-- ----------------------------
-- Records of jipu_order_comment
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_order_item
-- ----------------------------
DROP TABLE IF EXISTS `jipu_order_item`;
CREATE TABLE `jipu_order_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `supplier_id` int(11) DEFAULT '0' COMMENT '供应商ID',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `item_code` varchar(255) NOT NULL COMMENT '商品编码（未设置规格：用商品编码；已设置规格：用规格组合码）',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `number` varchar(255) NOT NULL DEFAULT '' COMMENT '商品编号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `thumb` int(11) NOT NULL DEFAULT '0' COMMENT '商品图片',
  `spec` text COMMENT '商品规格',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `weight` decimal(8,3) NOT NULL DEFAULT '0.000' COMMENT '重量',
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '小计',
  `fugou_dis_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '复购单件优惠金额',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '修改日期',
  `sdp_code` char(32) DEFAULT '' COMMENT '分销商编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单项目表';

-- ----------------------------
-- Records of jipu_order_item
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_order_ship
-- ----------------------------
DROP TABLE IF EXISTS `jipu_order_ship`;
CREATE TABLE `jipu_order_ship` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) DEFAULT '0' COMMENT '订单编号',
  `ship_uname` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `ship_mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人手机',
  `ship_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `ship_province` int(10) NOT NULL DEFAULT '0' COMMENT '收货人省ID',
  `ship_district` int(10) NOT NULL DEFAULT '0' COMMENT '收货人市ID',
  `ship_city` int(10) NOT NULL DEFAULT '0' COMMENT '收货人区/县ID',
  `ship_area` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人地区',
  `ship_address` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `ship_zipcode` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人邮编',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单收货信息表';

-- ----------------------------
-- Records of jipu_order_ship
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_payment
-- ----------------------------
DROP TABLE IF EXISTS `jipu_payment`;
CREATE TABLE `jipu_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `union_id` int(11) DEFAULT '0' COMMENT '用户扫描带参数的微信二维码ID',
  `payment_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '交易订单编号',
  `payment_type` varchar(255) NOT NULL DEFAULT '' COMMENT '支付类型',
  `payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `payment_account` varchar(255) NOT NULL COMMENT '付款人账号',
  `payment_bank` varchar(255) NOT NULL COMMENT '付款银行',
  `payment_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态（0：待支付，1：已支付）',
  `memo` varchar(255) NOT NULL COMMENT '备注',
  `payment_return` text COMMENT '支付平台返回的数据',
  `is_use_finance` tinyint(1) DEFAULT '0' COMMENT '是否使用账户余额',
  `finance_amount` decimal(10,2) DEFAULT '0.00' COMMENT '账户余额使用金额',
  `is_use_coupon` tinyint(1) DEFAULT '0' COMMENT '是否使用优惠券（0：未使用，1：已使用）',
  `coupon_id` int(11) DEFAULT '0' COMMENT '优惠券ID',
  `coupon_amount` decimal(10,2) DEFAULT '0.00' COMMENT '优惠券金额',
  `is_use_card` tinyint(1) DEFAULT '0' COMMENT '是否使用礼品卡（0：未使用，1：已使用）',
  `card_id` int(11) DEFAULT '0' COMMENT '礼品卡ID',
  `card_amount` decimal(10,2) DEFAULT '0.00' COMMENT '礼品卡使用金额',
  `manjian` decimal(10,2) DEFAULT '0.00' COMMENT '满减优惠金额',
  `use_score` int(11) NOT NULL DEFAULT '0' COMMENT '使用积分',
  `score_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分抵扣金额',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '修改日期',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（1正常 -1已删除）',
  `delivery_data` varchar(255) DEFAULT '' COMMENT '运费数组',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='收款(第三方支付)信息表';

-- ----------------------------
-- Records of jipu_payment
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_payment_log
-- ----------------------------
DROP TABLE IF EXISTS `jipu_payment_log`;
CREATE TABLE `jipu_payment_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `row_id` int(11) NOT NULL DEFAULT '0' COMMENT '支付类型对应的id',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `type` varchar(50) NOT NULL COMMENT '类型：finance、coupon、card……',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付日志，包括优惠券、礼品卡、账户余额';

-- ----------------------------
-- Records of jipu_payment_log
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_picture
-- ----------------------------
DROP TABLE IF EXISTS `jipu_picture`;
CREATE TABLE `jipu_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_picture
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_property_option
-- ----------------------------
DROP TABLE IF EXISTS `jipu_property_option`;
CREATE TABLE `jipu_property_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值编码',
  `prp_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性ID',
  `option` varchar(255) NOT NULL DEFAULT '' COMMENT '属性选项值',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值图片',
  `color` varchar(255) NOT NULL DEFAULT '' COMMENT '颜色值',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '显示排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='属性选项值表';

-- ----------------------------
-- Records of jipu_property_option
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_receiver
-- ----------------------------
DROP TABLE IF EXISTS `jipu_receiver`;
CREATE TABLE `jipu_receiver` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人手机',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人电话（座机）',
  `province` int(10) NOT NULL DEFAULT '0' COMMENT '省ID',
  `district` int(10) NOT NULL DEFAULT '0' COMMENT '市ID',
  `city` int(10) NOT NULL DEFAULT '0' COMMENT '区/县ID',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人地区（省市区+街道）',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `zipcode` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人邮编',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为默认收货地址',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '修改日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收货人信息表';

-- ----------------------------
-- Records of jipu_receiver
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_recharge
-- ----------------------------
DROP TABLE IF EXISTS `jipu_recharge`;
CREATE TABLE `jipu_recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` smallint(4) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `order_status` int(11) NOT NULL DEFAULT '0' COMMENT '订单状态（-1：已取消，0：交易中，1：交易成功）',
  `payment_type_id` smallint(4) NOT NULL DEFAULT '0' COMMENT '支付方式ID',
  `payment_type` varchar(255) NOT NULL DEFAULT '' COMMENT '支付方式',
  `payment_bank` char(30) NOT NULL COMMENT '付款银行',
  `payment_status` int(11) NOT NULL DEFAULT '0' COMMENT '支付状态（0：未支付，1：已支付）',
  `invoice_status` int(11) NOT NULL DEFAULT '0' COMMENT '开票状态（0：未申请，1：出票中，2：已出票）',
  `invoice_need` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否需要开发票：0-否，1-是',
  `invoice_type` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '发票类型：1-个人，2-公司',
  `invoice_title` varchar(255) NOT NULL DEFAULT '' COMMENT '发票抬头',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `memo` varchar(255) DEFAULT NULL COMMENT '附言',
  `order_from` tinyint(1) NOT NULL DEFAULT '1' COMMENT '充值订单来源：1-PC，2-手机触屏，3-微信，4-平板，5-iOS，6-Android',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '修改日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户充值表';

-- ----------------------------
-- Records of jipu_recharge
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_redpacket
-- ----------------------------
DROP TABLE IF EXISTS `jipu_redpacket`;
CREATE TABLE `jipu_redpacket` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户id',
  `open_id` char(32) NOT NULL COMMENT '微信用户id',
  `redpacket_id` int(11) NOT NULL DEFAULT '0' COMMENT '参抢的红包id',
  `redpacket_status` char(10) NOT NULL COMMENT '红包状态(receive:收到的红包，send:发出的红包)',
  `msg` varchar(60) DEFAULT NULL COMMENT '发红包时的红包描述',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包金额',
  `quantity` int(5) NOT NULL COMMENT '红包数量',
  `type` char(10) NOT NULL COMMENT '红包类型（multi:群红包，single:定向红包）',
  `order_sn` varchar(255) NOT NULL COMMENT '红包支付id',
  `pay_money` decimal(10,2) NOT NULL COMMENT '支付的金额',
  `payment_type` varchar(255) NOT NULL COMMENT '支付方式',
  `payment_status` int(2) NOT NULL DEFAULT '0' COMMENT '支付状态（0：未支付，1：已支付）',
  `payment_return` varchar(2000) NOT NULL COMMENT '支付平台返回的数据',
  `payment_time` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `expire_time` int(10) NOT NULL COMMENT '红包被抢完的时间',
  `roll_out` char(3) NOT NULL DEFAULT 'in' COMMENT '红包是否转出（out:转出；in:没转出）',
  `roll_out_time` int(10) unsigned NOT NULL COMMENT '红包转出时间',
  `limit_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '限制最大红包金额，为0时为不限制',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '红包的状态（当为0时，为进行中；1为抢购已完成）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='红包表';

-- ----------------------------
-- Records of jipu_redpacket
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_red_package
-- ----------------------------
DROP TABLE IF EXISTS `jipu_red_package`;
CREATE TABLE `jipu_red_package` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(32) DEFAULT '' COMMENT '加密码',
  `uid` int(11) DEFAULT '1' COMMENT '发送人',
  `name` varchar(255) DEFAULT '' COMMENT '活动名称',
  `number` int(11) DEFAULT '1' COMMENT '红包个数',
  `amount` decimal(8,2) DEFAULT '0.00' COMMENT '总金额',
  `send_number` int(11) DEFAULT '0' COMMENT '发出数量',
  `send_amount` decimal(8,2) DEFAULT '0.00' COMMENT '发出金额',
  `info` varchar(255) DEFAULT '' COMMENT '祝福语',
  `expire_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '截止时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1正常-1删除0禁用 2已领完 3已过期）',
  `share_title` varchar(200) DEFAULT '' COMMENT '分享标题',
  `share_desc` varchar(255) DEFAULT '' COMMENT '分享描述',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='红包表';

-- ----------------------------
-- Records of jipu_red_package
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_red_package_record
-- ----------------------------
DROP TABLE IF EXISTS `jipu_red_package_record`;
CREATE TABLE `jipu_red_package_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `red_package_id` int(11) DEFAULT '0' COMMENT '红包ID',
  `uid` int(11) DEFAULT '0' COMMENT '领取红包UID',
  `open_id` char(64) DEFAULT '' COMMENT '微信open_id',
  `amount` decimal(8,2) DEFAULT '0.00' COMMENT '红包金额',
  `nickname` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '昵称',
  `sex` tinyint(4) DEFAULT '0' COMMENT '性别',
  `avatar` varchar(200) DEFAULT '' COMMENT '头像地址',
  `country` varchar(100) DEFAULT '' COMMENT '国家',
  `province` varchar(100) DEFAULT '' COMMENT '省',
  `city` varchar(100) DEFAULT '' COMMENT '城市',
  `subscribe_time` int(10) DEFAULT '0' COMMENT '关注时间',
  `create_time` int(10) DEFAULT '0' COMMENT '记录创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_red_package_record
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_refund
-- ----------------------------
DROP TABLE IF EXISTS `jipu_refund`;
CREATE TABLE `jipu_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `operator_uid` int(10) NOT NULL DEFAULT '0' COMMENT '操作员UID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `trade_no` varchar(255) NOT NULL COMMENT '交易订单号',
  `refund_no` varchar(255) NOT NULL COMMENT '退款订单号',
  `refund_type` varchar(50) NOT NULL DEFAULT '' COMMENT '退款类型（item:购物退款、redpacket:红包退款',
  `payment_type` varchar(50) NOT NULL COMMENT '订单支付方式',
  `amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '退款金额',
  `detail` varchar(255) NOT NULL COMMENT '退款详细描述',
  `refund_return` text NOT NULL COMMENT '第三方返回数据',
  `refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退款表';

-- ----------------------------
-- Records of jipu_refund
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_score_log
-- ----------------------------
DROP TABLE IF EXISTS `jipu_score_log`;
CREATE TABLE `jipu_score_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `type` char(10) NOT NULL DEFAULT '' COMMENT '积分类型（in：收入，out：支出）',
  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分数量',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '积分备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员积分日志';

-- ----------------------------
-- Records of jipu_score_log
-- ----------------------------
INSERT INTO `jipu_score_log` VALUES ('1', '2', '0', '', 'in', '10', '每日登录奖励', '1469421732');

-- ----------------------------
-- Table structure for jipu_sdp_record
-- ----------------------------
DROP TABLE IF EXISTS `jipu_sdp_record`;
CREATE TABLE `jipu_sdp_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买人UID',
  `sdp_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销店铺UID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `item_code` varchar(255) DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品价格',
  `quantity` smallint(4) DEFAULT '0' COMMENT '购买数量',
  `cashback_amount` decimal(10,2) DEFAULT '0.00' COMMENT '返现金额',
  `create_time` int(10) DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分销记录表';

-- ----------------------------
-- Records of jipu_sdp_record
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_search
-- ----------------------------
DROP TABLE IF EXISTS `jipu_search`;
CREATE TABLE `jipu_search` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `result` text NOT NULL COMMENT '搜索结构集',
  `num` int(10) NOT NULL DEFAULT '0' COMMENT '搜索次数',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索索引表';

-- ----------------------------
-- Records of jipu_search
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_seckill
-- ----------------------------
DROP TABLE IF EXISTS `jipu_seckill`;
CREATE TABLE `jipu_seckill` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `item_ids` varchar(20000) NOT NULL DEFAULT '0' COMMENT '商品IDS',
  `start_time` int(10) NOT NULL COMMENT '开始时间',
  `expire_time` int(10) NOT NULL COMMENT '过期时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1-可用，0-禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='秒杀活动表';

-- ----------------------------
-- Records of jipu_seckill
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_seckill_item
-- ----------------------------
DROP TABLE IF EXISTS `jipu_seckill_item`;
CREATE TABLE `jipu_seckill_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `seckill_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '秒杀活动id',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `item_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品秒杀价格',
  `item_stock` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '秒杀商品库存',
  `quota_num` int(11) unsigned DEFAULT '0' COMMENT '秒杀商品限购',
  `item_spc` varchar(255) NOT NULL DEFAULT '' COMMENT '秒杀商品规格编码（all-针对没有规格的产品，其他的对应相应的规格）',
  `stime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '秒杀开始时间',
  `etime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '秒杀结束时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：1-可用，0-禁用',
  PRIMARY KEY (`id`),
  KEY `item` (`item_id`) USING BTREE,
  KEY `time` (`stime`,`etime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='秒杀产品、规格表';

-- ----------------------------
-- Records of jipu_seckill_item
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_second_pieces
-- ----------------------------
DROP TABLE IF EXISTS `jipu_second_pieces`;
CREATE TABLE `jipu_second_pieces` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '折扣',
  `start_time` int(10) NOT NULL COMMENT '开始时间',
  `expire_time` int(10) NOT NULL COMMENT '过期时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1-可用，0-禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='第二件折扣表';

-- ----------------------------
-- Records of jipu_second_pieces
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_ship
-- ----------------------------
DROP TABLE IF EXISTS `jipu_ship`;
CREATE TABLE `jipu_ship` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `supplier_id` int(11) DEFAULT '0' COMMENT '供应商ID',
  `delivery_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '物流单号',
  `delivery_name` varchar(255) NOT NULL COMMENT '配送方式',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '修改日期',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（1正常 -1已删除）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='发货单';

-- ----------------------------
-- Records of jipu_ship
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_shop
-- ----------------------------
DROP TABLE IF EXISTS `jipu_shop`;
CREATE TABLE `jipu_shop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '店铺所属用户ID',
  `secret` char(16) DEFAULT '' COMMENT '店铺分享key',
  `name` char(100) DEFAULT '' COMMENT '店铺名称',
  `logo` varchar(200) DEFAULT NULL COMMENT '店铺头像',
  `intro` varchar(255) DEFAULT NULL COMMENT '店铺简介',
  `total_revenue` decimal(10,2) DEFAULT '0.00' COMMENT '累计收入',
  `item_ids` varchar(500) DEFAULT '0' COMMENT '商品列表',
  `audit_data` text NOT NULL COMMENT '审核资料',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) DEFAULT '0' COMMENT '最后更新时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '店铺状态（1正常 0已禁用）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分销店铺表';

-- ----------------------------
-- Records of jipu_shop
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_sms
-- ----------------------------
DROP TABLE IF EXISTS `jipu_sms`;
CREATE TABLE `jipu_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(50) DEFAULT '' COMMENT '手机号码',
  `tpl_id` int(11) DEFAULT '0' COMMENT '请求类型',
  `content` varchar(255) DEFAULT '' COMMENT '短信内容',
  `code` varchar(50) DEFAULT '' COMMENT '验证码',
  `ip` varchar(50) DEFAULT '' COMMENT '请求的IP地址',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `validate_status` tinyint(1) DEFAULT '0' COMMENT '验证状态 0 未验证 1已验证',
  `validate_time` int(10) DEFAULT '0' COMMENT '验证通过时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='短信记录表';

-- ----------------------------
-- Records of jipu_sms
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_supplier
-- ----------------------------
DROP TABLE IF EXISTS `jipu_supplier`;
CREATE TABLE `jipu_supplier` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供应商id，其实是uid',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '供应商名称',
  `link_name` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人名称',
  `link_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系方式',
  `notice_mobile` varchar(20) DEFAULT NULL COMMENT '新订单短信通知手机号',
  `free_amount` int(5) NOT NULL DEFAULT '39' COMMENT '免运费限额',
  `key` varchar(50) NOT NULL DEFAULT '' COMMENT '加密key',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常0禁用-1删除',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_supplier
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_transaction
-- ----------------------------
DROP TABLE IF EXISTS `jipu_transaction`;
CREATE TABLE `jipu_transaction` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `flowid` varchar(255) NOT NULL DEFAULT '' COMMENT '流水号',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `number` varchar(255) NOT NULL DEFAULT '' COMMENT '交易编号（第三方支付返回的交易号）',
  `type` char(10) NOT NULL DEFAULT '' COMMENT '交易类型（充值、消费、退款）',
  `mode` varchar(255) NOT NULL DEFAULT '' COMMENT '交易方式（支付宝、网银、微信、站内）',
  `terminal` varchar(255) NOT NULL DEFAULT '' COMMENT '交易终端（PC、平板、手机、电视）',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '交易金额',
  `balance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '交易前账户余额',
  `flow` varchar(255) NOT NULL DEFAULT '' COMMENT '资金流向（in：收入，out：支出）',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '交易备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `transaction_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '交易时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '交易状态（0：交易中，1：交易成功，-1：交易失败）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='现金交易明细表';

-- ----------------------------
-- Records of jipu_transaction
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_union
-- ----------------------------
DROP TABLE IF EXISTS `jipu_union`;
CREATE TABLE `jipu_union` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1桌面商家2普通推广',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'uid',
  `qrcode_url` varchar(255) NOT NULL DEFAULT '' COMMENT '带参数的二维码URL',
  `name` varchar(255) DEFAULT '' COMMENT '商家名称',
  `link_name` varchar(255) DEFAULT '' COMMENT '联系人名称',
  `link_mobile` varchar(20) DEFAULT '' COMMENT '联系方式',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常0禁用-1删除',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_union
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_url
-- ----------------------------
DROP TABLE IF EXISTS `jipu_url`;
CREATE TABLE `jipu_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表';

-- ----------------------------
-- Records of jipu_url
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_user
-- ----------------------------
DROP TABLE IF EXISTS `jipu_user`;
CREATE TABLE `jipu_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `group_id` tinyint(10) unsigned DEFAULT NULL COMMENT '用户组id',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `mobile_bind_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机绑定状态（0未绑定1已绑定）',
  `from_union_id` int(11) DEFAULT '0' COMMENT '推广联盟-推广者uid',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- ----------------------------
-- Records of jipu_user
-- ----------------------------
INSERT INTO `jipu_user` VALUES ('1', '1', 'jipushop', '9e2387b7c6ec2bf88eb5ebd8449424e8', 'jipushop@jipushop.com', '', '0', '0', '1455518510', '3663224620', '1469421599', '2130706433', '1455518510', '1');
INSERT INTO `jipu_user` VALUES ('2', '12', 'shop', '9e2387b7c6ec2bf88eb5ebd8449424e8', '', '', '0', '0', '1469421216', '2130706433', '1469421924', '2130706433', '1469421216', '1');

-- ----------------------------
-- Table structure for jipu_usercount
-- ----------------------------
DROP TABLE IF EXISTS `jipu_usercount`;
CREATE TABLE `jipu_usercount` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `uid` int(11) NOT NULL COMMENT '用户UID',
  `key` varchar(50) NOT NULL COMMENT 'Key',
  `value` text COMMENT '对应Key的值',
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '当前当时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user-key` (`uid`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_usercount
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_userdata
-- ----------------------------
DROP TABLE IF EXISTS `jipu_userdata`;
CREATE TABLE `jipu_userdata` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型标识',
  `target_id` int(10) unsigned NOT NULL COMMENT '目标id',
  UNIQUE KEY `uid` (`uid`,`type`,`target_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_userdata
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_user_account
-- ----------------------------
DROP TABLE IF EXISTS `jipu_user_account`;
CREATE TABLE `jipu_user_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `type` char(30) NOT NULL DEFAULT '' COMMENT '账号类型',
  `bankname` char(255) DEFAULT NULL COMMENT '银行名称',
  `account` varchar(255) NOT NULL DEFAULT '' COMMENT '账号',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常-1已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_user_account
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_user_group
-- ----------------------------
DROP TABLE IF EXISTS `jipu_user_group`;
CREATE TABLE `jipu_user_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `discount` decimal(8,2) unsigned NOT NULL DEFAULT '1.00' COMMENT '用户组折扣',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_user_group
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_wechat_event
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_event`;
CREATE TABLE `jipu_wechat_event` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT '' COMMENT '事件',
  `data` varchar(500) DEFAULT '' COMMENT '内容json',
  `keyword` varchar(100) DEFAULT '' COMMENT '关联关键字',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信菜单点击事件推送内容';

-- ----------------------------
-- Records of jipu_wechat_event
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_wechat_log
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_log`;
CREATE TABLE `jipu_wechat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cTime` int(11) DEFAULT NULL,
  `cTime_format` varchar(30) DEFAULT NULL,
  `data` text,
  `data_post` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_wechat_log
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_wechat_loginpc
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_loginpc`;
CREATE TABLE `jipu_wechat_loginpc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` char(80) DEFAULT '' COMMENT '请求IP地址',
  `session_id` char(60) DEFAULT '' COMMENT '访问session_id',
  `login_time` int(10) DEFAULT '0' COMMENT '登录时间',
  `login_uid` int(11) DEFAULT '0' COMMENT '登录用户Id',
  `status` tinyint(1) DEFAULT '0' COMMENT '登录状态',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信快速登录PC';

-- ----------------------------
-- Records of jipu_wechat_loginpc
-- ----------------------------
INSERT INTO `jipu_wechat_loginpc` VALUES ('1', '127.0.0.1', '19d8bd7k4g8h1ou5a4o3sntqe2', '0', '0', '0', '1469419926');

-- ----------------------------
-- Table structure for jipu_wechat_menu
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_menu`;
CREATE TABLE `jipu_wechat_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` char(100) DEFAULT NULL COMMENT '按钮类型',
  `url` varchar(255) DEFAULT NULL COMMENT '关联URL',
  `keyword` varchar(100) DEFAULT NULL COMMENT '关联关键词',
  `event` char(50) DEFAULT NULL COMMENT '点击推事件',
  `title` varchar(50) NOT NULL COMMENT '菜单名',
  `pid` tinyint(2) DEFAULT '0' COMMENT '上级菜单',
  `sort` tinyint(4) DEFAULT '0' COMMENT '排序号',
  `token` varchar(255) NOT NULL COMMENT 'Token',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信自定义菜单';

-- ----------------------------
-- Records of jipu_wechat_menu
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_wechat_msg
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_msg`;
CREATE TABLE `jipu_wechat_msg` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `type` char(50) NOT NULL COMMENT '消息类型：text-文本， image-图片，voice-声音，news-图文消息',
  `option` char(20) NOT NULL COMMENT '消息选项：singel-单图文，multi-多图文',
  `event` char(50) NOT NULL COMMENT '事件类型',
  `keyword` varchar(50) NOT NULL COMMENT '匹配关键词',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `attach` varchar(50) NOT NULL COMMENT '附件ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1-可用，0-禁用，-1-已删除',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信消息配置表';

-- ----------------------------
-- Records of jipu_wechat_msg
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_wechat_qrcode_log
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_qrcode_log`;
CREATE TABLE `jipu_wechat_qrcode_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `union_id` int(11) NOT NULL COMMENT '推广联盟ID',
  `open_id` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_wechat_qrcode_log
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_wechat_tpl_msg
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_tpl_msg`;
CREATE TABLE `jipu_wechat_tpl_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT '' COMMENT '模板类型(order 订单)',
  `tpl_key` varchar(50) DEFAULT '' COMMENT '模板key',
  `tpl_id` varchar(100) DEFAULT NULL COMMENT '模板ID',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1已启用 0禁用）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jipu_wechat_tpl_msg
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_wechat_user
-- ----------------------------
DROP TABLE IF EXISTS `jipu_wechat_user`;
CREATE TABLE `jipu_wechat_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `subscribe` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关注/取消关注',
  `openid` varchar(32) NOT NULL COMMENT '用户openid',
  `nickname` varchar(32) NOT NULL COMMENT '昵称',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `language` varchar(30) NOT NULL COMMENT '语言',
  `city` varchar(20) NOT NULL COMMENT '市',
  `province` varchar(20) NOT NULL COMMENT '省',
  `country` varchar(20) NOT NULL COMMENT '国家',
  `headimgurl` varchar(255) NOT NULL COMMENT '头像地址',
  `subscribe_time` int(10) DEFAULT '0' COMMENT '关注时间',
  `remark` varchar(50) NOT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信粉丝表';

-- ----------------------------
-- Records of jipu_wechat_user
-- ----------------------------

-- ----------------------------
-- Table structure for jipu_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `jipu_withdraw`;
CREATE TABLE `jipu_withdraw` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `account_id` int(1) unsigned NOT NULL COMMENT '用户账户ID',
  `amount` decimal(10,2) NOT NULL COMMENT '提现金额',
  `fee` decimal(5,2) DEFAULT '0.00' COMMENT '手续费',
  `status` smallint(3) NOT NULL DEFAULT '100' COMMENT '状态http://t.cn/RLH1bDQ',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `admin_refuse_time` int(11) DEFAULT NULL COMMENT '管理员拒绝时间',
  `bank_time` int(11) DEFAULT NULL COMMENT '银行受理时间',
  `bank_refuse_time` int(11) DEFAULT NULL COMMENT '银行拒绝时间',
  `success_time` int(11) DEFAULT NULL COMMENT '成功时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of jipu_withdraw
-- ----------------------------
