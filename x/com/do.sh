#!/bin/sh


# ���� mac �ּ� mac.sh ����
/www/com/wgate.php > /www/com/mac.sh

# traffic ó�� (firewall ����� Ʈ������ ���µǱ����� db�� �ݿ�)
/www/com/traffic.sh &> /www/com/traffic.sh.log

# ��ȭ�� ������Ʈ
sh /root/firewall

date >> /www/com/do.sh.run.log


