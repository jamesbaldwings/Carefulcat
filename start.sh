#!/bin/bash
set -e

# Use Railway's PORT or default to 80
PORT="${PORT:-80}"

echo "Starting Apache on port $PORT"

# Configure Apache to listen on the correct port
echo "Listen $PORT" > /etc/apache2/ports.conf

# Create VirtualHost config with the correct port
cat > /etc/apache2/sites-available/carefulcat.conf <<EOF
<VirtualHost *:$PORT>
    DocumentRoot /var/www/html
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>
    ErrorLog /dev/stderr
    CustomLog /dev/stdout combined
</VirtualHost>
EOF

# Enable our site
a2ensite carefulcat > /dev/null 2>&1 || true

# Ensure proper ownership
chown -R www-data:www-data /var/www/html

# Start Apache in foreground
exec apache2ctl -D FOREGROUND
