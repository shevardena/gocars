<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'parameter',
        'value'
    ];

    /**
     * Filter records by phone parameter
     * @param $query
     * @return mixed
     */
    public function scopePhone($query): mixed
    {
        return $query->where('parameter','phone');
    }

    /**
     * Filter records by email parameter
     * @param $query
     * @return mixed
     */
    public function scopeEmail($query): mixed
    {
        return $query->where('parameter','email');
    }

    /**
     * Filter records by day parameter
     * @param $query
     * @return mixed
     */
    public function scopeDay($query): mixed
    {
        return $query->where('parameter','day');
    }
}
