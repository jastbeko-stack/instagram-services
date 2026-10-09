<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InstagramUsername extends Model
{
    protected $fillable = [
        'username',
        'type',
        'followers_count',
        'creation_year',
        'price',
        'description',
        'status',
        'delivery_info',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'followers_count' => 'integer',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(UsernameOrder::class);
    }

    public function getFormattedTypeAttribute(): string
    {
        return match ($this->type) {
            'quad' => 'رباعي (4L)',
            'tri_semi' => 'شبه ثلاثي (Semi 3L)',
            'vintage' => 'قديم (Vintage)',
            'verified' => 'موثق (Verified)',
            default => 'يوزر مميز',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available' => '<span class="px-2 py-1 text-xs rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">متاح للشراء</span>',
            'reserved' => '<span class="px-2 py-1 text-xs rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">محجوز</span>',
            'sold' => '<span class="px-2 py-1 text-xs rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">تم البيع</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-500/10 text-gray-400">' . $this->status . '</span>',
        };
    }
}
