/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : fenlei

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-11-07 17:13:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for fel_auth_permission
-- ----------------------------
DROP TABLE IF EXISTS `fel_auth_permission`;
CREATE TABLE `fel_auth_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '规则名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `menu` tinyint(1) DEFAULT '0' COMMENT '是否为菜单0 否 1是',
  `icon` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'fa fa-circle-o' COMMENT '图标',
  `weighing` int(11) DEFAULT '0' COMMENT '权重排序',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '备注说明',
  `lang_var` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  PRIMARY KEY (`id`),
  KEY `permission_url` (`url`) USING BTREE,
  KEY `lang_var` (`lang_var`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_auth_permission
-- ----------------------------
INSERT INTO `fel_auth_permission` VALUES ('1', '0', '系统管理', '#', '1', 'fa fa-laptop', '6', '', 'System');
INSERT INTO `fel_auth_permission` VALUES ('2', '7', '管理员授权', 'admin/user/auth', '0', '', '3', '', 'Site authorization');
INSERT INTO `fel_auth_permission` VALUES ('3', '0', '信息管理', '#', '1', 'fa fa-file-word-o', '2', '', 'Information');
INSERT INTO `fel_auth_permission` VALUES ('4', '0', '会员管理', '#', '1', 'fa fa-user', '4', '', 'Member');
INSERT INTO `fel_auth_permission` VALUES ('5', '0', '功能扩展', '#', '1', 'fa fa-chrome', '3', '', 'Extended');
INSERT INTO `fel_auth_permission` VALUES ('6', '3', '模型管理', 'admin/documentModel/index', '1', 'fa fa-cube', '4', '', 'Document model');
INSERT INTO `fel_auth_permission` VALUES ('7', '1', '用户管理', 'admin/user/index', '1', 'fa fa-user-secret', '1', '', 'Administrator');
INSERT INTO `fel_auth_permission` VALUES ('8', '1', '角色管理', 'admin/role/index', '1', 'fa fa-users', '2', '', 'Role');
INSERT INTO `fel_auth_permission` VALUES ('9', '1', '站点管理', 'admin/site/index', '1', 'fa fa-globe', '4', '', 'Sites');
INSERT INTO `fel_auth_permission` VALUES ('10', '0', '站点首页', 'admin/index/index', '1', 'fa fa-dashboard ', '1', '', 'Control panel');
INSERT INTO `fel_auth_permission` VALUES ('11', '9', '创建站点', 'admin/site/create', '0', 'fa fa-circle-o', '0', '', 'Create site');
INSERT INTO `fel_auth_permission` VALUES ('12', '1', '权限配置', 'admin/permission/index', '1', 'fa fa-shield', '3', '', 'Permission');
INSERT INTO `fel_auth_permission` VALUES ('13', '12', '增加权限', 'admin/permission/create', '0', 'fa fa-circle-o', '0', '', 'Create permission');
INSERT INTO `fel_auth_permission` VALUES ('14', '12', '权限编辑', 'admin/permission/edit', '0', '', '0', '', 'Permission update');
INSERT INTO `fel_auth_permission` VALUES ('15', '8', '增加角色', 'admin/role/create', '0', '', '0', '', 'Create role');
INSERT INTO `fel_auth_permission` VALUES ('16', '8', '角色编辑', 'admin/role/edit', '0', '', '0', '', 'Role update');
INSERT INTO `fel_auth_permission` VALUES ('17', '8', '角色授权', 'admin/role/auth', '0', '', '0', '', 'Role authorization');
INSERT INTO `fel_auth_permission` VALUES ('18', '7', '创建管理员', 'admin/user/create', '0', '', '0', '', 'Create administrator');
INSERT INTO `fel_auth_permission` VALUES ('19', '7', '更新管理员', 'admin/user/edit', '0', '', '0', '', 'Update administrator');
INSERT INTO `fel_auth_permission` VALUES ('20', '7', '删除管理员', 'admin/user/remove', '0', '', '0', '', 'Delete administrator');
INSERT INTO `fel_auth_permission` VALUES ('21', '7', '管理员批量操作', 'admin/user/handle', '0', '', '0', '', 'Administrator batch operation');
INSERT INTO `fel_auth_permission` VALUES ('22', '9', '编辑站点', 'admin/site/edit', '0', '', '0', '', 'Update site');
INSERT INTO `fel_auth_permission` VALUES ('23', '9', '删除站点', 'admin/site/remove', '0', '', '0', '', 'Delete site');
INSERT INTO `fel_auth_permission` VALUES ('24', '3', '文档信息', 'admin/document/index', '1', 'fa fa-file-text-o', '1', '', 'Document');
INSERT INTO `fel_auth_permission` VALUES ('25', '3', '栏目分类', 'admin/category/index', '1', 'fa fa-list-ol', '2', '', 'Category');
INSERT INTO `fel_auth_permission` VALUES ('26', '6', '创建模型', 'admin/documentModel/create', '0', '', '0', '', 'Create document model');
INSERT INTO `fel_auth_permission` VALUES ('27', '6', '编辑模型', 'admin/documentModel/edit', '0', '', '0', '', 'Edit document model');
INSERT INTO `fel_auth_permission` VALUES ('28', '6', '删除模型', 'admin/documentModel/remove', '0', '', '0', '', 'Remove document model');
INSERT INTO `fel_auth_permission` VALUES ('29', '3', '字段管理', 'admin/documentField/index', '1', 'fa fa-cubes', '3', '', 'Document Field');
INSERT INTO `fel_auth_permission` VALUES ('30', '29', '创建字段', 'admin/documentField/create', '0', '', '0', '', 'Create document field');
INSERT INTO `fel_auth_permission` VALUES ('31', '29', '编辑字段', 'admin/documentField/edit', '0', '', '0', '', 'Edit document field');
INSERT INTO `fel_auth_permission` VALUES ('32', '29', '删除字段', 'admin/documentField/remove', '0', '', '0', '', 'Remove document field');
INSERT INTO `fel_auth_permission` VALUES ('33', '29', '字段类别', 'admin/documentField/category', '0', '', '0', '', 'Document field category');
INSERT INTO `fel_auth_permission` VALUES ('34', '29', '删除字段分类', 'admin/documentField/removeCategory', '0', '', '0', '', 'Remove document field category');
INSERT INTO `fel_auth_permission` VALUES ('35', '29', '批量操作字段分类', 'admin/documentField/handleCategory', '0', '', '0', '', 'Handle document field category');
INSERT INTO `fel_auth_permission` VALUES ('36', '24', '创建文档', 'admin/document/create', '0', '', '0', '', 'Create document');
INSERT INTO `fel_auth_permission` VALUES ('37', '24', '更新文档', 'admin/document/edit', '0', '', '0', '', 'Edit document');
INSERT INTO `fel_auth_permission` VALUES ('38', '24', '删除文档', 'admin/document/remove', '0', '', '0', '', 'Remove document');
INSERT INTO `fel_auth_permission` VALUES ('39', '25', '创建文档', 'admin/category/create', '0', '', '0', '', 'Create category');
INSERT INTO `fel_auth_permission` VALUES ('40', '25', '更新文档', 'admin/category/edit', '0', '', '0', '', 'Edit category');
INSERT INTO `fel_auth_permission` VALUES ('41', '25', '删除文档', 'admin/category/remove', '0', '', '0', '', 'Remove category');
INSERT INTO `fel_auth_permission` VALUES ('42', '4', '注册会员', 'admin/member/index', '1', 'fa fa-user', '1', '', 'Member');
INSERT INTO `fel_auth_permission` VALUES ('43', '42', '创建会员', 'admin/member/create', '0', '', '0', '', 'Create member');
INSERT INTO `fel_auth_permission` VALUES ('44', '42', '更新会员', 'admin/member/edit', '0', '', '0', '', 'Update member');
INSERT INTO `fel_auth_permission` VALUES ('45', '42', '删除会员', 'admin/member/remove', '0', '', '0', '', 'Remove member');
INSERT INTO `fel_auth_permission` VALUES ('46', '42', '会员批量操作', 'admin/member/handle', '0', '', '0', '', 'Member batch operation');
INSERT INTO `fel_auth_permission` VALUES ('47', '4', '会员组', 'admin/memberGroup/index', '1', 'fa fa-users', '1', '', 'Group');
INSERT INTO `fel_auth_permission` VALUES ('48', '47', '创建会员组', 'admin/memberGroup/create', '0', '', '0', '', 'Create group');
INSERT INTO `fel_auth_permission` VALUES ('49', '47', '更新会员组', 'admin/memberGroup/edit', '0', '', '0', '', 'Update group');
INSERT INTO `fel_auth_permission` VALUES ('50', '47', '删除会员组', 'admin/memberGroup/remove', '0', '', '0', '', 'Remove group');
INSERT INTO `fel_auth_permission` VALUES ('51', '4', '积分配置', 'admin/score/index', '1', 'fa fa-star', '3', '', 'Score config');
INSERT INTO `fel_auth_permission` VALUES ('52', '86', '编辑评论', 'admin/comments/edit', '0', '', '0', '', 'Edit comments');
INSERT INTO `fel_auth_permission` VALUES ('53', '72', '文件上传', 'admin/site/uploadFile', '1', 'fa fa-file-o', '4', '', 'File upload');
INSERT INTO `fel_auth_permission` VALUES ('54', '72', '图片水印', 'admin/site/imageWater', '1', 'fa fa-file-photo-o', '5', '', 'Watermarking');
INSERT INTO `fel_auth_permission` VALUES ('55', '72', '验证码', 'admin/site/captcha', '1', 'fa fa-lock', '6', '', 'Captcha');
INSERT INTO `fel_auth_permission` VALUES ('56', '5', '友情链接', 'admin/link/index', '1', 'fa fa-link', '3', '', 'Link');
INSERT INTO `fel_auth_permission` VALUES ('57', '56', '创建友情链接', 'admin/link/create', '0', '', '0', '', 'Create link');
INSERT INTO `fel_auth_permission` VALUES ('58', '56', '编辑友情链接', 'admin/link/edit', '0', '', '0', '', 'Edit link');
INSERT INTO `fel_auth_permission` VALUES ('59', '56', '删除友情链接', 'admin/link/remove', '0', '', '0', '', 'Remove link');
INSERT INTO `fel_auth_permission` VALUES ('60', '56', '友情链接类别', 'admin/link/category', '0', '', '0', '', 'Link category');
INSERT INTO `fel_auth_permission` VALUES ('61', '56', '删除友情链接分类', 'admin/link/removeCategory', '0', '', '0', '', 'Remove link category');
INSERT INTO `fel_auth_permission` VALUES ('62', '56', '批量操作友情链接分类', 'admin/link/handleCategory', '0', '', '0', '', 'Handle link category');
INSERT INTO `fel_auth_permission` VALUES ('63', '1', '模板管理', 'admin/template/filelist', '1', 'fa fa-file-code-o', '8', '', 'Template file');
INSERT INTO `fel_auth_permission` VALUES ('64', '63', '模板修改', 'admin/template/fileedit', '0', '', '0', '', 'Template file edit');
INSERT INTO `fel_auth_permission` VALUES ('65', '5', '幻灯片', 'admin/slider/index', '1', 'fa fa-file-image-o', '2', '', 'Slider');
INSERT INTO `fel_auth_permission` VALUES ('66', '65', '创建幻灯片', 'admin/slider/create', '0', '', '0', '', 'Create slider');
INSERT INTO `fel_auth_permission` VALUES ('67', '65', '编辑幻灯片', 'admin/slider/edit', '0', '', '0', '', 'Edit slider');
INSERT INTO `fel_auth_permission` VALUES ('68', '65', '删除幻灯片', 'admin/slider/remove', '0', '', '0', '', 'Remove slider');
INSERT INTO `fel_auth_permission` VALUES ('69', '65', '幻灯片类别', 'admin/slider/category', '0', '', '0', '', 'Slider category');
INSERT INTO `fel_auth_permission` VALUES ('70', '65', '删除幻灯片分类', 'admin/slider/removeCategory', '0', '', '0', '', 'Remove slider category');
INSERT INTO `fel_auth_permission` VALUES ('71', '65', '批量操作幻灯片分类', 'admin/slider/handleCategory', '0', '', '0', '', 'Handle slider category');
INSERT INTO `fel_auth_permission` VALUES ('72', '1', '参数配置', '#', '1', 'fa fa-wrench', '5', '', 'Site config');
INSERT INTO `fel_auth_permission` VALUES ('73', '72', '短信接口', 'admin/site/sms', '1', 'fa fa-commenting', '3', '', 'SMS');
INSERT INTO `fel_auth_permission` VALUES ('74', '72', '邮件设置', 'admin/site/email', '1', 'fa fa-envelope-o', '2', '', 'Email');
INSERT INTO `fel_auth_permission` VALUES ('75', '5', '内容区块', 'admin/block/index', '1', 'fa fa-code', '4', '', 'Block');
INSERT INTO `fel_auth_permission` VALUES ('76', '75', '创建区块', 'admin/block/create', '0', '', '0', '', 'Create block');
INSERT INTO `fel_auth_permission` VALUES ('77', '75', '编辑区块', 'admin/block/edit', '0', '', '0', '', 'Edit block');
INSERT INTO `fel_auth_permission` VALUES ('78', '75', '删除区块', 'admin/block/remove', '0', '', '0', '', 'Remove block');
INSERT INTO `fel_auth_permission` VALUES ('79', '75', '区块类别', 'admin/block/category', '0', '', '0', '', 'Block category');
INSERT INTO `fel_auth_permission` VALUES ('80', '75', '删除区块分类', 'admin/block/removeCategory', '0', '', '0', '', 'Remove block category');
INSERT INTO `fel_auth_permission` VALUES ('81', '75', '批量操作区块分类', 'admin/block/handleCategory', '0', '', '0', '', 'Handle block category');
INSERT INTO `fel_auth_permission` VALUES ('82', '5', '地区管理', 'admin/district/index', '1', 'fa fa-location-arrow', '5', '', 'District');
INSERT INTO `fel_auth_permission` VALUES ('83', '82', '创建地区', 'admin/district/create', '0', '', '0', '', 'Create district');
INSERT INTO `fel_auth_permission` VALUES ('84', '82', '编辑地区', 'admin/district/edit', '0', '', '0', '', 'Edit district');
INSERT INTO `fel_auth_permission` VALUES ('85', '82', '删除地区', 'admin/district/remove', '0', '', '0', '', 'Remove district');
INSERT INTO `fel_auth_permission` VALUES ('86', '5', '评论管理', 'admin/comments/index', '1', 'fa fa-comments', '1', '', 'Comments');
INSERT INTO `fel_auth_permission` VALUES ('87', '86', '删除评论', 'admin/comments/remove', '0', '', '0', '', 'Remove comments');
INSERT INTO `fel_auth_permission` VALUES ('88', '86', '批量操作评论', 'admin/comments/handle', '0', '', '0', '', 'Handle comments');
INSERT INTO `fel_auth_permission` VALUES ('89', '1', '系统日志', 'admin/log/index', '1', 'fa fa-history', '5', '', 'Log');
INSERT INTO `fel_auth_permission` VALUES ('90', '1', '编辑菜单', 'admin/navigation/index', '1', 'fa fa-navicon', '7', '', 'Navigation');
INSERT INTO `fel_auth_permission` VALUES ('91', '90', '菜单管理', 'admin/navigation/category', '0', '', '0', '', 'Navigation category');
INSERT INTO `fel_auth_permission` VALUES ('92', '90', '删除菜单', 'admin/navigation/removeCategory', '0', '', '0', '', 'Remove navigation category');
INSERT INTO `fel_auth_permission` VALUES ('93', '90', '批量操作菜单', 'admin/navigation/handleCategory', '0', '', '0', '', 'Handle navigation category');

-- ----------------------------
-- Table structure for fel_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `fel_auth_role`;
CREATE TABLE `fel_auth_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `lang_var` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  `weighing` int(11) DEFAULT '0' COMMENT '权重排序',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_auth_role
-- ----------------------------
INSERT INTO `fel_auth_role` VALUES ('1', '管理员', 'Administrator', '1');
INSERT INTO `fel_auth_role` VALUES ('2', '编辑', 'Editor', '999');

