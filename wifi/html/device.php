<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");

  @$mode2 = $form['mode2'];

  $_TABLE = 'devices';

### {{{
function _menu() {
  global $self;
  print<<<EOS
<a href='$self?mode=device&mode2=new'>신규 단말기 등록</a>
EOS;
}

function _device_count($model) {
  $qry = "SELECT COUNT(*) AS count FROM users WHERE model='$model'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $count = $row['count'];
  return $count;
}
### }}}



### {{{
if ($mode2 == 'delete') {
  $id = $form['id'];

  $qry = "delete from $_TABLE where id='$id'";
  $ret = mysql_query($qry);
  print mysql_error();

  header("Location: $self?mode=device");
  exit;
}


if ($mode2 == 'doedit') {
  //print_r($form);

  $title = $form['title'];
  $chulsi = $form['chulsi'];
  $url = $form['url'];
  $id = $form['id'];
  $ord = $form['ord'];

  $qry = "update $_TABLE set title='$title', chulsi='$chulsi', url='$url', ord='$ord', idate=now()"
    ." where id='$id'";
  $ret = mysql_query($qry);
  print mysql_error();

  header("Location: $self?mode=device");
   exit;
}

if ($mode2 == 'doadd') {
  //print_r($form);

  $title = $form['title'];
  $chulsi = $form['chulsi'];
  $url = $form['url'];

  $qry = "insert into $_TABLE set title='$title', chulsi='$chulsi', url='$url', idate=now()";
  $ret = mysql_query($qry);
  print mysql_error();

  header("Location: $self?mode=device");
  exit;
}

if ($mode2 == 'new' or $mode2 == 'edit') {

  if ($mode2 == 'edit') {
    $id = $form['id'];
    $qry = "select * from $_TABLE where id='$id'";
    $ret = mysql_query($qry);
    $row = mysql_fetch_array($ret);
    //print_r($row); exit;
    $nextmode = 'doedit';
    $hiddens =<<<EOS
<input type='hidden' name='id' value='$id'>
EOS;
  }
  if ($mode2 == 'new') {
    $nextmode = 'doadd';
    $hiddens = "";
    $row = array();
    $row['title'] = '';
    $row['chulsi'] = '';
    $row['url'] = '';
  }


  PageHead();
  _menu();

  //print_r($form);
  print<<<EOS
<table class='main' border='1'>
<form name='form' method='post' action'$self'>
<tr>
  <td>단말기명</td>
  <td><input type='text' name='title' size='30' value='{$row['title']}'></td>
</tr>

<tr>
  <td>출시된 시기</td>
  <td><input type='text' name='chulsi' size='30' value='{$row['chulsi']}'></td>
</tr>

<tr>
  <td>참조메모</td>
  <td><input type='text' name='url' size='30' value='{$row['url']}'></td>
</tr>

<tr>
  <td>표시순서</td>
  <td><input type='text' name='ord' size='30' value='{$row['ord']}'></td>
</tr>

<tr>
  <td colspan='2' align='center'>
<input type='hidden' name='mode' value='device'>
$hiddens
<input type='hidden' name='mode2' value='$nextmode'>
<input type='submit' value='저장'>
  </td>
</tr>

</form>
</table>
EOS;

  exit;
}
### }}}


  PageHead('단말기 등록');
  _menu();

  $qry = "select * from $_TABLE order by ord";
  $ret = mysql_query($qry);

  print<<<EOS
<table class='main' border='1'>
<tr>
<th>#</th>
<th>단말기명</th>
<th>표시순서</th>
<th>메모</th>
<th>등록된수</th>
<th>수정|삭제</th>
</tr>
EOS;

  $cnt = 0;
  while ($row = mysql_fetch_assoc($ret)) {
    $cnt++;
    //print_r($row);

    $id = $row['id'];

    $modify=<<<EOS
<a href="javascript:_modify('$id')">수정</a>
EOS;
    $delete =<<<EOS
<a href="javascript:_delete('$id')">삭제</a>
EOS;

    $title = $row['title'];
    $dc = _device_count($title);

    print<<<EOS
<tr>
<td>{$cnt}</td>

<td>{$title}</td>

<td>{$row['ord']}</td>
<td>{$row['url']}</td>
<td>{$dc}</td>
<td>$modify|$delete</td>
</tr>
EOS;
  }

  print<<<EOS
</table>

<script>
function _modify(id) {
  var url = "$self?mode=device&mode2=edit&id="+id;
  document.location = url;
}
function _delete(id) {
  if (!confirm('삭제할까요?')) return;
  var url = "$self?mode=device&mode2=delete&id="+id;
  document.location = url;
}
</script>
EOS;

  PageTail();
  exit;

?>
