# Symfony App - Setup Guide

Projekt do obliczania zniżek w sklepie internetowym, oparty na frameworku Symfony. Wykorzystujący Docker Compose do obsługi infrastruktury.

## 📋 Wymagania systemowe

Wymagania:
*   **Docker** & **Docker Compose** (wersja v2 lub nowsza)
*   **Git**

1.  **Pobranie repo:**
    git clone https://github.com/azigazi521-coder/discount-generator.git .
    cd discount-generator

2.    **Przygotowanie pliku środowiskowego:**
    cp .env.dev .env

    **Upewnij się, że Twój plik .env zawiera poprawne dane dostępowe:**
    APP_ENV=dev
    DATABASE_URL="mysql://root:@database:3306/main?serverVersion=8.0.32&charset=utf8mb4"
    REDIS_URL="redis://redis:6379"

3.  **Start kontenerów (Docker Compose):**
    docker compose up -d --build

4.  **Instalacja zależności PHP (Composer) w kontenerze web:**
    docker compose exec web composer install

5.  **Migracje bazy danych (Doctrine Migrations):**
    **Najpierw (opcjonalnie) utwórz bazę, jeśli jeszcze nie istnieje:**
    docker compose exec web php bin/console doctrine:database:create
    **Następnie uruchom migracje:**
    docker compose exec web php bin/console doctrine:migrations:migrate --no-interaction

6.  **Wypełnienie bazy danych przykładowymi danymi:**
    docker compose exec web php bin/console app:reset-db

7.  **(Opcjonalnie) Czyszczenie Cache:**
    docker compose exec web php bin/console cache:clear

8.  **Po starcie:**
    aplikacja: http://localhost:8000
    phpMyAdmin: http://localhost:8080
    MySQL: localhost:3306
    Redis: localhost:6379

9.  **Endpoint**
    URL: http://localhost:8000/products/1/lowest-price
    Metoda: POST
    Body: JSON
    Kod trasy w projekcie: src/Controller/ProductsController.php

    **Przykład wywołania curl w PowerShell**
  $body = @'
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
'@
Invoke-RestMethod `
  -Method Post `
  -Uri "http://localhost:8000/products/1/lowest-price" `
  -ContentType "application/json" `
  -Body $body

10.  **przykład endpoint w pliku postmana:**
    Symfony6-microservice.postman_collection.json 

11 💡 **Przydatne komendy**

   `docker compose stop` – zatrzymanie usług.
   `docker compose ps` – status działających kontenerów.
   `php bin/console cache:clear` – czyszczenie cache Symfony.