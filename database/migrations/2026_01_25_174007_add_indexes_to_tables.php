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
        Schema::table('pacs', function (Blueprint $table) {
            $table->index(['bed_number', 'created_at']);
        });
        Schema::table('procedures', function (Blueprint $table) {
            $table->index(['bed_number', 'created_at']);
        });
        Schema::table('post_op_requests', function (Blueprint $table) {
             $table->index(['bed_number', 'created_at']);
        });
        Schema::table('chronic_rounds', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
