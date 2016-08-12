
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(200) DEFAULT NULL,
  `chulsi` char(100) DEFAULT NULL,
  `url` char(200) DEFAULT NULL,
  `ord` int(11) DEFAULT NULL,
  `idate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

insert into devices (id, title) values (1, '삼성갤럭시');
