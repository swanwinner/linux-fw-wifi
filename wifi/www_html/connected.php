<?php

  include("common.php");


### {{{
// 스마트폰 정보보호 이용자 10대 안전수칙
function _security10() {
  print<<<EOS
<div style='text-align:left; border:2px dotted #008800; padding:5px 5px 5px 5px; margin:10px 0px 10px 0px;'>

스마트폰 정보보호 '이용자 10대 안전수칙'<br>
<ol>
<li>
 의심스러운 애플리케이션 다운로드하지 않기<br>
<li>
 신뢰할 수 없는 사이트 방문하지 않기<br>
<li>
 발신인이 불명확하거나 의심스러운 메시지 및 메일 삭제하기<br>
<li>
 비밀번호 설정 기능을 이용하고 정기적으로 비밀번호 변경하기<br>
<li>
 블루투스 기능 등 무선 인터페이스는 사용시에만 켜놓기<br>
<li>
 이상증상이 지속될 경우 악성코드 감염여부 확인하기<br>
<li>
 다운로드한 파일은 바이러스 유무를 검사한 후 사용하기<br>
<li>
 PC에도 백신프로그램을 설치하고 정기적으로 바이러스 검사하기<br>
<li>
 스마트폰 플랫폼의 구조를 임의로 변경하지 않기<br>
<li>
 운영체제 및 백신프로그램을 항상 최신 버전으로 업데이트 하기<br>

</div>
EOS;
}

// 공지사항
function _wifi_notice() {
  print<<<EOS

<div style='text-align:left; border:2px dashed #008800; padding:5px 5px 5px 5px; margin:10px 0px 10px 0px;'>
<p style='text-align:center;'>
********** 공지사항 **********<br><br>
</p>

<ol>
<li style="margin-bottom:10px;">
<u>많은 사용자가 동시에 사용할 경우</u>(특히 주일과 수요일) 접속이 원활하지 않을 수 있으니,
휴대폰의 Wi-Fi 기능을 사용하지 않을 때는 꺼주시면 감사하겠습니다.</li>

<li style="margin-bottom:10px;">
무선인터넷 접속은 주기적으로 갱신하셔야 합니다.
무선인터넷이 차단되면 웹브라우저로 네이버, 다음 등의 사이트로 접속을 시도하시면
<u>무선인터넷 접속 페이지로 자동연결</u>됩니다.</li>

<li style="margin-bottom:10px;">
<u>3개월이상 무선인터넷을 사용하지 않으면</u> 무선인터넷 등록이 삭제될 수 있습니다.</li>
</ol>

</div>
EOS;
}

// 바로가기
function _go_links() {
  print<<<EOS
<div style='margin-bottom:10px; border:2px dashed #ff0000'>
<p style='margin-bottom:10px;'>
********** 바로가기 **********
</p>

<div class='links'>
<p>
<a href='http://m.cafe.daum.net/' class='link2'>다음 카페</a><br>
<span class='address' style='display:block'>http://m.cafe.daum.net/</span>
</p>

EOS;

  print<<<EOS
</div>
</div>
EOS;
}

// 이런 문자에 포함된 URL 절대 누르지 마세요
function _no_click() {
  print<<<EOS
<img src='noclick130319.jpg' width='100%'><br>
EOS;
}

### }}}


  $time = time();

  @$alert = $_REQUEST['alert'];
  if ($alert == '1') {
    print<<<EOS
<script>
alert("무선인터넷에 접속되었습니다.\\\n지금부터 인터넷 사용이 가능합니다.");
document.location = "$self";
</script>
EOS;
    exit;
  }


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
EOS;

  // user agent 값
  $uagent = $_SERVER['HTTP_USER_AGENT'];

/*
  // user agent 값 기록을 위한 테이블
CREATE TABLE `uagent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(20) DEFAULT NULL,
  `mac` char(20) DEFAULT NULL,
  `model` char(100) DEFAULT NULL,
  `uagent` char(255) DEFAULT NULL,
  `idate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=euckr;

*/


  $mac = get_mac_addr();
  $users_row = get_user_from_mac($mac);
//print_r($users_row);

  $name = $users_row['name'];
  $dept = $users_row['dept'];
  $atime = $users_row['atime'];
  $model = $users_row['model'];

  $ip = $_SERVER['REMOTE_ADDR'];
  $now = date("Y-m-d H:i");


  // user agent 값을 기록해 둔다. (2012.7.22)
  $qry = "INSERT INTO uagent SET name='$name', mac='$mac', model='$model', uagent='$uagent', idate=NOW()";
  $ret = mysql_query($qry);

  $str = $conf['max_lasting_time_str'];
  print<<<EOS
<body>

<center>
<p style='font-size:10pt; margin:0px 0px 0px 0px;'>현재 접속 IP: $ip</p>

<p style='font-size:15pt; margin:5px 0px 5px 0px; color:red;'>무선인터넷에 접속되었습니다.</p>

<p style='font-size:14pt; margin:5px 0px 5px 0px;'>지금부터 무선인터넷을</br>사용하실 수 있습니다.</p>

EOS;


/*
  $today = date('Y-m-d');
  if ($today <= '2014-03-04') {
  print<<<EOS
<p style='font-size:15pt; margin:5px 0px 5px 0px; color:red;'>3월 4일까지 총회사무실 정전으로 인하여 scj.tv에서 초등 말씀 청취하실 수 없습니다.</p>
EOS;
  }
*/

  print<<<EOS
<p style='font-size:15pt; margin:20px 0px 10px 0px; color:green;'>
<!--
사용자: $name ($dept)<br>
-->
사용기간: $atime 부터 {$str}간<br>
단말종류: $model
<p>
EOS;

  // 공지사항
  _wifi_notice();

  // 바로가기
  _go_links();

  // 이런 문자에 포함된 URL 절대 누르지 마세요
  _no_click();

  // 스마트폰 정보보호 이용자 10대 안전수칙
  _security10();

  print<<<EOS
사용후 아래 링크를 클릭하시면<br>
접속을 종료할 수 있습니다.<br>
<br>
<span onclick="goout()" class="goout">접속 종료</span><br>

<p style='margin:5px 0px 5px 0px;'>현재시간 : $now</p>
</center>

<script>
function goout() {
  if (!confirm('접속을 종료할까요?')) return;
  document.location = "http://{$conf['internalIP']}/goout.php";
}
</script>
EOS;

  print<<<EOS
</body>
</html>
EOS;

?>
