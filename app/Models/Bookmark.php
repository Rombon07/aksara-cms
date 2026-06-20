<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'article_id'];

    // Relasi balik (Inverse): Setiap data Bookmark dimiliki oleh satu User (Many-to-1).
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi balik (Inverse): Setiap data Bookmark merujuk pada satu Article (Many-to-1).
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
