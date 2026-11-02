FROM --platform=linux/amd64 php:8.4-cli

# Declaramos la imagen de Composer como una etapa para forzar la arquitectura
FROM --platform=linux/amd64 composer:2 AS composer_stage

FROM --platform=linux/amd64 php:8.4-cli

RUN apt-get update && apt-get install -y git unzip nodejs npm libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
