FROM ubuntu:18.04
MAINTAINER InAcademia Team, tech@inacademia.org

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    php7.0 \
    php-fpm \
    php-mysql
RUN apt clean

#Copy over start script
COPY app/start.sh /tmp/inacademia/start.sh

#Copy over index.php application script
COPY app/index.php /var/www/html/

# Copy over NGINX config
COPY config/php.ini		/etc/php/7.0/fpm/php.ini
COPY config/nginx_default	/etc/nginx/sites-available/default

ENTRYPOINT ["/tmp/inacademia/start.sh"]
#ENTRYPOINT ["/bin/bash"]

