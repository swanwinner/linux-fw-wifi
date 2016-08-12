<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  $ip = $_SERVER['REMOTE_ADDR'];


#{{{ modes ##############################################
#}}} modes ##############################################


// 로그인 상태가 아직 아님
@$logined = $_SESSION['logined'];
if (!$logined) {
  die('정상적인 접근이 아닙니다.');
  exit;
}



  PageHead('오래동안 접속하지 않은 단말기');
  print<<<EOS
6개월(180일) 이상 무선 인터넷에 접속하지 않은 단말기
EOS;


  $sql_where = " WHERE atime<=DATE_SUB(NOW(),INTERVAL 180 DAY)";

  $qry = "SELECT COUNT(*) AS total FROM users $sql_where";
  //print $qry;
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $total = $row['total'];

    $search_result=<<<EOS
총 {$total}건
EOS;


  # 페이지당 아이템 수
  @$ipp = $form['ipp'];
  if ($ipp == '') {
    $ipp = 20;
  }
  if ($ipp < 10) $ipp = 20; # 최소
  else if ($ipp > 100) $ipp = 100; # 최대


  @$page = $form['page'];
  if ($page == '') $page = 1;
  $last = ceil($total/$ipp);
  if ($last == 0) $last = 1;
  if ($page > $last) $page = $last;
  $start = ($page-1) * $ipp;


  @$sort = $form['sort'];
  if ($sort == '1') $order = "regdate DESC, idate DESC"; # 등록순
  else if ($sort == '2') $order = "name, idate DESC"; # 이름순
  else if ($sort == '3') $order = "dept, idate DESC"; # 부서순
  else if ($sort == '4') $order = "ipaddr, idate DESC"; # IP주소순
  else if ($sort == '5') $order = "atime DESC"; # 최근접근시간순
  else if ($sort == '6') $order = "atime"; # 오래된접근시간순
  else $order = "regdate DESC, idate DESC";


  $qry = "SELECT * FROM users $sql_where ORDER BY $order"
      ." LIMIT $start,$ipp";
  #print $qry;
  $ret = mysql_query($qry);
  print mysql_error();


  unset($form['page']);
  $qs = Qstr($form);
  $url = "home.php$qs";
  $pager = Pager_s($url, $page, $total, $ipp);
  if ($last == 1) $pager = '';

  unset($form['page']);
  $qs = Qstr($form);
  @$sort = $form['sort'];
  $a1 = '등록순';
  $a2 = '이름순';
  $a3 = '부서순';
  $a4 = 'IP주소순';
  $a5 = '최근접근시간순';
  $a6 = '오래된접근시간순';
  if ($sort == '1')      $a1 = "<b><u>$a1</u></b>";
  else if ($sort == '2') $a2 = "<b><u>$a2</u></b>";
  else if ($sort == '3') $a3 = "<b><u>$a3</u></b>";
  else if ($sort == '4') $a4 = "<b><u>$a4</u></b>";
  else if ($sort == '5') $a5 = "<b><u>$a5</u></b>";
  else if ($sort == '6') $a6 = "<b><u>$a6</u></b>";
  else                   $a1 = "<b><u>$a1</u></b>";
  $html_sort=<<<EOS
<a href='$self$qs&sort=1'>$a1</a>
| <a href='$self$qs&sort=2'>$a2</a>
| <a href='$self$qs&sort=3'>$a3</a>
| <a href='$self$qs&sort=4'>$a4</a>
| <a href='$self$qs&sort=5'>$a5</a>
| <a href='$self$qs&sort=6'>$a6</a>
&nbsp;&nbsp;&nbsp;&nbsp;
EOS;

  print<<<EOS
<table border='0'>
<tr>
<td align='center' nowrap>


<!--1-->
<table class='data' cellpadding='1' cellspacing='1' width='100%'>
<form name='form'>
<tr>
<td align='center'>
<!--2-->
<table border='0'>
<tr>
<td align='center' nowrap colspan='2'>
$html_sort
<input type='text' name='dummy' style="width:0; height:0;display:none;">
<input type='text' size='6' name='k' style="width:80; height:20;"
 onkeyup="_search_key()"><button
 style="width:60; height:22;"
 onclick="_search()">검색</button>
</td>
</tr>
<tr>
<td>$search_result</td>
<td>$pager</td>
</tr>
</table>
<!--2-->
</td></tr>
</form>
</table>
<!--1-->

</td>
</tr>
EOS;

  print<<<EOS
<tr>
<td>
<table class='data' cellpadding='1' cellspacing='1'>
<tr>
<th nowrap>#</th>
<th nowrap>ID</th>
<th nowrap>이름</th>
<th nowrap>부서</th>
<th nowrap>단말기</th>
<th nowrap>MAC주소/IP주소</th>
<th nowrap>등록일</th>
<th nowrap>접근시간</th>
<th nowrap>해제</th>
<th nowrap>삭제</th>
</tr>
EOS;

  $cnt = 0;
  while ($row = mysql_fetch_array($ret)) {
    $cnt++;
    //print_r($row);

    $uid = $row['uid'];
    $name = $row['name'];
    $atime = $row['atime'];

    $elapsed = get_elapsed_time($atime);

    $iptmp = $row['iptmp'];
    $ipaddr = $row['ipaddr'];

    $mac = $row['mac'];

    if ($iptmp != '') $mac = "$iptmp<br>등록대기";

    if ($name == '') $name = '-미입력-';

    $delete =<<<EOS
<span onclick="_delete('$uid')" style='cursor:pointer'>삭제</span>
EOS;
    $ban=<<<EOS
<span onclick="_ban('$uid')" style='cursor:pointer'>해제</span>
EOS;

    print<<<EOS
<tr>
<td class='r'>$cnt</td>
<td class='r'>$uid</td>
<td class='c' nowrap><a href='$self?mode=edit&uid=$uid'>$name</a></td>
<td class='c'>$row[dept]</td>
<td class='c'>$row[model]</td>
<td class='c' nowrap>$mac<br>$ipaddr</td>
<td class='c' nowrap>$row[regdate]</td>
<td class='c' nowrap>$atime<br>($elapsed)</td>
<td class='c' nowrap>$ban</td>
<td class='c' nowrap>$delete</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
</td></tr></table>
<iframe name='hiddenframe' width=0 height=0 style='display:none'></iframe>
<!--
<iframe name='hiddenframe' width=300 height=300></iframe>
-->

<script>
function _delete(uid) {
  if (!confirm('삭제할까요? 삭제하시면 복구할 수 없습니다.')) return;
  var url = "$self?mode=delete&uid="+uid;
  hiddenframe.location = url;
}

function _ban(uid) {
  if (!confirm('접속해제 할까요? 단말기에서 다시 접속시작해야 합니다.')) return;
  var url = "$self?mode=ban&uid="+uid;
  document.location = url;
}

function script_Go(url) {
  document.location = url;
}
EOS;

 
  unset($form['k']);
  $qs = Qstr($form);

  print<<<EOS
function _search_key() {
  if (event.keyCode == 13) { // 검색 실행
    _search();
  }
}
function _search() {
  var form = document.form;
  var k = form.k.value;
  //var enk = encodeURI(k);
  //var enk = encodeURIComponent(k);
  //var enk = escape(k);
  var enk = k;
  var url = "$self$qs&k="+enk;
  //alert(url);
  document.location  = url;
}
</script>
EOS;
  PageTail();
  exit

?>
