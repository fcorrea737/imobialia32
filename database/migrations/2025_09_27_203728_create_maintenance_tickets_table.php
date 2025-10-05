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
        Schema::create('maintenance_tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');

            // Quem abriu o chamado (geralmente o inquilino)
            $table->foreignId('requester_user_id')->constrained('users')->onDelete('cascade');

            // A quem o chamado foi atribuído (prestador)
            $table->foreignId('assigned_provider_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('title');
            $table->text('description');
            $table->string('status')->default('open'); // open, in_progress, awaiting_approval, completed, closed
            $table->string('priority')->default('low'); // low, medium, high

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tickets');
    }
};
