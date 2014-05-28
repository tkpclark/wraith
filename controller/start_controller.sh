kill `ps -ef|grep controller.py|grep -v grep|awk '{print $2}'` 2&>1 /dev/null
kill -9 `ps -ef|grep controller.py|grep -v grep|awk '{print $2}'` 2&>1 /dev/null
nohup python /home/app/wraith/src/controller/controller.py 2&>1 /dev/null &
sleep 1
ps ax -H|grep controller.py|grep -v grep
