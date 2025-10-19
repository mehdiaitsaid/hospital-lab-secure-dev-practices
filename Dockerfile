# Use official PHP with Apache
FROM php:8.2-apache

# Install necessary PHP extensions and cleanup
RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev && \
    docker-php-ext-install pdo pdo_mysql mysqli opcache intl zip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copy application source into the container
COPY ./ /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 for web access
EXPOSE 80