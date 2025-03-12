FROM php:8.2-fpm

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    libonig-dev \
    zip unzip git curl supervisor \
    imagemagick \
    libmagickwand-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath sockets \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*



COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u "$uid" -d /home/"$user" "$user" \
    && mkdir -p /home/"$user"/.composer \
    && chown -R "$user":"$user" /home/"$user"

WORKDIR /var/www

USER $user


CMD ["php-fpm"]