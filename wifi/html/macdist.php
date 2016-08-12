<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  $ip = $_SERVER['REMOTE_ADDR'];


// 로그인 상태가 아직 아님
@$logined = $_SESSION['logined'];
if (!$logined) {
  die('정상적인 접근이 아닙니다.');
  exit;
}

### {{{
function dd($msg) {
       if (is_string($msg)) print($msg);
  else if (is_array($msg)) { print("<pre>"); print_r($msg); print("</pre>"); }
  else print_r($msg);
}
### }}}


  PageHead('mac dist');


    print<<<EOS
<pre>
EOS;

  $tab = "\t";
  $nl = "\n";

  $qry = "SELECT A.*
 FROM (SELECT COUNT(*) AS count, SUBSTRING(mac,1,8) mac, model
  FROM users
  WHERE mac != ''
  GROUP BY SUBSTRING(mac,1,8), model) A
 ORDER BY A.count DESC";

  $ret = mysql_query($qry);
  while ($row = mysql_fetch_assoc($ret)) {
    //dd($row);
    print<<<EOS
{$row['mac']}$tab{$row['count']}$tab{$row['model']}$nl
EOS;
  }

    print<<<EOS
</pre>
EOS;


  PageTail();
  exit

?>
