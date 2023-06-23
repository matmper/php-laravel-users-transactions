FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    nano \
    vim \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install exif \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install zip
    #&& pecl install xdebug-3.1.1 \
    #&& docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf /var/www/html/*

WORKDIR /var/www/html

COPY . .

RUN composer install

EXPOSE 9000

EXPOSE 80

CMD ["php-fpm"]