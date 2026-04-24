FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=America/Chicago

# Install Apache, PHP and required extensions
RUN apt-get update && apt-get install -y \
    apache2 \
    php8.1 \
    php8.1-mysql \
    php8.1-pdo \
    php8.1-gd \
    php8.1-zip \
    php8.1-curl \
    php8.1-mbstring \
    php8.1-xml \
    libapache2-mod-php8.1 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers php8.1

# Disable default site and configure our own
RUN a2dissite 000-default

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
</VirtualHost>\n' > /etc/apache2/sites-available/carefulcat.conf

RUN a2ensite carefulcat
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY public_html/ /var/www/html/

# Create uploads directory
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html

# Set proper permissions
RUN find /var/www/html -type f -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \;

# Railway uses dynamic PORT
EXPOSE 80
CMD bash -c "sed -i \"s/Listen 80/Listen \${PORT:-80}/g\" /etc/apache2/ports.conf && \
    sed -i \"s/*:80/*:\${PORT:-80}/g\" /etc/apache2/sites-available/carefulcat.conf && \
    apache2ctl -D FOREGROUND"
