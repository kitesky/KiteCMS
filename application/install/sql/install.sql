/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : kitecms

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-07-03 14:43:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for kite_addons
-- ----------------------------
DROP TABLE IF EXISTS `kite_addons`;
CREATE TABLE `kite_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text DEFAULT NULL COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0 未安装 1 启用 2禁用 3损坏',
  `config` text DEFAULT NULL COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否有后台列表',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of kite_addons
-- ----------------------------
INSERT INTO `kite_addons` VALUES ('3', 'demo', '演示插件', '用于演示插件', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"6\",\"display\":\"1\"}', 'kitecms', '1.0', '1', null, null);

-- ----------------------------
-- Table structure for kite_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `kite_auth_role`;
CREATE TABLE `kite_auth_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `rule_ids` varchar(1024) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '角色拥有的权限集合',
  `site_ids` varchar(1024) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '站点ID集合',
  `lang_var` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_auth_role
-- ----------------------------
INSERT INTO `kite_auth_role` VALUES ('1', '管理员', '10,3,24,36,37,38,25,39,40,41,29,30,31,32,33,34,35,6,26,27,28,5,42,43,44,45,46,47,48,49,50,82,86,52,87,88,65,66,67,68,69,70,71,56,57,58,59,60,61,62,75,76,77,78,79,80,81,51,53,54,4,7,18,19,20,21,8,15,16,17,2,1,12,13,14,9,11,22,23,72,89,90,91,92,93,63,64', '1,2', 'Administrator', '1');
INSERT INTO `kite_auth_role` VALUES ('2', '编辑员', '10,3,24,36,37,38,25,39,40,41,29,30,31,32,33,34,35,6,26,27,28', '', 'Editor', '2');
INSERT INTO `kite_auth_role` VALUES ('3', '注册用户', '10', '', 'Member', '2');

