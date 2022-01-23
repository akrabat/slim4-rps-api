FROM php:8.1-apache

RUN apt-get update \
  && apt-get install -y vim git libzip-dev \
  && docker-php-ext-install zip \
  && a2enmod rewrite \
  && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/apache2.conf \
  && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
  && rm -rf /var/www/html

RUN sed -i '/^# vim:/d' /etc/apache2/sites-available/000-default.conf \
  && sed -i '/^<\/VirtualHost>/d' /etc/apache2/sites-available/000-default.conf \
  && echo '\n\
        RewriteEngine On \n\
        RewriteCond %{REQUEST_FILENAME} -s [OR] \n\
        RewriteCond %{REQUEST_FILENAME} -l [OR] \n\
        RewriteCond %{REQUEST_FILENAME} -d \n\
        RewriteRule ^.*$ - [L] \n\
        RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$ \n\
        RewriteRule ^(.*) - [E=BASE:%1] \n\
        RewriteRule ^(.*)$ %{ENV:BASE}/index.php [L] \n\
\n\
        ErrorDocument 404 /404 \n\
\n\
</VirtualHost>\n' >> /etc/apache2/sites-available/000-default.conf


WORKDIR /var/www

