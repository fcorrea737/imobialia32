<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketUpdate extends Model
{
    use HasFactory, BelongsToTenant;

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Nota: Não usamos HasUuids aqui, pois geralmente acessamos as atualizações
     * através do seu chamado pai, e não diretamente por uma URL.
     */

    protected $fillable = [
        'ticket_id',
        'user_id',
        'update_type',
        'content',
        'metadata',
    ];

    /**
     * Converte a coluna 'metadata' (JSON) para um array PHP automaticamente.
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * A atualização pertence a um chamado (MaintenanceTicket).
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTicket::class, 'ticket_id');
    }

    /**
     * A atualização foi feita por um usuário (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
