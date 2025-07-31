#!/bin/bash

# Script to run PHPUnit tests with proper Linux paths
# This avoids the "invalid working directory" error by using consistent path formats

# Change to the project directory (if running from elsewhere)
cd "$(dirname "$0")"

# Run the tests using Docker Compose with proper Linux paths
docker-compose run --rm php vendor/bin/phpunit --configuration phpunit.xml
