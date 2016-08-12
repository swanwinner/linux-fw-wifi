#!/usr/local/bin/php
<?php

  $conf['rrd_dir_path'] = "/www/com/rrd";
  $conf['rrd_img_path'] = "/www/com/rrdimg";

date_default_timezone_set('Asia/Seoul');

### {{{ function

/*
cd /www/com/rrd/

rrdtool create arp.rrd --step 300 --start 0  \
      "DS:running:GAUGE:600:U:U"  \
      "RRA:AVERAGE:0.5:1:676"  \
      "RRA:AVERAGE:0.5:6:672"  \
      "RRA:AVERAGE:0.5:24:720"  \
      "RRA:AVERAGE:0.5:288:730"  \
      "RRA:MAX:0.5:1:676"  \
      "RRA:MAX:0.5:6:672"  \
      "RRA:MAX:0.5:24:720"  \
      "RRA:MAX:0.5:288:797"
*/

function _graph($rrd_time, $rrdfile, $title, $pngpath) {
  $opts = array(
    "--imgformat", "PNG",
    "--start","$rrd_time",
    "--end","now",
    "--width","400",
    "--height","100",
    "DEF:running=$rrdfile:running:AVERAGE",
    "LINE2:running#ff0000:$title",
    "GPRINT:running:MIN:$title\:%6.2lf"
  );
  $ret = rrd_graph($pngpath, $opts, count($opts));
  if ( $ret == 0 ) {
    printf("rrd graph error: %s\n", rrd_error());
    SaveLog("rrd_graph failed");
    return false; // fail
  }
}


function _arp_rrd_update($cnt) {
  global $conf;

  $rrdfile = $conf['rrd_dir_path']."/arp.rrd";

  $ret = rrd_update($rrdfile, "N:$cnt"); // 업데이트
  if ( $ret == 0 ) {
    //printf("rrd update error: %s\n", rrd_error());
    SaveLog("rrd_update error");
    return false; // fail
  }

  $pngpath = $conf['rrd_img_path']."/arp_3hours.png";
  $rrd_time='-3hours';
  $title = '3hours';
  _graph($rrd_time, $rrdfile, $title, $pngpath);

  $pngpath = $conf['rrd_img_path']."/arp_8days.png";
  $rrd_time='-8days';
  $title = '8days';
  _graph($rrd_time, $rrdfile, $title, $pngpath);

  $pngpath = $conf['rrd_img_path']."/arp_5weeks.png";
  $rrd_time='-5weeks';
  $title = '5weeks';
  _graph($rrd_time, $rrdfile, $title, $pngpath);

  return true; // success
}


function SaveLog($msg) {
  $t = date("Y-m-d H:i:s");
  print("$t $msg\n");

  //$qry = "INSERT INTO log SET memo='$msg',idate=now()";
  //mysql_query($qry);
}

### }}} function

  // mysql 접속
  include("/www/config/config.php");
  //print_r($conf);
  $conn = mysql_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd']);
  mysql_select_db($conf['dbname']);

  // arp.txt 파일을 읽는다.
  $file = "/www/com/arp.txt";
  $content = file_get_contents($file);
  //print $content;
  $list = preg_split("/\n/", $content);
  //print_r($list);


  // 파일이 생성된 시간
  list($a, $b) = preg_split("/@/", $list[0]);
  $lastupdate = $b;
  //print("lastupdate=$lastupdate<br>");


  $msgs = array();
  $mac_list = array();
  $n = count($list);
  $cnt = 0;
  for ($i = 0; $i < $n; $i++) { // arp.txt 모든 라인에 대해서
    $line = $list[$i];

    if (preg_match("/[0-9A-F][0-9A-F]:[0-9A-F][0-9A-F]:[0-9A-F][0-9A-F]:/", $line)) {
      list($a,$b,$c,$d,$e) = preg_split("/ +/", $line);
      //print("$a,$b,$c,$d,$e\n");
      $mac = $c;
      $ip = $a;
      //print("$ip, $mac\n");

      $cnt++;

    } else if (preg_match("/incomplete/", $line)) {
      //print("$line *** 오류 \n");

    } else {
    }
  }


  // arp 캐시에서 발견된 MAC 주소 개수를 rrd db에 기록
  SaveLog("arp_rrd_update $cnt");
  _arp_rrd_update($cnt);

  exit;

?>
