<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceTicket extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'property_id',
        'contract_id',
        'requester_user_id',
        'assigned_provider_id',
        'title',
        'description',
        'status',
        'priority',
    ];

    /**
     * Usa 'uuid' para as rotas em vez do 'id'.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * O chamado pertence a um imóvel (Property).
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * O chamado pode estar associado a um contrato (Contract).
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * O chamado foi aberto por um usuário (User).
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    /**
     * O chamado pode ser atribuído a um prestador (User).
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_provider_id');
    }

    /**
     * Um chamado tem muitas atualizações (comentários, status, etc.).
     */
    public function updates(): HasMany
    {
        return $this->hasMany(TicketUpdate::class, 'ticket_id');
    }
}
