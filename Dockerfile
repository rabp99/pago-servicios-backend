FROM php:5.6-apache

RUN echo "deb http://archive.debian.org/debian stretch main" > /etc/apt/sources.list

# Instala las dependencias necesarias para la extensión intl
RUN apt-get update && \
    apt-get install -y \
    libxml2-dev \
    git \
    libicu-dev \
    g++ \
    unzip \
    zlib1g-dev \
    libmcrypt-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ... otras configuraciones que puedas necesitar ...
RUN docker-php-ext-install \
    zip \
    mcrypt \ 
    mbstring \
    intl \
    simplexml \
    mysqli \
    pdo_mysql

# Configura el documento raíz de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/webroot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilita el modulo de reescritura de Apache para CakePHP
RUN a2enmod rewrite