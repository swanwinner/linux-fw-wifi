<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  $ip = $_SERVER['REMOTE_ADDR'];


### {{{
if ($mode == 'doadd' or $mode == 'doedit') {
  //print_r($form); exit;

  @$name = $form['name'];
  $name = preg_replace("/ /", '', $name);

  @$mphone = $form['mphone'];
  @$dept = $form['dept'];
  @$email = $form['email'];

  @$passwd1 = $form['passwd1'];
  @$passwd2 = $form['passwd2'];

  @$model = $form['model'];
  @$model2 = $form['model2']; // 기타입력
  if ($model2 != '') $model = $model2;

  @$mac = $form['mac'];
  @$mname = $form['mname'];
  $_SESSION['prev_mname'] = $mname;

  $passwd = md5($passwd1);

  if ($mode == 'doadd') {

    $qry = "SELECT * FROM users WHERE mac='$mac'";
    $ret = db_query($qry);
    $row = db_fetch($ret);
    if ($row) {
      iError('같은 MAC주소가 존재합니다.');
    }

    $qry = "INSERT INTO users SET"
      ." name='$name'"
      .",mphone='$mphone'"
      .",dept='$dept'"
      .",email='$email'"
      .",passwd='$passwd'"
      .",model='$model'"
      .",mac='$mac'"
      .",regdate=now()"
      .",idate=now()"
      .",mname='$mname'"
      ;
    $ret = db_query($qry);

    $msg = '사용자 등록이 완료되었습니다.';

  } else if ($mode == 'doedit') {

    $uid = $form['uid'];

    $qry = "SELECT * FROM users WHERE uid='$uid'";
    $ret = db_query($qry);
    $row = db_fetch($ret);
    if (!$row) {
      iError('record not found');
    }

    $qry = "UPDATE users SET"
      ." name='$name'"
      .",mphone='$mphone'"
      .",dept='$dept'"
      .",email='$email'"
      .",passwd='$passwd'"
      .",model='$model'"
      .",mac='$mac'"
      .",mname='$mname'"
      ." WHERE uid='$uid'"
      ;
    $ret = db_query($qry);

    $msg = '사용자 정보수정이 완료되었습니다.';
  }

  print<<<EOS
<script>
alert('$msg');
document.location = "$self";
</script>
EOS;
  exit;
}

else if ($mode == 'management') {
  include("inc.manage.php");
  exit;
}

else if ($mode == 'doaddip') {
  //print_r($form);

  $iptmp = $form['iptmp'];

  $qry = "INSERT INTO users SET iptmp='$iptmp', regdate=NOW(), idate=NOW()";
  $ret = db_query($qry);

  print<<<EOS
<script>
document.location = "$self";
</script>
EOS;
  exit;
}

else if ($mode == 'addip') {

  PageHead('신규등록');
  //AdminHeadline();
  //Menu();

  print<<<EOS
등록 대기 IP 입력<br>
* 단말기에서 http://{$conf['internalIP']} 로 접속하여 표시된 IP 주소를 입력합니다.<br>
* 확인버튼을 누른후 단말기의 페이지를 새로고침합니다.(MAC주소 입력)<br>

<table class='datal' cellpadding='1' cellspacing='1'>
<form name='form' action='$self' method='post'>
<tr>
 <td>등록대기IP</td>
 <td><input type='text' name='iptmp' size='20'
 maxlength='20' value="192.168."></td>
</tr>

<tr>
 <td colspan='2' align='center'>
   <input type='hidden' name='mode' value='doaddip'>
   <input type='button' onclick="submit_form()" style='width:100; height:30;' value='확인'>
 </td>
</tr>


</form>
</table>

<script>
function submit_form() {
  var form = document.form;
  form.submit();
}
</script>
EOS;
  PageTail();
  exit;
}

