<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'image',
        'category',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if ( !$post->author_id ) {
                $post->author_id = Auth::id();
            }
        });
    }

    public static function getPosts()
    {
        $minutes = config('global.cache.news', 1440);

        return Cache::remember("news", now()->addMinutes($minutes), function () {
            return self::where('active', '=', 1)->where('published_at', '<=', now())->orderBy('created_at', 'DESC')->get();
        });
    }

    public static function getPost($slug)
    {
        $minutes = config('global.cache.news', 1440);

        return Cache::remember("news_view_{$slug}", now()->addMinutes($minutes), function () use ($slug) {
            return self::where('slug', $slug)->first();
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
