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

        User::firstOrCreate(
            ['email' => 'admin@aksara.com'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
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

        $articlesData = [];
        try {
            $listResponse = \Illuminate\Support\Facades\Http::timeout(5)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->get('https://dev.to/api/articles?per_page=12');
                
            if ($listResponse->successful()) {
                $listData = $listResponse->json() ?? [];
                // Fetch details for each article to get body_html (rich description)
                foreach ($listData as $item) {
                    try {
                        $detailResponse = \Illuminate\Support\Facades\Http::timeout(3)
                            ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                            ->get('https://dev.to/api/articles/' . $item['id']);
                        if ($detailResponse->successful()) {
                            $articlesData[] = $detailResponse->json();
                        }
                    } catch (\Exception $de) {
                        // Fallback using list data
                        $articlesData[] = [
                            'title' => $item['title'],
                            'body_html' => '<p>' . e($item['description'] ?? '') . '</p>',
                            'cover_image' => $item['cover_image'] ?? $item['social_image'] ?? null,
                            'published_at' => $item['published_at'] ?? now()->toIso8601String(),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback will be used if HTTP request fails
        }

        if (!empty($articlesData)) {
            foreach ($articlesData as $index => $data) {
                $status = 'published';
                $publishedAt = \Carbon\Carbon::parse($data['published_at'] ?? now());
                $editorNotes = null;

                if ($index == 9 || $index == 10) {
                    $status = 'pending';
                    $publishedAt = null;
                } elseif ($index == 11) {
                    $status = 'draft';
                    $publishedAt = null;
                    $editorNotes = 'Please expand more on the conclusion.';
                }

                \App\Models\Article::create([
                    'user_id' => 2,
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'type' => 'article',
                    'title' => $data['title'],
                    'slug' => \Illuminate\Support\Str::slug($data['title']) . '-' . uniqid(),
                    'body' => $data['body_html'] ?? '<p>No content available.</p>',
                    'thumbnail' => $data['cover_image'] ?? $data['social_image'] ?? null,
                    'status' => $status,
                    'editor_notes' => $editorNotes,
                    'published_at' => $publishedAt,
                ]);
            }
        } else {
            // Fallback to local hardcoded dummy articles
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
                'My Experience Living Off-Grid for a Month'
            ];

            foreach ($articleTitles as $index => $title) {
                $status = 'published';
                $publishedAt = now()->subDays(rand(1, 30));
                $editorNotes = null;

                if ($index == 9 || $index == 10) {
                    $status = 'pending';
                    $publishedAt = null;
                } elseif ($index == 11) {
                    $status = 'draft';
                    $publishedAt = null;
                    $editorNotes = 'Please expand more on the conclusion.';
                }

                \App\Models\Article::create([
                    'user_id' => 2,
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'type' => 'article',
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

        // --- Handle E-Books (Download real PDF ebooks from internet) ---
        $ebooksFolder = storage_path('app/public/ebooks');
        if (!file_exists($ebooksFolder)) {
            mkdir($ebooksFolder, 0755, true);
        }

        $realEbooks = [
            [
                'title' => 'Web Accessibility Evaluation Standards',
                'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                'filename' => 'w3c_accessibility_guide.pdf',
                'cover' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&auto=format&fit=crop',
                'desc' => '<p>Panduan standar internasional W3C mengenai evaluasi dan implementasi aksesibilitas web bagi penyandang disabilitas.</p>'
            ],
            [
                'title' => 'Sample Literary Reader & Prose',
                'url' => 'https://www.orimi.com/pdf-test.pdf',
                'filename' => 'orimi_sample_reader.pdf',
                'cover' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=600&auto=format&fit=crop',
                'desc' => '<p>Kumpulan prosa sastra klasik pilihan untuk meningkatkan pemahaman membaca dan apresiasi karya tulis.</p>'
            ],
            [
                'title' => 'Interactive Digital Marketing Manual',
                'url' => 'https://www.clickdimensions.com/links/TestPDFfile.pdf',
                'filename' => 'clickdimensions_test.pdf',
                'cover' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=600&auto=format&fit=crop',
                'desc' => '<p>Manual panduan praktis pemasaran digital interaktif menggunakan platform automasi dan CRM modern.</p>'
            ],
            [
                'title' => 'PDF.js Annotation Layout & Rendering',
                'url' => 'https://raw.githubusercontent.com/mozilla/pdf.js/master/test/pdfs/annotation-tx3.pdf',
                'filename' => 'mozilla_annotation_guide.pdf',
                'cover' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&auto=format&fit=crop',
                'desc' => '<p>Spesifikasi teknis Mozilla PDF.js mengenai rendering anotasi interaktif langsung pada browser web.</p>'
            ],
            [
                'title' => 'Software Design Patterns & Debugging',
                'url' => 'https://raw.githubusercontent.com/mozilla/pdf.js/master/test/pdfs/bug857077.pdf',
                'filename' => 'mozilla_bug_report.pdf',
                'cover' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=600&auto=format&fit=crop',
                'desc' => '<p>Laporan analisis arsitektur perangkat lunak untuk mengatasi kesalahan memori pada mesin rendering PDF.</p>'
            ]
        ];

        foreach ($realEbooks as $index => $eb) {
            $destPath = $ebooksFolder . '/' . $eb['filename'];
            
            // Download the PDF from the internet if it doesn't exist
            if (!file_exists($destPath)) {
                try {
                    $pdfContent = \Illuminate\Support\Facades\Http::timeout(10)
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                        ->get($eb['url'])
                        ->body();
                    
                    if ($pdfContent && strlen($pdfContent) > 100) {
                        file_put_contents($destPath, $pdfContent);
                    } else {
                        // Create a small fallback dummy PDF if download fails or is empty
                        file_put_contents($destPath, "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/Resources <<\n/Font <<\n/F1 4 0 R\n>>\n>>\n/MediaBox [0 0 612 792]\n/Contents 5 0 R\n>>\nendobj\n4 0 obj\n<<\n/Type /Font\n/Subtype /Type1\n/BaseFont /Helvetica\n>>\nendobj\n5 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 24 Tf\n100 700 Td\n(Dummy E-Book PDF Content) Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\n0000000250 00000 n\n0000000325 00000 n\ntrailer\n<<\n/Size 6\n/Root 1 0 R\n>>\nstartxref\n419\n%%EOF");
                    }
                } catch (\Exception $ee) {
                    // Create fallback PDF locally
                    file_put_contents($destPath, "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/Resources <<\n/Font <<\n/F1 4 0 R\n>>\n>>\n/MediaBox [0 0 612 792]\n/Contents 5 0 R\n>>\nendobj\n4 0 obj\n<<\n/Type /Font\n/Subtype /Type1\n/BaseFont /Helvetica\n>>\nendobj\n5 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 24 Tf\n100 700 Td\n(Dummy E-Book PDF Content) Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\n0000000250 00000 n\n0000000325 00000 n\ntrailer\n<<\n/Size 6\n/Root 1 0 R\n>>\nstartxref\n419\n%%EOF");
                }
            }

            \App\Models\Article::create([
                'user_id' => 2, // Resident Author
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'type' => 'ebook',
                'title' => $eb['title'],
                'slug' => \Illuminate\Support\Str::slug($eb['title']) . '-' . uniqid(),
                'body' => $eb['desc'],
                'thumbnail' => $eb['cover'],
                'file_path' => 'ebooks/' . $eb['filename'],
                'status' => 'published',
                'published_at' => now()->subDays($index),
            ]);
        }
    }
}
