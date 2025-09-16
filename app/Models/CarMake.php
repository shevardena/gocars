<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarMake extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'created_at',
        'updated_at'
    ];
}
