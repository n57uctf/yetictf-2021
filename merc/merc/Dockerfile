FROM php:7.3.3-apache
RUN apt-get update && apt-get upgrade -y && apt-get install -y sendmail
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
ADD . /var/www/html
RUN chown -R www-data:www-data /var/www/html             
RUN chmod -R g+rw /var/www/html
RUN service apache2 restart
COPY php.ini /usr/local/etc/php/
COPY entrypoint.sh /
ENTRYPOINT ["sh","/entrypoint.sh"]
EXPOSE 80
CMD ["apache2-foreground"]
