<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

function gmt_to_kst($time) {
  $time = trim($time);
  //print("*$time*<br>");
  if ($time == '') return '';
  $y = substr($time, 0, 4);
  $m = substr($time, 5, 2);
  $d = substr($time, 8, 2);
  $h = substr($time, 11, 2);
  $i = substr($time, 14, 2);
  $s = substr($time, 17, 2);

  $gmt_t = mktime($h,$i,$s,$m,$d,$y);
  $kst_t = $gmt_t + 9*3600;
  $ret = date('Y-m-d H:i:s', $kst_t);
  return $ret;
}


function parse_block($a) {
  $ip = $starts = $ends = $tstp = $state = $mac = $uid = '';

  $n = count($a);
  for ($i = 0; $i < $n; $i++) {
    $line = trim($a[$i]);
    if ($line == '') continue;
    //print $line."\n";
    $b = preg_split("/ /", $line);
    //print_r($b);

    if ($b[0] == 'lease') @$ip = $b[1];
    else if ($b[0] == 'starts') @$starts = $b[2].' '.$b[3];
    else if ($b[0] == 'ends') @$ends = $b[2].' '.$b[3];
    else if ($b[0] == 'tstp') @$tstp = $b[2].' '.$b[3];
    else if ($b[0] == 'binding') @$state = $b[2];
    else if ($b[0] == 'hardware') @$mac = $b[2];
    else if ($b[0] == 'uid') @$uid = $b[1];
  }

  //print("<font color=red>$ip,$starts,$ends,$tstp,$state,$mac,$uid<br></font>";
  return array($ip,$starts,$ends,$tstp,$state,$mac,$uid);
}



  // DHCP 할당 리스트
  $command = "/www/com/surun root \"/www/html/dhcplist.cli.php\"";
  exec($command, $output, $ret_var);

  $tmpfile = "/tmp/tmpfile";
  $content = file_get_contents($tmpfile);
  $list = preg_split("/\n}\n/", $content);

  PageHead();
  //AdminHeadline();


  $now = date('Y-m-d H:i:s');


  $active_ip_list = array();
  $active_ip_info_list = array();

  $cnt_active = $cnt_free = 0;
  $n = count($list);
  for ($i = 0; $i < $n; $i++) {
    $block = $list[$i];
    //print $block;
    $tmp = preg_split("/[{;]/", $block);
    //print_r($tmp);
    list ($ip,$starts,$ends,$tstp,$state,$mac,$uid) = parse_block($tmp);
    if ($ip == '') continue;

    $starts = gmt_to_kst($starts);
    $ends = gmt_to_kst($ends);

    if ($state == 'active') $cnt_active++;
    else if ($state == 'free') $cnt_free++;

    // 상태가 active 이면
    if ($state == 'active') {
      //print("$ends, $now <br>");
      if ($ends > $now) { // 시간이 아직 남아 있음
        // active_ip_list 에 없으면 넣는다.
        if (!in_array($ip, $active_ip_list)) {
           array_push($active_ip_list, $ip); // ip
           $active_ip_info_list[$ip] = array($starts, $ends, $mac);
        } else {
          if ($starts > @$active_ip_info_list[$ip][0]) {
            $active_ip_info_list[$ip] = array($starts, $ends, $mac);
          }
        }
      }
    }

  }

/*
  $n_ip = count($active_ip_list);
  print<<<EOS
active : $cnt_active, free : $cnt_free<br>
active ip count : $n_ip<br>
EOS;
*/


  //print_r($active_ip_info_list);

  print<<<EOS
dhcplist 현재시간: $now (KST)<br>
/var/lib/dhcpd/dhcpd.leases 파일을 분석한 내용<br>

<table border='1' class='main'>
<tr>
<th>#</th>
<th>ip</th>
<th>starts(KST)</th>
<th>ends(KST)</th>
<th>mac</th>
<th>name</th>
<th>dept</th>
<th>model</th>
</tr>
EOS;


  $ip_list = array_keys($active_ip_info_list);
  $n = count($ip_list);

  $cnt = 0;
  for ($i = 0; $i < $n; $i++) {
    $cnt++;
    $ip = $ip_list[$i];
    list($starts, $ends, $mac) = $active_ip_info_list[$ip];
 
    $qry = "select * from users where mac='$mac'";
    $ret = mysql_query($qry);
    $row = mysql_fetch_array($ret);
    //print_r($row);
    $name = $row['name'];
    $dept = $row['dept'];
    $model = $row['model'];
    if ($name == '') $name = '==미등록==';
    print<<<EOS
<tr>
<td>$cnt</td>
<td>$ip</td>
<td>$starts</td>
<td>$ends</td>
<td>$mac</td>
<td>$name</td>
<td>$dept</td>
<td>$model</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  PageTail();

?>
