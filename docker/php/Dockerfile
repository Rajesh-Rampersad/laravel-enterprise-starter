FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    linux-headers \
    libpq-dev \
    postgresql-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    libxml2-dev \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath xml

# Install Redis extension
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del pcre-dev $PHPIZE_DEPS

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create non-root user context
RUN chown -R www-data:www-data /var/www/html

FROM base AS development
USER www-data
CMD ["php-fpm"]

FROM base AS production
COPY --chown=www-data:www-data . .
USER www-data
RUN composer install --no-dev --optimize-autoloader
CMD ["php-fpm"]
