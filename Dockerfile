FROM php:8.3-apache

RUN a2enmod ssl rewrite proxy proxy_http

# Copiar certificados para dentro do container
COPY ./docker/ssl/apache.crt /etc/apache2/ssl/apache.crt
COPY ./docker/ssl/apache.key /etc/apache2/ssl/apache.key

# Instala dependências do sistema e PHP
RUN apt-get update && \
    apt-get install -y \
    libzip-dev libxml2-dev zip unzip mariadb-client git \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev openssl \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli zip soap gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Configuração do Apache
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copiar código
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build assets frontend
RUN npm ci && npm run build

# Permissões Laravel
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80 443

CMD ["apache2-foreground"]
