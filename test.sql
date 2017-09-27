/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-09-27 10:37:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admins`
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL COMMENT '登录账号',
  `real_name` varchar(100) NOT NULL,
  `usr_pwd` varchar(100) NOT NULL,
  `pay_pwd` varchar(100) DEFAULT NULL COMMENT '支付密码',
  `usr_name` varchar(100) NOT NULL,
  `qq` varchar(20) NOT NULL COMMENT '联系QQ',
  `last_login` varchar(10) NOT NULL COMMENT '最后登入时间',
  `last_ip` varchar(15) DEFAULT NULL,
  `is_del` smallint(1) NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺s_id',
  `last_service_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后服务时间',
  `group_id` int(5) NOT NULL DEFAULT '0',
  `union_type` smallint(1) DEFAULT '0' COMMENT '公会等级',
  `user_code` varchar(100) DEFAULT NULL,
  `token` varchar(32) NOT NULL DEFAULT '0',
  `modules` varchar(500) DEFAULT NULL,
  `apps` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_code` (`user_code`),
  KEY `user_code_2` (`user_code`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES ('1', 'yz', '影子', '88316675d7882e3fdbe066000273842c', 'e10adc3949ba59abbe56e057f20f883e', 'yyq', '860', '1505294663', '127.0.0.1', '0', '0', '1505294663', '1', '0', 'yyqtest', '439d14e4-dc4f-5c6a-f69b-fa944663', '', '1003,1002');

-- ----------------------------
-- Table structure for `admin_groups`
-- ----------------------------
DROP TABLE IF EXISTS `admin_groups`;
CREATE TABLE `admin_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `group_name` varchar(100) DEFAULT NULL COMMENT '组名',
  `ch_name` varchar(50) NOT NULL,
  `status` int(1) DEFAULT '1' COMMENT '状态',
  `module` varchar(200) DEFAULT NULL,
  `permissions` varchar(200) DEFAULT NULL,
  `operations` text COMMENT '操作权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_groups
-- ----------------------------
INSERT INTO `admin_groups` VALUES ('1', 'admin', '超管', '1', '6,13,15,14,7,73,2,69,68,67,66,63,76,64,59,61,60,65,65,65,65', '6,13,15,14,7,73,2,69,68,67,66,63,76,64,59,61,60,65,65,65,65', null);
INSERT INTO `admin_groups` VALUES ('2', 'svip', '超级vip', '1', null, null, null);
INSERT INTO `admin_groups` VALUES ('3', 'vip', 'vip', '1', null, null, null);
INSERT INTO `admin_groups` VALUES ('4', 'product', '产品', '1', null, null, null);
INSERT INTO `admin_groups` VALUES ('5', 'gao7media', '投放', '1', null, null, null);
INSERT INTO `admin_groups` VALUES ('6', 'business', '商务', '1', '2,65,69,68,67,66,63,76,64,59,61,60', '2,65,69,68,67,66,63,76,64,59,61,60', null);
INSERT INTO `admin_groups` VALUES ('7', 'operate', '运营', '1', null, null, null);
INSERT INTO `admin_groups` VALUES ('8', 'test', '测试', '1', '2,65,69', '2,65,69', '98-1,98-2,98-3,98-4,98-5,97-1,97-2,97-3,97-4,97-5,96-1,96-2,96-3,96-4,96-5,95-1,95-2,95-3,95-4,95-5');
INSERT INTO `admin_groups` VALUES ('9', 'shop_admin', '店铺管理', '1', null, null, null);
INSERT INTO `admin_groups` VALUES ('10', 'union', '公会', '1', '2,106,102,65,85,68,67,66', '2,106,102,65,85,68,67,66', '107-1,107-2,107-3,102-1,102-2,102-3,85-1,85-2,85-3,85-4,85-5,68-1,68-2,68-3,68-4,68-5,67-1,67-2,67-3,67-4,67-5,66-1,66-2,66-3,66-4,66-5');
INSERT INTO `admin_groups` VALUES ('11', 'channel', '渠道', '1', '2,102,106,105', '2,102,106,105', '98-1,98-2,98-3,98-4,98-5,85-1,85-2,85-3,85-4,85-5,68-1,68-2,68-3,68-4,68-5');

