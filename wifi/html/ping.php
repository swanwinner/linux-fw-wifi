<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  PageHead();
  //AdminHeadline();

  $lines = 100;

  // DHCP 할당 리스트
  //$command = "tail -n 100 /var/log/dhcpd.log";
  $command = "/www/com/surun root \"/root/ping.sh\"";
  $ret = exec($command, $output, $retvar);
  //print("retvar=$retvar");

  print("<pre>");
  passthru($command);
  print("</pre>");

  PageTail();

?>
