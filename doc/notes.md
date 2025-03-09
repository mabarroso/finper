# Notes

## Regenerate full database
```bash
php bin/console doctrine:schema:drop --force -q
php bin/console doctrine:migrations:migrate -n
php bin/console doctrine:fixtures:load  -n
```

## Regenerate full test database
```bash
APP_ENV=test php bin/console doctrine:schema:drop --force -q
APP_ENV=test php bin/console doctrine:migrations:migrate -n
APP_ENV=test php bin/console doctrine:fixtures:load  -n
```
