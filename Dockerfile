# Use PHP 8.1 official image
FROM php:8.1-apache

# Update package lists
RUN apt-get update

# Install PostgreSQL client and PHP extensions
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache2 modules
RUN a2enmod rewrite

# Copy the virtual host configuration
COPY recap.conf /etc/apache2/sites-available/recap.conf

# Enable the virtual host
RUN a2ensite recap.conf

# Set the working directory in the container
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies using Composer
RUN composer install --no-dev

# Expose port 80
EXPOSE 80

# Start Apache2
CMD ["apache2-foreground"]
