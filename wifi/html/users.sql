
DROP TABLE `users`;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(20) DEFAULT NULL,
  `mphone` char(20) DEFAULT NULL,
  `dept` char(20) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `passwd` char(50) DEFAULT NULL,
  `pwchday` date DEFAULT NULL,
  `mac` char(20) DEFAULT NULL,
  `ipaddr` char(20) DEFAULT NULL,
  `model` char(100) DEFAULT NULL,
  `mname` char(20) DEFAULT NULL,
  `regdate` date DEFAULT NULL,
  `iptmp` char(20) DEFAULT NULL,
  `idate` datetime DEFAULT NULL,
  `atime` datetime DEFAULT NULL,
  `astamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1120 DEFAULT CHARSET=euckr ;


alter table users add column iptmp char(20) after regdate;
alter table users add column ipaddr char(20) after mac;



