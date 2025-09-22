<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_type',
        'author_id',
        'backend_user_id',
        'amount_usd',
        'amount_gel',
        'usd_rate'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (BalanceHistory $balanceHistory) {
            $balanceHistory->author_id = auth()->id();
            $balanceHistory->save();
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
}
