#!/bin/bash
# Instalacja narzędzi do kompilacji (wymagane przez pecl)
apt-get update && apt-get install -y $PHPIZE_DEPS

# Instalacja Xdebug przez PECL
pecl install xdebug

# Włączenie rozszerzenia w PHP
docker-php-ext-enable xdebug

# Czyszczenie po instalacji, aby obraz był mniejszy
apt-get purge -y $PHPIZE_DEPS
apt-get autoremove -y
rm -rf /var/lib/apt/lists/*
