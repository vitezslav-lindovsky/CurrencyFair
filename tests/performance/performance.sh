#yop, simple perf test
ab -p json.txt -T application/json -c 100 -t 30 http://localhost/cf/consumer.php
