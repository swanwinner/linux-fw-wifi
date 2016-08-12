<?php

  include("$env[prefix]/config/config.php");
  include("$env[prefix]/inc/func.php");

  # connect MySQL database
  $connect = @mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  if (!$connect) { die('database connection error'); exit; }
  $ret = mysql_select_db($conf['dbname'], $connect);
  if (!$ret) { die('database connection error'); exit; }

  session_start();

  $form = $_REQUEST;
  @$mode = $form['mode'];
  $self = $_SERVER['PHP_SELF'];

  date_default_timezone_set('Asia/Seoul');

  $server_name = $_SERVER['SERVER_NAME'];
  $remote_addr = $_SERVER['REMOTE_ADDR'];

  # 브라우져에서 호출된 php 파일의 url
  $env['self'] = $_SERVER['SCRIPT_NAME'];

?>
