FROM php:7.1.10-fpm

RUN apt update
RUN apt install -y nginx supervisor nano

COPY ./conf/nginx.conf /etc/nginx/sites-available/default
COPY ./conf/supervisor.conf /etc/supervisor.conf
COPY ./conf/fpm.conf /usr/local/etc/php-fpm.conf
COPY ./code/ /var/www/html/
COPY ./static /var/www/static/
RUN chown -R www-data:www-data /var/www/ && chmod -R 775 /var/www
RUN mkdir /var/log/php-fpm/ && mkdir /run/nginx

CMD ["supervisord", "-c", "/etc/supervisor.conf"]