else if ($mode == 'add' or $mode == 'edit') {

  if ($mode == 'add') {
    $row = array();

    $mac = get_mac_addr();
    if ($mac == '') $mac = 'null';
    $row['mac'] = $mac;

    $nextmode = 'doadd';

  } else if ($mode == 'edit') {
    $uid = $form['uid'];

    $qry = "SELECT * FROM users WHERE uid='$uid'";
    $row = db_fetchone($qry);

    $nextmode = 'doedit';
  }

  PageHead('정보수정');
  //AdminHeadline();

  print<<<EOS
무선인터넷 사용자 등록<br>

휴대단말기 종류에 해당 기종이 없을 경우,
 <a href='/device.php'>단말기 관리 메뉴</a>에 들어가서 추가가능합니다.

<table class='datal' cellpadding='1' cellspacing='1'>
<form name='form' action='$self' method='post'>
EOS;

  if ($mode == 'edit') {
  print<<<EOS
<tr>
 <td>ID</td>
 <td>$row[uid]</td>
</tr>
EOS;
  }

  $size = '30';
  print<<<EOS
<tr>
 <td>이름</td>
 <td><input type='text' name='name' size='$size'
 maxlength='$size' value="$row[name]"></td>
</tr>
EOS;

  $mphone = $row['mphone'];
  if ($mphone == '') $mphone = '010-';
  print<<<EOS
<tr>
 <td>휴대폰</td>
 <td><input type='text' name='mphone' size='$size'
 maxlength='$size' value="$mphone"></td>
</tr>

<tr>
 <td>소속부서</td>
 <td><input type='text' name='dept' size='$size'
 maxlength='$size' value="$row[dept]"></td>
</tr>

<tr>
 <td>이메일</td>
 <td><input type='text' name='email' size='$size'
 maxlength='50' value="$row[email]"></td>
</tr>

EOS;


  $qry2 = "SELECT * FROM devices ORDER BY ord";
  $ret2 = db_query($qry2);
  $list = array();
  while ($row2 = db_fetch($ret2)) {
    $title = $row2['title'];
    $list[] = $title;
  }
  //print_r($list);


  $len = count($list);

  $model = preg_replace("/ /", "", $row['model']);
  $preset = $model;

  $opt = "<option value=''>==선택==</option>";
  for ($i = 0; $i < $len; $i++) {
    $val = $list[$i];
    if ($val == $preset) $sel = ' selected'; else $sel = '';
    $opt .=<<<EOS
<option value="$val"$sel>$val</option>
EOS;
  }
  $opt .= "<option value='기타'>*기타(직접입력)</option>";

  print<<<EOS
<style>
span.selmodel { color:#000; font-size:16pt; font-weight:bold;  cursor:pointer; }
</style>

<tr>
 <td>단말기종류</td>
 <td>
  <select name='model2' onchange="sel_model()">$opt</select>
기타:<input type='text' name='model' size='10' maxlength='50' value="$model">

<div>
<span onclick="_selmodel('애플 아이폰')" class='selmodel'>애플아이폰</span>
: <span onclick="_selmodel('삼성 갤럭시')" class='selmodel'>삼성갤럭시</span>
: <span onclick="_selmodel('LG')" class='selmodel'>LG</span>
</div>
 </td>
</tr>

<script>
function _selmodel(model) {
  var form = document.form;
  var sel = form.model2;
  for (i = 0; i < sel.length; i++) {
    var v = sel.options[i].value
    if (model == v) {
      sel.options[i].selected = true;
      return;
    }
  }
}
</script>
EOS;

  print<<<EOS
<tr>
 <td>MAC주소</td>
 <td><input type='text' name='mac' size='$size'
 maxlength='50' value='$row[mac]'></td>
</tr>
EOS;

  $mname = $row['mname'];
  if ($mname == '') $mname = $_SESSION['prev_mname'];
  print<<<EOS
<tr>
 <td>등록자</td>
 <td><input type='text' name='mname' size='$size' maxlength='50' value='$mname'></td>
</tr>
EOS;

  if ($mode == 'edit') {
    print<<<EOS
<tr>
 <td>등록일</td>
 <td>$row[regdate]</td>
</tr>

<tr>
 <td>사용량</td>
 <td>$row[pkts]pkts $row[bytes]bytes ($row[traffic_date])</td>
</tr>

<tr>
 <td>접속회수</td>
 <td>$row[conn_count]</td>
</tr>
EOS;
   // 접속회수 conn_count 는 2015.1.14 부터 적용
  }

  if ($mode == 'edit') {
    $hidden =<<<EOS
<input type='hidden' name='uid' value='$uid'>
EOS;
  } else $hidden = '';

  print<<<EOS
<tr>
 <td colspan='2' align='center'>
   $hidden
   <input type='hidden' name='mode' value='$nextmode'>
   <button onclick="submit_form()" style='width:100; height:30;'>확인</button>
 </td>
</tr>

</form>
</table>

<script>
function sel_model() {
  var form = document.form;
  var sel = form.model2;
  var idx = sel.selectedIndex;
  var val = sel.options[idx].value;
  //alert(val);
  if (val == '기타') form.model.focus();
}
function submit_form() {
  var form = document.form;
  form.submit();
}
</script>
EOS;
  PageTail();
  exit;
}

// hiddenframe
else if ($mode == 'ban') {
  $uid = $form['uid'];

  $qry = "SELECT * FROM users WHERE uid='$uid'";
  $ret = db_query($qry);
  $row = db_fetch($ret);
  if (!$row) die('row not found');
  $mac = $row['mac'];
  //print $mac;
  #exit;

  $qry = "UPDATE users SET atime='0000-00-00',astamp=0 WHERE uid='$uid'";
  $ret = db_query($qry);

# // 방화벽 규칙을 삭제
# $command = "/www/com/surun root \"/www/com/delrule $mac\"";
# system($command);

  // 방화벽 규칙을 재설정
  $command = "/www/com/surun root /www/com/do.sh";
  system($command);

  print<<<EOS
<script>
alert('접근해제완료');
//document.location = "$self";
parent.document.location.reload();
</script>
EOS;
  exit;
}

else if ($mode == 'delete') {
  $uid = $form['uid'];
  //print $uid;

  $qry = "DELETE FROM users WHERE uid='$uid'";
  $ret = db_query($qry);

  // 방화벽 규칙을 재설정
  $command = "/www/com/surun root /www/com/do.sh";
  system($command);

  // hiddenframe안에 로드됨
  print<<<EOS
<script>
alert('삭제완료');
//parent.document.location = "$self";
parent.document.location.reload();
</script>
EOS;
  exit;
}


else if ($mode == 'showrule') {
  //print_r($row);

  // 방화벽 규칙 보기
  $command = "/www/com/surun root \"/sbin/iptables -t nat -vn -L PREROUTING --line-numbers\"";
# exec($command, $output, $ret_var);
# print_r($output);
  print("<pre>");
  passthru($command);
  print("</pre>");

  exit;
}

### }}}



// 로그인 상태가 아직 아님
@$logined = $_SESSION['logined'];
if (!$logined) {

  // 로그인 암호 제출
  @$pw = $_REQUEST['pw'];
  if ($pw != '') {
    if ($pw == 'matthias') {
      // 로그인 세션 설정
      $_SESSION['logined'] = true;
      header("Location: $self");
      exit;
    } else {
      sleep(1);
      print "암호를 다시 입력하세요.";
    }
  }

  print<<<EOS
<html>
<head>
<title>IP등록</title>
<style type='text/css'>
body {  font-size: 12px; font-family: 굴림,돋움,verdana;
  font-style: normal; line-height: 12pt;
  text-decoration: none; color: #333333;
}
table,td,th { font-size: 12px; font-family: 돋움,verdana; white-space: nowrap; }
</style>

</head>
<body>

<center>

<form action='$self' method='post' name='form'>
무선인터넷 관리<br>
로그인:<input type='password' name='pw' size='20'><input
 type='submit' value='확인' style='width:60;height:25;'>
</form>
접속IP: $_SERVER[REMOTE_ADDR]

</center>

<script>
function _focus() {
  document.form.pass.focus();
}
window.attachEvent('onload', _focus);
</script>

</body>
</html>
EOS;
  exit;
}


/*
  // DHCP 할당 개수
  $command = "/www/com/surun root \"/www/com/dhcpcount2\"";
  exec($command, $output, $ret_var);
  //print_r($output);
  $dhcp_ip_count = $output[0];
현재 무선인터넷 접속자 수: <font color='red'>{$dhcp_ip_count}명</font><br>
*/

  PageHead('홈');
  //AdminHeadline();
  //Menu();

function _get_total_user_count() {
  $qry = "SELECT COUNT(*) AS total FROM users";
  $ret = db_query($qry);
  $row = db_fetch($ret);
  $total = $row['total'];
  return $total;
}

  $total = _get_total_user_count();
  print<<<EOS
<style>
div.totaluser { font-size:12pt; font-weight:bold; }
</style>
<div class='totaluser'>
WiFi 등록 사용자 총: $total 명
</div>
EOS;

  print<<<EOS
<font color='#999999'>
등록방법<br>
* 단말기에서 무선인터넷 연결 - 인터넷 접속시도 - http://{$conf['internalIP']} 로 리다이렉션된 페이지에서 IP 확인<br>
* 신규등록 클릭하고, 표시된 IP 주소를 입력 - 단말기의 페이지를 새로고침(MAC주소 입력과정)<br>
* 나머지 사용자 정보를 입력후 단말기에서 '무선인터넷 시작' 버튼을 클릭한 이후 부터 인터넷 사용가능<br>
</font>
EOS;

function _stat() {

  $qry = "
SELECT * FROM
(SELECT '삼성' AS model, COUNT(*) AS count FROM users WHERE model LIKE '%삼성%'
UNION
SELECT 'LG'    AS model, COUNT(*) AS count FROM users WHERE model LIKE '%lg%'
UNION
SELECT '펜텍'  AS model, COUNT(*) AS count FROM users WHERE model LIKE '%펜텍%'
UNION
SELECT '애플'  AS model, COUNT(*) AS count FROM users WHERE model LIKE '%애플%'
UNION
SELECT '기타'  AS model, COUNT(*) AS count FROM users WHERE 
 model NOT LIKE '%삼성%'
 AND model NOT LIKE '%LG%'
 AND model NOT LIKE '%펜텍%'
 AND model NOT LIKE '%애플%'
) A
";
  $ret = db_query($qry);

  $html = "";
  $total = 0;
  while ($row = db_fetch($ret)) {
    $total += $row['count'];
    $html .= " {$row['model']}:{$row['count']}건";
  }
  $html .= " 총:{$total}건";
  $html .= " / ";


  $qry = "
SELECT * FROM
(SELECT '테블릿' AS model, COUNT(*) AS count FROM users WHERE model LIKE '%테블릿%'
UNION
SELECT '아이패드'  AS model, COUNT(*) AS count FROM users WHERE model LIKE '%아이패드%'
UNION
SELECT '노트북'  AS model, COUNT(*) AS count FROM users WHERE model LIKE '%노트북%'
UNION
SELECT '기타'  AS model, COUNT(*) AS count FROM users WHERE 
 model NOT LIKE '%테블릿%'
 AND model NOT LIKE '%패드%'
 AND model NOT LIKE '%노트북%'
) A
";
  $ret = db_query($qry);

  $total = 0;
  while ($row = db_fetch($ret)) {
    //print_r($row);
    $total += $row['count'];
    $html .= " {$row['model']}:{$row['count']}건";
  }
  $html .= " 총:{$total}건";



  return $html;
}

  $html = _stat();
  print<<<EOS
등록통계: $html
EOS;

  $sql_where = " WHERE 1";

  // 이름,부서,단말기 으로 키워드 검색 조건
  @$k = $form['k'];
  if ($k != '') {
    if ($k == '미입력') $sql_where .= " and (name='' or name is null)";
    else {
      $sql_where .= " AND (name LIKE '%$k%'"
        ." OR dept LIKE '%$k%'"
        ." OR mphone LIKE '%$k%'"
        ." OR model LIKE '%$k%'"
        ." OR mac LIKE '%$k%'"
        ." OR mname LIKE '%$k%'"
      .")";
    }
  }

  $qry = "SELECT COUNT(*) AS total FROM users $sql_where";
  $ret = db_query($qry);
  $row = db_fetch($ret);
  $total = $row['total'];

  if ($k != '') {
    $search_result=<<<EOS
검색결과: {$total}건, <a href="$self">검색취소</a>
EOS;
  } else {
    $search_result=<<<EOS
총 {$total}건
EOS;
  }

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
  else if ($sort == '7') $order = "pkts DESC"; # 패킷
  else $order = "regdate DESC, idate DESC";


  $qry = "SELECT * FROM users $sql_where ORDER BY $order"
      ." LIMIT $start,$ipp";
  #print $qry;
  $ret = db_query($qry);

  unset($form['page']);
  $qs = Qstr($form);
  $url = "$self$qs";
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
  $a7 = '패킷순';
       if ($sort == '1') $a1 = "<b><u>$a1</u></b>";
  else if ($sort == '2') $a2 = "<b><u>$a2</u></b>";
  else if ($sort == '3') $a3 = "<b><u>$a3</u></b>";
  else if ($sort == '4') $a4 = "<b><u>$a4</u></b>";
  else if ($sort == '5') $a5 = "<b><u>$a5</u></b>";
  else if ($sort == '6') $a6 = "<b><u>$a6</u></b>";
  else if ($sort == '7') $a7 = "<b><u>$a7</u></b>";
  else                   $a1 = "<b><u>$a1</u></b>";
  $html_sort=<<<EOS
<a href='$self$qs&sort=1'>$a1</a>
| <a href='$self$qs&sort=2'>$a2</a>
| <a href='$self$qs&sort=3'>$a3</a>
| <a href='$self$qs&sort=4'>$a4</a>
| <a href='$self$qs&sort=5'>$a5</a>
| <a href='$self$qs&sort=6'>$a6</a>
| <a href='$self$qs&sort=7'>$a7</a>
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
<!--
<th nowrap>전화번호</th>
-->
<th nowrap>MAC주소/IP주소</th>
<th nowrap>등록자</th>
<th nowrap>등록일</th>
<th nowrap>접근시간</th>
<th nowrap>해제</th>
<th nowrap>삭제</th>
<th nowrap>pkts</th>
<th nowrap>bytes</th>
</tr>
EOS;

  $cnt = 0;
  while ($row = db_fetch($ret)) {
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

//<td class='c'>$row[mphone]</td>
    $pkts = $row['pkts'];
    $bytes = $row['bytes'];

    print<<<EOS
<tr>
<td class='r'>$cnt</td>
<td class='r'>$uid</td>
<td class='c' nowrap><a href='$self?mode=edit&uid=$uid'>$name</a></td>
<td class='c'>$row[dept]</td>
<td class='c'>$row[model]</td>
<td class='c' nowrap>$mac<br>$ipaddr</td>
<td class='c' nowrap>$row[mname]</td>
<td class='c' nowrap>$row[regdate]</td>
<td class='c' nowrap>$atime<br>($elapsed)</td>
<td class='c' nowrap>$ban</td>
<td class='c' nowrap>$delete</td>
<td class='c' nowrap>{$row['pkts']}</td>
<td class='c' nowrap>{$row['bytes']}</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
</td></tr></table>

<table border='0'>
<tr>
<td>
 <figure style="border:0px solid red; margin:1px 1px 1px 1px;">
 <a href='/health/wifiuser.html' target='_blank'>
 <img src='/health/wifiuser-5weeks.png' width='240' height='98'>
 </a>
 <figcaption style='text-align:center;'>무선인터넷 등록자수</figcaption>
 </figure>
</td>

<td>
 <figure style="border:0px solid red; margin:1px 1px 1px 1px;">
 <a href='/health/arpcnt.html' target='_blank'>
 <img src='/health/arpcnt-32hours.png' width='240' height='98'>
 </a>
 <figcaption style='text-align:center;'>현재 사용자 수</figcaption>
 </figure>
</td>

<td>
 <figure style="border:0px solid red; margin:1px 1px 1px 1px;">
 <a href='/health/eth0.html' target='_blank'>
 <img src='/health/eth0-32hours.png' width='240' height='98'>
 </a>
 <figcaption style='text-align:center;'>실시간 트래픽</figcaption>
 </figure>
</td>

</tr>
</table>


<iframe name='hiddenframe' width=0 height=0 style='display:none'></iframe>

<script>
function _delete(uid) {
  if (!confirm('삭제할까요? 삭제하시면 복구할 수 없습니다.')) return;
  var url = "$self?mode=delete&uid="+uid;
  hiddenframe.location = url;
}

function _ban(uid) {
  if (!confirm('접속해제 할까요? 단말기에서 다시 접속시작해야 합니다.')) return;
  var url = "$self?mode=ban&uid="+uid;
  hiddenframe.location = url;
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
