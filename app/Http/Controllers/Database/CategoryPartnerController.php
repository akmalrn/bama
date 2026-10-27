<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryPartner;
use App\Support\FrontendCache;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryPartnerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat kategori-partner', only: ['index']),
            new Middleware('permission:buat kategori-partner', only: ['create', 'store']),
            new Middleware('permission:edit kategori-partner', only: ['edit', 'update']),
            new Middleware('permission:hapus kategori-partner', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        return redirect()->route('dashboard-admin.partners.index')->with('activeTab', 'tab-category');
    }

    public function create()
    {
        return view('pages.partners.categorys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'title.max' => 'Judul maksimal 255 karakter.',
        ]);

        $category = new CategoryPartner;
        $category->name = $request->name;
        $category->title = $request->filled('title') ? $request->title : $request->name;
        $category->save();

        FrontendCache::forgetShared();
        FrontendCache::forgetHome();

        return redirect()->route('dashboard-admin.partners.index')
            ->with('success', 'Kategori partner berhasil ditambahkan.')
            ->with('activeTab', 'tab-category');
    }

    public function edit($id)
    {
        $category = CategoryPartner::findOrFail($id);

        return view('pages.partners.categorys.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = CategoryPartner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ], [
            'title.required' => 'Judul kategori wajib diisi.',
            'title.max' => 'Judul kategori maksimal 255 karakter.',
        ]);

        $category->title = $request->title;
        $category->save();

        FrontendCache::forgetShared();
        FrontendCache::forgetHome();

        return redirect()->route('dashboard-admin.partners.index')
            ->with('success', 'Judul kategori partner berhasil diperbarui.')
            ->with('activeTab', 'tab-category');
    }

    public function destroy($id)
    {
        $category = CategoryPartner::findOrFail($id);

        if ($category->isSystemCategory()) {
            return redirect()->route('dashboard-admin.partners.index')
                ->with('error', 'Kategori ini merupakan kategori utama sistem dan tidak dapat dihapus karena akan merusak website.')
                ->with('activeTab', 'tab-category');
        }

        $category->delete();

        FrontendCache::forgetShared();
        FrontendCache::forgetHome();

        return redirect()->route('dashboard-admin.partners.index')
            ->with('success', 'Kategori partner berhasil dihapus.')
            ->with('activeTab', 'tab-category');
    }
}
