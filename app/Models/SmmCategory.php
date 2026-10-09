<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmmCategory extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(SmmService::class, 'category_id')->where('status', true);
    }

    public function allServices(): HasMany
    {
        return $this->hasMany(SmmService::class, 'category_id');
    }
}
