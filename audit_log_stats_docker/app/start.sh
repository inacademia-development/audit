#!/bin/bash

/etc/init.d/php7.2-fpm start
/etc/init.d/php7.2-fpm status

/etc/init.d/nginx start
/etc/init.d/nginx status

echo "Showing nginx error logs using tail....."
/usr/bin/tail -f /var/log/nginx/error.log
