# System Kolejek Górskich

Ten projekt implementuje API do zarządzania systemem kolejek górskich, zgodnie ze specyfikacją z pliku `documentation/Zadanie testowe - PHP Backend Developer.pdf`.

## Wymagania wstępne

Upewnij się, że masz zainstalowane:

*   Docker
*   Docker Compose

## Struktura projektu

*   `app/`: Główny kod aplikacji (CodeIgniter 4), zawierający również testy (`app/tests`).
*   `docker/`: Konfiguracja kontenerów Docker (Nginx, PHP, Redis, MySQL).
*   `documentation/`: Dokumentacja projektu, w tym oryginalna specyfikacja zadania.
*   `logs/`: Logi generowane przez aplikację i usługi.
*   `performance-tests/`: Skrypty do testów wydajnościowych (k6).

## Konfiguracja środowiska

Przed pierwszym uruchomieniem, skopiuj plik `example.env` do `.env` i dostosuj zmienne środowiskowe w razie potrzeby:

```bash
cp example.env .env
```

## Uruchomienie środowiska deweloperskiego

Aby uruchomić wszystkie usługi (PHP-FPM, Nginx, Redis):

```bash
docker-compose up -d
```

Usługi będą dostępne pod następującymi adresami:

*   **Nginx (aplikacja PHP):** http://localhost:8080
*   **Redis:** Dostępny wewnętrznie dla kontenerów Docker.
*   **MySQL:** Dostępny wewnętrznie dla kontenerów Docker.

## Dostępne endpointy API

### Kolejki górskie (`/api/coasters`)

*   `POST /api/coasters`: Tworzy nową kolejkę górską.
*   `GET /api/coasters/{id}/status`: Zwraca status i szczegóły kolejki górskiej.

### Wagony (`/api/coasters/{id}/wagons`)

*   `POST /api/coasters/{id}/wagons`: Dodaje nowy wagon do kolejki.
*   `DELETE /api/coasters/{id}/wagons/{wagonId}`: Usuwa wagon z kolejki.


## Uruchomienie komendy monitorującej

Komenda `monitor:status` asynchronicznie monitoruje i wyświetla status kolejek górskich. Aby ją uruchomić:

```bash
docker-compose exec coaster_php php spark monitor:status
```

**Uwaga:** Ta komenda działa w nieskończonej pętli i będzie wyświetlać aktualizacje co 5 sekund. Aby ją zatrzymać, naciśnij `Ctrl+C`.

## Testy

Aby uruchomić testy PHPUnit:

```bash
bash app/run-tests.sh
```

## Testy wydajnościowe (k6)

Aby uruchomić testy wydajnościowe za pomocą k6 z obrazu Docker:

```bash
docker run --network host -v $(pwd)/performance-tests:/app grafana/k6 run /app/load-test.js
```

**Uwaga:** `--network host` jest używane, aby k6 w kontenerze mógł komunikować się z aplikacją działającą na `localhost:8080` hosta. Upewnij się, że aplikacja jest uruchomiona przed wykonaniem testów k6.

