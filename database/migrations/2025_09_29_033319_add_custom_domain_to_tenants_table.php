<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Domínio customizado (ex: www.imobiliaria-cliente.com.br)
            // É nullable (nem todos terão) e unique (só pode ser usado por um tenant)
            $table->string('custom_domain')->unique()->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('custom_domain');
        });
    }
};
