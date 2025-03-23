FROM php:7.4-fpm

# Instala extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql session

# Garante que o diretório de sessões existe
RUN mkdir -p /var/lib/php/sessions && chmod -R 777 /var/lib/php/sessions

# Define o local das sessões no PHP-FPM
RUN echo "session.save_path = /var/lib/php/sessions" > /usr/local/etc/php/conf.d/session.ini
