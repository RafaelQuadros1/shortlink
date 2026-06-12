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
        Schema::table('shorts', function (Blueprint $table) {
            $column = $table->string('short_code', 255)->nullable();
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $column->collation('utf8mb4_bin');
            }
            $column->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shorts', function (Blueprint $table) {
            $column = $table->string('short_code', 255)->nullable();
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $column->collation('utf8mb4_general_ci');
            }
            $column->change();
        });
    }
};
