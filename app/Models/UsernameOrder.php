<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsernameOrder extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'instagram_username_id',
        'price',
        'delivery_details',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function username(): BelongsTo
    {
        return $this->belongsTo(InstagramUsername::class, 'instagram_username_id');
    }
}
