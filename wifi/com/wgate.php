#!/usr/local/bin/php
<?php

  include("/www/config/config.php");

  $conn = mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  mysql_select_db($conf['dbname']);

  // ���� ���� �� �ð��� ����
  $time = $conf['max_lasting_time'];

  // mac.sh �� ���ÿ� ���� �� �ִ� ����� ���� ����
  $max = $conf['max_concurrent_users'];


  $qry = "SELECT * FROM users"
        ." WHERE TIMESTAMPDIFF(SECOND, atime, NOW())<'$time'"
        ." ORDER BY astamp DESC LIMIT 0,$max"
        ;

  $ret = mysql_query($qry);

  while ($row = mysql_fetch_assoc($ret)) {
    $mac = $row['mac'];
    $name = $row['name'];

    if ($mac == '') continue;

    print<<<EOS
/sbin/iptables -t nat -A PREROUTING -i $conf[internalNIC] -m mac --mac-source $mac -j ACCEPT 

EOS;
  }


?>
