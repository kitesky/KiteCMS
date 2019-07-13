ALTER TABLE `kite_document_category`
ADD COLUMN `list_rows`  int(11) NOT NULL DEFAULT 10 COMMENT '列表显示条数' AFTER `content`;

ALTER TABLE `kite_document_content`
ADD COLUMN `attach`  varchar(255) NULL DEFAULT '' COMMENT '文件' AFTER `image`;

-- ----------------------------
-- Table structure for kite_feedback
-- ----------------------------
DROP TABLE IF EXISTS `kite_feedback`;
CREATE TABLE `kite_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `site_id` int(11) NOT NULL COMMENT '网站ID',
  `uid` int(11) DEFAULT NULL COMMENT '评论人mid ',
  `title` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '反馈标题',
  `username` varchar(64) COLLATE utf8_bin DEFAULT '' COMMENT '反馈者姓名',
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '反馈者邮箱',
  `phone` varchar(64) COLLATE utf8_bin DEFAULT '' COMMENT '反馈者电话',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '反馈内容',
  `reply` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 隐藏 1 显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- ----------------------------
-- Table structure for kite_tags
-- ----------------------------
DROP TABLE IF EXISTS `kite_tags`;
CREATE TABLE `kite_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Tag ID',
  `site_id` int(11) NOT NULL COMMENT '站点ID',
  `tag_name` varchar(64) NOT NULL COMMENT 'Tag 名称',
  `count` int(11) DEFAULT 0 COMMENT 'Tag引用次数',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for kite_tags_mapping
-- ----------------------------
DROP TABLE IF EXISTS `kite_tags_mapping`;
CREATE TABLE `kite_tags_mapping` (
  `tag_id` int(11) NOT NULL,
  `document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `kite_auth_user`
ADD COLUMN `resume`  varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '个人简介' AFTER `avatar`;


ALTER TABLE `kite_site`
DROP COLUMN `city_id`;

ALTER TABLE `kite_site`
DROP COLUMN `alias`;

ALTER TABLE `kite_site`
DROP COLUMN `timezone`;

ALTER TABLE `kite_site`
DROP COLUMN `hot`;


ALTER TABLE `kite_language`
ENGINE=MyISAM;

ALTER TABLE `kite_block`
ENGINE=MyISAM;

ALTER TABLE `kite_log`
ENGINE=MyISAM;

ALTER TABLE `kite_document_field`
ENGINE=MyISAM;

ALTER TABLE `kite_document_model`
ENGINE=MyISAM;

ALTER TABLE `kite_document_model_field`
ENGINE=MyISAM;

ALTER TABLE `kite_document_content_like`
ENGINE=MyISAM;

ALTER TABLE `kite_document_content_extra`
ENGINE=MyISAM;

ALTER TABLE `kite_document_comments_like`
ENGINE=MyISAM;

ALTER TABLE `kite_link`
ENGINE=MyISAM;

ALTER TABLE `kite_message`
ENGINE=MyISAM;

ALTER TABLE `kite_navigation`
ENGINE=MyISAM;

ALTER TABLE `kite_site`
ENGINE=MyISAM;

ALTER TABLE `kite_site_config`
ENGINE=MyISAM;

ALTER TABLE `kite_site_file`
ENGINE=MyISAM;

ALTER TABLE `kite_slider`
ENGINE=MyISAM;

ALTER TABLE `kite_user_nav`
ENGINE=MyISAM;

ALTER TABLE `kite_document_category`
ENGINE=MyISAM;

ALTER TABLE `kite_auth_rule`
ENGINE=MyISAM;

ALTER TABLE `kite_auth_role`
ENGINE=MyISAM;
