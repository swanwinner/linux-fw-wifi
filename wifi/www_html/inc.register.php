<?php

  $ua = $_SERVER['HTTP_USER_AGENT'];

  // 네이버 어플에서 깨짐
  if (preg_match("/NAVER/", $ua)) $naver = true; else $naver = false;

  print<<<EOS
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />

</head>
<body>
EOS;

  if ($naver) {
    // 네이버 어플에서 가려져서 안보임
    print<<<EOS
<div style='height:160px; border:1px solid white;'>
</div>
EOS;
  }

  print<<<EOS
<div style='text-align:center;'>
<table border='0' align='center'>
<tr>
<td>
무선인터넷 사용자로 등록이 되어 있지 않습니다.<br>
</td>
</tr>
<tr>
<td align='center' style='font-family:verdana; font-size:20pt;'>IP $ip</td>
</tr>
<tr>
<td align='center' style='font-family:verdana; font-size:20pt;'>MAC $mac</td>
</tr>
</table>
</div>

<div style='text-align:center; font-size:20pt; background-color:#ff0;'>
<a href="javascript:window.location.reload()">이 페이지 새로고침</a>
</div>

</body>
</html>
EOS;

?>
