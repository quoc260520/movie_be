<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderMovie extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS = [
        self::STATUS_CANCEL,
        self::STATUS_UNUSED,
        self::STATUS_USED
    ];
    const STATUS_CANCEL = 0;
    const STATUS_UNUSED = 1;
    const STATUS_USED = 2;

    protected $fillable = [
        'user_id',
        'coupon_id',
        'time_id',
        'status',
    ];
    public function timeMovie(): BelongsTo
    {
        return $this->belongsTo('App\Models\TimeMovie', 'time_id');
    }
    public function coupon(): BelongsTo
    {
        return $this->belongsTo('App\Models\Coupon', 'coupon_id');
    }
    public function orderDetails(): HasMany
    {
        return $this->hasMany('App\Models\OrderMovieDetail', 'order_movie_id');
    }
}
