<?php

  print<<<EOS
<html>
<head>
</head>
<body>

<div style='text-align:center; border:0px solid #000; display:none'>
<table border='0' align='center'>
<form name='form' action='register.php' method='post'>
<tr>
<td align='center'>
무선인터넷 사용자로 등록이 되어 있지 않습니다.<br>
무선인터넷을 사용하기전 사용자 정보를 입력해 주세요.<br>
</td>
</tr>

<tr>
<td align='center'>MAC:$mac</td>
</tr>

<tr>
<td align='center'>
이름: <input type='text' name='name' size='20'>
</td>
</tr>

<tr>
<td align='center'>
연락처:<input type='text' name='phone' size='20' value='010-'>
</td>
</tr>

<tr>
<td align='center'>
고유번호: <input type='text' name='goyu' size='20'><br>
예)00310101-00001
</td>
</tr>

<tr>
<td align='center'>
소속:<select name='dept'>
<option value=''>::선택::</option>
<option value='교역자'>교역자</option>
<option value='자문회'>자문회</option>
<option value='장년회'>장년회</option>
<option value='부녀회'>부녀회</option>
<option value='청년회'>청년회</option>
</select>
</td>
</tr>

<tr>
<td align='center'>
<input type='hidden' name='mode' value='register'>
<input type='button' value='확인' onclick='sf_1()'>
</td>
</tr>

</form>
</table>
</div>

<script>
//31-05-13 페이지 로딩시 강제로 form submit
form.submit();
  
function sf_1() {
  var form = document.form;

  if (form.name.value == '') { alert('이름을 입력하세요'); form.name.focus(); return; }

  if (form.phone.value == '010-') { alert('연락처를 입력하세요'); form.phone.focus(); return; }

  if (form.goyu.value == '') { alert('고유번호를 입력하세요'); form.goyu.focus(); return; }

  if (form.dept.value == '') { alert('소속을 선택하세요'); form.dept.focus(); return; }

  form.submit();
}
</script>

</body>
</html>
EOS;


?>
