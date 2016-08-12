
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
  `conn_count` int(11) DEFAULT '0',
  `pkts` bigint(20) DEFAULT '0',
  `bytes` bigint(20) DEFAULT '0',
  `traffic_date` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

