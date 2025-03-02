# FinPer

## Installation guide (development)

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
2. Run `docker-compose up --build`
3. Run `docker exec -it $(docker ps -n=-1 -q --filter name=finper-www-1 --format="{{.ID}}") /usr/bin/composer install -d /var/www/finper`
4. Run `docker exec -it $(docker ps -n=-1 -q --filter name=finper-www-1 --format="{{.ID}}") /usr/local/bin/php /var/www/finper/bin/console doctrine:migrations:migrate -n`
5. Run `docker exec -it $(docker ps -n=-1 -q --filter name=finper-www-1 --format="{{.ID}}") /usr/local/bin/php /var/www/finper/bin/console doctrine:fixtures:load  -n`
6. Go to [http://localhost/](http://localhost/)

### Get into a Docker container's shell

```bash
docker exec -it finper-www-1 /bin/bash
```

```bash
docker exec -it finper-db-1 /bin/bash
```


### Run all tests

```bash
docker exec -it $(docker ps -n=-1 -q --filter name=finper-www-1 --format="{{.ID}}") php bin/phpunit --configuration phpunit.xml.dist --testsuite ci
```