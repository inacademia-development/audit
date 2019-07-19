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

#Setup apache - Site specific config is done with start script
RUN a2enmod ssl
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod php7.2
RUN a2dissite 000-default

# Setup simplesamlphp
RUN curl -L https://simplesamlphp.org/download?latest --output /tmp/simplesamlphp.tar.gz
RUN mkdir -p /var/simplesamlphp
RUN tar xvzfC /tmp/simplesamlphp.tar.gz /var/simplesamlphp --strip 1
RUN ln -s /var/simplesamlphp /root/simplesaml

ENTRYPOINT ["/tmp/inacademia/start.sh"]
#ENTRYPOINT ["/bin/bash"]

