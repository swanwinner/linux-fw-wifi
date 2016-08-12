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



  PageHead('최근접속 로그');

  print<<<EOS
<pre>
mysql> desc connlog ;
+---------+-----------+------+-----+---------+----------------+
| Field   | Type      | Null | Key | Default | Extra          |
+---------+-----------+------+-----+---------+----------------+
| id      | int(11)   | NO   | PRI | NULL    | auto_increment |
| uid     | int(11)   | YES  |     | NULL    |                |
| mac     | char(20)  | YES  |     | NULL    |                |
| ipaddr  | char(20)  | YES  |     | NULL    |                |
| uagent  | char(255) | YES  |     | NULL    |                |
| idate   | datetime  | YES  |     | NULL    |                |
| idate_t | int(11)   | YES  |     | NULL    |                |
+---------+-----------+------+-----+---------+----------------+
7 rows in set (0.00 sec)

select year(idate), month(idate), count(*) from connlog group by year(idate), month(idate);

</pre>
EOS;

  $qry = "select year(idate) as year, month(idate) as month, count(*) as count
from connlog group by year(idate), month(idate) ";
  $ret = mysql_query($qry);

  print<<<EOS
<table border='1'>
EOS;
  while ($row = mysql_fetch_array($ret)) {
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
  $ret = mysql_query($qry);

  while ($row = mysql_fetch_array($ret)) {
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
