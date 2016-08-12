<?php

  $pstr = $conf['max_lasting_time_str'];
  print<<<EOS
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />

<style>
a.conne:link    { text-decoration:none; }
a.conne:visited { text-decoration:none; }
a.conne:active  { text-decoration:none; }
a.conne:focus   { text-decoration:none; }
a.conne:hover   { text-decoration:none; }
a.conne { display:inline-block; width:250px; height:30px;
 background-color:#ccffcc; padding:9 9 9 9px; font-size:15pt;
 font-weight:bold; color:#c90000; }
</style>

</head>
<body>

<div style='width:100%; text-align:center; font-size:10pt'>
무선인터넷 사용을 시작하시려면,<br>
아래 버튼을 클릭하세요.<br>
</div>

<div style="text-align:center; margin:9 9 9 9px; font-size:10pt;">
<a href='index.php?connect=1' class='conne' style=''>무선인터넷시작</a>
</div>

<div style='color:#999999; size:9pt; text-align:center;'>
무선인터넷 접속 비번은 <span style='color:red; font-weight:bold;'>1234567890</span> 입니다.<br>
SSID: <span style="color:red; font-weight:bold;">AP-WPA, AP-WEP64, AP-WPA-5G</span><br>
</p>
</div>

<div style="text-align:center;">
<img src='wc2.jpg' width='50%'><br>
</div>

<div style="text-align:center; margin:9 9 9 9px;">
<a href='index.php?connect=1' class='conne' style=''>무선인터넷시작</a>
</div>

<div style="text-align:center; margin:9 9 9 9px;">
성전내 무선인터넷 안테나 설치된 곳:<br>
1층: 안네데스크, 초등관 복도<br>
2층: 교육관 복도<br>
3층: 소성전, 하늘도서관<br>
4층: 대성전 뒤쪽<br>
5층: 교육관<br>
지하: 지하주차장<br>
<br>
정보통신부에서 휴대폰을 등록하신 후 무선인터넷을 사용하실 수 있습니다.<br>
** 노트북은 무선인터넷 등록이 되지 않습니다. (휴대폰과 테블릿만 가능) **
</div>

<div style="text-align:center; margin:9 9 9 9px;">
<br>
<br>
<p style='color:#999999;'>
위 버튼을 클릭한 이후<br>
<u>{$pstr} 동안</u> 인터넷 사용이 가능합니다.<br>
{$pstr} 이후에 다시 위 버튼을 클릭해 주시면 계속해서 사용이 가능합니다.<br>
</p>

<p style='color:#aa9999;'>
사용시 불편사항은 <u>정보통신부</u>로 문의 바랍니다.<br>
</p>

</div>

</body>
</html>
EOS;

?>