-- ----------------------------
-- Table structure for kite_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `kite_auth_rule`;
CREATE TABLE `kite_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `module` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '规则所属模型',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '规则名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `menu` tinyint(1) DEFAULT 0 COMMENT '是否为菜单0 否 1是',
  `icon` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'fa fa-circle-o' COMMENT '图标',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '备注说明',
  `lang_var` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  PRIMARY KEY (`id`),
  KEY `permission_url` (`url`) USING BTREE,
  KEY `lang_var` (`lang_var`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_auth_rule
-- ----------------------------
INSERT INTO `kite_auth_rule` VALUES ('1', '0', 'admin', '系统管理', '#', '1', 'fa fa-laptop', '6', '', 'System');
INSERT INTO `kite_auth_rule` VALUES ('2', '8', 'admin', '站点授权', 'admin/role/siteAuth', '0', '', '3', '', 'Site authorize');
INSERT INTO `kite_auth_rule` VALUES ('3', '0', 'admin', '信息管理', '#', '1', 'fa fa-file-word-o', '2', '', 'Information');
INSERT INTO `kite_auth_rule` VALUES ('4', '0', 'admin', '会员管理', '#', '1', 'fa fa-user', '4', '', 'Member');
INSERT INTO `kite_auth_rule` VALUES ('5', '0', 'admin', '功能扩展', '#', '1', 'fa fa-chrome', '3', '', 'Extended');
INSERT INTO `kite_auth_rule` VALUES ('6', '3', 'admin', '模型管理', 'admin/documentModel/index', '1', 'fa fa-cube', '4', '', 'Document model');
INSERT INTO `kite_auth_rule` VALUES ('7', '4', 'admin', '用户管理', 'admin/user/index', '1', 'fa fa-user-secret', '1', '', 'User');
INSERT INTO `kite_auth_rule` VALUES ('8', '4', 'admin', '角色管理', 'admin/role/index', '1', 'fa fa-users', '2', '', 'Role');
INSERT INTO `kite_auth_rule` VALUES ('9', '1', 'admin', '站点管理', 'admin/site/index', '1', 'fa fa-globe', '4', '', 'Sites');
INSERT INTO `kite_auth_rule` VALUES ('10', '0', 'admin', '站点首页', 'admin/index/index', '1', 'fa fa-dashboard ', '1', '', 'Control panel');
INSERT INTO `kite_auth_rule` VALUES ('11', '9', 'admin', '创建站点', 'admin/site/create', '0', 'fa fa-circle-o', '0', '', 'Create site');
INSERT INTO `kite_auth_rule` VALUES ('12', '1', 'admin', '权限配置', 'admin/rule/index', '1', 'fa fa-shield', '3', '', 'Permission');
INSERT INTO `kite_auth_rule` VALUES ('13', '12', 'admin', '增加权限', 'admin/rule/create', '0', 'fa fa-circle-o', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('14', '12', 'admin', '权限编辑', 'admin/rule/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('15', '8', 'admin', '增加角色', 'admin/role/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('16', '8', 'admin', '角色编辑', 'admin/role/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('17', '8', 'admin', '角色授权', 'admin/role/auth', '0', '', '0', '', 'Role authorization');
INSERT INTO `kite_auth_rule` VALUES ('18', '7', 'admin', '创建管理员', 'admin/user/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('19', '7', 'admin', '更新管理员', 'admin/user/edit', '0', '', '0', '', 'Update');
INSERT INTO `kite_auth_rule` VALUES ('20', '7', 'admin', '删除管理员', 'admin/user/remove', '0', '', '0', '', 'Delete');
INSERT INTO `kite_auth_rule` VALUES ('21', '7', 'admin', '管理员批量操作', 'admin/user/handle', '0', '', '0', '', 'Batch operation');
INSERT INTO `kite_auth_rule` VALUES ('22', '9', 'admin', '编辑站点', 'admin/site/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('23', '9', 'admin', '删除站点', 'admin/site/remove', '0', '', '0', '', 'Delete');
INSERT INTO `kite_auth_rule` VALUES ('24', '3', 'admin', '文档信息', 'admin/document/index', '1', 'fa fa-file-text-o', '1', '', 'Document');
INSERT INTO `kite_auth_rule` VALUES ('25', '3', 'admin', '栏目分类', 'admin/category/index', '1', 'fa fa-list-ol', '2', '', 'Category');
INSERT INTO `kite_auth_rule` VALUES ('26', '6', 'admin', '创建模型', 'admin/documentModel/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('27', '6', 'admin', '编辑模型', 'admin/documentModel/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('28', '6', 'admin', '删除模型', 'admin/documentModel/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('29', '3', 'admin', '字段管理', 'admin/documentField/index', '1', 'fa fa-cubes', '3', '', 'Document Field');
INSERT INTO `kite_auth_rule` VALUES ('30', '29', 'admin', '创建字段', 'admin/documentField/create', '0', '', '0', '', 'Create document field');
INSERT INTO `kite_auth_rule` VALUES ('31', '29', 'admin', '编辑字段', 'admin/documentField/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('32', '29', 'admin', '删除字段', 'admin/documentField/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('33', '29', 'admin', '字段类别', 'admin/documentField/category', '0', '', '0', '', 'Document field category');
INSERT INTO `kite_auth_rule` VALUES ('34', '29', 'admin', '删除字段分类', 'admin/documentField/removeCategory', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('35', '29', 'admin', '批量操作字段分类', 'admin/documentField/handleCategory', '0', '', '0', '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES ('36', '24', 'admin', '创建文档', 'admin/document/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('37', '24', 'admin', '更新文档', 'admin/document/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('38', '24', 'admin', '删除文档', 'admin/document/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('39', '25', 'admin', '创建文档', 'admin/category/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('40', '25', 'admin', '更新文档', 'admin/category/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('41', '25', 'admin', '删除文档', 'admin/category/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('42', '5', 'admin', '插件管理', 'admin/addons/index', '1', 'fa fa-plus-square', '0', '', 'Addons');
INSERT INTO `kite_auth_rule` VALUES ('43', '42', 'admin', '插件安装', 'admin/addons/install', '0', '', '0', '', 'Addons install');
INSERT INTO `kite_auth_rule` VALUES ('44', '42', 'admin', '插件卸载', 'admin/addons/uninstall', '0', '', '0', '', 'Addons uninstall');
INSERT INTO `kite_auth_rule` VALUES ('45', '42', 'admin', '插件启用', 'admin/addons/enable', '0', '', '0', '', 'Addons enable');
INSERT INTO `kite_auth_rule` VALUES ('46', '42', 'admin', '插件禁用', 'admin/addons/disable', '0', '', '0', '', 'Addons disable');
INSERT INTO `kite_auth_rule` VALUES ('47', '42', 'admin', '插件配置', 'admin/addons/config', '0', '', '0', '', 'Addons config');
INSERT INTO `kite_auth_rule` VALUES ('48', '5', 'admin', '钩子管理', 'admin/hooks/index', '1', 'fa fa-gg-circle', '0', '', 'Hooks');
INSERT INTO `kite_auth_rule` VALUES ('49', '48', 'admin', '钩子添加', 'admin/hooks/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('50', '48', 'admin', '钩子编辑', 'admin/hooks/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('51', '5', 'admin', '订单', 'admin/order/index', '1', 'fa fa-circle-o', '10', '', 'Order');
INSERT INTO `kite_auth_rule` VALUES ('52', '86', 'admin', '编辑评论', 'admin/comments/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('53', '51', 'admin', '订单详情', 'admin/order/edit', '0', 'fa fa-circle-o', '0', '', 'Detail');
INSERT INTO `kite_auth_rule` VALUES ('54', '51', 'admin', '删除', 'admin/order/remove', '0', 'fa fa-circle-o', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('56', '5', 'admin', '友情链接', 'admin/link/index', '1', 'fa fa-link', '3', '', 'Link');
INSERT INTO `kite_auth_rule` VALUES ('57', '56', 'admin', '创建友情链接', 'admin/link/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('58', '56', 'admin', '编辑友情链接', 'admin/link/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('59', '56', 'admin', '删除友情链接', 'admin/link/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('60', '56', 'admin', '友情链接类别', 'admin/link/category', '0', '', '0', '', 'Link category');
INSERT INTO `kite_auth_rule` VALUES ('61', '56', 'admin', '删除友情链接分类', 'admin/link/removeCategory', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('62', '56', 'admin', '批量操作友情链接分类', 'admin/link/handleCategory', '0', '', '0', '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES ('63', '1', 'admin', '模板管理', 'admin/template/filelist', '1', 'fa fa-file-code-o', '8', '', 'Template file');
INSERT INTO `kite_auth_rule` VALUES ('64', '63', 'admin', '模板修改', 'admin/template/fileedit', '0', '', '0', '', 'Template file edit');
INSERT INTO `kite_auth_rule` VALUES ('65', '5', 'admin', '幻灯片', 'admin/slider/index', '1', 'fa fa-file-image-o', '2', '', 'Slider');
INSERT INTO `kite_auth_rule` VALUES ('66', '65', 'admin', '创建幻灯片', 'admin/slider/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('67', '65', 'admin', '编辑幻灯片', 'admin/slider/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('68', '65', 'admin', '删除幻灯片', 'admin/slider/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('69', '65', 'admin', '幻灯片类别', 'admin/slider/category', '0', '', '0', '', 'Slider category');
INSERT INTO `kite_auth_rule` VALUES ('70', '65', 'admin', '删除幻灯片分类', 'admin/slider/removeCategory', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('71', '65', 'admin', '批量操作幻灯片分类', 'admin/slider/handleCategory', '0', '', '0', '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES ('72', '1', 'admin', '参数配置', 'admin/site/config', '1', 'fa fa-wrench', '5', '', 'Site config');
INSERT INTO `kite_auth_rule` VALUES ('75', '5', 'admin', '内容区块', 'admin/block/index', '1', 'fa fa-code', '4', '', 'Block');
INSERT INTO `kite_auth_rule` VALUES ('76', '75', 'admin', '创建区块', 'admin/block/create', '0', '', '0', '', 'Create');
INSERT INTO `kite_auth_rule` VALUES ('77', '75', 'admin', '编辑区块', 'admin/block/edit', '0', '', '0', '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES ('78', '75', 'admin', '删除区块', 'admin/block/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('79', '75', 'admin', '区块类别', 'admin/block/category', '0', '', '0', '', 'Block category');
INSERT INTO `kite_auth_rule` VALUES ('80', '75', 'admin', '删除区块分类', 'admin/block/removeCategory', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('81', '75', 'admin', '批量操作区块分类', 'admin/block/handleCategory', '0', '', '0', '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES ('82', '48', 'admin', '钩子删除', 'admin/hooks/delete', '0', 'fa fa-circle-o', '0', '', 'Delete');
INSERT INTO `kite_auth_rule` VALUES ('86', '5', 'admin', '评论管理', 'admin/comments/index', '1', 'fa fa-comments', '1', '', 'Comments');
INSERT INTO `kite_auth_rule` VALUES ('87', '86', 'admin', '删除评论', 'admin/comments/remove', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('88', '86', 'admin', '批量操作评论', 'admin/comments/handle', '0', '', '0', '', 'Handle comments');
INSERT INTO `kite_auth_rule` VALUES ('89', '1', 'admin', '系统日志', 'admin/log/index', '1', 'fa fa-history', '6', '', 'Log');
INSERT INTO `kite_auth_rule` VALUES ('90', '1', 'admin', '编辑菜单', 'admin/navigation/index', '1', 'fa fa-navicon', '7', '', 'Navigation');
INSERT INTO `kite_auth_rule` VALUES ('91', '90', 'admin', '菜单管理', 'admin/navigation/category', '0', '', '0', '', 'Navigation category');
INSERT INTO `kite_auth_rule` VALUES ('92', '90', 'admin', '删除菜单', 'admin/navigation/removeCategory', '0', '', '0', '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES ('93', '90', 'admin', '批量操作菜单', 'admin/navigation/handleCategory', '0', '', '0', '', 'Handle');

-- ----------------------------
-- Table structure for kite_auth_user
-- ----------------------------
DROP TABLE IF EXISTS `kite_auth_user`;
CREATE TABLE `kite_auth_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `role_ids` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户所属角色组',
  `username` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '管理员密码',
  `phone` char(11) COLLATE utf8_bin DEFAULT '' COMMENT '手机号',
  `email` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '电子邮箱',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态 0 正常 1禁用',
  `score` int(11) DEFAULT NULL COMMENT '积分',
  `avatar` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '头像',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `user_id` (`uid`) USING BTREE,
  UNIQUE KEY `user_name` (`username`) USING BTREE,
  KEY `created` (`create_at`) USING BTREE,
  KEY `phone` (`phone`) USING BTREE,
  KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_auth_user
