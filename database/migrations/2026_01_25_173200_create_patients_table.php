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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('id_no');
            $table->longText('diagnosis');
            $table->string('bed_number')->index();
            $table->enum('type', ['chronic', 'post_op']);
            $table->boolean('is_discharged')->default(false);
            $table->foreignId('discharged_by')->nullable()->constrained('users');
            $table->timestamp('discharged_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
