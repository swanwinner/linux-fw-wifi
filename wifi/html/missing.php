<?php

  mysql_connect('localhost', 'root', 'xxxxxxxxx');
  mysql_select_db('wgate');

  $qs = $_SERVER['QUERY_STRING'];
  $ip = $_SERVER['REMOTE_ADDR'];
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $ru = $_SERVER['REQUEST_URI'];

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