-- ----------------------------
INSERT INTO `kite_auth_user` VALUES ('1', '1,2,3', 'admin', '$2y$10$YTKerqRFBLm8aiRvFgideePFmYVoM/vPGV5FBubtbeO3Yan6S9Lty', '18780221108', 'kite@kitesky.com', '0', '454', '/upload/20190627/aba796d04ef17b1862880b988a5b47d8.png', '1561568316', '1561989230');

-- ----------------------------
-- Table structure for kite_block
-- ----------------------------
DROP TABLE IF EXISTS `kite_block`;
CREATE TABLE `kite_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) DEFAULT 0 COMMENT '友情链接分类ID',
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站名称',
  `variable` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '区块变量标识',
  `content` text COLLATE utf8_bin DEFAULT NULL COMMENT '内容',
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态： 0隐藏  1 显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_block
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_category
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_category`;
CREATE TABLE `kite_document_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '上级父ID',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `model_id` int(11) NOT NULL COMMENT '模型ID',
  `sort` int(11) DEFAULT 0 COMMENT '权重排序',
  `title` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档标题',
  `keywords` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档关键词',
  `description` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '描述',
  `content` text COLLATE utf8_bin DEFAULT NULL COMMENT '文档内容',
  `list_rows` int(11) NOT NULL DEFAULT 10 COMMENT '列表显示条数',
  `list_tpl` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '栏目模板',
  `detail_tpl` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '内容模板',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 隐藏 1 显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_category
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_comments
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_comments`;
CREATE TABLE `kite_document_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '评论上级ID',
  `uid` int(11) NOT NULL COMMENT '评论人mid ',
  `document_id` int(11) NOT NULL COMMENT '评论文档ID',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '评论内容',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 隐藏 1 显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_comments
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_comments_like
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_comments_like`;
CREATE TABLE `kite_document_comments_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comments_id` int(11) NOT NULL COMMENT '文档ID',
  `like` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[顶]',
  `unlike` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[踩]',
  `ip` varchar(32) COLLATE utf8_bin DEFAULT NULL COMMENT '客户端IP',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_comments_like
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_content
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_content`;
CREATE TABLE `kite_document_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT 0 COMMENT '文档分类ID',
  `site_id` int(11) NOT NULL COMMENT '内容归属站点',
  `uid` int(11) DEFAULT NULL COMMENT '后台管理员发布者 UID',
  `title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档标题',
  `keywords` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档关键词',
  `description` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '描述',
  `content` longtext COLLATE utf8_bin DEFAULT NULL COMMENT '文档内容',
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '文档封面',
  `attach` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '文件',
  `album` text COLLATE utf8_bin DEFAULT NULL COMMENT '图片集合',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 待审 1通过',
  `image_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '图片类型标识',
  `video_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '视频类型标识',
  `attach_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '附件类型标识',
  `hot_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '热门标识',
  `recommend_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '推荐标识',
  `focus_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '焦点标识',
  `top_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '置顶标识',
  `pv` int(11) NOT NULL DEFAULT 0 COMMENT '访问次数',
  `price` decimal(8,2) DEFAULT 0.00 COMMENT '售价',
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '阅读权限限 0:游客组',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `title` (`title`) USING BTREE,
  KEY `site_id` (`site_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_content
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_content_extra
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_content_extra`;
CREATE TABLE `kite_document_content_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文档内容自定义ID',
  `document_id` int(11) NOT NULL COMMENT '文档ID',
  `type` char(20) COLLATE utf8_bin NOT NULL COMMENT '字段内容类型',
  `name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段名称',
  `variable` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段变量',
  `key` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '字段选项原始值',
  `value` text COLLATE utf8_bin DEFAULT NULL COMMENT '字段值',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`) USING BTREE,
  KEY `variable` (`variable`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_content_extra
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_content_like
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_content_like`;
CREATE TABLE `kite_document_content_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL COMMENT '文档ID',
  `like` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[顶]',
  `unlike` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[踩]',
  `ip` varchar(32) COLLATE utf8_bin DEFAULT NULL COMMENT '客户端IP',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_content_like
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_field
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_field`;
CREATE TABLE `kite_document_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '字段归类',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段名称',
  `variable` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '字段列名',
  `type` char(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段类型',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '权重排序',
  `is_require` tinyint(1) DEFAULT 0 COMMENT '0 正常 1必填',
  `is_filter` tinyint(1) DEFAULT NULL COMMENT '0正常 1筛选条件',
  `option` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段内容选项',
  `description` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '描述',
  `regular` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '正则表达式',
  `msg` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '验证失败提示语',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_field
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_model
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_model`;
CREATE TABLE `kite_document_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `name` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '模型名称',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序 越小越靠前',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_model
-- ----------------------------

-- ----------------------------
-- Table structure for kite_document_model_field
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_model_field`;
CREATE TABLE `kite_document_model_field` (
  `model_id` int(11) NOT NULL COMMENT '模型ID',
  `field_id` int(11) NOT NULL COMMENT '字段ID',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_document_model_field
-- ----------------------------

-- ----------------------------
-- Table structure for kite_hooks
-- ----------------------------
DROP TABLE IF EXISTS `kite_hooks`;
CREATE TABLE `kite_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text DEFAULT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '类型',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kite_hooks
-- ----------------------------
INSERT INTO `kite_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '', '1', '1561561552', '1561561552');
INSERT INTO `kite_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '', '1', '1561561552', '1561561552');
INSERT INTO `kite_hooks` VALUES ('3', 'AdminIndex', '首页小格子个性化显示', '2', 'demo', '1', '1561561552', '1561561552');

-- ----------------------------
-- Table structure for kite_language
-- ----------------------------
DROP TABLE IF EXISTS `kite_language`;
CREATE TABLE `kite_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '语言名称',
  `icon` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '权重排序 越大越靠后',
  `designation` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_language
-- ----------------------------
INSERT INTO `kite_language` VALUES ('1', 'zh-cn', null, '1', '简体中文(中国) ');
INSERT INTO `kite_language` VALUES ('2', 'en-us', null, '2', '英语(美国) ');

-- ----------------------------
-- Table structure for kite_link
-- ----------------------------
DROP TABLE IF EXISTS `kite_link`;
CREATE TABLE `kite_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) DEFAULT 0 COMMENT '友情链接分类ID',
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站地址',
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'logo地址',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态： 0隐藏  1 显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_link
-- ----------------------------

-- ----------------------------
-- Table structure for kite_log
-- ----------------------------
DROP TABLE IF EXISTS `kite_log`;
CREATE TABLE `kite_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '操作类型',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '操作内容',
  `ip` char(32) COLLATE utf8_bin DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_log
-- ----------------------------

-- ----------------------------
-- Table structure for kite_message
-- ----------------------------
DROP TABLE IF EXISTS `kite_message`;
CREATE TABLE `kite_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '信息编号',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `type` tinyint(1) NOT NULL COMMENT '信息类型 1 系统消息 2 短信 3 邮件',
  `subject` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '信息标题',
  `body` text COLLATE utf8_bin DEFAULT NULL COMMENT '信息内容',
  `code` varchar(32) COLLATE utf8_bin DEFAULT NULL COMMENT '动态码',
  `mid` int(11) DEFAULT NULL COMMENT '系统消息接收人mid',
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '邮件消息接收人email',
  `phone` char(11) COLLATE utf8_bin DEFAULT NULL COMMENT '短信接收人手机号码',
  `send_status` varchar(255) COLLATE utf8_bin NOT NULL COMMENT ' success 发送成功  fail 发送失败',
  `send_error` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '错误代码',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 正常 1 失效',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_message
-- ----------------------------

-- ----------------------------
-- Table structure for kite_navigation
-- ----------------------------
DROP TABLE IF EXISTS `kite_navigation`;
CREATE TABLE `kite_navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT 0 COMMENT '导航分类ID',
  `cat_id` int(11) NOT NULL COMMENT '文章栏目分类ID',
  `pid` int(11) NOT NULL DEFAULT 0,
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '菜单名称',
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '菜单URL',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 栏目 2自定义URL',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_navigation
-- ----------------------------

-- ----------------------------
-- Table structure for kite_order
-- ----------------------------
DROP TABLE IF EXISTS `kite_order`;
CREATE TABLE `kite_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `uid` int(11) NOT NULL COMMENT '购买者UID',
  `document_id` int(11) NOT NULL COMMENT '商品ID',
  `trade_no` char(32) DEFAULT '' COMMENT '支付交易单号',
  `out_trade_no` char(32) DEFAULT '' COMMENT '订单编号',
  `total_amount` varchar(8) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `payment_type` tinyint(1) DEFAULT 0 COMMENT '0 未支付 1支付宝 2微信',
  `status` int(1) DEFAULT 0 COMMENT '0 待付款 1 已经付款 2退款 ',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kite_order
-- ----------------------------

-- ----------------------------
-- Table structure for kite_score
-- ----------------------------
DROP TABLE IF EXISTS `kite_score`;
CREATE TABLE `kite_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '积分记录编号',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `sum` int(11) NOT NULL COMMENT '剩余总数',
  `score` int(11) NOT NULL COMMENT '积分数量',
  `source` varchar(255) CHARACTER SET latin1 DEFAULT NULL COMMENT '获取原因',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_score
-- ----------------------------

-- ----------------------------
-- Table structure for kite_site
-- ----------------------------
DROP TABLE IF EXISTS `kite_site`;
CREATE TABLE `kite_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '网站SID',
  `name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '站点名称',
  `logo` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT 'LOGO',
  `domain` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点绑定域名',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序 越小越靠前',
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点标题',
  `keywords` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点关键词',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点描述',
  `theme` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '模板名称',
  `copyright` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '版权信息',
  `icp` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ICP备案号',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 正常 1关闭',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `site_name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_site
-- ----------------------------
INSERT INTO `kite_site` VALUES ('1', '默认站点', '/upload/20190702/61126f8e5831cadc75aacf845122c8a8.png', 'http://f.19981.com', '0', '默认站点', '默认站点', '默认站点', 'compact', 'Copyright © 2019 19981.com. All Right Reserved.', '蜀ICP备12004586号-2', '0', '1541387367', '1562121505');

-- ----------------------------
-- Table structure for kite_site_config
-- ----------------------------
DROP TABLE IF EXISTS `kite_site_config`;
CREATE TABLE `kite_site_config` (
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `k` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '键名',
  `v` text COLLATE utf8_bin DEFAULT NULL COMMENT '键值',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  KEY `key` (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_site_config
-- ----------------------------
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_publish_document', 0x30, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'register_score', 0x313030, '1529499198', '1529499198');
INSERT INTO `kite_site_config` VALUES ('1', 'login_score', 0x3130, '1529499387', '1529499387');
INSERT INTO `kite_site_config` VALUES ('1', 'publish_score', 0x3130, '1529499540', '1529499540');
INSERT INTO `kite_site_config` VALUES ('1', 'comment_score', 0x2D35, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_type', 0x6C6F63616C, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_size', 0x6A70672C706E672C676966, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_ext', 0x6A70672C706E672C676966, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_path', 0x75706C6F6164, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'alioss_key', 0x34483543346A5162784241736277796531, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'alioss_secret', 0x5535426539564C5A437079386F436F377354515343713830367377714756, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'alioss_endpoint', 0x6F73732D636E2D7368656E7A68656E2E616C6979756E63732E636F6D, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'alioss_bucket', 0x6B697465736B79, '1529499606', '1529499606');
INSERT INTO `kite_site_config` VALUES ('1', 'qiniu_ak', 0x3956577A66316A6953336745414C42695F587477454C4861487A484A49654358453557344B744A74, '1530071701', '1530071701');
INSERT INTO `kite_site_config` VALUES ('1', 'qiniu_sk', 0x54474E6432317877662D794847576E3346774E3337666B5257704F7A7A4D685843356A4566677238, '1530071701', '1530071701');
INSERT INTO `kite_site_config` VALUES ('1', 'qiniu_bucket', 0x6B697465736B79, '1530071701', '1530071701');
INSERT INTO `kite_site_config` VALUES ('1', 'qiniu_domain', 0x687474703A2F2F6F6E7872386D7438792E626B742E636C6F7564646E2E636F6D, '1530071701', '1530071701');
INSERT INTO `kite_site_config` VALUES ('1', 'link_category', 0x5B7B226E616D65223A22E69687E5AD97E993BEE68EA5222C22736F7274223A2231222C226964223A317D5D, '1531141510', '1531141510');
INSERT INTO `kite_site_config` VALUES ('1', 'slider_category', 0x5B5D, '1531147967', '1531147967');
INSERT INTO `kite_site_config` VALUES ('1', 'field_category', 0x5B7B226E616D65223A22E6A8A1E69DBFE7B1BB222C22736F7274223A2231222C226964223A317D5D, '1531147967', '1531147967');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_useZh', 0x30, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_useImgBg', 0x30, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_fontSize', 0x3234, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_imageH', 0x3430, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_imageW', 0x323030, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_length', 0x34, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_member_register', 0x30, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_member_login', 0x30, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_publish_comment', 0x30, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_publish_feedback', 0x30, '1531213657', '1531213657');
INSERT INTO `kite_site_config` VALUES ('1', 'water_logo', 0x2F7075626C69632F7374617469632F61646D696E2F646973742F696D672F6E6F7069632E706E67, '1531213845', '1531213845');
INSERT INTO `kite_site_config` VALUES ('1', 'water_position', 0x39, '1531213845', '1531213845');
INSERT INTO `kite_site_config` VALUES ('1', 'water_quality', 0x3830, '1531213845', '1531213845');
INSERT INTO `kite_site_config` VALUES ('1', 'water_status', 0x30, '1531213845', '1531213845');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_useCurve', 0x30, '1531217269', '1531217269');
INSERT INTO `kite_site_config` VALUES ('1', 'captcha_useNoise', 0x30, '1531217269', '1531217269');
INSERT INTO `kite_site_config` VALUES ('1', 'sms_type', 0x6479736D73, '1531371550', '1531371550');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_access_key', '', '1531371550', '1531371550');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_access_key_secret', '', '1531371550', '1531371550');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_sign_name', '', '1531371550', '1531371550');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_template_code', '', '1531371550', '1531371550');
INSERT INTO `kite_site_config` VALUES ('1', 'email_host', 0x736D74702E3136332E636F6D, '1531378668', '1531378668');
INSERT INTO `kite_site_config` VALUES ('1', 'email_port', 0x343635313332, '1531378668', '1531378668');
INSERT INTO `kite_site_config` VALUES ('1', 'email_username', 0x6E73737368, '1531378668', '1531378668');
INSERT INTO `kite_site_config` VALUES ('1', 'email_password', 0x77616E677A68656E67, '1531378668', '1531378668');
INSERT INTO `kite_site_config` VALUES ('1', 'member_register', 0x30, '1531378916', '1531378916');
INSERT INTO `kite_site_config` VALUES ('1', 'email_from_email', 0x6E73737368403136332E636F6D, '1531383066', '1531383066');
INSERT INTO `kite_site_config` VALUES ('1', 'email_from_name', 0x4B697465434D53, '1531383066', '1531383066');
INSERT INTO `kite_site_config` VALUES ('1', 'block_category', 0x5B5D, '1531981998', '1531981998');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_image_ext', 0x6A70672C706E672C676966, '1532327020', '1532327020');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_image_size', 0x32303438, '1532327020', '1532327020');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_video_ext', 0x726D2C726D76622C776D762C3367702C6D70342C6D6F762C6176692C666C76, '1532327020', '1532327020');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_video_size', 0x3130323430, '1532327020', '1532327020');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_attach_ext', 0x646F632C786C732C7261722C7A69702C377A, '1532327020', '1532327020');
INSERT INTO `kite_site_config` VALUES ('1', 'upload_attach_size', 0x3130323430, '1532327020', '1532327020');
INSERT INTO `kite_site_config` VALUES ('1', 'navigation_category', 0x5B7B226E616D65223A22E9A1B6E983A8E5AFBCE888AA222C22736F7274223A2231222C226964223A317D2C7B226E616D65223A22E5BA95E983A8E5AFBCE888AA222C22736F7274223A2232222C226964223A327D5D, '1532675827', '1532675827');
INSERT INTO `kite_site_config` VALUES ('1', 'email_code_template', 0xE5B08AE695ACE79A84E4BC9AE59198247B757365726E616D657D20EFBC8CE682A8E69CACE6ACA1E79A84E9AA8CE8AF81E7A081E4B8BAEFBC9A247B636F64657D20EFBC8CE9AA8CE8AF81E7A081E59CA835E58886E9929FE58685E69C89E69588E38082, '1532856848', '1532856848');
INSERT INTO `kite_site_config` VALUES ('1', 'email_register_template', 0xE5B08AE695ACE79A84E4BC9AE59198247B757365726E616D657D20EFBC8CE682A8E5B7B2E7BB8FE68890E58A9FE6B3A8E5868CEFBC8CE8AFB7E8B0A8E8AEB0E682A8E79A84E794A8E688B7E5908DE58F8AE5AF86E7A081E38082, '1532856848', '1532856848');
INSERT INTO `kite_site_config` VALUES ('1', 'send_email_register', 0x30, '1532856848', '1532856848');
INSERT INTO `kite_site_config` VALUES ('2', 'field_category', 0x5B7B226E616D65223A22636D73222C22736F7274223A2231222C226964223A317D2C7B226E616D65223A22E4BAA7E59381222C22736F7274223A2232222C226964223A327D5D, '1541487138', '1541487138');
INSERT INTO `kite_site_config` VALUES ('2', 'slider_category', null, '1548224151', '1548224151');
INSERT INTO `kite_site_config` VALUES ('2', 'link_category', 0x5B7B226E616D65223A22E5BA95E983A8E993BEE68EA5222C22736F7274223A2231222C226964223A317D5D, '1548224152', '1548224152');
INSERT INTO `kite_site_config` VALUES ('2', 'block_category', 0x5B7B226E616D65223A2254455354222C22736F7274223A2231222C226964223A317D5D, '1548224152', '1548224152');
INSERT INTO `kite_site_config` VALUES ('2', 'register_score', 0x313030, '1548224155', '1548224155');
INSERT INTO `kite_site_config` VALUES ('2', 'login_score', 0x31, '1548224155', '1548224155');
INSERT INTO `kite_site_config` VALUES ('2', 'publish_score', 0x3130, '1548224155', '1548224155');
INSERT INTO `kite_site_config` VALUES ('2', 'comment_score', 0x35, '1548224156', '1548224156');
INSERT INTO `kite_site_config` VALUES ('2', 'email_host', 0x736D74702E3136332E636F6D, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'email_port', 0x343635, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'email_username', 0x6B697465333635, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'email_password', 0x77616E677A68656E67, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'email_from_email', 0x6B697465406B697465736B792E636F6D, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'email_from_name', 0x4B697465434D53, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'email_code_template', 0xE5B08AE695ACE79A84E4BC9AE59198247B757365726E616D657D20EFBC8CE682A8E69CACE6ACA1E79A84E9AA8CE8AF81E7A081E4B8BAEFBC9A247B636F64657D20EFBC8CE9AA8CE8AF81E7A081E59CA835E58886E9929FE58685E69C89E69588E38082, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'email_register_template', 0xE5B08AE695ACE79A84E4BC9AE59198247B757365726E616D657D20EFBC8CE682A8E5B7B2E7BB8FE68890E58A9FE6B3A8E5868CEFBC8CE8AFB7E8B0A8E8AEB0E682A8E79A84E794A8E688B7E5908DE58F8AE5AF86E7A081E38082, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'send_email_register', 0x30, '1548224167', '1548224167');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_type', 0x6C6F63616C, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_image_ext', 0x6A70672C706E672C676966, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_image_size', 0x32303430303030, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_video_ext', 0x726D2C726D76622C776D762C3367702C6D70342C6D6F762C6176692C666C76, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_video_size', 0x32303430303030, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_attach_ext', 0x646F632C786C732C7261722C7A6970, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_attach_size', 0x32303430303030, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'upload_path', 0x75706C6F6164, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'alioss_key', 0x34483543346A51627842417362777965, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'alioss_secret', 0x5535426539564C5A437079386F436F377354515343713830367377714756, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'alioss_endpoint', 0x6F73732D636E2D7368656E7A68656E2E616C6979756E63732E636F6D, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'alioss_bucket', 0x6B697465736B79, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'qiniu_ak', 0x3956577A66316A6953336745414C42695F587477454C4861487A484A49654358453557344B744A74, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'qiniu_sk', 0x794847576E3346774E3337666B5257704F7A7A4D685843356A4566677238, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'qiniu_bucket', 0x6B697465736B79, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'qiniu_domain', 0x687474703A2F2F6F6E7872386D7438792E626B742E636C6F7564646E2E636F6D, '1548224168', '1548224168');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_useZh', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_useImgBg', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_fontSize', 0x3134, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_imageH', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_imageW', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_length', 0x36, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_useCurve', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_useNoise', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_member_register', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_member_login', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_publish_document', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_publish_comment', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'captcha_publish_feedback', 0x30, '1548224169', '1548224169');
INSERT INTO `kite_site_config` VALUES ('2', 'navigation_category', 0x5B7B226E616D65223A22E9A1B6E983A8E5AFBCE888AA222C22736F7274223A22222C226964223A317D5D, '1548224170', '1548224170');
INSERT INTO `kite_site_config` VALUES ('4', 'field_category', null, '1551153424', '1551153424');
INSERT INTO `kite_site_config` VALUES ('4', 'link_category', null, '1551153427', '1551153427');
INSERT INTO `kite_site_config` VALUES ('4', 'slider_category', null, '1551153466', '1551153466');
INSERT INTO `kite_site_config` VALUES ('4', 'block_category', null, '1551153468', '1551153468');
INSERT INTO `kite_site_config` VALUES ('4', 'navigation_category', null, '1551153507', '1551153507');
INSERT INTO `kite_site_config` VALUES ('3', 'field_category', null, '1551343953', '1551343953');
INSERT INTO `kite_site_config` VALUES ('3', 'navigation_category', null, '1553520030', '1553520030');
INSERT INTO `kite_site_config` VALUES ('2', 'sms_type', 0x6479736D73, '1553582312', '1553582312');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_access_key', 0x4163636573734B6579204944, '1553582312', '1553582312');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_access_key_secret', 0x416363657373204B657920536563726574, '1553582312', '1553582312');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_sign_name', 0x31393938312E636F6D, '1553582312', '1553582312');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_template_code', 0x534D535F31323334, '1553582312', '1553582312');
INSERT INTO `kite_site_config` VALUES ('2', 'water_logo', 0x2F7075626C69632F7374617469632F61646D696E2F646973742F696D672F6E6F7069632E706E67, '1553582314', '1553582314');
INSERT INTO `kite_site_config` VALUES ('2', 'water_position', 0x39, '1553582315', '1553582315');
INSERT INTO `kite_site_config` VALUES ('2', 'water_quality', 0x3830, '1553582315', '1553582315');
INSERT INTO `kite_site_config` VALUES ('2', 'water_status', 0x30, '1553582315', '1553582315');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_appid', 0x32303136303332313031323330343937, '1561704850', '1561704850');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_private_key', 0x4D4949456F77494241414B434151454170724A774436677164554241786F366578764D7556416968683279696E6B696B666F3831575546517455663349707842516C6A36324D6D71364350646D72626A49666256435164732B676E564455326A777961314C71784453615A446B64344D384B4931616C4E382B595265734D75344B446D6B6941705633336C4853324F7A467365464A322F6E62644B664B56535676687A3037742B6D612F6170797354584B77416A384165716E782F456C4C67746450454272457471684E544D7A56636259314D413466357173674479492F56486A336C7236434A6532772F4F485855776B7A7573656A776F33327264526F3734766D496152706E3756665773547546663570347A73587842396E6B4676764E574B387142724A5653753942496536585875656B62316657794431593032394259556B6B4131534276327168683770544F306866746175706F71776A695558624154424E684B51494441514142416F494241514346745153726D6C434D4163437A51736B457656514174586553333346456B7248576A646E56774D305379796D51696C4C522B2F736733676D4738425731486C4C7245456871574A6C787157644A7032666568584B3467425873776A3761686F684D6A313957374B61476F55557566416B33777079565065304A4E6763596B6C793476637178436C4A51526176436841556B47366665336D646E6D38387652466E754E4D75656F52656254353163504150346F6E3378364C5874706842514A6F704B79366F43496B69456C4476746F494D5368676A4B347A2B443267505849706E30614C4A4B3934335947324664506441422B792F7573326F7654736F6E78473256384B7933396A345134454F7032616E33742F5045567369744B51706E323262705265514A6D524C36522B6F55684D682B35526B464F42426474704754505746566A4F6B37524662733548747638464E422B637742416F4742414E54554466735759356E4E765648455A6966476A6F4C694C41487931357A6D597245483932622F7438366B4F5A6A4E744C50797A456D6A696D766F3841503031524C6D524976767564687A7A797A4E39676D6770394A334E41464C4D6849754854634A7671342B6F64677A6552614272373476745765344F4D46567839616F7532694674394C696437394547624A752F76342B437133586F34444444524F784E4158522B334E3161585370416F4742414D694331584E736C78715A736635374E64326A6B447538465968326D556E35766A4273374861774A393465666A697A5861705363674365325034666A68626D397A504E6E75313546304548743669596364484D75337951567564595A423141647869656239787A76484C6A6C484E57416C51357A65705869757167442F50635A374466566C53316D657943787341656E6E573058316B684A6F4743456838633433482B6B78574C47646942416F4741514A44636450494434574D6A4C6930772B4A7771473962566C766D2F4936425A44472F6F5246304C764372694E6C4D686F50336C722B6C6E55766C6C3579316466744251743074517A44504742456576667067346B59634D52654130486F50533553475673584C6130715936382F4D41422B69645667767A572B50554C6E45642B63576E554E6A586A7A5476767377686D367669765836503462354B7431437041614D456237434D2F3545436759424E6841627738484961486F4470574D696950724672356E4B4D70777A7278466A3662364761344D3849313945454B704E7A5852776C6B554E694F75433769643758634136597A38396C6E49347235344E5A4545554C4375494E33655957534F3342337235774132342F48437776796E687342307A4C34377759714869435668726744666461474472426247315A7148797146476B6F452B444841486E772F5549517439493036656D3841514B42674649384D636356456B773655702B677738716A2B594A6F306D78354D32674B543742346F4E4F39564D562F43557041784732326B4B2B453371736D5452444B41632B4F374E455266375858774A5241686C35344F4A634E63697074642B746D3835453077654D62352F51446B7A4E7A536A764D6151534941373846706970553238716B73555137634D4A3235317677546472577365742F33355661367333656B364230385A466871775453, '1561704850', '1561704850');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_public_key', 0x4D494942496A414E42676B71686B6947397730424151454641414F43415138414D49494343674B43415145416A7144333234747062714469706C7071316B62377242374B7352465A4555314B6A7650746A6851536A34427964527955674F725655705331584755564E4A665A2F4A65315A5A676C6747456579337776746E55724E49587A733751576A787134757067777744485846784756376E75726E4173744667597372495A4179736B446D6655434D674C686842694961435A2B4C724A592F545272704D68704B732B414E456F7567317A52706E6B6374474479707A4A4F4D767A43597454704C5843667130354157323976704E384A2F5573384A6B43435531515930736E487542544A2B57665548322B544A58686C383764774669665979766B4B54714F65446369472B6A4248463965333071306D77663667646C75447130346A6D2B51444E553642496867566763506D423937586F774E657677713534614E506C4C6B666E45396B6B62653248654D4D79543457747A4E32793853495977494441514142, '1561704850', '1561704850');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_notify_url', 0x687474703A2F2F6465762E6B697465736B792E636F6D2F6D656D6265722F6F726465722F616C694E6F74696679, '1561704850', '1561704850');
INSERT INTO `kite_site_config` VALUES ('1', 'ali_return_url', 0x687474703A2F2F6465762E6B697465736B792E636F6D2F6D656D6265722F6F726465722F72657475726E, '1561704850', '1561704850');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_appid', '', '1562046555', '1562046555');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_private_key', '', '1562046555', '1562046555');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_public_key', '', '1562046555', '1562046555');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_notify_url', '', '1562046555', '1562046555');
INSERT INTO `kite_site_config` VALUES ('2', 'ali_return_url', '', '1562046555', '1562046555');
INSERT INTO `kite_site_config` VALUES ('2', 'member_allow_register', 0x31, '1562063662', '1562063662');
INSERT INTO `kite_site_config` VALUES ('2', 'member_allow_comment', 0x31, '1562063662', '1562063662');
INSERT INTO `kite_site_config` VALUES ('2', 'list_rows', 0x3135, '1562065361', '1562065361');
INSERT INTO `kite_site_config` VALUES ('2', 'list_row', 0x3135, '1562067176', '1562067176');
INSERT INTO `kite_site_config` VALUES ('1', 'member_allow_register', 0x31, '1562122709', '1562122709');
INSERT INTO `kite_site_config` VALUES ('1', 'member_allow_comment', 0x31, '1562122709', '1562122709');
INSERT INTO `kite_site_config` VALUES ('1', 'list_rows', 0x3135, '1562122709', '1562122709');

-- ----------------------------
-- Table structure for kite_site_file
-- ----------------------------
DROP TABLE IF EXISTS `kite_site_file`;
CREATE TABLE `kite_site_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `upload_type` char(20) COLLATE utf8_bin NOT NULL COMMENT '上传方式',
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '图片原始名称',
  `name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '图片上传后生成名字',
  `ext` char(20) COLLATE utf8_bin NOT NULL COMMENT '图片后缀',
  `url` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '图片URL',
  `thumb` text COLLATE utf8_bin DEFAULT NULL COMMENT '本地生成缩略图记录 多个用逗号隔开。方便以后清理',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 上传未被引用; 1 上传后被引用',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_site_file
-- ----------------------------

-- ----------------------------
-- Table structure for kite_slider
-- ----------------------------
DROP TABLE IF EXISTS `kite_slider`;
CREATE TABLE `kite_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) DEFAULT 0 COMMENT '友情链接分类ID',
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站地址',
  `image` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'logo地址',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '描述内容',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态： 0隐藏  1 显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_slider
