INSERT INTO `qb_hack` (`keywords`, `name`, `isclose`, `author`, `config`, `htmlcode`, `hackfile`, `hacksqltable`, `adminurl`, `about`, `class1`, `class2`, `list`, `linkname`, `isbiz`) VALUES ('propagandize', '�ƹ�׬ȡ���ֹ���', 0, '', '', '', '', '', 'index.php?lfj=propagandize&job=list', '', 'other', '��������', 8, '', 0);


DROP TABLE IF EXISTS `qb_propagandize`;
CREATE TABLE `qb_propagandize` (
  `id` int(10) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `ip` bigint(11) NOT NULL default '0',
  `day` smallint(3) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `fromurl` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `day` (`day`,`uid`,`ip`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
