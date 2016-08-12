#!/usr/local/bin/php
<?php

/*

create table timestamp (
  cronlast  datetime,
  cronlasti int
);
insert into timestamp set cronlast='0000-00-00 00:00:00';
update timestamp set  cronlast='0000-00-00 00:00:00';

drop table dhcplog;
create table dhcplog (
  id   int primary key not null auto_increment,
  t    datetime,
  mac  char(30),
  title  char(100),
  key(id)
);

*/

  include("/www/config/config.php");
  //print_r($conf);
  $conn = mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  mysql_select_db($conf['dbname']);

  ini_set("display_errors", "On");
  ini_set("display_startup_errors", "On");
  //error_reporting(E_ALL ^ E_NOTICE);
  error_reporting(0);


  // �α׿��� tail �� �� ������ ����, �ʹ� ������ ���, �ʹ� ��� ���
  $lines = 1000;

  // dhcpd.log ����  DHCPDISCOVER ���븸 �̾ƿ�
  $command = "tail -n $lines /var/log/dhcpd.log | grep DHCPDISCOVER";
  $ret = exec($command, $output, $retvar);
  //print("retvar=$retvar");

  $now = date("Y-m-d H:i:s");
  print("����ð�: $now\n");

  // ���������� äũ�� �ð��� ����
  $qry = "select * from timestamp";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $last = $row['cronlast'];
  if ($last == '') $last = '0000-00-00 00:00:00';
  $lastt = $last;

  // mac �ּ� ����
  $pattern = "/([0-9a-zA-Z][0-9a-zA-Z]\:..\:..\:..\:..\:[0-9a-zA-Z][0-9a-zA-Z])/";

  // ���� ������ ó��
  $n = count($output);
  for ($i = 0; $i < $n; $i++) {
    $line = $output[$i];

    // ���ּҸ� ã��
    $ret = preg_match($pattern, $line, $matches);
    //print("$ret\n");
    //print_r($matches);

    // ���ּҸ� �̿��Ͽ� �˻�
    if ($ret >= 1) {
      $mac = $matches[0];

      $qry = "select * from users where mac='$mac'";
      $ret = mysql_query($qry);
      $row = mysql_fetch_array($ret);
      $name = $row['name'];
      $dept = $row['dept'];
      $model = $row['model'];
      if ($name == '') $name = '==�̵��==';

      $str = "$name $dept $model";
    } else {
      $mac = '';
      $str = '';
    }

    //print("$line$str\n");
    //Jan 17 10:30:09 wgate dhcpd: DHCPDISCOVER from 00:17:c4:d5:1d:d5 via eth1==�̵��==
    $d = substr($line, 0, 6);
    $t = substr($line, 7, 8);
    $d = _date_format($d);
    $dt = "$d $t";
    if ($dt <= $last) continue; // �̹� ó���� ���̸� �ǳʶ�
    if ($dt > $lastt) $lastt = $dt;

    $qry = "insert into dhcplog set t='$dt', mac='$mac', title='$str'";
    //print("$qry\n");
    mysql_query($qry);
    print mysql_error();
  }


  // ���������� äũ�� �ð��� ����� ��
  $qry = "update timestamp set cronlast='$lastt'";
  //print("$qry\n");
  $ret = mysql_query($qry);


// ��¥ ���� ��ȯ
// 'Jan  7' --> '2011-01-07'
function _date_format($t) {
  $m = substr($t,0,3); 
  $d = substr($t,4,2); 
  if ($m == 'Jan')      $mm = 1;
  else if ($m == 'Feb') $mm = 2;
  else if ($m == 'Mar') $mm = 3;
  else if ($m == 'Apr') $mm = 4;
  else if ($m == 'May') $mm = 5;
  else if ($m == 'Jun') $mm = 6;
  else if ($m == 'Jul') $mm = 7;
  else if ($m == 'Aug') $mm = 8;
  else if ($m == 'Sep') $mm = 9;
  else if ($m == 'Oct') $mm = 10;
  else if ($m == 'Nov') $mm = 11;
  else if ($m == 'Dec') $mm = 12;
  $s = sprintf("2011-%02d-%02d", $mm, $d);
  return $s;
}


?>
