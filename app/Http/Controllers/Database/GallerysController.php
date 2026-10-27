<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryGallerys;
use App\Models\Database\Gallerys;
use App\Models\Database\SectionGallerys;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class GallerysController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat galeri-(kategori)|lihat kategori-galeri', only: ['index']),
            new Middleware('permission:buat galeri-(kategori)', only: ['create', 'store']),
            new Middleware('permission:edit galeri-(kategori)', only: ['edit', 'update']),
            new Middleware('permission:hapus galeri-(kategori)', only: ['destroy']),
            new Middleware('permission:edit label galeri-(kategori)', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $label = SectionGallerys::first();
        $datas = gallerys::latest()->get();
        $categories = CategoryGallerys::latest()->get();
        return view('pages.gallerys.index', compact('label', 'datas', 'categories'));
    }

    public function create()
    {
        $categories = Categorygallerys::all();
        return view('pages.gallerys.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_category' => 'required|exists:categories_gallerys,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:51200',
        ], [
            'id_category.required' => 'Kategori Galeri wajib dipilih.',
            'title.required' => 'Judul Galeri wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'image.mimes' => 'File harus berupa gambar atau video.',
        ]);

        $gallerys = new gallerys();
        $gallerys->id_category = $request->id_category;
        $gallerys->title = $request->title;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $extension = strtolower($file->getClientOriginalExtension());

            $uploadPath = 'uploads/gallerys';

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {

                $fileName = time() . '-' .
                    pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .
                    '.webp';

                $img = ImageManager::gd()
                    ->read($file->getRealPath())
                    ->scaleDown(width: 1200)
                    ->toWebp(80);

                file_put_contents($uploadPath . '/' . $fileName, $img);

                $gallerys->image = $uploadPath . '/' . $fileName;
            } else {

                $fileName = time() . '.' . $extension;

                $file->move(($uploadPath), $fileName);

                $gallerys->image = $uploadPath . '/' . $fileName;
            }
        }

        $gallerys->save();

        return redirect()
            ->route('dashboard-admin.gallerys.index')
            ->with('success', 'Galeri berhasil ditambahkan.');
    }
    public function edit($id)
    {
        $data = gallerys::findOrFail($id);

        $categories = CategoryGallerys::all();

        return view('pages.gallerys.edit', compact(
            'data',
            'categories'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_category' => 'required|exists:categories_gallerys,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:51200',
        ], [
            'id_category.required' => 'Kategori Galeri wajib diisi.',
            'title.required' => 'Judul Galeri wajib diisi.',
            'title.max' => 'Judul Galeri maksimal 255 karakter.',
            'image.mimes' => 'File harus berupa gambar atau video.',
        ]);

        $gallerys = gallerys::findOrFail($id);

        $gallerys->id_category = $request->id_category;
        $gallerys->title = $request->title;

        if ($request->hasFile('image')) {

            if ($gallerys->image && file_exists(($gallerys->image))) {
                @unlink(($gallerys->image));
            }

            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());

            $uploadPath = 'uploads/gallerys';

            if (!file_exists(($uploadPath))) {
                mkdir(($uploadPath), 0755, true);
            }

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {

                $fileName = time() . '-' .
                    pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .
                    '.webp';

                $img = ImageManager::gd()
                    ->read($file->getRealPath())
                    ->scaleDown(width: 1200)
                    ->toWebp(80);

                file_put_contents(
                    ($uploadPath . '/' . $fileName),
                    $img
                );

                $gallerys->image = $uploadPath . '/' . $fileName;
            }
            else {

                $fileName = time() . '.' . $extension;

                $file->move(
                    ($uploadPath),
                    $fileName
                );

                $gallerys->image = $uploadPath . '/' . $fileName;
            }
        }

        $gallerys->save();

        return redirect()
            ->route('dashboard-admin.gallerys.index')
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallerys = Gallerys::findOrFail($id);
        if ($gallerys->image && file_exists($gallerys->image)) {
            @unlink($gallerys->image);
        }
        $gallerys->delete();

        return redirect()
            ->route('dashboard-admin.gallerys.index')
            ->with('success', 'Galeri berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
            'span' => 'nullable|string',
        ]);

        SectionGallerys::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.gallerys.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
