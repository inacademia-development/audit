#!/bin/bash

# Set https certificates
ln -s /tmp/inacademia/config/apache/stats.crt /etc/ssl/certs/stats.crt
ln -s /tmp/inacademia/config/apache/stats.key /etc/ssl/private/stats.key

# Set site config
ln -s /tmp/inacademia/config/apache/stats_ssl.conf /etc/apache2/sites-available/stats_ssl.conf
a2ensite stats_ssl

# config for simplesamlphp
#Remove default config
rm /var/simplesamlphp/config/config.php
rm /var/simplesamlphp/config/authsources.php
rm /var/simplesamlphp/metadata/saml20-idp-remote.php

# link in our config
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
