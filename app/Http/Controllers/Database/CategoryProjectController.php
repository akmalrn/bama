<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryProject;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat kategori-projek', only: ['index']),
            new Middleware('permission:buat kategori-projek', only: ['create', 'store']),
            new Middleware('permission:edit kategori-projek', only: ['edit', 'update']),
            new Middleware('permission:hapus kategori-projek', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        return redirect()->route('dashboard-admin.projects.index')->with('activeTab', 'tab-category');
    }


    public function create()
    {
        return view('pages.projects.categorys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $category = new CategoryProject();
        $category->name = $request->name;
        $category->save();

        return redirect()->route('dashboard-admin.projects.index')->with('success', 'Kategori berhasil ditambahkan.')->with('activeTab', 'tab-category');
    }

    public function edit($id)
    {
        $category = CategoryProject::findOrFail($id);
        return view('pages.projects.categorys.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $category = CategoryProject::findOrFail($id);
        $category->name = $request->name;

        $category->save();

        return redirect()->route('dashboard-admin.projects.index')->with('success', 'Kategori berhasil diperbarui.')->with('activeTab', 'tab-category');
    }

    public function destroy($id)
    {
        $category = CategoryProject::findOrFail($id);
        $category->delete();
        return redirect()->route('dashboard-admin.projects.index')->with('success', 'Kategori berhasil dihapus.')->with('activeTab', 'tab-category');
    }
}
