<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'editor@aksara.com'],
            [
                'name' => 'Editor in Chief',
                'password' => bcrypt('password'),
                'role' => 'editor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'author@aksara.com'],
            [
                'name' => 'Resident Author',
                'password' => bcrypt('password'),
                'role' => 'author',
            ]
        );

        User::firstOrCreate(
            ['email' => 'reader@aksara.com'],
            [
                'name' => 'General Reader',
                'password' => bcrypt('password'),
                'role' => 'reader',
            ]
        );

        $categories = [
            'Essays',
            'Technology',
            'Fiction',
            'Non-Fiction',
            'Tutorials'
        ];

        // Create Categories
        $categoryIds = [];
        foreach ($categories as $catName) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName]
            );
            $categoryIds[] = $category->id;
        }

        // Dummy text for body
        $dummyBody = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam auctor, eros id auctor varius, ligula libero commodo nulla, quis faucibus leo eros at velit. In hac habitasse platea dictumst. Curabitur vel leo at nisi luctus faucibus. Donec dignissim lacus odio, vel euismod risus aliquet vel.</p>
<p>Nullam vitae erat id ex facilisis lacinia et quis orci. Aenean tristique felis sit amet diam scelerisque, quis tristique sem laoreet. Fusce sed ipsum sem. Phasellus interdum enim vitae metus vehicula, sit amet interdum felis semper.</p>
<h2>Understanding the Depth</h2>
<p>Duis laoreet sapien a ante consectetur, sit amet pretium nunc pulvinar. Nunc sed nunc a eros tempor tristique ac at lectus. Mauris non erat eu tellus volutpat aliquet ut ullamcorper dui. Mauris congue arcu in est venenatis, et vulputate quam elementum.</p>';

        $articleTitles = [
            'The Art of Minimalist Living in a Busy World',
            'Understanding the Fundamentals of Modern Architecture',
            'A Journey Through the Hidden Valleys of the Himalayas',
            'The Rise of AI: What It Means for Everyday Jobs',
            'Reflections on a Decade of Software Engineering',
            'Why We Need to Talk About Mental Health in Tech',
            'The Lost Chapters of Ancient History',
            'How to Build a Habit That Actually Sticks',
            'A Fictional Tale of Two Cities in the Cloud',
            'The Economics of Coffee: From Bean to Cup',
            'Getting Started with Tailwind CSS in 2026',
            'My Experience Living Off-Grid for a Month',
            'The Psychology Behind User Interface Design',
            'Top 10 Books That Changed My Perspective on Life',
            'A Comprehensive Guide to Laravel Eloquent'
        ];

        foreach ($articleTitles as $index => $title) {
            $status = 'published';
            $publishedAt = now()->subDays(rand(1, 30));
            $editorNotes = null;

            if ($index == 12 || $index == 13) {
                $status = 'pending';
                $publishedAt = null;
            } elseif ($index == 14) {
                $status = 'draft';
                $publishedAt = null;
                $editorNotes = 'Please expand more on the conclusion.';
            }

            \App\Models\Article::create([
                'user_id' => 2,
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'title' => $title,
                'slug' => \Illuminate\Support\Str::slug($title) . '-' . uniqid(),
                'body' => $dummyBody,
                'thumbnail' => null,
                'status' => $status,
                'editor_notes' => $editorNotes,
                'published_at' => $publishedAt,
            ]);
        }
    }
}
