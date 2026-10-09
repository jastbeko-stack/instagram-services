<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointConversion extends Model
{
    protected $fillable = [
        'user_id',
        'points_spent',
        'balance_credited',
    ];

    protected $casts = [
        'points_spent' => 'integer',
        'balance_credited' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
