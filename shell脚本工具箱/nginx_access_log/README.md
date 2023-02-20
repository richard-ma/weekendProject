# web服务日志分析

## 性能测试
1. 查看进程数
    * `ps aux | grep 'httpd' | wc -l`
1. 最近一周的请求数（每天）
    * `cat access_log | grep '15/Jun/2019' | awk '{print $2}' | sort | uniq -c`
1. 某天指定IP访问url情况
    * `cat access_log | grep '15/Jun/2019' | grep '192.168.31.2' | awk '{print $7}' | sort | uniq -c | sort -nr`
1. 每天访问前10的url
    * `cat access_log | grep '15/Jun/2019' | awk '{print $7}' | sort | uniq -c | sort -nr | head -n 10`
1. 访问次数最多的时间点
    * `awk '{print $4}' access_log | cut -c 14-18 | sort | uniq -c | sort -nr | head`
