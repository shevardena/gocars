<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Balance extends Model
{
    protected $fillable = [
        'author_id',
        'backend_user_id',
        'amount',
        'amount_usd',
        'amount_gel',
        'usd_rate'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Balance $balance) {
//            $service = new BalanceHistoryService();
//            $service->store($balance, 'deposit');
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(BackendUser::class, 'author_id');
    }

    public function backend_user(): BelongsTo
    {
        return $this->belongsTo(BackendUser::class, 'backend_user_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class,'balance_id');
    }
}
