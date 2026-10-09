<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'balance',
        'points',
        'phone',
        'telegram',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'balance' => 'decimal:2',
            'points' => 'integer',
        ];
    }

    public function usernameOrders(): HasMany
    {
        return $this->hasMany(UsernameOrder::class);
    }

    public function smmOrders(): HasMany
    {
        return $this->hasMany(SmmOrder::class);
    }

    public function cryptoDeposits(): HasMany
    {
        return $this->hasMany(CryptoDeposit::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->latest();
    }

    public function taskCompletions(): HasMany
    {
        return $this->hasMany(TaskCompletion::class);
    }

    public function pointConversions(): HasMany
    {
        return $this->hasMany(PointConversion::class)->latest();
    }

    public function completedTaskToday(int $taskId): bool
    {
        $today = now()->toDateString();
        return $this->taskCompletions()
            ->where('task_id', $taskId)
            ->where('completed_date', $today)
            ->exists();
    }

    public function addPoints(int $points): void
    {
        $this->increment('points', $points);
    }

    public function deductPoints(int $points): void
    {
        $this->decrement('points', $points);
    }

    public function hasSufficientBalance(float|string $amount): bool
    {
        return bccomp((string)$this->balance, (string)$amount, 2) >= 0;
    }

    public function deductBalance(float $amount, string $description, ?string $referenceId = null, string $type = 'purchase'): Transaction
    {
        return DB::transaction(function () use ($amount, $description, $referenceId, $type) {
            $user = User::where('id', $this->id)->lockForUpdate()->first();
            $before = $user->balance;
            $after = $before - $amount;

            $user->balance = $after;
            $user->save();
            $this->balance = $after;

            return Transaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => -$amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => $description,
                'reference_id' => $referenceId,
            ]);
        });
    }

    public function addBalance(float $amount, string $description, ?string $referenceId = null, string $type = 'deposit'): Transaction
    {
        return DB::transaction(function () use ($amount, $description, $referenceId, $type) {
            $user = User::where('id', $this->id)->lockForUpdate()->first();
            $before = $user->balance;
            $after = $before + $amount;

            $user->balance = $after;
            $user->save();
            $this->balance = $after;

            return Transaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => $description,
                'reference_id' => $referenceId,
            ]);
        });
    }
}
