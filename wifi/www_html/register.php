<?php

  include('common.php');

  //print_r($form);

  $name = $form['name'];
  $phone = $form['phone'];
  $goyu = $form['goyu'];
  $dept = $form['dept'];
  $mode = $form['mode'];

  // 맥주소 조회
  $mac = get_mac_addr();
  if ($mac == '') die('맥주소 조회 오류');


  // 등록된 맥주소인지 확인
/*  $qry = "SELECT * FROM users WHERE mac='$mac'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);  
  if ($row) die('이미 등록된 mac 주소입니다.');
*/

  //if ($mode != 'register') iError('mode error');
  //$qry = "INSERT INTO users SET name='$name', mphone='$phone', userno='$goyu', dept='$dept', mac='$mac', regdate=now(), idate=now()";

  $ip = $_SERVER['REMOTE_ADDR'];
  $qry = "INSERT INTO users SET mac='$mac', ipaddr='$ip', regdate=now(), idate=now()";

  $ret = mysql_query($qry);

  print<<<EOS
$mac 등록 완료<br>
<br>
<a href="javascript:_reload()">새로고침</a>
<script>
function _reload() {
  document.location.reload();
}
//alert('등록이 완료되었습니다.');
document.location.href = "index.php";
</script>
EOS;

  exit;


?>
