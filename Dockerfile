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

# Fix Apache MPM conflict - disable ALL mpm modules then enable only prefork
RUN for mpm in mpm_event mpm_worker mpm_prefork; do \
        a2dismod $mpm 2>/dev/null || true; \
    done \
    && a2enmod mpm_prefork \
    && a2enmod rewrite headers

# Verify only one MPM is enabled
RUN ls /etc/apache2/mods-enabled/ | grep mpm

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY public_html/ /var/www/html/

# Create uploads directory
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# Configure Apache VirtualHost
RUN printf '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options -Indexes +FollowSymLinks\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>\n' > /etc/apache2/sites-available/000-default.conf

# Add ServerName to suppress warning
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \;

# Railway uses dynamic PORT - update Apache to listen on it
EXPOSE 80
CMD bash -c "sed -i \"s/Listen 80/Listen \${PORT:-80}/g\" /etc/apache2/ports.conf && \
    sed -i \"s/*:80/*:\${PORT:-80}/g\" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground"
