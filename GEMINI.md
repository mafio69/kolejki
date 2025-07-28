# Projekt: System Kolejek Górskich

Jest to projekt API do zarządzania systemem kolejek górskich, oparty o specyfikację z pliku `documentation/Zadanie testowe - PHP Backend Developer.pdf`.

## Kluczowe informacje

*   **Cel:** Stworzenie API dla "Systemu kolejek górskich" do zarządzania kolejkami i wagonami.
*   **Technologie:**
    *   PHP 8.0+
    *   CodeIgniter 4
    *   Nginx z PHP-FPM
    *   Redis jako magazyn danych
    *   Docker Compose do konteneryzacji
*   **Struktura projektu:**
    *   `app/`: Główny kod aplikacji (prawdopodobnie CodeIgniter 4).
    *   `docker/`: Konfiguracja kontenerów Docker.
    *   `documentation/`: Dokumentacja projektu.
    *   `logs/`: Logi aplikacji.
    *   `.env`: Zmienne środowiskowe.
    *   `docker-compose.yml`: Plik do uruchamiania środowiska Docker.
*   **Główne zadania:**
    1.  Implementacja endpointów API zgodnie ze specyfikacją.
    2.  Stworzenie asynchronicznego serwisu monitorującego w PHP CLI.
    3.  Implementacja logiki biznesowej dotyczącej personelu, klientów i wydajności.

---

## Plan Wykonania Projektu

### Faza 0: Konfiguracja Środowiska (Ukończona)
- ✅ **Konfiguracja Docker Compose:** Rozdzielenie środowiska deweloperskiego i produkcyjnego.
- ✅ **Konfiguracja Logowania:** Dostosowanie poziomów logowania dla `dev` i `prod`.
- ✅ **Konfiguracja Xdebug:** Włączenie Xdebug tylko dla środowiska deweloperskiego.

### Faza 1: Implementacja Podstawowego API (Backend) (Ukończona)
- ✅ **Modelowanie Danych:** Zdefiniowanie klas/serwisów do interakcji z Redisem dla kolejek i wagonów.
- ✅ **Implementacja Kontrolerów API:** Stworzenie kontrolerów dla endpointów (`POST /coasters`, `POST /coasters/{id}/wagons`, etc.).
- ✅ **Routing:** Konfiguracja `app/Config/Routes.php`.
- ✅ **Walidacja Danych Wejściowych:** Dodanie walidacji dla żądań.
- ✅ **Refaktoryzacja (SOLID):** Zastosowanie wstrzykiwania zależności.

### Faza 2: Implementacja Logiki Biznesowej
1.  **Zarządzanie Personelem:** Obliczanie wymaganego i dostępnego personelu.
2.  **Zarządzanie Wydajnością:** Obliczanie zdolności obsługi klientów.
3.  **System Powiadomień:** Logowanie problemów (brak personelu, wagonów) jako `warning` lub `error`.

### Faza 3: Asynchroniczna Konsola Monitorująca (CLI)
1.  **Stworzenie Komendy CLI:** Użycie `php spark` do stworzenia komendy `monitor:status`.
2.  **Pobieranie Danych z Redis:** Łączenie z Redisem i pobieranie danych w czasie rzeczywistym.
3.  **Wyświetlanie Statystyk:** Formatowanie i cykliczne wyświetlanie danych w konsoli.

### Faza 4: Testy i Finalizacja
1.  **Testy Jednostkowe i Funkcjonalne:** Pisanie testów PHPUnit dla logiki i API.
2.  **Testy Integracyjne:** Manualne testowanie całego przepływu.
3.  **Dokumentacja:** Przygotowanie `README.md` z instrukcją uruchomienia.