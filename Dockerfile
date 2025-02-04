FROM php:8.2-cli
RUN pecl install xdebug
RUN apt-get update
RUN apt-get install -y libicu-dev zip unzip
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install intl
