<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmmService extends Model
{
    protected $fillable = [
        'category_id',
        'name_ar',
        'name_en',
        'price_per_1k',
        'min_quantity',
        'max_quantity',
        'execution_type',
        'provider_id',
        'provider_service_id',
        'description',
        'status',
    ];

    protected $casts = [
        'price_per_1k' => 'decimal:4',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SmmCategory::class, 'category_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmmProvider::class, 'provider_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SmmOrder::class, 'service_id');
    }

    public function calculateCost(int $quantity): float
    {
        return round(($quantity / 1000) * (float)$this->price_per_1k, 4);
    }
}
