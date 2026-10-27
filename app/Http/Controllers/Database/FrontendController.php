<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\About;
use App\Models\Database\Blog;
use App\Models\Database\BusinessField;
use App\Models\Database\Career;
use App\Models\Database\CareerPosition;
use App\Models\Database\CategoryBlog;
use App\Models\Database\CategoryPartner;
use App\Models\Database\CategoryPortofolio;
use App\Models\Database\CategoryProduct;
use App\Models\Database\CategoryService;
use App\Models\Database\Collaboration;
use App\Models\Database\Contact;
use App\Models\Database\CounterSection as Counter;
use App\Models\Database\Faq;
use App\Models\Database\Gallery;
use App\Models\Database\LabelBlog;
use App\Models\Database\LabelSlider;
use App\Models\Database\LabelSuperiority;
use App\Models\Database\LabelSuperiorityIcon;
use App\Models\Database\OrderStep;
use App\Models\Database\Partner;
use App\Models\Database\Portofolio;
use App\Models\Database\Product;
use App\Models\Database\Project;
use App\Models\Database\SectionBusiness;
use App\Models\Database\SectionFaq;
use App\Models\Database\SectionGallery;
use App\Models\Database\SectionPartner;
use App\Models\Database\SectionPortofolio;
use App\Models\Database\SectionProduct;
use App\Models\Database\SectionProject;
use App\Models\Database\SectionTestimonial;
use App\Models\Database\Service;
use App\Models\Database\ServiceSection;
use App\Models\Database\Slider;
use App\Models\Database\Superiority;
use App\Models\Database\SuperiorityIcon;
use App\Models\Database\Testimonial;
use App\Support\FrontendCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function index()
    {
        $data = Cache::remember('frontend:home', FrontendCache::TTL, function () {
            $labelSlider = LabelSlider::first();
            $sliders = Slider::latest()->get();
            $labelPartner = SectionPartner::first();
            $partners = Partner::with('category')->latest()->get();
            $labelSuperiority = LabelSuperiorityIcon::first();
            $superiorities = SuperiorityIcon::latest()->get();
            $labelTestimonial = SectionTestimonial::first() ?? LabelSuperiority::first();
            $testimonials = Testimonial::latest()->get();
            if ($testimonials->isEmpty()) {
                $testimonials = Superiority::latest()->get();
            }
            $labelSuperiorityImage = $labelTestimonial;
            $superioritiesImage = $testimonials;
            $labelProduct = SectionProduct::first();
            $products = Product::where('status', true)
                ->where('is_featured', true)
                ->orderBy('order')
                ->latest()
                ->take(6)
                ->get();
            $businessFields = BusinessField::where('status', true)->orderByRaw('CAST(`order` AS UNSIGNED) ASC')->get();
            $labelBusinessField = SectionBusiness::first();
            $services = Service::where('status', true)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
            $labelService = ServiceSection::first();
            $faqs = Faq::orderBy('order', 'asc')->get();
            $labelFaq = SectionFaq::first();
            $galleries = Gallery::latest()->get();
            $stats = Counter::orderByRaw('CAST(`order` AS UNSIGNED) ASC')->get();

            return compact(
                'labelSlider',
                'sliders',
                'labelProduct',
                'products',
                'labelPartner',
                'partners',
                'labelSuperiority',
                'superiorities',
                'labelSuperiorityImage',
                'superioritiesImage',
                'labelTestimonial',
                'testimonials',
                'businessFields',
                'labelBusinessField',
                'services',
                'labelService',
                'faqs',
                'labelFaq',
                'galleries',
                'stats',
            );
        });

        return view('frontend.index', $data);
    }

    public function about()
    {
        $data = Cache::remember('frontend:about', FrontendCache::TTL, function () {
            $about = About::first();
            $stats = Counter::orderBy('order', 'asc')->get();
            $counters = (object) [
                'title' => $about?->counter_title ?: 'Total selalu berkomitmen membantu Anda penuhi semua kebutuhan bisnis',
                'description' => $about?->counter_description ?: 'Kami terus berusaha memberikan pelayanan terbaik bagi pelanggan.',
            ];

            $techCategory = CategoryPartner::where('slug', 'teknologi')
                ->orWhere('title', 'like', '%Teknologi%')
                ->first();
            $techPartners = $techCategory ? $techCategory->partners : collect();

            return compact(
                'about',
                'stats',
                'counters',
                'techPartners'
            );
        });

        return view('frontend.about', $data);
    }

    public function career()
    {
        $data = Cache::remember('frontend:career', FrontendCache::TTL, function () {
            $career = Career::first();
            $contact = Contact::first();
            $positions = CareerPosition::where('is_active', true)->orderBy('order')->orderBy('id')->get();

            return compact('career', 'contact', 'positions');
        });

        return view('frontend.career', $data);
    }

    public function collaboration()
    {
        $data = Cache::remember('frontend:collaboration', FrontendCache::TTL, function () {
            $collaboration = Collaboration::first();
            $contact = Contact::first();

            return compact('collaboration', 'contact');
        });

        return view('frontend.collaboration', $data);
    }

    public function services(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:255'],
            'context' => ['nullable', 'in:catalog,sidebar'],
        ]);

        $services = Service::with('category')->where('status', true)
            ->when($filters['q'] ?? null, fn ($query, $q) => $query->where(function ($search) use ($q) {
                $search->where('title', 'like', '%'.$q.'%')
                    ->orWhere('overview', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%');
            }))
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            ->orderBy('order', 'asc')->orderBy('id', 'asc')->paginate(9)->withQueryString();

        $categories = CategoryService::withCount(['services' => fn ($q) => $q->where('status', true)])
            ->orderBy('title')
            ->get();
        $totalServicesCount = Service::where('status', true)->count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.components.service-search-results', compact('services'))->render(),
            ]);
        }

        $labelService = ServiceSection::first();
        $allServicesList = Service::where('status', true)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        $allMobileServices = $allServicesList;

        return view('frontend.services', compact('services', 'categories', 'labelService', 'totalServicesCount', 'allMobileServices', 'allServicesList'));
    }

    public function serviceShow($slug)
    {
        $data = Cache::remember("frontend:service:{$slug}", FrontendCache::TTL, function () use ($slug) {
            $service = Service::with('category:id,title,slug')
                ->where('status', true)
                ->where('slug', $slug)
                ->firstOrFail();

            if (is_string($service->images)) {
                $service->images = json_decode($service->images, true);
            }
            if (is_string($service->projects_data)) {
                $service->projects_data = json_decode($service->projects_data, true);
            }
            if (is_string($service->workflow_data)) {
                $service->workflow_data = json_decode($service->workflow_data, true);
            }
            if (is_string($service->portfolios_data)) {
                $service->portfolios_data = json_decode($service->portfolios_data, true);
            }
            $labelService = ServiceSection::first();
            $steps = OrderStep::orderBy('order', 'asc')->get();
            $partners = Partner::with('category')->latest()->get();
            $superiorities = SuperiorityIcon::latest()->get();
            $labelSuperiority = LabelSuperiorityIcon::first();
            $contact = Contact::first();

            $allServices = Service::where('status', true)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
            $allServices->each(function ($s) {
                if (is_string($s->portfolios_data)) {
                    $s->portfolios_data = json_decode($s->portfolios_data, true);
                }
            });

            return compact(
                'service',
                'labelService',
                'steps',
                'partners',
                'superiorities',
                'labelSuperiority',
                'contact',
                'allServices',
            );
        });

        return view('frontend.service-details', $data);
    }

    public function businessFieldShow($slug)
    {
        $data = Cache::remember("frontend:business_field:{$slug}", FrontendCache::TTL, function () use ($slug) {
            $businessField = BusinessField::where('status', true)
                ->where('slug', $slug)
                ->firstOrFail();

            $labelBusinessField = SectionBusiness::first();
            $steps = OrderStep::orderBy('order', 'asc')->get();

            return compact(
                'businessField',
                'labelBusinessField',
                'steps',
            );
        });

        return view('frontend.business-field-details', $data);
    }

    public function products(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:255'],
            'context' => ['nullable', 'in:catalog,sidebar'],
        ]);
        $products = Product::with('category')->where('status', true)
            ->when($filters['q'] ?? null, fn ($query, $q) => $query->where(function ($search) use ($q) {
                $search->where('title', 'like', '%'.$q.'%')->orWhere('overview', 'like', '%'.$q.'%');
            }))
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            ->orderBy('order')->latest()->paginate(9)->withQueryString();
        $categories = CategoryProduct::orderBy('title')->get();

        if ($request->ajax()) {
            $view = ($filters['context'] ?? 'catalog') === 'sidebar'
                ? 'frontend.components.product-sidebar-search-results'
                : 'frontend.components.product-search-results';

            return response()->json([
                'html' => view($view, compact('products'))->render(),
            ]);
        }

        $labelProduct = SectionProduct::first();
        $partners = Partner::with('category')->latest()->get();
        $labelPartner = SectionPartner::first();
        $contact = Contact::first();
        $allMobileProducts = Product::with('category')->where('status', true)->orderBy('order', 'asc')->latest()->get();

        return view('frontend.products', compact('products', 'categories', 'labelProduct', 'partners', 'labelPartner', 'contact', 'allMobileProducts'));
    }

    public function productShow(string $slug)
    {
        $data = Cache::remember("frontend:product:{$slug}", FrontendCache::TTL, function () use ($slug) {
            $product = Product::with([
                'category',
                'featureSection.features' => fn ($q) => $q->where('status', true)->orderBy('order')->orderBy('id'),
                'ecosystemSection',
                'solutionSection.solutions' => fn ($q) => $q->where('status', true)->orderBy('order')->orderBy('id'),
                'productOverview',
                'faqs' => fn ($q) => $q->where('status', true)->orderBy('order')->orderBy('id'),
            ])
                ->where('status', true)
                ->where(function ($query) use ($slug) {
                    $query->where('slug', $slug)
                        ->orWhereHas('category', function ($q) use ($slug) {
                            $q->where('slug', $slug);
                        });
                })
                ->firstOrFail();

            $categories = CategoryProduct::orderBy('order')->orderBy('title')->get();
            $latestProducts = Product::with('category')
                ->where('status', true)
                ->where('id', '!=', $product->getKey())
                ->latest()
                ->take(5)
                ->get();

            $labelProduct = SectionProduct::first();

            $partners = Partner::with('category')->latest()->get();
            $labelPartner = SectionPartner::first();
            $labelTestimonial = SectionTestimonial::first() ?? LabelSuperiority::first();
            $testimonials = Testimonial::latest()->get();
            if ($testimonials->isEmpty()) {
                $testimonials = Superiority::latest()->get();
            }
            $labelSuperiorityImage = $labelTestimonial;
            $superioritiesImage = $testimonials;

            return compact(
                'product',
                'categories',
                'latestProducts',
                'labelProduct',
                'partners',
                'testimonials',
                'labelTestimonial',
                'superioritiesImage',
                'labelPartner',
                'labelSuperiorityImage'
            );
        });

        return view('frontend.product-details', $data);
    }

    public function categoryProductShow(string $slug)
    {
        $data = Cache::remember("frontend:category_product:{$slug}", FrontendCache::TTL, function () use ($slug) {
            $category = CategoryProduct::with([
                'products' => fn ($q) => $q->where('status', true)->orderBy('order')->orderBy('title'),
            ])
                ->where('status', true)
                ->where('slug', $slug)
                ->firstOrFail();

            $categories = CategoryProduct::where('status', true)
                ->orderBy('order')
                ->orderBy('title')
                ->get();

            $labelProduct = SectionProduct::first();
            $partners = Partner::with('category')->latest()->get();
            $labelPartner = SectionPartner::first();

            return compact(
                'category',
                'categories',
                'labelProduct',
                'partners',
                'labelPartner'
            );
        });

        return view('frontend.category-product-details', $data);
    }

    public function projects()
    {
        $projects = Project::with('category')
            ->latest()
            ->paginate(4);

        $labelProject = SectionProject::first();

        return view('frontend.projects', compact(
            'projects',
            'labelProject',
        ));
    }

    public function projectShow($slug)
    {
        $data = Cache::remember("frontend:project:{$slug}", FrontendCache::TTL, function () use ($slug) {
            $project = Project::with([
                'category',
                'partnerSection.partners' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'superioritySection.superiorities' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'featureSection.features' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'whyUsSection.items' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'ctaSection',
            ])
                ->where('slug', $slug)
                ->firstOrFail();

            $isComparison = (bool) ($project->partnerSection || $project->superioritySection || $project->featureSection || $project->whyUsSection || $project->ctaSection);

            if ($isComparison) {
                $labelPartner = SectionPartner::first();
                $partners = Partner::latest()->get();

                return [
                    'view' => 'frontend.comparison-zahir',
                    'compact' => compact('project', 'labelPartner', 'partners', 'slug'),
                ];
            }

            $labelProject = SectionProject::first();

            $relatedProjects = Project::with('category')
                ->where('id', '!=', $project->getKey())
                ->when($project->id_category, function ($query, $categoryId) {
                    $query->where('id_category', $categoryId);
                })
                ->latest()
                ->take(3)
                ->get();

            $latestProjects = Project::with('category')
                ->where('id', '!=', $project->getKey())
                ->latest()
                ->take(4)
                ->get();

            return [
                'view' => 'frontend.project-details',
                'compact' => compact(
                    'project',
                    'relatedProjects',
                    'latestProjects',
                    'labelProject'
                ),
            ];
        });

        return view($data['view'], $data['compact']);
    }

    public function categoryService($slug)
    {
        $category = CategoryService::where('slug', $slug)->firstOrFail();

        $firstService = Service::where('status', true)
            ->where('id_category', $category->id)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if ($firstService) {
            return redirect()->route('service.show', $firstService->slug);
        }

        return redirect()->route('index');
    }

    public function galleries()
    {
        $data = Cache::remember('frontend:galleries', FrontendCache::TTL, function () {
            $labelGallery = SectionGallery::query()->first();
            $galleries = Gallery::latest()->get();

            return compact(
                'labelGallery',
                'galleries'
            );
        });

        return view('frontend.galleries', $data);
    }

    public function blogs(Request $request)
    {
        $search = $request->search;

        $baseQuery = Blog::with('categoryBlog')

            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });

        if ($search) {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('overview', 'like', "%{$search}%");
            });
        }

        $trendingBlog = (clone $baseQuery)
            ->latest('published_at')
            ->first();

        $featuredBlogs = (clone $baseQuery)
            ->latest('published_at')
            ->take(5)
            ->get();

        $latestBlogs = (clone $baseQuery)
            ->latest('published_at')
            ->take(8)
            ->get();

        $categories = CategoryBlog::whereHas('blogs', function ($query) {
            $query
                ->where(function ($query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                });
        })
            ->with([
                'blogs' => function ($query) {
                    $query
                        ->where(function ($query) {
                            $query->whereNull('published_at')
                                ->orWhere('published_at', '<=', now());
                        })
                        ->latest('published_at');
                },
            ])
            ->orderBy('title')
            ->get();

        $labelBlog = LabelBlog::first();

        return view('frontend.blogs', compact(
            'trendingBlog',
            'featuredBlogs',
            'latestBlogs',
            'categories',
            'search',
            'labelBlog',
        ));
    }

    public function eventsPromotions(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $published = fn ($query) => $query->whereNull('published_at')
            ->orWhere('published_at', '<=', now());

        $blogs = Blog::with('categoryBlog')
            ->whereHas('categoryBlog', fn ($query) => $this->eventPromotionCategory($query))
            ->where($published)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($searchQuery) use ($keyword) {
                    $searchQuery->where('title', 'LIKE', "%{$keyword}%")
                        ->orWhere('overview', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%")
                        ->orWhere('meta_keywords', 'LIKE', "%{$keyword}%");
                });
            })
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        $latestBlogs = Blog::with(['categoryBlog', 'user'])
            ->whereHas('categoryBlog', fn ($query) => $this->eventPromotionCategory($query))
            ->where($published)
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = collect();
        $labelBlog = LabelBlog::first();

        return view('frontend.events-promotions', compact(
            'blogs',
            'categories',
            'latestBlogs',
            'labelBlog',
        ));
    }

    public function blogShow($slug)
    {
        $published = function ($query) {
            $query->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
        };

        $data = Cache::remember("frontend:blog:{$slug}", FrontendCache::TTL, function () use ($slug, $published) {
            $blog = Blog::with([
                'categoryBlog:id,title,slug',
                'user:id,name',
            ])
                ->where('slug', $slug)
                ->where($published)
                ->firstOrFail();

            $latestBlogs = Blog::with('categoryBlog:id,title,slug')
                ->where($published)
                ->whereKeyNot($blog->getKey())
                ->orderByDesc('published_at')
                ->latest('id')
                ->take(4)
                ->get();

            $relatedBlogs = Blog::with('categoryBlog:id,title,slug')
                ->where($published)
                ->whereKeyNot($blog->getKey())
                ->orderByRaw('CASE WHEN id_category = ? THEN 0 ELSE 1 END', [$blog->id_category])
                ->orderByDesc('published_at')
                ->latest('id')
                ->take(3)
                ->get();

            $categories = CategoryBlog::withCount([
                'blog as published_blogs_count' => $published,
            ])
                ->orderBy('title')
                ->get();

            $labelBlog = LabelBlog::first();

            return compact(
                'blog',
                'latestBlogs',
                'relatedBlogs',
                'categories',
                'labelBlog'
            );
        });

        if ($data['blog']->categoryBlog?->isEventPromotion() && \Illuminate\Support\Facades\Route::has('events-promotions.show')) {
            return redirect()->route('events-promotions.show', $data['blog']->slug);
        }

        Blog::where('id', $data['blog']->id)->increment('views');

        return view('frontend.blog-details', $data);
    }

    public function eventPromotionShow($slug)
    {
        $published = fn ($query) => $query->whereNull('published_at')
            ->orWhere('published_at', '<=', now());

        $data = Cache::remember("frontend:event_promotion:{$slug}", FrontendCache::TTL, function () use ($slug, $published) {
            $blog = Blog::with(['categoryBlog:id,title,slug', 'user:id,name'])
                ->where('slug', $slug)
                ->where($published)
                ->firstOrFail();

            $latestBlogs = Blog::with('categoryBlog:id,title,slug')
                ->whereHas('categoryBlog', fn ($query) => $this->eventPromotionCategory($query))
                ->where($published)
                ->whereKeyNot($blog->getKey())
                ->orderByDesc('published_at')
                ->latest('id')
                ->take(4)
                ->get();

            $relatedBlogs = Blog::with('categoryBlog:id,title,slug')
                ->whereHas('categoryBlog', fn ($query) => $this->eventPromotionCategory($query))
                ->where($published)
                ->whereKeyNot($blog->getKey())
                ->orderByDesc('published_at')
                ->latest('id')
                ->take(3)
                ->get();

            $categories = collect();
            $labelBlog = LabelBlog::first();

            return compact(
                'blog',
                'latestBlogs',
                'relatedBlogs',
                'categories',
                'labelBlog'
            );
        });

        if (! $data['blog']->categoryBlog?->isEventPromotion()) {
            return redirect()->route('blog.show', $data['blog']->slug);
        }

        Blog::where('id', $data['blog']->id)->increment('views');

        return view('frontend.event-promotion-details', $data);
    }

    private function eventPromotionCategory($query)
    {
        return $query->whereIn('slug', ['acara', 'promosi'])
            ->orWhereIn('title', ['Acara & Promosi', 'Acara dan Promosi']);
    }

    public function blogSearch(Request $request)
    {
        $keyword = trim((string) ($request->query('q') ?? $request->query('search') ?? ''));

        if (mb_strlen($keyword) < 1) {
            return response()->json([]);
        }

        $keywords = array_filter(explode(' ', $keyword));

        $blogs = Blog::with('categoryBlog:id,title,slug')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->when(
                $request->query('section') === 'events-promotions',
                fn ($query) => $query->whereHas(
                    'categoryBlog',
                    fn ($categoryQuery) => $this->eventPromotionCategory($categoryQuery)
                ),
                fn ($query) => $query->whereDoesntHave(
                    'categoryBlog',
                    fn ($categoryQuery) => $this->eventPromotionCategory($categoryQuery)
                )
            )
            ->where(function ($query) use ($keyword, $keywords) {
                $query->where(function ($sub) use ($keyword) {
                    $sub->where('title', 'LIKE', "%{$keyword}%")
                        ->orWhere('overview', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%")
                        ->orWhere('meta_keywords', 'LIKE', "%{$keyword}%");
                });
                if (count($keywords) > 1) {
                    foreach ($keywords as $word) {
                        $query->orWhere('title', 'LIKE', "%{$word}%");
                    }
                }
            })
            ->orderByRaw('CASE WHEN title LIKE ? THEN 0 WHEN title LIKE ? THEN 1 ELSE 2 END', ["{$keyword}%", "%{$keyword}%"])
            ->latest('published_at')
            ->limit(7)
            ->get()
            ->map(fn ($blog) => [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'url' => route('blog.show', $blog->slug),
                'image' => $blog->image ? asset($blog->image) : asset('assets/images/default.jpg'),
                'category' => $blog->categoryBlog?->title ?? 'Artikel',
                'date' => ($blog->published_at ?? $blog->created_at)?->translatedFormat('d M Y'),
            ]);

        return response()->json($blogs);
    }

    public function categoryBlog($slug)
    {
        $category = CategoryBlog::where('slug', $slug)->firstOrFail();

        $published = function ($query) {
            $query->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
        };

        $baseQuery = Blog::with('categoryBlog')
            ->where('id_category', $category->id)
            ->where($published);

        $trendingBlog = (clone $baseQuery)->latest('published_at')->first();
        $featuredBlogs = (clone $baseQuery)->latest('published_at')->take(5)->get();
        $latestBlogs = (clone $baseQuery)->latest('published_at')->paginate(9);
        $blogs = $latestBlogs;

        $categories = CategoryBlog::withCount('blog')->get();
        $labelBlog = LabelBlog::first();

        return view('frontend.blogs', compact(
            'blogs',
            'category',
            'categories',
            'trendingBlog',
            'featuredBlogs',
            'latestBlogs',
            'labelBlog'
        ));
    }

    public function keyword($keyword)
    {
        $blogs = Blog::with('categoryBlog')
            ->where('meta_keywords', 'LIKE', '%'.$keyword.'%')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->paginate(9);

        $categories = CategoryBlog::all();
        $sectionBlog = LabelBlog::first();

        return view('frontend.blogs', compact(
            'blogs',
            'categories',
            'sectionBlog',
            'keyword'
        ));
    }

    public function contact()
    {
        $data = Cache::remember('frontend:contact', FrontendCache::TTL, function () {
            $contact = Contact::first();
            $services = Service::where('status', true)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();

            return compact('contact', 'services');
        });

        return view('frontend.contact', $data);
    }

    public function comparisonShow(string $slug = 'total-vs-zahir')
    {
        $data = Cache::remember("frontend:comparison:{$slug}", FrontendCache::TTL, function () use ($slug) {
            $project = Project::with([
                'partnerSection.partners' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'superioritySection.superiorities' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'featureSection.features' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'whyUsSection.items' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                'ctaSection',
            ])
                ->where('slug', $slug)
                ->first();

            if (! $project) {
                $project = Project::with([
                    'partnerSection.partners' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                    'superioritySection.superiorities' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                    'featureSection.features' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                    'whyUsSection.items' => fn ($query) => $query->orderBy('order')->orderBy('id'),
                    'ctaSection',
                ])->first();
            }

            $labelPartner = SectionPartner::first();
            $partners = Partner::with('category')->latest()->get();

            return compact('project', 'labelPartner', 'partners', 'slug');
        });

        return view('frontend.comparison-zahir', $data);
    }

    public function portfolios(Request $request)
    {
        $categorySlug = $request->query('kategori') ?? $request->query('category');

        $categories = CategoryPortofolio::withCount('portofolios')->orderBy('id', 'asc')->get();

        $portfoliosQuery = Portofolio::with('category')->latest();

        if ($categorySlug) {
            $portfoliosQuery->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $portfolios = $portfoliosQuery->get();
        $labelPortofolio = SectionPortofolio::first();
        $partners = Partner::with('category')->latest()->get();
        $labelPartner = SectionPartner::first();
        $contact = Contact::first();

        return view('frontend.portfolios', compact(
            'categories',
            'portfolios',
            'labelPortofolio',
            'partners',
            'labelPartner',
            'contact',
            'categorySlug'
        ));
    }
}
