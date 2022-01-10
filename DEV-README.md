# Development Setup

1. `composer install --ignore-platform-reqs` (
   or `docker run --rm --interactive --tty --volume $PWD:/app composer install --ignore-platform-reqs`)
2. `cp .env.example .env`
3. `./vendor/bin/sail up -d`
4. `./vendor/bin/sail artisan key:generate`
5. `./vendor/bin/sail artisan migrate && ./vendor/bin/sail artisan db:seed`
6. `cd resources/ui && npm install && npx quasar dev`. If this does not start on port 3000, you will need to update
   Sanctum. See `config/sanctum.php:18`. This should open a browser tab to the port it starts on, on localhost.

# Testing

A test user with these credentials and 10 products is included in the seeders:

```
username: test-user@example.com
password: password
```

You will be asked to login when you first bring up the site.

Only Vuex is used to store the authenticated user right now. So, refreshing will make the SPA think you've logged out.
