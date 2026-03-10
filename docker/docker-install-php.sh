#!/bin/bash
# 1. Aktualizacja i instalacja pakietów systemowych
apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nano \
    && rm -rf /var/lib/apt/lists/*

# 2. Konfiguracja i instalacja rozszerzeń PHP
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    bcmath \
    sockets \
    zip
