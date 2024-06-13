FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
curl \
wget \
git \
libfreetype6-dev \
libjpeg62-turbo-dev \
libxslt-dev \
libicu-dev \
libmcrypt-dev \
libpng-dev \
libxml2-dev \
libzip-dev \
zip \
unzip \
vim \
&& docker-php-ext-install -j$(nproc) iconv mysqli pdo_mysql zip \
&& docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
&& docker-php-ext-install -j$(nproc) gd \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl xsl soap
ARG NODE_VERSION=9.2

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get install -y nodejs
RUN apt-get update && apt-get install -y npm

RUN npm install -g npm
RUN npm i vite

ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www
EXPOSE 9000

CMD ["php-fpm"]
