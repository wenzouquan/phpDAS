#/bin/bash

count=`ps -fe |lsof -i :8091| wc -l`
echo $count
if [ $count -lt 1 ]; then
ps -eaf |grep "server.php" | grep -v "grep"| awk '{print $2}'|xargs kill -9
sleep 2
ulimit -c unlimited
php  /Volumes/UNTITLED/www/phpDAS/phpDAS_service/server.php
echo "restart";
echo $(date +%Y-%m-%d_%H:%M:%S) >/Volumes/UNTITLED/www/phpDAS/phpDAS_service/log/restart.log
fi