INSERT INTO `qb_hack` (`keywords`, `name`, `isclose`, `author`, `config`, `htmlcode`, `hackfile`, `hacksqltable`, `adminurl`, `about`, `class1`, `class2`, `list`, `linkname`, `isbiz`) VALUES ('weixin', '微信自动答复设置', 0, '', '', '', '', '', 'index.php?lfj=weixin&job=list', '', 'other', '其它功能', 9, '', 0);

DROP TABLE IF EXISTS `qb_weixingword`;
CREATE TABLE `qb_weixingword` (
  `id` int(10) NOT NULL auto_increment,
  `ask` varchar(50) NOT NULL default '',
  `answer` text NOT NULL,
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;