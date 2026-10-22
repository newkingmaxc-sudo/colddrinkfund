FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd zip bcmath opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.save_comments=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

WORKDIR /var/www/html

COPY . .

RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN if [ ! -d "vendor" ]; then composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --ignore-platform-reqs; fi

RUN chmod -R 777 storage bootstrap/cache

ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "php artisan config:clear || true; php -S 0.0.0.0:${PORT:-10000} server.php"]
