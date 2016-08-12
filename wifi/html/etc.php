<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");


  $ip = $_SERVER['REMOTE_ADDR'];


  PageHead('기타');
  //AdminHeadline();
  //Menu();

  print<<<EOS
<ul>
<li> <a href='/accesspoint.php'>공유기 접속(내부망)</a>
<li> <a href="/home.php?mode=showrule">MAC 허용 규칙보기</a>
<li> <a href="/dhcplist.php">DHCP 할당 개수</a>
<li> <a href="/dhcplog.php">DHCPD 로그</a>
<li> <a href="/dhcplog2.php">DHCPD 로그2</a>
<li> <a href="/wifizone.zip">무선인터넷 장소 스티커</a>
<li> <a href="/connected.php">접속완료 화면</a>
<li> <a href="/notused.php">오래동안 접속하지 않은 단말</a>
<li> <a href="/distrib.php">최근 접속시간 분포</a>
<li> <a href="/connlog.php">최근 접속 로그</a>
<li> <a href="/ping.php">공유기 ping</a>
<li> <a href="/macdist.php">맥주소분포</a>
<li> <a href="/iftop.php">iftop</a>

</ul>
EOS;

  PageTail();
  exit

?>
