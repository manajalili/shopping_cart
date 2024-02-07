FROM php:7.3

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libxml2-dev \
        libpng-dev \
    && docker-php-ext-install zip intl xml pdo_mysql gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-scripts --no-autoloader

RUN composer dump-autoload --optimize --no-dev

EXPOSE 8000

CMD ["php", "bin/console", "server:run", "0.0.0.0:8002"]