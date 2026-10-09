<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmmProvider extends Model
{
    protected $fillable = [
        'name',
        'api_url',
        'api_key',
        'balance',
        'currency',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(SmmService::class, 'provider_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SmmOrder::class, 'provider_id');
    }
}
