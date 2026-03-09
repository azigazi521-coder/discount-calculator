# Symfony App - Setup Guide

Projekt do obliczania zniżek w sklepie internetowym, oparty na frameworku Symfony. Wykorzystujący Docker Compose do obsługi infrastruktury.

## 📋 Wymagania systemowe

Zanim zaczniesz, upewnij się, że masz zainstalowane:
*   **Docker** & **Docker Compose** (wersja v2 lub nowsza)
*   **PHP 8.1+**
*   **Composer**
*   **Git**

## 🚀 Szybki start

1.  **Pobranie repo:**
    git clone https://github.com/azigazi521-coder/discount-generator.git .

2.    **Przygotowanie pliku środowiskowego:**
    cp .env.dev .env

    **Upewnij się, że Twój plik .env zawiera poprawne dane dostępowe:**
    DATABASE_URL=mysql://127.0.0.1:3306/main?sslmode=disable&charset=utf8mb4&serverVersion=8.0.45-1.el9
    REDIS_URL="redis://127.0.0.1:6379"

3.  **Uruchomienie infrastruktury:**
    docker compose up -d

4.  **Instalacja zależności PHP:**
    composer install

5.  **Wykonanie migracji:**
    php bin/console doctrine:migrations:migrate --no-interaction

6.  **Wypełnienie bazy danych:**
    php bin/console app:reset-db

7.  **Uruchomienie serwera:**
    php bin/console server:start

8.  **Endpointy w pliku postmana:**
    Symfony6-microservice.postman_collection.json 
    **np url:**
    http://localhost:8000/products/1/lowest-price
    **dane (Body - raw)**
    {
    "quantity": 5,
    "request_location": "UK",
    "voucher_code": "OU812",
    "request_date": "2026-03-12",
    "product_id": 1,
    "price": 100,
    "discounted_price": 88,
    "promotion_id": 3,
    "promotion_name": "Spring Sale"
    }

## 🛠 Usługi i porty

| Usługa | Adres / Port |
| :--- | :--- |
| **Baza danych (MySQL)** | `127.0.0.1:3306` |
| **phpMyAdmin** | [http://localhost:8080](http://localhost:8080) |
| **Redis** | `127.0.0.1:6379` |

## 💡 Przydatne komendy

*   `docker compose stop` – zatrzymanie usług.
*   `docker compose ps` – status działających kontenerów.
*   `php bin/console cache:clear` – czyszczenie cache Symfony.