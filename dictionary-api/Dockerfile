# Escolhendo a imagem base do PHP com suporte ao FPM
FROM php:8.2-fpm

# Diretório de trabalho
WORKDIR /var/www

# Instalação de dependências básicas e extensões
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libssl-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    nano \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalação do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiando arquivos do projeto
COPY . /var/www

# Ajustando permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Instalando as dependências do Laravel
RUN composer install

# Gerando a documentação do Swagger
RUN php artisan l5-swagger:generate

# Expondo a porta padrão
EXPOSE 8000

# Comando para iniciar o PHP-FPM
CMD ["php-fpm"]
