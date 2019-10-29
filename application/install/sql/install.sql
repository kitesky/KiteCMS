/*
 Navicat Premium Data Transfer

 Source Server         : 119.23.255.36
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : 119.23.255.36:3306
 Source Schema         : kitecms

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 29/10/2019 17:09:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kite_addons
-- ----------------------------
DROP TABLE IF EXISTS `kite_addons`;
CREATE TABLE `kite_addons`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0 未安装 1 启用 2禁用 3损坏',
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置',
  `author` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '版本号',
  `has_adminlist` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否有后台列表',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '插件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_addons
-- ----------------------------
INSERT INTO `kite_addons` VALUES (3, 'demo', '演示插件', '用于演示插件', 1, '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"4\",\"display\":\"1\",\"id\":\"3\"}', 'kitecms', '1.0', 1, NULL, NULL);

-- ----------------------------
-- Table structure for kite_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `kite_auth_role`;
CREATE TABLE `kite_auth_role`  (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `rule_ids` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '角色拥有的权限集合',
  `site_ids` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '站点ID集合',
  `lang_var` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_auth_role
-- ----------------------------
INSERT INTO `kite_auth_role` VALUES (1, '管理员', '10,97,108,107,98,99,100,101,109,110,111,112,113,114,102,103,104,105,106,3,24,36,37,38,25,39,40,41,29,30,31,32,33,34,35,6,26,27,28,5,42,43,44,45,46,47,48,49,50,82,55,73,74,83,85,94,95,96,86,52,87,88,65,66,67,68,69,70,71,56,57,58,59,60,61,62,75,76,77,78,79,80,81,51,53,54,4,7,18,19,20,21,8,15,16,17,2,1,12,13,14,9,11,22,23,72,89,90,91,92,93,63,64', '1,2', 'Administrator', 1);
INSERT INTO `kite_auth_role` VALUES (2, '编辑员', '10,3,24,36,37,38,25,39,40,41,29,30,31,32,33,34,35,6,26,27,28', '', 'Editor', 2);
INSERT INTO `kite_auth_role` VALUES (3, '注册用户', '10,97,108,107,98,99,100,101,109,110,111,112,113,114,102,103,104,105,106', '', 'Member', 2);

-- ----------------------------
-- Table structure for kite_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `kite_auth_rule`;
CREATE TABLE `kite_auth_rule`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `module` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '规则所属模型',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `url` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `menu` tinyint(1) NULL DEFAULT 0 COMMENT '是否为菜单0 否 1是',
  `icon` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'fa fa-circle-o' COMMENT '图标',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注说明',
  `lang_var` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `permission_url`(`url`) USING BTREE,
  INDEX `lang_var`(`lang_var`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 115 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_auth_rule
-- ----------------------------
INSERT INTO `kite_auth_rule` VALUES (1, 0, 'admin', '系统管理', '#', 1, 'fa fa-laptop', 6, '', 'System');
INSERT INTO `kite_auth_rule` VALUES (2, 8, 'admin', '站点授权', 'admin/role/siteAuth', 0, '', 3, '', 'Site authorize');
INSERT INTO `kite_auth_rule` VALUES (3, 0, 'admin', '信息管理', '#', 1, 'fa fa-file-word-o', 2, '', 'Information');
INSERT INTO `kite_auth_rule` VALUES (4, 0, 'admin', '会员管理', '#', 0, 'fa fa-user', 4, '', 'User');
INSERT INTO `kite_auth_rule` VALUES (5, 0, 'admin', '功能扩展', '#', 1, 'fa fa-chrome', 3, '', 'Extended');
INSERT INTO `kite_auth_rule` VALUES (6, 3, 'admin', '模型管理', 'admin/documentModel/index', 1, 'fa fa-cube', 4, '', 'Document model');
INSERT INTO `kite_auth_rule` VALUES (7, 1, 'admin', '用户管理', 'admin/user/index', 1, 'fa fa-user', 1, '', 'User');
INSERT INTO `kite_auth_rule` VALUES (8, 1, 'admin', '角色管理', 'admin/role/index', 1, 'fa fa-users', 2, '', 'Role');
INSERT INTO `kite_auth_rule` VALUES (9, 1, 'admin', '站点管理', 'admin/site/index', 1, 'fa fa-globe', 4, '', 'Sites');
INSERT INTO `kite_auth_rule` VALUES (10, 0, 'admin', '站点首页', 'admin/index/index', 1, 'fa fa-dashboard ', 1, '', 'Control panel');
INSERT INTO `kite_auth_rule` VALUES (11, 9, 'admin', '创建站点', 'admin/site/create', 0, 'fa fa-circle-o', 0, '', 'Create site');
INSERT INTO `kite_auth_rule` VALUES (12, 1, 'admin', '权限配置', 'admin/rule/index', 1, 'fa fa-shield', 3, '', 'Permission');
INSERT INTO `kite_auth_rule` VALUES (13, 12, 'admin', '增加权限', 'admin/rule/create', 0, 'fa fa-circle-o', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (14, 12, 'admin', '权限编辑', 'admin/rule/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (15, 8, 'admin', '增加角色', 'admin/role/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (16, 8, 'admin', '角色编辑', 'admin/role/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (17, 8, 'admin', '角色授权', 'admin/role/auth', 0, '', 0, '', 'Role authorization');
INSERT INTO `kite_auth_rule` VALUES (18, 7, 'admin', '创建管理员', 'admin/user/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (19, 7, 'admin', '更新管理员', 'admin/user/edit', 0, '', 0, '', 'Update');
INSERT INTO `kite_auth_rule` VALUES (20, 7, 'admin', '删除管理员', 'admin/user/remove', 0, '', 0, '', 'Delete');
INSERT INTO `kite_auth_rule` VALUES (21, 7, 'admin', '管理员批量操作', 'admin/user/handle', 0, '', 0, '', 'Batch operation');
INSERT INTO `kite_auth_rule` VALUES (22, 9, 'admin', '编辑站点', 'admin/site/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (23, 9, 'admin', '删除站点', 'admin/site/remove', 0, '', 0, '', 'Delete');
INSERT INTO `kite_auth_rule` VALUES (24, 3, 'admin', '文档信息', 'admin/document/index', 1, 'fa fa-file-text-o', 1, '', 'Document');
INSERT INTO `kite_auth_rule` VALUES (25, 3, 'admin', '栏目分类', 'admin/category/index', 1, 'fa fa-list-ol', 2, '', 'Category');
INSERT INTO `kite_auth_rule` VALUES (26, 6, 'admin', '创建模型', 'admin/documentModel/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (27, 6, 'admin', '编辑模型', 'admin/documentModel/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (28, 6, 'admin', '删除模型', 'admin/documentModel/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (29, 3, 'admin', '字段管理', 'admin/documentField/index', 1, 'fa fa-cubes', 3, '', 'Document Field');
INSERT INTO `kite_auth_rule` VALUES (30, 29, 'admin', '创建字段', 'admin/documentField/create', 0, '', 0, '', 'Create document field');
INSERT INTO `kite_auth_rule` VALUES (31, 29, 'admin', '编辑字段', 'admin/documentField/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (32, 29, 'admin', '删除字段', 'admin/documentField/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (33, 29, 'admin', '字段类别', 'admin/documentField/category', 0, '', 0, '', 'Document field category');
INSERT INTO `kite_auth_rule` VALUES (34, 29, 'admin', '删除字段分类', 'admin/documentField/removeCategory', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (35, 29, 'admin', '批量操作字段分类', 'admin/documentField/handleCategory', 0, '', 0, '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES (36, 24, 'admin', '创建文档', 'admin/document/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (37, 24, 'admin', '更新文档', 'admin/document/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (38, 24, 'admin', '删除文档', 'admin/document/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (39, 25, 'admin', '创建文档', 'admin/category/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (40, 25, 'admin', '更新文档', 'admin/category/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (41, 25, 'admin', '删除文档', 'admin/category/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (42, 1, 'admin', '插件管理', 'admin/addons/index', 1, 'fa fa-plus-square', 0, '', 'Addons');
INSERT INTO `kite_auth_rule` VALUES (43, 42, 'admin', '插件安装', 'admin/addons/install', 0, '', 0, '', 'Addons install');
INSERT INTO `kite_auth_rule` VALUES (44, 42, 'admin', '插件卸载', 'admin/addons/uninstall', 0, '', 0, '', 'Addons uninstall');
INSERT INTO `kite_auth_rule` VALUES (45, 42, 'admin', '插件启用', 'admin/addons/enable', 0, '', 0, '', 'Addons enable');
INSERT INTO `kite_auth_rule` VALUES (46, 42, 'admin', '插件禁用', 'admin/addons/disable', 0, '', 0, '', 'Addons disable');
INSERT INTO `kite_auth_rule` VALUES (47, 42, 'admin', '插件配置', 'admin/addons/config', 0, '', 0, '', 'Addons config');
INSERT INTO `kite_auth_rule` VALUES (48, 1, 'admin', '钩子管理', 'admin/hooks/index', 1, 'fa fa-gg-circle', 0, '', 'Hooks');
INSERT INTO `kite_auth_rule` VALUES (49, 48, 'admin', '钩子添加', 'admin/hooks/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (50, 48, 'admin', '钩子编辑', 'admin/hooks/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (51, 5, 'admin', '订单', 'admin/order/index', 1, 'fa fa-shopping-cart', 10, '', 'Order');
INSERT INTO `kite_auth_rule` VALUES (52, 86, 'admin', '编辑评论', 'admin/comments/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (53, 51, 'admin', '订单详情', 'admin/order/edit', 0, 'fa fa-circle-o', 0, '', 'Detail');
INSERT INTO `kite_auth_rule` VALUES (54, 51, 'admin', '删除', 'admin/order/remove', 0, 'fa fa-circle-o', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (56, 5, 'admin', '友情链接', 'admin/link/index', 1, 'fa fa-link', 3, '', 'Link');
INSERT INTO `kite_auth_rule` VALUES (57, 56, 'admin', '创建友情链接', 'admin/link/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (58, 56, 'admin', '编辑友情链接', 'admin/link/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (59, 56, 'admin', '删除友情链接', 'admin/link/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (60, 56, 'admin', '友情链接类别', 'admin/link/category', 0, '', 0, '', 'Link category');
INSERT INTO `kite_auth_rule` VALUES (61, 56, 'admin', '删除友情链接分类', 'admin/link/removeCategory', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (62, 56, 'admin', '批量操作友情链接分类', 'admin/link/handleCategory', 0, '', 0, '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES (63, 1, 'admin', '模板管理', 'admin/template/filelist', 1, 'fa fa-file-code-o', 8, '', 'Template file');
INSERT INTO `kite_auth_rule` VALUES (64, 63, 'admin', '模板修改', 'admin/template/fileedit', 0, '', 0, '', 'Template file edit');
INSERT INTO `kite_auth_rule` VALUES (65, 5, 'admin', '幻灯片', 'admin/slider/index', 1, 'fa fa-file-image-o', 2, '', 'Slider');
INSERT INTO `kite_auth_rule` VALUES (66, 65, 'admin', '创建幻灯片', 'admin/slider/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (67, 65, 'admin', '编辑幻灯片', 'admin/slider/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (68, 65, 'admin', '删除幻灯片', 'admin/slider/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (69, 65, 'admin', '类别', 'admin/slider/category', 0, '', 0, '', 'Slider category');
INSERT INTO `kite_auth_rule` VALUES (70, 65, 'admin', '删除幻灯片分类', 'admin/slider/removeCategory', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (71, 65, 'admin', '批量操作幻灯片分类', 'admin/slider/handleCategory', 0, '', 0, '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES (72, 1, 'admin', '参数配置', 'admin/site/config', 1, 'fa fa-wrench', 5, '', 'Site config');
INSERT INTO `kite_auth_rule` VALUES (75, 5, 'admin', '内容区块', 'admin/block/index', 1, 'fa fa-code', 4, '', 'Block');
INSERT INTO `kite_auth_rule` VALUES (76, 75, 'admin', '创建区块', 'admin/block/create', 0, '', 0, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (77, 75, 'admin', '编辑区块', 'admin/block/edit', 0, '', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (78, 75, 'admin', '删除区块', 'admin/block/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (79, 75, 'admin', '区块类别', 'admin/block/category', 0, '', 0, '', 'Block category');
INSERT INTO `kite_auth_rule` VALUES (80, 75, 'admin', '删除区块分类', 'admin/block/removeCategory', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (81, 75, 'admin', '批量操作区块分类', 'admin/block/handleCategory', 0, '', 0, '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES (82, 48, 'admin', '钩子删除', 'admin/hooks/delete', 0, 'fa fa-circle-o', 0, '', 'Delete');
INSERT INTO `kite_auth_rule` VALUES (86, 5, 'admin', '评论管理', 'admin/comments/index', 1, 'fa fa-comments', 1, '', 'Comments');
INSERT INTO `kite_auth_rule` VALUES (87, 86, 'admin', '删除评论', 'admin/comments/remove', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (88, 86, 'admin', '批量操作评论', 'admin/comments/handle', 0, '', 0, '', 'Handle comments');
INSERT INTO `kite_auth_rule` VALUES (89, 1, 'admin', '系统日志', 'admin/log/index', 1, 'fa fa-history', 6, '', 'Log');
INSERT INTO `kite_auth_rule` VALUES (90, 1, 'admin', '编辑菜单', 'admin/navigation/index', 1, 'fa fa-navicon', 7, '', 'Navigation');
INSERT INTO `kite_auth_rule` VALUES (91, 90, 'admin', '菜单管理', 'admin/navigation/category', 0, '', 0, '', 'Navigation category');
INSERT INTO `kite_auth_rule` VALUES (92, 90, 'admin', '删除菜单', 'admin/navigation/removeCategory', 0, '', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (93, 90, 'admin', '批量操作菜单', 'admin/navigation/handleCategory', 0, '', 0, '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES (55, 5, 'admin', '留言反馈', 'admin/feedback/index', 1, 'fa fa-commenting-o', 0, '', 'Feedback');
INSERT INTO `kite_auth_rule` VALUES (73, 55, 'admin', '回复', 'admin/feedback/edit', 0, 'fa fa-circle-o', 0, '', 'Reply');
INSERT INTO `kite_auth_rule` VALUES (74, 55, 'admin', '删除', 'admin/feedback/remove', 0, 'fa fa-circle-o', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (83, 55, 'admin', '批量操作', 'admin/feedback/handle', 0, 'fa fa-circle-o', 0, '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES (85, 5, 'admin', 'Tags', 'admin/tags/index', 1, 'fa fa-tags', 0, '', 'Tags');
INSERT INTO `kite_auth_rule` VALUES (94, 85, 'admin', '删除', 'admin/tags/remove', 0, 'fa fa-circle-o', 0, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (95, 85, 'admin', '编辑', 'admin/tags/edit', 0, 'fa fa-circle-o', 0, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (96, 85, 'admin', '操作', 'admin/tags/handle', 0, 'fa fa-circle-o', 0, '', 'Handle');
INSERT INTO `kite_auth_rule` VALUES (97, 0, 'member', '会员中心', 'member/index/index', 1, 'fa fa-laptop', 1, '', 'Member center');
INSERT INTO `kite_auth_rule` VALUES (98, 108, 'member', '发布信息', 'member/document/create', 1, 'fa fa-edit', 2, '', 'Create');
INSERT INTO `kite_auth_rule` VALUES (99, 108, 'member', '修改信息', 'member/document/edit', 0, 'fa fa-circle-o', 3, '', 'Edit');
INSERT INTO `kite_auth_rule` VALUES (100, 108, 'member', '删除信息', 'member/document/remove', 0, 'fa fa-circle-o', 4, '', 'Remove');
INSERT INTO `kite_auth_rule` VALUES (101, 97, 'member', '账户设置', '#', 1, 'fa fa-user', 5, '', 'Account setting');
INSERT INTO `kite_auth_rule` VALUES (102, 101, 'member', '个人资料', 'member/member/profile', 1, 'fa fa-circle-o text-red', 6, '', 'Member profile');
INSERT INTO `kite_auth_rule` VALUES (103, 101, 'member', '账户绑定', 'member/member/bind', 1, 'fa fa-circle-o text-yellow', 7, '', 'Member bind');
INSERT INTO `kite_auth_rule` VALUES (104, 101, 'member', '手机绑定', 'member/member/mobileBind', 0, 'fa fa-circle-o', 8, '', 'Mobile bind');
INSERT INTO `kite_auth_rule` VALUES (105, 101, 'member', '邮箱绑定', 'member/member/emailBind', 0, 'fa fa-circle-o', 9, '', 'Email bind');
INSERT INTO `kite_auth_rule` VALUES (106, 101, 'member', '密码修改', 'member/member/password', 1, 'fa fa-circle-o text-aqua', 10, '', 'Password update');
INSERT INTO `kite_auth_rule` VALUES (107, 108, 'member', '信息列表', 'member/document/index', 1, 'fa fa-book', 0, '', 'Document');
INSERT INTO `kite_auth_rule` VALUES (108, 97, 'member', '信息管理', '#', 1, 'fa fa-laptop', 1, '', 'My document');
INSERT INTO `kite_auth_rule` VALUES (109, 101, 'member', '手机解绑', 'member/member/mobileUnbind', 0, 'fa fa-circle-o', 0, '', 'Mobile unbind');
INSERT INTO `kite_auth_rule` VALUES (110, 101, 'member', '邮箱解绑', 'member/member/emailUnbind', 0, 'fa fa-circle-o', 0, '', 'Email unbind');
INSERT INTO `kite_auth_rule` VALUES (111, 101, 'member', '头像设置', 'member/member/avatar', 0, 'fa fa-circle-o', 0, '', 'Member avatar');
INSERT INTO `kite_auth_rule` VALUES (112, 101, 'member', '创建订单', 'member/order/create', 0, 'fa fa-circle-o', 0, '', 'Create order');
INSERT INTO `kite_auth_rule` VALUES (113, 101, 'member', '我的订单', 'member/order/my', 1, 'fa fa-circle-o', 0, '', 'My order');
INSERT INTO `kite_auth_rule` VALUES (114, 101, 'member', '订单详情', 'member/order/detail', 0, 'fa fa-circle-o', 0, '', 'Detail');

-- ----------------------------
-- Table structure for kite_auth_user
-- ----------------------------
DROP TABLE IF EXISTS `kite_auth_user`;
CREATE TABLE `kite_auth_user`  (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `role_ids` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户所属角色组',
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '管理员密码',
  `phone` char(11) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '电子邮箱',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态 0 正常 1禁用',
  `score` int(11) NULL DEFAULT NULL COMMENT '积分',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '头像',
  `resume` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '个人简介',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `user_id`(`uid`) USING BTREE,
  UNIQUE INDEX `user_name`(`username`) USING BTREE,
  INDEX `created`(`create_at`) USING BTREE,
  INDEX `phone`(`phone`) USING BTREE,
  INDEX `email`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Compact;

-- ----------------------------
-- Records of kite_auth_user
-- ----------------------------
INSERT INTO `kite_auth_user` VALUES (1, '1,2,3', 'admin', '$2y$10$fs9ReKopa2MweYbKuHoKRuyJ.EF0yedROHhkac2C4YW6GIjU/oKV.', '18780221108', 'kite@kitesky.com', 0, 584, '/upload/20190627/aba796d04ef17b1862880b988a5b47d8.png', '', 1562859309, 1562859309);
INSERT INTO `kite_auth_user` VALUES (2, '3', 'kite', '$2y$10$99iZt/YBVSWHu.2wixnar.yjf8Ly1t9v337gVcaAv/1u6uBbdMTbS', '', 'kite@kitesky.com', 0, 50, '', '', 1562595397, 1562600544);

-- ----------------------------
-- Table structure for kite_block
-- ----------------------------
DROP TABLE IF EXISTS `kite_block`;
CREATE TABLE `kite_block`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) NULL DEFAULT 0 COMMENT '友情链接分类ID',
  `site_id` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '网站名称',
  `variable` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '区块变量标识',
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '内容',
  `start_time` int(11) NULL DEFAULT NULL,
  `end_time` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态： 0隐藏  1 显示',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_document_category
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_category`;
CREATE TABLE `kite_document_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '上级父ID',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `model_id` int(11) NOT NULL COMMENT '模型ID',
  `sort` int(11) NULL DEFAULT 0 COMMENT '权重排序',
  `title` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档标题',
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档关键词',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '描述',
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '文档内容',
  `list_rows` int(11) NOT NULL DEFAULT 10 COMMENT '列表显示条数',
  `list_tpl` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '栏目模板',
  `detail_tpl` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '内容模板',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 隐藏 1 显示',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_document_comments
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_comments`;
CREATE TABLE `kite_document_comments`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '评论上级ID',
  `uid` int(11) NOT NULL COMMENT '评论人mid ',
  `document_id` int(11) NOT NULL COMMENT '评论文档ID',
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '评论内容',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 隐藏 1 显示',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for kite_document_comments_like
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_comments_like`;
CREATE TABLE `kite_document_comments_like`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comments_id` int(11) NOT NULL COMMENT '文档ID',
  `like` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[顶]',
  `unlike` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[踩]',
  `ip` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '客户端IP',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_document_content
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_content`;
CREATE TABLE `kite_document_content`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT 0 COMMENT '文档分类ID',
  `site_id` int(11) NOT NULL COMMENT '内容归属站点',
  `uid` int(11) NULL DEFAULT NULL COMMENT '后台管理员发布者 UID',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档标题',
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '文档关键词',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '描述',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '文档内容',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '文档封面',
  `attach` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '文件',
  `album` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '图片集合',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 待审 1通过',
  `image_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '图片类型标识',
  `video_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '视频类型标识',
  `attach_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '附件类型标识',
  `hot_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '热门标识',
  `recommend_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '推荐标识',
  `focus_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '焦点标识',
  `top_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '置顶标识',
  `pv` int(11) NOT NULL DEFAULT 0 COMMENT '访问次数',
  `price` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '售价',
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '阅读权限限 0:游客组',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `site_id`(`site_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for kite_document_content_extra
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_content_extra`;
CREATE TABLE `kite_document_content_extra`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文档内容自定义ID',
  `document_id` int(11) NOT NULL COMMENT '文档ID',
  `type` char(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '字段内容类型',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段名称',
  `variable` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段变量',
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '字段选项原始值',
  `value` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '字段值',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `document_id`(`document_id`) USING BTREE,
  INDEX `variable`(`variable`) USING BTREE,
  INDEX `key`(`key`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_document_content_like
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_content_like`;
CREATE TABLE `kite_document_content_like`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL COMMENT '文档ID',
  `like` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[顶]',
  `unlike` tinyint(1) NOT NULL DEFAULT 0 COMMENT '[踩]',
  `ip` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '客户端IP',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_document_field
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_field`;
CREATE TABLE `kite_document_field`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '字段归类',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段名称',
  `variable` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '字段列名',
  `type` char(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段类型',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '权重排序',
  `is_require` tinyint(1) NULL DEFAULT 0 COMMENT '0 正常 1必填',
  `is_filter` tinyint(1) NULL DEFAULT NULL COMMENT '0正常 1筛选条件',
  `option` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '字段内容选项',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '描述',
  `regular` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '正则表达式',
  `msg` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '验证失败提示语',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_document_model
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_model`;
CREATE TABLE `kite_document_model`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `site_id` int(11) NOT NULL COMMENT '模型归属站点',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模型名称',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序 越小越靠前',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_document_model_field
-- ----------------------------
DROP TABLE IF EXISTS `kite_document_model_field`;
CREATE TABLE `kite_document_model_field`  (
  `model_id` int(11) NOT NULL COMMENT '模型ID',
  `field_id` int(11) NOT NULL COMMENT '字段ID',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序'
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kite_feedback
-- ----------------------------
DROP TABLE IF EXISTS `kite_feedback`;
CREATE TABLE `kite_feedback`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `uid` int(11) NULL DEFAULT NULL COMMENT '评论人mid ',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '反馈标题',
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '反馈者姓名',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '反馈者邮箱',
  `phone` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '反馈者电话',
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '反馈内容',
  `reply` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 隐藏 1 显示',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_hooks
-- ----------------------------
DROP TABLE IF EXISTS `kite_hooks`;
CREATE TABLE `kite_hooks`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '描述',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '类型',
  `addons` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 \'，\'分割',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_hooks
-- ----------------------------
INSERT INTO `kite_hooks` VALUES (1, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, '', 1, 1561561552, 1561561552);
INSERT INTO `kite_hooks` VALUES (2, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, '', 1, 1561561552, 1561561552);
INSERT INTO `kite_hooks` VALUES (3, 'AdminIndex', '首页小格子个性化显示', 2, 'demo', 1, 1561561552, 1562602365);

-- ----------------------------
-- Table structure for kite_language
-- ----------------------------
DROP TABLE IF EXISTS `kite_language`;
CREATE TABLE `kite_language`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '语言名称',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '权重排序 越大越靠后',
  `designation` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`, `name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_language
-- ----------------------------
INSERT INTO `kite_language` VALUES (1, 'zh-cn', NULL, 1, '简体中文(中国) ');
INSERT INTO `kite_language` VALUES (2, 'en-us', NULL, 2, '英语(美国) ');

-- ----------------------------
-- Table structure for kite_link
-- ----------------------------
DROP TABLE IF EXISTS `kite_link`;
CREATE TABLE `kite_link`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) NULL DEFAULT 0 COMMENT '友情链接分类ID',
  `site_id` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '网站名称',
  `url` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '网站地址',
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'logo地址',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态： 0隐藏  1 显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_log
-- ----------------------------
DROP TABLE IF EXISTS `kite_log`;
CREATE TABLE `kite_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `site_id` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '操作类型',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '操作内容',
  `ip` char(32) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `create_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_log
-- ----------------------------
INSERT INTO `kite_log` VALUES (1, 1, NULL, NULL, NULL, '171.217.162.33', 1572339187);
INSERT INTO `kite_log` VALUES (2, 1, NULL, NULL, NULL, '183.192.37.142', 1572339190);
INSERT INTO `kite_log` VALUES (3, 1, NULL, NULL, NULL, '124.204.43.26', 1572339462);

-- ----------------------------
-- Table structure for kite_message
-- ----------------------------
DROP TABLE IF EXISTS `kite_message`;
CREATE TABLE `kite_message`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '信息编号',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `type` tinyint(1) NOT NULL COMMENT '信息类型 1 系统消息 2 短信 3 邮件',
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '信息标题',
  `body` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '信息内容',
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '动态码',
  `mid` int(11) NULL DEFAULT NULL COMMENT '系统消息接收人mid',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '邮件消息接收人email',
  `phone` char(11) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '短信接收人手机号码',
  `send_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT ' success 发送成功  fail 发送失败',
  `send_error` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '错误代码',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 正常 1 失效',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_navigation
-- ----------------------------
DROP TABLE IF EXISTS `kite_navigation`;
CREATE TABLE `kite_navigation`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT 0 COMMENT '导航分类ID',
  `cat_id` int(11) NOT NULL DEFAULT 0 COMMENT '文章栏目分类ID',
  `pid` int(11) NOT NULL DEFAULT 0,
  `site_id` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '菜单名称',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '菜单URL',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 栏目 2自定义URL',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_navigation
-- ----------------------------
INSERT INTO `kite_navigation` VALUES (1, 1, 0, 0, '1', 'blog', 'http://www.kitesky.com', 2, 0, 1572339699, 1572339699);

-- ----------------------------
-- Table structure for kite_order
-- ----------------------------
DROP TABLE IF EXISTS `kite_order`;
CREATE TABLE `kite_order`  (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `uid` int(11) NOT NULL COMMENT '购买者UID',
  `document_id` int(11) NOT NULL COMMENT '商品ID',
  `trade_no` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '支付交易单号',
  `out_trade_no` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '订单编号',
  `total_amount` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `payment_type` tinyint(1) NULL DEFAULT 0 COMMENT '0 未支付 1支付宝 2微信',
  `status` int(1) NULL DEFAULT 0 COMMENT '0 待付款 1 已经付款 2退款 ',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for kite_score
-- ----------------------------
DROP TABLE IF EXISTS `kite_score`;
CREATE TABLE `kite_score`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '积分记录编号',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `sum` int(11) NOT NULL COMMENT '剩余总数',
  `score` int(11) NULL DEFAULT 0 COMMENT '积分数量',
  `source` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '获取原因',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for kite_site
-- ----------------------------
DROP TABLE IF EXISTS `kite_site`;
CREATE TABLE `kite_site`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '网站SID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '站点名称',
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT 'LOGO',
  `domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '站点绑定域名',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序 越小越靠前',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '站点标题',
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '站点关键词',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '站点描述',
  `theme` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '模板名称',
  `copyright` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '版权信息',
  `icp` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ICP备案号',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 正常 1关闭',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `site_name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_site
-- ----------------------------
INSERT INTO `kite_site` VALUES (1, '默认站点', '/upload/20190702/61126f8e5831cadc75aacf845122c8a8.png', 'http://f.19981.com', 0, '默认站点', '默认站点', '默认站点', 'default', 'Copyright © 2019 19981.com. All Right Reserved.', '蜀ICP备12004586号-2', 0, 1562859309, 1562945997);
INSERT INTO `kite_site` VALUES (2, '测试站点', '', 'http://doc.19981.com', 0, '', '', '', 'clumb', '', '', 0, 1562135923, 1562135923);

-- ----------------------------
-- Table structure for kite_site_config
-- ----------------------------
DROP TABLE IF EXISTS `kite_site_config`;
CREATE TABLE `kite_site_config`  (
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `k` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '键名',
  `v` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '键值',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  INDEX `key`(`k`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_site_config
-- ----------------------------
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_document', '0', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'register_score', '100', 1529499198, 1529499198);
INSERT INTO `kite_site_config` VALUES (1, 'login_score', '10', 1529499387, 1529499387);
INSERT INTO `kite_site_config` VALUES (1, 'publish_score', '10', 1529499540, 1529499540);
INSERT INTO `kite_site_config` VALUES (1, 'comment_score', '-5', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'upload_type', 'local', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'upload_size', 'jpg,png,gif', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'upload_ext', 'jpg,png,gif', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'upload_path', 'upload', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'alioss_key', '4H5C4jQbxBAsbwye1', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'alioss_secret', 'U5Be9VLZCpy8oCo7sTQSCq806swqGV', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'alioss_endpoint', 'oss-cn-shenzhen.aliyuncs.com', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'alioss_bucket', 'kitesky', 1529499606, 1529499606);
INSERT INTO `kite_site_config` VALUES (1, 'qiniu_ak', '9VWzf1jiS3gEALBi_XtwELHaHzHJIeCXE5W4KtJt', 1530071701, 1530071701);
INSERT INTO `kite_site_config` VALUES (1, 'qiniu_sk', 'TGNd21xwf-yHGWn3FwN37fkRWpOzzMhXC5jEfgr8', 1530071701, 1530071701);
INSERT INTO `kite_site_config` VALUES (1, 'qiniu_bucket', 'kitesky', 1530071701, 1530071701);
INSERT INTO `kite_site_config` VALUES (1, 'qiniu_domain', 'http://onxr8mt8y.bkt.clouddn.com', 1530071701, 1530071701);
INSERT INTO `kite_site_config` VALUES (1, 'link_category', '[{\"name\":\"文字链接\",\"sort\":\"1\",\"id\":1},{\"name\":\"22\",\"sort\":\"22\",\"id\":2},{\"name\":\"333\",\"sort\":\"333\",\"id\":3}]', 1531141510, 1531141510);
INSERT INTO `kite_site_config` VALUES (1, 'slider_category', '[]', 1531147967, 1531147967);
INSERT INTO `kite_site_config` VALUES (1, 'field_category', '[{\"name\":\"模板类\",\"sort\":\"1\",\"id\":1}]', 1531147967, 1531147967);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useZh', '0', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useImgBg', '0', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_fontSize', '24', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_imageH', '40', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_imageW', '200', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_length', '4', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_register', '0', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_login', '0', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_comment', '0', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_feedback', '0', 1531213657, 1531213657);
INSERT INTO `kite_site_config` VALUES (1, 'water_logo', '/public/static/admin/dist/img/nopic.png', 1531213845, 1531213845);
INSERT INTO `kite_site_config` VALUES (1, 'water_position', '9', 1531213845, 1531213845);
INSERT INTO `kite_site_config` VALUES (1, 'water_quality', '80', 1531213845, 1531213845);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1531213845, 1531213845);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useCurve', '0', 1531217269, 1531217269);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useNoise', '0', 1531217269, 1531217269);
INSERT INTO `kite_site_config` VALUES (1, 'sms_type', 'dysms', 1531371550, 1531371550);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', '', 1531371550, 1531371550);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', '', 1531371550, 1531371550);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '', 1531371550, 1531371550);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', '', 1531371550, 1531371550);
INSERT INTO `kite_site_config` VALUES (1, 'email_host', 'smtp.163.com', 1531378668, 1531378668);
INSERT INTO `kite_site_config` VALUES (1, 'email_port', '465132', 1531378668, 1531378668);
INSERT INTO `kite_site_config` VALUES (1, 'email_username', 'nsssh', 1531378668, 1531378668);
INSERT INTO `kite_site_config` VALUES (1, 'email_password', 'wangzheng', 1531378668, 1531378668);
INSERT INTO `kite_site_config` VALUES (1, 'member_register', '0', 1531378916, 1531378916);
INSERT INTO `kite_site_config` VALUES (1, 'email_from_email', 'nsssh@163.com', 1531383066, 1531383066);
INSERT INTO `kite_site_config` VALUES (1, 'email_from_name', 'KiteCMS', 1531383066, 1531383066);
INSERT INTO `kite_site_config` VALUES (1, 'block_category', '[]', 1531981998, 1531981998);
INSERT INTO `kite_site_config` VALUES (1, 'upload_image_ext', 'jpg,png,gif', 1532327020, 1532327020);
INSERT INTO `kite_site_config` VALUES (1, 'upload_image_size', '2048', 1532327020, 1532327020);
INSERT INTO `kite_site_config` VALUES (1, 'upload_video_ext', 'rm,rmvb,wmv,3gp,mp4,mov,avi,flv', 1532327020, 1532327020);
INSERT INTO `kite_site_config` VALUES (1, 'upload_video_size', '10240', 1532327020, 1532327020);
INSERT INTO `kite_site_config` VALUES (1, 'upload_attach_ext', 'doc,xls,rar,zip,7z', 1532327020, 1532327020);
INSERT INTO `kite_site_config` VALUES (1, 'upload_attach_size', '10240', 1532327020, 1532327020);
INSERT INTO `kite_site_config` VALUES (1, 'navigation_category', '[{\"name\":\"顶部导航\",\"sort\":\"1\",\"id\":1},{\"name\":\"底部导航\",\"sort\":\"2\",\"id\":2}]', 1532675827, 1532675827);
INSERT INTO `kite_site_config` VALUES (1, 'email_code_template', '尊敬的会员${username} ，您本次的验证码为：${code} ，验证码在5分钟内有效。', 1532856848, 1532856848);
INSERT INTO `kite_site_config` VALUES (1, 'email_register_template', '尊敬的会员${username} ，您已经成功注册，请谨记您的用户名及密码。', 1532856848, 1532856848);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1532856848, 1532856848);
INSERT INTO `kite_site_config` VALUES (2, 'field_category', '[{\"name\":\"cms\",\"sort\":\"1\",\"id\":1},{\"name\":\"产品\",\"sort\":\"2\",\"id\":2}]', 1541487138, 1541487138);
INSERT INTO `kite_site_config` VALUES (2, 'slider_category', NULL, 1548224151, 1548224151);
INSERT INTO `kite_site_config` VALUES (2, 'link_category', '[{\"name\":\"底部链接\",\"sort\":\"1\",\"id\":1}]', 1548224152, 1548224152);
INSERT INTO `kite_site_config` VALUES (2, 'block_category', '[{\"name\":\"TEST\",\"sort\":\"1\",\"id\":1}]', 1548224152, 1548224152);
INSERT INTO `kite_site_config` VALUES (2, 'register_score', '100', 1548224155, 1548224155);
INSERT INTO `kite_site_config` VALUES (2, 'login_score', '1', 1548224155, 1548224155);
INSERT INTO `kite_site_config` VALUES (2, 'publish_score', '10', 1548224155, 1548224155);
INSERT INTO `kite_site_config` VALUES (2, 'comment_score', '5', 1548224156, 1548224156);
INSERT INTO `kite_site_config` VALUES (2, 'email_host', 'smtp.163.com', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'email_port', '465', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'email_username', 'kite365', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'email_password', 'wangzheng', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'email_from_email', 'kite@kitesky.com', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'email_from_name', 'KiteCMS', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'email_code_template', '尊敬的会员${username} ，您本次的验证码为：${code} ，验证码在5分钟内有效。', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'email_register_template', '尊敬的会员${username} ，您已经成功注册，请谨记您的用户名及密码。', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'send_email_register', '0', 1548224167, 1548224167);
INSERT INTO `kite_site_config` VALUES (2, 'upload_type', 'local', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'upload_image_ext', 'jpg,png,gif', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'upload_image_size', '2040000', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'upload_video_ext', 'rm,rmvb,wmv,3gp,mp4,mov,avi,flv', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'upload_video_size', '2040000', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'upload_attach_ext', 'doc,xls,rar,zip', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'upload_attach_size', '2040000', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'upload_path', 'upload', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'alioss_key', '4H5C4jQbxBAsbwye', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'alioss_secret', 'U5Be9VLZCpy8oCo7sTQSCq806swqGV', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'alioss_endpoint', 'oss-cn-shenzhen.aliyuncs.com', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'alioss_bucket', 'kitesky', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'qiniu_ak', '9VWzf1jiS3gEALBi_XtwELHaHzHJIeCXE5W4KtJt', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'qiniu_sk', 'yHGWn3FwN37fkRWpOzzMhXC5jEfgr8', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'qiniu_bucket', 'kitesky', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'qiniu_domain', 'http://onxr8mt8y.bkt.clouddn.com', 1548224168, 1548224168);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useZh', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useImgBg', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_fontSize', '14', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_imageH', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_imageW', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_length', '6', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useCurve', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useNoise', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_member_register', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_member_login', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_publish_document', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_publish_comment', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_publish_feedback', '0', 1548224169, 1548224169);
INSERT INTO `kite_site_config` VALUES (2, 'navigation_category', '[{\"name\":\"顶部导航\",\"sort\":\"\",\"id\":1}]', 1548224170, 1548224170);
INSERT INTO `kite_site_config` VALUES (4, 'field_category', NULL, 1551153424, 1551153424);
INSERT INTO `kite_site_config` VALUES (4, 'link_category', NULL, 1551153427, 1551153427);
INSERT INTO `kite_site_config` VALUES (4, 'slider_category', NULL, 1551153466, 1551153466);
INSERT INTO `kite_site_config` VALUES (4, 'block_category', NULL, 1551153468, 1551153468);
INSERT INTO `kite_site_config` VALUES (4, 'navigation_category', NULL, 1551153507, 1551153507);
INSERT INTO `kite_site_config` VALUES (3, 'field_category', NULL, 1551343953, 1551343953);
INSERT INTO `kite_site_config` VALUES (3, 'navigation_category', NULL, 1553520030, 1553520030);
INSERT INTO `kite_site_config` VALUES (2, 'sms_type', 'dysms', 1553582312, 1553582312);
INSERT INTO `kite_site_config` VALUES (2, 'ali_access_key', 'AccessKey ID', 1553582312, 1553582312);
INSERT INTO `kite_site_config` VALUES (2, 'ali_access_key_secret', 'Access Key Secret', 1553582312, 1553582312);
INSERT INTO `kite_site_config` VALUES (2, 'ali_sign_name', '19981.com', 1553582312, 1553582312);
INSERT INTO `kite_site_config` VALUES (2, 'ali_template_code', 'SMS_1234', 1553582312, 1553582312);
INSERT INTO `kite_site_config` VALUES (2, 'water_logo', '/public/static/admin/dist/img/nopic.png', 1553582314, 1553582314);
INSERT INTO `kite_site_config` VALUES (2, 'water_position', '9', 1553582315, 1553582315);
INSERT INTO `kite_site_config` VALUES (2, 'water_quality', '80', 1553582315, 1553582315);
INSERT INTO `kite_site_config` VALUES (2, 'water_status', '0', 1553582315, 1553582315);
INSERT INTO `kite_site_config` VALUES (1, 'ali_appid', '2016032101230497', 1561704850, 1561704850);
INSERT INTO `kite_site_config` VALUES (1, 'ali_private_key', 'MIIEowIBAAKCAQEAprJwD6gqdUBAxo6exvMuVAihh2yinkikfo81WUFQtUf3IpxBQlj62Mmq6CPdmrbjIfbVCQds+gnVDU2jwya1LqxDSaZDkd4M8KI1alN8+YResMu4KDmkiApV33lHS2OzFseFJ2/nbdKfKVSVvhz07t+ma/apysTXKwAj8Aeqnx/ElLgtdPEBrEtqhNTMzVcbY1MA4f5qsgDyI/VHj3lr6CJe2w/OHXUwkzusejwo32rdRo74vmIaRpn7VfWsTuFf5p4zsXxB9nkFvvNWK8qBrJVSu9BIe6XXuekb1fWyD1Y029BYUkkA1SBv2qhh7pTO0hftaupoqwjiUXbATBNhKQIDAQABAoIBAQCFtQSrmlCMAcCzQskEvVQAtXeS33FEkrHWjdnVwM0SyymQilLR+/sg3gmG8BW1HlLrEEhqWJlxqWdJp2fehXK4gBXswj7ahohMj19W7KaGoUUufAk3wpyVPe0JNgcYkly4vcqxClJQRavChAUkG6fe3mdnm88vRFnuNMueoRebT51cPAP4on3x6LXtphBQJopKy6oCIkiElDvtoIMShgjK4z+D2gPXIpn0aLJK943YG2FdPdAB+y/us2ovTsonxG2V8Ky39j4Q4EOp2an3t/PEVsitKQpn22bpReQJmRL6R+oUhMh+5RkFOBBdtpGTPWFVjOk7RFbs5Htv8FNB+cwBAoGBANTUDfsWY5nNvVHEZifGjoLiLAHy15zmYrEH92b/t86kOZjNtLPyzEmjimvo8AP01RLmRIvvudhzzyzN9gmgp9J3NAFLMhIuHTcJvq4+odgzeRaBr74vtWe4OMFVx9aou2iFt9Lid79EGbJu/v4+Cq3Xo4DDDROxNAXR+3N1aXSpAoGBAMiC1XNslxqZsf57Nd2jkDu8FYh2mUn5vjBs7HawJ94efjizXapScgCe2P4fjhbm9zPNnu15F0EHt6iYcdHMu3yQVudYZB1Adxieb9xzvHLjlHNWAlQ5zepXiuqgD/PcZ7DfVlS1meyCxsAennW0X1khJoGCEh8c43H+kxWLGdiBAoGAQJDcdPID4WMjLi0w+JwqG9bVlvm/I6BZDG/oRF0LvCriNlMhoP3lr+lnUvll5y1dftBQt0tQzDPGBEevfpg4kYcMReA0HoPS5SGVsXLa0qY68/MAB+idVgvzW+PULnEd+cWnUNjXjzTvvswhm6vivX6P4b5Kt1CpAaMEb7CM/5ECgYBNhAbw8HIaHoDpWMiiPrFr5nKMpwzrxFj6b6Ga4M8I19EEKpNzXRwlkUNiOuC7id7XcA6Yz89lnI4r54NZEEULCuIN3eYWSO3B3r5wA24/HCwvynhsB0zL47wYqHiCVhrgDfdaGDrBbG1ZqHyqFGkoE+DHAHnw/UIQt9I06em8AQKBgFI8MccVEkw6Up+gw8qj+YJo0mx5M2gKT7B4oNO9VMV/CUpAxG22kK+E3qsmTRDKAc+O7NERf7XXwJRAhl54OJcNciptd+tm85E0weMb5/QDkzNzSjvMaQSIA78FpipU28qksUQ7cMJ251vwTdrWset/35Va6s3ek6B08ZFhqwTS', 1561704850, 1561704850);
INSERT INTO `kite_site_config` VALUES (1, 'ali_public_key', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIICCgKCAQEAjqD324tpbqDiplpq1kb7rB7KsRFZEU1KjvPtjhQSj4BydRyUgOrVUpS1XGUVNJfZ/Je1ZZglgGEey3wvtnUrNIXzs7QWjxq4upgwwDHXFxGV7nurnAstFgYsrIZAyskDmfUCMgLhhBiIaCZ+LrJY/TRrpMhpKs+ANEoug1zRpnkctGDypzJOMvzCYtTpLXCfq05AW29vpN8J/Us8JkCCU1QY0snHuBTJ+WfUH2+TJXhl87dwFifYyvkKTqOeDciG+jBHF9e30q0mwf6gdluDq04jm+QDNU6BIhgVgcPmB97XowNevwq54aNPlLkfnE9kkbe2HeMMyT4WtzN2y8SIYwIDAQAB', 1561704850, 1561704850);
INSERT INTO `kite_site_config` VALUES (1, 'ali_notify_url', 'http://dev.kitesky.com/member/order/aliNotify', 1561704850, 1561704850);
INSERT INTO `kite_site_config` VALUES (1, 'ali_return_url', 'http://dev.kitesky.com/member/order/return', 1561704850, 1561704850);
INSERT INTO `kite_site_config` VALUES (2, 'ali_appid', '', 1562046555, 1562046555);
INSERT INTO `kite_site_config` VALUES (2, 'ali_private_key', '', 1562046555, 1562046555);
INSERT INTO `kite_site_config` VALUES (2, 'ali_public_key', '', 1562046555, 1562046555);
INSERT INTO `kite_site_config` VALUES (2, 'ali_notify_url', '', 1562046555, 1562046555);
INSERT INTO `kite_site_config` VALUES (2, 'ali_return_url', '', 1562046555, 1562046555);
INSERT INTO `kite_site_config` VALUES (2, 'member_allow_register', '1', 1562063662, 1562063662);
INSERT INTO `kite_site_config` VALUES (2, 'member_allow_comment', '1', 1562063662, 1562063662);
INSERT INTO `kite_site_config` VALUES (2, 'list_rows', '15', 1562065361, 1562065361);
INSERT INTO `kite_site_config` VALUES (2, 'list_row', '15', 1562067176, 1562067176);
INSERT INTO `kite_site_config` VALUES (1, 'member_allow_register', '1', 1562122709, 1562122709);
INSERT INTO `kite_site_config` VALUES (1, 'member_allow_comment', '1', 1562122709, 1562122709);
INSERT INTO `kite_site_config` VALUES (1, 'list_rows', '15', 1562122709, 1562122709);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562381353, 1562381353);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562381354, 1562381354);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562381354, 1562381354);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562381354, 1562381354);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562381354, 1562381354);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562381355, 1562381355);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562381367, 1562381367);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562381367, 1562381367);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562381367, 1562381367);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562381367, 1562381367);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562381369, 1562381369);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562381369, 1562381369);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562381369, 1562381369);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562381369, 1562381369);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562381369, 1562381369);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562381370, 1562381370);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562381370, 1562381370);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562381370, 1562381370);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562381370, 1562381370);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562381370, 1562381370);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562483152, 1562483152);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562483152, 1562483152);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562483152, 1562483152);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562483152, 1562483152);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562483161, 1562483161);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562483172, 1562483172);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562483172, 1562483172);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562483172, 1562483172);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562483172, 1562483172);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562483173, 1562483173);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useZh', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useImgBg', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useCurve', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useNoise', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_register', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_login', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_document', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_comment', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_feedback', '0', 1562488885, 1562488885);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562488886, 1562488886);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562560385, 1562560385);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562560385, 1562560385);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562560385, 1562560385);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562560385, 1562560385);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562560401, 1562560401);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562560401, 1562560401);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562560401, 1562560401);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562560401, 1562560401);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562562599, 1562562599);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562562599, 1562562599);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562562599, 1562562599);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562562599, 1562562599);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562562608, 1562562608);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562562610, 1562562610);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562562610, 1562562610);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562562610, 1562562610);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562562610, 1562562610);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562562612, 1562562612);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useZh', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useImgBg', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useCurve', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useNoise', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_register', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_login', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_document', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_comment', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_feedback', '0', 1562562613, 1562562613);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562562614, 1562562614);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562562614, 1562562614);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562562614, 1562562614);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562562614, 1562562614);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562562615, 1562562615);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562562617, 1562562617);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562562617, 1562562617);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562562617, 1562562617);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562562617, 1562562617);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useZh', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useImgBg', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useCurve', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useNoise', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_register', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_login', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_document', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_comment', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_feedback', '0', 1562562652, 1562562652);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562562653, 1562562653);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562562653, 1562562653);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562562653, 1562562653);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562562653, 1562562653);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562562654, 1562562654);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562562655, 1562562655);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562562655, 1562562655);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562562655, 1562562655);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562562655, 1562562655);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562562686, 1562562686);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562562686, 1562562686);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562562686, 1562562686);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562562686, 1562562686);
INSERT INTO `kite_site_config` VALUES (1, 'wx_appid', '1', 1562562711, 1562562711);
INSERT INTO `kite_site_config` VALUES (1, 'wx_mch_id', '2', 1562562711, 1562562711);
INSERT INTO `kite_site_config` VALUES (1, 'wx_notify_url', '3', 1562562711, 1562562711);
INSERT INTO `kite_site_config` VALUES (1, 'wx_key', '4', 1562562711, 1562562711);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562562979, 1562562979);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562562979, 1562562979);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562587516, 1562587516);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562587516, 1562587516);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562587516, 1562587516);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562587516, 1562587516);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562587517, 1562587517);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562587517, 1562587517);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562587517, 1562587517);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562587517, 1562587517);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562587518, 1562587518);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562587552, 1562587552);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useZh', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useImgBg', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useCurve', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useNoise', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_register', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_login', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_document', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_comment', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_feedback', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562587554, 1562587554);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562587557, 1562587557);
INSERT INTO `kite_site_config` VALUES (0, 'field_category', NULL, 1562597748, 1562597748);
INSERT INTO `kite_site_config` VALUES (0, 'member_allow_register', '1', 1562597917, 1562597917);
INSERT INTO `kite_site_config` VALUES (0, 'member_allow_comment', '1', 1562597917, 1562597917);
INSERT INTO `kite_site_config` VALUES (0, 'list_rows', '15', 1562597917, 1562597917);
INSERT INTO `kite_site_config` VALUES (0, 'navigation_category', NULL, 1562597919, 1562597919);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562599086, 1562599086);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562599086, 1562599086);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562599086, 1562599086);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562599086, 1562599086);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562599086, 1562599086);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useZh', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useImgBg', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useCurve', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useNoise', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_register', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_login', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_document', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_comment', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_feedback', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562599087, 1562599087);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useZh', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useImgBg', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_imageH', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_imageW', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useCurve', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_useNoise', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_member_register', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_member_login', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_publish_document', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_publish_comment', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'captcha_publish_feedback', '0', 1562603284, 1562603284);
INSERT INTO `kite_site_config` VALUES (2, 'ali_appid', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'ali_private_key', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'ali_public_key', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'ali_notify_url', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'ali_return_url', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'wx_appid', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'wx_mch_id', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'wx_notify_url', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'wx_key', '', 1562603286, 1562603286);
INSERT INTO `kite_site_config` VALUES (2, 'send_email_register', '0', 1562603287, 1562603287);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562603290, 1562603290);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562769736, 1562769736);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useZh', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useImgBg', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useCurve', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_useNoise', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_register', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_member_login', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_document', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_comment', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'captcha_publish_feedback', '0', 1562769737, 1562769737);
INSERT INTO `kite_site_config` VALUES (1, 'send_email_register', '0', 1562769740, 1562769740);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562769740, 1562769740);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562769740, 1562769740);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562769740, 1562769740);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562769740, 1562769740);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key', 'AccessKey ID', 1562769741, 1562769741);
INSERT INTO `kite_site_config` VALUES (1, 'ali_access_key_secret', 'Access Key Secret', 1562769741, 1562769741);
INSERT INTO `kite_site_config` VALUES (1, 'ali_sign_name', '19981.com', 1562769741, 1562769741);
INSERT INTO `kite_site_config` VALUES (1, 'ali_template_code', 'SMS_1234', 1562769741, 1562769741);
INSERT INTO `kite_site_config` VALUES (1, 'water_status', '0', 1562769741, 1562769741);

-- ----------------------------
-- Table structure for kite_site_file
-- ----------------------------
DROP TABLE IF EXISTS `kite_site_file`;
CREATE TABLE `kite_site_file`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `upload_type` char(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '上传方式',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '图片原始名称',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '图片上传后生成名字',
  `ext` char(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '图片后缀',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '图片URL',
  `thumb` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '本地生成缩略图记录 多个用逗号隔开。方便以后清理',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 上传未被引用; 1 上传后被引用',
  `create_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_slider
-- ----------------------------
DROP TABLE IF EXISTS `kite_slider`;
CREATE TABLE `kite_slider`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) NULL DEFAULT 0 COMMENT '友情链接分类ID',
  `site_id` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '网站编号',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '网站名称',
  `url` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '网站地址',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'logo地址',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '描述内容',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态： 0隐藏  1 显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kite_tags
-- ----------------------------
DROP TABLE IF EXISTS `kite_tags`;
CREATE TABLE `kite_tags`  (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Tag ID',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `tag_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Tag 名称',
  `count` int(11) NULL DEFAULT 0 COMMENT 'Tag引用次数',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`tag_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for kite_tags_mapping
-- ----------------------------
DROP TABLE IF EXISTS `kite_tags_mapping`;
CREATE TABLE `kite_tags_mapping`  (
  `tag_id` int(11) NOT NULL,
  `document_id` int(11) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for kite_user_nav
-- ----------------------------
DROP TABLE IF EXISTS `kite_user_nav`;
CREATE TABLE `kite_user_nav`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `url` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `menu` tinyint(1) NULL DEFAULT 0 COMMENT '是否为菜单0 否 1是',
  `icon` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'fa fa-circle-o' COMMENT '图标',
  `sort` int(11) NULL DEFAULT 0 COMMENT '权重排序',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注说明',
  `lang_var` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '语言表示',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `permission_url`(`url`) USING BTREE,
  INDEX `lang_var`(`lang_var`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 19 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kite_user_nav
-- ----------------------------
INSERT INTO `kite_user_nav` VALUES (1, 0, '信息管理', '#', 1, 'fa fa-laptop', 1, '', 'My document');
INSERT INTO `kite_user_nav` VALUES (2, 1, '发布信息', 'member/document/create', 1, 'fa fa-edit', 2, '', 'Publish document');
INSERT INTO `kite_user_nav` VALUES (3, 1, '修改信息', 'member/document/edit', 0, 'fa fa-circle-o', 3, '', 'Edit document');
INSERT INTO `kite_user_nav` VALUES (4, 1, '删除信息', 'member/document/remove', 0, 'fa fa-circle-o', 4, '', 'Remove document');
INSERT INTO `kite_user_nav` VALUES (5, 0, '账户设置', '#', 1, 'fa fa-user', 5, '', 'Account setting');
INSERT INTO `kite_user_nav` VALUES (6, 5, '个人资料', 'member/member/profile', 1, 'fa fa-circle-o text-red', 6, '', 'Member profile');
INSERT INTO `kite_user_nav` VALUES (7, 5, '账户绑定', 'member/member/bind', 1, 'fa fa-circle-o text-yellow', 7, '', 'Member bind');
INSERT INTO `kite_user_nav` VALUES (8, 5, '手机绑定', 'member/member/mobileBind', 0, 'fa fa-circle-o', 8, '', 'Mobile bind');
INSERT INTO `kite_user_nav` VALUES (9, 5, '邮箱绑定', 'member/member/emailBind', 0, 'fa fa-circle-o', 9, '', 'Email bind');
INSERT INTO `kite_user_nav` VALUES (10, 5, '密码修改', 'member/member/password', 1, 'fa fa-circle-o text-aqua', 10, '', 'Password update');
INSERT INTO `kite_user_nav` VALUES (11, 1, '信息列表', 'member/document/index', 1, 'fa fa-book', 0, '', 'Document list');
INSERT INTO `kite_user_nav` VALUES (12, 0, '会员中心', 'member/index/index', 0, 'fa fa-circle-o', 0, '', 'Member index');
INSERT INTO `kite_user_nav` VALUES (13, 5, '手机解绑', 'member/member/mobileUnbind', 0, 'fa fa-circle-o', 0, '', 'Mobile unbind');
INSERT INTO `kite_user_nav` VALUES (14, 5, '邮箱解绑', 'member/member/emailUnbind', 0, 'fa fa-circle-o', 0, '', 'Email unbind');
INSERT INTO `kite_user_nav` VALUES (15, 5, '头像设置', 'member/member/avatar', 0, 'fa fa-circle-o', 0, '', 'Member avatar');
INSERT INTO `kite_user_nav` VALUES (16, 0, '创建订单', 'member/order/create', 0, 'fa fa-circle-o', 0, '', 'Create');
INSERT INTO `kite_user_nav` VALUES (17, 5, '我的订单', 'member/order/my', 1, 'fa fa-circle-o', 0, '', 'My order');
INSERT INTO `kite_user_nav` VALUES (18, 0, '订单详情', 'member/order/detail', 0, 'fa fa-circle-o', 0, '', 'Detail');

SET FOREIGN_KEY_CHECKS = 1;
