<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_id',
        'name',
        'description' ,
        'author' ,
        'time' ,
        'images',
    ];
   
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
    ];

    public function timeMovies()
    {
        return $this->hasMany('App\Models\TimeMovie', 'movie_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
