<?php

  # database connection
  $conf['dbhost']   = "localhost:/tmp/mysql.sock";

  $conf['dbuser']   = "DB사용자";
  $conf['dbpasswd'] = "암호";
  $conf['dbname']   = "wifi";

  $conf['externalNIC'] = 'eth0';
  $conf['internalNIC'] = 'eth1';
  $conf['internalIP'] = '192.168.0.1';

  $conf['site_title'] = 'WiFi';

  // 연결 지속 시간
  $conf['max_lasting_time'] = 3600*24*1; // 1일
  $conf['max_lasting_time_str'] = '1일';

  // 동시 접속 가능한 사용자 수
  $conf['max_concurrent_users'] = 400;

?>
