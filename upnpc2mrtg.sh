#!/bin/sh
upnpc -u http://192.168.0.1:80/RootDevice.xml -s | awk '
BEGIN { }
/uptime=/ { uptime = int(substr($4,8,100) / 3600) } 
/Found valid IGD/ { split($5, a, "/") ;  split(a[3], b, ":") ; igdip = b[1] }
/Bytes:/ { print $5 "\n" $3 }
END { print uptime " hours \nIGD at " igdip "\n" }
'
