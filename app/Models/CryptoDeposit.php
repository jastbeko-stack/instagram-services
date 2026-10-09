<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoDeposit extends Model
{
    protected $fillable = [
        'deposit_code',
        'user_id',
        'crypto_currency',
        'network',
        'wallet_address',
        'amount_usd',
        'txid',
        'proof_image',
        'status',
        'admin_notes',
        'approved_at',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">قيد المراجعة</span>',
            'approved' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">مقبول ومضاف</span>',
            'rejected' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">مرفوض</span>',
            default => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-500/10 text-gray-400">' . $this->status . '</span>',
        };
    }
}
