# FinPer

## Installation guide (development)

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
2. Run `docker-compose up --build`
3. Run `docker exec -it $(docker ps -n=-1 -q --filter name=finper-backend-www-1 --format="{{.ID}}") /bin/sh /var/www/finper/bin/bootstrap.sh`
6. Go to [http://localhost/](http://localhost/)

### Get into a Docker container's shell

```bash
docker exec -it finper-backend-www-1 /bin/bash
```

```bash
docker exec -it finper-backend-db-1 /bin/bash
```

### Run all tests

```bash
docker exec -it $(docker ps -n=-1 -q --filter name=finper-backend-www-1 --format="{{.ID}}") php bin/phpunit --configuration phpunit.xml.dist --testsuite ci
```

## Using API
You can use the Postman Collection in `/doc`

### Login and getToken

#### Request
    curl --location --request POST 'localhost/api/v1/auth/login' \
    --header 'Content-Type: application/json' \
    --data-raw '{
    "email": "demo@mailinator.com",
    "password": "demo"
    }'
#### Response
    {
    "id": 1,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoxLCJpYXQiOjE3NDEyMDg5NjksImV4cCI6MTc0MTIxOTIwMH0.nj1hK7Xv96Z9oU07FSUZb9n6jX5_sojzTV0s7V67vPVsYWgtTvVgZyfGSzJc2Dvhu9Rj7NzcLN0BNSioyKLLSA"
    }

### Example: List accounts

#### Request
    curl --location --request GET 'localhost/api/v1/accounts' \
    --header 'TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoxLCJpYXQiOjE3NDEyMDg5NjksImV4cCI6MTc0MTIxOTIwMH0.nj1hK7Xv96Z9oU07FSUZb9n6jX5_sojzTV0s7V67vPVsYWgtTvVgZyfGSzJc2Dvhu9Rj7NzcLN0BNSioyKLLSA'
#### Response
    [
        {
            "id": 2,
            "name": "Bank 2",
            "iban": "*5678"
        },
        {
            "id": 1,
            "name": "Bank 1",
            "iban": "*1234"
        }
    ]
