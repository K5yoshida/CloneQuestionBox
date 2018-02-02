FROM php:7.1-apache

RUN apt-get update \
  && docker-php-ext-install pdo_mysql mysqli mbstring

RUN apt-get update \
  && apt-get install -y libmemcached-dev zlib1g-dev \
  && apt-get install -y vim git zip unzip

#install Imagemagick & PHP Imagick ext
RUN apt-get update \
      && apt-get install -y libmagickwand-dev --no-install-recommends

RUN pecl install imagick && docker-php-ext-enable imagick

ADD 000-default.conf /etc/apache2/sites-available
