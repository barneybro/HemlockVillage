<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class DropTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:drop-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all the tables in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = Config::get('database.connections.mysql.database');

        $tables = DB::select('SHOW TABLES');
        $s = "Tables_in_".$database;

        Schema::disableForeignKeyConstraints();

        foreach($tables as $t)
        {
            $this->info("Dropping table {$t->$s}"); // display in terminal

            Schema::drop($t->$s);
        }

        Schema::enableForeignKeyConstraints();
    }
}
