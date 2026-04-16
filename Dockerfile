FROM drupal:11-apache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /opt/drupal
RUN apt-get update && apt-get install -y git msmtp msmtp-mta \
   && echo "output_buffering=On" >> /usr/local/etc/php/php.ini \
   && echo "memory_limit=256M" >> /usr/local/etc/php/php.ini \
   && echo "account default" > /etc/msmtprc \
   && echo "host smtppro.zoho.eu" >> /etc/msmtprc \
   && echo "port 587" >> /etc/msmtprc \
   && echo "tls on" >> /etc/msmtprc \
   && echo "tls_starttls on" >> /etc/msmtprc \
   && echo "auth on" >> /etc/msmtprc \
   && echo "user info@wabb.co.uk" >> /etc/msmtprc \
   && echo "password Ge0rg1@1234#!" >> /etc/msmtprc \
   && echo "from info@wabb.co.uk" >> /etc/msmtprc
COPY composer.json composer.lock ./
COPY patches/ ./patches/
RUN composer install --no-dev --optimize-autoloader
COPY . .
RUN echo '<VirtualHost *:80>\n\
   ServerAdmin webmaster@localhost\n\
   DocumentRoot /opt/drupal/web\n\
   <Directory /opt/drupal/web>\n\
       AllowOverride All\n\
       Require all granted\n\
   </Directory>\n\
   RewriteEngine On\n\
   RewriteRule ^/service-worker\.js$ /js/web-push/service-worker [L,PT]\n\
   ErrorLog ${APACHE_LOG_DIR}/error.log\n\
   CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf \
   && a2enmod rewrite
RUN chown -R www-data:www-data /opt/drupal
EXPOSE 80
