<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'amount_gel',
        'amount_usd',
        'car_id',
        'backend_user_id',
        'operation_id',
        'balance_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Expense $expense) {

            // Attach backend user on model create
            if(!$expense->backend_user_id){
                $expense->backend_user_id = auth()->user()->id ?? 2;
                $expense->save();
            }

        });
    }

    /**
     * Related car
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * Related backend user
     * @return BelongsTo
     */
    public function backend_user(): BelongsTo
    {
        return $this->belongsTo(BackendUser::class, 'backend_user_id');
    }

    /**
     * Related balance
     * @return BelongsTo
     */
    public function balance(): BelongsTo
    {
        return $this->belongsTo(Balance::class, 'balance_id');
    }
}
