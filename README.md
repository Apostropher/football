# Football leagues

1. Clone the repository
2. Copy `.env.dist` to `.env`
3. Modify the `DATABASE_DRIVER` and `DATABASE_URL` paramaters inside the `.env` file
4. Execute `composer install`
5. Execute `bin/console doctrine:database:create`
6. Execute `bin/console doctrine:migration:migrate`
7. Execute `bin/console doctrine:fixtures:load`
8. Execute `bin/console server:start`
9. Generate a token: `curl -X POST -H "Content-Type: application/json" http://localhost:8000/tokens -d '{"name":"A-NAME"}'`
10. Use the token to make API requests to `http://localhost:8000/leagues`

## Tests

Behat: `bin/behat`

PHPSpec: `bin/phpspec --verbose run`