-- ----------------------------

-- ----------------------------
-- Table structure for kite_user_nav
-- ----------------------------
DROP TABLE IF EXISTS `kite_user_nav`;
CREATE TABLE `kite_user_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '规则名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `menu` tinyint(1) DEFAULT 0 COMMENT '是否为菜单0 否 1是',
  `icon` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'fa fa-circle-o' COMMENT '图标',
  `sort` int(11) DEFAULT 0 COMMENT '权重排序',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '备注说明',
  `lang_var` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  PRIMARY KEY (`id`),
  KEY `permission_url` (`url`) USING BTREE,
  KEY `lang_var` (`lang_var`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of kite_user_nav
-- ----------------------------
INSERT INTO `kite_user_nav` VALUES ('1', '0', '信息管理', '#', '1', 'fa fa-laptop', '1', '', 'My document');
INSERT INTO `kite_user_nav` VALUES ('2', '1', '发布信息', 'member/document/create', '1', 'fa fa-edit', '2', '', 'Publish document');
INSERT INTO `kite_user_nav` VALUES ('3', '1', '修改信息', 'member/document/edit', '0', 'fa fa-circle-o', '3', '', 'Edit document');
INSERT INTO `kite_user_nav` VALUES ('4', '1', '删除信息', 'member/document/remove', '0', 'fa fa-circle-o', '4', '', 'Remove document');
INSERT INTO `kite_user_nav` VALUES ('5', '0', '账户设置', '#', '1', 'fa fa-user', '5', '', 'Account setting');
INSERT INTO `kite_user_nav` VALUES ('6', '5', '个人资料', 'member/member/profile', '1', 'fa fa-circle-o text-red', '6', '', 'Member profile');
INSERT INTO `kite_user_nav` VALUES ('7', '5', '账户绑定', 'member/member/bind', '1', 'fa fa-circle-o text-yellow', '7', '', 'Member bind');
INSERT INTO `kite_user_nav` VALUES ('8', '5', '手机绑定', 'member/member/mobileBind', '0', 'fa fa-circle-o', '8', '', 'Mobile bind');
INSERT INTO `kite_user_nav` VALUES ('9', '5', '邮箱绑定', 'member/member/emailBind', '0', 'fa fa-circle-o', '9', '', 'Email bind');
INSERT INTO `kite_user_nav` VALUES ('10', '5', '密码修改', 'member/member/password', '1', 'fa fa-circle-o text-aqua', '10', '', 'Password update');
INSERT INTO `kite_user_nav` VALUES ('11', '1', '信息列表', 'member/document/index', '1', 'fa fa-book', '0', '', 'Document list');
INSERT INTO `kite_user_nav` VALUES ('12', '0', '会员中心', 'member/index/index', '0', 'fa fa-circle-o', '0', '', 'Member index');
INSERT INTO `kite_user_nav` VALUES ('13', '5', '手机解绑', 'member/member/mobileUnbind', '0', 'fa fa-circle-o', '0', '', 'Mobile unbind');
INSERT INTO `kite_user_nav` VALUES ('14', '5', '邮箱解绑', 'member/member/emailUnbind', '0', 'fa fa-circle-o', '0', '', 'Email unbind');
INSERT INTO `kite_user_nav` VALUES ('15', '5', '头像设置', 'member/member/avatar', '0', 'fa fa-circle-o', '0', '', 'Member avatar');
INSERT INTO `kite_user_nav` VALUES ('16', '0', '创建订单', 'member/order/create', '0', 'fa fa-circle-o', '0', '', 'Create');
INSERT INTO `kite_user_nav` VALUES ('17', '5', '我的订单', 'member/order/my', '1', 'fa fa-circle-o', '0', '', 'My order');
INSERT INTO `kite_user_nav` VALUES ('18', '0', '订单详情', 'member/order/detail', '0', 'fa fa-circle-o', '0', '', 'Detail');
