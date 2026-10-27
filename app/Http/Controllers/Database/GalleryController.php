<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Database\Gallery;
use App\Models\Database\SectionGallery;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GalleryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat galeri', only: ['index']),
            new Middleware('permission:buat galeri', only: ['create', 'store']),
            new Middleware('permission:edit galeri', only: ['edit', 'update']),
            new Middleware('permission:hapus galeri', only: ['destroy']),
            new Middleware('permission:edit label galeri', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $galleries = Gallery::latest()->get();
        $label = SectionGallery::first();
        return view('pages.galleries.index', compact('galleries', 'label'));
    }

    public function create()
    {
        return view('pages.galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|mimes:jpg,jpeg,png,webp',
        ]);

        $gallery = new Gallery();
        $gallery->title = $request->title;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/galleries';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);
            $gallery->image = $imagePath . '/' . $imageName;
        }

        $gallery->save();

        return redirect()
            ->route('dashboard-admin.galleries.index')
            ->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('pages.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp',
        ]);

        $gallery = Gallery::findOrFail($id);
        $gallery->title = $request->title;

        if ($request->hasFile('image')) {

            if ($gallery->image && file_exists($gallery->image)) {
                @unlink($gallery->image);
            }

            $image = $request->file('image');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/galleries';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);
            $gallery->image = $imagePath . '/' . $imageName;
        }

        $gallery->save();

        return redirect()
            ->route('dashboard-admin.galleries.index')
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if ($gallery->image && file_exists($gallery->image)) {
            @unlink($gallery->image);
        }

        $gallery->delete();

        return redirect()
            ->route('dashboard-admin.galleries.index')
            ->with('success', 'Galer berhasil dihapus.');
    }
    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
            'span' => 'nullable|string',
        ]);

        SectionGallery::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.galleries.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
