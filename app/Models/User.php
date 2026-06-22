<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'author_request_status',
        'author_request_bio',
        'author_request_portfolio',
        'author_request_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Fungsi ini mendefinisikan relasi: Satu User bisa menulis banyak Article (1-to-Many).
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Fungsi ini mendefinisikan relasi: Satu User bisa menyukai banyak Article (1-to-Many).
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Fungsi ini mendefinisikan relasi: Satu User bisa menyimpan (bookmark) banyak Article (1-to-Many).
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // Fungsi ini mendefinisikan relasi: Satu User bisa menulis banyak Comment di berbagai Article (1-to-Many).
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
