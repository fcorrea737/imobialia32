<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, HasUuids;

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
        'name',
        'plan',
        'slug',
        'custom_domain',
        'address_street',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zipcode',
    ];

    /**
     * Usa 'uuid' para as rotas em vez do 'id'.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Um Tenant (imobiliária) tem muitos usuários.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Um Tenant tem muitas propriedades.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
