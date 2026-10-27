<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Blog;
use App\Models\Database\CategoryBlog;
use App\Models\Database\LabelBlog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\ImageManager;

class BlogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat blog|lihat kategori-blog', only: ['index']),
            new Middleware('permission:buat blog', only: ['create', 'store']),
            new Middleware('permission:edit blog', only: ['edit', 'update']),
            new Middleware('permission:hapus blog', only: ['destroy']),
            new Middleware('permission:edit blog', only: ['storeOrUpdate']),
        ];
    }

    private function normalizeDescriptionContent(?string $description): ?string
    {
        if (blank($description)) {
            return $description;
        }

        // Ubah <input ... type="image" ...> menjadi <img ...>
        $description = preg_replace_callback(
            '/<input\b([^>]*?)\/?>/i',
            function ($matches) {
                $attrs = $matches[1];
                if (preg_match('/type=["\']image["\']/i', $attrs)) {
                    $cleanedAttrs = preg_replace('/type=["\']image["\']/i', '', $attrs);
                    $cleanedAttrs = preg_replace('/style=["\'][^"\']*?(?:width|height)\s*:\s*\d+px[^"\']*?["\']/i', '', $cleanedAttrs);

                    return '<img '.trim($cleanedAttrs).' />';
                }

                return $matches[0];
            },
            $description
        );

        return $description;
    }

    private function moveTemporaryImages($description)
    {
        if (blank($description)) {
            return $description;
        }

        $description = $this->normalizeDescriptionContent($description);

        $tempPath = ('uploads/ckeditor/temp');
        $permanentPath = ('uploads/ckeditor');

        if (! file_exists($permanentPath)) {
            mkdir($permanentPath, 0755, true);
        }

        preg_match_all('/uploads\/ckeditor\/temp\/([^"\'<>\s#\?]+)/i', $description, $matches);

        if (! empty($matches[1])) {
            $uniqueFilenames = array_unique($matches[1]);
            foreach ($uniqueFilenames as $filename) {
                $tempFilePath = $tempPath.DIRECTORY_SEPARATOR.$filename;
                $permanentFilePath = $permanentPath.DIRECTORY_SEPARATOR.$filename;

                if (file_exists($tempFilePath)) {
                    copy($tempFilePath, $permanentFilePath);
                    @unlink($tempFilePath);
                }

                $description = str_replace(
                    'uploads/ckeditor/temp/'.$filename,
                    'uploads/ckeditor/'.$filename,
                    $description
                );
            }
        }

        return $description;
    }

    private function cleanOldTemporaryFiles()
    {
        $tempPath = ('uploads/ckeditor/temp');

        if (file_exists($tempPath) && is_dir($tempPath)) {
            $files = scandir($tempPath);
            $currentTime = time();
            $maxAge = 24 * 60 * 60;

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $tempPath.DIRECTORY_SEPARATOR.$file;
                    if (is_file($filePath)) {
                        $fileTime = filemtime($filePath);
                        if ($currentTime - $fileTime > $maxAge) {
                            @unlink($filePath);
                        }
                    }
                }
            }
        }
    }

    private function deleteUnusedCKEditorImages($oldDescription, $newDescription)
    {
        preg_match_all('/uploads\/ckeditor\/(?!temp\/)([^"\'<>\s#\?]+)/i', (string) $oldDescription, $oldMatches);
        preg_match_all('/uploads\/ckeditor\/(?!temp\/)([^"\'<>\s#\?]+)/i', (string) $newDescription, $newMatches);

        $oldImages = array_unique($oldMatches[1] ?? []);
        $newImages = array_unique($newMatches[1] ?? []);

        $unusedImages = array_diff($oldImages, $newImages);

        foreach ($unusedImages as $filename) {
            $filePath = ('uploads/ckeditor'.DIRECTORY_SEPARATOR.$filename);
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }

    public function index(Request $request)
    {
        $totalBlogs = Blog::count();
        $totalViews = Blog::sum('views');
        $label = LabelBlog::first();
        $query = Blog::with('categoryBlog');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('categoryBlog', function ($catQuery) use ($search) {
                        $catQuery->where('title', 'like', '%'.$search.'%');
                    });
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $blogs = $query->latest()->paginate($perPage)->withQueryString();
        $categories = CategoryBlog::withCount('blogs')->latest()->get();

        return view('pages.blogs.index', compact('blogs', 'categories', 'totalBlogs', 'totalViews', 'label'));
    }

    public function create()
    {
        $categories = CategoryBlog::all();

        return view('pages.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_category' => 'required|exists:categoriesblog,id',
            'title' => 'required|string|max:255|unique:blogs,title',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp',
            'overview' => 'nullable|string|max:500',
            'meta_keywords' => 'required|string',
            'meta_descriptions' => 'required|string',
            'published_at' => 'nullable|date',
        ], [
            'id_category.required' => 'Kategori wajib dipilih.',
            'title.required' => 'Judul wajib diisi.',
            'title.unique' => 'Judul blog sudah digunakan.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'overview.max' => 'Ringkasan maksimal 255 karakter.',
            'image.required' => 'Gambar wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
        ]);

        $blog = new Blog;
        $blog->id_category = $request->id_category;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->overview = $request->overview;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_descriptions = $request->meta_descriptions;
        $blog->published_at = $request->filled('published_at')
            ? $request->published_at
            : now();
        $blog->user_id = auth()->id();

        $blog->description = $this->moveTemporaryImages($blog->description);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'-'.pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $imagePath = 'uploads/blogs';
            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 800)
                ->toWebp(80);

            file_put_contents($imagePath.'/'.$imageName, $img);
            $blog->image = $imagePath.'/'.$imageName;
        }

        $blog->save();

        Artisan::call('sitemap:generate');

        $this->cleanOldTemporaryFiles();

        return redirect()->route('dashboard-admin.blogs.index')->with('success', 'Blog berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = CategoryBlog::all();

        return view('pages.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_category' => 'required|exists:categoriesblog,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
            'overview' => 'nullable|string|max:500',
            'meta_keywords' => 'required|string',
            'meta_descriptions' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        $blog = Blog::findOrFail($id);

        $oldDescription = $blog->description;

        $blog->id_category = $request->id_category;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->overview = $request->overview;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_descriptions = $request->meta_descriptions;
        $blog->published_at = $request->published_at;

        $blog->description = $this->moveTemporaryImages($blog->description);

        $this->deleteUnusedCKEditorImages($oldDescription, $blog->description);

        if ($request->hasFile('image')) {
            if ($blog->image && file_exists(($blog->image))) {
                @unlink(($blog->image));
            }

            $image = $request->file('image');
            $imageName = time().'-'.pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $imagePath = 'uploads/blogs';
            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 800)
                ->toWebp(80);

            file_put_contents($imagePath.'/'.$imageName, $img);
            $blog->image = $imagePath.'/'.$imageName;
        }

        $blog->save();

        Artisan::call('sitemap:generate');

        $this->cleanOldTemporaryFiles();

        return redirect()->route('dashboard-admin.blogs.index')->with('success', 'Blog berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image && file_exists(($blog->image))) {
            @unlink(($blog->image));
        }

        if ($blog->description) {
            preg_match_all('/uploads\/ckeditor\/(?!temp\/)([^"\'<>\s#\?]+)/i', $blog->description, $matches);
            foreach (array_unique($matches[1] ?? []) as $filename) {
                $filePath = ('uploads/ckeditor'.DIRECTORY_SEPARATOR.$filename);
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        $blog->delete();

        Artisan::call('sitemap:generate');

        return redirect()->route('dashboard-admin.blogs.index')->with('success', 'Blog berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
            'span' => 'nullable|string',
        ]);

        LabelBlog::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.blogs.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
