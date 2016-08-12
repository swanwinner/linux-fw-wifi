<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");


  PageHead();
  //AdminHeadline();

  print<<<EOS
/var/log/dhcpd.log 로그파일에서 DHCPDISCOVER 로그만
 cron 프로그램으로 5분간격으로 dhcplog 테이블에 넣음<br>
EOS;

  $qry = "select count(*) as count from dhcplog";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $total = $row['count'];

  $qry = "select count(*) as count from dhcplog where t > subdate(now(), interval 60 minute)";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $min = $row['count'];

  print("총 $total, 최근 60분 $min");
  

  $qry = "select * from dhcplog order by t desc limit 0, 100";
  $ret = mysql_query($qry);

  print<<<EOS
<table border='1' class='main'>
<tr>
<th>#</th>
<th>ID</th>
<th>Time</th>
<th>MAC</th>
<th>User</th>
</tr>
EOS;

  $cnt = 0;
  while ($row = mysql_fetch_array($ret)) {
    $cnt++;
    //print_r($row);
    print<<<EOS
<tr>
<td>{$cnt}</td>
<td>{$row['id']}</td>
<td>{$row['t']}</td>
<td>{$row['mac']}</td>
<td>{$row['title']}</td>
</tr>
EOS;
  }

  print<<<EOS
</table>
EOS;

  PageTail();

?>
