FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite headers

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY public_html/ /var/www/html/

# Install PHP dependencies if composer.json exists
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction; fi

# Create uploads directory
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# Configure Apache
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options -Indexes +FollowSymLinks\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \;

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
