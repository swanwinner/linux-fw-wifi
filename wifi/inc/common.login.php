<?php


  include("$env[prefix]/config/config.php");
  include("$env[prefix]/inc/func.php");

  $sh = $_SERVER['HTTP_HOST'];
  if (preg_match("/matthias.or.kr/", $sh)) {
    $sh = preg_replace("/matthias.or.kr/", "scjmatthias.net", $sh);
    header("Location: http://$sh");
    exit;
  }

  # MySQL 데이터 베이스 접속
  $connect = @mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  if (!$connect) { die('데이터베이스 접속 에러'); exit; }
  $ret = mysql_select_db($conf['dbname'], $connect);
  if (!$ret) { die('데이터베이스 접속 에러'); exit; }

  session_start();

  $form = $_REQUEST;
  @$mode = $form['mode'];
  $self = $_SERVER['PHP_SELF'];

  date_default_timezone_set('Asia/Seoul');

  $server_name = $_SERVER['SERVER_NAME'];
  $remote_addr = $_SERVER['REMOTE_ADDR'];

# print("<pre>");
# print_r($_SERVER);
# print_r($info);
# print("</pre>");

  # 브라우져에서 호출된 php 파일의 url
  $env['self'] = $_SERVER['SCRIPT_NAME'];

?>
