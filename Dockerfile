# Utiliser l'image officielle de PHP avec Apache
FROM php:8.1.6-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html

# Configurer les permissions pour les fichiers et répertoires
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Activer le module de réécriture d'URL d'Apache
RUN a2enmod rewrite

# Configurer Apache pour pointer vers le répertoire public et autoriser .htaccess
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog \${APACHE_LOG_DIR}/error.log\n\
    CustomLog \${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Ajouter la directive ServerName pour éviter les avertissements d'Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exposer le port 80 pour le conteneur
EXPOSE 80

# Commande pour démarrer Apache
CMD ["apache2-foreground"]
