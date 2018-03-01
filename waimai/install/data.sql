INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`, `ifsys`) VALUES (56, 2, '外卖', 'waimai_', 'waimai', '', '', '', 0, '', '', 0, 1);


DROP TABLE IF EXISTS `qb_waimai_address`;
CREATE TABLE IF NOT EXISTS `qb_waimai_address` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `order_username` varchar(20) NOT NULL default '',
  `order_phone` varchar(20) NOT NULL default '',
  `order_mobphone` varchar(15) NOT NULL default '',
  `order_email` varchar(50) NOT NULL default '',
  `order_qq` varchar(11) NOT NULL default '',
  `order_postcode` varchar(6) NOT NULL default '',
  `order_address` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`rid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `qb_waimai_car`;
CREATE TABLE IF NOT EXISTS `qb_waimai_car` (
  `id` mediumint(7) NOT NULL auto_increment,
  `cid` mediumint(10) NOT NULL default '0',
  `joins` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=7 ;



INSERT INTO `qb_waimai_car` (`id`, `cid`, `joins`, `uid`, `type`) VALUES(6, 3, 1, 1, 1);
INSERT INTO `qb_waimai_car` (`id`, `cid`, `joins`, `uid`, `type`) VALUES(5, 1, 1, 1, 1);
INSERT INTO `qb_waimai_car` (`id`, `cid`, `joins`, `uid`, `type`) VALUES(4, 2, 1, 1, 1);



DROP TABLE IF EXISTS `qb_waimai_collection`;
CREATE TABLE IF NOT EXISTS `qb_waimai_collection` (
  `cid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `qb_waimai_collectionhy`;
CREATE TABLE IF NOT EXISTS `qb_waimai_collectionhy` (
  `cid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;



INSERT INTO `qb_waimai_collectionhy` (`cid`, `id`, `uid`, `posttime`) VALUES(1, 5, 1, 1468309503);



DROP TABLE IF EXISTS `qb_waimai_comments`;
CREATE TABLE IF NOT EXISTS `qb_waimai_comments` (
  `cid` mediumint(7) unsigned NOT NULL auto_increment,
  `cuid` int(7) NOT NULL default '0',
  `type` tinyint(2) NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `fid` mediumint(7) unsigned NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `flowers` smallint(4) NOT NULL default '0',
  `egg` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `qb_waimai_company`;
CREATE TABLE IF NOT EXISTS `qb_waimai_company` (
  `id` int(10) NOT NULL auto_increment,
  `zone_id` mediumint(7) NOT NULL,
  `street_id` mediumint(7) NOT NULL,
  `title` varchar(100) NOT NULL default '',
  `mid` smallint(4) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `fname` varchar(50) NOT NULL default '',
  `hits` mediumint(7) NOT NULL default '0',
  `comments` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` varchar(10) NOT NULL default '',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `picurl` varchar(150) NOT NULL default '',
  `ispic` tinyint(1) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `levels` tinyint(2) NOT NULL default '0',
  `levelstime` int(10) NOT NULL default '0',
  `keywords` varchar(100) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `lastfid` mediumint(7) NOT NULL default '0',
  `money` mediumint(7) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `lastview` int(10) NOT NULL default '0',
  `city_id` mediumint(7) NOT NULL default '0',
  `picnum` smallint(4) NOT NULL default '0',
  `price` double NOT NULL default '0',
  `sellnum` mediumint(7) NOT NULL default '0',
  `sendprice` mediumint(5) NOT NULL,
  `arrive_time` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `maps` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `telphoto` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `ispic` (`ispic`),
  KEY `city_id` (`city_id`),
  KEY `list` (`list`,`fid`,`city_id`,`yz`),
  KEY `hits` (`hits`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=10 ;



INSERT INTO `qb_waimai_company` (`id`, `zone_id`, `street_id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `sendprice`, `arrive_time`, `address`, `maps`, `content`, `telphoto`) VALUES(1, 1, 0, '北京诺金酒店禾家中餐厅', 0, 1, '', 3, 0, 1465980922, '1465980922', 1, 'admin', '', 'http://qcloud.dpfile.com/pc/00rLSEnUBxQgHb6aaV4t0oYepCkol6GS4waQqPstXDfNXmvln4Ik58yVA1fN3nW_TYGVDmosZWTLal1WbWRW3A.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1465981067, 1, 2, 10, 0, 20, '30', '将台路甲2号(近14号线地铁将台路站', '39.89301160647708,116.38486862', '<p><span style="color:#282828;font-family:&#39;microsoft yahei&#39;, &#39;hiragino sans gb&#39;;font-size:14px;line-height:24px;">味道很好，茶点也很精致口味纯正，禽类做的味道很正宗，对于粤菜馆而言，口味很家常，相比较同价位的几个馆子，从摆盘来看还是很家常的，不过贵在口味，还不错，还是会再来的，脆皮鸡不错，其他菜式也下饭可口</span><br /></p>', '');
INSERT INTO `qb_waimai_company` (`id`, `zone_id`, `street_id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `sendprice`, `arrive_time`, `address`, `maps`, `content`, `telphoto`) VALUES(2, 0, 0, '祈年八号中餐厅', 0, 1, '', 15, 0, 1465981055, '1465981055', 1, 'admin', '', 'http://qcloud.dpfile.com/pc/PXmLCsm0lyUMTo6yCWyV5qn3ISbQ-GIv5lmXH4mLjajJVC6bnk8gaYpElMMrbfnlsUUjuoRat_w088HnHHkzYQ.jpg', 1, 2, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1466738269, 1, 1, 20, 0, 5, '30', '祈年大街8号新世界酒店2楼(地铁2号线崇文门站', '39.89722607068418,116.45147323', '<p><span style="color:#282828;font-family:&#39;microsoft yahei&#39;, &#39;hiragino sans gb&#39;;font-size:14px;line-height:24px;">本月太忙，終於可以週末休一下，来品茗和吃粤式点心，以环境来說，这家最有氣氛，服务高級优雅，令人舒服休憩。</span><br style="color:#282828;font-family:&#39;microsoft yahei&#39;, &#39;hiragino sans gb&#39;;font-size:14px;line-height:24px;" /><span style="color:#282828;font-family:&#39;microsoft yahei&#39;, &#39;hiragino sans gb&#39;;font-size:14px;line-height:24px;">食物方面，素菜分了菜心和生菜等，給客人有所选择，菜不油不腻，很好；但腸粉还是做得不好，粉皮的粉太粘，应该是份量不準確的关系；而紅油炒手没有炒，只是浸紅油。我認為要跟进下。其他都很好吃，特别是炒萝卜糕。很贊。</span><br /></p>', '');
INSERT INTO `qb_waimai_company` (`id`, `zone_id`, `street_id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `sendprice`, `arrive_time`, `address`, `maps`, `content`, `telphoto`) VALUES(3, 0, 0, '粤舍粗菜馆', 0, 1, '', 1, 0, 1465981491, '1465981491', 1, 'admin', '', 'http://qcloud.dpfile.com/pc/-tA6FxybPM3rtKBqpyRvlE9dHJGU_W-O6a1GlswjaCjDmr-smpteMJFKQkVywW2DsUUjuoRat_w088HnHHkzYQ.jpg', 1, 2, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1465984058, 1, 1, 20, 0, 5, '30', '方庄簋街二楼(家乐福与阳光市场旁)', '39.89195794993252,116.46091461', '<p><span style="color:#282828;font-family:&#39;microsoft yahei&#39;, &#39;hiragino sans gb&#39;;font-size:14px;line-height:24px;">这是一家方庄附近的粤菜馆。虽然刚开业半年，但是菜品和服务都很到位！这次特意带爸爸妈妈来尝尝。我们三个人点了一份锡纸鲈鱼，半只盐焗鸡，一份咸蛋黄焗南瓜，一份凉拌冰草</span><br /></p>', '');
INSERT INTO `qb_waimai_company` (`id`, `zone_id`, `street_id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `sendprice`, `arrive_time`, `address`, `maps`, `content`, `telphoto`) VALUES(4, 0, 0, '潇湘95号院', 0, 1, '', 16, 0, 1465981605, '1465981605', 1, 'admin', '', 'http://qcloud.dpfile.com/pc/zFecokyNOin1OixxrD0YOZUi0BJ612KeLYfMYhiT3eDRhHMmqiV8YanicpcwG53UsUUjuoRat_w088HnHHkzYQ.jpg', 1, 2, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1466818449, 1, 1, 20, 0, 5, '30', '西城区 宣武门西大街95号', '39.91250133090293,116.37096405', '<p><span style="color:#282828;font-family:&#39;microsoft yahei&#39;, &#39;hiragino sans gb&#39;;font-size:14px;line-height:24px;">500一位，还不含酒水，吃的就是环境，原料据说不错，但菜品味道真是一般</span><br /></p>', '');
INSERT INTO `qb_waimai_company` (`id`, `zone_id`, `street_id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `sendprice`, `arrive_time`, `address`, `maps`, `content`, `telphoto`) VALUES(5, 0, 0, '辉记士多店', 0, 2, '士多便利店类', 8, 0, 1465981741, '1465981741', 1, 'admin', '', 'http://file.youboy.com/d/85/7/54/6/850636.jpg', 1, 2, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1468309500, 1, 1, 20, 0, 5, '20', '中华路23号', '39.88168394982042,116.36924743', '<p>全心全意为你服务</p>', '');
INSERT INTO `qb_waimai_company` (`id`, `zone_id`, `street_id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `sendprice`, `arrive_time`, `address`, `maps`, `content`, `telphoto`) VALUES(9, 0, 0, '喜士多便利店', 0, 2, '', 1, 0, 1466578897, '1466578897', 1, 'admin', '', 'http://i1.s2.dpfile.com/pc/b42512412979f207f5c21c1e322ae242%28700x700%29/thumb.jpg', 1, 1, 1, 1466578924, '', '127.0.0.1', 0, 0, '', 0, 0, 1466578899, 1, 1, 10, 0, 5, '15', '方庄簋街二楼(家乐福与阳光市场旁)', '116.4423,39.887765', '<p>在天有好货</p>', '');



DROP TABLE IF EXISTS `qb_waimai_companypic`;
CREATE TABLE IF NOT EXISTS `qb_waimai_companypic` (
  `pid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(10) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `mid` smallint(4) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `imgurl` varchar(150) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `id` (`id`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=23 ;



INSERT INTO `qb_waimai_companypic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(14, 1, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/00rLSEnUBxQgHb6aaV4t0oYepCkol6GS4waQqPstXDfNXmvln4Ik58yVA1fN3nW_TYGVDmosZWTLal1WbWRW3A.jpg', '');
INSERT INTO `qb_waimai_companypic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(15, 1, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/ZEUwZg1Ac70xtqt4H-Sl2SB-bPHzBQD42oAGK0XiNv3j3BCsJw3apoGOxNj8GjSqTYGVDmosZWTLal1WbWRW3A.jpg', '');
INSERT INTO `qb_waimai_companypic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(16, 2, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/PXmLCsm0lyUMTo6yCWyV5qn3ISbQ-GIv5lmXH4mLjajJVC6bnk8gaYpElMMrbfnlsUUjuoRat_w088HnHHkzYQ.jpg', '');
INSERT INTO `qb_waimai_companypic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(17, 3, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/-tA6FxybPM3rtKBqpyRvlE9dHJGU_W-O6a1GlswjaCjDmr-smpteMJFKQkVywW2DsUUjuoRat_w088HnHHkzYQ.jpg', '');
INSERT INTO `qb_waimai_companypic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(18, 4, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/zFecokyNOin1OixxrD0YOZUi0BJ612KeLYfMYhiT3eDRhHMmqiV8YanicpcwG53UsUUjuoRat_w088HnHHkzYQ.jpg', '');
INSERT INTO `qb_waimai_companypic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(19, 5, 2, 0, 1, 0, 'http://file.youboy.com/d/85/7/54/6/850636.jpg', '');
INSERT INTO `qb_waimai_companypic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(22, 9, 2, 0, 1, 0, 'http://i1.s2.dpfile.com/pc/b42512412979f207f5c21c1e322ae242%28700x700%29/thumb.jpg', '');



DROP TABLE IF EXISTS `qb_waimai_config`;
CREATE TABLE IF NOT EXISTS `qb_waimai_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;



INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('sort_layout', '1,75#2,4#71,65#5,54#3#', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_ReportDB', '违法信息\r\n过期信息\r\n垃圾信息', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_index_cache', '', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_list_cache', '', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_TopNum', '10', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_TopDay', '10', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_TopMoney', '10', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_TopColor', '#FF0000', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_webOpen', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('rmb_pay', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('rmb_late_pay', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('daili_receive', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('comment_limit', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('ForbidDelOrder', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('giveMoneyFromSystem', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('give_send_sms', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_description', '', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_metakeywords', '商城', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('give_send_mail', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('pay_send_sms2Seller', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('pay_send_mail2Seller', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('order_send_sms', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('order_send_mail', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('order_send_msg', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('UpdatePostTime', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('showNoPassComment', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_ShowNoYz', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('PostInfoMoney', '10', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_close', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('ForbidSellerDelNoPayOrder', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('ForbidDelPayOrder', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('postShopNeedQy', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_allowGuesSearch', '1', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_pre', 'waimai_', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_id', '56', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_description', '', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_keywords', '', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_title', '', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_webname', '外卖', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('IF_Private_tpl', '0', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Private_tpl_head', '', '');
INSERT INTO `qb_waimai_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Private_tpl_foot', '', '');



DROP TABLE IF EXISTS `qb_waimai_content`;
CREATE TABLE IF NOT EXISTS `qb_waimai_content` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `mid` smallint(4) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `fname` varchar(50) NOT NULL default '',
  `hits` mediumint(7) NOT NULL default '0',
  `comments` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` varchar(10) NOT NULL default '',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `picurl` varchar(150) NOT NULL default '',
  `ispic` tinyint(1) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `levels` tinyint(2) NOT NULL default '0',
  `levelstime` int(10) NOT NULL default '0',
  `keywords` varchar(100) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `lastfid` mediumint(7) NOT NULL default '0',
  `money` mediumint(7) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `lastview` int(10) NOT NULL default '0',
  `city_id` mediumint(7) NOT NULL default '0',
  `picnum` smallint(4) NOT NULL default '0',
  `price` double NOT NULL default '0',
  `sellnum` mediumint(7) NOT NULL default '0',
  `myfid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `ispic` (`ispic`),
  KEY `city_id` (`city_id`),
  KEY `list` (`list`,`fid`,`city_id`,`yz`),
  KEY `hits` (`hits`),
  KEY `myfid` (`myfid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=4 ;



INSERT INTO `qb_waimai_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `myfid`) VALUES(1, '生鸡蛋蘸料', 1, 1, '餐饮美食类', 2, 0, 1465983822, '1465983822', 1, 'admin', '', 'http://qcloud.dpfile.com/pc/CMNr0Yy89l5pRAoNN5Py_NJ3lW-aPbajrjqiClyH3UVc9kX-oBDXcOcNNs3lqDhXTYGVDmosZWTLal1WbWRW3A.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1468309493, 1, 1, 50, 0, 0);
INSERT INTO `qb_waimai_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `myfid`) VALUES(2, '火锅', 1, 1, '餐饮美食类', 1, 0, 1465983892, '1465983892', 1, 'admin', '', 'http://qcloud.dpfile.com/pc/Jw_jXCbMkTun-IFpKs6Zk_AixPUSSBW3lkhBJz7ab4uaYBAPVE6jc5e4Mli570xPTYGVDmosZWTLal1WbWRW3A.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1466818410, 1, 1, 120, 0, 0);
INSERT INTO `qb_waimai_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `passwd`, `begintime`, `endtime`, `lastview`, `city_id`, `picnum`, `price`, `sellnum`, `myfid`) VALUES(3, '鱼刺', 1, 1, '餐饮美食类', 6, 0, 1465984039, '1465984039', 1, 'admin', '', 'http://qcloud.dpfile.com/pc/LPZyOwEkW8P29LeUCWlMvWFc4DIsdinQr9s9hS9H8KwcsKmuFA0GbGX2sKiTUaZxTYGVDmosZWTLal1WbWRW3A.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1466819072, 1, 1, 60, 0, 0);



DROP TABLE IF EXISTS `qb_waimai_content_1`;
CREATE TABLE IF NOT EXISTS `qb_waimai_content_1` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `market_price` varchar(10) NOT NULL default '',
  `shoptype` varchar(50) NOT NULL,
  `storage` int(7) NOT NULL default '0',
  PRIMARY KEY  (`rid`),
  KEY `fid` (`fid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=4 ;



INSERT INTO `qb_waimai_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `market_price`, `shoptype`, `storage`) VALUES(1, 1, 1, 1, '<p>好好吃的</p>', '55', '', 999);
INSERT INTO `qb_waimai_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `market_price`, `shoptype`, `storage`) VALUES(2, 2, 1, 1, '<p>很美味的哦！</p>', '130', '', 999);
INSERT INTO `qb_waimai_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `market_price`, `shoptype`, `storage`) VALUES(3, 3, 1, 1, '<p>美味的哦！</p>', '70', '', 999);



DROP TABLE IF EXISTS `qb_waimai_content_2`;
CREATE TABLE IF NOT EXISTS `qb_waimai_content_2` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` int(10) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `order_username` varchar(20) NOT NULL default '',
  `order_phone` varchar(20) NOT NULL default '',
  `order_mobphone` varchar(15) NOT NULL default '',
  `order_email` varchar(50) NOT NULL default '',
  `order_qq` varchar(11) NOT NULL default '',
  `order_postcode` varchar(6) NOT NULL default '',
  `order_sendtype` tinyint(1) NOT NULL default '0',
  `order_paytype` tinyint(1) NOT NULL default '0',
  `order_address` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`rid`),
  KEY `fid` (`fid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=17 ;


DROP TABLE IF EXISTS `qb_waimai_dianping`;
CREATE TABLE IF NOT EXISTS `qb_waimai_dianping` (
  `cid` mediumint(7) unsigned NOT NULL auto_increment,
  `cuid` int(7) NOT NULL default '0',
  `type` tinyint(2) NOT NULL default '0',
  `id` mediumint(7) unsigned NOT NULL default '0',
  `fid` mediumint(7) unsigned NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `fen1` smallint(4) NOT NULL default '0',
  `fen2` smallint(4) NOT NULL default '0',
  `fen3` smallint(4) NOT NULL default '0',
  `fen4` smallint(4) NOT NULL default '0',
  `fen5` smallint(4) NOT NULL default '0',
  `flowers` smallint(4) NOT NULL default '0',
  `egg` smallint(4) NOT NULL default '0',
  `price` mediumint(5) NOT NULL default '0',
  `keywords` varchar(100) NOT NULL default '',
  `keywords2` varchar(100) NOT NULL default '',
  `fen6` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`cid`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;



DROP TABLE IF EXISTS `qb_waimai_field`;
CREATE TABLE IF NOT EXISTS `qb_waimai_field` (
  `id` mediumint(7) NOT NULL auto_increment,
  `mid` mediumint(5) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `field_name` varchar(30) NOT NULL default '',
  `field_type` varchar(15) NOT NULL default '',
  `field_leng` smallint(3) NOT NULL default '0',
  `orderlist` int(10) NOT NULL default '0',
  `form_type` varchar(15) NOT NULL default '',
  `field_inputwidth` smallint(3) default NULL,
  `field_inputheight` smallint(3) NOT NULL default '0',
  `form_set` text NOT NULL,
  `form_value` text NOT NULL,
  `form_units` varchar(30) NOT NULL default '',
  `form_title` text NOT NULL,
  `mustfill` tinyint(1) NOT NULL default '0',
  `listshow` tinyint(1) NOT NULL default '0',
  `listfilter` tinyint(1) default NULL,
  `search` tinyint(1) NOT NULL default '0',
  `allowview` varchar(255) NOT NULL default '',
  `allowpost` varchar(255) NOT NULL default '',
  `js_check` text NOT NULL,
  `js_checkmsg` varchar(255) NOT NULL default '',
  `classid` mediumint(7) NOT NULL default '0',
  `form_js` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=154 ;



INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(86, 1, '商品介绍', 'content', 'mediumtext', 0, 1, 'ieedit', 600, 250, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(151, 2, '支付方式', 'order_paytype', 'int', 1, 2, 'radio', 0, 0, '1|货到付款 \r\n2|银行电汇或ATM转帐\r\n3|邮局汇款\r\n4|网上即时支付', '1', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(152, 2, '联系地址', 'order_address', 'varchar', 100, 1, 'text', 200, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(80, 1, '种类', 'shoptype', 'varchar', 50, 7, 'text', 10, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(142, 2, '附注留言', 'content', 'mediumtext', 0, -1, 'textarea', 400, 50, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(78, 1, '市场价格', 'market_price', 'varchar', 10, 9, 'text', 12, 0, '', '', '元', '', 0, 1, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(145, 2, '联系电话', 'order_phone', 'varchar', 20, 8, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(144, 2, '顾客姓名', 'order_username', 'varchar', 20, 9, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(146, 2, '联系手机', 'order_mobphone', 'varchar', 15, 7, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(147, 2, '联系邮箱', 'order_email', 'varchar', 50, 6, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(148, 2, '联系QQ', 'order_qq', 'varchar', 11, 5, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(149, 2, '邮政编码', 'order_postcode', 'varchar', 6, 4, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(150, 2, '配送方式', 'order_sendtype', 'int', 1, 3, 'radio', 0, 0, '1|上门取货\r\n2|平邮\r\n3|普通快递\r\n4|EMS快递', '3', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_waimai_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(153, 1, '库存量', 'storage', 'int', 7, 6, 'text', 50, 0, '', '999', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');



DROP TABLE IF EXISTS `qb_waimai_join`;
CREATE TABLE IF NOT EXISTS `qb_waimai_join` (
  `id` mediumint(7) NOT NULL auto_increment,
  `mid` smallint(4) NOT NULL default '0',
  `cid` mediumint(7) NOT NULL default '0',
  `cuid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `shopnum` mediumint(7) NOT NULL default '0',
  `ifpay` tinyint(1) NOT NULL default '0',
  `ifsend` tinyint(1) NOT NULL default '0',
  `totalmoney` float NOT NULL default '0',
  `banktype` varchar(15) NOT NULL default '',
  `emscode` varchar(32) NOT NULL default '',
  `products` text NOT NULL,
  `rmb` float NOT NULL default '0',
  `receive` tinyint(1) NOT NULL default '0',
  `ifcomment` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`),
  KEY `yz` (`yz`,`fid`,`mid`,`cid`),
  KEY `cuid` (`cuid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=17 ;



DROP TABLE IF EXISTS `qb_waimai_module`;
CREATE TABLE IF NOT EXISTS `qb_waimai_module` (
  `id` smallint(4) NOT NULL auto_increment,
  `sort_id` mediumint(5) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `list` smallint(4) NOT NULL default '0',
  `style` varchar(50) NOT NULL default '',
  `config` text NOT NULL,
  `config2` text NOT NULL,
  `comment_type` tinyint(1) NOT NULL default '0',
  `ifdp` tinyint(1) NOT NULL default '0',
  `template` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;



INSERT INTO `qb_waimai_module` (`id`, `sort_id`, `name`, `list`, `style`, `config`, `config2`, `comment_type`, `ifdp`, `template`) VALUES(2, 0, '订购表单', 1, '', '', '', 0, 0, 'a:4:{s:4:"list";s:12:"joinlist.htm";s:4:"show";s:12:"joinshow.htm";s:4:"post";s:8:"join.htm";s:6:"search";s:0:"";}');
INSERT INTO `qb_waimai_module` (`id`, `sort_id`, `name`, `list`, `style`, `config`, `config2`, `comment_type`, `ifdp`, `template`) VALUES(1, 0, '外卖商品', 4, '', '', '', 1, 0, '');



DROP TABLE IF EXISTS `qb_waimai_mysort`;
CREATE TABLE IF NOT EXISTS `qb_waimai_mysort` (
  `fid` int(10) NOT NULL auto_increment,
  `fup` int(10) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `uid` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=6 ;



INSERT INTO `qb_waimai_mysort` (`fid`, `fup`, `type`, `name`, `uid`, `list`) VALUES(3, 0, 0, '菜品', 1, 0);
INSERT INTO `qb_waimai_mysort` (`fid`, `fup`, `type`, `name`, `uid`, `list`) VALUES(2, 0, 1, '饮料', 1, 1);
INSERT INTO `qb_waimai_mysort` (`fid`, `fup`, `type`, `name`, `uid`, `list`) VALUES(4, 0, 1, '小吃', 1, 0);
INSERT INTO `qb_waimai_mysort` (`fid`, `fup`, `type`, `name`, `uid`, `list`) VALUES(5, 0, 1, '零食', 1, 0);



DROP TABLE IF EXISTS `qb_waimai_pic`;
CREATE TABLE IF NOT EXISTS `qb_waimai_pic` (
  `pid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(10) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `mid` smallint(4) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `imgurl` varchar(150) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `id` (`id`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=4 ;



INSERT INTO `qb_waimai_pic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(1, 1, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/CMNr0Yy89l5pRAoNN5Py_NJ3lW-aPbajrjqiClyH3UVc9kX-oBDXcOcNNs3lqDhXTYGVDmosZWTLal1WbWRW3A.jpg', '');
INSERT INTO `qb_waimai_pic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(2, 2, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/Jw_jXCbMkTun-IFpKs6Zk_AixPUSSBW3lkhBJz7ab4uaYBAPVE6jc5e4Mli570xPTYGVDmosZWTLal1WbWRW3A.jpg', '');
INSERT INTO `qb_waimai_pic` (`pid`, `id`, `fid`, `mid`, `uid`, `type`, `imgurl`, `name`) VALUES(3, 3, 1, 0, 1, 0, 'http://qcloud.dpfile.com/pc/LPZyOwEkW8P29LeUCWlMvWFc4DIsdinQr9s9hS9H8KwcsKmuFA0GbGX2sKiTUaZxTYGVDmosZWTLal1WbWRW3A.jpg', '');



DROP TABLE IF EXISTS `qb_waimai_report`;
CREATE TABLE IF NOT EXISTS `qb_waimai_report` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `onlineip` varchar(15) NOT NULL default '',
  `type` tinyint(2) NOT NULL default '0',
  `content` text NOT NULL,
  `iftrue` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `qb_waimai_sort`;
CREATE TABLE IF NOT EXISTS `qb_waimai_sort` (
  `fid` mediumint(7) unsigned NOT NULL auto_increment,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `mid` smallint(4) NOT NULL default '0',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metatitle` varchar(250) NOT NULL default '',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` mediumtext NOT NULL,
  `index_show` tinyint(1) NOT NULL default '0',
  `contents` mediumint(4) NOT NULL default '0',
  `tableid` varchar(30) NOT NULL default '',
  `dir_name` varchar(50) NOT NULL default '',
  `ifcolor` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`fid`),
  KEY `mid` (`mid`),
  KEY `fup` (`fup`,`list`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;



INSERT INTO `qb_waimai_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(1, 0, '餐饮美食类', 1, 1, 0, 0, '', 0, 0, '', 'http://wlife.net/images/wins/info3.png', '', '', 'a:4:{s:4:"head";s:0:"";s:4:"foot";s:0:"";s:4:"list";s:0:"";s:8:"bencandy";s:0:"";}', '', 0, '', '', '', 0, '', '', '', '', 0, 'a:1:{s:11:"field_value";N;}', 0, 0, '', '', 0);
INSERT INTO `qb_waimai_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(2, 0, '士多便利店类', 1, 1, 0, 0, '', 0, 0, '', '', '', '', 'a:4:{s:4:"head";s:0:"";s:4:"foot";s:0:"";s:4:"list";s:0:"";s:8:"bencandy";s:0:"";}', '', 0, '', '', '', 0, '', '', '', '', 0, 'a:1:{s:11:"field_value";N;}', 0, 0, '', '', 0);
