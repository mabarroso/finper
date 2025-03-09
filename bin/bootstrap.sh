#!/bin/sh
/usr/bin/composer install -d /var/www/finper
/usr/local/bin/php /var/www/finper/bin/console doctrine:schema:drop --force --full-database -q
/usr/local/bin/php /var/www/finper/bin/console doctrine:migrations:migrate -n
/usr/local/bin/php /var/www/finper/bin/console doctrine:fixtures:load  -n
