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
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();
            $table->date("date_assigned");
            $table->unsignedBigInteger("supervisor_id")->nullable();
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->unsignedBigInteger("caregiver_one_id")->nullable();
            $table->unsignedBigInteger("caregiver_two_id")->nullable();
            $table->unsignedBigInteger("caregiver_three_id")->nullable();
            $table->unsignedBigInteger("caregiver_four_id")->nullable();
            $table->timestamps();

            $table->foreign("supervisor_id")
                ->references("id")
                ->on("employees")
                ->onUpdate("cascade")
                ->onDelete("set null"); // Prevent deletion of record if employee deleted
            $table->foreign("doctor_id")
                ->references("id")
                ->on("employees")
                ->onUpdate("cascade")
                ->onDelete("set null"); // Prevent deletion of record if employee deleted
            $table->foreign("caregiver_one_id")
                ->references("id")
                ->on("employees")
                ->onUpdate("cascade")
                ->onDelete("set null"); // Prevent deletion of record if employee deleted
            $table->foreign("caregiver_two_id")
                ->references("id")
                ->on("employees")
                ->onUpdate("cascade")
                ->onDelete("set null"); // Prevent deletion of record if employee deleted
            $table->foreign("caregiver_three_id")
                ->references("id")
                ->on("employees")
                ->onUpdate("cascade")
                ->onDelete("set null"); // Prevent deletion of record if employee deleted
            $table->foreign("caregiver_four_id")
                ->references("id")
                ->on("employees")
                ->onUpdate("cascade")
                ->onDelete("set null"); // Prevent deletion of record if employee deleted

            // ALlow only one roster per date
            $table->unique("date_assigned");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rosters');
    }
};
