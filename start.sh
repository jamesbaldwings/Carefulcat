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
    DirectoryIndex index.php index.html
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>
    ErrorLog /dev/stderr
    CustomLog /dev/stdout combined
</VirtualHost>
EOF

# Disable default site and enable ours
a2dissite 000-default > /dev/null 2>&1 || true
a2ensite carefulcat > /dev/null 2>&1 || true

# Remove default Apache page so index.php is served
rm -f /var/www/html/index.html

# Ensure uploads directory exists and is writable.
# On Railway the container filesystem is ephemeral; mount a Volume at
# /var/www/html/uploads so uploaded photos survive redeploys. A freshly
# mounted volume is empty/owned by root, so we (re)create + chown it here.
mkdir -p /var/www/html/uploads
chown -R www-data:www-data /var/www/html/uploads
chmod 775 /var/www/html/uploads

# Ensure proper ownership
chown -R www-data:www-data /var/www/html

# Start Apache in foreground
exec apache2ctl -D FOREGROUND
