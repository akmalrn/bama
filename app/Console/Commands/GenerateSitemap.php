<?php

namespace App\Console\Commands;

use App\Models\Database\Blog;
use App\Models\Database\BusinessField;
use App\Models\Database\CategoryBlog;
use App\Models\Database\CategoryProduct;
use App\Models\Database\Product;
use App\Models\Database\Project;
use App\Models\Database\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap.xml';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        /*
         * Halaman frontend statis sesuai navigasi dan route publik aktif.
         */
        $staticPages = [
            [
                'route' => 'index',
                'frequency' => Url::CHANGE_FREQUENCY_DAILY,
                'priority' => 1.0,
            ],
            [
                'route' => 'about',
                'frequency' => Url::CHANGE_FREQUENCY_MONTHLY,
                'priority' => 0.8,
            ],
            [
                'route' => 'services',
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY,
                'priority' => 0.9,
            ],
            [
                'route' => 'products',
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY,
                'priority' => 0.9,
            ],
            [
                'route' => 'portfolios',
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY,
                'priority' => 0.8,
            ],
            [
                'route' => 'projects',
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY,
                'priority' => 0.8,
            ],
            [
                'route' => 'blogs',
                'frequency' => Url::CHANGE_FREQUENCY_DAILY,
                'priority' => 0.9,
            ],
            [
                'route' => 'career',
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY,
                'priority' => 0.8,
            ],
            [
                'route' => 'collaboration',
                'frequency' => Url::CHANGE_FREQUENCY_MONTHLY,
                'priority' => 0.7,
            ],
            [
                'route' => 'contact',
                'frequency' => Url::CHANGE_FREQUENCY_MONTHLY,
                'priority' => 0.7,
            ],
        ];

        foreach ($staticPages as $page) {
            if (! Route::has($page['route'])) {
                continue;
            }

            $sitemap->add(
                Url::create(route($page['route']))
                    ->setChangeFrequency($page['frequency'])
                    ->setPriority($page['priority'])
            );
        }

        /*
         * Kategori blog yang memiliki artikel terbit.
         */
        CategoryBlog::query()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->whereHas('blogs', function ($query) {
                $query->where(function ($publishedQuery) {
                    $publishedQuery
                        ->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                });
            })
            ->latest('updated_at')
            ->each(function (CategoryBlog $category) use ($sitemap) {
                $url = Url::create(route('blog.category', ['slug' => $category->slug]))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7);

                if ($category->updated_at) {
                    $url->setLastModificationDate($category->updated_at);
                }

                $sitemap->add($url);
            });

        /*
         * Detail blog yang terbit.
         */
        Blog::query()
            ->with('categoryBlog:id,title,slug')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('updated_at')
            ->each(function (Blog $blog) use ($sitemap) {
                $url = Url::create(route('blog.show', ['slug' => $blog->slug]))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                if ($blog->updated_at) {
                    $url->setLastModificationDate($blog->updated_at);
                }

                $sitemap->add($url);
            });

        /*
         * Detail layanan aktif.
         */
        Service::query()
            ->where('status', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->each(function (Service $service) use ($sitemap) {
                $url = Url::create(
                    route('service.show', [
                        'slug' => $service->slug,
                    ])
                )
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                if ($service->updated_at) {
                    $url->setLastModificationDate($service->updated_at);
                }

                $sitemap->add($url);
            });

        /*
         * Detail bidang usaha aktif.
         */
        BusinessField::query()
            ->where('status', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->each(function (BusinessField $businessField) use ($sitemap) {
                $url = Url::create(route('business-field.show', ['slug' => $businessField->slug]))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                if ($businessField->updated_at) {
                    $url->setLastModificationDate($businessField->updated_at);
                }

                $sitemap->add($url);
            });

        /*
         * Kategori produk aktif.
         */
        CategoryProduct::query()
            ->where('status', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->each(function (CategoryProduct $category) use ($sitemap) {
                $url = Url::create(route('category-product.show', ['slug' => $category->slug]))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                if ($category->updated_at) {
                    $url->setLastModificationDate($category->updated_at);
                }

                $sitemap->add($url);
            });

        /*
         * Detail produk aktif. Route produk berada di slug tingkat teratas.
         */
        Product::query()
            ->where('status', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->each(function (Product $product) use ($sitemap) {
                $url = Url::create(route('product.show', ['slug' => $product->slug]))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9);

                if ($product->updated_at) {
                    $url->setLastModificationDate($product->updated_at);
                }

                $sitemap->add($url);
            });

        /*
         * Detail proyek dan halaman perbandingan.
         */
        Project::query()
            ->where('status', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->each(function (Project $project) use ($sitemap) {
                foreach (['project.show', 'comparison.show'] as $routeName) {
                    if (! Route::has($routeName)) {
                        continue;
                    }

                    $url = Url::create(route($routeName, ['slug' => $project->slug]))
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority($routeName === 'comparison.show' ? 0.9 : 0.8);

                    if ($project->updated_at) {
                        $url->setLastModificationDate($project->updated_at);
                    }

                    $sitemap->add($url);
                }
            });

        // $sitemap->writeToFile(public_path('sitemap.xml'));
         $sitemap->writeToFile('/home/cijfcnuj/public_html/sitemap.xml');

        $this->info(sprintf(
            '[%s] Sitemap generated successfully: %s',
            now()->format('Y-m-d H:i:s T'),
            public_path('sitemap.xml')
        ));

        return self::SUCCESS;
    }
}
