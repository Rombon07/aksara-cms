<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'article_id', 'body'];

    // Relasi balik (Inverse): Setiap Comment ditulis oleh satu User (Many-to-1).
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi balik (Inverse): Setiap Comment berada di dalam satu Article (Many-to-1).
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
