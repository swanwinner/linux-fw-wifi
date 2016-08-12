<?php

  include("path.php");
  include("$env[prefix]/inc/common.php");


  PageHead('공유기 접속');
  //AdminHeadline();
  //Menu();

// 공유기정보
$info = array(
array('192.168.10.2','http://localhost:8002','1','1층','건설부사무실'
     ,'00089FA1D82C','신31년 6월','N104T','AP-WPA,AP-WEB64', 'N104T', '1000'),

array('192.168.10.29','http://localhost:8031','2/44','2층','2층복도-1'
     ,'------------','신32년 9월','* A603(5G)','AP-WPA-5G', '', ''),
array('192.168.10.33','http://localhost:8032','5/157','2층','2층복도-2'
     ,'------------','신32년 9월','* A603(5G)','AP-WPA-5G', '', ''),
array('192.168.10.37','http://localhost:8034','9/40','2층','2층복도-3'
     ,'------------','신32년 9월','* A603(5G)','AP-WPA-5G', '', ''),


/*
array('192.168.10.4','http://localhost:8004','1','2층','교육2-1관'
     ,'0026662B22E8','신27년 10월','N704M','AP-WPA,AP-WEB64,TEST', '', '1000'),

array('192.168.10.3','http://localhost:8003','5','2층','교육2-3관'
     ,'0026662B42B8','신27년 10월','N704M','AP-WPA,AP-WEB64,TEST', '', '1000'),

array('192.168.10.5','http://localhost:8005','7','2층','시청각실'
     ,'0026662B3834','신27년 10월','N704M','AP-WPA,AP-WEB64,TEST', '', '1000'),
*/



array('192.168.10.28','http://localhost:8028','4/149','2층','정통부전산실'
     ,'0026662DC038','신31년','* A603','AP-WPA,AP-WEB64,TEST', '', '1000'),

array('192.168.10.20','http://localhost:8020','11','2층','(구)문화부실안'
     ,'002666A15952','신29년','N604A','AP-WPA,AP-WEB64', '','1000'),


array('192.168.10.36','http://localhost:8036','9','1층','로비천장'
     ,'0026662B41C4','신32년 10월','* A603','AP-WPA,AP-WEB64', '', '1000'),

array('192.168.10.35','http://localhost:8035','5','1층','1-1관'
     ,'0026662DC27C','신32년 10월','* A603','AP-WPA,AP-WEB64', '', '1000'),

array('192.168.10.30','http://localhost:8030','9','1층','1-3관'
     ,'0026662DB7C8','신32년 10월','* A603','AP-WPA,AP-WEB64', '', '1000'),

#array('192.168.10.19','http://localhost:8019','3','1층','건설부사무실'
#     ,'00089FD942A0','신29년','N504','AP-WPA,AP-WEB64', '', '1000'),


array('192.168.10.15','http://localhost:8015','1','지하','지하주차장'
     ,'0026662DC64C','신27년 12월','N704M','AP-WPA,AP-WEB64', '', '1000'),


array('192.168.10.7','http://localhost:8007','11','3층','하늘도서관'
     ,'0026662B37D0','신27년 10월','N704M','AP-WPA,AP-WEB64', '', '1000'),

array('192.168.10.21','http://localhost:8021','1','3층','3층방송실'
     ,'0026662278B5','신30년 6월','N6004M','AP-WPA,AP-WEB64', '', '1000'),

array('192.168.10.13','http://localhost:8013','9','3층','소성전뒤쪽중간'
     ,'0026662DCC9D','신30년 12월','N704M','AP-WPA,AP-WEB64', '', '1000'),

array('192.168.10.22','http://localhost:8022','13/36','3층','소성전뒤쪽오른쪽'
     ,'64E59934BA20/24','신30년 12월','N904(5G지원)','AP-WPA,AP-WEB64<br>AP-WPA-5G', '', '1000'),

array('192.168.10.23','http://localhost:8023','13/40','3층','소성전뒤쪽왼쪽'
     ,'64E5993447D8/DC','신30년 12월','N904(5G지원)','AP-WPA,AP-WEB64<br>AP-WPA-5G', '', '1000'),


array('192.168.10.31','http://localhost:8031','4/157','3층','3층소성전 앞쪽'
     ,'------------','신32년 9월','* A603(5G)','AP-WPA-5G', '', ''),
array('192.168.10.32','http://localhost:8032','10/44','3층','3층소성전 앞쪽'
     ,'------------','신32년 9월','* A603(5G)','AP-WPA-5G', '', ''),
array('192.168.10.34','http://localhost:8034','2/153','3층','3층소성전 앞쪽'
     ,'------------','신32년 9월','* A603(5G)','AP-WPA-5G', '', ''),

array('192.168.10.10','http://localhost:8010','9','5층','5층-1번'
     ,'0026662DC014','신27년 12월','N704M','AP-WPA,AP-WEB64'
     , "/cgi-bin/timepro.cgi?tmenu=wirelessconf&smenu=info", '1000'),

array('192.168.10.16','http://localhost:8016','5','5층','5층-2번'
     ,'0026662DC500','신27년 12월','N704M','AP-WPA,AP-WEB64'
     , "/cgi-bin/timepro.cgi?tmenu=wirelessconf&smenu=info", '1000'),


array('192.168.10.25','http://localhost:8025','13/48','2층','증축연결복도'
     ,'64E59934A114','신30년 12월','N904(5G)','AP-WPA,AP-WEB64<br>AP-WPA-5G', '', '1000'),


array('192.168.10.26','http://localhost:8026','11/149','3층','소성전국제부전용'
     ,'64E5990F281A','신31년 2월','N904NS(5G)','international', '', '1000'),

array('192.168.10.27','http://localhost:8027','11','5층','5-1교육관복도'
     ,'0026662B4624','신31년','N704M','AP-WPA,AP-WEB64', '', '1000'),

array('192.168.10.24','http://localhost:8024','11/149','2층','통합사무실신문고부'
     ,'64E599346EF4','신31년','N904(5G)','AP-WPA,AP-WEB64<br>AP-WPA-5G', '', '1000'),

array('192.168.10.19','http://localhost:8019','??','예비','예비'
     ,'64E599??????','신31년','N604M','AP-WPA,AP-WEB64', '', '1000'),


);
// print_r($info);

