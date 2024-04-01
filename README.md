# audit-schedule

To set-up:
- composer install
- Create a MySQL schema called "audit-schedule"
- Connect to the MySQL schema in your .env file
- symfony console doctrine:migrations:migrate // to migrate the tables
- symfony console doctrine:fixtures:load // to populate the locations table
- symfony server:start
- Access the Swagger type documentation in the URI /api/doc