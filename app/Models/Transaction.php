<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'deposit' => '<span class="px-2 py-0.5 text-xs rounded bg-emerald-500/10 text-emerald-400">إيداع USDT</span>',
            'username_purchase' => '<span class="px-2 py-0.5 text-xs rounded bg-indigo-500/10 text-indigo-400">شراء يوزر</span>',
            'smm_order' => '<span class="px-2 py-0.5 text-xs rounded bg-sky-500/10 text-sky-400">طلب متابعين</span>',
            'refund' => '<span class="px-2 py-0.5 text-xs rounded bg-amber-500/10 text-amber-400">استرجاع رصيد</span>',
            'admin_adjustment' => '<span class="px-2 py-0.5 text-xs rounded bg-purple-500/10 text-purple-400">تعديل إداري</span>',
            default => '<span class="px-2 py-0.5 text-xs rounded bg-gray-500/10 text-gray-400">' . $this->type . '</span>',
        };
    }
}
