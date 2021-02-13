# Baseball

## Usage

This predicates you have composer and a web server running on your machine.

Then clone this repository, cd into the root directory, and run `composer install`.

Create a mysql or pgsql database.

Copy env.example to .env and update:

* APP_Name to Baseball
* APP_KEY - the value of php artisan key:generate --show
* DB_CONNECTION to mysql or pgsql
* Set DB credentials
* Add GOOGLE_MAPS_API_KEY property and set it to your api key*

Run database migrate scripts - php artisan migrate

Update database with existing data - storage/backups/artifacts.sql 

For Google Maps go to  - https://developers.google.com/maps/gmp-get-started

