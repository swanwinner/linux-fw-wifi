#!/usr/local/bin/php
<?php

  $dhcp_leases_file = '/var/lib/dhcpd/dhcpd.leases';

  // # 로시작되는 주석 제거
  $tmpfile = "/tmp/tmpfile";
  $cmd = "grep -v '^#' $dhcp_leases_file > $tmpfile";
  system($cmd);

?>
