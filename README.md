# audit-schedule

To set-up:
- composer install
- Connect to MySQL database
- symfony console doctrine:migrations:migrate
- symfony console doctrine:fixtures:load 
- symfony server:start
- Access the Swagger type documentation in the URI /api/doc