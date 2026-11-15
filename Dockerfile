FROM php:8.2-apache

# I-install ang mga kinakailangang system dependencies para sa cURL
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# I-install ang cURL PHP extension
RUN docker-php-ext-install curl

# I-enable ang Apache Rewrite module
RUN a2enmod rewrite

# Kopyahin ang buong project files papunta sa Apache web directory
COPY . /var/www/html/

# Baguhin ang permission para sa web server
RUN chown -R www-data:www-data /var/www/html

# I-expose ang port 80 para sa web traffic
EXPOSE 80