-- ----------------------------
-- Table structure for fel_auth_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `fel_auth_role_permission`;
CREATE TABLE `fel_auth_role_permission` (
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `permission_id` int(11) NOT NULL COMMENT '权限ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_auth_role_permission
-- ----------------------------
INSERT INTO `fel_auth_role_permission` VALUES ('2', '10');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '3');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '24');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '25');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '29');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '30');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '31');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '32');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '33');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '6');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '26');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '27');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '28');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '4');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '5');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '1');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '7');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '18');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '19');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '20');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '21');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '2');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '8');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '15');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '16');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '17');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '9');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '11');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '22');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '23');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '12');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '13');
INSERT INTO `fel_auth_role_permission` VALUES ('2', '14');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '10');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '3');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '24');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '36');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '37');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '38');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '25');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '39');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '40');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '41');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '29');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '30');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '31');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '32');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '33');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '34');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '35');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '6');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '26');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '27');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '28');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '5');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '86');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '52');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '87');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '88');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '65');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '66');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '67');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '68');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '69');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '70');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '71');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '56');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '57');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '58');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '59');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '60');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '61');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '62');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '75');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '76');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '77');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '78');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '79');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '80');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '81');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '82');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '83');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '84');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '85');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '4');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '42');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '43');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '44');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '45');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '46');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '47');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '48');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '49');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '50');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '51');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '72');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '74');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '73');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '53');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '54');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '55');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '90');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '91');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '92');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '93');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '63');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '64');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '1');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '7');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '18');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '19');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '20');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '21');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '2');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '8');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '15');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '16');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '17');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '12');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '13');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '14');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '9');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '11');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '22');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '23');
INSERT INTO `fel_auth_role_permission` VALUES ('1', '89');

