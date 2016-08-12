<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  PageHead('iftop');

  print<<<EOS
<style>
table.main { border:0px solid red; border-collapse:collapse; }
table.main th, table.main td { border:1px solid #999; padding:2 9 2 9px; }
table.main th { background-color:#eeeeee; font-weight:bold; text-align:center; }
table.main td.c { text-align:center; }
table.main td.r { text-align:right; }

span.link { cursor:pointer; color:#880000; }
</style>
EOS;

  $path = "/www/com/iftop.txt";
  $content = file_get_contents($path);
  print<<<EOS
<pre>
updated every 1 min:

$content
</pre>
EOS;

function _get_row($ip) {
  $qry = "SELECT * FROM users WHERE ipaddr='$ip'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_assoc($ret);
  return $row;
}

    print<<<EOS
<table border='1' class='main'>
<tr>
<th></th>
<th></th>
<th>부서</th>
<th>이름</th>
<th></th>
<th>2s</th>
<th>10s</th>
<th>40s</th>
<th></th>
</tr>
EOS;
  $list = preg_split("/\n/", $content);

  $n = count($list);
  $ln = 0;
  for ($i = 0; $i < $n; $i++) {
    $ln++;
    $line = $list[$i];
    if ($ln < 5) continue; // start from line 5
    if ($ln > 24) continue; // end to line 24
    //print("$ln $line<br>");

    $num = substr($line, 0,4); // column 0~4
    $num = trim($num);

    $line = substr($line, 5); // start from column 5
    
    $item = preg_split("/ +/", $line);
    //dd($item);
    $a = $item[0];
    $b = $item[1];
    $c = $item[2];
    $d = $item[3];
    $e = $item[4];
    $f = $item[5];
    list($ip, $port) = preg_split("/:/", $a);

    $row = _get_row($ip);
    //dd($row);

    print<<<EOS
<tr>
<td class='c'>$num</td>
<td>$ip:$port</td>
<td>{$row['dept']}</td>
<td>{$row['name']}</td>
<td>$b</td>
<td class='r'>$c</td>
<td class='r'>$d</td>
<td class='r'>$e</td>
<td class='r'>$f</td>
</tr>
EOS;
  } 
  print<<<EOS
</table>
<br>
무선망 최근 3시간 트래픽:<br>
<a href='/health/eth1.html'>
<img src='/health/eth1-3hours.png'>
</a>
EOS;

  PageTail();
  exit;

?>
