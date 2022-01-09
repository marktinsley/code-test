# Development Setup

1. `composer install`
2. `cp .env.example .env`
3. `./vendor/bin/sail up -d`
4. `./vendor/bin/sail artisan key:generate`
5. `./vendor/bin/sail artisan migrate && ./vendor/bin/sail artisan db:seed`
6. `cd resources/ui && npm install && npx quasar dev` -- (if this does not start on port 3000, you will need to update
   Sanctum. See `config/sanctum.php:18`)
