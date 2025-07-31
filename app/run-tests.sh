#!/bin/bash

# Script to run PHPUnit tests with proper Linux paths
# This avoids the "invalid working directory" error by using consistent path formats

# Run the tests using Docker Compose with proper Linux paths
docker-compose run php /var/www/html/vendor/bin/phpunit --configuration /var/www/html/phpunit.xml "/var/www/html/$@"

# Exit with the same status code as the test command
exit $?