--
-- 数据库: `ecmoban_dsc`
--

-- --------------------------------------------------------

--
-- 表的结构 `dsc_account_log`
--

DROP TABLE IF EXISTS `dsc_account_log`;
CREATE TABLE IF NOT EXISTS `dsc_account_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deposit_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rank_points` int(10) NOT NULL DEFAULT '0',
  `pay_points` int(10) NOT NULL DEFAULT '0',
  `change_time` int(10) unsigned NOT NULL DEFAULT '0',
  `change_desc` varchar(255) NOT NULL,
  `change_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `rank_points` (`rank_points`),
  KEY `pay_points` (`pay_points`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_ad`
--

DROP TABLE IF EXISTS `dsc_ad`;
CREATE TABLE IF NOT EXISTS `dsc_ad` (
  `ad_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(10) unsigned NOT NULL DEFAULT '0',
  `media_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ad_name` varchar(60) NOT NULL DEFAULT '',
  `ad_link` varchar(255) NOT NULL DEFAULT '',
  `link_color` varchar(60) NOT NULL,
  `b_title` varchar(60) NOT NULL,
  `s_title` varchar(60) NOT NULL,
  `ad_code` text NOT NULL,
  `ad_bg_code` text NOT NULL,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `link_man` varchar(60) NOT NULL DEFAULT '',
  `link_email` varchar(60) NOT NULL DEFAULT '',
  `link_phone` varchar(60) NOT NULL DEFAULT '',
  `click_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `public_ruid` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL,
  PRIMARY KEY (`ad_id`),
  KEY `position_id` (`position_id`),
  KEY `enabled` (`enabled`),
  KEY `ad_name` (`ad_name`),
  KEY `media_type` (`media_type`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=710 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_admin_action`
--

DROP TABLE IF EXISTS `dsc_admin_action`;
CREATE TABLE IF NOT EXISTS `dsc_admin_action` (
  `action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `action_code` varchar(20) NOT NULL DEFAULT '',
  `relevance` varchar(20) NOT NULL DEFAULT '',
  `seller_show` tinyint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`action_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=304 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_admin_log`
--

DROP TABLE IF EXISTS `dsc_admin_log`;
CREATE TABLE IF NOT EXISTS `dsc_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_admin_message`
--

DROP TABLE IF EXISTS `dsc_admin_message`;
CREATE TABLE IF NOT EXISTS `dsc_admin_message` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `receiver_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sent_time` int(11) unsigned NOT NULL DEFAULT '0',
  `read_time` int(11) unsigned NOT NULL DEFAULT '0',
  `readed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `sender_id` (`sender_id`,`receiver_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_admin_user`
--

DROP TABLE IF EXISTS `dsc_admin_user`;
CREATE TABLE IF NOT EXISTS `dsc_admin_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rs_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `ec_salt` varchar(10) DEFAULT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `action_list` text NOT NULL,
  `nav_list` text NOT NULL,
  `lang_type` varchar(50) NOT NULL DEFAULT '',
  `agency_id` int(10) unsigned NOT NULL,
  `suppliers_id` int(10) unsigned DEFAULT '0',
  `todolist` longtext,
  `role_id` smallint(5) DEFAULT NULL,
  `major_brand` smallint(8) unsigned NOT NULL DEFAULT '0',
  `admin_user_img` varchar(255) NOT NULL,
  `recently_cat` varchar(255) NOT NULL COMMENT '管理员最近使用分类',
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `agency_id` (`agency_id`),
  KEY `ru_id` (`ru_id`),
  KEY `rs_id` (`rs_id`),
  KEY `role_id` (`role_id`),
  KEY `ec_salt` (`ec_salt`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_adsense`
--

DROP TABLE IF EXISTS `dsc_adsense`;
CREATE TABLE IF NOT EXISTS `dsc_adsense` (
  `from_ad` smallint(5) NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `clicks` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `from_ad` (`from_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_ad_custom`
--

DROP TABLE IF EXISTS `dsc_ad_custom`;
CREATE TABLE IF NOT EXISTS `dsc_ad_custom` (
  `ad_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ad_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ad_name` varchar(60) DEFAULT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext,
  `url` varchar(255) DEFAULT NULL,
  `ad_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_ad_position`
--

DROP TABLE IF EXISTS `dsc_ad_position`;
CREATE TABLE IF NOT EXISTS `dsc_ad_position` (
  `position_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `position_name` varchar(60) NOT NULL DEFAULT '',
  `ad_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ad_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `position_model` varchar(255) NOT NULL,
  `position_desc` varchar(255) NOT NULL DEFAULT '',
  `position_style` text NOT NULL,
  `is_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `theme` varchar(160) NOT NULL,
  PRIMARY KEY (`position_id`),
  KEY `user_id` (`user_id`),
  KEY `theme` (`theme`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=344 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_affiliate_log`
--

DROP TABLE IF EXISTS `dsc_affiliate_log`;
CREATE TABLE IF NOT EXISTS `dsc_affiliate_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `time` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(60) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int(10) NOT NULL DEFAULT '0',
  `separate_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_agency`
--

DROP TABLE IF EXISTS `dsc_agency`;
CREATE TABLE IF NOT EXISTS `dsc_agency` (
  `agency_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agency_name` varchar(255) NOT NULL,
  `agency_desc` text NOT NULL,
  PRIMARY KEY (`agency_id`),
  KEY `agency_name` (`agency_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_alidayu_configure`
--

DROP TABLE IF EXISTS `dsc_alidayu_configure`;
CREATE TABLE IF NOT EXISTS `dsc_alidayu_configure` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `temp_id` varchar(255) NOT NULL,
  `temp_content` varchar(255) NOT NULL,
  `add_time` int(15) NOT NULL,
  `set_sign` varchar(255) NOT NULL,
  `send_time` varchar(255) NOT NULL,
  `signature` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `temp_id` (`temp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_alitongxin_configure`
--

DROP TABLE IF EXISTS `dsc_alitongxin_configure`;
CREATE TABLE IF NOT EXISTS `dsc_alitongxin_configure` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `temp_id` varchar(255) NOT NULL COMMENT '模板ID',
  `temp_content` varchar(255) NOT NULL COMMENT '模板内容',
  `add_time` int(15) NOT NULL COMMENT '添加时间',
  `set_sign` varchar(255) NOT NULL COMMENT '签名',
  `send_time` varchar(255) NOT NULL COMMENT '短信发送时机',
  `signature` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `temp_id` (`temp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_appeal_img`
--

DROP TABLE IF EXISTS `dsc_appeal_img`;
CREATE TABLE IF NOT EXISTS `dsc_appeal_img` (
  `img_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `complaint_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `img_file` varchar(255) NOT NULL,
  PRIMARY KEY (`img_id`),
  KEY `order_id` (`order_id`),
  KEY `ru_id` (`ru_id`),
  KEY `complaint_id` (`complaint_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_area_region`
--

DROP TABLE IF EXISTS `dsc_area_region`;
CREATE TABLE IF NOT EXISTS `dsc_area_region` (
  `shipping_area_id` smallint(8) unsigned NOT NULL DEFAULT '0',
  `region_id` smallint(8) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) NOT NULL,
  PRIMARY KEY (`shipping_area_id`,`region_id`),
  KEY `region_id` (`region_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_article`
--

DROP TABLE IF EXISTS `dsc_article`;
CREATE TABLE IF NOT EXISTS `dsc_article` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` mediumint(8) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `author` varchar(30) NOT NULL DEFAULT '',
  `author_email` varchar(60) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `article_type` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `file_url` varchar(255) NOT NULL DEFAULT '',
  `open_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `cat_id` (`cat_id`),
  KEY `is_open` (`is_open`),
  KEY `open_type` (`open_type`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_article_cat`
--

DROP TABLE IF EXISTS `dsc_article_cat`;
CREATE TABLE IF NOT EXISTS `dsc_article_cat` (
  `cat_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `cat_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `show_in_nav` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `cat_type` (`cat_type`),
  KEY `sort_order` (`sort_order`),
  KEY `parent_id` (`parent_id`),
  KEY `cat_name` (`cat_name`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_article_extend`
--

DROP TABLE IF EXISTS `dsc_article_extend`;
CREATE TABLE IF NOT EXISTS `dsc_article_extend` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` mediumint(8) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '0',
  `likenum` int(10) unsigned NOT NULL DEFAULT '0',
  `hatenum` int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_attribute`
--

DROP TABLE IF EXISTS `dsc_attribute`;
CREATE TABLE IF NOT EXISTS `dsc_attribute` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_name` varchar(60) NOT NULL DEFAULT '',
  `attr_cat_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attr_input_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `attr_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `attr_values` text NOT NULL,
  `color_values` text NOT NULL,
  `attr_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  `is_linked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attr_group` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attr_input_category` varchar(30) NOT NULL,
  PRIMARY KEY (`attr_id`),
  KEY `cat_id` (`cat_id`),
  KEY `attr_name` (`attr_name`),
  KEY `attr_cat_type` (`attr_cat_type`),
  KEY `attr_type` (`attr_type`),
  KEY `attr_group` (`attr_group`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_attribute_img`
--

DROP TABLE IF EXISTS `dsc_attribute_img`;
CREATE TABLE IF NOT EXISTS `dsc_attribute_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_values` varchar(80) NOT NULL,
  `attr_img` varchar(255) NOT NULL,
  `attr_site` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attr_id` (`attr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_auction_log`
--

DROP TABLE IF EXISTS `dsc_auction_log`;
CREATE TABLE IF NOT EXISTS `dsc_auction_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(10) unsigned NOT NULL,
  `bid_user` int(10) unsigned NOT NULL,
  `bid_price` decimal(10,2) unsigned NOT NULL,
  `bid_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `act_id` (`act_id`),
  KEY `bid_user` (`bid_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_auto_manage`
--

DROP TABLE IF EXISTS `dsc_auto_manage`;
CREATE TABLE IF NOT EXISTS `dsc_auto_manage` (
  `item_id` mediumint(8) NOT NULL,
  `type` varchar(10) NOT NULL,
  `starttime` int(10) NOT NULL,
  `endtime` int(10) NOT NULL,
  PRIMARY KEY (`item_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_auto_sms`
--

DROP TABLE IF EXISTS `dsc_auto_sms`;
CREATE TABLE IF NOT EXISTS `dsc_auto_sms` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_type` tinyint(1) NOT NULL,
  `user_id` int(10) NOT NULL,
  `ru_id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  `add_time` varchar(255) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_back_goods`
--

DROP TABLE IF EXISTS `dsc_back_goods`;
CREATE TABLE IF NOT EXISTS `dsc_back_goods` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `back_id` int(10) unsigned DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `product_sn` varchar(60) DEFAULT NULL,
  `goods_name` varchar(120) DEFAULT NULL,
  `brand_name` varchar(60) DEFAULT NULL,
  `goods_sn` varchar(60) DEFAULT NULL,
  `is_real` tinyint(1) unsigned DEFAULT '0',
  `send_number` int(10) unsigned DEFAULT '0',
  `goods_attr` text,
  PRIMARY KEY (`rec_id`),
  KEY `back_id` (`back_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_back_order`
--

DROP TABLE IF EXISTS `dsc_back_order`;
CREATE TABLE IF NOT EXISTS `dsc_back_order` (
  `back_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_sn` varchar(30) NOT NULL,
  `order_sn` varchar(30) NOT NULL,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `invoice_no` varchar(50) DEFAULT NULL,
  `add_time` int(10) unsigned DEFAULT '0',
  `shipping_id` tinyint(3) unsigned DEFAULT '0',
  `shipping_name` varchar(120) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT '0',
  `action_user` varchar(30) DEFAULT NULL,
  `consignee` varchar(60) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `country` int(10) unsigned DEFAULT '0',
  `province` int(10) unsigned DEFAULT '0',
  `city` int(10) unsigned DEFAULT '0',
  `district` int(10) unsigned DEFAULT '0',
  `sign_building` varchar(120) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `zipcode` varchar(60) DEFAULT NULL,
  `tel` varchar(60) DEFAULT NULL,
  `mobile` varchar(60) DEFAULT NULL,
  `best_time` varchar(120) DEFAULT NULL,
  `postscript` varchar(255) DEFAULT NULL,
  `how_oos` varchar(120) DEFAULT NULL,
  `insure_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `shipping_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `update_time` int(10) unsigned DEFAULT '0',
  `suppliers_id` smallint(5) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `return_time` int(10) unsigned DEFAULT '0',
  `agency_id` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`back_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_baitiao`
--

DROP TABLE IF EXISTS `dsc_baitiao`;
CREATE TABLE IF NOT EXISTS `dsc_baitiao` (
  `baitiao_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '白条金额',
  `repay_term` varchar(50) NOT NULL COMMENT '还款期限',
  `over_repay_trem` int(10) NOT NULL DEFAULT '0' COMMENT '超过还款期限的天数',
  `add_time` varchar(50) NOT NULL,
  PRIMARY KEY (`baitiao_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_baitiao_log`
--

DROP TABLE IF EXISTS `dsc_baitiao_log`;
CREATE TABLE IF NOT EXISTS `dsc_baitiao_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT,
  `baitiao_id` int(10) NOT NULL COMMENT '白条id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `use_date` varchar(50) NOT NULL COMMENT '记账日期',
  `repay_date` text NOT NULL COMMENT '还款日期',
  `order_id` int(10) NOT NULL COMMENT '订单id',
  `repayed_date` varchar(50) NOT NULL DEFAULT '' COMMENT '完成支付日期',
  `is_repay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否还款',
  `is_stages` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为白条分期商品 1:分期 0:不分期',
  `stages_total` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '当前订单的分期总期数',
  `stages_one_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '每期金额',
  `yes_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '已还期数',
  `is_refund` tinyint(3) unsigned DEFAULT '0' COMMENT '该白条记录对应的订单是否退款了. 1:退款 0:正常;',
  `pay_num` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `pay_num` (`pay_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_baitiao_pay_log`
--

DROP TABLE IF EXISTS `dsc_baitiao_pay_log`;
CREATE TABLE IF NOT EXISTS `dsc_baitiao_pay_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `baitiao_id` int(10) unsigned NOT NULL DEFAULT '0',
  `log_id` int(10) unsigned NOT NULL DEFAULT '0',
  `stages_num` smallint(3) unsigned NOT NULL DEFAULT '0',
  `stages_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `is_pay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_id` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_code` varchar(20) NOT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `stages_num` (`stages_num`) USING BTREE,
  KEY `log_id` (`log_id`) USING BTREE,
  KEY `baitiao_id` (`baitiao_id`),
  KEY `is_pay` (`is_pay`),
  KEY `pai_id` (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_bonus_type`
--

DROP TABLE IF EXISTS `dsc_bonus_type`;
CREATE TABLE IF NOT EXISTS `dsc_bonus_type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(60) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL,
  `type_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `send_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `usebonus_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `min_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `max_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `send_start_date` int(11) NOT NULL DEFAULT '0',
  `send_end_date` int(11) NOT NULL DEFAULT '0',
  `use_start_date` int(11) NOT NULL DEFAULT '0',
  `use_end_date` int(11) NOT NULL DEFAULT '0',
  `min_goods_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  PRIMARY KEY (`type_id`),
  KEY `user_id` (`user_id`),
  KEY `review_status` (`review_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_booking_goods`
--

DROP TABLE IF EXISTS `dsc_booking_goods`;
CREATE TABLE IF NOT EXISTS `dsc_booking_goods` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL DEFAULT '',
  `link_man` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_desc` varchar(255) NOT NULL DEFAULT '',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0',
  `booking_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_dispose` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dispose_user` varchar(30) NOT NULL DEFAULT '',
  `dispose_time` int(10) unsigned NOT NULL DEFAULT '0',
  `dispose_note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_brand`
--

DROP TABLE IF EXISTS `dsc_brand`;
CREATE TABLE IF NOT EXISTS `dsc_brand` (
  `brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(60) NOT NULL DEFAULT '',
  `brand_letter` varchar(60) NOT NULL,
  `brand_first_char` char(1) NOT NULL,
  `brand_logo` varchar(80) NOT NULL DEFAULT '',
  `index_img` varchar(80) NOT NULL,
  `brand_bg` varchar(80) NOT NULL,
  `brand_desc` text NOT NULL,
  `site_url` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `audit_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` varchar(120) NOT NULL,
  PRIMARY KEY (`brand_id`),
  KEY `is_show` (`is_show`),
  KEY `audit_status` (`audit_status`),
  KEY `brand_name` (`brand_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=212 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_brand_extend`
--

DROP TABLE IF EXISTS `dsc_brand_extend`;
CREATE TABLE IF NOT EXISTS `dsc_brand_extend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL COMMENT '品牌id',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否推荐0否1是',
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`),
  KEY `is_recommend` (`is_recommend`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_card`
--

DROP TABLE IF EXISTS `dsc_card`;
CREATE TABLE IF NOT EXISTS `dsc_card` (
  `card_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `card_name` varchar(120) NOT NULL DEFAULT '',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `card_img` varchar(255) NOT NULL DEFAULT '',
  `card_fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `free_money` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `card_desc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`card_id`),
  KEY `user_id` (`user_id`),
  KEY `card_name` (`card_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_cart`
--

DROP TABLE IF EXISTS `dsc_cart`;
CREATE TABLE IF NOT EXISTS `dsc_cart` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(255) DEFAULT NULL,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` varchar(255) NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text NOT NULL,
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rec_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_gift` int(10) unsigned NOT NULL DEFAULT '0',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_handsel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `model_attr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` text NOT NULL,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shopping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `warehouse_id` int(10) unsigned NOT NULL DEFAULT '0',
  `area_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  `stages_qishu` varchar(4) NOT NULL DEFAULT '-1',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `freight` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `store_mobile` varchar(20) NOT NULL,
  `take_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `is_checked` tinyint(1) NOT NULL DEFAULT '1' COMMENT '选中状态，0未选中，1选中',
  `commission_rate` varchar(10) NOT NULL DEFAULT '0',
  `is_invalid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '购物车商品是否无效',
  PRIMARY KEY (`rec_id`),
  KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_id` (`product_id`),
  KEY `is_real` (`is_real`),
  KEY `parent_id` (`parent_id`),
  KEY `is_shipping` (`is_shipping`),
  KEY `ru_id` (`ru_id`),
  KEY `store_id` (`store_id`),
  KEY `freight` (`freight`),
  KEY `tid` (`tid`),
  KEY `is_checked` (`is_checked`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `area_id` (`area_id`),
  KEY `is_gift` (`is_gift`),
  KEY `rec_type` (`rec_type`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_cart_combo`
--

DROP TABLE IF EXISTS `dsc_cart_combo`;
CREATE TABLE IF NOT EXISTS `dsc_cart_combo` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(255) NOT NULL DEFAULT '',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` varchar(255) NOT NULL,
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text NOT NULL,
  `img_flie` varchar(255) NOT NULL,
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rec_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_gift` int(10) unsigned NOT NULL DEFAULT '0',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_handsel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` text NOT NULL,
  `warehouse_id` int(11) unsigned NOT NULL DEFAULT '0',
  `area_id` int(11) unsigned NOT NULL DEFAULT '0',
  `model_attr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL,
  `commission_rate` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rec_id`),
  KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_id` (`product_id`),
  KEY `group_id` (`group_id`),
  KEY `is_real` (`is_real`),
  KEY `extension_code` (`extension_code`),
  KEY `parent_id` (`parent_id`),
  KEY `is_gift` (`is_gift`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `area_id` (`area_id`),
  KEY `model_attr` (`model_attr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_cart_user_info`
--

DROP TABLE IF EXISTS `dsc_cart_user_info`;
CREATE TABLE IF NOT EXISTS `dsc_cart_user_info` (
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` varchar(255) NOT NULL,
  `shipping_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  KEY `ru_id` (`ru_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_category`
--

DROP TABLE IF EXISTS `dsc_category`;
CREATE TABLE IF NOT EXISTS `dsc_category` (
  `cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(90) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort_order` smallint(8) unsigned NOT NULL DEFAULT '50',
  `template_file` varchar(50) NOT NULL DEFAULT '',
  `measure_unit` varchar(15) NOT NULL DEFAULT '',
  `show_in_nav` tinyint(1) NOT NULL DEFAULT '0',
  `style` varchar(150) NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `grade` tinyint(4) NOT NULL DEFAULT '0',
  `filter_attr` varchar(255) NOT NULL DEFAULT '0',
  `is_top_style` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `top_style_tpl` varchar(255) NOT NULL,
  `style_icon` varchar(50) NOT NULL DEFAULT 'other',
  `cat_icon` varchar(255) NOT NULL,
  `is_top_show` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category_links` text NOT NULL,
  `category_topic` text NOT NULL,
  `pinyin_keyword` text NOT NULL,
  `cat_alias_name` varchar(90) NOT NULL,
  `commission_rate` smallint(5) unsigned NOT NULL DEFAULT '0',
  `touch_icon` varchar(255) NOT NULL,
  `cate_title` varchar(200) NOT NULL DEFAULT '' COMMENT '关键词',
  `cate_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词',
  `cate_description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`cat_id`),
  KEY `parent_id` (`parent_id`),
  KEY `is_show` (`is_show`),
  KEY `cat_name` (`cat_name`),
  KEY `show_in_nav` (`show_in_nav`),
  KEY `grade` (`grade`),
  KEY `is_top_style` (`is_top_style`),
  KEY `top_style_tpl` (`top_style_tpl`),
  KEY `is_top_show` (`is_top_show`)
) ENGINE=MyISAM AUTO_INCREMENT=1476 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_cat_recommend`
--

DROP TABLE IF EXISTS `dsc_cat_recommend`;
CREATE TABLE IF NOT EXISTS `dsc_cat_recommend` (
  `cat_id` smallint(5) NOT NULL,
  `recommend_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`cat_id`,`recommend_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_collect_brand`
--

DROP TABLE IF EXISTS `dsc_collect_brand`;
CREATE TABLE IF NOT EXISTS `dsc_collect_brand` (
  `rec_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `brand_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(8) NOT NULL DEFAULT '0',
  `user_brand` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `brand_id` (`brand_id`),
  KEY `user_brand` (`user_brand`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_collect_goods`
--

DROP TABLE IF EXISTS `dsc_collect_goods`;
CREATE TABLE IF NOT EXISTS `dsc_collect_goods` (
  `rec_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `is_attention` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_attention` (`is_attention`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_collect_store`
--

DROP TABLE IF EXISTS `dsc_collect_store`;
CREATE TABLE IF NOT EXISTS `dsc_collect_store` (
  `rec_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `ru_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商家ID',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `is_attention` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`ru_id`),
  KEY `is_attention` (`is_attention`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_comment`
--

DROP TABLE IF EXISTS `dsc_comment`;
CREATE TABLE IF NOT EXISTS `dsc_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id_value` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL DEFAULT '',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `comment_rank` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `comment_server` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `comment_delivery` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(11) unsigned NOT NULL,
  `single_id` int(10) unsigned DEFAULT '0',
  `order_id` int(10) unsigned DEFAULT '0',
  `rec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_tag` varchar(500) DEFAULT NULL,
  `useful` int(10) DEFAULT '0',
  `useful_user` text NOT NULL,
  `use_ip` varchar(15) DEFAULT NULL,
  `dis_id` int(10) unsigned DEFAULT '0',
  `like_num` int(10) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `dis_browse_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  PRIMARY KEY (`comment_id`),
  KEY `parent_id` (`parent_id`),
  KEY `id_value` (`id_value`),
  KEY `ru_id` (`ru_id`),
  KEY `status` (`status`),
  KEY `comment_type` (`comment_type`),
  KEY `user_id` (`user_id`),
  KEY `single_id` (`single_id`),
  KEY `order_id` (`order_id`),
  KEY `rec_id` (`rec_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_comment_baseline`
--

DROP TABLE IF EXISTS `dsc_comment_baseline`;
CREATE TABLE IF NOT EXISTS `dsc_comment_baseline` (
  `id` smallint(8) NOT NULL AUTO_INCREMENT,
  `goods` smallint(3) unsigned NOT NULL DEFAULT '0',
  `service` smallint(3) unsigned NOT NULL DEFAULT '0',
  `shipping` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_comment_img`
--

DROP TABLE IF EXISTS `dsc_comment_img`;
CREATE TABLE IF NOT EXISTS `dsc_comment_img` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_id` int(10) unsigned NOT NULL,
  `comment_img` varchar(255) NOT NULL,
  `img_thumb` varchar(255) NOT NULL,
  `cont_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `rec_id` (`rec_id`),
  KEY `goods_id` (`goods_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_comment_seller`
--

DROP TABLE IF EXISTS `dsc_comment_seller`;
CREATE TABLE IF NOT EXISTS `dsc_comment_seller` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `desc_rank` tinyint(1) NOT NULL,
  `service_rank` tinyint(1) NOT NULL,
  `delivery_rank` tinyint(1) NOT NULL,
  `sender_rank` tinyint(1) NOT NULL,
  `add_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `user_id` (`user_id`),
  KEY `ru_id` (`ru_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_complaint`
--

DROP TABLE IF EXISTS `dsc_complaint`;
CREATE TABLE IF NOT EXISTS `dsc_complaint` (
  `complaint_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_sn` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_name` varchar(60) NOT NULL,
  `title_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `complaint_content` text NOT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `complaint_handle_time` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `appeal_messg` text NOT NULL,
  `appeal_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_handle_time` int(10) NOT NULL DEFAULT '0',
  `end_admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `end_handle_messg` text NOT NULL,
  `complaint_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `complaint_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`complaint_id`),
  KEY `order_id` (`order_id`),
  KEY `order_sn` (`order_sn`),
  KEY `user_id` (`user_id`),
  KEY `ru_id` (`ru_id`),
  KEY `title_id` (`title_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_complaint_img`
--

DROP TABLE IF EXISTS `dsc_complaint_img`;
CREATE TABLE IF NOT EXISTS `dsc_complaint_img` (
  `img_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `complaint_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `img_file` varchar(255) NOT NULL,
  PRIMARY KEY (`img_id`),
  KEY `order_id` (`order_id`),
  KEY `complaint_id` (`complaint_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_complaint_talk`
--

DROP TABLE IF EXISTS `dsc_complaint_talk`;
CREATE TABLE IF NOT EXISTS `dsc_complaint_talk` (
  `talk_id` int(10) NOT NULL AUTO_INCREMENT,
  `complaint_id` int(10) unsigned NOT NULL,
  `talk_member_id` int(10) unsigned NOT NULL,
  `talk_member_name` varchar(30) NOT NULL,
  `talk_member_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `talk_content` varchar(255) NOT NULL,
  `talk_state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `talk_time` int(10) NOT NULL DEFAULT '0',
  `view_state` varchar(60) NOT NULL,
  PRIMARY KEY (`talk_id`),
  KEY `complaint_id` (`complaint_id`),
  KEY `talk_member_id` (`talk_member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_complain_title`
--

DROP TABLE IF EXISTS `dsc_complain_title`;
CREATE TABLE IF NOT EXISTS `dsc_complain_title` (
  `title_id` int(10) NOT NULL AUTO_INCREMENT,
  `title_name` varchar(30) NOT NULL,
  `title_desc` varchar(255) NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`title_id`),
  KEY `is_show` (`is_show`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_connect_user`
--

DROP TABLE IF EXISTS `dsc_connect_user`;
CREATE TABLE IF NOT EXISTS `dsc_connect_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `connect_code` char(30) NOT NULL COMMENT '登录插件名sns_qq，sns_wechat',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员,0是会员 ,1是管理员',
  `open_id` char(64) NOT NULL DEFAULT '' COMMENT '标识',
  `refresh_token` char(64) DEFAULT '',
  `access_token` char(64) NOT NULL DEFAULT '' COMMENT 'token',
  `profile` text COMMENT '序列化用户信息',
  `create_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `expires_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'token过期时间',
  `expires_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'token保存时间',
  PRIMARY KEY (`id`),
  KEY `open_id` (`connect_code`,`open_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_coupons`
--

DROP TABLE IF EXISTS `dsc_coupons`;
CREATE TABLE IF NOT EXISTS `dsc_coupons` (
  `cou_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cou_name` varchar(128) NOT NULL DEFAULT '',
  `cou_total` int(11) NOT NULL DEFAULT '0',
  `cou_man` decimal(10,0) unsigned NOT NULL DEFAULT '0',
  `cou_money` decimal(10,0) unsigned NOT NULL DEFAULT '0',
  `cou_user_num` int(11) unsigned NOT NULL DEFAULT '1',
  `cou_goods` varchar(255) NOT NULL DEFAULT '0',
  `spec_cat` text NOT NULL,
  `cou_start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `cou_end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `cou_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `cou_get_man` decimal(10,0) NOT NULL DEFAULT '0',
  `cou_ok_user` varchar(255) NOT NULL DEFAULT '0',
  `cou_ok_goods` varchar(255) NOT NULL DEFAULT '0',
  `cou_ok_cat` text NOT NULL,
  `cou_intro` text NOT NULL,
  `cou_add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cou_order` int(11) unsigned NOT NULL DEFAULT '0',
  `cou_title` varchar(255) NOT NULL DEFAULT '',
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  PRIMARY KEY (`cou_id`),
  KEY `cou_type` (`cou_type`),
  KEY `review_status` (`review_status`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_coupons_region`
--

DROP TABLE IF EXISTS `dsc_coupons_region`;
CREATE TABLE IF NOT EXISTS `dsc_coupons_region` (
  `cf_id` int(10) NOT NULL AUTO_INCREMENT,
  `cou_id` int(10) unsigned NOT NULL DEFAULT '0',
  `region_list` text NOT NULL,
  PRIMARY KEY (`cf_id`),
  KEY `cou_id` (`cou_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_coupons_user`
--

DROP TABLE IF EXISTS `dsc_coupons_user`;
CREATE TABLE IF NOT EXISTS `dsc_coupons_user` (
  `uc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `cou_id` int(11) DEFAULT NULL,
  `is_use` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `uc_sn` char(12) NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_use_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uc_id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `cou_id` (`cou_id`),
  KEY `is_use` (`is_use`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_crons`
--

DROP TABLE IF EXISTS `dsc_crons`;
CREATE TABLE IF NOT EXISTS `dsc_crons` (
  `cron_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `cron_code` varchar(20) NOT NULL,
  `cron_name` varchar(120) NOT NULL,
  `cron_desc` text,
  `cron_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cron_config` text NOT NULL,
  `thistime` int(10) NOT NULL DEFAULT '0',
  `nextime` int(10) NOT NULL,
  `day` tinyint(2) NOT NULL,
  `week` varchar(1) NOT NULL,
  `hour` varchar(2) NOT NULL,
  `minute` varchar(255) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `run_once` tinyint(1) NOT NULL DEFAULT '0',
  `allow_ip` varchar(100) NOT NULL DEFAULT '',
  `alow_files` varchar(255) NOT NULL,
  PRIMARY KEY (`cron_id`),
  KEY `nextime` (`nextime`),
  KEY `enable` (`enable`),
  KEY `cron_code` (`cron_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_delivery_goods`
--

DROP TABLE IF EXISTS `dsc_delivery_goods`;
CREATE TABLE IF NOT EXISTS `dsc_delivery_goods` (
  `rec_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product_id` mediumint(8) unsigned DEFAULT '0',
  `product_sn` varchar(60) DEFAULT NULL,
  `goods_name` varchar(120) DEFAULT NULL,
  `brand_name` varchar(60) DEFAULT NULL,
  `goods_sn` varchar(60) DEFAULT NULL,
  `is_real` tinyint(1) unsigned DEFAULT '0',
  `extension_code` varchar(30) DEFAULT NULL,
  `parent_id` mediumint(8) unsigned DEFAULT '0',
  `send_number` smallint(5) unsigned DEFAULT '0',
  `goods_attr` text,
  PRIMARY KEY (`rec_id`),
  KEY `delivery_id` (`delivery_id`,`goods_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_delivery_order`
--

DROP TABLE IF EXISTS `dsc_delivery_order`;
CREATE TABLE IF NOT EXISTS `dsc_delivery_order` (
  `delivery_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_sn` varchar(20) NOT NULL,
  `order_sn` varchar(20) NOT NULL,
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `invoice_no` varchar(50) DEFAULT NULL,
  `add_time` int(10) unsigned DEFAULT '0',
  `shipping_id` tinyint(3) unsigned DEFAULT '0',
  `shipping_name` varchar(120) DEFAULT NULL,
  `user_id` mediumint(8) unsigned DEFAULT '0',
  `action_user` varchar(30) DEFAULT NULL,
  `consignee` varchar(60) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `country` smallint(5) unsigned DEFAULT '0',
  `province` smallint(5) unsigned DEFAULT '0',
  `city` smallint(5) unsigned DEFAULT '0',
  `district` smallint(5) unsigned DEFAULT '0',
  `sign_building` varchar(120) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `zipcode` varchar(60) DEFAULT NULL,
  `tel` varchar(60) DEFAULT NULL,
  `mobile` varchar(60) DEFAULT NULL,
  `best_time` varchar(120) DEFAULT NULL,
  `postscript` varchar(255) DEFAULT NULL,
  `how_oos` varchar(120) DEFAULT NULL,
  `insure_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `shipping_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `update_time` int(10) unsigned DEFAULT '0',
  `suppliers_id` smallint(5) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `agency_id` smallint(5) unsigned DEFAULT '0',
  `is_zc_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`delivery_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `delivery_sn` (`delivery_sn`),
  KEY `order_sn` (`order_sn`),
  KEY `shipping_id` (`shipping_id`),
  KEY `suppliers_id` (`suppliers_id`),
  KEY `agency_id` (`agency_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_discuss_circle`
--

DROP TABLE IF EXISTS `dsc_discuss_circle`;
CREATE TABLE IF NOT EXISTS `dsc_discuss_circle` (
  `dis_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dis_browse_num` int(10) unsigned NOT NULL COMMENT '浏览数',
  `like_num` int(10) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `quote_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order_id` mediumint(8) unsigned DEFAULT '0',
  `dis_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dis_title` varchar(200) NOT NULL DEFAULT '',
  `dis_text` text NOT NULL,
  `add_time` int(11) NOT NULL,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`dis_id`),
  KEY `parent_id` (`parent_id`),
  KEY `goods_id` (`goods_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `dis_type` (`dis_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_email_list`
--

DROP TABLE IF EXISTS `dsc_email_list`;
CREATE TABLE IF NOT EXISTS `dsc_email_list` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `stat` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_email_sendlist`
--

DROP TABLE IF EXISTS `dsc_email_sendlist`;
CREATE TABLE IF NOT EXISTS `dsc_email_sendlist` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `template_id` mediumint(8) NOT NULL,
  `email_content` text NOT NULL,
  `error` tinyint(1) NOT NULL DEFAULT '0',
  `pri` tinyint(10) NOT NULL,
  `last_send` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_entry_criteria`
--

DROP TABLE IF EXISTS `dsc_entry_criteria`;
CREATE TABLE IF NOT EXISTS `dsc_entry_criteria` (
  `id` smallint(10) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(10) unsigned NOT NULL DEFAULT '0',
  `criteria_name` varchar(255) NOT NULL,
  `charge` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `standard_name` varchar(60) NOT NULL,
  `type` varchar(10) NOT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `option_value` varchar(255) NOT NULL,
  `is_cumulative` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_error_log`
--

DROP TABLE IF EXISTS `dsc_error_log`;
CREATE TABLE IF NOT EXISTS `dsc_error_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `info` varchar(255) NOT NULL,
  `file` varchar(100) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_exchange_goods`
--

DROP TABLE IF EXISTS `dsc_exchange_goods`;
CREATE TABLE IF NOT EXISTS `dsc_exchange_goods` (
  `eid` int(10) NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `exchange_integral` int(10) unsigned NOT NULL DEFAULT '0',
  `market_integral` int(10) unsigned NOT NULL DEFAULT '0',
  `is_exchange` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`),
  UNIQUE KEY `goods_id` (`goods_id`),
  KEY `is_hot` (`is_hot`),
  KEY `is_best` (`is_best`),
  KEY `review_status` (`review_status`),
  KEY `user_id` (`user_id`),
  KEY `is_exchange` (`is_exchange`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_favourable_activity`
--

DROP TABLE IF EXISTS `dsc_favourable_activity`;
CREATE TABLE IF NOT EXISTS `dsc_favourable_activity` (
  `act_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `user_rank` varchar(255) NOT NULL,
  `act_range` tinyint(3) unsigned NOT NULL,
  `act_range_ext` varchar(255) NOT NULL,
  `min_amount` decimal(10,2) unsigned NOT NULL,
  `max_amount` decimal(10,2) unsigned NOT NULL,
  `act_type` tinyint(3) unsigned NOT NULL,
  `act_type_ext` decimal(10,2) unsigned NOT NULL,
  `activity_thumb` varchar(255) NOT NULL,
  `gift` text NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `user_id` int(11) unsigned NOT NULL,
  `rs_id` int(10) NOT NULL COMMENT '卖场ID',
  `userFav_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userFav_type_ext` varchar(255) NOT NULL COMMENT '使用类型扩展',
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  `user_range_ext` varchar(255) NOT NULL DEFAULT '',
  `is_user_brand` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`act_id`),
  KEY `act_name` (`act_name`),
  KEY `user_id` (`user_id`),
  KEY `review_status` (`review_status`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `act_type` (`act_type`),
  KEY `rs_id` (`rs_id`),
  KEY `userFav_type` (`userFav_type`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_feedback`
--

DROP TABLE IF EXISTS `dsc_feedback`;
CREATE TABLE IF NOT EXISTS `dsc_feedback` (
  `msg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `user_email` varchar(60) NOT NULL DEFAULT '',
  `msg_title` varchar(200) NOT NULL DEFAULT '',
  `msg_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `msg_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `msg_content` text NOT NULL,
  `msg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `message_img` varchar(255) NOT NULL DEFAULT '0',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_area` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  KEY `order_id` (`order_id`),
  KEY `msg_type` (`msg_type`),
  KEY `msg_status` (`msg_status`),
  KEY `msg_area` (`msg_area`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_floor_content`
--

DROP TABLE IF EXISTS `dsc_floor_content`;
CREATE TABLE IF NOT EXISTS `dsc_floor_content` (
  `fb_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(50) NOT NULL COMMENT '关联模版表filename',
  `region` varchar(100) NOT NULL COMMENT '关联模版表region',
  `id` int(11) NOT NULL COMMENT '关联模版表id',
  `id_name` varchar(100) NOT NULL COMMENT 'id对应的内容名称',
  `brand_id` int(11) NOT NULL COMMENT '品牌id',
  `brand_name` varchar(100) NOT NULL COMMENT '品牌名称',
  `theme` varchar(100) NOT NULL COMMENT '当前选择的模板',
  PRIMARY KEY (`fb_id`),
  KEY `brand_id` (`brand_id`),
  KEY `theme` (`theme`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_friend_link`
--

DROP TABLE IF EXISTS `dsc_friend_link`;
CREATE TABLE IF NOT EXISTS `dsc_friend_link` (
  `link_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_logo` varchar(255) NOT NULL DEFAULT '',
  `show_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  PRIMARY KEY (`link_id`),
  KEY `show_order` (`show_order`),
  KEY `link_name` (`link_name`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_gallery_album`
--

DROP TABLE IF EXISTS `dsc_gallery_album`;
CREATE TABLE IF NOT EXISTS `dsc_gallery_album` (
  `album_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_album_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `album_mame` varchar(60) NOT NULL,
  `album_cover` varchar(255) NOT NULL,
  `album_desc` varchar(255) NOT NULL,
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '50',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`album_id`),
  KEY `parent_album_id` (`parent_album_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_gift_gard_log`
--

DROP TABLE IF EXISTS `dsc_gift_gard_log`;
CREATE TABLE IF NOT EXISTS `dsc_gift_gard_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `gift_gard_id` int(11) unsigned NOT NULL DEFAULT '0',
  `delivery_status` varchar(60) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `handle_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gift_gard_id` (`gift_gard_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_gift_gard_type`
--

DROP TABLE IF EXISTS `dsc_gift_gard_type`;
CREATE TABLE IF NOT EXISTS `dsc_gift_gard_type` (
  `gift_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gift_name` varchar(100) NOT NULL,
  `gift_menory` decimal(10,2) DEFAULT NULL,
  `gift_min_menory` decimal(10,2) DEFAULT NULL,
  `gift_start_date` int(11) NOT NULL,
  `gift_end_date` int(11) NOT NULL,
  `gift_number` smallint(5) NOT NULL,
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  PRIMARY KEY (`gift_id`),
  KEY `review_status` (`review_status`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods`
--

DROP TABLE IF EXISTS `dsc_goods`;
CREATE TABLE IF NOT EXISTS `dsc_goods` (
  `goods_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_cat` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `bar_code` varchar(60) NOT NULL,
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_name_style` varchar(60) NOT NULL DEFAULT '+',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0',
  `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `provider_name` varchar(100) NOT NULL DEFAULT '',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000',
  `default_shipping` int(11) unsigned NOT NULL,
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_start_date` int(11) unsigned NOT NULL DEFAULT '0',
  `promote_end_date` int(11) unsigned NOT NULL DEFAULT '0',
  `warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `goods_brief` varchar(255) NOT NULL DEFAULT '',
  `goods_desc` text NOT NULL,
  `desc_mobile` text NOT NULL,
  `goods_thumb` varchar(255) NOT NULL DEFAULT '',
  `goods_img` varchar(255) NOT NULL DEFAULT '',
  `original_img` varchar(255) NOT NULL DEFAULT '',
  `is_real` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_alone_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `integral` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` smallint(4) unsigned NOT NULL DEFAULT '100',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_promote` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_volume` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_fullcut` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bonus_type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `seller_note` varchar(255) NOT NULL DEFAULT '',
  `give_integral` int(11) NOT NULL DEFAULT '-1',
  `rank_integral` int(11) NOT NULL DEFAULT '-1',
  `suppliers_id` smallint(5) unsigned DEFAULT NULL,
  `is_check` tinyint(1) unsigned DEFAULT NULL,
  `store_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `store_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `store_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_number` smallint(8) unsigned NOT NULL DEFAULT '0',
  `is_xiangou` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否限购',
  `xiangou_start_date` int(11) NOT NULL DEFAULT '0' COMMENT '限购开始时间',
  `xiangou_end_date` int(11) NOT NULL DEFAULT '0' COMMENT '限购结束时间',
  `xiangou_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限购设定数量',
  `review_status` tinyint(1) NOT NULL DEFAULT '1',
  `review_content` varchar(255) NOT NULL,
  `goods_shipai` text NOT NULL,
  `comments_number` int(10) unsigned NOT NULL DEFAULT '0',
  `sales_volume` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `model_price` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `model_inventory` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `model_attr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `largest_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pinyin_keyword` text,
  `goods_product_tag` varchar(2000) DEFAULT NULL,
  `goods_tag` varchar(255) DEFAULT NULL COMMENT '商品标签',
  `stages` varchar(512) NOT NULL DEFAULT '',
  `stages_rate` decimal(10,2) NOT NULL DEFAULT '0.50',
  `freight` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_unit` varchar(15) NOT NULL DEFAULT '个',
  `goods_cause` varchar(10) NOT NULL,
  `commission_rate` varchar(10) NOT NULL DEFAULT '0',
  `from_seller` int(11) NOT NULL DEFAULT '0',
  `user_brand` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌统一使用平台品牌ID异步操作',
  `product_table` varchar(60) NOT NULL DEFAULT 'products',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品默认勾选属性货品',
  `product_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品默认勾选属性货品价格',
  `product_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_video` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`goods_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `cat_id` (`cat_id`),
  KEY `last_update` (`last_update`),
  KEY `brand_id` (`brand_id`),
  KEY `goods_weight` (`goods_weight`),
  KEY `promote_end_date` (`promote_end_date`),
  KEY `promote_start_date` (`promote_start_date`),
  KEY `goods_number` (`goods_number`),
  KEY `sort_order` (`sort_order`),
  KEY `sales_volume` (`sales_volume`),
  KEY `xiangou_start_date` (`xiangou_start_date`),
  KEY `xiangou_end_date` (`xiangou_end_date`),
  KEY `user_id` (`user_id`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `is_alone_sale` (`is_alone_sale`),
  KEY `is_delete` (`is_delete`),
  KEY `user_cat` (`user_cat`),
  KEY `freight` (`freight`),
  KEY `tid` (`tid`),
  KEY `review_status` (`review_status`),
  KEY `user_brand` (`user_brand`),
  KEY `from_seller` (`from_seller`)
) ENGINE=MyISAM AUTO_INCREMENT=909 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_activity`
--

DROP TABLE IF EXISTS `dsc_goods_activity`;
CREATE TABLE IF NOT EXISTS `dsc_goods_activity` (
  `act_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `act_desc` text NOT NULL,
  `activity_thumb` varchar(255) NOT NULL,
  `act_promise` text NOT NULL,
  `act_ensure` text NOT NULL,
  `act_type` tinyint(3) unsigned NOT NULL,
  `goods_id` mediumint(8) unsigned NOT NULL,
  `product_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `is_finished` tinyint(3) unsigned NOT NULL,
  `ext_info` text NOT NULL,
  `is_hot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`act_id`),
  KEY `act_name` (`act_name`,`act_type`,`goods_id`),
  KEY `user_id` (`user_id`),
  KEY `is_hot` (`is_hot`),
  KEY `review_status` (`review_status`),
  KEY `product_id` (`product_id`),
  KEY `is_new` (`is_new`),
  KEY `goods_name` (`goods_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_article`
--

DROP TABLE IF EXISTS `dsc_goods_article`;
CREATE TABLE IF NOT EXISTS `dsc_goods_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `article_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`,`article_id`,`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_attr`
--

DROP TABLE IF EXISTS `dsc_goods_attr`;
CREATE TABLE IF NOT EXISTS `dsc_goods_attr` (
  `goods_attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_value` text NOT NULL,
  `color_value` text NOT NULL,
  `attr_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `attr_sort` int(10) unsigned NOT NULL,
  `attr_img_flie` varchar(255) NOT NULL,
  `attr_gallery_flie` varchar(255) NOT NULL,
  `attr_img_site` varchar(255) NOT NULL,
  `attr_checked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attr_value1` text,
  `lang_flag` int(2) DEFAULT '0',
  `attr_img` varchar(255) DEFAULT NULL,
  `attr_thumb` varchar(255) DEFAULT NULL,
  `img_flag` int(2) DEFAULT '0',
  `attr_pid` varchar(60) DEFAULT NULL,
  `admin_id` smallint(8) unsigned NOT NULL,
  PRIMARY KEY (`goods_attr_id`),
  KEY `goods_id` (`goods_id`),
  KEY `attr_id` (`attr_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=714 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_cat`
--

DROP TABLE IF EXISTS `dsc_goods_cat`;
CREATE TABLE IF NOT EXISTS `dsc_goods_cat` (
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_id`,`cat_id`),
  KEY `goods_id` (`goods_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_change_log`
--

DROP TABLE IF EXISTS `dsc_goods_change_log`;
CREATE TABLE IF NOT EXISTS `dsc_goods_change_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增日志ID',
  `goods_id` mediumint(8) NOT NULL COMMENT '商品ID',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价',
  `shipping_fee` decimal(10,2) NOT NULL COMMENT '运费',
  `promote_price` decimal(10,2) NOT NULL COMMENT '促销价',
  `member_price` varchar(255) NOT NULL COMMENT '会员价',
  `volume_price` varchar(255) NOT NULL COMMENT '阶梯价',
  `give_integral` int(11) NOT NULL COMMENT '赠送消费积分',
  `rank_integral` int(11) NOT NULL COMMENT '赠送等级积分',
  `goods_weight` decimal(10,3) NOT NULL COMMENT '商品重量',
  `is_on_sale` tinyint(1) NOT NULL COMMENT '上下架',
  `user_id` int(11) NOT NULL COMMENT '操作者ID',
  `handle_time` int(11) NOT NULL COMMENT '操作时间',
  `old_record` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '原纪录',
  PRIMARY KEY (`log_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_conshipping`
--

DROP TABLE IF EXISTS `dsc_goods_conshipping`;
CREATE TABLE IF NOT EXISTS `dsc_goods_conshipping` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sfull` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `sreduce` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_consumption`
--

DROP TABLE IF EXISTS `dsc_goods_consumption`;
CREATE TABLE IF NOT EXISTS `dsc_goods_consumption` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cfull` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `creduce` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_extend`
--

DROP TABLE IF EXISTS `dsc_goods_extend`;
CREATE TABLE IF NOT EXISTS `dsc_goods_extend` (
  `extend_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `is_reality` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是正品0否1是',
  `is_return` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持包退服务0否1是',
  `is_fast` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否闪速送货0否1是',
  `width` varchar(50) NOT NULL,
  `height` varchar(50) NOT NULL,
  `depth` varchar(50) NOT NULL,
  `origincountry` varchar(50) NOT NULL,
  `originplace` varchar(50) NOT NULL,
  `assemblycountry` varchar(50) NOT NULL,
  `barcodetype` varchar(50) NOT NULL,
  `catena` varchar(50) NOT NULL,
  `isbasicunit` varchar(50) NOT NULL,
  `packagetype` varchar(50) NOT NULL,
  `grossweight` varchar(50) NOT NULL,
  `netweight` varchar(50) NOT NULL,
  `netcontent` varchar(50) NOT NULL,
  `licensenum` varchar(50) NOT NULL,
  `healthpermitnum` varchar(50) NOT NULL,
  PRIMARY KEY (`extend_id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_reality` (`is_reality`),
  KEY `is_return` (`is_return`),
  KEY `is_fast` (`is_fast`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=346 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_gallery`
--

DROP TABLE IF EXISTS `dsc_goods_gallery`;
CREATE TABLE IF NOT EXISTS `dsc_goods_gallery` (
  `img_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `img_url` varchar(255) NOT NULL DEFAULT '',
  `img_desc` smallint(4) NOT NULL DEFAULT '100',
  `thumb_url` varchar(255) NOT NULL DEFAULT '',
  `img_original` varchar(255) NOT NULL DEFAULT '',
  `single_id` mediumint(8) DEFAULT NULL,
  `external_url` varchar(255) NOT NULL,
  `front_cover` tinyint(2) DEFAULT NULL,
  `dis_id` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`img_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1267 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_inventory_logs`
--

DROP TABLE IF EXISTS `dsc_goods_inventory_logs`;
CREATE TABLE IF NOT EXISTS `dsc_goods_inventory_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `order_id` int(10) NOT NULL DEFAULT '0',
  `use_storage` tinyint(1) NOT NULL DEFAULT '0',
  `admin_id` int(10) NOT NULL DEFAULT '0',
  `number` varchar(160) NOT NULL,
  `model_inventory` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `model_attr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `warehouse_id` int(10) unsigned NOT NULL DEFAULT '0',
  `area_id` int(10) unsigned NOT NULL DEFAULT '0',
  `suppliers_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  `batch_number` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `order_id` (`order_id`),
  KEY `admin_id` (`admin_id`),
  KEY `model_inventory` (`model_inventory`),
  KEY `product_id` (`product_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `area_id` (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_lib`
--

DROP TABLE IF EXISTS `dsc_goods_lib`;
CREATE TABLE IF NOT EXISTS `dsc_goods_lib` (
  `goods_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `lib_cat_id` smallint(5) NOT NULL COMMENT '商品库分类ID',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `bar_code` varchar(60) NOT NULL,
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_name_style` varchar(60) NOT NULL DEFAULT '+',
  `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `goods_weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `goods_brief` varchar(255) NOT NULL DEFAULT '',
  `goods_desc` text NOT NULL,
  `desc_mobile` text NOT NULL,
  `goods_thumb` varchar(255) NOT NULL DEFAULT '',
  `goods_img` varchar(255) NOT NULL DEFAULT '',
  `original_img` varchar(255) NOT NULL DEFAULT '',
  `is_real` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` smallint(4) unsigned NOT NULL DEFAULT '100',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_check` tinyint(1) unsigned DEFAULT NULL,
  `largest_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pinyin_keyword` text,
  `lib_goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品库商品ID',
  `is_on_sale` tinyint(1) NOT NULL COMMENT '上下架',
  `from_seller` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `cat_id` (`cat_id`),
  KEY `last_update` (`last_update`),
  KEY `brand_id` (`brand_id`),
  KEY `goods_weight` (`goods_weight`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_lib_cat`
--

DROP TABLE IF EXISTS `dsc_goods_lib_cat`;
CREATE TABLE IF NOT EXISTS `dsc_goods_lib_cat` (
  `cat_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `parent_id` mediumint(8) NOT NULL COMMENT '父类ID号',
  `cat_name` varchar(50) NOT NULL COMMENT '商品库商品分类名称',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示',
  `sort_order` tinyint(3) NOT NULL COMMENT '排序',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_lib_gallery`
--

DROP TABLE IF EXISTS `dsc_goods_lib_gallery`;
CREATE TABLE IF NOT EXISTS `dsc_goods_lib_gallery` (
  `img_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `img_url` varchar(255) NOT NULL DEFAULT '',
  `img_desc` varchar(255) NOT NULL DEFAULT '',
  `thumb_url` varchar(255) NOT NULL DEFAULT '',
  `img_original` varchar(255) NOT NULL DEFAULT '',
  `single_id` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`img_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_report`
--

DROP TABLE IF EXISTS `dsc_goods_report`;
CREATE TABLE IF NOT EXISTS `dsc_goods_report` (
  `report_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL,
  `goods_image` varchar(255) NOT NULL,
  `title_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `inform_content` text NOT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `report_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `handle_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `handle_message` text NOT NULL,
  `handle_time` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_report_img`
--

DROP TABLE IF EXISTS `dsc_goods_report_img`;
CREATE TABLE IF NOT EXISTS `dsc_goods_report_img` (
  `img_id` int(10) NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `report_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `img_file` varchar(255) NOT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_report_title`
--

DROP TABLE IF EXISTS `dsc_goods_report_title`;
CREATE TABLE IF NOT EXISTS `dsc_goods_report_title` (
  `title_id` int(10) NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title_name` varchar(60) NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`title_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_report_type`
--

DROP TABLE IF EXISTS `dsc_goods_report_type`;
CREATE TABLE IF NOT EXISTS `dsc_goods_report_type` (
  `type_id` int(10) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(60) NOT NULL,
  `type_desc` text NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_transport`
--

DROP TABLE IF EXISTS `dsc_goods_transport`;
CREATE TABLE IF NOT EXISTS `dsc_goods_transport` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `freight_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `shipping_title` varchar(255) NOT NULL,
  `free_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_transport_express`
--

DROP TABLE IF EXISTS `dsc_goods_transport_express`;
CREATE TABLE IF NOT EXISTS `dsc_goods_transport_express` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_id` text NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_transport_extend`
--

DROP TABLE IF EXISTS `dsc_goods_transport_extend`;
CREATE TABLE IF NOT EXISTS `dsc_goods_transport_extend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `area_id` text NOT NULL,
  `top_area_id` text NOT NULL,
  `sprice` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_transport_tpl`
--

DROP TABLE IF EXISTS `dsc_goods_transport_tpl`;
CREATE TABLE IF NOT EXISTS `dsc_goods_transport_tpl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tpl_name` varchar(255) NOT NULL,
  `tid` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `shipping_id` int(10) NOT NULL DEFAULT '0',
  `region_id` text NOT NULL,
  `configure` text NOT NULL,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `tid` (`tid`),
  KEY `user_id` (`user_id`),
  KEY `shipping_id` (`shipping_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_type`
--

DROP TABLE IF EXISTS `dsc_goods_type`;
CREATE TABLE IF NOT EXISTS `dsc_goods_type` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `cat_name` varchar(60) NOT NULL DEFAULT '',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `attr_group` varchar(255) NOT NULL,
  `c_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `user_id` (`user_id`),
  KEY `cat_name` (`cat_name`),
  KEY `enabled` (`enabled`),
  KEY `c_id` (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_goods_type_cat`
--

DROP TABLE IF EXISTS `dsc_goods_type_cat`;
CREATE TABLE IF NOT EXISTS `dsc_goods_type_cat` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cat_name` varchar(90) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT '50',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`cat_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  KEY `cat_name` (`cat_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_group_goods`
--

DROP TABLE IF EXISTS `dsc_group_goods`;
CREATE TABLE IF NOT EXISTS `dsc_group_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配件分组',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `goods_id` (`goods_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_home_templates`
--

DROP TABLE IF EXISTS `dsc_home_templates`;
CREATE TABLE IF NOT EXISTS `dsc_home_templates` (
  `temp_id` int(10) NOT NULL AUTO_INCREMENT,
  `rs_id` int(10) unsigned NOT NULL DEFAULT '0',
  `code` varchar(60) NOT NULL,
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `theme` varchar(160) NOT NULL,
  PRIMARY KEY (`temp_id`),
  KEY `rs_id` (`rs_id`),
  KEY `code` (`code`),
  KEY `is_enable` (`is_enable`),
  KEY `theme` (`theme`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_intelligent_weight`
--

DROP TABLE IF EXISTS `dsc_intelligent_weight`;
CREATE TABLE IF NOT EXISTS `dsc_intelligent_weight` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品购买数量',
  `return_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品退换货数量',
  `user_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买此商品的会员数量',
  `goods_comment_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对商品评价数量',
  `merchants_comment_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对商家评价数量',
  `user_attention_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员关注此商品数量',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_keywords`
--

DROP TABLE IF EXISTS `dsc_keywords`;
CREATE TABLE IF NOT EXISTS `dsc_keywords` (
  `date` date NOT NULL DEFAULT '0000-00-00',
  `searchengine` varchar(20) NOT NULL DEFAULT '',
  `keyword` varchar(90) NOT NULL DEFAULT '',
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`date`,`searchengine`,`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_link_area_goods`
--

DROP TABLE IF EXISTS `dsc_link_area_goods`;
CREATE TABLE IF NOT EXISTS `dsc_link_area_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `region_id` (`region_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_link_brand`
--

DROP TABLE IF EXISTS `dsc_link_brand`;
CREATE TABLE IF NOT EXISTS `dsc_link_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` smallint(8) unsigned NOT NULL DEFAULT '0',
  `brand_id` smallint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`),
  KEY `brand_id` (`brand_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_link_desc_goodsid`
--

DROP TABLE IF EXISTS `dsc_link_desc_goodsid`;
CREATE TABLE IF NOT EXISTS `dsc_link_desc_goodsid` (
  `d_id` int(11) unsigned NOT NULL,
  `goods_id` int(11) unsigned NOT NULL,
  KEY `goods_id` (`goods_id`),
  KEY `d_id` (`d_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_link_desc_temporary`
--

DROP TABLE IF EXISTS `dsc_link_desc_temporary`;
CREATE TABLE IF NOT EXISTS `dsc_link_desc_temporary` (
  `goods_id` text NOT NULL,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_link_goods`
--

DROP TABLE IF EXISTS `dsc_link_goods`;
CREATE TABLE IF NOT EXISTS `dsc_link_goods` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link_goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_double` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_id`,`link_goods_id`,`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_link_goods_desc`
--

DROP TABLE IF EXISTS `dsc_link_goods_desc`;
CREATE TABLE IF NOT EXISTS `dsc_link_goods_desc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` text NOT NULL,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `desc_name` varchar(255) NOT NULL,
  `goods_desc` text NOT NULL,
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`),
  KEY `review_status` (`review_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_mail_templates`
--

DROP TABLE IF EXISTS `dsc_mail_templates`;
CREATE TABLE IF NOT EXISTS `dsc_mail_templates` (
  `template_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `template_code` varchar(30) NOT NULL DEFAULT '',
  `is_html` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `template_subject` varchar(200) NOT NULL DEFAULT '',
  `template_content` text NOT NULL,
  `last_modify` int(10) unsigned NOT NULL DEFAULT '0',
  `last_send` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `template_code` (`template_code`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_mass_sms_log`
--

DROP TABLE IF EXISTS `dsc_mass_sms_log`;
CREATE TABLE IF NOT EXISTS `dsc_mass_sms_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `send_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:未发送,1:已发送,2:发送失败',
  `last_send` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_mass_sms_template`
--

DROP TABLE IF EXISTS `dsc_mass_sms_template`;
CREATE TABLE IF NOT EXISTS `dsc_mass_sms_template` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `temp_id` varchar(255) NOT NULL COMMENT '模板ID',
  `temp_content` varchar(255) NOT NULL COMMENT '模板内容',
  `content` varchar(255) NOT NULL COMMENT '自定义内容',
  `add_time` int(15) NOT NULL COMMENT '添加时间',
  `set_sign` varchar(255) NOT NULL COMMENT '签名',
  `signature` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `temp_id` (`temp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_member_price`
--

DROP TABLE IF EXISTS `dsc_member_price`;
CREATE TABLE IF NOT EXISTS `dsc_member_price` (
  `price_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_rank` tinyint(3) NOT NULL DEFAULT '0',
  `user_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`price_id`),
  KEY `goods_id` (`goods_id`,`user_rank`),
  KEY `user_rank` (`user_rank`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_account_log`
--

DROP TABLE IF EXISTS `dsc_merchants_account_log`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_account_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `change_time` int(10) unsigned NOT NULL,
  `change_desc` varchar(255) NOT NULL,
  `change_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `change_type` (`change_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_category`
--

DROP TABLE IF EXISTS `dsc_merchants_category`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_category` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(90) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `cat_desc` varchar(255) NOT NULL,
  `sort_order` smallint(8) unsigned NOT NULL DEFAULT '0',
  `measure_unit` varchar(15) NOT NULL,
  `show_in_nav` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `style` varchar(150) NOT NULL,
  `grade` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `filter_attr` varchar(225) NOT NULL,
  `is_top_style` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `top_style_tpl` varchar(255) NOT NULL,
  `cat_icon` varchar(255) NOT NULL,
  `is_top_show` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category_links` text NOT NULL,
  `category_topic` text NOT NULL,
  `pinyin_keyword` text NOT NULL,
  `cat_alias_name` varchar(90) NOT NULL,
  `template_file` varchar(50) NOT NULL,
  `add_titme` int(11) NOT NULL,
  `touch_icon` varchar(255) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `user_id` (`user_id`),
  KEY `is_show` (`is_show`),
  KEY `parent_id` (`parent_id`),
  KEY `is_top_show` (`is_top_show`),
  KEY `cat_name` (`cat_name`),
  KEY `show_in_nav` (`show_in_nav`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM AUTO_INCREMENT=1442 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_category_temporarydate`
--

DROP TABLE IF EXISTS `dsc_merchants_category_temporarydate`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_category_temporarydate` (
  `ct_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `cat_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `parent_name` varchar(255) NOT NULL,
  `is_add` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`ct_id`),
  KEY `user_id` (`user_id`),
  KEY `cat_id` (`cat_id`),
  KEY `parent_id` (`parent_id`),
  KEY `cat_name` (`cat_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=226 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_documenttitle`
--

DROP TABLE IF EXISTS `dsc_merchants_documenttitle`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_documenttitle` (
  `dt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dt_title` varchar(255) NOT NULL,
  `cat_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`dt_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_dt_file`
--

DROP TABLE IF EXISTS `dsc_merchants_dt_file`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_dt_file` (
  `dtf_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned NOT NULL,
  `dt_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `permanent_file` varchar(255) NOT NULL,
  `permanent_date` varchar(255) NOT NULL,
  `cate_title_permanent` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`dtf_id`),
  KEY `cat_id` (`cat_id`),
  KEY `dt_id` (`dt_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_goods_comment`
--

DROP TABLE IF EXISTS `dsc_merchants_goods_comment`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_goods_comment` (
  `goods_id` int(11) unsigned NOT NULL,
  `comment_start` varchar(60) NOT NULL,
  `comment_end` varchar(60) NOT NULL,
  `comment_last_percent` varchar(60) NOT NULL,
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_grade`
--

DROP TABLE IF EXISTS `dsc_merchants_grade`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_grade` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `grade_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `year_num` int(10) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_nav`
--

DROP TABLE IF EXISTS `dsc_merchants_nav`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_nav` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ctype` varchar(10) DEFAULT NULL,
  `cid` smallint(5) unsigned DEFAULT NULL,
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `ifshow` tinyint(1) NOT NULL,
  `vieworder` tinyint(1) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `ru_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `ifshow` (`ifshow`),
  KEY `cat_id` (`cat_id`),
  KEY `cid` (`cid`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_percent`
--

DROP TABLE IF EXISTS `dsc_merchants_percent`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_percent` (
  `percent_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `percent_value` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`percent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_privilege`
--

DROP TABLE IF EXISTS `dsc_merchants_privilege`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_privilege` (
  `action_list` text NOT NULL,
  `grade_id` tinyint(10) unsigned NOT NULL,
  KEY `grade_id` (`grade_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_region_area`
--

DROP TABLE IF EXISTS `dsc_merchants_region_area`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_region_area` (
  `ra_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ra_name` varchar(255) NOT NULL,
  `ra_sort` int(11) unsigned NOT NULL,
  `add_time` int(11) unsigned NOT NULL,
  `up_titme` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ra_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_region_info`
--

DROP TABLE IF EXISTS `dsc_merchants_region_info`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_region_info` (
  `ra_id` int(11) unsigned NOT NULL,
  `region_id` int(11) unsigned NOT NULL,
  KEY `ra_id` (`ra_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_server`
--

DROP TABLE IF EXISTS `dsc_merchants_server`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_server` (
  `server_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `suppliers_desc` mediumtext,
  `suppliers_percent` int(10) unsigned NOT NULL DEFAULT '0',
  `commission_model` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bill_freeze_day` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cycle` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `day_number` smallint(8) unsigned NOT NULL DEFAULT '0',
  `bill_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`server_id`),
  KEY `user_id` (`user_id`),
  KEY `cycle` (`cycle`),
  KEY `bill_time` (`bill_time`),
  KEY `suppliers_percent` (`suppliers_percent`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_shop_brand`
--

DROP TABLE IF EXISTS `dsc_merchants_shop_brand`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_shop_brand` (
  `bid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `bank_name_letter` varchar(150) NOT NULL,
  `brandName` varchar(180) NOT NULL,
  `brandFirstChar` char(60) NOT NULL,
  `brandLogo` varchar(255) NOT NULL,
  `brandType` tinyint(1) unsigned NOT NULL,
  `brand_operateType` tinyint(1) unsigned NOT NULL,
  `brandEndTime` varchar(255) NOT NULL,
  `brandEndTime_permanent` tinyint(1) unsigned NOT NULL,
  `site_url` varchar(255) NOT NULL,
  `brand_desc` text NOT NULL,
  `sort_order` varchar(255) NOT NULL DEFAULT '50',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `major_business` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `audit_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` varchar(120) NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `user_id` (`user_id`),
  KEY `is_show` (`is_show`),
  KEY `audit_status` (`audit_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_shop_brandfile`
--

DROP TABLE IF EXISTS `dsc_merchants_shop_brandfile`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_shop_brandfile` (
  `b_fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned NOT NULL,
  `qualificationNameInput` varchar(255) NOT NULL,
  `qualificationImg` varchar(255) NOT NULL,
  `expiredDateInput` varchar(255) NOT NULL,
  `expiredDate_permanent` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`b_fid`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_shop_information`
--

DROP TABLE IF EXISTS `dsc_merchants_shop_information`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_shop_information` (
  `shop_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `region_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `shoprz_type` tinyint(3) unsigned NOT NULL,
  `subShoprz_type` tinyint(3) unsigned NOT NULL,
  `shop_expireDateStart` varchar(255) NOT NULL,
  `shop_expireDateEnd` varchar(255) NOT NULL,
  `shop_permanent` tinyint(1) unsigned NOT NULL,
  `authorizeFile` varchar(255) NOT NULL,
  `shop_hypermarketFile` varchar(255) NOT NULL,
  `shop_categoryMain` int(11) unsigned NOT NULL,
  `user_shopMain_category` text NOT NULL,
  `shoprz_brandName` varchar(150) NOT NULL,
  `shop_class_keyWords` varchar(150) NOT NULL,
  `shopNameSuffix` varchar(150) NOT NULL,
  `rz_shopName` varchar(150) NOT NULL,
  `hopeLoginName` varchar(150) NOT NULL,
  `merchants_message` varchar(255) NOT NULL,
  `allow_number` int(11) unsigned NOT NULL DEFAULT '0',
  `steps_audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `merchants_audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `review_goods` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort_order` smallint(4) unsigned NOT NULL DEFAULT '100',
  `store_score` int(10) NOT NULL DEFAULT '5',
  `is_street` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_IM` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启"在线客服"功能 0:关闭 1:启用',
  `self_run` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '自营店铺',
  `shop_close` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否关闭店铺（0：关闭，1：开启）',
  PRIMARY KEY (`shop_id`),
  KEY `user_id` (`user_id`),
  KEY `shoprz_brandName` (`shoprz_brandName`),
  KEY `shopNameSuffix` (`shopNameSuffix`),
  KEY `rz_shopName` (`rz_shopName`),
  KEY `sort_order` (`sort_order`),
  KEY `merchants_audit` (`merchants_audit`),
  KEY `is_street` (`is_street`),
  KEY `shop_close` (`shop_close`),
  KEY `self_run` (`self_run`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_steps_fields`
--

DROP TABLE IF EXISTS `dsc_merchants_steps_fields`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_steps_fields` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `agreement` tinyint(1) unsigned NOT NULL,
  `steps_site` varchar(255) NOT NULL,
  `site_process` text NOT NULL,
  `contactName` varchar(255) NOT NULL COMMENT '联系人姓名',
  `contactPhone` varchar(255) NOT NULL COMMENT '联系人手机',
  `contactEmail` varchar(255) NOT NULL COMMENT '联系人电子邮箱',
  `organization_code` varchar(255) NOT NULL COMMENT '组织机构代码',
  `organization_fileImg` varchar(255) NOT NULL COMMENT '组织机构代码证电子版',
  `companyName` varchar(255) NOT NULL COMMENT '公司名称',
  `business_license_id` varchar(255) NOT NULL COMMENT '营业执照注册号',
  `legal_person` varchar(255) NOT NULL COMMENT '法定代表人姓名',
  `personalNo` varchar(255) NOT NULL COMMENT '身份证号',
  `legal_person_fileImg` varchar(255) NOT NULL COMMENT '法人身份证电子版',
  `license_comp_adress` varchar(255) NOT NULL COMMENT '营业执照所在地',
  `license_adress` varchar(255) NOT NULL COMMENT '营业执照详细地址',
  `establish_date` varchar(255) NOT NULL COMMENT '成立日期',
  `business_term` varchar(255) NOT NULL COMMENT '营业期限',
  `shopTime_term` tinyint(1) NOT NULL DEFAULT '0',
  `busines_scope` varchar(255) NOT NULL COMMENT '经营范围',
  `license_fileImg` varchar(255) NOT NULL COMMENT '营业执照副本电子版',
  `company_located` varchar(255) NOT NULL COMMENT '公司所在地',
  `company_adress` varchar(255) NOT NULL COMMENT '公司详细地址',
  `company_contactTel` varchar(255) NOT NULL COMMENT '公司电话',
  `company_tentactr` varchar(255) NOT NULL COMMENT '公司紧急联系人',
  `company_phone` varchar(255) NOT NULL COMMENT '公司紧急联系人手机',
  `taxpayer_id` varchar(255) NOT NULL COMMENT '纳税人识别号',
  `taxs_type` char(150) NOT NULL COMMENT '纳税人类型',
  `taxs_num` char(60) NOT NULL COMMENT '纳税类型税码',
  `tax_fileImg` varchar(255) NOT NULL COMMENT '税务登记证电子版',
  `status_tax_fileImg` varchar(255) NOT NULL COMMENT '一般纳税人资格证电子版',
  `company_name` varchar(255) NOT NULL COMMENT '公司名称',
  `account_number` varchar(255) NOT NULL COMMENT '公司银行账号',
  `bank_name` varchar(255) NOT NULL COMMENT '开户银行支行名称',
  `linked_bank_number` varchar(255) NOT NULL COMMENT '开户银行支行联行号',
  `linked_bank_address` varchar(255) NOT NULL COMMENT '开户银行支行所在地',
  `linked_bank_fileImg` varchar(255) NOT NULL COMMENT '银行开户许可证电子版',
  `company_type` char(180) NOT NULL COMMENT '公司类型',
  `company_website` varchar(255) NOT NULL COMMENT '公司官网地址',
  `company_sale` varchar(255) NOT NULL COMMENT '最近一年销售额',
  `shop_seller_have_experience` char(180) NOT NULL COMMENT '同类电子商务网站经验',
  `shop_website` varchar(255) NOT NULL COMMENT '网店地址',
  `shop_employee_num` varchar(255) NOT NULL COMMENT '网店运营人数',
  `shop_sale_num` char(180) NOT NULL COMMENT '可网售商品数量',
  `shop_average_price` char(180) NOT NULL COMMENT '预计平均客单价',
  `shop_warehouse_condition` char(180) NOT NULL COMMENT '仓库情况',
  `shop_warehouse_address` varchar(255) NOT NULL COMMENT '仓库地址',
  `shop_delicery_company` varchar(255) NOT NULL COMMENT '常用物流公司',
  `shop_erp_type` char(180) NOT NULL COMMENT 'ERP类型',
  `shop_operating_company` varchar(255) NOT NULL COMMENT '代运营公司名称',
  `shop_buy_ecmoban_store` varchar(180) NOT NULL COMMENT '是否会选择商创仓储',
  `shop_buy_delivery` char(180) NOT NULL COMMENT '是否会选择京东物流',
  `preVendorId` varchar(255) NOT NULL COMMENT '推荐码',
  `preVendorId_fileImg` varchar(255) NOT NULL COMMENT '电子版',
  `shop_vertical` char(180) NOT NULL COMMENT '垂直站',
  `registered_capital` varchar(255) NOT NULL COMMENT '注册资本',
  `contactXinbie` varchar(255) NOT NULL COMMENT '性别',
  PRIMARY KEY (`fid`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_steps_fields_centent`
--

DROP TABLE IF EXISTS `dsc_merchants_steps_fields_centent`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_steps_fields_centent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `textFields` text NOT NULL,
  `fieldsDateType` text NOT NULL,
  `fieldsLength` text NOT NULL,
  `fieldsNotnull` text NOT NULL,
  `fieldsFormName` text NOT NULL,
  `fieldsCoding` text NOT NULL,
  `fieldsForm` text NOT NULL,
  `fields_sort` text NOT NULL,
  `will_choose` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_steps_img`
--

DROP TABLE IF EXISTS `dsc_merchants_steps_img`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_steps_img` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `steps_img` varchar(255) NOT NULL,
  PRIMARY KEY (`gid`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_steps_process`
--

DROP TABLE IF EXISTS `dsc_merchants_steps_process`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_steps_process` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `process_steps` tinyint(1) unsigned NOT NULL,
  `process_title` varchar(255) NOT NULL,
  `process_article` int(11) unsigned NOT NULL,
  `steps_sort` int(11) unsigned NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `fields_next` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_merchants_steps_title`
--

DROP TABLE IF EXISTS `dsc_merchants_steps_title`;
CREATE TABLE IF NOT EXISTS `dsc_merchants_steps_title` (
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fields_steps` tinyint(1) unsigned NOT NULL,
  `fields_titles` varchar(255) NOT NULL,
  `steps_style` tinyint(3) unsigned NOT NULL,
  `titles_annotation` varchar(255) NOT NULL,
  `fields_special` text NOT NULL,
  `special_type` varchar(255) NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_nav`
--

DROP TABLE IF EXISTS `dsc_nav`;
CREATE TABLE IF NOT EXISTS `dsc_nav` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ctype` varchar(10) DEFAULT NULL,
  `cid` int(10) unsigned DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `ifshow` tinyint(1) NOT NULL,
  `vieworder` tinyint(1) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `ifshow` (`ifshow`),
  KEY `cid` (`cid`),
  KEY `vieworder` (`vieworder`),
  KEY `opennew` (`opennew`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_notice_log`
--

DROP TABLE IF EXISTS `dsc_notice_log`;
CREATE TABLE IF NOT EXISTS `dsc_notice_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(30) NOT NULL,
  `send_ok` tinyint(1) NOT NULL,
  `send_type` tinyint(1) NOT NULL DEFAULT '1',
  `send_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_offline_store`
--

DROP TABLE IF EXISTS `dsc_offline_store`;
CREATE TABLE IF NOT EXISTS `dsc_offline_store` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) NOT NULL DEFAULT '0',
  `stores_user` varchar(60) NOT NULL,
  `stores_pwd` varchar(32) NOT NULL,
  `stores_name` varchar(60) NOT NULL,
  `country` smallint(5) NOT NULL DEFAULT '0',
  `province` smallint(5) NOT NULL DEFAULT '0',
  `city` smallint(5) NOT NULL DEFAULT '0',
  `district` smallint(5) NOT NULL DEFAULT '0',
  `stores_address` varchar(255) NOT NULL,
  `stores_tel` varchar(60) NOT NULL,
  `stores_opening_hours` varchar(255) NOT NULL,
  `stores_traffic_line` varchar(255) NOT NULL,
  `stores_img` varchar(255) NOT NULL,
  `is_confirm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL,
  `ec_salt` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`),
  KEY `stores_user` (`stores_user`),
  KEY `ec_salt` (`ec_salt`),
  KEY `is_confirm` (`is_confirm`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_open_api`
--

DROP TABLE IF EXISTS `dsc_open_api`;
CREATE TABLE IF NOT EXISTS `dsc_open_api` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `app_key` varchar(225) NOT NULL,
  `action_code` text NOT NULL,
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_key` (`app_key`),
  KEY `is_open` (`is_open`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_action`
--

DROP TABLE IF EXISTS `dsc_order_action`;
CREATE TABLE IF NOT EXISTS `dsc_order_action` (
  `action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `action_user` varchar(30) NOT NULL DEFAULT '',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_place` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_note` varchar(255) NOT NULL DEFAULT '',
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`),
  KEY `order_id` (`order_id`),
  KEY `action_user` (`action_user`),
  KEY `order_status` (`order_status`),
  KEY `shipping_status` (`shipping_status`),
  KEY `pay_status` (`pay_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_delayed`
--

DROP TABLE IF EXISTS `dsc_order_delayed`;
CREATE TABLE IF NOT EXISTS `dsc_order_delayed` (
  `delayed_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL,
  `apply_day` tinyint(2) unsigned NOT NULL,
  `apply_time` int(10) unsigned NOT NULL,
  `review_status` tinyint(1) unsigned NOT NULL,
  `review_time` int(10) unsigned NOT NULL,
  `review_admin` int(10) unsigned NOT NULL,
  PRIMARY KEY (`delayed_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_goods`
--

DROP TABLE IF EXISTS `dsc_order_goods`;
CREATE TABLE IF NOT EXISTS `dsc_order_goods` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `cart_recid` text NOT NULL,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_number` smallint(5) unsigned NOT NULL DEFAULT '1',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_attr` text NOT NULL,
  `send_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_gift` int(10) unsigned NOT NULL DEFAULT '0',
  `model_attr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` text NOT NULL,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shopping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `warehouse_id` int(10) unsigned NOT NULL DEFAULT '0',
  `area_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_single` tinyint(1) DEFAULT '0',
  `freight` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `drp_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `commission_rate` varchar(10) NOT NULL DEFAULT '0',
  `stages_qishu` mediumint(5) NOT NULL DEFAULT '-1' COMMENT '用户选择的当前商品的分期期数 -1:不分期',
  PRIMARY KEY (`rec_id`),
  KEY `goods_id` (`goods_id`),
  KEY `order_id` (`order_id`),
  KEY `ru_id` (`ru_id`),
  KEY `freight` (`freight`),
  KEY `tid` (`tid`),
  KEY `stages_qishu` (`stages_qishu`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `is_real` (`is_real`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `area_id` (`area_id`),
  KEY `is_gift` (`is_gift`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_info`
--

DROP TABLE IF EXISTS `dsc_order_info`;
CREATE TABLE IF NOT EXISTS `dsc_order_info` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `main_order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_sn` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `country` int(10) unsigned NOT NULL DEFAULT '0',
  `province` int(10) unsigned NOT NULL DEFAULT '0',
  `city` int(10) unsigned NOT NULL DEFAULT '0',
  `district` int(10) unsigned NOT NULL DEFAULT '0',
  `street` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `best_time` varchar(120) NOT NULL DEFAULT '',
  `sign_building` varchar(120) NOT NULL DEFAULT '',
  `postscript` varchar(255) NOT NULL DEFAULT '',
  `shipping_id` text NOT NULL,
  `shipping_name` text NOT NULL,
  `shipping_code` text NOT NULL,
  `shipping_type` text NOT NULL,
  `pay_id` tinyint(3) NOT NULL DEFAULT '0',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `how_oos` varchar(120) NOT NULL DEFAULT '',
  `how_surplus` varchar(120) NOT NULL DEFAULT '',
  `pack_name` varchar(120) NOT NULL DEFAULT '',
  `card_name` varchar(120) NOT NULL DEFAULT '',
  `card_message` varchar(255) NOT NULL DEFAULT '',
  `inv_payee` varchar(120) NOT NULL DEFAULT '',
  `inv_content` varchar(120) NOT NULL DEFAULT '',
  `goods_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cost_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单成本',
  `shipping_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `insure_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pay_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pack_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `card_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `money_paid` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `surplus` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `integral` int(10) unsigned NOT NULL DEFAULT '0',
  `integral_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bonus` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `return_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单整站退款金额',
  `from_ad` smallint(5) NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `confirm_time` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_time` int(10) unsigned NOT NULL DEFAULT '0',
  `confirm_take_time` int(10) unsigned NOT NULL DEFAULT '0',
  `auto_delivery_time` int(11) unsigned NOT NULL DEFAULT '15',
  `pack_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `card_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bonus_id` int(10) unsigned NOT NULL DEFAULT '0',
  `invoice_no` varchar(255) NOT NULL DEFAULT '',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `extension_id` int(10) unsigned NOT NULL DEFAULT '0',
  `to_buyer` varchar(255) NOT NULL DEFAULT '',
  `pay_note` varchar(255) NOT NULL DEFAULT '',
  `agency_id` int(10) unsigned NOT NULL,
  `inv_type` varchar(60) NOT NULL,
  `tax` decimal(10,2) unsigned NOT NULL,
  `is_separate` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `discount` decimal(10,2) unsigned NOT NULL,
  `discount_all` decimal(10,2) unsigned NOT NULL,
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_settlement` tinyint(1) NOT NULL DEFAULT '0',
  `sign_time` int(30) DEFAULT NULL,
  `is_single` tinyint(1) DEFAULT '0',
  `point_id` smallint(8) unsigned NOT NULL DEFAULT '0',
  `shipping_dateStr` varchar(255) NOT NULL,
  `supplier_id` int(10) NOT NULL DEFAULT '0',
  `froms` char(10) NOT NULL DEFAULT 'pc',
  `coupons` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `uc_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_zc_order` int(10) DEFAULT '0',
  `zc_goods_id` int(10) NOT NULL,
  `is_frozen` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `chargeoff_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `invoice_type` tinyint(1) NOT NULL DEFAULT '0',
  `vat_id` int(10) NOT NULL DEFAULT '0' COMMENT '增值税发票信息ID 关联 users_vat_invoices_info表自增ID',
  `tax_id` varchar(255) NOT NULL DEFAULT '',
  `is_update_sale` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_sn` (`order_sn`),
  KEY `user_id` (`user_id`),
  KEY `order_status` (`order_status`),
  KEY `shipping_status` (`shipping_status`),
  KEY `pay_status` (`pay_status`),
  KEY `shipping_id` (`shipping_id`(333)),
  KEY `pay_id` (`pay_id`),
  KEY `extension_code` (`extension_code`,`extension_id`),
  KEY `agency_id` (`agency_id`),
  KEY `main_order_id` (`main_order_id`),
  KEY `uc_id` (`uc_id`),
  KEY `parent_id` (`parent_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `is_zc_order` (`is_zc_order`),
  KEY `zc_goods_id` (`zc_goods_id`),
  KEY `chargeoff_status` (`chargeoff_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_invoice`
--

DROP TABLE IF EXISTS `dsc_order_invoice`;
CREATE TABLE IF NOT EXISTS `dsc_order_invoice` (
  `invoice_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `inv_payee` varchar(100) NOT NULL,
  `tax_id` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`invoice_id`),
  KEY `user_id` (`user_id`),
  KEY `tax_id` (`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_print_setting`
--

DROP TABLE IF EXISTS `dsc_order_print_setting`;
CREATE TABLE IF NOT EXISTS `dsc_order_print_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `specification` varchar(50) NOT NULL,
  `printer` varchar(50) NOT NULL,
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_print_size`
--

DROP TABLE IF EXISTS `dsc_order_print_size`;
CREATE TABLE IF NOT EXISTS `dsc_order_print_size` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `specification` varchar(50) NOT NULL,
  `width` varchar(50) NOT NULL,
  `height` varchar(50) NOT NULL,
  `size` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_return`
--

DROP TABLE IF EXISTS `dsc_order_return`;
CREATE TABLE IF NOT EXISTS `dsc_order_return` (
  `ret_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '退换货id',
  `return_sn` varchar(20) NOT NULL,
  `goods_id` int(13) NOT NULL COMMENT '商品唯一id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `rec_id` int(10) NOT NULL COMMENT '订单商品唯一id',
  `order_id` int(10) NOT NULL COMMENT '所属订单号',
  `order_sn` varchar(20) NOT NULL,
  `credentials` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `maintain` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `back` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退货标识',
  `goods_attr` text NOT NULL COMMENT '退货颜色属性',
  `exchange` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '换货标识',
  `return_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attr_val` text NOT NULL COMMENT '换货属性',
  `cause_id` int(10) NOT NULL COMMENT '退换货原因',
  `apply_time` int(10) NOT NULL DEFAULT '0' COMMENT '退换货申请时间',
  `return_time` int(10) NOT NULL DEFAULT '0' COMMENT '退换货时间',
  `should_return` decimal(10,2) NOT NULL COMMENT '应退金额',
  `actual_return` decimal(10,2) NOT NULL COMMENT '实退金额',
  `return_shipping_fee` decimal(10,2) unsigned NOT NULL,
  `return_brief` varchar(2000) NOT NULL,
  `remark` varchar(2000) NOT NULL COMMENT '备注',
  `country` int(10) NOT NULL COMMENT '国家',
  `province` int(10) NOT NULL COMMENT '省份',
  `city` int(10) NOT NULL COMMENT '城市',
  `district` int(10) NOT NULL COMMENT '区',
  `street` int(10) unsigned NOT NULL DEFAULT '0',
  `addressee` varchar(30) NOT NULL COMMENT '收件人',
  `phone` char(22) NOT NULL COMMENT '联系电话',
  `address` varchar(100) NOT NULL COMMENT '详细地址',
  `zipcode` int(6) DEFAULT NULL COMMENT '邮编',
  `is_check` tinyint(3) NOT NULL COMMENT '是否审核0：未审核1：已审核',
  `return_status` tinyint(3) NOT NULL COMMENT '退换货状态',
  `refound_status` tinyint(3) NOT NULL COMMENT '退款状态',
  `back_shipping_name` varchar(30) NOT NULL COMMENT '退回快递名称',
  `back_other_shipping` varchar(30) NOT NULL COMMENT '其他快递名称',
  `back_invoice_no` varchar(50) NOT NULL COMMENT '退回快递单号',
  `out_shipping_name` varchar(30) NOT NULL COMMENT '换出快递名称',
  `out_invoice_no` varchar(50) NOT NULL COMMENT '换出快递单号',
  `agree_apply` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `chargeoff_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `activation_number` tinyint(3) NOT NULL DEFAULT '0',
  `refund_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ret_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `rec_id` (`rec_id`),
  KEY `order_sn` (`order_sn`),
  KEY `return_sn` (`return_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_order_return_extend`
--

DROP TABLE IF EXISTS `dsc_order_return_extend`;
CREATE TABLE IF NOT EXISTS `dsc_order_return_extend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ret_id` int(10) unsigned NOT NULL,
  `return_number` mediumint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ret_id` (`ret_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_oss_configure`
--

DROP TABLE IF EXISTS `dsc_oss_configure`;
CREATE TABLE IF NOT EXISTS `dsc_oss_configure` (
  `id` smallint(8) NOT NULL AUTO_INCREMENT,
  `bucket` varchar(255) NOT NULL,
  `keyid` varchar(255) NOT NULL,
  `keysecret` varchar(255) NOT NULL,
  `is_cname` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `endpoint` varchar(255) NOT NULL,
  `regional` varchar(100) NOT NULL,
  `is_use` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_delimg` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_use` (`is_use`),
  KEY `is_delimg` (`is_delimg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_pack`
--

DROP TABLE IF EXISTS `dsc_pack`;
CREATE TABLE IF NOT EXISTS `dsc_pack` (
  `pack_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `pack_name` varchar(120) NOT NULL DEFAULT '',
  `pack_img` varchar(255) NOT NULL DEFAULT '',
  `pack_fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `free_money` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pack_desc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pack_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_package_goods`
--

DROP TABLE IF EXISTS `dsc_package_goods`;
CREATE TABLE IF NOT EXISTS `dsc_package_goods` (
  `package_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '1',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`package_id`,`goods_id`,`admin_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_partner_list`
--

DROP TABLE IF EXISTS `dsc_partner_list`;
CREATE TABLE IF NOT EXISTS `dsc_partner_list` (
  `link_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_logo` varchar(255) NOT NULL DEFAULT '',
  `show_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  PRIMARY KEY (`link_id`),
  KEY `show_order` (`show_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_payment`
--

DROP TABLE IF EXISTS `dsc_payment`;
CREATE TABLE IF NOT EXISTS `dsc_payment` (
  `pay_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(20) NOT NULL DEFAULT '',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `pay_fee` varchar(10) NOT NULL DEFAULT '0',
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`),
  KEY `is_online` (`is_online`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_pay_card`
--

DROP TABLE IF EXISTS `dsc_pay_card`;
CREATE TABLE IF NOT EXISTS `dsc_pay_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_number` varchar(60) NOT NULL,
  `card_psd` varchar(40) NOT NULL,
  `user_id` int(20) NOT NULL,
  `used_time` varchar(40) NOT NULL,
  `status` smallint(5) unsigned DEFAULT '0',
  `c_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_number` (`card_number`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_pay_card_type`
--

DROP TABLE IF EXISTS `dsc_pay_card_type`;
CREATE TABLE IF NOT EXISTS `dsc_pay_card_type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(40) NOT NULL,
  `type_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `type_prefix` varchar(10) NOT NULL,
  `use_end_date` varchar(60) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_pay_log`
--

DROP TABLE IF EXISTS `dsc_pay_log`;
CREATE TABLE IF NOT EXISTS `dsc_pay_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order_amount` decimal(10,2) unsigned NOT NULL,
  `order_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `order_id` (`order_id`),
  KEY `is_paid` (`is_paid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_pic_album`
--

DROP TABLE IF EXISTS `dsc_pic_album`;
CREATE TABLE IF NOT EXISTS `dsc_pic_album` (
  `pic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pic_name` varchar(100) NOT NULL,
  `album_id` int(10) unsigned NOT NULL,
  `pic_file` varchar(255) NOT NULL,
  `pic_thumb` varchar(255) NOT NULL,
  `pic_image` varchar(255) NOT NULL,
  `pic_size` int(10) unsigned NOT NULL,
  `pic_spec` varchar(100) NOT NULL,
  `ru_id` int(10) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pic_id`),
  KEY `album_id` (`album_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=165 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_plugins`
--

DROP TABLE IF EXISTS `dsc_plugins`;
CREATE TABLE IF NOT EXISTS `dsc_plugins` (
  `code` varchar(30) NOT NULL DEFAULT '',
  `version` varchar(10) NOT NULL DEFAULT '',
  `library` varchar(255) NOT NULL DEFAULT '',
  `assign` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `install_date` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_presale_activity`
--

DROP TABLE IF EXISTS `dsc_presale_activity`;
CREATE TABLE IF NOT EXISTS `dsc_presale_activity` (
  `act_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL,
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL,
  `goods_name` varchar(255) NOT NULL,
  `act_desc` text NOT NULL,
  `deposit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `pay_start_time` int(10) unsigned NOT NULL,
  `pay_end_time` int(10) unsigned NOT NULL,
  `is_finished` tinyint(1) unsigned NOT NULL,
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  `pre_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`act_id`),
  KEY `review_status` (`review_status`),
  KEY `goods_id` (`goods_id`),
  KEY `goods_name` (`goods_name`),
  KEY `user_id` (`user_id`),
  KEY `cat_id` (`cat_id`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_presale_cat`
--

DROP TABLE IF EXISTS `dsc_presale_cat`;
CREATE TABLE IF NOT EXISTS `dsc_presale_cat` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(90) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `cat_desc` varchar(255) NOT NULL,
  `measure_unit` varchar(15) NOT NULL,
  `show_in_nav` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `style` varchar(150) NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `grade` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `filter_attr` varchar(225) NOT NULL,
  `is_top_style` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `top_style_tpl` varchar(255) NOT NULL,
  `cat_icon` varchar(255) NOT NULL,
  `is_top_show` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category_links` text NOT NULL,
  `category_topic` text NOT NULL,
  `pinyin_keyword` text NOT NULL,
  `cat_alias_name` varchar(90) NOT NULL,
  `template_file` varchar(50) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(1) NOT NULL DEFAULT '50',
  PRIMARY KEY (`cat_id`),
  KEY `parent_id` (`parent_id`),
  KEY `is_show` (`is_show`),
  KEY `cat_name` (`cat_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_products`
--

DROP TABLE IF EXISTS `dsc_products`;
CREATE TABLE IF NOT EXISTS `dsc_products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text,
  `product_sn` varchar(60) DEFAULT NULL,
  `bar_code` varchar(60) NOT NULL,
  `product_number` int(10) unsigned DEFAULT '0',
  `product_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_sn` (`product_sn`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_products_area`
--

DROP TABLE IF EXISTS `dsc_products_area`;
CREATE TABLE IF NOT EXISTS `dsc_products_area` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text,
  `product_sn` varchar(60) DEFAULT NULL,
  `bar_code` varchar(60) NOT NULL,
  `product_number` int(10) unsigned DEFAULT '0',
  `product_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `area_id` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '10',
  PRIMARY KEY (`product_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_sn` (`product_sn`),
  KEY `admin_id` (`admin_id`),
  KEY `area_id` (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_products_changelog`
--

DROP TABLE IF EXISTS `dsc_products_changelog`;
CREATE TABLE IF NOT EXISTS `dsc_products_changelog` (
  `product_id` int(10) NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` varchar(50) NOT NULL,
  `product_sn` varchar(60) NOT NULL,
  `bar_code` varchar(60) NOT NULL,
  `product_number` int(10) unsigned NOT NULL DEFAULT '0',
  `product_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_warn_number` int(10) unsigned NOT NULL DEFAULT '0',
  `warehouse_id` int(11) unsigned NOT NULL DEFAULT '0',
  `area_id` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `goods_id` (`goods_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `area_id` (`area_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_products_warehouse`
--

DROP TABLE IF EXISTS `dsc_products_warehouse`;
CREATE TABLE IF NOT EXISTS `dsc_products_warehouse` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text,
  `product_sn` varchar(60) DEFAULT NULL,
  `bar_code` varchar(60) NOT NULL,
  `product_number` int(10) unsigned DEFAULT '0',
  `product_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `product_warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `warehouse_id` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_sn` (`product_sn`),
  KEY `admin_id` (`admin_id`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_region`
--

DROP TABLE IF EXISTS `dsc_region`;
CREATE TABLE IF NOT EXISTS `dsc_region` (
  `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `region_name` varchar(120) NOT NULL DEFAULT '',
  `region_type` tinyint(1) NOT NULL DEFAULT '2',
  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`region_type`),
  KEY `agency_id` (`agency_id`),
  KEY `region_name` (`region_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3412 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_region_backup`
--

DROP TABLE IF EXISTS `dsc_region_backup`;
CREATE TABLE IF NOT EXISTS `dsc_region_backup` (
  `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `region_name` varchar(120) NOT NULL DEFAULT '',
  `region_type` tinyint(1) NOT NULL DEFAULT '2',
  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`region_type`),
  KEY `agency_id` (`agency_id`),
  KEY `region_name` (`region_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3412 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_region_store`
--

DROP TABLE IF EXISTS `dsc_region_store`;
CREATE TABLE IF NOT EXISTS `dsc_region_store` (
  `rs_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rs_name` varchar(50) NOT NULL,
  PRIMARY KEY (`rs_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_region_warehouse`
--

DROP TABLE IF EXISTS `dsc_region_warehouse`;
CREATE TABLE IF NOT EXISTS `dsc_region_warehouse` (
  `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `regionId` int(11) unsigned NOT NULL,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `region_name` varchar(120) NOT NULL DEFAULT '',
  `region_code` varchar(255) NOT NULL DEFAULT '',
  `region_type` tinyint(1) NOT NULL DEFAULT '2',
  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`region_type`),
  KEY `agency_id` (`agency_id`),
  KEY `regionId` (`regionId`),
  KEY `region_code` (`region_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=811 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_reg_extend_info`
--

DROP TABLE IF EXISTS `dsc_reg_extend_info`;
CREATE TABLE IF NOT EXISTS `dsc_reg_extend_info` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_field_id` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_reg_fields`
--

DROP TABLE IF EXISTS `dsc_reg_fields`;
CREATE TABLE IF NOT EXISTS `dsc_reg_fields` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `reg_field_name` varchar(60) NOT NULL,
  `dis_order` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_need` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_return_action`
--

DROP TABLE IF EXISTS `dsc_return_action`;
CREATE TABLE IF NOT EXISTS `dsc_return_action` (
  `action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ret_id` int(10) unsigned NOT NULL DEFAULT '0',
  `action_user` varchar(30) NOT NULL DEFAULT '',
  `return_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `refound_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_place` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action_note` varchar(255) NOT NULL DEFAULT '',
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`),
  KEY `order_id` (`ret_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_return_cause`
--

DROP TABLE IF EXISTS `dsc_return_cause`;
CREATE TABLE IF NOT EXISTS `dsc_return_cause` (
  `cause_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `cause_name` varchar(50) NOT NULL COMMENT '退换货原因',
  `parent_id` int(11) NOT NULL COMMENT '父级id',
  `sort_order` int(10) NOT NULL COMMENT '排序',
  `is_show` tinyint(3) NOT NULL COMMENT '是否显示',
  PRIMARY KEY (`cause_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_return_goods`
--

DROP TABLE IF EXISTS `dsc_return_goods`;
CREATE TABLE IF NOT EXISTS `dsc_return_goods` (
  `rg_id` int(10) NOT NULL AUTO_INCREMENT,
  `rec_id` int(10) unsigned NOT NULL,
  `ret_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `product_sn` varchar(60) DEFAULT NULL,
  `goods_name` varchar(120) DEFAULT NULL,
  `brand_name` varchar(60) DEFAULT NULL,
  `goods_sn` varchar(60) DEFAULT NULL,
  `is_real` tinyint(1) unsigned DEFAULT '0',
  `goods_attr` text,
  `attr_id` varchar(255) NOT NULL,
  `return_type` tinyint(1) NOT NULL DEFAULT '0',
  `return_number` int(11) unsigned NOT NULL DEFAULT '0',
  `out_attr` text NOT NULL,
  `return_attr_id` varchar(255) NOT NULL,
  `refound` decimal(10,2) NOT NULL,
  PRIMARY KEY (`rg_id`),
  KEY `goods_id` (`goods_id`),
  KEY `rec_id` (`rec_id`),
  KEY `ret_id` (`ret_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_return_images`
--

DROP TABLE IF EXISTS `dsc_return_images`;
CREATE TABLE IF NOT EXISTS `dsc_return_images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rec_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `img_file` varchar(255) NOT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rec_id` (`rec_id`),
  KEY `user_id` (`user_id`),
  KEY `rg_id` (`rg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_role`
--

DROP TABLE IF EXISTS `dsc_role`;
CREATE TABLE IF NOT EXISTS `dsc_role` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(60) NOT NULL DEFAULT '',
  `action_list` text NOT NULL,
  `role_describe` text,
  PRIMARY KEY (`role_id`),
  KEY `user_name` (`role_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_rs_region`
--

DROP TABLE IF EXISTS `dsc_rs_region`;
CREATE TABLE IF NOT EXISTS `dsc_rs_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rs_id` int(10) unsigned NOT NULL DEFAULT '0',
  `region_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rs_id` (`rs_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_sale_notice`
--

DROP TABLE IF EXISTS `dsc_sale_notice`;
CREATE TABLE IF NOT EXISTS `dsc_sale_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `cellphone` varchar(16) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `hopeDiscount` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '2',
  `send_type` tinyint(1) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  `mark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_searchengine`
--

DROP TABLE IF EXISTS `dsc_searchengine`;
CREATE TABLE IF NOT EXISTS `dsc_searchengine` (
  `date` date NOT NULL DEFAULT '0000-00-00',
  `searchengine` varchar(20) NOT NULL DEFAULT '',
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`date`,`searchengine`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_search_keyword`
--

DROP TABLE IF EXISTS `dsc_search_keyword`;
CREATE TABLE IF NOT EXISTS `dsc_search_keyword` (
  `keyword_id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(500) NOT NULL,
  `pinyin` varchar(1000) DEFAULT NULL,
  `is_on` tinyint(1) unsigned DEFAULT NULL,
  `count` int(32) NOT NULL,
  `addtime` varchar(20) DEFAULT NULL,
  `pinyin_keyword` varchar(2000) DEFAULT '',
  `result_count` int(32) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`keyword_id`),
  KEY `keyword` (`keyword`(255))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seckill`
--

DROP TABLE IF EXISTS `dsc_seckill`;
CREATE TABLE IF NOT EXISTS `dsc_seckill` (
  `sec_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '秒杀活动自增ID',
  `ru_id` int(10) unsigned NOT NULL COMMENT '商家ID',
  `acti_title` varchar(50) NOT NULL COMMENT '秒杀活动标题',
  `begin_time` int(11) NOT NULL,
  `is_putaway` tinyint(1) NOT NULL DEFAULT '1' COMMENT '上下架',
  `acti_time` int(11) NOT NULL COMMENT '秒杀活动日期',
  `add_time` int(11) NOT NULL COMMENT '秒杀活动添加时间',
  `review_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核状态',
  PRIMARY KEY (`sec_id`),
  KEY `ru_id` (`ru_id`),
  KEY `review_status` (`review_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seckill_goods`
--

DROP TABLE IF EXISTS `dsc_seckill_goods`;
CREATE TABLE IF NOT EXISTS `dsc_seckill_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tb_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '秒杀时段ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sec_price` decimal(10,2) NOT NULL,
  `sec_num` smallint(5) NOT NULL,
  `sec_limit` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sec_id` (`sec_id`),
  KEY `tb_id` (`tb_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seckill_goods_remind`
--

DROP TABLE IF EXISTS `dsc_seckill_goods_remind`;
CREATE TABLE IF NOT EXISTS `dsc_seckill_goods_remind` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增秒杀提醒ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sec_goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '秒杀商品ID',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`r_id`),
  KEY `user_id` (`user_id`),
  KEY `sec_goods_id` (`sec_goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='秒杀商品提醒关联表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seckill_time_bucket`
--

DROP TABLE IF EXISTS `dsc_seckill_time_bucket`;
CREATE TABLE IF NOT EXISTS `dsc_seckill_time_bucket` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `begin_time` time NOT NULL COMMENT '开始时间段',
  `end_time` time NOT NULL COMMENT '结束时间段',
  `title` varchar(50) NOT NULL COMMENT '秒杀时段标题',
  PRIMARY KEY (`id`),
  UNIQUE KEY `begin_time` (`begin_time`,`end_time`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_account_log`
--

DROP TABLE IF EXISTS `dsc_seller_account_log`;
CREATE TABLE IF NOT EXISTS `dsc_seller_account_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `real_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '实名认证ID',
  `ru_id` int(10) NOT NULL COMMENT '商家ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商家账户金额',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `certificate_img` varchar(255) NOT NULL,
  `deposit_mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `log_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型(1/4:提现 2:结算 3:充值)',
  `apply_sn` varchar(225) NOT NULL,
  `pay_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款方式ID',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `admin_note` varchar(225) NOT NULL COMMENT '管理员回复信息',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `seller_note` varchar(225) NOT NULL COMMENT '操作描述',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否付款',
  `percent_value` varchar(20) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `real_id` (`real_id`),
  KEY `admin_id` (`admin_id`),
  KEY `ru_id` (`ru_id`),
  KEY `pay_id` (`pay_id`),
  KEY `log_type` (`log_type`),
  KEY `is_paid` (`is_paid`),
  KEY `add_time` (`add_time`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_apply_info`
--

DROP TABLE IF EXISTS `dsc_seller_apply_info`;
CREATE TABLE IF NOT EXISTS `dsc_seller_apply_info` (
  `apply_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ru_id` mediumint(10) NOT NULL DEFAULT '0',
  `grade_id` mediumint(8) NOT NULL DEFAULT '0',
  `apply_sn` varchar(20) NOT NULL,
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `apply_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payable_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `back_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fee_num` smallint(5) unsigned NOT NULL DEFAULT '1',
  `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `entry_criteria` text NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `is_confirm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL,
  `pay_id` tinyint(3) NOT NULL DEFAULT '0',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `confirm_time` int(10) unsigned NOT NULL,
  `reply_seller` varchar(255) NOT NULL,
  `valid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`apply_id`),
  KEY `ru_id` (`ru_id`),
  KEY `grade_id` (`grade_id`),
  KEY `apply_sn` (`apply_sn`),
  KEY `pay_status` (`pay_status`),
  KEY `apply_status` (`apply_status`),
  KEY `pay_id` (`pay_id`),
  KEY `is_paid` (`is_paid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_bill_goods`
--

DROP TABLE IF EXISTS `dsc_seller_bill_goods`;
CREATE TABLE IF NOT EXISTS `dsc_seller_bill_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `proportion` varchar(20) NOT NULL DEFAULT '',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `dis_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品单品满减优惠金额',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text NOT NULL,
  `drp_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `commission_rate` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rec_id` (`rec_id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_bill_order`
--

DROP TABLE IF EXISTS `dsc_seller_bill_order`;
CREATE TABLE IF NOT EXISTS `dsc_seller_bill_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `seller_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_sn` varchar(255) NOT NULL DEFAULT '',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `return_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `return_shippingfee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `insure_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pay_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pack_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `card_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bonus` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `integral_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `coupons` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `value_card` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `money_paid` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `surplus` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `drp_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `confirm_take_time` int(10) unsigned NOT NULL DEFAULT '0',
  `chargeoff_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  UNIQUE KEY `order_sn` (`order_sn`),
  KEY `seller_id` (`seller_id`),
  KEY `user_id` (`user_id`),
  KEY `bill_id` (`bill_id`),
  KEY `order_status` (`order_status`),
  KEY `shipping_status` (`shipping_status`),
  KEY `chargeoff_status` (`chargeoff_status`),
  KEY `confirm_take_time` (`confirm_take_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_commission_bill`
--

DROP TABLE IF EXISTS `dsc_seller_commission_bill`;
CREATE TABLE IF NOT EXISTS `dsc_seller_commission_bill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bill_sn` varchar(255) NOT NULL DEFAULT '',
  `order_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `shipping_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `return_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `return_shippingfee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `proportion` varchar(20) NOT NULL DEFAULT '',
  `commission_model` tinyint(1) NOT NULL DEFAULT '-1',
  `gain_commission` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `should_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `actual_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实结金额（账单结束）',
  `chargeoff_time` int(10) unsigned NOT NULL DEFAULT '0',
  `settleaccounts_time` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `chargeoff_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bill_cycle` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `bill_apply` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `apply_note` varchar(255) NOT NULL DEFAULT '',
  `apply_time` int(10) unsigned NOT NULL DEFAULT '0',
  `operator` varchar(255) NOT NULL DEFAULT '',
  `check_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reject_note` varchar(255) NOT NULL DEFAULT '',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0',
  `frozen_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `frozen_data` smallint(5) unsigned NOT NULL DEFAULT '0',
  `frozen_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  KEY `bill_cycle` (`bill_cycle`),
  KEY `bill_sn` (`bill_sn`),
  KEY `chargeoff_time` (`chargeoff_time`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `chargeoff_status` (`chargeoff_status`),
  KEY `bill_apply` (`bill_apply`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_domain`
--

DROP TABLE IF EXISTS `dsc_seller_domain`;
CREATE TABLE IF NOT EXISTS `dsc_seller_domain` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(60) NOT NULL,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0',
  `validity_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_grade`
--

DROP TABLE IF EXISTS `dsc_seller_grade`;
CREATE TABLE IF NOT EXISTS `dsc_seller_grade` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `grade_name` varchar(255) NOT NULL,
  `goods_sun` int(255) NOT NULL,
  `seller_temp` int(255) NOT NULL,
  `favorable_rate` varchar(20) NOT NULL,
  `give_integral` smallint(8) unsigned NOT NULL,
  `rank_integral` smallint(8) unsigned NOT NULL,
  `pay_integral` smallint(8) NOT NULL,
  `white_bar` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `grade_introduce` varchar(255) NOT NULL,
  `entry_criteria` text NOT NULL,
  `grade_img` varchar(255) NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_qrcode`
--

DROP TABLE IF EXISTS `dsc_seller_qrcode`;
CREATE TABLE IF NOT EXISTS `dsc_seller_qrcode` (
  `qrcode_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ru_id` int(11) NOT NULL,
  `qrcode_thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`qrcode_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_shopbg`
--

DROP TABLE IF EXISTS `dsc_seller_shopbg`;
CREATE TABLE IF NOT EXISTS `dsc_seller_shopbg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bgimg` varchar(500) NOT NULL COMMENT '背景图片',
  `bgrepeat` varchar(50) NOT NULL DEFAULT 'no-repeat' COMMENT '背景图片重复',
  `bgcolor` varchar(20) NOT NULL COMMENT '背景颜色',
  `show_img` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认显示背景图片',
  `is_custom` int(1) NOT NULL DEFAULT '0' COMMENT '是否自定义背景，默认为否',
  `ru_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `seller_theme` varchar(50) NOT NULL COMMENT '模板',
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_shopheader`
--

DROP TABLE IF EXISTS `dsc_seller_shopheader`;
CREATE TABLE IF NOT EXISTS `dsc_seller_shopheader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `headtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `headbg_img` varchar(255) CHARACTER SET latin1 NOT NULL,
  `shop_color` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `seller_theme` varchar(100) NOT NULL,
  `ru_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_shopinfo`
--

DROP TABLE IF EXISTS `dsc_seller_shopinfo`;
CREATE TABLE IF NOT EXISTS `dsc_seller_shopinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商店id',
  `ru_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '入驻商家id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `shop_title` varchar(50) NOT NULL COMMENT '店铺标题',
  `shop_keyword` varchar(50) NOT NULL COMMENT '店铺关键字',
  `country` int(10) NOT NULL COMMENT '所在国家',
  `province` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所在省份',
  `city` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所在城市',
  `district` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_address` varchar(50) NOT NULL COMMENT '详细地址',
  `seller_email` varchar(120) CHARACTER SET latin1 NOT NULL,
  `kf_qq` varchar(120) NOT NULL COMMENT '客服qq',
  `kf_ww` varchar(120) NOT NULL COMMENT '客服旺旺',
  `meiqia` varchar(20) NOT NULL,
  `kf_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `kf_tel` varchar(50) NOT NULL COMMENT '客服电话',
  `site_head` varchar(125) CHARACTER SET latin1 NOT NULL,
  `mobile` char(11) CHARACTER SET latin1 NOT NULL,
  `shop_logo` varchar(255) NOT NULL COMMENT '店铺logo',
  `logo_thumb` varchar(255) CHARACTER SET latin1 NOT NULL,
  `street_thumb` varchar(255) CHARACTER SET latin1 NOT NULL,
  `brand_thumb` varchar(255) CHARACTER SET latin1 NOT NULL,
  `notice` varchar(100) NOT NULL COMMENT '店铺公告',
  `street_desc` varchar(255) NOT NULL,
  `shop_header` text COMMENT '店铺头部',
  `shop_color` varchar(20) DEFAULT NULL COMMENT '店铺整体色调',
  `shop_style` tinyint(1) NOT NULL DEFAULT '1' COMMENT '店铺样式1显示左侧信息和分类，0不显示左侧信息和分类',
  `shop_close` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否关闭店铺（0：关闭，1：开启）',
  `apply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否申请加入店铺街，0否，1是',
  `is_street` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否以加入店铺街，0否，1是',
  `remark` varchar(100) NOT NULL COMMENT '网站管理员备注信息',
  `seller_theme` varchar(20) NOT NULL,
  `win_goods_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `store_style` varchar(20) NOT NULL,
  `check_sellername` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shopname_audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `shipping_id` smallint(8) unsigned NOT NULL DEFAULT '0',
  `shipping_date` varchar(255) CHARACTER SET latin1 NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `tengxun_key` varchar(255) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `kf_appkey` int(11) NOT NULL DEFAULT '0' COMMENT '在线客服appkey',
  `kf_touid` varchar(255) NOT NULL DEFAULT '' COMMENT '在线客服账号(旺旺号)',
  `kf_logo` varchar(255) NOT NULL DEFAULT 'http://' COMMENT '在线客服头像',
  `kf_welcomeMsg` varchar(255) NOT NULL DEFAULT '' COMMENT '在线客服欢迎信息',
  `kf_secretkey` char(32) NOT NULL DEFAULT '' COMMENT 'appkeySecret',
  `user_menu` text NOT NULL COMMENT '店铺快捷菜单',
  `kf_im_switch` tinyint(4) NOT NULL DEFAULT '1',
  `seller_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `seller_templates` varchar(160) NOT NULL,
  `templates_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `js_appkey` varchar(50) NOT NULL,
  `js_appsecret` varchar(50) NOT NULL,
  `print_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `kdniao_printer` varchar(50) NOT NULL,
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`),
  KEY `shipping_id` (`shipping_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_shopinfo_changelog`
--

DROP TABLE IF EXISTS `dsc_seller_shopinfo_changelog`;
CREATE TABLE IF NOT EXISTS `dsc_seller_shopinfo_changelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `data_key` varchar(50) NOT NULL,
  `data_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_shopslide`
--

DROP TABLE IF EXISTS `dsc_seller_shopslide`;
CREATE TABLE IF NOT EXISTS `dsc_seller_shopslide` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `ru_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻商家id',
  `img_url` varchar(100) NOT NULL COMMENT '图片地址',
  `img_link` varchar(100) NOT NULL COMMENT '图片超链接',
  `img_desc` varchar(50) NOT NULL COMMENT '图片描述',
  `img_order` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `slide_type` varchar(50) NOT NULL DEFAULT 'roll' COMMENT '图片变换方式''roll'',''shade'',默认是''roll''',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `seller_theme` varchar(20) CHARACTER SET latin1 NOT NULL,
  `install_img` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=141 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_shopwindow`
--

DROP TABLE IF EXISTS `dsc_seller_shopwindow`;
CREATE TABLE IF NOT EXISTS `dsc_seller_shopwindow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `win_type` smallint(1) NOT NULL COMMENT '橱窗类型0商品列表，1自定义内容',
  `win_goods_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `win_order` smallint(6) NOT NULL COMMENT '橱窗排序',
  `win_goods` text COMMENT '橱窗商品',
  `win_name` varchar(50) NOT NULL COMMENT '橱窗名称',
  `win_color` char(10) NOT NULL COMMENT '橱窗色调',
  `win_img` varchar(100) NOT NULL COMMENT '橱窗广告图片，暂时无用',
  `win_img_link` varchar(100) NOT NULL COMMENT '广告图片链接，暂时无用',
  `ru_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '入驻商id',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `win_custom` text NOT NULL COMMENT '店铺自定义橱窗内容',
  `seller_theme` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=805 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seller_template_apply`
--

DROP TABLE IF EXISTS `dsc_seller_template_apply`;
CREATE TABLE IF NOT EXISTS `dsc_seller_template_apply` (
  `apply_id` int(10) NOT NULL AUTO_INCREMENT,
  `apply_sn` varchar(20) NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `temp_id` int(10) unsigned NOT NULL DEFAULT '0',
  `temp_code` varchar(60) NOT NULL,
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `apply_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`apply_id`),
  KEY `apply_sn` (`apply_sn`),
  KEY `ru_id` (`ru_id`),
  KEY `temp_id` (`temp_id`),
  KEY `pay_id` (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_seo`
--

DROP TABLE IF EXISTS `dsc_seo`;
CREATE TABLE IF NOT EXISTS `dsc_seo` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键词',
  `description` text NOT NULL COMMENT '描述',
  `type` varchar(20) NOT NULL COMMENT '类型',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='SEO信息存放表';

-- --------------------------------------------------------

--
-- 表的结构 `dsc_sessions`
--

DROP TABLE IF EXISTS `dsc_sessions`;
CREATE TABLE IF NOT EXISTS `dsc_sessions` (
  `sesskey` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `expiry` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `adminid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `user_name` varchar(60) NOT NULL,
  `user_rank` tinyint(3) NOT NULL,
  `discount` decimal(3,2) NOT NULL,
  `email` varchar(60) NOT NULL,
  `data` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_sessions_data`
--

DROP TABLE IF EXISTS `dsc_sessions_data`;
CREATE TABLE IF NOT EXISTS `dsc_sessions_data` (
  `sesskey` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `expiry` int(10) unsigned NOT NULL DEFAULT '0',
  `data` longtext NOT NULL,
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_shipping`
--

DROP TABLE IF EXISTS `dsc_shipping`;
CREATE TABLE IF NOT EXISTS `dsc_shipping` (
  `shipping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_code` varchar(20) NOT NULL DEFAULT '',
  `shipping_name` varchar(120) NOT NULL DEFAULT '',
  `shipping_desc` varchar(255) NOT NULL DEFAULT '',
  `insure` varchar(10) NOT NULL DEFAULT '0',
  `support_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipping_print` text NOT NULL,
  `print_bg` varchar(255) DEFAULT NULL,
  `config_lable` text,
  `print_model` tinyint(1) DEFAULT '0',
  `shipping_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `customer_name` varchar(50) NOT NULL,
  `customer_pwd` varchar(50) NOT NULL,
  `month_code` varchar(50) NOT NULL,
  `send_site` varchar(50) NOT NULL,
  PRIMARY KEY (`shipping_id`),
  KEY `shipping_code` (`shipping_code`,`enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_shipping_area`
--

DROP TABLE IF EXISTS `dsc_shipping_area`;
CREATE TABLE IF NOT EXISTS `dsc_shipping_area` (
  `shipping_area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_area_name` varchar(150) NOT NULL DEFAULT '',
  `shipping_id` int(10) unsigned NOT NULL DEFAULT '0',
  `configure` text NOT NULL,
  `ru_id` int(10) NOT NULL,
  PRIMARY KEY (`shipping_area_id`),
  KEY `shipping_id` (`shipping_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_shipping_date`
--

DROP TABLE IF EXISTS `dsc_shipping_date`;
CREATE TABLE IF NOT EXISTS `dsc_shipping_date` (
  `shipping_date_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` varchar(10) NOT NULL DEFAULT '0',
  `end_date` varchar(10) NOT NULL DEFAULT '0',
  `select_day` int(10) unsigned NOT NULL DEFAULT '0',
  `select_date` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`shipping_date_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_shipping_point`
--

DROP TABLE IF EXISTS `dsc_shipping_point`;
CREATE TABLE IF NOT EXISTS `dsc_shipping_point` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_area_id` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `address` varchar(255) NOT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `anchor` varchar(30) NOT NULL,
  `line` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shipping_area_id` (`shipping_area_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_shipping_tpl`
--

DROP TABLE IF EXISTS `dsc_shipping_tpl`;
CREATE TABLE IF NOT EXISTS `dsc_shipping_tpl` (
  `st_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `print_bg` varchar(255) NOT NULL,
  `print_model` tinyint(1) NOT NULL,
  `config_lable` text NOT NULL,
  `shipping_print` text NOT NULL,
  `update_time` varchar(255) NOT NULL,
  PRIMARY KEY (`st_id`),
  KEY `shipping_id` (`shipping_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_shop_config`
--

DROP TABLE IF EXISTS `dsc_shop_config`;
CREATE TABLE IF NOT EXISTS `dsc_shop_config` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `code` varchar(30) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `store_range` varchar(255) NOT NULL DEFAULT '',
  `store_dir` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `shop_group` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1025 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_single`
--

DROP TABLE IF EXISTS `dsc_single`;
CREATE TABLE IF NOT EXISTS `dsc_single` (
  `single_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `order_id` mediumint(8) NOT NULL,
  `single_name` varchar(100) NOT NULL,
  `single_description` text NOT NULL,
  `single_like` char(8) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `is_audit` tinyint(1) NOT NULL,
  `order_sn` varchar(20) NOT NULL,
  `addtime` varchar(20) NOT NULL,
  `goods_name` varchar(120) NOT NULL,
  `goods_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `order_time` varchar(20) NOT NULL,
  `comment_id` mediumint(8) DEFAULT NULL,
  `single_ip` varchar(15) DEFAULT '',
  `cat_id` mediumint(8) DEFAULT NULL,
  `integ` varchar(8) DEFAULT NULL,
  `single_browse_num` int(10) unsigned DEFAULT '0',
  `cover` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`single_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `comment_id` (`comment_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_single_sun_images`
--

DROP TABLE IF EXISTS `dsc_single_sun_images`;
CREATE TABLE IF NOT EXISTS `dsc_single_sun_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `img_file` varchar(255) NOT NULL,
  `img_thumb` varchar(255) NOT NULL,
  `cont_desc` varchar(2000) NOT NULL,
  `comment_id` smallint(8) unsigned NOT NULL DEFAULT '0',
  `img_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `order_id` (`order_id`),
  KEY `single_id` (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_snatch_log`
--

DROP TABLE IF EXISTS `dsc_snatch_log`;
CREATE TABLE IF NOT EXISTS `dsc_snatch_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `snatch_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bid_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bid_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `snatch_id` (`snatch_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_source_ip`
--

DROP TABLE IF EXISTS `dsc_source_ip`;
CREATE TABLE IF NOT EXISTS `dsc_source_ip` (
  `ipid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) NOT NULL,
  `ipdata` varchar(16) NOT NULL COMMENT '访问者的IP',
  `iptime` varchar(30) NOT NULL COMMENT '访问时间',
  PRIMARY KEY (`ipid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_stages`
--

DROP TABLE IF EXISTS `dsc_stages`;
CREATE TABLE IF NOT EXISTS `dsc_stages` (
  `stages_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分期表的ID',
  `order_sn` varchar(20) NOT NULL COMMENT '订单编号',
  `stages_total` tinyint(3) unsigned NOT NULL COMMENT '总分期数',
  `stages_one_price` decimal(10,2) unsigned NOT NULL COMMENT '每期的金额',
  `yes_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '已还期数',
  `create_date` int(10) unsigned NOT NULL COMMENT '分期单创建时间',
  `repay_date` text NOT NULL COMMENT '还款日期',
  PRIMARY KEY (`stages_id`),
  KEY `order_sn` (`order_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_stats`
--

DROP TABLE IF EXISTS `dsc_stats`;
CREATE TABLE IF NOT EXISTS `dsc_stats` (
  `access_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `visit_times` smallint(5) unsigned NOT NULL DEFAULT '1',
  `browser` varchar(60) NOT NULL DEFAULT '',
  `system` varchar(20) NOT NULL DEFAULT '',
  `language` varchar(20) NOT NULL DEFAULT '',
  `area` varchar(30) NOT NULL DEFAULT '',
  `referer_domain` varchar(100) NOT NULL DEFAULT '',
  `referer_path` varchar(200) NOT NULL DEFAULT '',
  `access_url` varchar(255) NOT NULL DEFAULT '',
  KEY `access_time` (`access_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_store_action`
--

DROP TABLE IF EXISTS `dsc_store_action`;
CREATE TABLE IF NOT EXISTS `dsc_store_action` (
  `action_id` int(8) NOT NULL AUTO_INCREMENT,
  `parent_id` int(8) unsigned NOT NULL DEFAULT '0',
  `action_code` varchar(20) NOT NULL,
  `relevance` varchar(20) NOT NULL,
  `action_name` varchar(20) NOT NULL,
  PRIMARY KEY (`action_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_store_goods`
--

DROP TABLE IF EXISTS `dsc_store_goods`;
CREATE TABLE IF NOT EXISTS `dsc_store_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '1',
  `extend_goods_number` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `store_id` (`store_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_store_order`
--

DROP TABLE IF EXISTS `dsc_store_order`;
CREATE TABLE IF NOT EXISTS `dsc_store_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_grab_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `grab_store_list` text NOT NULL,
  `pick_code` varchar(25) NOT NULL,
  `take_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `store_id` (`store_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_store_products`
--

DROP TABLE IF EXISTS `dsc_store_products`;
CREATE TABLE IF NOT EXISTS `dsc_store_products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` varchar(50) DEFAULT NULL,
  `product_sn` varchar(60) DEFAULT NULL,
  `product_number` int(10) unsigned DEFAULT '0',
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_sn` (`product_sn`),
  KEY `ru_id` (`ru_id`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_store_user`
--

DROP TABLE IF EXISTS `dsc_store_user`;
CREATE TABLE IF NOT EXISTS `dsc_store_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `stores_user` varchar(60) NOT NULL,
  `stores_pwd` varchar(32) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `email` varchar(60) NOT NULL,
  `store_action` text NOT NULL,
  `add_time` int(10) NOT NULL DEFAULT '0',
  `ec_salt` varchar(10) NOT NULL,
  `store_user_img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ru_id` (`ru_id`),
  KEY `store_id` (`store_id`),
  KEY `parent_id` (`parent_id`),
  KEY `ec_salt` (`ec_salt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_suppliers`
--

DROP TABLE IF EXISTS `dsc_suppliers`;
CREATE TABLE IF NOT EXISTS `dsc_suppliers` (
  `suppliers_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `suppliers_name` varchar(255) DEFAULT NULL,
  `suppliers_desc` mediumtext,
  `is_check` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`suppliers_id`),
  KEY `is_check` (`is_check`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_tag`
--

DROP TABLE IF EXISTS `dsc_tag`;
CREATE TABLE IF NOT EXISTS `dsc_tag` (
  `tag_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tag_words` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`tag_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_template`
--

DROP TABLE IF EXISTS `dsc_template`;
CREATE TABLE IF NOT EXISTS `dsc_template` (
  `filename` varchar(30) NOT NULL DEFAULT '',
  `region` varchar(40) NOT NULL DEFAULT '',
  `library` varchar(40) NOT NULL DEFAULT '',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `number` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `theme` varchar(60) NOT NULL DEFAULT '',
  `remarks` varchar(30) NOT NULL DEFAULT '',
  `floor_tpl` smallint(5) NOT NULL DEFAULT '0' COMMENT '首页楼层模板',
  KEY `filename` (`filename`,`region`),
  KEY `theme` (`theme`),
  KEY `remarks` (`remarks`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_templates_left`
--

DROP TABLE IF EXISTS `dsc_templates_left`;
CREATE TABLE IF NOT EXISTS `dsc_templates_left` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) NOT NULL DEFAULT '0',
  `seller_templates` varchar(160) NOT NULL,
  `bg_color` char(10) NOT NULL,
  `img_file` varchar(120) NOT NULL,
  `if_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bgrepeat` varchar(50) NOT NULL,
  `align` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `theme` varchar(60) NOT NULL,
  `fileurl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_template_mall`
--

DROP TABLE IF EXISTS `dsc_template_mall`;
CREATE TABLE IF NOT EXISTS `dsc_template_mall` (
  `temp_id` int(10) NOT NULL AUTO_INCREMENT,
  `temp_file` varchar(255) NOT NULL,
  `temp_mode` tinyint(1) NOT NULL DEFAULT '0',
  `temp_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `temp_code` varchar(60) NOT NULL,
  `add_time` int(10) NOT NULL DEFAULT '0',
  `sales_volume` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`temp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_topic`
--

DROP TABLE IF EXISTS `dsc_topic`;
CREATE TABLE IF NOT EXISTS `dsc_topic` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '''''',
  `intro` text NOT NULL,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(10) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `template` varchar(255) NOT NULL DEFAULT '''''',
  `css` text NOT NULL,
  `topic_img` varchar(255) DEFAULT NULL,
  `title_pic` varchar(255) DEFAULT NULL,
  `base_style` char(6) DEFAULT NULL,
  `htmls` mediumtext,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  KEY `topic_id` (`topic_id`),
  KEY `review_status` (`review_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_touch_ad`
--

DROP TABLE IF EXISTS `dsc_touch_ad`;
CREATE TABLE IF NOT EXISTS `dsc_touch_ad` (
  `ad_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `media_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ad_name` varchar(60) NOT NULL DEFAULT '',
  `ad_link` varchar(255) NOT NULL DEFAULT '',
  `link_color` varchar(60) NOT NULL,
  `ad_code` text NOT NULL,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `link_man` varchar(60) NOT NULL DEFAULT '',
  `link_email` varchar(60) NOT NULL DEFAULT '',
  `link_phone` varchar(60) NOT NULL DEFAULT '',
  `click_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `public_ruid` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL,
  PRIMARY KEY (`ad_id`),
  KEY `position_id` (`position_id`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_touch_adsense`
--

DROP TABLE IF EXISTS `dsc_touch_adsense`;
CREATE TABLE IF NOT EXISTS `dsc_touch_adsense` (
  `from_ad` smallint(5) NOT NULL DEFAULT '0',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `clicks` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `from_ad` (`from_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_touch_ad_position`
--

DROP TABLE IF EXISTS `dsc_touch_ad_position`;
CREATE TABLE IF NOT EXISTS `dsc_touch_ad_position` (
  `position_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `position_name` varchar(60) NOT NULL DEFAULT '',
  `ad_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ad_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `position_desc` varchar(255) NOT NULL DEFAULT '',
  `position_style` text NOT NULL,
  `is_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `theme` varchar(160) NOT NULL,
  PRIMARY KEY (`position_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=260 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_touch_auth`
--

DROP TABLE IF EXISTS `dsc_touch_auth`;
CREATE TABLE IF NOT EXISTS `dsc_touch_auth` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `auth_config` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_touch_nav`
--

DROP TABLE IF EXISTS `dsc_touch_nav`;
CREATE TABLE IF NOT EXISTS `dsc_touch_nav` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ctype` varchar(10) DEFAULT NULL,
  `cid` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ifshow` tinyint(1) NOT NULL,
  `vieworder` tinyint(1) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `pic` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `ifshow` (`ifshow`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_touch_page_view`
--

DROP TABLE IF EXISTS `dsc_touch_page_view`;
CREATE TABLE IF NOT EXISTS `dsc_touch_page_view` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家ID',
  `type` varchar(60) NOT NULL DEFAULT '1' COMMENT '店铺或专题',
  `page_id` int(160) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID或专题ID',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `data` longtext COMMENT '内容',
  `pic` longtext COMMENT '图片',
  `thumb_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `create_at` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '数据 0 自定义数据 1 默认数据',
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '审核状态1 3 ',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示 0 1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_touch_topic`
--

DROP TABLE IF EXISTS `dsc_touch_topic`;
CREATE TABLE IF NOT EXISTS `dsc_touch_topic` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '''''',
  `intro` text NOT NULL,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(10) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `template` varchar(255) NOT NULL DEFAULT '''''',
  `css` text NOT NULL,
  `topic_img` varchar(255) DEFAULT NULL,
  `title_pic` varchar(255) DEFAULT NULL,
  `base_style` char(6) DEFAULT NULL,
  `htmls` mediumtext,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  KEY `topic_id` (`topic_id`),
  KEY `review_status` (`review_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_trade_snapshot`
--

DROP TABLE IF EXISTS `dsc_trade_snapshot`;
CREATE TABLE IF NOT EXISTS `dsc_trade_snapshot` (
  `trade_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` int(5) unsigned NOT NULL DEFAULT '1',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rz_shopName` varchar(60) NOT NULL,
  `goods_weight` decimal(10,3) NOT NULL DEFAULT '0.000',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_attr` varchar(255) NOT NULL,
  `goods_attr_id` varchar(255) NOT NULL DEFAULT '',
  `ru_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_desc` text NOT NULL,
  `goods_img` varchar(255) NOT NULL DEFAULT '',
  `snapshot_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '快照新增时间',
  PRIMARY KEY (`trade_id`),
  KEY `order_sn` (`order_sn`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `ru_id` (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_users`
--

DROP TABLE IF EXISTS `dsc_users`;
CREATE TABLE IF NOT EXISTS `dsc_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `aite_id` text NOT NULL,
  `email` varchar(60) NOT NULL DEFAULT '',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `nick_name` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL DEFAULT '',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` varchar(255) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_points` int(10) NOT NULL DEFAULT '0',
  `rank_points` int(10) unsigned NOT NULL DEFAULT '0',
  `address_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
  `last_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `visit_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_rank` int(10) unsigned NOT NULL DEFAULT '0',
  `is_special` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ec_salt` varchar(10) DEFAULT NULL,
  `salt` varchar(10) NOT NULL DEFAULT '0',
  `parent_id` mediumint(9) NOT NULL DEFAULT '0',
  `flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(60) NOT NULL,
  `msn` varchar(60) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `office_phone` varchar(20) NOT NULL,
  `home_phone` varchar(20) NOT NULL,
  `mobile_phone` varchar(20) NOT NULL,
  `is_validated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `credit_line` decimal(10,2) unsigned NOT NULL,
  `passwd_question` varchar(50) DEFAULT NULL,
  `passwd_answer` varchar(255) DEFAULT NULL,
  `user_picture` text NOT NULL,
  `old_user_picture` text NOT NULL,
  `report_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `email` (`email`),
  KEY `parent_id` (`parent_id`),
  KEY `flag` (`flag`),
  KEY `is_validated` (`is_validated`),
  KEY `address_id` (`address_id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_users_auth`
--

DROP TABLE IF EXISTS `dsc_users_auth`;
CREATE TABLE IF NOT EXISTS `dsc_users_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL,
  `identity_type` varchar(32) NOT NULL,
  `identifier` varchar(128) NOT NULL,
  `credential` varchar(128) NOT NULL,
  `verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `identifier` (`identifier`),
  KEY `user_id` (`user_id`),
  KEY `user_name` (`user_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_users_log`
--

DROP TABLE IF EXISTS `dsc_users_log`;
CREATE TABLE IF NOT EXISTS `dsc_users_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `change_time` int(10) NOT NULL DEFAULT '0',
  `change_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL,
  `change_city` varchar(255) NOT NULL,
  `logon_service` varchar(60) NOT NULL DEFAULT 'pc',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_users_paypwd`
--

DROP TABLE IF EXISTS `dsc_users_paypwd`;
CREATE TABLE IF NOT EXISTS `dsc_users_paypwd` (
  `paypwd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ec_salt` varchar(10) DEFAULT NULL,
  `pay_password` varchar(32) NOT NULL DEFAULT '',
  `pay_online` tinyint(1) unsigned NOT NULL,
  `user_surplus` tinyint(1) unsigned NOT NULL,
  `user_point` tinyint(1) unsigned NOT NULL,
  `baitiao` tinyint(1) unsigned NOT NULL,
  `gift_card` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`paypwd_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_users_real`
--

DROP TABLE IF EXISTS `dsc_users_real`;
CREATE TABLE IF NOT EXISTS `dsc_users_real` (
  `real_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` text NOT NULL,
  `real_name` varchar(60) NOT NULL DEFAULT '',
  `bank_mobile` varchar(20) NOT NULL,
  `bank_name` varchar(60) NOT NULL,
  `bank_card` varchar(255) NOT NULL DEFAULT '',
  `self_num` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL,
  `review_content` varchar(200) NOT NULL,
  `review_status` tinyint(1) NOT NULL DEFAULT '0',
  `review_time` int(11) NOT NULL,
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `front_of_id_card` varchar(60) NOT NULL COMMENT '身份证正面',
  `reverse_of_id_card` varchar(60) NOT NULL COMMENT '身份证反面',
  PRIMARY KEY (`real_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_users_type`
--

DROP TABLE IF EXISTS `dsc_users_type`;
CREATE TABLE IF NOT EXISTS `dsc_users_type` (
  `user_id` int(10) unsigned NOT NULL,
  `enterprise_personal` tinyint(1) unsigned NOT NULL,
  `companyname` varchar(255) NOT NULL,
  `contactname` varchar(255) NOT NULL,
  `companyaddress` varchar(255) NOT NULL,
  `industry` int(10) unsigned NOT NULL,
  `surname` varchar(150) NOT NULL,
  `givenname` varchar(150) NOT NULL,
  `agreement` tinyint(1) unsigned NOT NULL,
  `country` int(11) unsigned NOT NULL,
  `province` int(11) unsigned NOT NULL,
  `city` int(11) unsigned NOT NULL,
  `district` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_users_vat_invoices_info`
--

DROP TABLE IF EXISTS `dsc_users_vat_invoices_info`;
CREATE TABLE IF NOT EXISTS `dsc_users_vat_invoices_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `company_name` varchar(60) NOT NULL DEFAULT '',
  `company_address` varchar(255) NOT NULL DEFAULT '',
  `tax_id` varchar(20) NOT NULL DEFAULT '',
  `company_telephone` varchar(20) NOT NULL DEFAULT '',
  `bank_of_deposit` varchar(20) NOT NULL DEFAULT '',
  `bank_account` varchar(30) NOT NULL DEFAULT '',
  `consignee_name` varchar(20) NOT NULL DEFAULT '',
  `consignee_mobile_phone` varchar(15) NOT NULL DEFAULT '',
  `consignee_address` varchar(255) NOT NULL DEFAULT '',
  `audit_status` tinyint(1) NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `country` int(10) unsigned NOT NULL DEFAULT '0',
  `province` int(10) unsigned NOT NULL DEFAULT '0',
  `city` int(10) unsigned NOT NULL DEFAULT '0',
  `district` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `audit_status` (`audit_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_account`
--

DROP TABLE IF EXISTS `dsc_user_account`;
CREATE TABLE IF NOT EXISTS `dsc_user_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_user` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `deposit_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `paid_time` int(10) NOT NULL DEFAULT '0',
  `admin_note` varchar(255) NOT NULL,
  `user_note` varchar(255) NOT NULL,
  `process_type` tinyint(1) NOT NULL DEFAULT '0',
  `payment` varchar(90) NOT NULL,
  `pay_id` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '支付ID',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `complaint_details` varchar(500) NOT NULL DEFAULT '' COMMENT '申诉内容',
  `complaint_imges` varchar(255) NOT NULL COMMENT '申诉照片',
  `complaint_time` int(10) unsigned NOT NULL COMMENT '申诉时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_paid` (`is_paid`),
  KEY `pay_id` (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_account_fields`
--

DROP TABLE IF EXISTS `dsc_user_account_fields`;
CREATE TABLE IF NOT EXISTS `dsc_user_account_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户id',
  `account_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联ecs_user_account表id',
  `bank_number` varchar(255) NOT NULL COMMENT '银行账号',
  `real_name` varchar(50) NOT NULL COMMENT '真是姓名',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_address`
--

DROP TABLE IF EXISTS `dsc_user_address`;
CREATE TABLE IF NOT EXISTS `dsc_user_address` (
  `address_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `address_name` varchar(50) NOT NULL DEFAULT '',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `country` smallint(5) NOT NULL DEFAULT '0',
  `province` smallint(5) NOT NULL DEFAULT '0',
  `city` smallint(5) NOT NULL DEFAULT '0',
  `district` smallint(5) NOT NULL DEFAULT '0',
  `street` smallint(5) NOT NULL DEFAULT '0',
  `address` varchar(120) NOT NULL DEFAULT '',
  `zipcode` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `sign_building` varchar(120) NOT NULL DEFAULT '',
  `best_time` varchar(120) NOT NULL DEFAULT '',
  `audit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userUp_time` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_bank`
--

DROP TABLE IF EXISTS `dsc_user_bank`;
CREATE TABLE IF NOT EXISTS `dsc_user_bank` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(50) DEFAULT NULL,
  `bank_card` varchar(50) DEFAULT NULL,
  `bank_region` varchar(255) NOT NULL,
  `bank_user_name` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_bonus`
--

DROP TABLE IF EXISTS `dsc_user_bonus`;
CREATE TABLE IF NOT EXISTS `dsc_user_bonus` (
  `bonus_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `bonus_type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bonus_sn` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bonus_password` varchar(60) NOT NULL,
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `used_time` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(8) unsigned NOT NULL DEFAULT '0',
  `emailed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bind_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bonus_id`),
  KEY `user_id` (`user_id`),
  KEY `bonus_type_id` (`bonus_type_id`),
  KEY `order_id` (`order_id`),
  KEY `emailed` (`emailed`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_feed`
--

DROP TABLE IF EXISTS `dsc_user_feed`;
CREATE TABLE IF NOT EXISTS `dsc_user_feed` (
  `feed_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `value_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `feed_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_feed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`feed_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_gift_gard`
--

DROP TABLE IF EXISTS `dsc_user_gift_gard`;
CREATE TABLE IF NOT EXISTS `dsc_user_gift_gard` (
  `gift_gard_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gift_sn` bigint(20) unsigned NOT NULL,
  `gift_password` char(32) NOT NULL,
  `user_id` int(10) unsigned DEFAULT '0',
  `goods_id` int(10) unsigned DEFAULT '0',
  `user_time` int(10) unsigned DEFAULT '0',
  `express_no` varchar(64) DEFAULT '0',
  `gift_id` mediumint(8) unsigned NOT NULL,
  `address` varchar(120) DEFAULT NULL,
  `consignee_name` varchar(60) DEFAULT NULL,
  `mobile` varchar(60) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '0',
  `config_goods_id` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) unsigned DEFAULT '1',
  `shipping_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`gift_gard_id`),
  KEY `gift_sn` (`gift_sn`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `gift_id` (`gift_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_user_rank`
--

DROP TABLE IF EXISTS `dsc_user_rank`;
CREATE TABLE IF NOT EXISTS `dsc_user_rank` (
  `rank_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rank_name` varchar(30) NOT NULL DEFAULT '',
  `min_points` int(10) unsigned NOT NULL DEFAULT '0',
  `max_points` int(10) unsigned NOT NULL DEFAULT '0',
  `discount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `show_price` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `special_rank` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rank_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_value_card`
--

DROP TABLE IF EXISTS `dsc_value_card`;
CREATE TABLE IF NOT EXISTS `dsc_value_card` (
  `vid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `tid` int(10) NOT NULL COMMENT '储值卡类型ID',
  `value_card_sn` varchar(30) NOT NULL COMMENT '储值卡账号',
  `value_card_password` varchar(20) NOT NULL COMMENT '储值卡密码',
  `user_id` int(10) NOT NULL COMMENT '绑定用户ID',
  `vc_value` int(10) NOT NULL,
  `card_money` decimal(10,2) unsigned NOT NULL COMMENT '卡内余额',
  `bind_time` int(11) NOT NULL COMMENT '绑定时间',
  `end_time` int(11) NOT NULL COMMENT '截止日期',
  PRIMARY KEY (`vid`),
  UNIQUE KEY `value_card_sn` (`value_card_sn`),
  KEY `tid` (`tid`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_value_card_record`
--

DROP TABLE IF EXISTS `dsc_value_card_record`;
CREATE TABLE IF NOT EXISTS `dsc_value_card_record` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `vc_id` int(10) NOT NULL COMMENT '储值卡ID',
  `order_id` int(10) NOT NULL COMMENT '订单ID',
  `use_val` decimal(10,2) NOT NULL COMMENT '使用金额',
  `add_val` int(10) NOT NULL COMMENT '充值金额',
  `record_time` int(11) NOT NULL COMMENT '记录时间',
  PRIMARY KEY (`rid`),
  KEY `vc_id` (`vc_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_value_card_type`
--

DROP TABLE IF EXISTS `dsc_value_card_type`;
CREATE TABLE IF NOT EXISTS `dsc_value_card_type` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(180) DEFAULT NULL COMMENT '类型名称',
  `vc_desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `vc_value` decimal(10,0) NOT NULL COMMENT '面值',
  `vc_prefix` varchar(10) NOT NULL,
  `vc_dis` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '折扣率',
  `vc_limit` tinyint(5) NOT NULL DEFAULT '1' COMMENT '限制数量',
  `use_condition` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用条件',
  `use_merchants` varchar(255) NOT NULL COMMENT '可使用店铺',
  `spec_goods` varchar(255) NOT NULL COMMENT '指定商品',
  `spec_cat` varchar(255) NOT NULL COMMENT '指定分类',
  `vc_indate` tinyint(3) NOT NULL COMMENT '有效期单位为自然月',
  `is_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '可否充值',
  `add_time` int(11) NOT NULL COMMENT '储值卡类型新增时间',
  PRIMARY KEY (`id`),
  KEY `use_condition` (`use_condition`),
  KEY `is_rec` (`is_rec`),
  KEY `vc_indate` (`vc_indate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_virtual_card`
--

DROP TABLE IF EXISTS `dsc_virtual_card`;
CREATE TABLE IF NOT EXISTS `dsc_virtual_card` (
  `card_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `card_sn` varchar(60) NOT NULL DEFAULT '',
  `card_password` varchar(60) NOT NULL DEFAULT '',
  `add_date` int(11) NOT NULL DEFAULT '0',
  `end_date` int(11) NOT NULL DEFAULT '0',
  `is_saled` tinyint(1) NOT NULL DEFAULT '0',
  `order_sn` varchar(20) NOT NULL DEFAULT '',
  `crc32` varchar(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`card_id`),
  KEY `goods_id` (`goods_id`),
  KEY `car_sn` (`card_sn`),
  KEY `is_saled` (`is_saled`),
  KEY `add_date` (`add_date`),
  KEY `end_date` (`end_date`),
  KEY `order_sn` (`order_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_volume_price`
--

DROP TABLE IF EXISTS `dsc_volume_price`;
CREATE TABLE IF NOT EXISTS `dsc_volume_price` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `price_type` tinyint(1) unsigned NOT NULL,
  `goods_id` mediumint(8) unsigned NOT NULL,
  `volume_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `volume_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `price_type` (`price_type`),
  KEY `volume_price` (`volume_price`),
  KEY `volume_number` (`volume_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_vote`
--

DROP TABLE IF EXISTS `dsc_vote`;
CREATE TABLE IF NOT EXISTS `dsc_vote` (
  `vote_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `vote_name` varchar(250) NOT NULL DEFAULT '',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0',
  `can_multi` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vote_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_vote_log`
--

DROP TABLE IF EXISTS `dsc_vote_log`;
CREATE TABLE IF NOT EXISTS `dsc_vote_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `vote_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `vote_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `vote_id` (`vote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_vote_option`
--

DROP TABLE IF EXISTS `dsc_vote_option`;
CREATE TABLE IF NOT EXISTS `dsc_vote_option` (
  `option_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `vote_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `option_name` varchar(250) NOT NULL DEFAULT '',
  `option_count` int(8) unsigned NOT NULL DEFAULT '0',
  `option_order` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`option_id`),
  KEY `vote_id` (`vote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_warehouse_area_attr`
--

DROP TABLE IF EXISTS `dsc_warehouse_area_attr`;
CREATE TABLE IF NOT EXISTS `dsc_warehouse_area_attr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` varchar(50) NOT NULL,
  `area_id` int(11) unsigned NOT NULL DEFAULT '0',
  `attr_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `goods_id` (`goods_id`),
  KEY `goods_attr_id` (`goods_attr_id`),
  KEY `area_id` (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_warehouse_area_goods`
--

DROP TABLE IF EXISTS `dsc_warehouse_area_goods`;
CREATE TABLE IF NOT EXISTS `dsc_warehouse_area_goods` (
  `a_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `region_sn` varchar(60) NOT NULL DEFAULT '',
  `region_number` int(10) unsigned NOT NULL DEFAULT '0',
  `region_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `region_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `region_sort` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `give_integral` int(10) unsigned NOT NULL DEFAULT '0',
  `rank_integral` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_integral` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`a_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=150 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_warehouse_attr`
--

DROP TABLE IF EXISTS `dsc_warehouse_attr`;
CREATE TABLE IF NOT EXISTS `dsc_warehouse_attr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` varchar(50) NOT NULL,
  `warehouse_id` int(11) unsigned NOT NULL DEFAULT '0',
  `attr_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `goods_id` (`goods_id`),
  KEY `goods_attr_id` (`goods_attr_id`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_warehouse_freight`
--

DROP TABLE IF EXISTS `dsc_warehouse_freight`;
CREATE TABLE IF NOT EXISTS `dsc_warehouse_freight` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `configure` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `shipping_id` (`shipping_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_warehouse_freight_tpl`
--

DROP TABLE IF EXISTS `dsc_warehouse_freight_tpl`;
CREATE TABLE IF NOT EXISTS `dsc_warehouse_freight_tpl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `warehouse_id` varchar(255) NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `region_id` varchar(255) NOT NULL,
  `configure` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `shipping_id` (`shipping_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_warehouse_goods`
--

DROP TABLE IF EXISTS `dsc_warehouse_goods`;
CREATE TABLE IF NOT EXISTS `dsc_warehouse_goods` (
  `w_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `region_sn` varchar(60) NOT NULL DEFAULT '',
  `region_number` int(10) unsigned NOT NULL DEFAULT '0',
  `warehouse_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `warehouse_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `give_integral` int(10) NOT NULL DEFAULT '0',
  `rank_integral` int(10) NOT NULL DEFAULT '0',
  `pay_integral` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`w_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=85 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_wholesale`
--

DROP TABLE IF EXISTS `dsc_wholesale`;
CREATE TABLE IF NOT EXISTS `dsc_wholesale` (
  `act_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL,
  `goods_name` varchar(255) NOT NULL,
  `rank_ids` varchar(255) NOT NULL,
  `prices` text NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL,
  `review_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `review_content` varchar(1000) NOT NULL,
  PRIMARY KEY (`act_id`),
  KEY `goods_id` (`goods_id`),
  KEY `review_status` (`review_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_category`
--

DROP TABLE IF EXISTS `dsc_zc_category`;
CREATE TABLE IF NOT EXISTS `dsc_zc_category` (
  `cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(90) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL,
  `measure_unit` varchar(15) NOT NULL,
  `show_in_nav` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `style` varchar(150) NOT NULL,
  `grade` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `filter_attr` varchar(225) NOT NULL,
  `is_top_style` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `top_style_tpl` varchar(255) NOT NULL,
  `cat_icon` varchar(255) NOT NULL,
  `is_top_show` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category_links` text NOT NULL,
  `category_topic` text NOT NULL,
  `pinyin_keyword` text NOT NULL,
  `cat_alias_name` varchar(90) NOT NULL,
  `template_file` varchar(50) NOT NULL,
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '50',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`cat_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_focus`
--

DROP TABLE IF EXISTS `dsc_zc_focus`;
CREATE TABLE IF NOT EXISTS `dsc_zc_focus` (
  `rec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `add_time` varchar(255) NOT NULL,
  PRIMARY KEY (`rec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_goods`
--

DROP TABLE IF EXISTS `dsc_zc_goods`;
CREATE TABLE IF NOT EXISTS `dsc_zc_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `limit` int(11) NOT NULL,
  `backer_num` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `shipping_fee` decimal(10,0) NOT NULL,
  `content` text NOT NULL,
  `img` varchar(255) NOT NULL,
  `return_time` int(11) NOT NULL,
  `backer_list` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_initiator`
--

DROP TABLE IF EXISTS `dsc_zc_initiator`;
CREATE TABLE IF NOT EXISTS `dsc_zc_initiator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `intro` text NOT NULL,
  `describe` text NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_progress`
--

DROP TABLE IF EXISTS `dsc_zc_progress`;
CREATE TABLE IF NOT EXISTS `dsc_zc_progress` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `progress` text NOT NULL,
  `add_time` varchar(255) NOT NULL,
  `img` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_project`
--

DROP TABLE IF EXISTS `dsc_zc_project`;
CREATE TABLE IF NOT EXISTS `dsc_zc_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `init_id` varchar(255) NOT NULL,
  `start_time` varchar(255) NOT NULL,
  `end_time` varchar(255) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `join_money` decimal(10,0) NOT NULL,
  `join_num` int(11) NOT NULL,
  `focus_num` int(11) NOT NULL,
  `prais_num` int(11) NOT NULL,
  `title_img` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `describe` text NOT NULL,
  `risk_instruction` text NOT NULL,
  `img` text NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_rank_logo`
--

DROP TABLE IF EXISTS `dsc_zc_rank_logo`;
CREATE TABLE IF NOT EXISTS `dsc_zc_rank_logo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo_name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `logo_intro` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `dsc_zc_topic`
--

DROP TABLE IF EXISTS `dsc_zc_topic`;
CREATE TABLE IF NOT EXISTS `dsc_zc_topic` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_topic_id` int(11) NOT NULL,
  `reply_topic_id` int(11) NOT NULL,
  `topic_status` tinyint(1) NOT NULL,
  `topic_content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `add_time` varchar(255) NOT NULL,
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
