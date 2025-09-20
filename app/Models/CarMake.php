<?php

namespace App\Models;

use Filament\Schemas\Components\Concerns\HasMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CarMake extends Model implements HasMedia
{

    use HasSlug, InteractsWithMedia;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    protected $fillable = [
        'id',
        'name',
        'slug',
        'created_at',
        'updated_at'
    ];

    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
