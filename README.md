# MRTS + MRTG + router statistics via UPnP

DIY bundle for getting router statistics via UPnP with MRTG and showing it with MRTS (or mrtg-rrd.cgi, 14all.cgi, routers2.cgi).

mrtg.cfg is ready to use. I used `/var/www/html/mrtg` for RRD files.

mrts.php was fixed to support PHP 8 but you might need to change `$dir`.

14all.cgi has minor fixes but you might need to change `$cfgfile` and [mrtg.cfg](mrtg.cfg).

mrtg-rrd.cgi there is fixed version but you need to change `BEGIN { @config_files = qw(/etc/mrtg/mrtg.cfg); }`.

routers2.cgi works out of the box, just use the installer.

For .cgi files I used `/usr/lib/cgi-bin/` and WWW document root is `/var/www/html`.

```
sudo apt install mrtg
sudo apt install rrdtool
sudo apt install lighttpd
sudo lighttpd-enable-mod cgi fastcgi-php
sudo systemctl force-reload lighttpd
sudo apt install libcgi-pm-perl
sudo apt install librrds-perl # for mrtg-rrd.cgi
sudo apt install libgd-perl # for routers2.cgi
sudo apt install php-cgi # for mrts.php
sudo usermod -a -G mrtg www-data
sudo chgrp www-data /var/www/html/mrtg/
sudo chmod g+w /var/www/html/mrtg/
```

> [!IMPORTANT]
> It looks like that UPnP uses 32-bit counter for NewTotalBytesReceived & NewTotalBytesSent, so you must adjust Interval to your network speed in order not to lose the statistics. Set Interval = how fast you can download 4GB in MM:SS.

## References

Router statistics via UPnP: https://eva-quirinius.blogspot.com/2014/01/nice-you-can-use-upnp-to-read-amount-of.html

Original MRTS - MRTG RRDtool Total Statistics: http://apt-get.dk/mrts/

Fixed mrtg-rrd.cgi: https://github.com/frals/mrtg-rrd.cgi/tree/master

Original mrtg-rrd.cgi: https://www.fi.muni.cz/~kas/mrtg-rrd/

Original 14all.cgi: https://my14all.sourceforge.net/

Original routers2.cgi: https://github.com/sshipway/routers2

## Screenshot

![MRTS Screenshot](MRTS%20Screenshot.png "MRTS Screenshot")
