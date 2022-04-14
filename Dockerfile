FROM php:8-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Copy existing application directory contents to the working directory
COPY . /var/www
 
# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    #zip \
    #unzip \
    #jpegoptim optipng pngquant gifsicle \
    vim \
    git \
    libonig-dev \
    curl
 
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
 
# Install extensions for php
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
 
# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user \
    && chown -R $user:www-data /var/www

# Expose ports
EXPOSE 9000
EXPOSE 80
EXPOSE 3306

# Set working directory
WORKDIR /var/www

USER $user

CMD ["php-fpm"]