# How to Run

## Preparations

1. Start the services: `docker-compose up -d`
2. Open a bash shell in the backend service: `bash docker-compose exec backend bash`
3. Install PHP dependencies: `composer install`
4. Run database migrations: `php artisan migrate`
5. Generate IDE helper files: 
```
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
```

## execute command example:
`php artisan parse:xml centers_techexpert.xml`
