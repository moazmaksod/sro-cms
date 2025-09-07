# This file defines the instructions for building the custom PHP application container.
# It should be placed inside a folder named ".docker" in your project root.

# Use the official PHP 8.2 FPM image as a base to match project requirements
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
# - Node.js and npm for frontend assets
# - Packages for SQL Server drivers (ODBC)
# - Other utilities like git, zip, etc.
# - ADDED gnupg, lsb-release, and ca-certificates for key management
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    gnupg \
    lsb-release \
    ca-certificates

# Add Microsoft's official repository for SQL Server drivers using the new recommended method
RUN mkdir -p /etc/apt/keyrings
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /etc/apt/keyrings/microsoft.gpg
RUN echo "deb [arch=amd64,arm64,armhf signed-by=/etc/apt/keyrings/microsoft.gpg] https://packages.microsoft.com/debian/11/prod bullseye main" > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools

# Install PHP extensions
RUN apt-get install -y unixodbc-dev
RUN pecl install sqlsrv pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add the project directory as a system-wide safe directory for Git.
# This must be done as the root user before switching to www-data.
RUN git config --system --add safe.directory /var/www/html

# Change the owner of the workspace to the non-root user
RUN chown -R www-data:www-data /var/www/html

# Switch to the non-root user
USER www-data

# Expose port 9000 for PHP-FPM
EXPOSE 9000

