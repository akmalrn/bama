<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryPortofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryPortofolioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CategoryPortofolio::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()
            ->route('dashboard-admin.portofolios.index')
            ->with('success', 'Kategori Portofolio berhasil ditambahkan.')
            ->with('activeTab', 'tab-category');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = CategoryPortofolio::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()
            ->route('dashboard-admin.portofolios.index')
            ->with('success', 'Kategori Portofolio berhasil diperbarui.')
            ->with('activeTab', 'tab-category');
    }

    public function destroy($id)
    {
        $category = CategoryPortofolio::findOrFail($id);
        $category->delete();

        return redirect()
            ->route('dashboard-admin.portofolios.index')
            ->with('success', 'Kategori Portofolio berhasil dihapus.')
            ->with('activeTab', 'tab-category');
    }
}
