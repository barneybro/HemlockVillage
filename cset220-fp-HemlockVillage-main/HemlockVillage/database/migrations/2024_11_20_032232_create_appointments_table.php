<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string("patient_id", 16);
            $table->date("date_scheduled");
            $table->date("appointment_date");
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->enum('status', ['Missing', 'Pending', 'Completed'])->default('Pending');
            $table->text("comment")->nullable();
            $table->text("morning")->nullable();
            $table->text("afternoon")->nullable();
            $table->text("night")->nullable();
            $table->timestamps();

            $table->foreign("patient_id")
                ->references("id")
                ->on("patients")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            $table->foreign("doctor_id")
                ->references("id")
                ->on("employees")
                ->onUpdate("cascade")
                ->onDelete("set null");

            // Prevent a patient from having multiple appointments for the same day
            $table->unique([ "patient_id", "appointment_date" ]);
        });

        // Ensure that the appointment date is after the day it was scheduled
        // DB::statement("ALTER TABLE appointments ADD CONSTRAINT CHECK (appointment_date > date_scheduled);");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
