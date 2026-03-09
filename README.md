# Symfony App - Setup Guide

Projekt oparty na frameworku Symfony, wykorzystujący Docker Compose do obsługi infrastruktury.

## 🚀 Szybki start

1.  **Pobranie najnowszych zmian:**
    git pull origin main

2.    **Przygotowanie pliku środowiskowego:**
    cp .env.dev .env

    **Upewnij się, że Twój plik `.env.local` posiada poprawne dane dostępowe:**
    DATABASE_URL=mysql://127.0.0.1:3306/main?sslmode=disable&charset=utf8mb4&serverVersion=8.0.45-1.el9
    REDIS_URL="redis://127.0.0.1:6379"

3.  **Uruchomienie infrastruktury:**
    docker compose up -d

4.  **Instalacja zależności PHP:**
    composer install

5.  **Aktualizacja bazy danych:**
    php bin/console doctrine:migrations:migrate --no-interaction

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