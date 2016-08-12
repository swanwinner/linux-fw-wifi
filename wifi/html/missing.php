<?php

  mysql_connect('localhost', 'root', 'xxxxxxxxx');
  mysql_select_db('wgate');

  $qs = $_SERVER['QUERY_STRING'];
  $ip = $_SERVER['REMOTE_ADDR'];
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $ru = $_SERVER['REQUEST_URI'];

# print("<pre>");
# print_r($_SERVER);
# print("</pre>");
# $uahash = md5($ua);

/* =================================================================

drop table missing;

CREATE TABLE `missing` (

  `id` int(11) NOT NULL AUTO_INCREMENT,

  `sv` char(40) DEFAULT NULL,
  `qs` char(255) DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  `ua` char(255) DEFAULT NULL,
  `ru` char(255) DEFAULT NULL,
  `hash` char(32) DEFAULT NULL,
   ts  int,
   td  date,

  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `uid` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=euckr;


select * from missing;
select id,sv,ip,hash,ts,td from missing;



================================================================= */


  $now = time();
  $today = date("Ymd");

  $str = $sv.$ru.$qs.$ua.$today.$now;
  $hash = md5($str);


  $qry = "SELECT * FROM missing WHERE hash='$hash'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  if (!$row) {

    $qry2 = "INSERT INTO missing SET sv='$sv',qs='$qs',ip='$ip',ua='$ua',ru='$ru',hash='$hash',ts='$now',td='$today'";
    //print $qry2;
    $ret2 = mysql_query($qry2);
    //print mysql_error();

  }


  print<<<EOS
