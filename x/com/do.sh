#!/bin/sh


# 허용된 mac 주소 mac.sh 생성
/www/com/wgate.php > /www/com/mac.sh

# traffic 처리 (firewall 실행시 트래픽이 리셋되기전에 db에 반영)
/www/com/traffic.sh &> /www/com/traffic.sh.log

# 방화벽 업데이트
sh /root/firewall

date >> /www/com/do.sh.run.log


