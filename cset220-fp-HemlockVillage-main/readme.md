## php artisan commands:
* `php artisan custom:drop-tables`
	* Drop all the tables in the database
	* Command file location: `app/Console/Commands/DropTables.php`
* `php artisan custom:migrate`
	* Run migrations in a custom order, as defined in the command
	* Command file location: `app/Console/Commands/RunMigrations.php`
* `php artisan db:seed`
	* Run all the included seeders
	* File location: `database/seeders/DatabaseSeeder.php`
