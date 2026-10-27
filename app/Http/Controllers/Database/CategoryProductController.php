<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryProduct;
use App\Services\Image\WebpConversionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class CategoryProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat kategori-produk', only: ['index']),
            new Middleware('permission:buat kategori-produk', only: ['create', 'store']),
            new Middleware('permission:edit kategori-produk', only: ['edit', 'update']),
            new Middleware('permission:hapus kategori-produk', only: ['destroy']),
        ];
    }

    public function index()
    {
        return redirect()->route('dashboard-admin.products.index')->with('activeTab', 'tab-category');
    }

    public function create()
    {
        return view('pages.products.categorys.create');
    }

    public function store(Request $request, WebpConversionService $converter)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ], $this->messages());
        $data['status'] = $request->boolean('status');
        $data['icon'] = $this->sanitizeIconifyMarkup($data['icon'] ?? null);
        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), $converter);
        }
        CategoryProduct::create($data);

        return redirect()->route('dashboard-admin.products.index')->with('success', 'Kategori produk berhasil ditambahkan.')->with('activeTab', 'tab-category');
    }

    public function edit(CategoryProduct $categorys_product)
    {
        return view('pages.products.categorys.edit', ['category' => $categorys_product]);
    }

    public function update(Request $request, CategoryProduct $categorys_product, WebpConversionService $converter)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ], $this->messages());
        $data['status'] = $request->boolean('status');
        $data['icon'] = $this->sanitizeIconifyMarkup($data['icon'] ?? null);
        if ($request->hasFile('image')) {
            $this->deleteFile($categorys_product->image);
            $data['image'] = $this->storeImage($request->file('image'), $converter);
        }
        $categorys_product->update($data);

        return redirect()->route('dashboard-admin.products.index')->with('success', 'Kategori produk berhasil diperbarui.')->with('activeTab', 'tab-category');
    }

    public function destroy(CategoryProduct $categorys_product)
    {
        $this->deleteFile($categorys_product->image);
        $categorys_product->delete();

        return redirect()->route('dashboard-admin.products.index')->with('success', 'Kategori produk berhasil dihapus.')->with('activeTab', 'tab-category');
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Nama tombol kategori wajib diisi.',
            'name.max' => 'Nama tombol kategori maksimal 255 karakter.',
            'title.required' => 'Judul kategori wajib diisi.',
            'title.max' => 'Judul kategori maksimal 255 karakter.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
        ];
    }

    private function storeImage($file, WebpConversionService $converter): string
    {
        $path = 'uploads/products/categories/'.time().'-'.uniqid().'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
        $converter->convert($file->getRealPath(), $path);

        return $path;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && is_file($path)) {
            @unlink($path);
        }
    }

    private function sanitizeIconifyMarkup(?string $markup): ?string
    {
        $markup = trim((string) $markup);
        if ($markup === '') {
            return null;
        }

        if (! str_contains(strtolower($markup), '<iconify-icon')) {
            $icon = htmlspecialchars($markup, ENT_QUOTES, 'UTF-8');
            return '<iconify-icon icon="'.$icon.'"></iconify-icon>';
        }

        $markup = strip_tags($markup, '<iconify-icon>');
        $markup = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $markup);
        $markup = preg_replace('/(?:javascript\s*:|expression\s*\(|url\s*\()/i', '', $markup);

        return trim((string) $markup);
    }
}
