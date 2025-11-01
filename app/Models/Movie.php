<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'poster',
        'trailer_url',
        'is_restricted', // optional 18+ flag
        'user_id',
    ];

    /**
     * A movie belongs to one producer (user).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A movie can have many comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * A movie can have many ratings.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }



}
