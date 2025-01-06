FROM php:8.4 AS php-base

ENV APP_NAME=Calc
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

RUN apt-get update -y \
    && apt-get install -y libonig-dev openssl zip unzip git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install bcmath mbstring \
    && rm -rf /var/lib/apt/lists/*


FROM php-base AS composer

COPY composer.json composer.lock /app/

RUN composer install --no-plugins --no-scripts


FROM node:23 AS vite

ENV VITE_APP_NAME=Calc

WORKDIR /app

COPY package.json package-lock.json /app/

RUN npm ci

COPY --from=composer /app/vendor /app/vendor

COPY . .

RUN npm run build

FROM php-base AS runtime

ENV APP_URL=http://localhost:8080

EXPOSE 8080

COPY . .

COPY --from=composer /app/vendor /app/vendor

RUN composer dump-autoload --classmap-authoritative \
    && touch database.sqlite \
    && php artisan migrate --force

COPY --from=vite /app/public/build /app/public/build

CMD ["sh", "/app/.docker/entrypoint.sh"]
