<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmmOrder extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'service_id',
        'link',
        'quantity',
        'charge',
        'start_count',
        'remains',
        'execution_type',
        'provider_id',
        'provider_order_id',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'charge' => 'decimal:4',
        'quantity' => 'integer',
        'start_count' => 'integer',
        'remains' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(SmmService::class, 'service_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmmProvider::class, 'provider_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">قيد الانتظار</span>',
            'in_progress' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">قيد التنفيذ</span>',
            'completed' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">مكتمل</span>',
            'partial' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">مكتمل جزئياً</span>',
            'canceled' => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">ملغي</span>',
            default => '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-500/10 text-gray-400">' . $this->status . '</span>',
        };
    }
}
