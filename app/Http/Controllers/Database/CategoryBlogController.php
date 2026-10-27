<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryBlog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryBlogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat kategori-blog', only: ['index']),
            new Middleware('permission:buat kategori-blog', only: ['create', 'store']),
            new Middleware('permission:edit kategori-blog', only: ['edit', 'update']),
            new Middleware('permission:hapus kategori-blog', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        return redirect()->route('dashboard-admin.blogs.index')->with('activeTab', 'tab-category');
    }

    public function create()
    {
        return view('pages.blogs.categorys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $category = new CategoryBlog();
        $category->title = $request->title;
        $category->save();

        return redirect()->route('dashboard-admin.blogs.index')->with('success', 'Kategori berhasil ditambahkan.')->with('activeTab', 'tab-category');
    }

    public function edit($id)
    {
        $category = CategoryBlog::findOrFail($id);
        return view('pages.blogs.categorys.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $category = CategoryBlog::findOrFail($id);
        $category->title = $request->title;

        $category->save();

        return redirect()->route('dashboard-admin.blogs.index')->with('success', 'Kategori berhasil diperbarui.')->with('activeTab', 'tab-category');
    }

    public function destroy($id)
    {
        $category = CategoryBlog::findOrFail($id);

        if ($category->blog()->exists()) {
            return redirect()
                ->route('dashboard-admin.blogs.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $category->blog()->count() . ' blog.')
                ->with('activeTab', 'tab-category');
        }

        $category->delete();
        return redirect()->route('dashboard-admin.blogs.index')->with('success', 'Kategori berhasil dihapus.')->with('activeTab', 'tab-category');
    }
}
