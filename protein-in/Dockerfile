FROM php:8.3-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system deps
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev \
    default-mysql-client libzip-dev libgmp-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql bcmath gd zip gmp

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache config - point DocumentRoot to /app/public
ENV APACHE_DOCUMENT_ROOT=/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && echo '<Directory /app/public>\n    AllowOverride All\n    Require all granted\n</Directory>' > /etc/apache2/conf-available/proteinin.conf \
    && a2enconf proteinin

WORKDIR /app
