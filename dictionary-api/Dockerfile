FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /var/www

COPY . /var/www

CMD ["php-fpm"]
