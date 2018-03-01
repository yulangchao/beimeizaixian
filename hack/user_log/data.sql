INSERT INTO `qb_hack` (`keywords`, `name`, `isclose`, `author`, `config`, `htmlcode`, `hackfile`, `hacksqltable`, `adminurl`, `about`, `class1`, `class2`, `list`, `linkname`, `isbiz`) VALUES ('user_log', '用户ip端口记录', 0, '', '', '', '', '', 'index.php?lfj=user_log&job=list', '', 'other', '其它功能', 8, '', 0);



DROP TABLE IF EXISTS `qb_log`;
CREATE TABLE `qb_log` (
  `lid` int(10) NOT NULL auto_increment,
  `systype` varchar(30) NOT NULL default '',
  `type` tinyint(4) NOT NULL default '0',
  `tag` varchar(50) NOT NULL default '',
  `uid` int(10) NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `port` int(8) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `id` int(10) NOT NULL default '0',
  `fid` int(10) NOT NULL default '0',
  `cid` int(10) NOT NULL default '0',
  `about` text NOT NULL,
  PRIMARY KEY  (`lid`),
  KEY `uid` (`uid`),
  KEY `id` (`id`),
  KEY `posttime` (`posttime`),
  KEY `systype` (`systype`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;