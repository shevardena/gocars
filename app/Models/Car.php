<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Car extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, InteractsWithMedia;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function (Car $car) {
                $parts = [];

                $parts[] = $car->id;

                if ($car->model?->make) {
                    $parts[] = $car->model->make->name;
                }

                if ($car->model) {
                    $parts[] = $car->model->name;
                }

                return implode(' ', $parts);
            })
            ->saveSlugsTo('slug');
    }



    protected $fillable = [
        'id',
        'slug',
        'year',
        'vin',
        'arrival_date',
        'purchase_date',
        'is_sold',
        'sold_price_usd',
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

    public function getTotalExpensesUsdAttribute(): float
    {
        return (float) $this->expenses()->sum('amount_usd');
    }

    public function getProfitUsdAttribute(): ?float
    {
        if (!$this->is_sold || !$this->sold_price_usd) {
            return null;
        }

        return round(
            $this->sold_price_usd - $this->total_expenses_usd,
            2
        );
    }
}
