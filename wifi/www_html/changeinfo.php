<?php

exit;

  include("common.php");

  $self = 'changeinfo.php';



### {{{
function _head() {
  print<<<EOS
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=1.5, minimum-scale=0.5, user-scalable=yes, target-densitydpi=medium-dpi" />

<title>Matthias Wi-Fi Zone</title>

<style>
div.links p a  { font-size:18pt; text-decoration:none; color:#0000bb; background-color:#eeeeff; }
div.links p a.link2  { font-size:25pt; text-decoration:none; color:#0000bb; background-color:#eeeeff; }

span.address { font-size:12pt; }
span.address2 { font-size:15pt; }
span.goout { text-decoration:underline; font-size:large; }
</style>

</head>
<body>
EOS;
}

function _tail() {
  print<<<EOS
</body>
</html>
EOS;
}

// 디버그 함수
function dd($msg) {
       if (is_string($msg)) print($msg);
  else if (is_array($msg)) { print("<pre>"); print_r($msg); print("</pre>"); }
  else print_r($msg);
}


function option_model($preset='') {
  $qry2 = "SELECT * FROM devices ORDER BY title";
  $ret2 = mysql_query($qry2);
  $list = array();
  while ($row2 = mysql_fetch_array($ret2)) {
    $title = $row2['title'];
    $list[] = $title;
  }
  //print_r($list);

  $len = count($list);

  $model = preg_replace("/ /", "", $row['model']);
  $preset = $model;

  $opt = "<option value=''>==선택==</option>";
  for ($i = 0; $i < $len; $i++) {
    $val = $list[$i];
    if ($val == $preset) $sel = ' selected'; else $sel = '';
    $opt .=<<<EOS
<option value="$val"$sel>$val</option>
EOS;
  }
  return $opt;
}




### }}}

if ($mode == 's2') {

  $name = $form['name'];
  $phone = $form['phone'];

  _head();

  $qry = "SELECT * FROM users WHERE name='$name' AND mphone='$phone'";
  $ret = mysql_query($qry);

  $n = mysql_affected_rows();
  if ($n == 0) die('기존 등록된 정보를 찾을 수 없습니다.');

  print<<<EOS
기존 등록된 휴대폰 정보가 맞습니까?<br>
<br>
EOS;

  $row = mysql_fetch_assoc($ret);

  //dd($row);
  $mac = $row['mac'];
  $uid = $row['uid'];
  print<<<EOS
이름: {$row['name']}<br>
부서: {$row['dept']}<br>
연락처: {$row['mphone']}<br>
종류: {$row['model']}<br>
등록일: {$row['regdate']}<br>
EOS;

  print<<<EOS
<br>
기존 등록된 휴대폰 정보가 맞습니까?<br>
맞으면 아래 링크를 터치하세요.<br>
<br>
<a href='$self?mode=s3&uid=$uid&mac=$mac'>예 맞습니다</a>
EOS;

  _tail();
  exit;
}

if ($mode == 's3') {
  _head();
  //dd($form);

  $uid = $form['uid'];
  $mac = $form['mac'];

  $qry = "SELECT * FROM users WHERE uid='$uid' AND mac='$mac'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_assoc($ret);
  if (!$row) die('error');

  //dd($row);

  print<<<EOS
변경된 정보를 입력하세요.

<table border='1'>
<form name='form2' action='$self' method='post'>
<tr>
 <td>이름</td>
 <td><input type='text' name='name' size='20' value="{$row['name']}"></td>
</tr>

<tr>
 <td>소속부서</td>
 <td><input type='text' name='dept' size='20' value="{$row['dept']}">
<div>
ex) 장년회 대흥, 부녀회 대흥1, 청년회 대학1
</div>
</td>
</tr>
EOS;

  $preset = $row['model'];
  $opt = option_model($preset);
  print<<<EOS
<tr>
 <td>휴대단말기종류</td>
 <td>
  <select name='model'>$opt</select>
 </td>
</tr>

<tr>
 <td>휴대폰번호</td>
 <td><input type='text' name='phone' size='20' value="{$row['mphone']}"></td>
</tr>

<tr>
 <td colspan='2' align='center'>
   <input type='hidden' name='uid' value='$uid'>
   <input type='hidden' name='mode' value='s4'>
   <input type='button' value='확인' onclick="sf_2()">
 </td>
</tr>

</form>
</table>

<script>
function sf_2() {
  document.form2.submit();
}
</script>
EOS;

  _tail();
  exit;
}

if ($mode == 's4') {
  //dd($form);

  $uid = $form['uid'];
  $name = $form['name'];
  $dept = $form['dept'];
  $model = $form['model'];
  $phone = $form['phone'];

  $mac = get_mac_addr();

  $qry = "UPDATE users SET name='$name', mphone='$phone', dept='$dept', model='$model',mac='$mac' WHERE uid='$uid'";
  //dd($qry);

  $ret = mysql_query($qry);
  print mysql_error();

  _head();
  print<<<EOS
변경되었습니다.
<br>
<a href='/'>무선인터넷 시작하기</a>
EOS;
  _tail();

  exit;
}



  $ip = $_SERVER['REMOTE_ADDR'];
  $now = date("Y-m-d H:i");
  $mac = get_mac_addr();

  _head();
  print<<<EOS
<div style='text-align:center;'>
<p>현재 접속 IP: $ip</p>
<p>MAC: $mac</p>
</div>

기존 등록된 정보의 이름과 휴대폰 번호(-포함) 를 입력하세요.

<table border='1'>
<form name='form1' action='$self' method='post'>
<tr>
 <td>이름</td>
 <td><input type='text' name='name' size='20'></td>
</tr>

<tr>
 <td>휴대폰번호</td>
 <td><input type='text' name='phone' size='20'>
<br>ex) 010-1234-1234
</td>
</tr>

<tr>
 <td colspan='2' align='center'>
   <input type='hidden' name='mode' value='s2'>
   <input type='button' value='확인' onclick="sf_1()">
 </td>
</tr>

</form>
</table>

<script>
function sf_1() {
  document.form1.submit();
}
</script>
EOS;
  _tail();


?>
