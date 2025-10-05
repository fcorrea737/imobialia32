<?php

namespace App\Traits;

use App\Models\Property; // <-- Importante adicionar
use App\Models\Tenant;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    protected static function booted(): void
    {
        // Aplica o escopo global para filtrar automaticamente por tenant_id
        static::addGlobalScope(new TenantScope);

        // Define o tenant_id e o código único automaticamente ao criar um novo registro
        static::creating(function ($model) {
            if (Auth::check() && empty($model->tenant_id)) {
                $model->tenant_id = Auth::user()->tenant_id;
            }

            // --- LÓGICA DO CÓDIGO ADICIONADA AQUI ---
            // Verifica se o modelo tem a propriedade 'code' e a gera se estiver vazia
           if (empty($model->code)) {
                $model->code = self::generateUniqueCodeForTenant($model);
            }
            // --- FIM DA LÓGICA DO CÓDIGO ---
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // --- FUNÇÃO AUXILIAR COPIADA PARA CÁ ---
    private static function generateUniqueCodeForTenant(Model $model): string
    {
        $tenant = $model->tenant
            ?? (isset($model->tenant_id) ? Tenant::find($model->tenant_id) : (Auth::check() ? Auth::user()->tenant : null));

        if (!$tenant || empty($tenant->slug)) {
            throw new \Exception('Tenant ou slug do tenant não encontrado para geração do código.');
        }

        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $tenant->slug), 0, 3));

        $lastCode = Property::withTrashed()
            ->where('tenant_id', $tenant->id)
            ->where('code', 'like', "{$prefix}-%")
            ->orderByDesc('code')
            ->value('code');

        if ($lastCode) {
            $lastNumber = (int)substr($lastCode, strlen($prefix) + 1);
            $nextId = $lastNumber + 1;
        } else {
            $nextId = 1;
        }

        $paddedId = str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return "{$prefix}-{$paddedId}";
    }
}
