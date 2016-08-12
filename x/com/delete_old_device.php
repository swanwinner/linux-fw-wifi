#!/usr/local/bin/php
<?php


function _log($msg) {
  $now = date("Y-m-d H:i:s");
  print("$now : $msg\n");
}

  include("/www/config/config.php");
  //print_r($conf);
  $conn = mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  mysql_select_db($conf['dbname']);

  ini_set("display_errors", "On");
  ini_set("display_startup_errors", "On");
  //error_reporting(E_ALL ^ E_NOTICE);
  error_reporting(0);


  $qry = "SELECT COUNT(*) AS total FROM users WHERE atime<=DATE_SUB(NOW(),INTERVAL 180 DAY)";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $total = $row['total'];

  _log("180 일간 접속하지 않은 단말개수 $total 개 삭제");

  print("이름/휴대폰/부서/맥/모델/등록일/마지막접근\n");

  $qry = "DELETE FROM users WHERE atime<=DATE_SUB(NOW(),INTERVAL 180 DAY)";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);

  _log(" $total 개 삭제 완료");

  print_r($row);

?>
