#!/bin/sh

# arp 명령 실행 결과를 arp.txt 파일에 저장
file=/www/wifi/com/arp.txt

echo -n "UPDATE @" > $file
date +"%Y-%m-%d %H:%M:%S" >> $file
date >> $file

echo "/sbin/arp -n -i eth1" >> $file
/sbin/arp -n -i eth1 >> $file

sleep 1

# 허용된 mac 주소 mac.sh 생성
/www/com/wgate.php > /www/com/mac.sh

# traffic 처리 (firewall 실행시 트래픽이 리셋되기전에 db에 반영)
/www/com/traffic.sh &> /www/com/traffic.sh.log

# 방화벽 업데이트
/root/firewall

