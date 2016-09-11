FROM alpine:3.3

RUN apk add --no-cache bash curl git \
    php-cli php-curl php-json php-phar php-openssl php-dom php-ctype php-phalcon

COPY . /app
WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install

CMD [""]
