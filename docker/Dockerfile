FROM php:8.5-apache
MAINTAINER Piotr Krzewicki

#Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

#Instalacja pakietów systemowych i PHP
COPY docker-install-php.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-install-php.sh && /usr/local/bin/docker-install-php.sh

#Instalacja Xdebug
COPY docker-install-xdebug.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-install-xdebug.sh && /usr/local/bin/docker-install-xdebug.sh

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

RUN rm -f /etc/apache2/sites-enabled/000-default.conf

COPY my_project.conf /etc/apache2/sites-available/my_project.conf

RUN a2ensite my_project.conf

EXPOSE 80