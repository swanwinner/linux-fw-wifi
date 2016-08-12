<?php

  include('common.php');


  $ip = $_SERVER['REMOTE_ADDR'];
# print $server_name."<br>";
# print $ip."<br>";
# exit;

  // missing.php 에서 "http://internalIP/" 으로
  // 리다이렉트되므로 항상 index.php 가 호출된다.

  // 등록대기중인 IP인지 확인
  $qry = "SELECT * FROM users WHERE iptmp='$ip'";
#print $qry;
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);  
  if ($row) {
    // 맥주소 조회
    $mac = get_mac_addr();
    if ($mac == '') die('맥주소 조회 오류');

    $qry = "UPDATE users SET mac='$mac' WHERE iptmp='$ip'";
    $ret = mysql_query($qry);
    $qry = "UPDATE users SET iptmp='' WHERE iptmp='$ip'";
    $ret = mysql_query($qry);

    print<<<EOS
$mac 등록 완료<br>

<br>
<br>
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
  }



  // 맥주소 조회
  $mac = get_mac_addr();
  //print $mac;
  if ($mac == '') {
    print<<<EOS
<h1>MAC주소 조회 실패</h1>
EOS;
    exit;
  }


  // 등록된 맥주소인지 확인
  $qry = "SELECT * FROM users WHERE mac='$mac'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);  
  //print_r($row);

  if (!$row) { // 맥주소가 등록되어 있지 않음
    $ip = $_SERVER['REMOTE_ADDR'];
    include("inc.register.php");

    // 사용자가 직접 등록함
    //include("inc.register_self.php");

    exit;
  }

  $users_row = $row;
  $uid = $users_row['uid'];
//print $uid;

/*

// 접속 기록을 남기기 위한 로그 테이블
CREATE TABLE `connlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  uid int, # users' id #
  `mac` char(20) DEFAULT NULL,
  `ipaddr` char(20) DEFAULT NULL,
  uagent  char(255),
  `idate` datetime DEFAULT NULL,
  `idate_t` int,
  PRIMARY KEY (`id`),
  KEY `uid` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=euckr;

*/

  // 접속을 위한 링크를 클릭했을 때
  @$connect = $_REQUEST['connect'];
  if ($connect == '1') {

    // 마지막 접속시각 atime,astamp 을 현재시간으로 업데이트
    $time = time();
    $qry = "UPDATE users SET atime=NOW(),astamp='$time',ipaddr='$ip',conn_count=conn_count+1 WHERE mac='$mac'";
    $ret = mysql_query($qry);

    // 접속 기록을 로그테이블에 남긴다.
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    $qry = "INSERT INTO connlog SET uid='$uid'"
       .", mac='$mac'"
       .", ipaddr='$ip'"
       .", uagent='$uagent'"
       .", idate=NOW(),idate_t='$time'";
    $ret = mysql_query($qry);

#   // 방화벽 규칙을 추가
#   $command = "/www/com/surun root \"/www/com/addrule $mac\"";
#   system($command);

    // 방화벽 규칙을 재설정
    $command = "/www/com/surun root /www/com/do.sh";
    system($command);

    // 접속되었다는 화면
  print<<<EOS
<script>
document.location = "http://{$conf['internalIP']}/connected.php?alert=1";
</script>
EOS;
    exit;
  }

  // 접속을 위한 링크
  include("inc.connect.php");
  exit;

?>