-- ----------------------------
-- Table structure for fel_auth_user
-- ----------------------------
DROP TABLE IF EXISTS `fel_auth_user`;
CREATE TABLE `fel_auth_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `username` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '管理员密码',
  `phone` char(11) COLLATE utf8_bin DEFAULT '' COMMENT '手机号',
  `email` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '电子邮箱',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0 正常 1禁用',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `last_login_time` int(11) DEFAULT NULL COMMENT '最后一次登陆时间',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `user_id` (`uid`) USING BTREE,
  UNIQUE KEY `user_name` (`username`) USING BTREE,
  KEY `created` (`create_at`) USING BTREE,
  KEY `phone` (`phone`) USING BTREE,
  KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_auth_user
-- ----------------------------
INSERT INTO `fel_auth_user` VALUES ('1', 'admin', '$2y$10$4oDTTUWwPtMSoBzkkhDP0.3.nxYlOTv2hYIyrZEhS.H7q6c9k.04i', '18780221108', 'kite@kitesky.com', '0', '15', null, '1539940015', '1535434946');
INSERT INTO `fel_auth_user` VALUES ('2', 'kite', '$2y$10$xYF6T7m1xpJskcElnbgSTu1cnRuSnZPQN7ptrAGg59Dmc8W2cmPLa', '', '123@123.com', '0', '0', null, '1528854424', '1539936051');

-- ----------------------------
-- Table structure for fel_auth_user_role
-- ----------------------------
DROP TABLE IF EXISTS `fel_auth_user_role`;
CREATE TABLE `fel_auth_user_role` (
  `uid` int(11) NOT NULL COMMENT 'UID',
  `role_id` int(11) NOT NULL COMMENT '角色ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_auth_user_role
-- ----------------------------
INSERT INTO `fel_auth_user_role` VALUES ('1', '1');
INSERT INTO `fel_auth_user_role` VALUES ('1', '2');
INSERT INTO `fel_auth_user_role` VALUES ('41', '2');
INSERT INTO `fel_auth_user_role` VALUES ('2', '1');
INSERT INTO `fel_auth_user_role` VALUES ('2', '2');

-- ----------------------------
-- Table structure for fel_auth_user_site
-- ----------------------------
DROP TABLE IF EXISTS `fel_auth_user_site`;
CREATE TABLE `fel_auth_user_site` (
  `uid` int(11) NOT NULL,
  `site_id` int(11) NOT NULL COMMENT '站点ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_auth_user_site
-- ----------------------------
INSERT INTO `fel_auth_user_site` VALUES ('1', '4');
INSERT INTO `fel_auth_user_site` VALUES ('1', '3');
INSERT INTO `fel_auth_user_site` VALUES ('1', '2');
INSERT INTO `fel_auth_user_site` VALUES ('1', '1');

-- ----------------------------
-- Table structure for fel_block
-- ----------------------------
DROP TABLE IF EXISTS `fel_block`;
CREATE TABLE `fel_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) DEFAULT '0' COMMENT '友情链接分类ID',
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站名称',
  `variable` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '区块变量标识',
  `content` text COLLATE utf8_bin COMMENT '内容',
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态： 0隐藏  1 显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_block
-- ----------------------------

