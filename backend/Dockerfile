# backend/Dockerfile
FROM php:8.1-cli

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Définir le dossier de travail
WORKDIR /app

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances Symfony
RUN composer install

# Exposer le port 8000 pour symfony server:start
EXPOSE 8000

# Commande pour démarrer Symfony
CMD ["symfony", "server:start", "--no-tls", "--port=8000", "--allow-http", "--disable-tls"]