-- ----------------------------
-- Table structure for `admin_login_log`
-- ----------------------------
DROP TABLE IF EXISTS `admin_login_log`;
CREATE TABLE `admin_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) DEFAULT '0',
  `usr_name` varchar(100) DEFAULT NULL,
  `desc` varchar(100) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `browser` varchar(216) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_login_log
-- ----------------------------
INSERT INTO `admin_login_log` VALUES ('1', '1', 'yz', '登录成功', '2017-09-13 15:35:19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36', '88316675d7882e3fdbe066000273842c');
INSERT INTO `admin_login_log` VALUES ('2', '1', 'yz', '登录成功', '2017-09-13 17:03:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36', '88316675d7882e3fdbe066000273842c');
INSERT INTO `admin_login_log` VALUES ('3', '1', 'yz', '登录成功', '2017-09-13 17:19:17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36', '88316675d7882e3fdbe066000273842c');
INSERT INTO `admin_login_log` VALUES ('4', '1', 'yz', '登录成功', '2017-09-13 17:19:45', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36', '88316675d7882e3fdbe066000273842c');
INSERT INTO `admin_login_log` VALUES ('5', '1', 'yz', '登录成功', '2017-09-13 17:21:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36', '88316675d7882e3fdbe066000273842c');
INSERT INTO `admin_login_log` VALUES ('6', '1', 'yz', '登录成功', '2017-09-13 17:24:23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3159.5 Safari/537.36', '88316675d7882e3fdbe066000273842c');

-- ----------------------------
-- Table structure for `admin_menus`
-- ----------------------------
DROP TABLE IF EXISTS `admin_menus`;
CREATE TABLE `admin_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT '父ID',
  `name` varchar(128) DEFAULT '' COMMENT '菜单名称',
  `mid` int(11) DEFAULT NULL COMMENT '模块ID',
  `url` varchar(512) DEFAULT '' COMMENT 'URL',
  `tabid` varchar(512) DEFAULT '' COMMENT '菜单唯一码',
  `target` varchar(128) DEFAULT '' COMMENT '页面类型',
  `class` varchar(128) DEFAULT '' COMMENT '菜单样式',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态（0显示1不显示）',
  `is_del` tinyint(4) DEFAULT '0' COMMENT '1-删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_menus
