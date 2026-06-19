<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->string('key_lookup', 64)->nullable()->unique()->after('key');
        });

        DB::table('api_keys')
            ->where('key', '!=', '')
            ->orderBy('id')
            ->each(function ($apiKey) {
                DB::table('api_keys')
                    ->where('id', $apiKey->id)
                    ->update(['key_lookup' => hash('sha256', $apiKey->key)]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropIndex('api_keys_key_lookup_unique');
            $table->dropColumn('key_lookup');
        });
    }
};
