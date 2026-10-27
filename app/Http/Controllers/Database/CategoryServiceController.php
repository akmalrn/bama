<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Intervention\Image\ImageManager;

class CategoryServiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat kategori-layanan', only: ['index']),
            new Middleware('permission:buat kategori-layanan', only: ['create', 'store']),
            new Middleware('permission:edit kategori-layanan', only: ['edit', 'update']),
            new Middleware('permission:hapus kategori-layanan', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        return redirect()->route('dashboard-admin.services.index')->with('activeTab', 'tab-category');
    }


    public function create()
    {
        return view('pages.services.categorys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $category = new CategoryService();
        $category->title = $request->title;

        $category->save();

        return redirect()->route('dashboard-admin.services.index')->with('success', 'Kategori berhasil ditambahkan.')->with('activeTab', 'tab-category');
    }

    public function edit($id)
    {
        $category = CategoryService::findOrFail($id);
        return view('pages.services.categorys.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $category = CategoryService::findOrFail($id);
        $category->title = $request->title;

        $category->save();

        return redirect()->route('dashboard-admin.services.index')->with('success', 'Kategori berhasil diperbarui.')->with('activeTab', 'tab-category');
    }

    public function destroy($id)
    {
        $category = CategoryService::findOrFail($id);

        $category->delete();
        return redirect()->route('dashboard-admin.services.index')->with('success', 'Kategori berhasil dihapus.')->with('activeTab', 'tab-category');
    }
}
