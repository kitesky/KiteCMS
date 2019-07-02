ALTER TABLE `kite_document_category`
ADD COLUMN `list_rows`  int(11) NOT NULL DEFAULT 10 COMMENT '列表显示条数' AFTER `content`;
