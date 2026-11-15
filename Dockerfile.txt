FROM php:8.2-apache

# I-install ang mga kinakailangang PHP extensions (tulad ng cURL para sa Firebase)
RUN docker-php-ext-install curl

# I-enable ang Apache Rewrite module (para sa magandang URL routing kung kailangan)
RUN a2enmod rewrite

# Kopyahin ang buong project files papunta sa Apache web directory ng container
COPY . /var/www/html/

# Baguhin ang permission para sa web server
RUN chown -R www-data:www-data /var/www/html

# I-expose ang port 80 para sa web traffic
EXPOSE 80