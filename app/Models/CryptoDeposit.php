<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoDeposit extends Model
{
    protected $fillable = [
        'deposit_code',
        'user_id',
        'payment_method',
        'crypto_currency',
        'network',
        'wallet_address',
        'currency',
        'amount_usd',
        'amount_iqd',
        'txid',
        'proof_image',
        'card_last_four',
        'sender_phone',
        'status',
        'admin_notes',
        'approved_at',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:2',
        'amount_iqd' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMethodBadgeAttribute(): string
    {
        return match ($this->payment_method) {
            'super_qi' => '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-500/10 text-yellow-400 border border-yellow-500/20"><i class="fa-solid fa-credit-card mr-1"></i> سوبر كي (Super Qi)</span>',
            'zaincash' => '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20"><i class="fa-solid fa-mobile-screen mr-1"></i> زين كاش</span>',
            'mastercard_manual' => '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-500/10 text-red-400 border border-red-500/20"><i class="fa-brands fa-cc-mastercard mr-1"></i> ماستركارد</span>',
            default => '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><i class="fa-solid fa-coins mr-1"></i> USDT (' . ($this->network ?? 'TRC20') . ')</span>',
        };
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