-- ----------------------------
-- Table structure for fel_district
-- ----------------------------
DROP TABLE IF EXISTS `fel_district`;
CREATE TABLE `fel_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父节点',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `initial` varchar(64) NOT NULL COMMENT '首字母',
  `level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '层级标识： 1  省份， 2  市， 3  区县 ,  4 街道',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COMMENT='区县行政编码字典表';

-- ----------------------------
-- Records of fel_district
-- ----------------------------
INSERT INTO `fel_district` VALUES ('8', '1', '0', '武侯区', 'W', '0', '1532005594', '1532050055');
INSERT INTO `fel_district` VALUES ('9', '1', '0', '青羊区', 'Q', '0', '1532005594', '1532050055');
INSERT INTO `fel_district` VALUES ('10', '1', '0', '金牛区', 'J', '0', '1532005594', '1532399592');
INSERT INTO `fel_district` VALUES ('11', '1', '8', '川大', 'C', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('12', '1', '8', '川音', 'C', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('13', '1', '8', '簇桥', 'C', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('14', '1', '8', '大石西路', 'D', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('15', '1', '8', '芳草街', 'F', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('16', '1', '8', '高攀路', 'G', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('17', '1', '8', '高升桥', 'G', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('18', '1', '8', '广福桥', 'G', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('19', '1', '8', '航空路', 'H', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('20', '1', '8', '好望角', 'H', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('21', '1', '8', '红牌楼', 'H', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('22', '1', '8', '红瓦寺', 'H', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('23', '1', '8', '火车南站', 'H', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('24', '1', '8', '金花镇', 'J', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('25', '1', '8', '机投镇', 'J', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('26', '1', '8', '科华路', 'K', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('27', '1', '8', '磨子桥', 'M', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('28', '1', '8', '内双楠', 'N', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('29', '1', '8', '清水河', 'Q', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('30', '1', '8', '十二中', 'S', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('31', '1', '8', '跳伞塔', 'T', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('32', '1', '8', '桐梓林', 'T', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('33', '1', '8', '外双楠', 'W', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('34', '1', '8', '望江路', 'W', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('35', '1', '8', '五大花园', 'W', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('36', '1', '8', '武侯祠大街', 'W', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('37', '1', '8', '武侯周边', 'W', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('38', '1', '8', '武阳大道', 'W', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('39', '1', '8', '肖家河', 'X', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('40', '1', '8', '小天竺街', 'X', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('41', '1', '8', '洗面桥', 'X', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('42', '1', '8', '新南门', 'X', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('43', '1', '8', '新双楠', 'X', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('44', '1', '8', '衣冠庙', 'Y', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('45', '1', '8', '玉林', 'Y', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('46', '1', '8', '棕北', 'Z', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('47', '1', '8', '棕南', 'Z', '0', '1532005921', '1532050055');
INSERT INTO `fel_district` VALUES ('48', '1', '8', '清水河', 'Q', '0', '1532005938', '1532050055');
INSERT INTO `fel_district` VALUES ('49', '1', '8', '十二中', 'S', '0', '1532005938', '1532050055');
INSERT INTO `fel_district` VALUES ('50', '1', '8', '跳伞塔', 'T', '0', '1532005938', '1532050055');
INSERT INTO `fel_district` VALUES ('51', '1', '8', '肖家河', 'X', '0', '1532005938', '1532050055');
INSERT INTO `fel_district` VALUES ('52', '1', '8', '衣冠庙', 'Y', '0', '1532005938', '1532050055');
INSERT INTO `fel_district` VALUES ('53', '1', '8', '棕北', 'Z', '0', '1532005938', '1532050055');
INSERT INTO `fel_district` VALUES ('54', '1', '9', '八宝街', 'B', '0', '1532006187', '1532050055');
INSERT INTO `fel_district` VALUES ('55', '1', '9', '白果林', 'B', '0', '1532006214', '1532050055');

-- ----------------------------
-- Table structure for fel_document_category
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_category`;
CREATE TABLE `fel_document_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级父ID',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `model_id` int(11) NOT NULL COMMENT '模型ID',
  `weighing` int(11) DEFAULT NULL COMMENT '权重排序',
  `title` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档标题',
  `alias` varchar(64) COLLATE utf8_bin NOT NULL,
  `keywords` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档关键词',
  `description` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '描述',
  `content` text COLLATE utf8_bin COMMENT '文档内容',
  `list_tpl` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '栏目模板',
  `detail_tpl` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '内容模板',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 隐藏 1 显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_category
-- ----------------------------
INSERT INTO `fel_document_category` VALUES ('4', '0', '1', '3', '0', '明星', 'cdf', '', '', null, '', '', '1', null, '1530520313');
INSERT INTO `fel_document_category` VALUES ('5', '0', '1', '1', '0', '图片', 'tupian', '', '', '', '', '', '1', '1541328648', '1541328648');

-- ----------------------------
-- Table structure for fel_document_comments
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_comments`;
CREATE TABLE `fel_document_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '评论上级ID',
  `mid` int(11) NOT NULL COMMENT '评论人mid ',
  `document_id` int(11) NOT NULL COMMENT '评论文档ID',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '评论内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 隐藏 1 显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_comments
-- ----------------------------

-- ----------------------------
-- Table structure for fel_document_comments_like
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_comments_like`;
CREATE TABLE `fel_document_comments_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comments_id` int(11) NOT NULL COMMENT '文档ID',
  `like` tinyint(1) NOT NULL DEFAULT '0' COMMENT '[顶]',
  `unlike` tinyint(1) NOT NULL DEFAULT '0' COMMENT '[踩]',
  `ip` varchar(32) COLLATE utf8_bin DEFAULT NULL COMMENT '客户端IP',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_comments_like
-- ----------------------------

-- ----------------------------
-- Table structure for fel_document_content
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_content`;
CREATE TABLE `fel_document_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '文档分类ID',
  `site_id` int(11) NOT NULL COMMENT '内容归属站点',
  `mid` int(11) DEFAULT NULL COMMENT '会员发布者 mid',
  `uid` int(11) DEFAULT NULL COMMENT '后台管理员发布者 UID',
  `title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档标题',
  `keywords` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档关键词',
  `description` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '描述',
  `content` longtext COLLATE utf8_bin COMMENT '文档内容',
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '文档封面',
  `album` text COLLATE utf8_bin COMMENT '图片集合',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 待审 1通过',
  `image_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '图片类型标识',
  `video_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '视频类型标识',
  `attach_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '附件类型标识',
  `hot_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热门标识',
  `recommend_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐标识',
  `focus_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '焦点标识',
  `top_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶标识',
  `pv` int(11) NOT NULL DEFAULT '0' COMMENT '访问次数',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_content
-- ----------------------------
INSERT INTO `fel_document_content` VALUES ('1', '5', '1', null, '1', '网站备案信息真实性核验单 ', '', '', 0x3C703E3C696D67207372633D222F75706C6F61642F32303138313130342F63373561363161633961353564386437396530386132373331333734383064392E706E6722207469746C653D2263373561363161633961353564386437396530386132373331333734383064392E706E672220616C743D226879642E706E67222F3E3C2F703E, '/upload/20181104/c75a61ac9a55d8d79e08a273137480d9.png', 0x2F75706C6F61642F32303138313130342F63373561363161633961353564386437396530386132373331333734383064392E706E67, '1', '1', '0', '0', '0', '0', '0', '0', '16', '1541328668', '1541548516');

-- ----------------------------
-- Table structure for fel_document_content_extra
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_content_extra`;
CREATE TABLE `fel_document_content_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文档内容自定义ID',
  `document_id` int(11) NOT NULL COMMENT '文档ID',
  `type` char(20) COLLATE utf8_bin NOT NULL COMMENT '字段内容类型',
  `name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段名称',
  `variable` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段变量',
  `key` text COLLATE utf8_bin COMMENT '字段选项原始值',
  `value` text COLLATE utf8_bin COMMENT '字段值',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`) USING BTREE,
  KEY `variable` (`variable`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_content_extra
-- ----------------------------

-- ----------------------------
-- Table structure for fel_document_content_like
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_content_like`;
CREATE TABLE `fel_document_content_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL COMMENT '文档ID',
  `like` tinyint(1) NOT NULL DEFAULT '0' COMMENT '[顶]',
  `unlike` tinyint(1) NOT NULL DEFAULT '0' COMMENT '[踩]',
  `ip` varchar(32) COLLATE utf8_bin DEFAULT NULL COMMENT '客户端IP',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_content_like
-- ----------------------------
INSERT INTO `fel_document_content_like` VALUES ('1', '1', '1', '0', '171.88.1.80', '1541328834', '1541328834');

-- ----------------------------
-- Table structure for fel_document_field
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_field`;
CREATE TABLE `fel_document_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '字段归类',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段名称',
  `variable` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '字段列名',
  `type` char(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段类型',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '权重排序',
  `is_require` tinyint(1) DEFAULT '0' COMMENT '0 正常 1必填',
  `is_filter` tinyint(1) DEFAULT NULL COMMENT '0正常 1筛选条件',
  `option` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段内容选项',
  `description` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '描述',
  `regular` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '正则表达式',
  `msg` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '验证失败提示语',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_field
-- ----------------------------

-- ----------------------------
-- Table structure for fel_document_model
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_model`;
CREATE TABLE `fel_document_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `name` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '模型名称',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '排序 越小越靠前',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_model
-- ----------------------------
INSERT INTO `fel_document_model` VALUES ('1', '1', '空模型', '0', '1541328594', '1541328594');

-- ----------------------------
-- Table structure for fel_document_model_field
-- ----------------------------
DROP TABLE IF EXISTS `fel_document_model_field`;
CREATE TABLE `fel_document_model_field` (
  `model_id` int(11) NOT NULL COMMENT '模型ID',
  `field_id` int(11) NOT NULL COMMENT '字段ID',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_document_model_field
-- ----------------------------

-- ----------------------------
-- Table structure for fel_language
-- ----------------------------
DROP TABLE IF EXISTS `fel_language`;
CREATE TABLE `fel_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '语言名称',
  `icon` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `weighing` int(11) NOT NULL COMMENT '权重排序 越大越靠后',
  `designation` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_language
-- ----------------------------
INSERT INTO `fel_language` VALUES ('1', 'zh-cn', null, '1', '简体中文(中国) ');
INSERT INTO `fel_language` VALUES ('2', 'en-us', null, '2', '英语(美国) ');

-- ----------------------------
-- Table structure for fel_link
-- ----------------------------
DROP TABLE IF EXISTS `fel_link`;
CREATE TABLE `fel_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) DEFAULT '0' COMMENT '友情链接分类ID',
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站地址',
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'logo地址',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态： 0隐藏  1 显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_link
-- ----------------------------

-- ----------------------------
-- Table structure for fel_log
-- ----------------------------
DROP TABLE IF EXISTS `fel_log`;
CREATE TABLE `fel_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL COMMENT '会员MID',
  `site_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '操作类型',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '操作内容',
  `ip` char(32) COLLATE utf8_bin DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_log
-- ----------------------------
INSERT INTO `fel_log` VALUES ('1', '1', null, null, null, null, '171.221.254.149', '1540453386');
INSERT INTO `fel_log` VALUES ('2', '1', null, null, null, null, '171.221.254.149', '1540862715');
INSERT INTO `fel_log` VALUES ('3', null, '1', '1', null, null, '171.221.254.149', '1540863288');
INSERT INTO `fel_log` VALUES ('4', '1', null, null, null, null, '171.221.254.149', '1540974277');
INSERT INTO `fel_log` VALUES ('5', '1', null, null, null, null, '171.88.1.80', '1541328472');
INSERT INTO `fel_log` VALUES ('6', '1', null, null, null, null, '171.221.254.149', '1541386371');
INSERT INTO `fel_log` VALUES ('7', '1', null, null, null, null, '171.221.254.149', '1541470914');
INSERT INTO `fel_log` VALUES ('8', '1', null, null, null, null, '171.221.254.149', '1541557011');
INSERT INTO `fel_log` VALUES ('9', '1', null, null, null, null, '171.221.254.149', '1541557012');

-- ----------------------------
-- Table structure for fel_member
-- ----------------------------
DROP TABLE IF EXISTS `fel_member`;
CREATE TABLE `fel_member` (
  `mid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `site_id` int(11) NOT NULL COMMENT '归属网站ID',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员组',
  `avatar` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '头像',
  `username` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '管理员密码',
  `phone` char(11) COLLATE utf8_bin DEFAULT '' COMMENT '手机号',
  `email` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '电子邮箱',
  `score` int(11) DEFAULT NULL COMMENT '积分',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0 正常 1禁用',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`mid`),
  UNIQUE KEY `user_name` (`username`) USING BTREE,
  UNIQUE KEY `member_id` (`mid`) USING BTREE,
  KEY `created` (`create_at`) USING BTREE,
  KEY `phone` (`phone`) USING BTREE,
  KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_member
-- ----------------------------
INSERT INTO `fel_member` VALUES ('1', '1', '1', '/upload/20180730/a5c81705fbe10ac396a961fe090eff4e.jpg', 'kite', '$2y$10$TimwqIGwCkS4ha7zt/L5w.CEcJobc8/vdc0ZvNda5Zs5KXf0pB9xa', '18780221108', 'kite@kitesky.com', '20', '0', '0', '1531207849', '1532935867');

-- ----------------------------
-- Table structure for fel_member_group
-- ----------------------------
DROP TABLE IF EXISTS `fel_member_group`;
CREATE TABLE `fel_member_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL COMMENT '归属网站ID',
  `group_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `min_score` int(11) NOT NULL DEFAULT '0' COMMENT '最低积分',
  `max_score` int(11) NOT NULL DEFAULT '0' COMMENT '最大分值',
  `permission_ids` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '会员组拥有权限ID集合 多个用逗号","隔开',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '权重排序',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_member_group
-- ----------------------------
INSERT INTO `fel_member_group` VALUES ('1', '1', '注册会员', '0', '11', '12,1,11,2,3,4,5,13,14,15,6,7,8,9,10', '1', '1529465745', '1535422620');
INSERT INTO `fel_member_group` VALUES ('2', '1', '高级会员', '0', '0', '', '999', '1529465745', null);
INSERT INTO `fel_member_group` VALUES ('3', '1', '超级会员', '1', '22', '', '1', '1529459276', '1529465745');
INSERT INTO `fel_member_group` VALUES ('4', '1', 'Demo', '1', '11', '1,2', '11', '1529471934', '1529501930');

-- ----------------------------
-- Table structure for fel_member_permission
-- ----------------------------
DROP TABLE IF EXISTS `fel_member_permission`;
CREATE TABLE `fel_member_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '规则名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `menu` tinyint(1) DEFAULT '0' COMMENT '是否为菜单0 否 1是',
  `icon` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'fa fa-circle-o' COMMENT '图标',
  `weighing` int(11) DEFAULT '0' COMMENT '权重排序',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '备注说明',
  `lang_var` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  PRIMARY KEY (`id`),
  KEY `permission_url` (`url`) USING BTREE,
  KEY `lang_var` (`lang_var`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_member_permission
-- ----------------------------
INSERT INTO `fel_member_permission` VALUES ('1', '0', '信息管理', '#', '1', 'fa fa-laptop', '1', '', 'My document');
INSERT INTO `fel_member_permission` VALUES ('2', '1', '发布信息', 'member/document/create', '1', 'fa fa-edit', '2', '', 'Publish document');
INSERT INTO `fel_member_permission` VALUES ('3', '1', '修改信息', 'member/document/edit', '0', 'fa fa-circle-o', '3', '', 'Edit document');
INSERT INTO `fel_member_permission` VALUES ('4', '1', '删除信息', 'member/document/remove', '0', 'fa fa-circle-o', '4', '', 'Remove document');
INSERT INTO `fel_member_permission` VALUES ('5', '0', '账户设置', '#', '1', 'fa fa-user', '5', '', 'Account setting');
INSERT INTO `fel_member_permission` VALUES ('6', '5', '个人资料', 'member/member/profile', '1', 'fa fa-circle-o text-red', '6', '', 'Member profile');
INSERT INTO `fel_member_permission` VALUES ('7', '5', '账户绑定', 'member/member/bind', '1', 'fa fa-circle-o text-yellow', '7', '', 'Member bind');
INSERT INTO `fel_member_permission` VALUES ('8', '5', '手机绑定', 'member/member/mobileBind', '0', 'fa fa-circle-o', '8', '', 'Mobile bind');
INSERT INTO `fel_member_permission` VALUES ('9', '5', '邮箱绑定', 'member/member/emailBind', '0', 'fa fa-circle-o', '9', '', 'Email bind');
INSERT INTO `fel_member_permission` VALUES ('10', '5', '密码修改', 'member/member/password', '1', 'fa fa-circle-o text-aqua', '10', '', 'Password update');
INSERT INTO `fel_member_permission` VALUES ('11', '1', '信息列表', 'member/document/index', '1', 'fa fa-book', '0', '', 'Document list');
INSERT INTO `fel_member_permission` VALUES ('12', '0', '会员中心', 'member/index/index', '0', 'fa fa-circle-o', '0', '', 'Member index');
INSERT INTO `fel_member_permission` VALUES ('13', '5', '手机解绑', 'member/member/mobileUnbind', '0', 'fa fa-circle-o', '0', '', 'Mobile unbind');
INSERT INTO `fel_member_permission` VALUES ('14', '5', '邮箱解绑', 'member/member/emailUnbind', '0', 'fa fa-circle-o', '0', '', 'Email unbind');
INSERT INTO `fel_member_permission` VALUES ('15', '5', '头像设置', 'member/member/avatar', '0', 'fa fa-circle-o', '0', '', 'Member avatar');

-- ----------------------------
-- Table structure for fel_member_score
-- ----------------------------
DROP TABLE IF EXISTS `fel_member_score`;
CREATE TABLE `fel_member_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '积分记录编号',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `mid` int(11) NOT NULL COMMENT '会员ID',
  `sum` int(11) NOT NULL COMMENT '剩余总数',
  `score` int(11) NOT NULL COMMENT '积分数量',
  `source` varchar(255) CHARACTER SET latin1 DEFAULT NULL COMMENT '获取原因',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_member_score
-- ----------------------------
INSERT INTO `fel_member_score` VALUES ('1', '1', '9', '100', '100', 'register_score', '1532932197', '1532932197');
INSERT INTO `fel_member_score` VALUES ('2', '1', '1', '1', '1', 'login_score', '1532932783', '1532932783');
INSERT INTO `fel_member_score` VALUES ('3', '1', '1', '2', '1', 'login_score', '1532932811', '1532932811');
INSERT INTO `fel_member_score` VALUES ('4', '1', '1', '12', '10', 'publish_score', '1532933504', '1532933504');
INSERT INTO `fel_member_score` VALUES ('5', '1', '1', '17', '5', 'comment_score', '1532933830', '1532933830');
INSERT INTO `fel_member_score` VALUES ('6', '1', '1', '12', '-5', 'comment_score', '1532933938', '1532933938');
INSERT INTO `fel_member_score` VALUES ('7', '1', '1', '13', '1', 'login_score', '1532944564', '1532944564');
INSERT INTO `fel_member_score` VALUES ('8', '1', '1', '14', '1', 'login_score', '1533116836', '1533116836');
INSERT INTO `fel_member_score` VALUES ('9', '1', '1', '9', '-5', 'comment_score', '1533116847', '1533116847');
INSERT INTO `fel_member_score` VALUES ('10', '1', '1', '4', '-5', 'comment_score', '1533116860', '1533116860');
INSERT INTO `fel_member_score` VALUES ('11', '1', '1', '5', '1', 'login_score', '1533168598', '1533168598');
INSERT INTO `fel_member_score` VALUES ('12', '1', '1', '15', '10', 'publish_score', '1533168746', '1533168746');
INSERT INTO `fel_member_score` VALUES ('13', '1', '1', '16', '1', 'login_score', '1534987891', '1534987891');
INSERT INTO `fel_member_score` VALUES ('14', '1', '1', '17', '1', 'login_score', '1536225544', '1536225544');
INSERT INTO `fel_member_score` VALUES ('15', '1', '1', '18', '1', 'login_score', '1537162807', '1537162807');
INSERT INTO `fel_member_score` VALUES ('16', '1', '1', '19', '1', 'login_score', '1537162858', '1537162858');
INSERT INTO `fel_member_score` VALUES ('17', '1', '1', '20', '1', 'login_score', '1540863288', '1540863288');

-- ----------------------------
-- Table structure for fel_message
-- ----------------------------
DROP TABLE IF EXISTS `fel_message`;
CREATE TABLE `fel_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '信息编号',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `type` tinyint(1) NOT NULL COMMENT '信息类型 1 系统消息 2 短信 3 邮件',
  `subject` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '信息标题',
  `body` text COLLATE utf8_bin COMMENT '信息内容',
  `code` varchar(32) COLLATE utf8_bin DEFAULT NULL COMMENT '动态码',
  `mid` int(11) DEFAULT NULL COMMENT '系统消息接收人mid',
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '邮件消息接收人email',
  `phone` char(11) COLLATE utf8_bin DEFAULT NULL COMMENT '短信接收人手机号码',
  `send_status` varchar(255) COLLATE utf8_bin NOT NULL COMMENT ' success 发送成功  fail 发送失败',
  `send_error` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '错误代码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 正常 1 失效',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_message
-- ----------------------------

-- ----------------------------
-- Table structure for fel_navigation
-- ----------------------------
DROP TABLE IF EXISTS `fel_navigation`;
CREATE TABLE `fel_navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '菜单名称',
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '菜单URL',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 栏目 2自定义URL',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_navigation
-- ----------------------------

-- ----------------------------
-- Table structure for fel_site
-- ----------------------------
DROP TABLE IF EXISTS `fel_site`;
CREATE TABLE `fel_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '网站SID',
  `city_id` int(11) NOT NULL COMMENT '站点关联城市',
  `name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '站点名称',
  `alias` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '站点别名',
  `logo` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT 'LOGO',
  `domain` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点绑定域名',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '排序 越小越靠前',
  `hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热门站点',
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点标题',
  `keywords` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点关键词',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '站点描述',
  `timezone` char(64) COLLATE utf8_bin DEFAULT NULL COMMENT '时区',
  `theme` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '模板名称',
  `copyright` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '版权信息',
  `icp` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ICP备案号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 正常 1关闭',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `site_name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_site
-- ----------------------------
INSERT INTO `fel_site` VALUES ('1', '0', '搜索引擎优化', 'default', '/upload/20180730/456e76097ab1f8e2fa51cca535eb3161.png', 'http://www.19981.com', '0', '0', '搜索引擎优化', '搜索引擎优化', '搜索引擎优化', 'Asia/Shanghai', 'default', '© 2018 19981.com. All rights reserved', '蜀ICP备12004586号-4', '0', '1528854424', '1541387323');
INSERT INTO `fel_site` VALUES ('2', '0', '风筝的天空', 'kitesky', '', 'http://www.kitesky.com', '0', '0', '风筝的天空', '风筝的天空', '风筝的天空', null, 'default', '个人网站', '蜀ICP备12004586号-2', '0', '1541387367', '1541387731');
INSERT INTO `fel_site` VALUES ('3', '0', '我的留学生活', 'lxle', '', 'http://www.lxle.com', '0', '0', '我的留学生活', '我的留学生活', '我的留学生活', null, 'default', '我的留学生活', '蜀ICP备12004586号-5', '0', '1541387657', '1541387723');
INSERT INTO `fel_site` VALUES ('4', '0', '若秀时尚', 'ruoxiu', '', 'http://www.ruoxiu.com', '0', '0', '若秀时尚', '若秀时尚', '若秀时尚', null, 'default', 'ruoxiu', '蜀ICP备12004586号-3', '0', '1541387780', '1541387780');

-- ----------------------------
-- Table structure for fel_site_config
-- ----------------------------
DROP TABLE IF EXISTS `fel_site_config`;
CREATE TABLE `fel_site_config` (
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `k` varchar(64) COLLATE utf8_bin NOT NULL COMMENT '键名',
  `v` text COLLATE utf8_bin COMMENT '键值',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  KEY `key` (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_site_config
-- ----------------------------
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_publish_document', 0x30, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'register_score', 0x313030, '1529499198', '1529499198');
INSERT INTO `fel_site_config` VALUES ('1', 'login_score', 0x31, '1529499387', '1529499387');
INSERT INTO `fel_site_config` VALUES ('1', 'publish_score', 0x3130, '1529499540', '1529499540');
INSERT INTO `fel_site_config` VALUES ('1', 'comment_score', 0x2D35, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_type', 0x6C6F63616C, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_size', 0x6A70672C706E672C676966, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_ext', 0x6A70672C706E672C676966, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_path', 0x75706C6F6164, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'alioss_key', 0x34483543346A51627842417362777965, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'alioss_secret', 0x5535426539564C5A437079386F436F377354515343713830367377714756, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'alioss_endpoint', 0x6F73732D636E2D7368656E7A68656E2E616C6979756E63732E636F6D, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'alioss_bucket', 0x6B697465736B79, '1529499606', '1529499606');
INSERT INTO `fel_site_config` VALUES ('1', 'qiniu_ak', 0x3956577A66316A6953336745414C42695F587477454C4861487A484A49654358453557344B744A74, '1530071701', '1530071701');
INSERT INTO `fel_site_config` VALUES ('1', 'qiniu_sk', 0x54474E6432317877662D794847576E3346774E3337666B5257704F7A7A4D685843356A4566677238, '1530071701', '1530071701');
INSERT INTO `fel_site_config` VALUES ('1', 'qiniu_bucket', 0x6B697465736B79, '1530071701', '1530071701');
INSERT INTO `fel_site_config` VALUES ('1', 'qiniu_domain', 0x687474703A2F2F6F6E7872386D7438792E626B742E636C6F7564646E2E636F6D, '1530071701', '1530071701');
INSERT INTO `fel_site_config` VALUES ('1', 'link_category', 0x5B5D, '1531141510', '1531141510');
INSERT INTO `fel_site_config` VALUES ('1', 'slider_category', 0x5B5D, '1531147967', '1531147967');
INSERT INTO `fel_site_config` VALUES ('1', 'field_category', 0x5B5D, '1531147967', '1531147967');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_useZh', 0x30, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_useImgBg', 0x30, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_fontSize', 0x3234, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_imageH', 0x3430, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_imageW', 0x323030, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_length', 0x34, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_member_register', 0x30, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_member_login', 0x30, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_publish_comment', 0x30, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_publish_feedback', 0x30, '1531213657', '1531213657');
INSERT INTO `fel_site_config` VALUES ('1', 'water_logo', 0x2F7374617469632F61646D696E2F646973742F696D672F6E6F7069632E706E67, '1531213845', '1531213845');
INSERT INTO `fel_site_config` VALUES ('1', 'water_position', 0x39, '1531213845', '1531213845');
INSERT INTO `fel_site_config` VALUES ('1', 'water_quality', 0x3830, '1531213845', '1531213845');
INSERT INTO `fel_site_config` VALUES ('1', 'water_status', 0x30, '1531213845', '1531213845');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_useCurve', 0x30, '1531217269', '1531217269');
INSERT INTO `fel_site_config` VALUES ('1', 'captcha_useNoise', 0x30, '1531217269', '1531217269');
INSERT INTO `fel_site_config` VALUES ('1', 'sms_type', 0x6479736D73, '1531371550', '1531371550');
INSERT INTO `fel_site_config` VALUES ('1', 'ali_access_key', '', '1531371550', '1531371550');
INSERT INTO `fel_site_config` VALUES ('1', 'ali_access_key_secret', '', '1531371550', '1531371550');
INSERT INTO `fel_site_config` VALUES ('1', 'ali_sign_name', '', '1531371550', '1531371550');
INSERT INTO `fel_site_config` VALUES ('1', 'ali_template_code', '', '1531371550', '1531371550');
INSERT INTO `fel_site_config` VALUES ('1', 'email_host', 0x736D74702E3136332E636F6D, '1531378668', '1531378668');
INSERT INTO `fel_site_config` VALUES ('1', 'email_port', 0x343635, '1531378668', '1531378668');
INSERT INTO `fel_site_config` VALUES ('1', 'email_username', 0x6E73737368, '1531378668', '1531378668');
INSERT INTO `fel_site_config` VALUES ('1', 'email_password', 0x77616E677A68656E67, '1531378668', '1531378668');
INSERT INTO `fel_site_config` VALUES ('1', 'member_register', 0x30, '1531378916', '1531378916');
INSERT INTO `fel_site_config` VALUES ('1', 'email_from_email', 0x6E73737368403136332E636F6D, '1531383066', '1531383066');
INSERT INTO `fel_site_config` VALUES ('1', 'email_from_name', 0x4B697465434D53, '1531383066', '1531383066');
INSERT INTO `fel_site_config` VALUES ('1', 'block_category', 0x5B5D, '1531981998', '1531981998');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_image_ext', 0x6A70672C706E672C676966, '1532327020', '1532327020');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_image_size', 0x32303438, '1532327020', '1532327020');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_video_ext', 0x726D2C726D76622C776D762C3367702C6D70342C6D6F762C6176692C666C76, '1532327020', '1532327020');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_video_size', 0x3130323430, '1532327020', '1532327020');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_attach_ext', 0x646F632C786C732C7261722C7A6970, '1532327020', '1532327020');
INSERT INTO `fel_site_config` VALUES ('1', 'upload_attach_size', 0x3130323430, '1532327020', '1532327020');
INSERT INTO `fel_site_config` VALUES ('1', 'navigation_category', 0x5B5D, '1532675827', '1532675827');
INSERT INTO `fel_site_config` VALUES ('1', 'email_code_template', 0xE5B08AE695ACE79A84E4BC9AE59198247B757365726E616D657D20EFBC8CE682A8E69CACE6ACA1E79A84E9AA8CE8AF81E7A081E4B8BAEFBC9A247B636F64657D20EFBC8CE9AA8CE8AF81E7A081E59CA835E58886E9929FE58685E69C89E69588E38082, '1532856848', '1532856848');
INSERT INTO `fel_site_config` VALUES ('1', 'email_register_template', 0xE5B08AE695ACE79A84E4BC9AE59198247B757365726E616D657D20EFBC8CE682A8E5B7B2E7BB8FE68890E58A9FE6B3A8E5868CEFBC8CE8AFB7E8B0A8E8AEB0E682A8E79A84E794A8E688B7E5908DE58F8AE5AF86E7A081E38082, '1532856848', '1532856848');
INSERT INTO `fel_site_config` VALUES ('1', 'send_email_register', 0x30, '1532856848', '1532856848');
INSERT INTO `fel_site_config` VALUES ('2', 'field_category', null, '1541487138', '1541487138');

-- ----------------------------
-- Table structure for fel_site_file
-- ----------------------------
DROP TABLE IF EXISTS `fel_site_file`;
CREATE TABLE `fel_site_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `upload_type` char(20) COLLATE utf8_bin NOT NULL COMMENT '上传方式',
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '图片原始名称',
  `name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '图片上传后生成名字',
  `ext` char(20) COLLATE utf8_bin NOT NULL COMMENT '图片后缀',
  `url` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '图片URL',
  `thumb` text COLLATE utf8_bin COMMENT '本地生成缩略图记录 多个用逗号隔开。方便以后清理',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 上传未被引用; 1 上传后被引用',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_site_file
-- ----------------------------
INSERT INTO `fel_site_file` VALUES ('1', '1', 'local', 'hyd.png', 'c75a61ac9a55d8d79e08a273137480d9.png', 'png', '/upload/20181104/c75a61ac9a55d8d79e08a273137480d9.png', 0x2F75706C6F61642F32303138313130342F63373561363161633961353564386437396530386132373331333734383064395F323530783235302E706E672C2F75706C6F61642F32303138313130342F63373561363161633961353564386437396530386132373331333734383064395F3132307837352E706E672C2F75706C6F61642F32303138313130342F63373561363161633961353564386437396530386132373331333734383064395F323230783134302E706E672C2F75706C6F61642F32303138313130342F63373561363161633961353564386437396530386132373331333734383064395F333530783233302E706E672C2F75706C6F61642F32303138313130342F63373561363161633961353564386437396530386132373331333734383064395F333530783233302E706E67, '0', '1541328538', '1541536786');

-- ----------------------------
-- Table structure for fel_slider
-- ----------------------------
DROP TABLE IF EXISTS `fel_slider`;
CREATE TABLE `fel_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) DEFAULT '0' COMMENT '友情链接分类ID',
  `site_id` char(64) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站名称',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网站地址',
  `image` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'logo地址',
  `content` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '描述内容',
  `weighing` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态： 0隐藏  1 显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of fel_slider
-- ----------------------------
