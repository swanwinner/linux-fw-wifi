<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  $ip = $_SERVER['REMOTE_ADDR'];


#{{{ modes ##############################################
#}}} modes ##############################################


// 로그인 상태가 아직 아님
@$logined = $_SESSION['logined'];
if (!$logined) {
  die('정상적인 접근이 아닙니다.');
  exit;
}



  PageHead('오래동안 접속하지 않은 단말기');


function get_count($min, $max) {
  $sql_where = " WHERE atime<=DATE_SUB(NOW(),INTERVAL $min DAY)";
  $sql_where .= " AND atime>DATE_SUB(NOW(),INTERVAL $max DAY)";

  $qry = "SELECT COUNT(*) AS total FROM users $sql_where";
  //print $qry;
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $total = $row['total'];
  return $total;
}

  $c1 = get_count('0', '7');
  $c2 = get_count('8', '14');
  $c3 = get_count('15', '21');
  $c4 = get_count('22', '30');
  $c5 = get_count('31', '60');
  $c6 = get_count('61', '90');
  $c7 = get_count('91', '120');
  $c8 = get_count('121', '150');
  $c9 = get_count('151', '180');
  $c10 = get_count('180', '500');

  print<<<EOS
마지막 접속일<br>
0-7일: $c1<br>
8-14일:$c2<br>
15-21일: $c3<br>
22-30일: $c4<br>
31-60일: $c5<br>
61-90일: $c6<br>
91-120일: $c7<br>
121-150일:$c8<br>
151-180일:$c9<br>
180일 이상:$c10<br>
<br>
EOS;
exit;

  PageTail();
  exit

?>
