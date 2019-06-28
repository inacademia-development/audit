FROM ubuntu:18.04
MAINTAINER InAcademia Team, tech@inacademia.org

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    php7.0 \
    php-fpm \
    php-mysql \
    curl \
    ca-certificates \
    php-mysql php-xml php-mbstring php-curl

#Copy over start script
COPY app/start.sh /tmp/inacademia/start.sh

#Copy over index.php application script
COPY app/index.php /var/www/html/

# Copy over NGINX config
COPY config/php.ini		/etc/php/7.0/fpm/php.ini
COPY config/nginx_default	/etc/nginx/sites-available/default

# Setup simplesamlphp
RUN curl -L https://simplesamlphp.org/download?latest --output /tmp/simplesamlphp.tar.gz
RUN mkdir -p /var/simplesamlphp
RUN tar xvzfC /tmp/simplesamlphp.tar.gz /var/simplesamlphp --strip 1

# Copy over config for simplesamlphp
COPY config/simplesamlphp/config.php /var/simplesamlphp/config/config.php
COPY config/simplesamlphp/authsources.php /var/simplesamlphp/config/authsources.php
COPY config/simplesamlphp/saml20-idp-remote.php /var/simplesamlphp/metadata/saml20-idp-remote.php
COPY config/simplesamlphp/saml.pem /var/simplesamlphp/cert/saml.pem
COPY config/simplesamlphp/saml.crt /var/simplesamlphp/cert/saml.crt

#ENTRYPOINT ["/tmp/inacademia/start.sh"]
ENTRYPOINT ["/bin/bash"]

