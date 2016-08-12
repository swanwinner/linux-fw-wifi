<?php

  include('common.php');

  // 맥주소 조회
  $mac = get_mac_addr();

  print $mac;
  $qry = "UPDATE users SET astamp=0 WHERE mac='$mac'";
  //print $qry;
  $ret = mysql_query($qry);

#   // 방화벽 규칙을 삭제
#   $command = "/www/com/surun root \"/www/com/delrule $mac\"";
#   system($command);

  // 방화벽 규칙을 재설정
  $command = "/www/com/surun root /www/com/do.sh";
  system($command);

  print<<<EOS
<script>
alert("무선인터넷 사용이 종료되었습니다.");
document.location = "http://{$conf['internalIP']}/";
</script>
EOS;


?>
