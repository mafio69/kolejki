# Rozwiązywanie problemów z Composerem w kontenerze Docker

## Problem z autoloaderem

Jeśli pojawia się błąd:
```
Composer failed to update autoloader for ./app/composer.json.
Docker account not found
```

Można rozwiązać ten problem wykonując następujące kroki:

### 1. Napraw uprawnienia plików

W kontenerze Docker wykonaj:

```bash
docker-compose exec app composer fix-permissions
```

Alternatywnie, na hoście wykonaj:

```bash
chmod -R 755 app
find app -type f -exec chmod 644 {} \;
chmod +x app/spark app/run-tests.sh
if [ -d app/scripts ]; then chmod +x app/scripts/*.sh; fi
```

### 2. Przebuduj autoloader

```bash
docker-compose exec app composer dump-autoload --optimize
```

### 3. Sprawdź strukturę katalogów

Upewnij się, że struktura katalogów odpowiada konfiguracji w `composer.json` w sekcji `autoload.psr-4`.

### 4. Sprawdź konfigurację Dockera

Upewnij się, że użytkownik w kontenerze ma dostęp do zapisu w katalogu projektu:

```bash
docker-compose exec app id
```

Jeśli konieczne, zaktualizuj ustawienia użytkownika w `docker-compose.yml`.

## Inne typowe problemy

### Problem z zależnościami

Jeśli masz problemy z zależnościami, spróbuj:

```bash
docker-compose exec app composer update --with-all-dependencies
```

### Problemy z pamięcią PHP

Jeśli Composer wyczerpuje pamięć, zwiększ limit pamięci PHP:

```bash
docker-compose exec app php -d memory_limit=-1 /usr/local/bin/composer update
```
