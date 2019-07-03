ALTER TABLE `kite_document_category`
ADD COLUMN `list_rows`  int(11) NOT NULL DEFAULT 10 COMMENT '列表显示条数' AFTER `content`;

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
