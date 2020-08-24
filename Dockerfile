FROM wordpress:5.5.0-php7.4-apache
RUN pecl install -f xdebug \
&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini;
COPY ./php/uploads.ini /usr/local/etc/php/conf.d
ADD ./postman-smtp /var/www/html/wp-content/plugins/postman-smtp
