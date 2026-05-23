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
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@kabarkini.com',
            'password' => bcrypt('password'),
            'role' => 'editor',
        ]);

        User::create([
            'name' => 'Journalist User',
            'email' => 'jurnalis@kabarkini.com',
            'password' => bcrypt('password'),
            'role' => 'journalist',
        ]);

        $categories = [
            'Technology',
            'Politics',
            'Sports',
            'Business',
            'Entertainment'
        ];

        // Define Categories and their Antara RSS feeds
        $categoryFeeds = [
            'Politics' => 'https://www.antaranews.com/rss/politik.xml',
            'Economy' => 'https://www.antaranews.com/rss/ekonomi.xml',
            'Sports' => 'https://www.antaranews.com/rss/olahraga.xml',
            'Entertainment' => 'https://www.antaranews.com/rss/hiburan.xml',
            'Technology' => 'https://www.antaranews.com/rss/tekno.xml',
        ];

        // Fetch 50 random Wikipedia articles in Indonesian for dummy body text
        $options  = ['http' => ['user_agent' => 'KabarKini Seeder/1.0']];
        $context  = stream_context_create($options);
        $wikiJson = @file_get_contents('https://id.wikipedia.org/w/api.php?format=json&action=query&generator=random&grnnamespace=0&grnlimit=50&prop=extracts&exchars=2000', false, $context);
        $wikiData = json_decode($wikiJson, true);
        $wikiPages = array_values($wikiData['query']['pages'] ?? []);

        $allArticles = [];
        $wikiIndex = 0;

        foreach ($categoryFeeds as $catName => $rssUrl) {
            $category = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
            ]);

            $xml = @simplexml_load_file($rssUrl);
            if ($xml && isset($xml->channel->item)) {
                $count = 0;
                foreach ($xml->channel->item as $item) {
                    if ($count >= 10) break; // 10 articles per category = 50 total
                    
                    $realExcerpt = strip_tags((string) $item->description);
                    $wikiText = isset($wikiPages[$wikiIndex]) ? $wikiPages[$wikiIndex]['extract'] : '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>';
                    
                    // Generate a full story by combining the real excerpt with Wikipedia paragraphs
                    $body = '<p><strong>' . $realExcerpt . '</strong></p>' . $wikiText;

                    $allArticles[] = [
                        'category_id' => $category->id,
                        'title' => (string) $item->title,
                        'body' => $body,
                    ];
                    $count++;
                    $wikiIndex++;
                }
            }
        }

        shuffle($allArticles);

        foreach ($allArticles as $index => $data) {
            $status = 'published';
            $publishedAt = now()->subDays(rand(1, 10));
            $editorNotes = null;

            if ($index >= 30 && $index < 40) {
                $status = 'pending';
                $publishedAt = null;
            } elseif ($index >= 40) {
                $status = 'draft';
                $publishedAt = null;
                $editorNotes = 'Mohon perbaiki gaya bahasa di paragraf terakhir.';
            }

            \App\Models\Article::create([
                'user_id' => 2,
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'slug' => \Illuminate\Support\Str::slug($data['title']) . '-' . uniqid(),
                'body' => $data['body'],
                'thumbnail' => null,
                'status' => $status,
                'editor_notes' => $editorNotes,
                'published_at' => $publishedAt,
            ]);
        }
    }
}
