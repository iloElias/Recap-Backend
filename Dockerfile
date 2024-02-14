FROM php:8.1-apache

RUN apt-get update && apt-get install -y libxml2-dev autoconf automake libpq-dev libonig-dev libzip-dev libcurl4-openssl-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev


# RUN docker-php-ext-install xml
RUN docker-php-ext-install dom
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install curl
RUN docker-php-ext-install gd

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# XDEBUG
RUN pecl install -o -f xdebug \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable xdebug

RUN echo "zend_extension = xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "XDEBUG_SESSION=Kleber" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.var_display_max_children = 128" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


RUN apt-get install -y wget libzip-dev

RUN docker-php-ext-install zip

# COMPOSER
RUN wget https://getcomposer.org/composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod -R 755 /usr/local/bin/composer


# VIRTUAL-HOST
COPY virtualhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN service apache2 restart

EXPOSE 80