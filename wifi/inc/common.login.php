<?php


  include("$env[prefix]/config/config.php");
  include("$env[prefix]/inc/func.php");

  db_connect();

  session_start();

  $form = $_REQUEST;
  @$mode = $form['mode'];
  $self = $_SERVER['PHP_SELF'];

  date_default_timezone_set('Asia/Seoul');

  $server_name = $_SERVER['SERVER_NAME'];
  $remote_addr = $_SERVER['REMOTE_ADDR'];

  $env['self'] = $_SERVER['SCRIPT_NAME'];

?>
