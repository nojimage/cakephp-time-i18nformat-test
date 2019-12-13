FROM centos:7

RUN yum install -y centos-release-scl \
    && yum install -y rh-php72-php-cli rh-php72-php-intl

RUN ln -s /opt/rh/rh-php72/root/usr/bin/php /usr/bin/php

RUN mkdir /data
ADD ./data /data

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && cd /data && composer install

WORKDIR /data
CMD [ "/opt/rh/rh-php72/root/usr/bin/php", "/data/test.php" ]
