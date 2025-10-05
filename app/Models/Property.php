<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;



class Property extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

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
        'tenant_id',
        'owner_id',
        'code',
        'address_street',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zipcode',
        'listing_type',
        'property_type',
        'status',
        'sale_price',
        'rental_price',
        'condo_fee',
        'iptu_value',
        'fire_insurance_value',
        'service_fee',
        'bedrooms',
        'suites',
        'bathrooms',
        'garage_spots',
        'total_area_sqm',
        'usable_area_sqm',
        'construction_year',
        'accepts_pets',
        'has_storage_unit',
        'description',
        'amenities',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'rental_price' => 'decimal:2',
        'condo_fee' => 'decimal:2',
        'iptu_value' => 'decimal:2',
        'accepts_pets' => 'boolean',
        'has_storage_unit' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Uma propriedade pertence a um Tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Uma propriedade pertence a um usuário (o proprietário).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Uma propriedade pode ter muitos contratos de aluguel.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
