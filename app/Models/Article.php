<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'body',
        'thumbnail',
        'status',
        'editor_notes',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->thumbnail) {
            return \Illuminate\Support\Facades\Storage::url($this->thumbnail);
        }
        
        $keyword = urlencode($this->category ? $this->category->name : 'berita');
        return "https://loremflickr.com/800/600/{$keyword}?lock={$this->id}";
    }
}
