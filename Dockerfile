FROM ubuntu:latest

# Establece el modo no interactivo para la instalación de paquetes
ENV DEBIAN_FRONTEND noninteractive
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update && \
    apt-get install -y apache2 curl unzip wget cron && \
    apt-get clean

# Instala PHP 8.2 y extensiones comunes de PHP
RUN apt-get install -y software-properties-common && \
    add-apt-repository -y ppa:ondrej/php && \
    apt -qq -y install php8.2

RUN apt-get install -y libapache2-mod-php8.2 \
    php8.2-mysql \
    php8.2-xml \
    php8.2-gd \
    php8.2-mbstring \
    php8.2-bcmath \
    php8.2-cli \
    php8.2-zip \
    php8.2-curl \
    php8.2-intl \
    php8.2-mongodb \
    php8.2-apcu

# Instala Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Instalar node y npm
RUN apt install -y nodejs npm

#Configuracion de apache para Symfony
COPY image_management.conf /etc/apache2/sites-available

RUN a2dissite 000-default && \
    a2ensite image_management

WORKDIR /var/www/html/image_management

COPY . /var/www/html/image_management

RUN a2enmod rewrite

CMD bash -c "/usr/sbin/apache2ctl -D FOREGROUND"