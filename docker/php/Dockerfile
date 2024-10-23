FROM php:8.2-fpm-alpine3.17

RUN apk add --update nodejs npm yarn supervisor dcron

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions apcu intl opcache pcov pdo pdo_pgsql

RUN wget https://getcomposer.org/installer -O - | php -- --install-dir=/usr/local/bin --filename=composer

ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ADD crontab /etc/crontabs/root

RUN chmod 0644 /etc/crontabs/root
RUN touch /var/log/cron.log

ADD php.ini /usr/local/etc/php/php.ini
ADD php-fpm.conf /usr/local/etc/php-fpm.conf

WORKDIR /var/www/app

EXPOSE 9000

# CMD ["php-fpm", "-F", "-R"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
