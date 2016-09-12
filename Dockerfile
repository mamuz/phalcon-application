FROM phalconphp/php:7

RUN apt-get -y install git

COPY . /phapp
WORKDIR /phapp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install
