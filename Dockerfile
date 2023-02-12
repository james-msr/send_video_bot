FROM php:8.1-fpm

#ARG USE_C_PROTOBUF=true

RUN set -x \
# create nginx user/group first, to be consistent throughout docker variants
    && addgroup --system --gid 101 nginx \
    && adduser --system --ingroup nginx --no-create-home --home /nonexistent --gecos "nginx user" --shell /bin/false --uid 101 nginx \
    && apt update \
    && apt install --no-install-recommends --no-install-suggests -y gnupg1 ca-certificates \
    && apt -y update \
    && apt -y install nginx \
# forward request and error logs to docker log collector
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# All things PHP
RUN apt update \
	&& apt install -y --fix-missing \
        openssh-client \
        sshpass \
        git \
        vim \
        zlib1g-dev \
        libicu-dev \
		libpng-dev \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libmcrypt4 \
		libmcrypt-dev \
        libzip-dev \
        gnupg2 \
        apt-utils \
	&& apt clean all \
	&& apt autoremove -y

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN rm -f /etc/apt/preferences.d/no-debian-php && \
    apt update && apt install -y --fix-missing \
        libwebp-dev \
        libmcrypt-dev \
        libjpeg-dev \
        libxpm-dev \
        libxml2-dev \
        php-soap \
        libc-client-dev \
        libkrb5-dev \
        libonig-dev

RUN docker-php-ext-configure \
    imap \
    --with-kerberos \
    --with-imap-ssl \
    && docker-php-ext-install \
        gd \
        intl \
        zip \
        exif \
        gd \
        mbstring \
        imap \
        bcmath \
        opcache \
        soap \
        iconv \
        pdo \
        pdo_mysql \
        mysqli \
    && docker-php-ext-configure gd \
        --with-webp \
        --with-jpeg \
        --with-xpm \
        --with-freetype \
    && docker-php-ext-enable opcache gd

USER root

RUN echo "deb http://security.debian.org/debian-security jessie/updates main" >> /etc/apt/sources.list
RUN apt update -y && apt install -y --no-install-recommends libssl1.0.0

# All things nginx
RUN apt update \
    && apt install -y \
        libssl-dev \
        openssl \
    && apt clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN apt update  -y

COPY deploy/nginx/bot.conf /etc/nginx/conf.d/default.conf
COPY deploy/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./ /var/www/domains/send_video_bot
COPY deploy/php/php.ini /usr/local/etc/php/conf.d/app.ini

COPY ./deploy/entrypoint.sh /entrypoint.sh

RUN chmod -R 777 /var/www/domains/send_video_bot/storage/
RUN chown -R nginx:nginx /var/www/domains/

WORKDIR /var/www/domains/send_video_bot

# Expose the port nginx is reachable on
EXPOSE 80

RUN ["chmod", "+x", "/entrypoint.sh"]
CMD ["/entrypoint.sh"]
