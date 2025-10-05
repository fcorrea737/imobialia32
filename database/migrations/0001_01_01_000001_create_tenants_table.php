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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id(); // ID interno, primário
            $table->uuid('uuid')->unique(); // Identificador público para APIs e URLs

            $table->string('name'); // Nome da Imobiliária
            $table->string('plan')->nullable(); // Para planos futuros (ex: basic, premium)

            // Endereço da Imobiliária
            $table->string('address_street')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_complement')->nullable();
            $table->string('address_neighborhood')->nullable(); // Bairro
            $table->string('address_city');
            $table->string('address_state', 2); // Sigla do estado, ex: SP
            $table->string('address_zipcode'); // CEP

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
