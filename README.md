# FinPer

## Installation guide (development)

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
2. Run `docker-compose up --build`
3. Rum `docker exec -it finper-www-1 "/usr/bin/composer" "install"`
4. Go to [http://localhost/](http://localhost/)

### Get into a Docker container's shell

```bash
docker exec -it finper-www-1 /bin/bash
```

```bash
docker exec -it finper-db-1 /bin/bash
```


### Run all tests

```bash
docker exec -it finper-www-1 "/usr/local/bin/php bin/phpunit --configuration phpunit.xml.dist --testsuite ci"
```