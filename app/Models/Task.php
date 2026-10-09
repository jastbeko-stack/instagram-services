<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'type',
        'target_url',
        'points_reward',
        'max_completions',
        'current_completions',
        'instructions',
        'is_daily',
        'status',
    ];

    protected $casts = [
        'points_reward' => 'integer',
        'max_completions' => 'integer',
        'current_completions' => 'integer',
        'is_daily' => 'boolean',
        'status' => 'boolean',
    ];

    public function completions(): HasMany
    {
        return $this->hasMany(TaskCompletion::class);
    }

    public function getFormattedTypeAttribute(): string
    {
        return match ($this->type) {
            'like_post' => 'لايك بوست انستقرام',
            'like_reel' => 'لايك ومشاهدة ريلز',
            'follow_account' => 'متابعة حساب انستقرام',
            'comment' => 'تعليق على منشور',
            'story_view' => 'مشاهدة وتصويت ستوري',
            default => 'مهمة تفاعلية',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'like_post' => 'fa-heart text-rose-500',
            'like_reel' => 'fa-play-circle text-pink-500',
            'follow_account' => 'fa-user-plus text-sky-400',
            'comment' => 'fa-comment text-emerald-400',
            'story_view' => 'fa-circle-dot text-amber-400',
            default => 'fa-bolt text-yellow-400',
        };
    }
}
