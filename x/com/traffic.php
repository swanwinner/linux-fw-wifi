#!/usr/local/bin/php
<?php

  include("/www/config/config.php");

  $conn = mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  mysql_select_db($conf['dbname']);


  // 방화벽 규칙 보기
  $command = "/www/com/surun root \"/sbin/iptables -t nat -vn -L PREROUTING --line-numbers\"";
  exec($command, $output, $ret_var);
  //print_r($output);

  //print("<pre>");
  //passthru($command);
  //print("</pre>");

  $sum_pkts = 0;
  $sum_bytes = 0;

  foreach ($output as $line) {
    //print("$line \n");
    $arr = preg_split("/ +/", $line);
    //print_r($arr);

    @$pkts  = $arr[1];
    @$bytes = $arr[2];
    @$mac   = $arr[11];

    $len = strlen($mac);
    if ($len != 17) continue;

    if (!$pkts) continue;

    $sum_pkts += $pkts;
    $sum_bytes += $bytes;

    $qry = "UPDATE users SET pkts=pkts+$pkts, bytes=bytes+$bytes, traffic_date=NOW() WHERE mac='$mac'";
    //print("$qry \n");

    $ret = mysql_query($qry);
    print mysql_error();

    //print("$pkts $bytes $mac \n");
  }

  $qry = "INSERT INTO traffic SET pkts='$sum_pkts',bytes='$sum_bytes',idate=now()";
  $ret = mysql_query($qry);

?>
