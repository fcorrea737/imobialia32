<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('code')->after('uuid')->nullable(); // nullable temporariamente

            // Garante que o 'code' seja único para cada 'tenant_id'
            $table->unique(['tenant_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'code']);
            $table->dropColumn('code');
        });
    }
};
