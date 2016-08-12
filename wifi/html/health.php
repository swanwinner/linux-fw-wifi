<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");


  $ip = $_SERVER['REMOTE_ADDR'];


  PageHead('Health');
  //AdminHeadline();
  //Menu();

  print<<<EOS
<a href='/health/' target='_blank'>System Health Monitor</a>
<br>

<style>
table.main { border:0px solid red; border-collapse:collapse; }
table.main th, table.main td { border:1px solid #999; padding:2px 5px 2px 5px; }
table.main th { background-color:#eeeeee; font-weight:bold; text-align:center; }
table.main td.c { text-align:center; }
table.main td.r { text-align:right; }
</style>
EOS;


  print<<<EOS
<table border='0'>
<tr>
<td>

<table class='main'>
<tr><td>eth0 (외부)<br></tr>
<tr><td><img src='/health/eth0-3hours.png'></td></tr>
<tr><td><img src='/health/eth0-32hours.png'></td></tr>
<tr><td><img src='/health/eth0-8days.png'></td></tr>
</table>

</td><td>

<table class='main'>
<tr><td>eth1 (내부)<br></tr>
<tr><td><img src='/health/eth1-3hours.png'></td></tr>
<tr><td><img src='/health/eth1-32hours.png'></td></tr>
<tr><td><img src='/health/eth1-8days.png'></td></tr>
</table>

</td>
</tr><tr>
<td>

<table class='main'>
<tr><td>loadavg<br></tr>
<tr><td><img src='/health/loadavg-3hours.png'></td></tr>
<tr><td><img src='/health/loadavg-32hours.png'></td></tr>
<tr><td><img src='/health/loadavg-8days.png'></td></tr>
</table>

</td><td>

<table class='main'>
<tr><td>uptime<br></tr>
<tr><td><img src='/health/uptime-3hours.png'></td></tr>
<tr><td><img src='/health/uptime-32hours.png'></td></tr>
<tr><td><img src='/health/uptime-8days.png'></td></tr>
</table>


</td></tr></table>
EOS;


  PageTail();
  exit

?>
