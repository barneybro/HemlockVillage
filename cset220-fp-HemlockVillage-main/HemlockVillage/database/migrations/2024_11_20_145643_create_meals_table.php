<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string("patient_id", 16);
            $table->date("meal_date");
            $table->enum("breakfast", ["Missing", "Pending", "Completed"])->default("Pending");
            $table->enum("lunch", ["Missing", "Pending", "Completed"])->default("Pending");
            $table->enum("dinner", ["Missing", "Pending", "Completed"])->default("Pending");
            $table->timestamps();

            $table->foreign("patient_id")
                ->references("id")
                ->on("patients")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            // Prevent duplicate records of patient id and meal date
            $table->unique([ "patient_id", "meal_date" ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
