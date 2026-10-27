<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryGallerys;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Intervention\Image\ImageManager;

class CategoryGallerysController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat kategori-galeri', only: ['index']),
            new Middleware('permission:buat kategori-galeri', only: ['create', 'store']),
            new Middleware('permission:edit kategori-galeri', only: ['edit', 'update']),
            new Middleware('permission:hapus kategori-galeri', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        return redirect()->route('dashboard-admin.gallerys.index')->with('activeTab', 'tab-category');
    }


    public function create()
    {
        return view('pages.gallerys.categorys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $category = new CategoryGallerys();
        $category->title = $request->title;

        $category->save();

        return redirect()->route('dashboard-admin.gallerys.index')->with('success', 'Kategori berhasil ditambahkan.')->with('activeTab', 'tab-category');
    }

    public function edit($id)
    {
        $category = CategoryGallerys::findOrFail($id);
        return view('pages.gallerys.categorys.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $category = CategoryGallerys::findOrFail($id);
        $category->title = $request->title;

        $category->save();

        return redirect()->route('dashboard-admin.gallerys.index')->with('success', 'Kategori berhasil diperbarui.')->with('activeTab', 'tab-category');
    }

    public function destroy($id)
    {
        $category = CategoryGallerys::findOrFail($id);     
        $category->delete();
        return redirect()->route('dashboard-admin.gallerys.index')->with('success', 'Kategori berhasil dihapus.')->with('activeTab', 'tab-category');
    }
}
