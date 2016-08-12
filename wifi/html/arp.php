<?php

  include("path.php");
  include("$env[prefix]/inc/common.login.php");



### {{{
if ($mode == 'update') {

  // arp 정보를 읽는다.
  $command = "/www/com/surun root \"/www/com/cron.sh\"";
  system($command);

  print<<<EOS
<script>
alert('갱신되었습니다.');
history.go(-1);
</script>
EOS;
  exit;
}
### }}}


  PageHead('ARP 캐시 조회');


  print<<<EOS
<style>
table.main { border:0px solid red; border-collapse:collapse; }
table.main th, table.main td { border:1px solid #999; padding:1px 5px 1px 5px; }
table.main th { background-color:#eeeeee; font-weight:bold; text-align:center; }
table.main td.c { text-align:center; }
table.main td.r { text-align:right; }
table.main td.a { text-align:right; font-family:"맑은 고딕"; text-align:left; }
</style>
EOS;


  print<<<EOS
<pre>
매 5분 마다 갱신됨 (  arp -i $conf[internalNIC] > arp.txt  )   <a href='$env[self]?mode=update'>지금 갱신</a>
</pre>
EOS;


  $file = "/www/com/arp.txt";
  $content = file_get_contents($file);
  //print $content;
  $list = preg_split("/\n/", $content);
  //print_r($list);
  $n = count($list);


  $arpinfo = array();
  for ($i = 0; $i < $n; $i++) {
    $line = $list[$i];
    $line = strtoupper($line);

    if (preg_match("/incomplete/", $line)) continue;
    if (!preg_match("/[0-9A-F][0-9A-F]:[0-9A-F][0-9A-F]:[0-9A-F][0-9A-F]:/", $line)) continue;
    //dd($line."<br>");

    list($a,$b,$c,$d,$e) = preg_split("/ +/", $line);
    //print("$a,$b,$c,$d,$e\n");

    $ip = $a;
    $mac = $c;
    $arpinfo[] = array($ip, $mac);
  }
 //dd($arpinfo);

  print<<<EOS
<table class='main' cellpadding='1' cellspacing='1'>
<tr>
<th nowrap>#</th>
<th nowrap>IP</th>
<th nowrap>MAC</th>
<th nowrap>제조사</th>
<th nowrap>이름</th>
<th nowrap>부서</th>
<th nowrap>단말기종</th>
</tr>
EOS;

  $n = count($arpinfo);
  $cnt = 0;
  for ($i = 0; $i < $n; $i++) {
    list($ip, $mac) = $arpinfo[$i];
    $cnt++;

    $mac6 = substr($mac, 0, 8); # 11:22:33
    $mac6dash = preg_replace("/:/", "-", substr($mac, 0, 8)); # 11-22-33

    $mac66 = preg_replace("/:/", "", substr($mac, 0, 8)); # 112233
    //print("$mac $mac6 -------<br>");

    // mac 으로 검색
    $qry2 = "SELECT oui.company, a.*"
       ." FROM (SELECT u.*, CONCAT(SUBSTRING(u.mac,1,2),'-',SUBSTRING(u.mac,4,2),'-',SUBSTRING(u.mac,7,2)) AS mac6 "
       ." FROM users u WHERE u.mac='$mac') a"
       ." LEFT JOIN oui ON a.mac6=oui.mac6"
       ;
    $ret2 = mysql_query($qry2);
    $mc2 = mysql_num_rows($ret2);
    $row2 = mysql_fetch_assoc($ret2);
    //dd($row2);

    $qry3 = "SELECT * FROM oui WHERE mac6='$mac6dash'";
    //dd("$qry3 ; <br>");
    $ret3 = mysql_query($qry3);
    $row3 = mysql_fetch_assoc($ret3);
    $company = $row3['company'];
    //dd("$company -------<br>");

    $dept = $row2['dept'];
    $name = $row2['name'];
    $model = $row2['model'];
    if (!$name) $name = "--미등록--";

    //if ($company == '') {
      $company=<<<EOS
<a href='http://standards.ieee.org/cgi-bin/ouisearch?$mac66'>$mac66</a> $company
EOS;
    //}

    print<<<EOS
<tr>
<td class='a'>$cnt</td>
<td class='a'>$ip</td>
<td class='a'>$mac</td>
<td class='a'>$company</td>
<td class='c'>$name</td>
<td class='c'>$dept</td>
<td class='c'>$model</td>
</tr>
EOS;
  }

  print<<<EOS
</table>
EOS;

  PageTail();
  exit

?>
