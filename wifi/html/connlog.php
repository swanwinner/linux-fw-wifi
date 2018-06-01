<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  $ip = $_SERVER['REMOTE_ADDR'];


// 로그인 상태가 아직 아님
@$logined = $_SESSION['logined'];
if (!$logined) {
  die('정상적인 접근이 아닙니다.');
  exit;
}



  PageHead('최근접속 로그');

  $qry = "select year(idate) as year, month(idate) as month, count(*) as count
from connlog group by year(idate), month(idate) ";
  $ret = db_query($qry);

  print<<<EOS
<table border='1'>
EOS;
  while ($row = db_fetch($ret)) {
    print<<<EOS
<tr>
<td>$row[year]</td>
<td>$row[month]</td>
<td>$row[count]</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  exit;

  print<<<EOS
SELECT * FROM connlog order by idate desc<br>
최대 1000개<br>
EOS;

  $qry = "SELECT * FROM connlog"
    ." ORDER BY idate DESC"
    ." LIMIT 0,1000 ";
  $ret = db_query($qry);

  while ($row = db_fetch($ret)) {
    //print_r($row);
    print<<<EOS
id={$row['id']} <br>
uid={$row['uid']} <br>
mac={$row['mac']} <br>
ipaddr={$row['ipaddr']} <br>
uagent={$row['uagent']} <br>
idate={$row['idate']} <br>
<br>
EOS;
  }

  PageTail();
  exit

?>
