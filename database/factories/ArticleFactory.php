<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(8);
        return [
            'user_id' => 2, // The Journalist User (id=2)
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . uniqid(),
            'body' => '<p>' . implode('</p><p>', fake()->paragraphs(10)) . '</p>',
            'thumbnail' => null,
            'status' => 'draft',
            'editor_notes' => null,
            'published_at' => null,
        ];
    }
}
