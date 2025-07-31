# System Kolejek Górskich

Ten projekt implementuje API do zarządzania systemem kolejek górskich, zgodnie ze specyfikacją z pliku `documentation/Zadanie testowe - PHP Backend Developer.pdf`.

## Wymagania wstępne

Upewnij się, że masz zainstalowane:

*   Docker
*   Docker Compose

## Uruchomienie środowiska deweloperskiego

Aby uruchomić wszystkie usługi (PHP-FPM, Nginx, Redis):

```bash
docker-compose up -d
```

Usługi będą dostępne pod następującymi adresami:

*   **Nginx (aplikacja PHP):** http://localhost:8080
*   **Redis:** Dostępny wewnętrznie dla kontenerów Docker.

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

