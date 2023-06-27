<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeMovie extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_CLOSE = 0;
    const STATUS_OPEN = 1;
    protected $fields = [
        'movie_id',
        'room_id',
        'time_start',
        'time_end',
        'status',
        'price',
    ];

    public function movie()
    {
        return $this->belongsTo('App\Models\Movie');
    }


}
