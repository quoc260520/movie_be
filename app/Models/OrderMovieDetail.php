<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderMovieDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_movie_id',
        'price',
        'no_chair'
    ];
    public function orderMovie(): BelongsTo
    {
        return $this->belongsTo('App\Models\OrderMovie');
    }
}