if ($mode == 'view') {

  $ip = $form['ip'];
  print<<<EOS

$ip
EOS;

  PageTail();
  exit;
}


  print<<<EOS
<style>
</style>

<table border='1' class='main' cellpadding='3' cellspacing='3'>
<tr>
<th nowrap>#</th>
<th nowrap>IP</th>
<th nowrap>접속</th>
<th nowrap>채널</th>
<th nowrap>층</th>
<th nowrap>공유기이름</th>
<th nowrap>무선MAC</th>
<th nowrap>설치일시</th>
<th nowrap>모델명</th>
<th nowrap>SSID</th>
<th nowrap>무선접속정보</th>
<th nowrap>설정1</th>
<th nowrap>조회</th>
</tr>
EOS;
  $cnt = 0;
  foreach ($info as $item) {
    $cnt++;
    //print_r($item);

    list($u1, $u2, $ch, $fl, $name, $mac, $date, $mo, $ssid, $u3, $set1) = $item;

    if ($u3) {
      $url_wireless = "$u2$u3"; // 무선접속 정보
      $wc = "<a href='$url_wireless' target='_blank'>무선접속정보</a>";
    } else $wc = '';

    $view =<<<EOS
<span class=link onclick="_view('$u1', this)">조회</span>
EOS;

    print<<<EOS
<tr>
<td nowrap>$cnt</td>
<td nowrap><a href='http://$u1' target='_blank'>$u1</a></td>
<td nowrap><span class=link onclick="viewlink('$u2',this)">$u2</span></td>
<td nowrap>$ch</td>
<td nowrap>$fl</td>
<td nowrap>$name</td>
<td nowrap>$mac</td>
<td nowrap>$date</td>
<td nowrap>$mo</td>
<td nowrap>$ssid</td>
<td nowrap>$wc</td>
<td nowrap>$set1</td>
<td nowrap>$view</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
<p>
설정1: 무선고급설정 - RTS Threshold 값 조정, Fragmentation Threshold 값 조정, Tx Burst 값을 중단으로 설정<br>
3층구성: 3층방송실 --> 뒤쪽중간(3개) --> 카메라있는곳(3개)<br>
</p>
<pre>
+-----------------------------------+
|             소성전                |
|                                   |
|             카메라                |
|          *    *    *              |
|                                   |
|   기둥                   기둥     |
|     O *       *          * O      |
|                                   |
+------+                            |
|      |                            |
|     입구                          |
|      +-----------------+          |
+------+     방송실    * +----------+
</pre>

<script>
function wopen(url, width, height, scrollbars, resizable) {
  option = "width="+width
          +",height="+height
          +",scrollbars="+scrollbars
          +",resizable="+resizable;
          //+",status="+status; 
  open(url, '', option);
}

function viewlink2(link) {
  var url = link;
  wopen(url, 1000,800,1,1);
}
function viewlink(link, span) {
  span.style.backgroundColor='#80FF00';
  var str = "viewlink2('"+link+"')";
  setTimeout(str, 300);
}
function _view(ip, span) {
  span.style.backgroundColor='#80FF00';
  setTimeout(function () {
    var url = "$env[self]?mode=view&ip="+ip;
    wopen(url, 1000,800,1,1);
  }, 300);
}
</script>
EOS;


  print<<<EOS
<br>
<a href='wifi-utf8.xsh'>xshell 접속 파일 다운로드</a><br>
EOS;

  PageTail();
  exit;

?>
