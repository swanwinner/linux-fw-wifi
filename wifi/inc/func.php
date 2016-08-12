<?php


// 맥 주소를 구함
function get_mac_addr() {
  $ip = $_SERVER['REMOTE_ADDR'];

  $command = "/sbin/arp | grep '$ip '";
  $lastline = exec($command, $out, $retval);
# print("<pre>");
# print $lastline;
# print("</pre>");

  list($a,$b,$c,$d,$e) = preg_split("/ +/", $lastline);
# print("$a,$b,$c,$d,$e");
  return $c;
}



# display an error message and terminate program
function iError($msg, $go_back=1, $win_close=0, $exit=1) {
  $msg = ereg_replace("\n", "\\n", $msg);
  print("<script>\n");
  print("alert(\"$msg\");\n");
  if ($go_back) print("history.go(-1);\n");
  if ($win_close) print("window.close();");
  print("</script>\n");
  if ($exit) exit;
}



function PageHead($title='') {
  global $remote_addr;
  global $conf;

  if ($title != '') {
    $title_s = sprintf("%s - %s", $conf['site_title'], $title);
  } else {
    $title_s = sprintf("%s", $conf['site_title']);
  }

  print<<<EOS
<html>
<head>
<title>$title_s</title>
 
<link rel="stylesheet" type="text/css" href="/style.css">

</head>
 
<body>
관리자 페이지 (접속IP:$remote_addr)<br>

<a href='/home.php'>홈</a>
| <a href='/home.php?mode=addip'>신규등록</a>
| <a href='/device.php'>단말기관리</a>
| <a href="/health.php">Health</a>
| <a href="/arp.php">ARP캐시</a>
| <a href="/etc.php">기타</a>
| <a href='/logout.php'>로그아웃</a>
<br>
EOS;

}

function PageTail() {
  print<<<EOS
</body>
</html>
EOS;
}


# $form 변수로 부터 쿼리스트링을 만들어준다.
function Qstr($form) {
  global $env;
# if (!is_array($form)) $form = array();
  $retval = "?d";
  ksort($form);
  while (list($k, $v) = each($form)) {
    if ($v == '') continue;
    $retval .= "&$k=$v";
  # print("$k, $v");
  }
  return $retval;
}

function Pager_s($url, $page, $total, $ipp) {
  global $conf, $env;
  $html = '';

  $btn_prev = "<img src='/img/calendar/l.gif' border=0 width=11 height=11 title='이전 페이지'>";
  $btn_next = "<img src='/img/calendar/r.gif' border=0 width=11 height=11 title='다음 페이지'>";
  $btn_prev10 = "<img src='/img/calendar/l2.gif' border=0 width=11 height=11 title='이전 10 페이지'>";
  $btn_next10 = "<img src='/img/calendar/r2.gif' border=0 width=11 height=11 title='다음 10 페이지'>";

  $last = ceil($total/$ipp);
  if ($last == 0) $last = 1;

  $start = floor(($page - 1) / 10) * 10 + 1;
  $end = $start + 9;

  $html .= "<table border='0' cellpadding='2' cellspacing='0'><tr>"; # table 1

  $attr1 = " onmouseover=\"this.className='pager_on'\""
         ." onmouseout=\"this.className='pager_off'\""
         ." class='pager_off' align='center' style='cursor:pointer;'";
  $attr2 = " onmouseover=\"this.className='pager_sel_on'\""
         ." onmouseout=\"this.className='pager_sel_off'\""
         ." class='pager_sel_off' align='center' style='cursor:pointer;'";
 
  # previous link
  if ($start > 1) {
    $prevpage = $start - 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$prevpage')\"><a href='$url&page=$prevpage'>$btn_prev10</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_prev10</td>\n";

  if ($page > 1) {
    $prevpage = $page - 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$prevpage')\"><a href='$url&page=$prevpage'>$btn_prev</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_prev</td>\n";


  if ($end > $last) $end = $last;
 $html .= "</td>";
  for ($i = $start; $i <= $end; $i++) {
    $s = "$i";
    if ($i != $page) {
      $html .= "<td$attr1 onclick=\"script_Go('$url&page=$i')\">$s</td>\n";
    } else {
      $html .= "<td$attr2>$s</td>\n";
    }
  }

  # next link
  if ($page < $last) {
    $nextpage = $page + 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$nextpage')\"><a href='$url&page=$nextpage'>$btn_next</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_next</td>\n";

  if ($end < $last) {
    $nextpage = $end + 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$nextpage')\"><a href='$url&page=$nextpage'>$btn_next10</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_next10</td>\n";

  $html .= "</tr></table>\n";

  return $html;
}



/*
function AdminHeadline() {
  global $remote_addr;
  print<<<EOS
관리자 페이지 (접속IP:$remote_addr)<br>
EOS;
}
*/

function get_elapsed_time($atime) {
  @list($y,$m,$d,$h,$i,$s) = preg_split("/[- :]/", $atime);
  //print_r($a);

  @$t1 = mktime($h,$i,$s,$m,$d,$y);
  if ($t1 == 943887600) return "--d --h --m --s";

  $t2 = time();
  $s = $t2 - $t1;

  unset($h,$i,$m,$d,$y);

  if ($s > 60) {
    $m = floor($s / 60);
    $s = $s % 60;
  } else $m = '0';
  if ($m > 60) {
    $h = floor($m / 60);
    $m = $m % 60;
  } else $h = '0';
  if ($h > 24) {
    $d = floor($h / 24);
    $h = $h % 24;
  } else $d = '0';

  $elapsed = "{$d}d {$h}h {$m}m {$s}s";
  return $elapsed;
}


/*
function Menu() {
  global $self;
  print<<<EOS
<a href='/home.php'>홈</a>
| <a href='/home.php?mode=addip'>신규등록</a>
| <a href='/home.php?mode=device'>단말기관리</a>
| <a href="/health.php">Health</a>
| <a href="/arp.php">ARP캐시</a>
| <a href="/etc.php">기타</a>
| <a href='/logout.php'>로그아웃</a>
<br>
EOS;
}
*/


// debugging
function dd($msg) {
       if (is_string($msg)) print($msg);
  else if (is_array($msg)) { print("<pre>"); print_r($msg); print("</pre>"); }
  else print_r($msg);
}


?>