-- ----------------------------
INSERT INTO `admin_menus` VALUES ('2', '0', '旅游事业', '2', '', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('6', '0', '系统设置', '6', '', '', '', 'fa fa-cog', '0', '0');
INSERT INTO `admin_menus` VALUES ('7', '6', '菜单设置', '0', 'menus.php?act=list', 'menu_view', 'navTab', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('13', '6', '账号管理', '0', '', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('14', '13', '账号管理', '0', 'account.php?act=list', 'admins', 'navTab', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('15', '13', '用户组管理', '0', 'account.php?act=groups', 'groups', 'navTab', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('59', '2', '景点管理', '0', '', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('60', '59', '景点配置', '0', 'scenery.php?act=list', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('61', '59', '游戏商品', '0', 'product.php?act=list', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('63', '2', '客服系统', '0', '', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('64', '63', '用户反馈', '0', 'feedback.php?act=list', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('65', '2', '数据日志', '0', '', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('66', '65', '充值数据', '0', 'orders.php?act=list', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('67', '65', '设备数据', '0', 'qa.php?act=device', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('68', '65', '角色数据', '0', 'qa.php?act=role', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('69', '65', '登入数据', '0', 'qa.php?act=login', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('73', '7', '菜单设置', '0', 'menu.php?act=list', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('76', '63', '账号找回', null, 'feedback.php?act=account_retrieve', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('133', '65', '用户查询', null, 'user.php?act=list', '', '', '', '0', '0');
INSERT INTO `admin_menus` VALUES ('134', '59', '城市管理', null, 'city.php?act=list', '', '', '', '0', '0');

-- ----------------------------
-- Table structure for `admin_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `module` text COMMENT '模块ID逗号隔开',
  `permissions` text COMMENT '权限',
  `token` varchar(30) NOT NULL DEFAULT '0',
  `operations` text COMMENT '操作权限',
  `user_groups` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否组或用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
INSERT INTO `admin_permissions` VALUES ('1', '1', '6,13,15,14,7,73,2,65,133,69,68,67,66,63,76,64,59,61,60', '6,13,15,14,7,73,2,65,133,69,68,67,66,63,76,64,59,61,60', '0', '67-1,67-2,67-3,67-4,67-5,68-1,68-2,68-3,68-4,68-5,69-1,69-2,69-3,69-4,69-5,85-1,85-2,85-3,85-4,85-5,88-5,73-1,73-2,73-3,73-4,73-5,91-1,91-2,91-3,91-4,91-5,92-1,92-2,92-3,92-4,92-5,93-1,93-2,93-3,93-4,93-5,95-1,95-2,95-3,95-4,95-5,96-1,96-2,96-3,96-4,96-5,97-1,97-2,97-3,97-4,97-5,98-1,98-2,98-3,98-4,98-5', '0');

-- ----------------------------
-- Table structure for `apps`
-- ----------------------------
DROP TABLE IF EXISTS `apps`;
CREATE TABLE `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `app_key` varchar(32) NOT NULL,
  `app_icon` varchar(255) DEFAULT NULL,
  `app_name` varchar(50) NOT NULL,
  `app_type` smallint(1) NOT NULL DEFAULT '1' COMMENT '1:安卓 2:ios',
  `add_time` varchar(20) NOT NULL DEFAULT '0',
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '1:正常 2:关闭',
  `sdk_order_url` varchar(200) DEFAULT NULL,
  `sdk_charge_url` varchar(200) DEFAULT NULL,
  `role_type` smallint(1) NOT NULL DEFAULT '0' COMMENT '0 角色名 1角色id',
  `web_url` varchar(200) NOT NULL COMMENT '官网地址',
  `web_serv_url` varchar(200) DEFAULT NULL,
  `web_user_url` varchar(200) DEFAULT NULL,
  `web_order_url` varchar(200) DEFAULT NULL,
  `web_charge_url` varchar(200) DEFAULT NULL,
  `lastupdate` varchar(20) NOT NULL DEFAULT '0',
  `apk_url` varchar(200) DEFAULT NULL COMMENT '源包地址[安卓]',
  `notice` text,
  `notice_status` smallint(1) NOT NULL DEFAULT '0',
  `version` varchar(10) NOT NULL DEFAULT '1.0.0.0',
  `version_time` varchar(30) NOT NULL DEFAULT '0',
  `version_url` varchar(300) DEFAULT NULL,
  `up_title` varchar(300) DEFAULT NULL COMMENT '更新标题',
  `up_desc` varchar(300) DEFAULT NULL COMMENT '更新内容',
  `up_status` smallint(1) NOT NULL DEFAULT '0' COMMENT '状态：0.更新关闭  2.测试ip更新 1.开启更新',
  `payee_ch` smallint(1) NOT NULL DEFAULT '1' COMMENT '收款方  1.福建牛牛   2.海南牛牛',
  `access_type` smallint(1) NOT NULL DEFAULT '0' COMMENT '接入类型  0:接入中 1.接入完成 2.终止接入 3.预接入',
  `verify_type` int(1) DEFAULT '0',
  `channel` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of apps
-- ----------------------------

-- ----------------------------
-- Table structure for `app_goods`
-- ----------------------------
DROP TABLE IF EXISTS `app_goods`;
CREATE TABLE `app_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `good_name` varchar(50) DEFAULT NULL,
  `good_code` varchar(200) NOT NULL,
  `good_unit` varchar(20) NOT NULL,
  `good_amount` int(5) NOT NULL,
  `good_type` smallint(1) NOT NULL DEFAULT '1',
  `good_intro` varchar(255) NOT NULL,
  `good_price` float(5,0) NOT NULL,
  `status` smallint(1) NOT NULL DEFAULT '1',
  `app_id` int(11) NOT NULL,
  `rec_type` tinyint(1) DEFAULT '1' COMMENT '1:web充值  2:Google充值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of app_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `city`
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province` varchar(255) DEFAULT NULL COMMENT '城市名',
  `city` varchar(255) DEFAULT NULL,
  `add_time` varchar(30) DEFAULT NULL,
  `is_hot` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of city
-- ----------------------------

-- ----------------------------
-- Table structure for `orders`
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL COMMENT '订单号，guid生成',
  `title` varchar(200) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL COMMENT '购买人用户id',
  `role_id` varchar(20) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL COMMENT '商品id',
  `amount` int(5) DEFAULT '1' COMMENT '数量',
  `unit_price` float DEFAULT NULL COMMENT '单价',
  `pay_money` float DEFAULT NULL COMMENT '应付金额',
  `app_id` int(11) DEFAULT NULL COMMENT '应用id',
  `serv_id` varchar(11) DEFAULT NULL COMMENT '区服id',
  `status` smallint(1) DEFAULT '0' COMMENT '订单状态：0已下单 1已付款 2已发货 9接口请求',
  `pay_price` float NOT NULL COMMENT '实际付款金额',
  `buy_time` varchar(30) DEFAULT '0' COMMENT '购买时间',
  `pay_time` varchar(30) DEFAULT '0' COMMENT '支付时间',
  `charge_time` int(30) DEFAULT '0' COMMENT '回调时间',
  `pay_channel` int(2) DEFAULT '1' COMMENT '支付渠道 1.支付宝 2.微信 3.牛币 5.QQ钱包 6.牛点',
  `pay_order_id` varchar(100) DEFAULT NULL COMMENT '支付渠道订单号',
  `bank_order_id` varchar(100) DEFAULT NULL COMMENT '银行单号',
  `payer` varchar(100) DEFAULT NULL COMMENT '支付人',
  `role_name` varchar(100) DEFAULT NULL COMMENT '角色名',
  `app_order_id` varchar(200) DEFAULT NULL COMMENT 'cp订单号',
  `ip` varchar(30) DEFAULT NULL COMMENT 'ip地址',
  `pay_from` smallint(1) DEFAULT '1' COMMENT '下单来源：1：sdk',
  `payExpandData` text,
  `ch_type` int(1) NOT NULL DEFAULT '0' COMMENT '渠道分类 1.公会 2.信息流 3.web充值 4.其他',
  `currency` varchar(20) NOT NULL COMMENT '货币名称',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `pay_money` (`pay_money`),
  KEY `app_id` (`app_id`),
  KEY `status` (`status`),
  KEY `buy_time` (`buy_time`),
  KEY `pay_time` (`pay_time`),
  KEY `pay_from` (`pay_from`),
  KEY `app_order_id` (`app_order_id`(191)),
  KEY `pay_channel` (`pay_channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of orders
-- ----------------------------

-- ----------------------------
-- Table structure for `scenery`
-- ----------------------------
DROP TABLE IF EXISTS `scenery`;
CREATE TABLE `scenery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '标题',
  `subtitle` varchar(255) DEFAULT '短标题',
  `content` varchar(255) DEFAULT '内容',
  `add_time` varchar(30) DEFAULT NULL,
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '0否 1是',
  `is_del` tinyint(1) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL COMMENT '城市ID',
  `img` varchar(500) DEFAULT NULL COMMENT '展示图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of scenery
-- ----------------------------

-- ----------------------------
-- Table structure for `stats_device`
-- ----------------------------
DROP TABLE IF EXISTS `stats_device`;
CREATE TABLE `stats_device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ActIP` varchar(20) DEFAULT NULL COMMENT '活跃id',
  `ActTime` int(11) DEFAULT NULL COMMENT '活跃时间',
  `Android_id` varchar(40) DEFAULT NULL COMMENT '安卓id',
  `AppID` int(11) DEFAULT NULL COMMENT '游戏id',
  `Channel` varchar(20) DEFAULT NULL COMMENT '渠道',
  `Mtype` varchar(20) DEFAULT NULL,
  `Dt` int(2) DEFAULT NULL,
  `GUID` varchar(40) DEFAULT NULL,
  `Lang` varchar(20) DEFAULT NULL,
  `Mac` varchar(20) DEFAULT NULL,
  `Net` varchar(20) DEFAULT NULL,
  `OSVer` varchar(20) DEFAULT NULL,
  `Imei` varchar(20) DEFAULT NULL,
  `RegIP` varchar(20) DEFAULT NULL,
  `RegTime` int(11) DEFAULT NULL,
  `SDKVer` varchar(20) DEFAULT NULL,
  `SID` varchar(50) DEFAULT NULL,
  `type` int(1) DEFAULT NULL COMMENT '设备类型  1.ios 2.安卓',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of stats_device
-- ----------------------------

-- ----------------------------
-- Table structure for `stats_user_app`
-- ----------------------------
DROP TABLE IF EXISTS `stats_user_app`;
CREATE TABLE `stats_user_app` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ActIP` varchar(20) DEFAULT NULL,
  `ActTime` int(11) DEFAULT NULL,
  `Imei` varchar(20) DEFAULT NULL,
  `AppID` int(11) DEFAULT NULL,
  `AreaServerID` varchar(20) DEFAULT NULL,
  `AreaServerName` varchar(30) DEFAULT NULL,
  `Channel` varchar(30) DEFAULT NULL,
  `GUID` varchar(50) DEFAULT NULL,
  `LastChannel` varchar(30) DEFAULT NULL,
  `Mac` varchar(20) DEFAULT NULL,
  `Android_id` varchar(20) DEFAULT NULL,
  `RegIP` varchar(20) DEFAULT NULL,
  `RegTime` int(11) DEFAULT NULL,
  `RoleID` varchar(20) DEFAULT NULL,
  `RoleLevel` int(5) DEFAULT NULL,
  `RoleName` varchar(20) DEFAULT NULL,
  `SID` varchar(50) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `type` int(1) DEFAULT NULL COMMENT '设备类型 1.ios 2.安卓',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of stats_user_app
-- ----------------------------

-- ----------------------------
-- Table structure for `stats_user_login_log`
-- ----------------------------
DROP TABLE IF EXISTS `stats_user_login_log`;
CREATE TABLE `stats_user_login_log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AppID` int(11) DEFAULT NULL,
  `AreaServerID` varchar(20) DEFAULT NULL,
  `AreaServerName` varchar(50) DEFAULT NULL,
  `Channel` varchar(50) DEFAULT NULL,
  `GUID` varchar(50) DEFAULT NULL,
  `IP` varchar(20) DEFAULT NULL,
  `Mac` varchar(20) DEFAULT NULL,
  `RecordTime` int(11) DEFAULT NULL,
  `RoleID` varchar(20) DEFAULT NULL,
  `RoleLevel` int(5) DEFAULT NULL,
  `RoleName` varchar(30) DEFAULT NULL,
  `SID` varchar(50) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `do` varchar(20) DEFAULT NULL,
  `type` int(1) DEFAULT NULL COMMENT '设备类型 1.ios 2.安卓',
  `Android_id` varchar(20) DEFAULT NULL,
  `Imei` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of stats_user_login_log
-- ----------------------------

-- ----------------------------
-- Table structure for `stats_user_op_log`
-- ----------------------------
DROP TABLE IF EXISTS `stats_user_op_log`;
CREATE TABLE `stats_user_op_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) DEFAULT NULL COMMENT 'ip地址',
  `do` varchar(200) DEFAULT NULL COMMENT '操作动作',
  `androidid` varchar(200) DEFAULT NULL,
  `appid` int(11) DEFAULT NULL,
  `mac` varchar(30) DEFAULT NULL,
  `imei` varchar(100) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `sid` varchar(100) DEFAULT NULL,
  `channel` varchar(30) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `type` int(1) DEFAULT NULL COMMENT '类型 1.ios 2.安卓',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of stats_user_op_log
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_account_back`
-- ----------------------------
DROP TABLE IF EXISTS `sys_account_back`;
CREATE TABLE `sys_account_back` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(254) DEFAULT NULL COMMENT '设备标示',
  `email` varchar(50) DEFAULT NULL COMMENT '用户邮箱',
  `transfer_id` varchar(20) DEFAULT NULL COMMENT '数据转移ID',
  `user_info` varchar(500) DEFAULT NULL COMMENT '用户信息',
  `device` varchar(50) DEFAULT NULL COMMENT '设备信息',
  `pay_order` varchar(50) DEFAULT NULL COMMENT '充值订单信息',
  `img_path` varchar(1000) DEFAULT NULL COMMENT '图片路径',
  `creation_time` int(11) DEFAULT NULL COMMENT '账号创建时间 ',
  `last_time` int(11) DEFAULT NULL COMMENT '最后一次登录时间',
  `other` varchar(500) DEFAULT NULL COMMENT '其他',
  `add_time` varchar(20) DEFAULT NULL COMMENT '添加时间',
  `status` int(2) NOT NULL COMMENT '状态(未处理-0,已处理-1)',
  `operator_id` int(10) NOT NULL COMMENT '操作人id',
  `reply` varchar(512) NOT NULL COMMENT '回复内容',
  `remarks` varchar(512) NOT NULL COMMENT 'GM备注',
  `operator_time` varchar(20) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_account_back
-- ----------------------------
INSERT INTO `sys_account_back` VALUES ('1', null, '860627356@qq.com', null, null, null, null, null, null, null, null, null, '0', '0', '', '', '');

-- ----------------------------
-- Table structure for `sys_faq`
-- ----------------------------
DROP TABLE IF EXISTS `sys_faq`;
CREATE TABLE `sys_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text,
  `create_time` datetime DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `appid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_module` (`module_id`,`site_id`),
  KEY `app_module` (`module_id`,`appid`)
) ENGINE=MyISAM AUTO_INCREMENT=721 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sys_faq
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_feedbacktb`
-- ----------------------------
DROP TABLE IF EXISTS `sys_feedbacktb`;
CREATE TABLE `sys_feedbacktb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL COMMENT '反馈内容',
  `create_time` datetime DEFAULT NULL,
  `feedback` varchar(1000) DEFAULT NULL COMMENT '回复内容',
  `feedback_usr` varchar(20) DEFAULT NULL COMMENT '回复人',
  `feedback_time` datetime DEFAULT NULL COMMENT '回复时间',
  `read_status` smallint(1) DEFAULT '0',
  `is_del` smallint(1) DEFAULT '0' COMMENT '假删除',
  `type` smallint(1) DEFAULT NULL COMMENT '意见反馈类型 1其他 2账号问题 3充值问题 4游戏内问题',
  `is_show` smallint(1) DEFAULT NULL,
  `osname` varchar(30) DEFAULT NULL COMMENT '系统名称',
  `gamever` varchar(30) DEFAULT NULL COMMENT '游戏版本',
  `osver` varchar(30) DEFAULT NULL COMMENT '系统版本',
  `mtype` varchar(30) DEFAULT NULL COMMENT '设备版本',
  `net` varchar(30) DEFAULT NULL COMMENT '网络类型',
  `logintype` int(2) DEFAULT NULL COMMENT '登陆类型',
  `sdkver` varchar(10) DEFAULT NULL COMMENT 'sdk版本',
  `role_name` varchar(50) DEFAULT NULL COMMENT '角色名',
  `img_path` varchar(500) DEFAULT NULL COMMENT '截图',
  `server_name` varchar(50) DEFAULT NULL COMMENT '区服名',
  `player_id` int(11) DEFAULT NULL COMMENT '玩家id',
  `service_type` int(11) DEFAULT NULL,
  `bind_level` smallint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app` (`appid`),
  KEY `appid` (`appid`,`is_del`,`read_status`,`create_time`),
  KEY `app_usr` (`is_del`,`appid`,`user_id`,`create_time`)
) ENGINE=MyISAM AUTO_INCREMENT=9636 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sys_feedbacktb
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_feedback_notice`
-- ----------------------------
DROP TABLE IF EXISTS `sys_feedback_notice`;
CREATE TABLE `sys_feedback_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) NOT NULL COMMENT '对应appid',
  `notice` varchar(512) NOT NULL COMMENT '公告内容',
  `time` varchar(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_feedback_notice
-- ----------------------------

-- ----------------------------
-- Table structure for `user_apptb`
-- ----------------------------
DROP TABLE IF EXISTS `user_apptb`;
CREATE TABLE `user_apptb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT '',
  `app_id` int(11) NOT NULL DEFAULT '0',
  `sid` varchar(80) DEFAULT NULL,
  `mac` varchar(30) DEFAULT NULL,
  `imei` varchar(50) DEFAULT NULL,
  `android_id` varchar(50) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `userid` int(11) DEFAULT '0',
  `channel` varchar(50) DEFAULT NULL,
  `acttime` varchar(20) DEFAULT NULL,
  `regtime` varchar(20) DEFAULT NULL,
  `logincount` int(11) DEFAULT '1',
  `usertype` smallint(1) DEFAULT '0',
  `last_ip` varchar(30) DEFAULT NULL,
  `last_channel` varchar(20) DEFAULT '',
  `last_do` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mac` (`sid`),
  KEY `appid_mac_userid` (`app_id`,`sid`,`userid`),
  KEY `udid` (`mac`),
  KEY `acttime` (`acttime`),
  KEY `guid` (`guid`),
  KEY `userid` (`userid`),
  KEY `pid` (`app_id`),
  KEY `regtime` (`app_id`,`regtime`),
  KEY `sid3` (`imei`),
  KEY `pid_userid` (`userid`,`app_id`),
  KEY `appid` (`sid`,`app_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3935 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of user_apptb
-- ----------------------------

-- ----------------------------
-- Table structure for `user_info`
-- ----------------------------
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `guid` varchar(36) DEFAULT '' COMMENT 'GUID',
  `nick_name` varchar(50) DEFAULT '' COMMENT '昵称',
  `login_name` varchar(30) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT '' COMMENT '真实姓名',
  `password` varchar(50) DEFAULT '' COMMENT '密码',
  `reg_time` varchar(20) DEFAULT '0' COMMENT '注册时间(时间戳)',
  `reg_ip` varchar(20) DEFAULT '' COMMENT '注册IP',
  `reg_from` smallint(1) NOT NULL DEFAULT '1' COMMENT '1: 网站 2:H5 3:微信 4:SDK 5:乐8 6:乐8预注册',
  `login_type` smallint(1) NOT NULL DEFAULT '0' COMMENT '0:游客账号 1.fb账号',
  `from_app_id` int(11) NOT NULL DEFAULT '0',
  `token` varchar(50) DEFAULT NULL,
  `last_ip` varchar(30) DEFAULT NULL,
  `channel` varchar(20) DEFAULT '66173',
  `fb_id` varchar(30) DEFAULT NULL COMMENT '脸书ID',
  `fb_bind` int(1) DEFAULT '0' COMMENT '1.绑定fb账号',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `u_id` (`user_id`) USING BTREE,
  KEY `n_name` (`nick_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=244 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_info
-- ----------------------------
INSERT INTO `user_info` VALUES ('1', '07DF363F-54B3-8A49-FD3B-ABFFF67B4EE5', '123', 'n1', 'n1', 'e10adc3949ba59abbe56e057f20f883e', '1468312009', '127.0.0.1', '1', '1', '0', 'B0C1DA27-4F89-EE75-5417-9735B2958A60', '58.22.113.58', '66173', null, '0');
INSERT INTO `user_info` VALUES ('2', '07DF363F-54B3-8A49-FD3B-ABFFF67B4EE5', '456', 'n2', 'n2', 'e10adc3949ba59abbe56e057f20f883e', '1488312009', '192.168.0.166', '2', '0', '1000', 'c2f9fd1d-1b23-11e5-8897-525400282448', null, '66173', '58', '1');
INSERT INTO `user_info` VALUES ('3', '07DF363F-54B3-8A49-FD3B-ABFFF67B4EE5', '789', 'n3', 'n3', 'e10adc3949ba59abbe56e057f20f883e', '1478312009', '192.168.0.2', '3', '0', '0', 'c2f9fd1d-1b23-11e5-8897-525400282448', null, '66173', '59', '1');
