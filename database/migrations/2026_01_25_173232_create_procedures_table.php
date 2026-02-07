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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('patient_name');
            $table->string('bed_number');
            $table->string('procedure_name');
            $table->enum('status', ['pending', 'done', 'in progress'])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('added_by')->constrained('users');
            $table->foreignId('finished_by')->nullable()->constrained('users');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
