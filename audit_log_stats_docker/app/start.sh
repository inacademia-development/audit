#!/bin/bash

# Copy over https certificates
rm /etc/ssl/certs/stats.local.crt
ln -s /tmp/inacademia/config/apache/stats.local.crt /etc/ssl/certs/stats.local.crt
rm /etc/ssl/private/stats.local.key
ln -s /tmp/inacademia/config/apache/stats.local.key /etc/ssl/private/stats.local.key

# Set up Apache
rm /etc/apache2/sites-available/stats.local_ssl.conf
ln -s /tmp/inacademia/config/apache/stats.local_ssl.conf /etc/apache2/sites-available/stats.local_ssl.conf
a2enmod ssl
a2enmod rewrite
a2enmod headers
a2enmod php7.2
a2dissite 000-default
a2ensite stats.local_ssl

# config for simplesamlphp
rm /var/simplesamlphp/config/config.php
rm /var/simplesamlphp/config/authsources.php
rm /var/simplesamlphp/metadata/saml20-idp-remote.php
rm /var/simplesamlphp/cert/saml.pem
rm /var/simplesamlphp/cert/saml.crt

ln -s /tmp/inacademia/config/simplesamlphp/config.php /var/simplesamlphp/config/config.php
ln -s /tmp/inacademia/config/simplesamlphp/authsources.php /var/simplesamlphp/config/authsources.php
ln -s /tmp/inacademia/config/simplesamlphp/saml20-idp-remote.php /var/simplesamlphp/metadata/saml20-idp-remote.php
ln -s /tmp/inacademia/config/simplesamlphp/saml.pem /var/simplesamlphp/cert/saml.pem
ln -s /tmp/inacademia/config/simplesamlphp/saml.crt /var/simplesamlphp/cert/saml.crt

# Run Apache:
/etc/init.d/apache2 start
/etc/init.d/apache2 status

echo "Showing apache error logs using tail....."
/usr/bin/tail -f /var/log/apache2/error.log
