<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'category_id',
        'title',
        'slug',
        'body',
        'thumbnail',
        'file_path',
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
            return url('media/' . $this->thumbnail);
        }
        
        return null;
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return url('media/' . $this->file_path);
        }
        
        return null;
    }

    // Fungsi ini mendefinisikan relasi: Satu Article bisa disukai oleh banyak User (1-to-Many).
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Fungsi ini mendefinisikan relasi: Satu Article bisa disimpan (bookmark) oleh banyak User (1-to-Many).
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // Fungsi ini mendefinisikan relasi: Satu Article bisa memiliki banyak Comment (1-to-Many).
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
