FROM php:8.3-fpm

# Instalar dependências necessárias
RUN apt-get update && apt-get install -y \
    git \
    libssl-dev \
    pkg-config
#RUN pecl install mongodb 
RUN docker-php-ext-enable mongodb 
RUN apt-get clean

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar o código da aplicação para o contêiner
COPY . /var/www

# Instalar dependências do Laravel
RUN composer install

# Adicionar dependência do Laravel para MongoDB
RUN composer require jenssegers/mongodb

RUN composer clear-cache

# Configuração de permissões
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# Rodar comandos do Laravel (caso necessário)
RUN php artisan config:cache

EXPOSE 9000
CMD ["php-fpm"]
