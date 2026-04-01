# --- STAGE 1: BASE (Dependências comuns) ---
FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath gd opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/src

EXPOSE 9000

# --- STAGE 2: DEV (Xdebug + Ferramentas de Dev) ---
FROM base AS dev
RUN pecl install xdebug && docker-php-ext-enable xdebug
# Copie sua config de xdebug se tiver uma (ex: xdebug.mode=debug)
COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
USER root

# --- STAGE 3: TEST (Ambiente de CI/Testes) ---
FROM base AS test
ENV APP_ENV=testing
# Copiamos o código para o container de teste para ele ser autossuficiente
COPY ./src .
RUN composer install --no-interaction
# O comando padrão para este estágio
CMD ["php", "artisan", "test"]

# --- STAGE 4: PROD (Otimizado + Segurança) ---
FROM base AS prod
# Configurações de performance
COPY .docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copia código e instala sem dependências de dev
COPY ./src .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissões estritas para produção
RUN chown -R www-data:www-data /var/www/html/src/storage /var/www/html/src/bootstrap/cache
USER www-data