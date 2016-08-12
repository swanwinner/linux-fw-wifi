<?php


// 맥 주소를 구함
function get_mac_addr() {
  $ip = $_SERVER['REMOTE_ADDR'];

  $command = "/sbin/arp -n | grep '$ip '";
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

function PageHead() {
  print<<<EOS
<html>
<head>
<title>Matthias WiFi</title>
 
<style type='text/css'> 
body,tr,td,input { font-family:"맑은 고딕"; font:10pt;
 background-color:white; }

button { font-family:"맑은 고딕"; font:10pt; }

a:link { text-decoration:none; }
a.offsite { font-style: oblique; }  
a:visited { text-decoration:none; color: rgb(60%,0%,30%); }  
a:active  { text-decoration:none; } 
a:hover   { text-decoration:none; }

table.data { border:0px; background:#999999; }
table.data th { background:#eeeeee; padding:1 3 1 3px; }
table.data td { background:#ffffff; padding:1 3 1 3px; }
table.data td.l { text-align:left; }
table.data td.c { text-align:center; }
table.data td.r { text-align:right; }

table.datal { border:0px; background:#999999; }
table.datal th { background:#eeeeee; padding:1 3 1 3px; }
table.datal td { background:#ffffff; padding:1 3 1 3px; font-size:20pt; }
table.datal td input { font-size:15pt; }
table.datal td select { font-size:15pt; }
table.datal td.l { text-align:left; }
table.datal td.c { text-align:center; }
table.datal td.r { text-align:right; }

/* 페이저 버튼 */

a.pager:link    { color:#000000; text-decoration:none; }
a.pager:visited { color:#000000; text-decoration:none; }
a.pager:active  { color:#000000; text-decoration:none; }
a.pager:focus   { color:#000000; text-decoration:none; }
a.pager:hover   { color:#000000; text-decoration:none; }


td.pager_off {
  background-color:#ffffff;
  border:1px solid #ffffff; width:20px;
}
td.pager_on {
  background-color:#eeeeee;
  border:1px solid #000000; width:20px;
  font-weight: bold;
}
td.pager_sel_off {
  background-color:#ffffff;
  border:1px dashed #000000; width:20px;
  color: orangered; font-weight: bold;
}
td.pager_sel_on {
  background-color:#eeeeee;
  border:1px solid #000000; width:20px;
  color: orangered; font-weight: bold;
}
td.pager_static {
  background-color:#ffffff; width:20px;
}

</style>
</head>
 
<body>
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

// MAC 주소를 이용하여 사용자 정보를 가져온다.
function get_user_from_mac($mac) {

  if ($mac == '') return null;

  $qry = "SELECT * FROM users WHERE mac='$mac'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  return $row;

/*
mysql> desc users;
+---------+-----------+------+-----+---------+----------------+
| Field   | Type      | Null | Key | Default | Extra          |
+---------+-----------+------+-----+---------+----------------+
| uid     | int(11)   | NO   | PRI | NULL    | auto_increment |
| name    | char(20)  | YES  |     | NULL    |                |
| mphone  | char(20)  | YES  |     | NULL    |                |
| dept    | char(20)  | YES  |     | NULL    |                |
| email   | char(50)  | YES  |     | NULL    |                |
| passwd  | char(50)  | YES  |     | NULL    |                |
| pwchday | date      | YES  |     | NULL    |                |
| mac     | char(20)  | YES  |     | NULL    |                |
| ipaddr  | char(20)  | YES  |     | NULL    |                |
| model   | char(100) | YES  |     | NULL    |                |
| mname   | char(20)  | YES  |     | NULL    |                |
| regdate | date      | YES  |     | NULL    |                |
| iptmp   | char(20)  | YES  |     | NULL    |                |
| idate   | datetime  | YES  |     | NULL    |                |
| atime   | datetime  | YES  |     | NULL    |                |
| astamp  | int(11)   | YES  |     | NULL    |                |
+---------+-----------+------+-----+---------+----------------+
*/
}


?>
