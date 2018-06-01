<?php

  include("path.php");
  include("$env[prefix]/inc/common.login.php");

  $ip = $_SERVER['REMOTE_ADDR'];

if ($mode == 'login') {

  // 비밀번호는 md5 hash 되서 넘어온다.
  $f_password = $form['pass'];

  $qry = "SELECT * FROM login WHERE username='admin'";
  $ret = db_query($qry);
  $lrow = db_fetch($ret);
  $pw = $lrow['password'];

  // $time 과 db 의 password 를 해쉬하여 비교
  //$hash = md5($pw);
  $hash = $pw;

  if ($f_password != $hash) {
    die('비밀번호가 틀렸습니다.'); exit;
  }

  $_SESSION['logined'] = true;
  header("Location: /home.php");

}


# ----------------------------------------------

  // system uptime
  $ret = exec("uptime", $output);
  //print_r($output);
  $uptime = $output[0];
  list($a,) = preg_split("/user,/", $uptime);
  list(,$a) = preg_split("/up/", $a);
  $a = preg_split("/,/", $a);
  $uptime = "$a[0] $a[1]";


  print<<<EOS
<html>
<head>

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">

<title>Wi-Fi 관리</title>
<style type='text/css'>
body {  font-size: 12px; font-family: 굴림,돋움,verdana;
  font-style: normal; line-height: 12pt;
  text-decoration: none; color: #333333;
}
table,td,th { font-size: 12px; font-family: 돋움,verdana; white-space: nowrap; }
</style>

<script src='/js/script.md5.js' type='text/javascript'></script>

</head>
<body>

<center>

<form action='$self' method='post' name='form'>
무선인터넷 관리<br>
로그인:<input type='password' name='pass' size='20' onkeypress='keypress_text()'>
<input type='hidden' name='mode' value='login'>
<input type='button' value='확인' style='width:60;height:25;' onclick="sf_1()">
</form>
접속IP: $_SERVER[REMOTE_ADDR]<br>
UP TIME: $uptime<br>
</center>

<script>
function keypress_text() {
  if (event.keyCode != 13) return;
  sf_1();
}

function sf_1() {
  var form = document.form;
  // password hash
  form.pass.value = MD5(form.pass.value);

  form.submit();
}

function _onload() {
  document.form.pass.focus();
}

if (window.addEventListener) {
  window.addEventListener("load", _onload, false);
} else if (document.attachEvent) {
  window.attachEvent("onload", _onload);
}

</script>

</body>
</html>
EOS;
  exit;


?>
