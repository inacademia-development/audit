FROM ubuntu:18.04
MAINTAINER InAcademia Team, tech@inacademia.org

RUN apt  update && DEBIAN_FRONTEND=noninteractive apt-get install -y tzdata
RUN apt install -y \
    apache2 \
    libapache2-mod-php \
    curl \
    ca-certificates \
    php-mysql php-xml php-mbstring php-curl

#Copy over start script
COPY app/start.sh /tmp/inacademia/start.sh
RUN ln -s /tmp/inacademia/start.sh /root/start.sh

# Copy over https certificates
#COPY config/apache/stats.local.crt etc/ssl/certs/stats.local.crt
#COPY config/apache/stats.local.key etc/ssl/private/stats.local.key

# Set up Apache
#COPY config/apache/stats.local_ssl.conf /etc/apache2/sites-available/stats.local_ssl.conf
#RUN a2enmod ssl
#RUN a2enmod rewrite
#RUN a2enmod headers
#RUN a2enmod php7.2
#RUN a2dissite 000-default
#RUN a2ensite stats.local_ssl

# Setup simplesamlphp
RUN curl -L https://simplesamlphp.org/download?latest --output /tmp/simplesamlphp.tar.gz
RUN mkdir -p /var/simplesamlphp
RUN tar xvzfC /tmp/simplesamlphp.tar.gz /var/simplesamlphp --strip 1
RUN ln -s /var/simplesamlphp /root/simplesaml

# Copy over config for simplesamlphp
#COPY config/simplesamlphp/config.php /var/simplesamlphp/config/config.php
#COPY config/simplesamlphp/authsources.php /var/simplesamlphp/config/authsources.php
#COPY config/simplesamlphp/saml20-idp-remote.php /var/simplesamlphp/metadata/saml20-idp-remote.php
#COPY config/simplesamlphp/saml.pem /var/simplesamlphp/cert/saml.pem
#COPY config/simplesamlphp/saml.crt /var/simplesamlphp/cert/saml.crt

#ENTRYPOINT ["/tmp/inacademia/start.sh"]
ENTRYPOINT ["/bin/bash"]

