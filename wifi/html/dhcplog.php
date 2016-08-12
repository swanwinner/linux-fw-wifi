<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  PageHead();
  //AdminHeadline();

  $lines = 100;

  // DHCP 할당 리스트
  //$command = "tail -n 100 /var/log/dhcpd.log";
  $command = "/www/com/surun root \"tail -n $lines /var/log/dhcpd.log\"";
  $ret = exec($command, $output, $retvar);
  //print("retvar=$retvar");

# print("<pre>");
# passthru($command);
# print("</pre>");

  $now = date("Y-m-d H:i:s");
  print<<<EOS
현재시간: $now<br>
/var/log/dhcpd.log 로그 파일 덤프<br>
EOS;

  $pattern = "/([0-9a-zA-Z][0-9a-zA-Z]\:..\:..\:..\:..\:[0-9a-zA-Z][0-9a-zA-Z])/";

  $prn = array("/(DHCPDISCOVER)/", "/(DHCPREQUEST)/", "/(DHCPACK)/", "/(DHCPOFFER)/");
  $rep = array(
    '<font color="#ff0000">${1}</font>',
    '<font color="#0000ff">${1}</font>',
    '<font color="#008800">${1}</font>',
    '<font color="#a07000">${1}</font>');

  print("<pre>");
  $n = count($output);
  for ($i = 0; $i < $n; $i++) {
    $line = $output[$i];

    $line = preg_replace($prn, $rep, $line);
    //$line = preg_replace("/(DHCPREQUEST)/", '<font color="blue">${1}</font>', $line);

    $ret = preg_match($pattern, $line, $matches);
    //print("$ret\n");
    //print_r($matches);

    if ($ret >= 1) {
      $mac = $matches[0];

      $qry = "select * from users where mac='$mac'";
      $ret = mysql_query($qry);
      $row = mysql_fetch_array($ret);
      $name = $row['name'];
      $dept = $row['dept'];
      $model = $row['model'];
      if ($name == '') $name = '==미등록==';

      $str = " <font color='green'>($name $dept $model)</font>";
    } else $str = '';

    print("$line$str\n");
  }
  print("</pre>");

  PageTail();

?>
