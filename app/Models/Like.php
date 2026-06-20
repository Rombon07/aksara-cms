<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'article_id'];

    // Relasi balik (Inverse): Setiap Like dilakukan oleh satu User (Many-to-1).
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi balik (Inverse): Setiap Like terkait dengan satu Article (Many-to-1).
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
