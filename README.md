# MRTS + MRTG + router statistics via UPnP

DIY bundle for getting router statistics via UPnP with MRTG and showing it with MRTS (or mrtg-rrd.cgi, 14all.cgi, routers2.cgi, mrtgrg.php).

mrtg.cfg is ready to use. I used `/var/www/html/mrtg/` for RRD files.

mrts.php was fixed to support PHP 8, but you might need to change `$dir`.

14all.cgi works (minor fixes were applied), but you might need to change `$cfgfile` and [mrtg.cfg](mrtg.cfg).

mrtg-rrd.cgi there is the fixed version, but you need to change `BEGIN { @config_files = qw(/etc/mrtg/mrtg.cfg); }`.

routers2.cgi works out of the box, just use the installer and provide correct answers.

mrtgrg.php works out of the box, but you need to change `$mrtgconfigfiles`.

Copy .cgi files to `/usr/lib/cgi-bin/` and copy *.php to `/var/www/html/mrtg/`. Default lighttpd WWW document root is `/var/www/html`.

```
sudo cp upnpc2mrtg.sh /usr/local/bin/upnpc2mrtg.sh
sudo chmod +x /usr/local/bin/upnpc2mrtg.sh
#sudo cp mrtg.cfg /etc/mtrg/
sudo apt install mrtg
sudo apt install rrdtool
sudo apt install lighttpd
sudo lighttpd-enable-mod cgi fastcgi-php
sudo systemctl force-reload lighttpd
#sudo apt install libcgi-pm-perl # for all Perl CGI scripts
#sudo apt install librrds-perl # for all Perl CGI scripts
#sudo apt install libgd-perl # for routers2.cgi
#sudo apt install php-gd php-rrd # for mrtgrg.php
sudo apt install php-cgi # for mrts.php
sudo usermod -a -G mrtg www-data
sudo chgrp www-data /var/www/html/mrtg/
sudo chmod g+w /var/www/html/mrtg/
```

> [!IMPORTANT]
> It looks like that UPnP uses 32-bit counter for NewTotalBytesReceived & NewTotalBytesSent, so you must adjust Interval to your network speed in order not to lose the statistics in case when you downloaded more than 4GB during Interval. If you downloaded less than 4GB but counter was reset because of overflow, but current counter's value is less than previous counter's value - then [MRTG will understand that](https://stackoverflow.com/questions/21019879/explain-me-a-difference-of-how-mrtg-measures-incoming-data). But if you downloaded more than 4GB and counter was reset and current counter's value is more than previous counter's value - then it is impossible to detect this situation, so you lose some statistics. Set Interval to "how fast you can download 4GB" in MM:SS format.

## References

Router statistics via UPnP: https://eva-quirinius.blogspot.com/2014/01/nice-you-can-use-upnp-to-read-amount-of.html

Original MRTS - MRTG RRDtool Total Statistics: http://apt-get.dk/mrts/

Fixed mrtg-rrd.cgi: https://github.com/frals/mrtg-rrd.cgi/

Original mrtg-rrd.cgi: https://www.fi.muni.cz/~kas/mrtg-rrd/

Original 14all.cgi: https://my14all.sourceforge.net/

Original routers2.cgi: https://github.com/sshipway/routers2/

Original mrtgrg.php: https://github.com/jcmichot/mrtgrg/

Original grapherrd (I was not able to make it work): https://github.com/laeti-tia/grapherrd/

## Screenshot

![MRTS Screenshot](MRTS%20Screenshot.png "MRTS Screenshot")
