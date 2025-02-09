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
        Schema::create('prescription_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("appointment_id");
            $table->date("prescription_date");
            $table->enum('morning', ['Missing', 'Pending', 'Completed'])->nullable();
            $table->enum('afternoon', ['Missing', 'Pending', 'Completed'])->nullable();
            $table->enum('night', ['Missing', 'Pending', 'Completed'])->nullable();
            $table->timestamps();

            $table->foreign("appointment_id")
                ->references("id")
                ->on("appointments")
                ->onUpdate("cascade")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_statuses');
    }
};
