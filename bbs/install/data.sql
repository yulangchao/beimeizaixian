INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`, `ifsys`) VALUES ('', 2, '论坛交流', 'bbs_', 'bbs', '', '', '', 0, '', '', 0, 0);





DROP TABLE IF EXISTS `qb_bbs_comments`;
CREATE TABLE IF NOT EXISTS `qb_bbs_comments` (
  `cid` mediumint(7) unsigned NOT NULL auto_increment,
  `id` int(10) unsigned NOT NULL default '0',
  `cids` int(10) NOT NULL,
  `fid` mediumint(7) unsigned NOT NULL default '0',
  `cuid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `ifcom` tinyint(1) NOT NULL default '0',
  `agree` mediumint(5) NOT NULL default '0',
  `disagree` mediumint(5) NOT NULL default '0',
  `quoteid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `aid` (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `ifcom` (`ifcom`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=76 ;



INSERT INTO `qb_bbs_comments` (`cid`, `id`, `cids`, `fid`, `cuid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`, `ifcom`, `agree`, `disagree`, `quoteid`) VALUES(75, 979, 0, 1, 1, 1, 'admin', 1464071790, '<p>很好</p>', '127.0.0.1', 0, 0, 0, 0, 0, 0);



DROP TABLE IF EXISTS `qb_bbs_config`;
CREATE TABLE IF NOT EXISTS `qb_bbs_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;



INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_PassCommentType', '1', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_webOpen', '1', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_adminfen', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('GroupPostjumpurl', '3,4', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('GroupPostIframe', '3,4', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('IF_Private_tpl', '0', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('UseArea', '0', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_forbidOutPost', '0', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_closeWhy', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('GroupPostYZ', '3,4,8', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_PostCommentType', '1', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_close', '0', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_pre', 'bbs_', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('topArticleMoney', '2', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('frontArticleMoney', '1', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('comArticleMoney', '5', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('deleteArticleMoney', '-2', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('postArticleMoney', '2', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_GroupPostYZ', '3,8,9,10', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('allowGroupPost', '3,8,9,10', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_description', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_metakeywords', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_id', '53', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_description', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_keywords', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_title', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_webname', '论坛交流', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Private_tpl_head', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Private_tpl_foot', '', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_ShowCommentRows', '0', '');
INSERT INTO `qb_bbs_config` (`c_key`, `c_value`, `c_descrip`) VALUES('bbsIndexNote', '', '');



DROP TABLE IF EXISTS `qb_bbs_content`;
CREATE TABLE IF NOT EXISTS `qb_bbs_content` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `title` varchar(150) NOT NULL default '',
  `smalltitle` varchar(100) NOT NULL default '',
  `fid` mediumint(7) unsigned NOT NULL default '0',
  `mid` mediumint(5) NOT NULL default '0',
  `fname` varchar(50) NOT NULL default '',
  `hits` mediumint(7) NOT NULL default '0',
  `pages` smallint(4) NOT NULL default '0',
  `comments` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `author` varchar(30) NOT NULL default '',
  `copyfrom` varchar(100) NOT NULL default '',
  `copyfromurl` varchar(150) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `fonttype` tinyint(1) NOT NULL default '0',
  `picurl` varchar(150) NOT NULL default '0',
  `ispic` tinyint(1) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `yzer` varchar(30) NOT NULL default '',
  `yztime` int(10) NOT NULL default '0',
  `levels` tinyint(2) NOT NULL default '0',
  `levelstime` int(10) NOT NULL default '0',
  `keywords` varchar(100) NOT NULL default '',
  `jumpurl` varchar(150) NOT NULL default '',
  `iframeurl` varchar(150) NOT NULL default '',
  `style` varchar(15) NOT NULL default '',
  `template` varchar(255) NOT NULL default '',
  `target` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `lastfid` mediumint(7) NOT NULL default '0',
  `money` decimal(8,2) NOT NULL default '0.00',
  `buyuser` text NOT NULL,
  `passwd` varchar(32) NOT NULL default '',
  `allowdown` varchar(150) NOT NULL default '',
  `allowview` varchar(150) NOT NULL default '',
  `editer` varchar(30) NOT NULL default '',
  `edittime` int(10) NOT NULL default '0',
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `description` text NOT NULL,
  `lastview` int(10) NOT NULL default '0',
  `digg_num` mediumint(7) NOT NULL default '0',
  `digg_time` int(10) NOT NULL default '0',
  `forbidcomment` tinyint(1) NOT NULL default '0',
  `ifvote` tinyint(1) NOT NULL default '0',
  `heart` varchar(255) NOT NULL default '',
  `htmlname` varchar(100) NOT NULL default '',
  `city_id` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `hits` (`hits`,`yz`,`fid`,`ispic`),
  KEY `list` (`list`,`yz`,`fid`,`ispic`),
  KEY `ispic` (`ispic`),
  KEY `uid` (`uid`),
  KEY `levels` (`levels`),
  KEY `digg_num` (`digg_num`),
  KEY `digg_time` (`digg_time`),
  KEY `mid` (`mid`),
  KEY `city_id` (`city_id`),
  KEY `posttime` (`yz`,`posttime`,`fid`,`ispic`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=980 ;



INSERT INTO `qb_bbs_content` (`id`, `title`, `smalltitle`, `fid`, `mid`, `fname`, `hits`, `pages`, `comments`, `posttime`, `list`, `uid`, `username`, `author`, `copyfrom`, `copyfromurl`, `titlecolor`, `fonttype`, `picurl`, `ispic`, `yz`, `yzer`, `yztime`, `levels`, `levelstime`, `keywords`, `jumpurl`, `iframeurl`, `style`, `template`, `target`, `ip`, `lastfid`, `money`, `buyuser`, `passwd`, `allowdown`, `allowview`, `editer`, `edittime`, `begintime`, `endtime`, `description`, `lastview`, `digg_num`, `digg_time`, `forbidcomment`, `ifvote`, `heart`, `htmlname`, `city_id`) VALUES(979, '网站全新改版，大家有任何问题与建议欢迎提出来！', '', 1, 1, '投诉建议', 3, 1, 1, 1464071775, 1464071775, 1, 'admin', '', '', '', '', 0, '', 0, 1, '', 0, 0, 0, '', '', '', '', '', 0, '127.0.0.1', 0, 0.00, '', '', '', '', '', 0, 0, 0, '', 1464071790, 0, 0, 0, 0, '', '', 0);



DROP TABLE IF EXISTS `qb_bbs_content_1`;
CREATE TABLE IF NOT EXISTS `qb_bbs_content_1` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `subhead` varchar(150) NOT NULL default '',
  `id` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `topic` tinyint(1) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `orderid` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`rid`),
  KEY `orderid` (`orderid`),
  KEY `topic` (`topic`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=974 ;



INSERT INTO `qb_bbs_content_1` (`rid`, `subhead`, `id`, `fid`, `uid`, `topic`, `content`, `orderid`) VALUES(973, '', 979, 1, 1, 1, '<p>如题所示，欢迎大家把发现的问题提出来！</p>', 0);



DROP TABLE IF EXISTS `qb_bbs_digguser`;
CREATE TABLE IF NOT EXISTS `qb_bbs_digguser` (
  `did` int(10) NOT NULL auto_increment,
  `id` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `posttime` int(10) NOT NULL,
  PRIMARY KEY  (`did`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=5 ;



INSERT INTO `qb_bbs_digguser` (`did`, `id`, `uid`, `posttime`) VALUES(1, 967, 1, 1459483262);
INSERT INTO `qb_bbs_digguser` (`did`, `id`, `uid`, `posttime`) VALUES(2, 969, 1, 1459914671);
INSERT INTO `qb_bbs_digguser` (`did`, `id`, `uid`, `posttime`) VALUES(3, 963, 1, 1459924876);
INSERT INTO `qb_bbs_digguser` (`did`, `id`, `uid`, `posttime`) VALUES(4, 971, 1, 1460453202);



DROP TABLE IF EXISTS `qb_bbs_givemoney`;
CREATE TABLE IF NOT EXISTS `qb_bbs_givemoney` (
  `gid` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `money` decimal(7,2) NOT NULL,
  `postime` int(11) NOT NULL,
  PRIMARY KEY  (`gid`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;



INSERT INTO `qb_bbs_givemoney` (`gid`, `id`, `uid`, `money`, `postime`) VALUES(1, 0, 0, 12345.02, 0);



DROP TABLE IF EXISTS `qb_bbs_keyword`;
CREATE TABLE IF NOT EXISTS `qb_bbs_keyword` (
  `id` mediumint(5) NOT NULL auto_increment,
  `keywords` varchar(30) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `ifhide` tinyint(1) NOT NULL default '0',
  `url` varchar(150) NOT NULL default '',
  `num` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `keywords` (`keywords`),
  KEY `num` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;





DROP TABLE IF EXISTS `qb_bbs_keywordid`;
CREATE TABLE IF NOT EXISTS `qb_bbs_keywordid` (
  `id` mediumint(7) NOT NULL default '0',
  `aid` mediumint(7) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;



DROP TABLE IF EXISTS `qb_bbs_pic`;
CREATE TABLE IF NOT EXISTS `qb_bbs_pic` (
  `pid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(10) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `cid` smallint(4) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `imgurl` varchar(150) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `id` (`id`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `qb_bbs_sort`;
CREATE TABLE IF NOT EXISTS `qb_bbs_sort` (
  `fid` mediumint(7) unsigned NOT NULL auto_increment,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `mid` mediumint(5) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
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
  `metatitle` varchar(255) NOT NULL default '',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list_html` varchar(255) NOT NULL default '',
  `bencandy_html` varchar(255) NOT NULL default '',
  `domain` varchar(150) NOT NULL default '',
  `domain_dir` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `fup` (`fup`),
  KEY `fmid` (`mid`),
  KEY `mid` (`mid`),
  KEY `fup_2` (`fup`,`list`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;


INSERT INTO `qb_bbs_sort` (`fid`, `fup`, `mid`, `name`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `list_html`, `bencandy_html`, `domain`, `domain_dir`) VALUES(1, 0, 1, '投诉建议', 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', '', '', '', '');
INSERT INTO `qb_bbs_sort` (`fid`, `fup`, `mid`, `name`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `list_html`, `bencandy_html`, `domain`, `domain_dir`) VALUES(2, 0, 1, '同城报料', 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', '', '', '', '');
