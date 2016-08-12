<?php

  include("func.php");

  include("/www/config/config.php");
  //print_r($conf);
  $conn = mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  mysql_select_db($conf['dbname']);

  $form = $_REQUEST;
  @$mode = $form['mode'];
  $self = $_SERVER['PHP_SELF'];

  date_default_timezone_set('Asia/Seoul');

  session_start();

/*
  print<<<EOS
<html>
EOS;
  $info = get_browser(null, true);
*/

  $server_name = $_SERVER['SERVER_NAME'];
  $remote_addr = $_SERVER['REMOTE_ADDR'];

# print("<pre>");
# print_r($_SERVER);
# print_r($info);
# print("</pre>");

?>
