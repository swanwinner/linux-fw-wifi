<?php


  include("$env[prefix]/config/config.php");
  include("$env[prefix]/inc/func.php");

  db_connect();

  session_start();

  if (!$_SESSION['logined']) {
    header("Location: /index.php");
    //Redirect("/index.php");
    exit;
  }

  $form = $_REQUEST;
  @$mode = $form['mode'];
  $self = $_SERVER['PHP_SELF'];

  date_default_timezone_set('Asia/Seoul');

  $server_name = $_SERVER['SERVER_NAME'];
  $remote_addr = $_SERVER['REMOTE_ADDR'];

  # 브라우져에서 호출된 php 파일의 url
  $env['self'] = $_SERVER['SCRIPT_NAME'];

# print("<pre>");
# print_r($_SERVER);
# print_r($info);
# print("</pre>");

?>
