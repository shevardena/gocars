<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Car extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'slug',
        'year',
        'vin',
        'arrival_date',
        'purchase_date',
        'is_sold',
        'phone',
        'email',
        'car_model_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'purchase_date' => 'date',
        'arrival_date' => 'date',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Generate slug from make and model when model is created
        static::created(function (Car $car) {
            if (empty($car->slug)) {
                $parts = [];

                if (isset($car->model) && isset($car->model->make)) {
                    $parts[] = $car->model->make->name;
                }

                if (isset($car->model)) {
                    $parts[] = $car->model->name;
                }

                if (empty($parts)) {
                    $parts[] = $car->arrival_date;
                }

                $slug = Str::slug(implode(' ', $parts));

                $car->slug = $slug;
                $car->save();
            }
        });
    }

    /**
     * Car model
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    /**
     * Car expenses
     * @return hasMany
     */
    public function expenses(): hasMany
    {
        return $this->hasMany(Expense::class, 'car_id');
    }
}
