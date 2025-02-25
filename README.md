# FinPer

## Installation guide (development)

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
2. Run `cp .env_example .env`
3. Run `docker-compose up --build`
4. Go to [http://localhost/](http://localhost/)

### Get into a Docker container's shell

```bash
docker exec -it finper-www-1 /bin/bash
```

```bash
docker exec -it finper-db-1 /bin/bash
```

