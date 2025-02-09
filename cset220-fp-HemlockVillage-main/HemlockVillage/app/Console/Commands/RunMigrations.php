<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filenames = [
            "2024_11_18_195627_create_roles_table.php",
            "0001_01_01_000000_create_users_table.php",
            "0001_01_01_000001_create_cache_table.php",
            "0001_01_01_000002_create_jobs_table.php",
            "2024_11_19_203321_create_patients_table.php",
            "2024_11_19_205527_create_employees_table.php",
            "2024_11_20_032232_create_appointments_table.php",
            "2024_11_20_144059_create_prescription_statuses_table.php",
            "2024_11_20_145643_create_meals_table.php",
            "2024_11_20_151908_create_rosters_table.php"
        ];

        $this->info("Running migrations:");

        if (count($filenames) < 1) $this->info("Nothing to migrate");

        foreach ($filenames as $f)
        {
            $this->info($f);
            Artisan::call("migrate", ["--path" => "database/migrations/$f"]);
        }
    }
}
