INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`, `ifsys`) VALUES (55, 2, '上门服务', 'fuwu_', 'fuwu', '', '', 'a:7:{s:12:"list_PhpName";s:18:"list.php?&fid=$fid";s:12:"show_PhpName";s:29:"bencandy.php?&fid=$fid&id=$id";s:8:"MakeHtml";N;s:14:"list_HtmlName1";N;s:14:"show_HtmlName1";N;s:14:"list_HtmlName2";N;s:14:"show_HtmlName2";N;}', 78, '', '', 0, 1);



DROP TABLE IF EXISTS `qb_fuwu_collection`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_collection` (
  `cid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `qb_fuwu_comments`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_comments` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;



INSERT INTO `qb_fuwu_comments` (`cid`, `cuid`, `type`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`, `flowers`, `egg`) VALUES(1, 1, 0, 36, 6, 1, 'admin', 1289872552, '4546', '127.0.0.1', 0, 1, 0, 0);



DROP TABLE IF EXISTS `qb_fuwu_config`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;



INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_close', '0', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_allowpost', '9', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_webOpen', '1', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('IF_Private_tpl', '0', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Private_tpl_head', '', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_ReportDB', '虚假信息\r\n过期信息\r\n垃圾信息', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_pre', 'fuwu_', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_id', '55', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_description', '', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_keywords', '', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_title', '', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_webname', '上门服务', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Private_tpl_foot', '', '');



DROP TABLE IF EXISTS `qb_fuwu_content`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_content` (
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
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `lastview` int(10) NOT NULL default '0',
  `city_id` mediumint(7) NOT NULL default '0',
  `totaluser` mediumint(7) unsigned NOT NULL default '0',
  `gg_maps` varchar(50) NOT NULL,
  `replytime` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `ispic` (`ispic`),
  KEY `city_id` (`city_id`),
  KEY `list` (`list`,`fid`,`city_id`,`yz`),
  KEY `hits` (`hits`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=48 ;



INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(42, '云山保洁家政公司', 1, 1, '保洁服务', 6, 0, 1465361401, '1465361401', 1, 'admin', '', 'http://img.jdzj.com/UserDocument/2014d/jinlilai/Picture/201489205028.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466567697, 1, 0, '39.88603083007855,116.51498794555664', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(43, '金诺家电维修', 1, 5, '家电维修', 1, 0, 1465361836, '1465361836', 1, 'admin', '', 'http://image2.sina.com.cn/dy/c/2006-09-08/d893a4a8867e45a521a18b6a37556279.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1465368631, 1, 0, '39.887611445481035,116.36220932006836', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(44, '邦清洁服务有限公司', 1, 2, '地板打蜡', 2, 0, 1465362437, '1465362437', 1, 'admin', '', 'http://img003.hc360.cn/m3/M03/44/8A/wKhQ51SeFdCEQnB6AAAAAOT0-RM600.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1465369526, 1, 0, '39.877732001352726,116.36787414550781', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(45, '深圳沙发翻新', 1, 6, '沙发保养', 7, 0, 1465368616, '1465368616', 1, 'admin', '', 'http://file.youboy.com/d/152/68/45/3/865223.JPG', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466653389, 1, 0, '39.865611272604156,116.39516830444336', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(46, '广州市通马桶', 1, 10, '马桶疏通', 1, 0, 1466579235, '1466579235', 1, 'admin', '', 'http://www.haogongzhang.com/Uploads/baike/201412/54817665903d0.png', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466579236, 1, 0, '116.54176,39.826616', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(47, 'APP上门洗车', 1, 4, '上门洗车', 1, 0, 1466579361, '1466579361', 1, 'admin', '', 'qb_fuwu_/4/1_20160623110639_fk3jx.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466579363, 1, 0, '', 0);



DROP TABLE IF EXISTS `qb_fuwu_content_1`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_content_1` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `servetime` varchar(50) NOT NULL,
  `moneytype` varchar(50) NOT NULL,
  `telphone` varchar(30) NOT NULL,
  `linkman` varchar(20) NOT NULL,
  `qq` varchar(20) NOT NULL,
  PRIMARY KEY  (`rid`),
  KEY `fid` (`fid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=31 ;



INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(25, 42, 1, 1, '<p><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">商场保洁是确保商场室内外环境卫生整洁，是后勤保障工作的重要部分、对于商场各项工作具有十分重要的意义。用科学的技术和方法提高保洁工作质量，是商场管理者很值得研究的重要课题。商场保洁环境卫生 操作程序 质量考核 商场保洁，顾名思义，就是确保商场环境卫生，随着社会的发展，文明程度不断提高，人们对环境质量的要求越来越高，因此维护好公共场所的清洁卫生，就象一个人每天要洗脸，保持衣着整洁一样重要，现代商场管理中，如何运用科学的技术和方法，如何规范管理，如何制定质量标准，提高环境卫生的工作质量，是我们商场管理者很值得研究的课题。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;商场保洁管理</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;一.第一种托管形式：</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">① 固定保洁员1名，每天3-5小时保洁时间（节假日不计）。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">② 作息时间企业定，过午供餐。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">③ 服务内容包括：门前三包、抹桌擦窗、推尘擦地、收拾垃圾、清理卫生间。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">④ 公司提供：简单清洁用具、清洁剂（不包括垃圾袋、卫生纸、香皂、卫生球等清洁消耗品）。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">⑤ 公司每月对托管方进行一次全面擦玻璃，卫生间消毒服务，企业享受其它清洁服务一律八折收费。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;托管价格：双方协议</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;二.第二种托管形式：</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">① 固定保洁员1名起始：全天负责企业卫生清理（节假日不计），每天8小时工作制。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">② 服务内容包括：所有企业内部清理卫生工作。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">③ 公司提供：清洁用品、清洁剂、垃圾袋、卫生纸、香皂（洗衣粉）、卫生球等清洁消耗品。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">④ 公司每月对托管方进行一次全方位保洁杀菌服务，企业享受其它清洁服务一律八折收费。</span><br /></p>', '早上9点到晚上9点', '50元/小时', '02028654212', '张生', '5466456');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(24, 42, 1, 1, '<p><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">商场保洁是确保商场室内外环境卫生整洁，是后勤保障工作的重要部分、对于商场各项工作具有十分重要的意义。用科学的技术和方法提高保洁工作质量，是商场管理者很值得研究的重要课题。商场保洁环境卫生 操作程序 质量考核 商场保洁，顾名思义，就是确保商场环境卫生，随着社会的发展，文明程度不断提高，人们对环境质量的要求越来越高，因此维护好公共场所的清洁卫生，就象一个人每天要洗脸，保持衣着整洁一样重要，现代商场管理中，如何运用科学的技术和方法，如何规范管理，如何制定质量标准，提高环境卫生的工作质量，是我们商场管理者很值得研究的课题。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;商场保洁管理</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;一.第一种托管形式：</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">① 固定保洁员1名，每天3-5小时保洁时间（节假日不计）。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">② 作息时间企业定，过午供餐。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">③ 服务内容包括：门前三包、抹桌擦窗、推尘擦地、收拾垃圾、清理卫生间。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">④ 公司提供：简单清洁用具、清洁剂（不包括垃圾袋、卫生纸、香皂、卫生球等清洁消耗品）。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">⑤ 公司每月对托管方进行一次全面擦玻璃，卫生间消毒服务，企业享受其它清洁服务一律八折收费。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;托管价格：双方协议</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;二.第二种托管形式：</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">① 固定保洁员1名起始：全天负责企业卫生清理（节假日不计），每天8小时工作制。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">② 服务内容包括：所有企业内部清理卫生工作。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">③ 公司提供：清洁用品、清洁剂、垃圾袋、卫生纸、香皂（洗衣粉）、卫生球等清洁消耗品。</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">④ 公司每月对托管方进行一次全方位保洁杀菌服务，企业享受其它清洁服务一律八折收费。</span><br /></p>', '早上9点到晚上9点', '50元/小时', '02028654212', '张生', '5466456');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(26, 43, 5, 1, '<p style="text-align:left;"><span style="color:#666666;font-family:arial, helvetica, sans-serif;font-size:13px;line-height:18px;background-color:#ffffff;">金诺家电维修在全国组织了专业化技术高的上门维修队伍统一培训，统一服务规范，统管理模式。经过十余年不断发展壮大，在技术上与规模上早步步领先。<br style="color:#666666;font-family:arial, helvetica, sans-serif;font-size:13px;line-height:18px;background-color:#ffffff;" />　　 特别擅长维修松下、索尼、东芝等品牌电视：大屏幕彩电、液晶电视、等离子电视使用频繁，故障率高，因品牌多、配件、维修资料少，维修难度大，特聘一批有十几年经验技师上门维修，普通话粤语流利，检测速度　快，判断准，使原厂配件，以保重质量。 <br style="color:#666666;font-family:arial, helvetica, sans-serif;font-size:13px;line-height:18px;background-color:#ffffff;" />　　专业维修冰箱、空调、洗衣机：由于制冷家电的智能化、电脑化、对维修人没的技术要求越来越高，属下冷气工程部设有专业技术人员，经验丰富，速度快返修率低对此类机更是得心应手。</span><br /></p>', '全天候', '100元/件', '02025487548', '李生', '5464588');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(27, 44, 2, 1, '<p>　广州雅邦清洁服务有限公司是经广州市工商行政管理局批准成立的(注册资本为人民币200万元)广州清洁公司,广州保洁公司，是集高空作业、地毯清洗、沙发清洗</p><p>、物业保洁、石材翻新、地板打蜡清洁环保企业。本公司经国家工商行政管理总局商标局审核、批准是“雅邦”商标唯一、合法的持有人。为了提高清洁服务质量，环境管理水平和员工的环保意识，实施质量第一，品牌战略的现代企业经营理念，本公司在2006年全面推行了ISO9001质量管理体系认证和ISO14001国际环保体系认证。</p><p>　　本公司自创立以来，为了切实满足广州及周边地区的保洁、清洁需求，在广州市各区铺设清洁公司营业网点的基础上增设了东莞清洁、佛山清洁、增城清洁、从化清洁等清洁公司，力求为客户提供更好更快的清洁服务。</p><p><br /></p><p>　　二十年来，雅邦清洁服务有限公司拥有真空吸粪车8台、吊桶垃圾车12台、自卸封闭式垃圾车33台、人货车5台、面包车11台和各类大型、先进的清洁、保洁设备，并拥有一支2380余人经过专业培训、高素质爱岗敬业的清洁、保洁队伍。(其中物业保洁部员工总数在2007年突破3000人)这支队伍被分为八个部门：保洁公司</p>', '周一至周五工作日', '100元/件', '02021254545', '何生', '875445');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(28, 45, 6, 1, '<p><span id="_baidu_bookmark_start_17" style="display:none;line-height:0px;"></span><span id="_baidu_bookmark_start_19" style="display:none;line-height:0px;"></span></p><p>沙发是客厅中必须要有的家具，没有沙发的家里会显得空荡荡。沙发大多非常的好看，但使用久了，沙发外面的一层皮可能会出现脱落、颜色变浅等等损害沙发外观的情况。如果想要让沙发恢复以往美丽漂亮的莫言，就需要对沙发进行换皮，让沙发外面重新拥有一层漂亮的皮。那么，沙发换皮多少钱?不妨来看看土巴兔装修网小编介绍的沙发换皮相关知识。<br /></p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_d7830c4819ce768ced89LPPR6TzCIMWe.jpg?" title="沙发换皮" alt="沙发换皮" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, 微软雅黑, 黑体, 宋体, &#39;microsoft jhenghei&#39;, 华文细黑, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">沙发换皮多少钱?沙发换皮价格有便宜有贵的，主要是按换皮的面积来计算价格，比较常见的价格是10元——20元/平方英尺，也就是0.3*0.3平方米，但这个价格里面是没有包括人工费用，实际换皮的价格可能会比这个价格还要高一些。</p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_cb89a5a290fe4212284dTsC0V2TxQ2wM.jpg?" title="沙发换皮" alt="沙发换皮" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, 微软雅黑, 黑体, 宋体, &#39;microsoft jhenghei&#39;, 华文细黑, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">沙发换皮注意事项：</p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, 微软雅黑, 黑体, 宋体, &#39;microsoft jhenghei&#39;, 华文细黑, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">1、换皮之前要先选择沙发皮，素色、深色、线条、碎花、方格等都是常见的沙发皮选择，要根据客厅的面积和沙发原本的风格来看;如果客厅是比较小，沙发本身颜色比较淡，就建议更换小清新风格的沙发皮;如果客户比较大，光线充足，就可以选择深颜色的沙发皮，带来富丽堂皇感。</p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_7ef79b7ef7f7cb36e7a5mg94l0DxfSWu.jpg?" title="沙发换皮" alt="沙发换皮" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, 微软雅黑, 黑体, 宋体, &#39;microsoft jhenghei&#39;, 华文细黑, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">2、沙发如果是皮质，出现破损的时候就需要特别的注意更换，但在在更换的时候要注意与原来的皮颜色差异不要过大，同时要能够与整体客厅的风格互相呼应，不要更换皮后变得很难看，影响到美观。</p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, 微软雅黑, 黑体, 宋体, &#39;microsoft jhenghei&#39;, 华文细黑, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">3、更换沙发皮的时候，还需要与天花板、<a href="http://mall.to8to.com/tag/men/" target="_blank" style="margin:0px;padding:0px;outline:none;cursor:pointer;color:#14bf76;text-decoration:none !important;">门</a>窗、<span id="_baidu_bookmark_start_15" style="display:none;line-height:0px;"></span><a href="http://www.to8to.com/baike/1215" target="_blank"><span id="_baidu_bookmark_start_13" style="display:none;line-height:0px;"></span>地面</a>、<a href="http://jiaju.to8to.com/list/chuanglian/" target="_blank" style="margin:0px;padding:0px;outline:none;cursor:pointer;color:#14bf76;text-decoration:none !important;">窗帘<span id="_baidu_bookmark_end_14" style="display:none;line-height:0px;"></span></a><span id="_baidu_bookmark_end_16" style="display:none;line-height:0px;"></span>等等颜色与风格互相呼应，至少要能在色调上差不多，不要原本是黄色的客厅，弄一个红色的沙发皮，这样从视觉效果上来说就会显得非常的突兀，非常的违和。</p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_9862b1cd229690d3ca1dv4TBuGWZjVz0.jpg?" title="沙发换皮" alt="沙发换皮" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, 微软雅黑, 黑体, 宋体, &#39;microsoft jhenghei&#39;, 华文细黑, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">沙发皮换了后，不是就代表可以随意损坏它的了，人们日常生活中要能够好好保养，不要用尖锐的物品突破沙发皮，才不会让沙发皮受到损坏。</p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, 微软雅黑, 黑体, 宋体, &#39;microsoft jhenghei&#39;, 华文细黑, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">沙发换皮多少钱?通过文章的介绍后，大家对于沙发换皮价格也有一定的人士，由于沙发换皮采用的皮类型不同，而且更换的面积、不同沙发换皮厂家商家等因素影响，沙发换皮价格也会不一样，在需要沙发换皮的时候，需要先确定好要换的皮样式和类型、面积等，然后请相关的工作人员为你报价。</p><p><br /><span id="_baidu_bookmark_end_8" style="display:none;line-height:0px;"></span><span id="_baidu_bookmark_end_6" style="display:none;line-height:0px;"></span></p><p><span id="_baidu_bookmark_end_20" style="display:none;line-height:0px;"></span><span id="_baidu_bookmark_end_18" style="display:none;line-height:0px;"></span></p>', '全天候', '3000元/套', '02025454587', '黄生', '487846');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(29, 46, 10, 1, '<p>广州市通马桶收费标准，速度快。技术专业可靠 正规疏通公司，疏通疏通管道工具齐全，票据齐全，节假日不休息24小时服务，随叫随到</p>', '全天候', '50元/次', '02021254568', '何生', '43242342');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(30, 47, 4, 1, '<p>上门洗车加盟，1-2周即可完成前期培训及筹备工作，低自行寻找客户，每天轻松接单!上门洗车加盟，总部提供产品配送，投诉处理，推广，促销等后续服务，解决后顾之忧</p>', '白天', '30元/次', '13654587454', '张生', '7845454');



DROP TABLE IF EXISTS `qb_fuwu_content_2`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_content_2` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `realname` varchar(30) NOT NULL,
  `telphone` varchar(20) NOT NULL default '',
  `gototime` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  PRIMARY KEY  (`rid`),
  KEY `fid` (`fid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=8 ;



INSERT INTO `qb_fuwu_content_2` (`rid`, `id`, `fid`, `uid`, `content`, `realname`, `telphone`, `gototime`, `address`) VALUES(6, 42, 1, 1, '空调安装', '唐小姐', '13710936097', '下午 3:00', '北京市 朝阳区 5环街 81号');
INSERT INTO `qb_fuwu_content_2` (`rid`, `id`, `fid`, `uid`, `content`, `realname`, `telphone`, `gototime`, `address`) VALUES(4, 42, 1, 27, '打扫卫生', '林先生', '13456734566', '下午 4:00', '北京 朝阳区 3环街道 34号');
INSERT INTO `qb_fuwu_content_2` (`rid`, `id`, `fid`, `uid`, `content`, `realname`, `telphone`, `gototime`, `address`) VALUES(7, 41, 1, 1, '', 'fdsafds', 'rewqrew', 'rewqrewq', 'rewqrewqr');



DROP TABLE IF EXISTS `qb_fuwu_dianping`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_dianping` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=10 ;



INSERT INTO `qb_fuwu_dianping` (`cid`, `cuid`, `type`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`, `fen1`, `fen2`, `fen3`, `fen4`, `fen5`, `flowers`, `egg`, `price`, `keywords`, `keywords2`, `fen6`) VALUES(9, 1, 0, 41, 1, 0, 'fda', 1465354688, 'fdsafdsafds', '127.0.0.1', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '');



DROP TABLE IF EXISTS `qb_fuwu_field`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_field` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=153 ;



INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(88, 2, '附注留言', 'content', 'mediumtext', 0, 2, 'textarea', 500, 100, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(86, 1, '商家介绍', 'content', 'mediumtext', 0, 1, 'ieedit', 700, 250, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(150, 2, '上门时间', 'gototime', 'varchar', 50, 10, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(144, 2, '联系人', 'realname', 'varchar', 30, 9, 'text', 8, 0, '', '', '', '', 0, 1, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(145, 2, '联系电话', 'telphone', 'varchar', 20, 8, 'text', 10, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(152, 1, '联系人', 'linkman', 'varchar', 20, 12, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(148, 1, '服务时间', 'servetime', 'varchar', 50, 9, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(149, 1, '收费标准', 'moneytype', 'varchar', 50, 10, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(151, 1, '联系电话', 'telphone', 'varchar', 30, 11, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');



DROP TABLE IF EXISTS `qb_fuwu_join`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_join` (
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
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`),
  KEY `fid` (`fid`,`cid`),
  KEY `yz` (`yz`,`fid`,`mid`,`cid`),
  KEY `cuid` (`cuid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=7 ;



DROP TABLE IF EXISTS `qb_fuwu_module`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_module` (
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



INSERT INTO `qb_fuwu_module` (`id`, `sort_id`, `name`, `list`, `style`, `config`, `config2`, `comment_type`, `ifdp`, `template`) VALUES(1, 0, '商家', 10, '', 'a:1:{s:9:"moduleSet";a:1:{s:6:"useMap";s:1:"1";}}', '', 1, 0, '');
INSERT INTO `qb_fuwu_module` (`id`, `sort_id`, `name`, `list`, `style`, `config`, `config2`, `comment_type`, `ifdp`, `template`) VALUES(2, 0, '预约表单', 4, '', 'a:1:{s:9:"moduleSet";a:1:{s:6:"useMap";s:1:"0";}}', '', 1, 0, 'a:4:{s:4:"list";s:12:"joinlist.htm";s:4:"show";s:12:"joinshow.htm";s:4:"post";s:8:"join.htm";s:6:"search";s:0:"";}');



DROP TABLE IF EXISTS `qb_fuwu_report`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_report` (
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



DROP TABLE IF EXISTS `qb_fuwu_sort`;
CREATE TABLE IF NOT EXISTS `qb_fuwu_sort` (
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
  `ifcolor` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=11 ;



INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(1, 0, '保洁服务', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(2, 0, '地板打蜡', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(3, 0, '空调移机', 1, 1, 0, 0, '', 0, 0, '', '', '', '', 'N;', '', 0, '', '', '', 0, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(4, 0, '上门洗车', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(5, 0, '家电维修', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(6, 0, '沙发保养', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(7, 0, '手机维修', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(8, 0, '厨卫保洁', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(9, 0, '开锁换锁', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(10, 0, '马桶疏通', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
