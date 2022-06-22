# Laravel Twitter Feeder App

## Installation Requirements
- Docker and docker-compose must be installed.
- The docker-compose.yml file can be adjusted as needed.
- To connect to the database, the host address must be **database**

## Setup
### .env Configuration

```
cp .env.example .env
```
Just set the below environment variables in your `.env`.

```
TWITTER_CONSUMER_KEY=
TWITTER_CONSUMER_SECRET=
TWITTER_ACCESS_TOKEN=
TWITTER_ACCESS_TOKEN_SECRET=
TWITTER_API_VERSION=
```

Build php twitter
```
docker-compose build twitter
```
Starts linked services
```
docker-compose up -d
```

System configuration
```
docker-compose exec twitter composer install
```
```
docker-compose exec twitter php artisan key:generate
```
```
docker-compose exec twitter php artisan jwt:secret
```
```
docker-compose exec twitter php artisan migrate --seed
```
```
docker-compose exec twitter php artisan storage:link
```

## Starting and Stopping the Service
Start service
```console
docker-compose up -d
```
Stop service
```console
docker-compose down
```

## Api Documentation
```
http://127.0.0.1:8000/docs
```

## Testing
```
docker-compose exec twitter ./vendor/bin/pest
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://raw.githubusercontent.com/Bariskau/Amazon-Location-Based-Cookie/main/LICENSE)

