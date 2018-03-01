INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`, `ifsys`) VALUES (55, 2, '���ŷ���', 'fuwu_', 'fuwu', '', '', 'a:7:{s:12:"list_PhpName";s:18:"list.php?&fid=$fid";s:12:"show_PhpName";s:29:"bencandy.php?&fid=$fid&id=$id";s:8:"MakeHtml";N;s:14:"list_HtmlName1";N;s:14:"show_HtmlName1";N;s:14:"list_HtmlName2";N;s:14:"show_HtmlName2";N;}', 78, '', '', 0, 1);



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
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_ReportDB', '�����Ϣ\r\n������Ϣ\r\n������Ϣ', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_pre', 'fuwu_', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('module_id', '55', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_description', '', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_keywords', '', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('SEO_title', '', '');
INSERT INTO `qb_fuwu_config` (`c_key`, `c_value`, `c_descrip`) VALUES('Info_webname', '���ŷ���', '');
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



INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(42, '��ɽ���������˾', 1, 1, '�������', 6, 0, 1465361401, '1465361401', 1, 'admin', '', 'http://img.jdzj.com/UserDocument/2014d/jinlilai/Picture/201489205028.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466567697, 1, 0, '39.88603083007855,116.51498794555664', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(43, '��ŵ�ҵ�ά��', 1, 5, '�ҵ�ά��', 1, 0, 1465361836, '1465361836', 1, 'admin', '', 'http://image2.sina.com.cn/dy/c/2006-09-08/d893a4a8867e45a521a18b6a37556279.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1465368631, 1, 0, '39.887611445481035,116.36220932006836', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(44, '�����������޹�˾', 1, 2, '�ذ����', 2, 0, 1465362437, '1465362437', 1, 'admin', '', 'http://img003.hc360.cn/m3/M03/44/8A/wKhQ51SeFdCEQnB6AAAAAOT0-RM600.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1465369526, 1, 0, '39.877732001352726,116.36787414550781', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(45, '����ɳ������', 1, 6, 'ɳ������', 7, 0, 1465368616, '1465368616', 1, 'admin', '', 'http://file.youboy.com/d/152/68/45/3/865223.JPG', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466653389, 1, 0, '39.865611272604156,116.39516830444336', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(46, '������ͨ��Ͱ', 1, 10, '��Ͱ��ͨ', 1, 0, 1466579235, '1466579235', 1, 'admin', '', 'http://www.haogongzhang.com/Uploads/baike/201412/54817665903d0.png', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466579236, 1, 0, '116.54176,39.826616', 0);
INSERT INTO `qb_fuwu_content` (`id`, `title`, `mid`, `fid`, `fname`, `hits`, `comments`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `picurl`, `ispic`, `yz`, `levels`, `levelstime`, `keywords`, `ip`, `lastfid`, `money`, `begintime`, `endtime`, `lastview`, `city_id`, `totaluser`, `gg_maps`, `replytime`) VALUES(47, 'APP����ϴ��', 1, 4, '����ϴ��', 1, 0, 1466579361, '1466579361', 1, 'admin', '', 'qb_fuwu_/4/1_20160623110639_fk3jx.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, 0, 0, 1466579363, 1, 0, '', 0);



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



INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(25, 42, 1, 1, '<p><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�̳�������ȷ���̳������⻷���������࣬�Ǻ��ڱ��Ϲ�������Ҫ���֡������̳����������ʮ����Ҫ�����塣�ÿ�ѧ�ļ����ͷ�����߱��๤�����������̳������ߺ�ֵ���о�����Ҫ���⡣�̳����໷������ �������� �������� �̳����࣬����˼�壬����ȷ���̳������������������ķ�չ�������̶Ȳ�����ߣ����ǶԻ���������Ҫ��Խ��Խ�ߣ����ά���ù����������������������һ����ÿ��Ҫϴ����������������һ����Ҫ���ִ��̳������У�������ÿ�ѧ�ļ����ͷ�������ι淶��������ƶ�������׼����߻��������Ĺ����������������̳������ߺ�ֵ���о��Ŀ��⡣</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;�̳��������</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;һ.��һ���й���ʽ��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �̶�����Ա1����ÿ��3-5Сʱ����ʱ�䣨�ڼ��ղ��ƣ���</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��Ϣʱ����ҵ�������繩�͡�</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �������ݰ�������ǰ������Ĩ���������Ƴ����ء���ʰ���������������䡣</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾�ṩ��������þߡ�������������������������ֽ��������������������Ʒ����</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾ÿ�¶��йܷ�����һ��ȫ�������������������������ҵ��������������һ�ɰ����շѡ�</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;�йܼ۸�˫��Э��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;��.�ڶ����й���ʽ��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �̶�����Ա1����ʼ��ȫ�츺����ҵ���������ڼ��ղ��ƣ���ÿ��8Сʱ�����ơ�</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �������ݰ�����������ҵ�ڲ���������������</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾�ṩ�������Ʒ��������������������ֽ������ϴ�·ۣ�����������������Ʒ��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾ÿ�¶��йܷ�����һ��ȫ��λ����ɱ��������ҵ��������������һ�ɰ����շѡ�</span><br /></p>', '����9�㵽����9��', '50Ԫ/Сʱ', '02028654212', '����', '5466456');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(24, 42, 1, 1, '<p><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�̳�������ȷ���̳������⻷���������࣬�Ǻ��ڱ��Ϲ�������Ҫ���֡������̳����������ʮ����Ҫ�����塣�ÿ�ѧ�ļ����ͷ�����߱��๤�����������̳������ߺ�ֵ���о�����Ҫ���⡣�̳����໷������ �������� �������� �̳����࣬����˼�壬����ȷ���̳������������������ķ�չ�������̶Ȳ�����ߣ����ǶԻ���������Ҫ��Խ��Խ�ߣ����ά���ù����������������������һ����ÿ��Ҫϴ����������������һ����Ҫ���ִ��̳������У�������ÿ�ѧ�ļ����ͷ�������ι淶��������ƶ�������׼����߻��������Ĺ����������������̳������ߺ�ֵ���о��Ŀ��⡣</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;�̳��������</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;һ.��һ���й���ʽ��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �̶�����Ա1����ÿ��3-5Сʱ����ʱ�䣨�ڼ��ղ��ƣ���</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��Ϣʱ����ҵ�������繩�͡�</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �������ݰ�������ǰ������Ĩ���������Ƴ����ء���ʰ���������������䡣</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾�ṩ��������þߡ�������������������������ֽ��������������������Ʒ����</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾ÿ�¶��йܷ�����һ��ȫ�������������������������ҵ��������������һ�ɰ����շѡ�</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;�йܼ۸�˫��Э��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;"> &nbsp; &nbsp; &nbsp;��.�ڶ����й���ʽ��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �̶�����Ա1����ʼ��ȫ�츺����ҵ���������ڼ��ղ��ƣ���ÿ��8Сʱ�����ơ�</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� �������ݰ�����������ҵ�ڲ���������������</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾�ṩ�������Ʒ��������������������ֽ������ϴ�·ۣ�����������������Ʒ��</span><br style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;" /><span style="color:#747474;font-family:&#39;microsoft yahei&#39;, simsun, arial, helvetica, sans-serif;font-size:14px;line-height:30px;background-color:#ffffff;">�� ��˾ÿ�¶��йܷ�����һ��ȫ��λ����ɱ��������ҵ��������������һ�ɰ����շѡ�</span><br /></p>', '����9�㵽����9��', '50Ԫ/Сʱ', '02028654212', '����', '5466456');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(26, 43, 5, 1, '<p style="text-align:left;"><span style="color:#666666;font-family:arial, helvetica, sans-serif;font-size:13px;line-height:18px;background-color:#ffffff;">��ŵ�ҵ�ά����ȫ����֯��רҵ�������ߵ�����ά�޶���ͳһ��ѵ��ͳһ����淶��ͳ����ģʽ������ʮ���겻�Ϸ�չ׳���ڼ��������ģ���粽�����ȡ�<br style="color:#666666;font-family:arial, helvetica, sans-serif;font-size:13px;line-height:18px;background-color:#ffffff;" />���� �ر��ó�ά�����¡����ᡢ��֥��Ʒ�Ƶ��ӣ�����Ļ�ʵ硢Һ�����ӡ������ӵ���ʹ��Ƶ���������ʸߣ���Ʒ�ƶࡢ�����ά�������٣�ά���Ѷȴ���Ƹһ����ʮ���꾭�鼼ʦ����ά�ޣ���ͨ����������������ٶȡ��죬�ж�׼��ʹԭ��������Ա��������� <br style="color:#666666;font-family:arial, helvetica, sans-serif;font-size:13px;line-height:18px;background-color:#ffffff;" />����רҵά�ޱ��䡢�յ���ϴ�»�����������ҵ�����ܻ������Ի�����ά����û�ļ���Ҫ��Խ��Խ�ߣ������������̲�����רҵ������Ա������ḻ���ٶȿ췵���ʵͶԴ�������ǵ���Ӧ�֡�</span><br /></p>', 'ȫ���', '100Ԫ/��', '02025487548', '����', '5464588');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(27, 44, 2, 1, '<p>�������Ű����������޹�˾�Ǿ������й��������������׼������(ע���ʱ�Ϊ�����200��Ԫ)������๫˾,���ݱ��๫˾���Ǽ��߿���ҵ����̺��ϴ��ɳ����ϴ</p><p>����ҵ���ࡢʯ�ķ��¡��ذ������໷����ҵ������˾�����ҹ������������ܾ��̱����ˡ���׼�ǡ��Ű�̱�Ψһ���Ϸ��ĳ����ˡ�Ϊ�������������������������ˮƽ��Ա���Ļ�����ʶ��ʵʩ������һ��Ʒ��ս�Ե��ִ���ҵ��Ӫ�������˾��2006��ȫ��������ISO9001����������ϵ��֤��ISO14001���ʻ�����ϵ��֤��</p><p>��������˾�Դ���������Ϊ����ʵ������ݼ��ܱߵ����ı��ࡢ��������ڹ����и���������๫˾Ӫҵ����Ļ����������˶�ݸ��ࡢ��ɽ��ࡢ������ࡢ�ӻ�������๫˾������Ϊ�ͻ��ṩ���ø����������</p><p><br /></p><p>������ʮ�������Ű����������޹�˾ӵ��������೵8̨����Ͱ������12̨����ж���ʽ������33̨���˻���5̨�������11̨�͸�����͡��Ƚ�����ࡢ�����豸����ӵ��һ֧2380���˾���רҵ��ѵ�������ʰ��ھ�ҵ����ࡢ������顣(������ҵ���ಿԱ��������2007��ͻ��3000��)��֧���鱻��Ϊ�˸����ţ����๫˾</p>', '��һ�����幤����', '100Ԫ/��', '02021254545', '����', '875445');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(28, 45, 6, 1, '<p><span id="_baidu_bookmark_start_17" style="display:none;line-height:0px;"></span><span id="_baidu_bookmark_start_19" style="display:none;line-height:0px;"></span></p><p>ɳ���ǿ����б���Ҫ�еļҾߣ�û��ɳ���ļ�����Եÿյ�����ɳ�����ǳ��ĺÿ�����ʹ�þ��ˣ�ɳ�������һ��Ƥ���ܻ�������䡢��ɫ��ǳ�ȵ���ɳ����۵�����������Ҫ��ɳ���ָ���������Ư����Ī�ԣ�����Ҫ��ɳ�����л�Ƥ����ɳ����������ӵ��һ��Ư����Ƥ����ô��ɳ����Ƥ����Ǯ?����������������װ����С����ܵ�ɳ����Ƥ���֪ʶ��<br /></p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_d7830c4819ce768ced89LPPR6TzCIMWe.jpg?" title="ɳ����Ƥ" alt="ɳ����Ƥ" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, ΢���ź�, ����, ����, &#39;microsoft jhenghei&#39;, ����ϸ��, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">ɳ����Ƥ����Ǯ?ɳ����Ƥ�۸��б����й�ģ���Ҫ�ǰ���Ƥ�����������۸񣬱Ƚϳ����ļ۸���10Ԫ����20Ԫ/ƽ��Ӣ�ߣ�Ҳ����0.3*0.3ƽ���ף�������۸�������û�а����˹����ã�ʵ�ʻ�Ƥ�ļ۸���ܻ������۸�Ҫ��һЩ��</p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_cb89a5a290fe4212284dTsC0V2TxQ2wM.jpg?" title="ɳ����Ƥ" alt="ɳ����Ƥ" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, ΢���ź�, ����, ����, &#39;microsoft jhenghei&#39;, ����ϸ��, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">ɳ����Ƥע�����</p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, ΢���ź�, ����, ����, &#39;microsoft jhenghei&#39;, ����ϸ��, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">1����Ƥ֮ǰҪ��ѡ��ɳ��Ƥ����ɫ����ɫ���������黨������ȶ��ǳ�����ɳ��Ƥѡ��Ҫ���ݿ����������ɳ��ԭ���ķ������;��������ǱȽ�С��ɳ��������ɫ�Ƚϵ����ͽ������С���·���ɳ��Ƥ;����ͻ��Ƚϴ󣬹��߳��㣬�Ϳ���ѡ������ɫ��ɳ��Ƥ�����������ûʸС�</p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_7ef79b7ef7f7cb36e7a5mg94l0DxfSWu.jpg?" title="ɳ����Ƥ" alt="ɳ����Ƥ" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, ΢���ź�, ����, ����, &#39;microsoft jhenghei&#39;, ����ϸ��, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">2��ɳ�������Ƥ�ʣ����������ʱ�����Ҫ�ر��ע������������ڸ�����ʱ��Ҫע����ԭ����Ƥ��ɫ���첻Ҫ����ͬʱҪ�ܹ�����������ķ�����Ӧ����Ҫ����Ƥ���ú��ѿ���Ӱ�쵽���ۡ�</p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, ΢���ź�, ����, ����, &#39;microsoft jhenghei&#39;, ����ϸ��, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">3������ɳ��Ƥ��ʱ�򣬻���Ҫ���컨�塢<a href="http://mall.to8to.com/tag/men/" target="_blank" style="margin:0px;padding:0px;outline:none;cursor:pointer;color:#14bf76;text-decoration:none !important;">��</a>����<span id="_baidu_bookmark_start_15" style="display:none;line-height:0px;"></span><a href="http://www.to8to.com/baike/1215" target="_blank"><span id="_baidu_bookmark_start_13" style="display:none;line-height:0px;"></span>����</a>��<a href="http://jiaju.to8to.com/list/chuanglian/" target="_blank" style="margin:0px;padding:0px;outline:none;cursor:pointer;color:#14bf76;text-decoration:none !important;">����<span id="_baidu_bookmark_end_14" style="display:none;line-height:0px;"></span></a><span id="_baidu_bookmark_end_16" style="display:none;line-height:0px;"></span>�ȵ���ɫ�������Ӧ������Ҫ����ɫ���ϲ�࣬��Ҫԭ���ǻ�ɫ�Ŀ�����Ūһ����ɫ��ɳ��Ƥ���������Ӿ�Ч������˵�ͻ��Ե÷ǳ���ͻأ���ǳ���Υ�͡�</p><p style="text-align:center"><img onload=''if(this.width>600)makesmallpic(this,600,800);'' src="http://pic.to8to.com/attch/day_151226/20151226_9862b1cd229690d3ca1dv4TBuGWZjVz0.jpg?" title="ɳ����Ƥ" alt="ɳ����Ƥ" style="margin:auto;padding:0px;border:0px;display:block;max-width:560px;" /></p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, ΢���ź�, ����, ����, &#39;microsoft jhenghei&#39;, ����ϸ��, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">ɳ��Ƥ���˺󣬲��Ǿʹ�����������������ˣ������ճ�������Ҫ�ܹ��úñ�������Ҫ�ü������Ʒͻ��ɳ��Ƥ���Ų�����ɳ��Ƥ�ܵ��𻵡�</p><p style="padding:0px 0px 15px;text-indent:2em;color:#333333;font-family:&#39;microsoft yahei&#39;, ΢���ź�, ����, ����, &#39;microsoft jhenghei&#39;, ����ϸ��, stheiti, mingliu;font-size:14px;line-height:24px;text-align:left;margin-top:0px;margin-bottom:0px;">ɳ����Ƥ����Ǯ?ͨ�����µĽ��ܺ󣬴�Ҷ���ɳ����Ƥ�۸�Ҳ��һ������ʿ������ɳ����Ƥ���õ�Ƥ���Ͳ�ͬ�����Ҹ������������ͬɳ����Ƥ�����̼ҵ�����Ӱ�죬ɳ����Ƥ�۸�Ҳ�᲻һ��������Ҫɳ����Ƥ��ʱ����Ҫ��ȷ����Ҫ����Ƥ��ʽ�����͡�����ȣ�Ȼ������صĹ�����ԱΪ�㱨�ۡ�</p><p><br /><span id="_baidu_bookmark_end_8" style="display:none;line-height:0px;"></span><span id="_baidu_bookmark_end_6" style="display:none;line-height:0px;"></span></p><p><span id="_baidu_bookmark_end_20" style="display:none;line-height:0px;"></span><span id="_baidu_bookmark_end_18" style="display:none;line-height:0px;"></span></p>', 'ȫ���', '3000Ԫ/��', '02025454587', '����', '487846');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(29, 46, 10, 1, '<p>������ͨ��Ͱ�շѱ�׼���ٶȿ졣����רҵ�ɿ� ������ͨ��˾����ͨ��ͨ�ܵ�������ȫ��Ʊ����ȫ���ڼ��ղ���Ϣ24Сʱ��������浽</p>', 'ȫ���', '50Ԫ/��', '02021254568', '����', '43242342');
INSERT INTO `qb_fuwu_content_1` (`rid`, `id`, `fid`, `uid`, `content`, `servetime`, `moneytype`, `telphone`, `linkman`, `qq`) VALUES(30, 47, 4, 1, '<p>����ϴ�����ˣ�1-2�ܼ������ǰ����ѵ���ﱸ������������Ѱ�ҿͻ���ÿ�����ɽӵ�!����ϴ�����ˣ��ܲ��ṩ��Ʒ���ͣ�Ͷ�ߴ����ƹ㣬�����Ⱥ������񣬽�����֮��</p>', '����', '30Ԫ/��', '13654587454', '����', '7845454');



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



INSERT INTO `qb_fuwu_content_2` (`rid`, `id`, `fid`, `uid`, `content`, `realname`, `telphone`, `gototime`, `address`) VALUES(6, 42, 1, 1, '�յ���װ', '��С��', '13710936097', '���� 3:00', '������ ������ 5���� 81��');
INSERT INTO `qb_fuwu_content_2` (`rid`, `id`, `fid`, `uid`, `content`, `realname`, `telphone`, `gototime`, `address`) VALUES(4, 42, 1, 27, '��ɨ����', '������', '13456734566', '���� 4:00', '���� ������ 3���ֵ� 34��');
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



INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(88, 2, '��ע����', 'content', 'mediumtext', 0, 2, 'textarea', 500, 100, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(86, 1, '�̼ҽ���', 'content', 'mediumtext', 0, 1, 'ieedit', 700, 250, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(150, 2, '����ʱ��', 'gototime', 'varchar', 50, 10, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(144, 2, '��ϵ��', 'realname', 'varchar', 30, 9, 'text', 8, 0, '', '', '', '', 0, 1, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(145, 2, '��ϵ�绰', 'telphone', 'varchar', 20, 8, 'text', 10, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(152, 1, '��ϵ��', 'linkman', 'varchar', 20, 12, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(148, 1, '����ʱ��', 'servetime', 'varchar', 50, 9, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(149, 1, '�շѱ�׼', 'moneytype', 'varchar', 50, 10, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');
INSERT INTO `qb_fuwu_field` (`id`, `mid`, `title`, `field_name`, `field_type`, `field_leng`, `orderlist`, `form_type`, `field_inputwidth`, `field_inputheight`, `form_set`, `form_value`, `form_units`, `form_title`, `mustfill`, `listshow`, `listfilter`, `search`, `allowview`, `allowpost`, `js_check`, `js_checkmsg`, `classid`, `form_js`) VALUES(151, 1, '��ϵ�绰', 'telphone', 'varchar', 30, 11, 'text', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 31, '');



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



INSERT INTO `qb_fuwu_module` (`id`, `sort_id`, `name`, `list`, `style`, `config`, `config2`, `comment_type`, `ifdp`, `template`) VALUES(1, 0, '�̼�', 10, '', 'a:1:{s:9:"moduleSet";a:1:{s:6:"useMap";s:1:"1";}}', '', 1, 0, '');
INSERT INTO `qb_fuwu_module` (`id`, `sort_id`, `name`, `list`, `style`, `config`, `config2`, `comment_type`, `ifdp`, `template`) VALUES(2, 0, 'ԤԼ��', 4, '', 'a:1:{s:9:"moduleSet";a:1:{s:6:"useMap";s:1:"0";}}', '', 1, 0, 'a:4:{s:4:"list";s:12:"joinlist.htm";s:4:"show";s:12:"joinshow.htm";s:4:"post";s:8:"join.htm";s:6:"search";s:0:"";}');



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



INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(1, 0, '�������', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(2, 0, '�ذ����', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(3, 0, '�յ��ƻ�', 1, 1, 0, 0, '', 0, 0, '', '', '', '', 'N;', '', 0, '', '', '', 0, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(4, 0, '����ϴ��', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(5, 0, '�ҵ�ά��', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(6, 0, 'ɳ������', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(7, 0, '�ֻ�ά��', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(8, 0, '��������', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(9, 0, '��������', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_fuwu_sort` (`fid`, `fup`, `name`, `mid`, `class`, `sons`, `type`, `admin`, `list`, `listorder`, `passwd`, `logo`, `descrip`, `style`, `template`, `jumpurl`, `maxperpage`, `metatitle`, `metakeywords`, `metadescription`, `allowcomment`, `allowpost`, `allowviewtitle`, `allowviewcontent`, `allowdownload`, `forbidshow`, `config`, `index_show`, `contents`, `tableid`, `dir_name`, `ifcolor`) VALUES(10, 0, '��Ͱ��ͨ', 1, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
