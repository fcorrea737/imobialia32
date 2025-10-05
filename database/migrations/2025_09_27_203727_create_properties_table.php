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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');

            // --- ENDEREÇO (Já tínhamos) ---
            $table->string('address_street');
            $table->string('address_number');
            $table->string('address_complement')->nullable();
            $table->string('address_neighborhood');
            $table->string('address_city');
            $table->string('address_state', 2);
            $table->string('address_zipcode');

            // --- CLASSIFICAÇÃO E STATUS ---
            $table->string('listing_type')->default('for_rent'); // Tipo de anúncio: 'for_rent', 'for_sale', 'both'
            $table->string('property_type'); // Tipo do imóvel: 'apartment', 'house', 'commercial_room', etc.
            $table->string('status')->default('available'); // 'available', 'rented', 'sold', 'inactive'

            // --- DETALHES FINANCEIROS (Sua Sugestão) ---
            $table->decimal('sale_price', 12, 2)->nullable(); // Valor de Venda
            $table->decimal('rental_price', 10, 2)->nullable(); // Valor de Aluguel (proposto no anúncio)
            $table->decimal('condo_fee', 10, 2)->nullable(); // Taxa de Condomínio (mensal)
            $table->decimal('iptu_value', 10, 2)->nullable(); // Valor do IPTU (pode ser anual ou mensal, precisa definir a regra)
            $table->decimal('fire_insurance_value', 10, 2)->nullable(); // Seguro Incêndio
            $table->decimal('service_fee', 10, 2)->nullable(); // Taxa de Serviço

            // --- CARACTERÍSTICAS ESTRUTURAIS (Sua Sugestão) ---
            $table->integer('bedrooms')->default(0); // Quartos
            $table->integer('suites')->default(0); // Suítes
            $table->integer('bathrooms')->default(0); // Banheiros
            $table->integer('garage_spots')->default(0); // Vagas de Garagem
            $table->decimal('total_area_sqm', 8, 2)->nullable(); // Área Total em m²
            $table->decimal('usable_area_sqm', 8, 2)->nullable(); // Área Útil/Construída em m²
            $table->unsignedSmallInteger('construction_year')->nullable(); // Ano de construção do imóvel

            // --- OUTRAS CARACTERÍSTICAS (Sua Sugestão) ---
            $table->boolean('accepts_pets')->default(false); // Aceita pet?
            $table->boolean('has_storage_unit')->default(false); // Tem box/depósito na garagem?
            $table->text('description')->nullable(); // Descrição geral que já tínhamos
            $table->text('amenities')->nullable(); // Comodidades (ex: Piscina, Churrasqueira, Salão de Festas) - armazenado como texto ou JSON

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
