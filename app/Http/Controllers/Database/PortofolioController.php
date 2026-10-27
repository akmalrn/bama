<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryPortofolio;
use App\Models\Database\Portofolio;
use App\Models\Database\SectionPortofolio;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\ImageManager;

class PortofolioController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat portofolio', only: ['index']),
            new Middleware('permission:buat portofolio', only: ['create', 'store']),
            new Middleware('permission:edit portofolio', only: ['edit', 'update']),
            new Middleware('permission:hapus portofolio', only: ['destroy']),
            new Middleware('permission:edit label portofolio', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $label = SectionPortofolio::first();
        $datas = Portofolio::with('category')->latest()->get();
        $categories = CategoryPortofolio::withCount('portofolios')->latest()->get();

        return view('pages.portofolios.index', compact('label', 'datas', 'categories'));
    }

    public function create()
    {
        $categories = CategoryPortofolio::orderBy('name')->get();

        return view('pages.portofolios.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_category' => 'nullable|exists:categories_portofolio,id',
            'name' => 'required|string|max:255',
            'year' => 'required|numeric',
            'images.*' => 'nullable|mimes:jpg,jpeg,png,webp',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $imageName = time().'-'.uniqid().'.webp';
                $imagePath = 'uploads/portofolios';

                if (! file_exists($imagePath)) {
                    mkdir($imagePath, 0755, true);
                }

                $img = ImageManager::gd()
                    ->read($image->getRealPath())
                    ->scaleDown(width: 1200)
                    ->toWebp(80);

                file_put_contents($imagePath.'/'.$imageName, $img);

                $imagePaths[] = $imagePath.'/'.$imageName;
            }
        }

        Portofolio::create([
            'id_category' => $request->id_category,
            'name' => $request->name,
            'year' => $request->year,
            'images' => $imagePaths,
        ]);

        return redirect()
            ->route('dashboard-admin.portofolios.index')
            ->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Portofolio::findOrFail($id);
        $categories = CategoryPortofolio::orderBy('name')->get();

        return view('pages.portofolios.edit', compact('data', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_category' => 'nullable|exists:categories_portofolio,id',
            'name' => 'required|string|max:255',
            'year' => 'required|numeric',
            'images.*' => 'nullable|mimes:jpg,jpeg,png,webp',
        ]);

        $portofolio = Portofolio::findOrFail($id);

        $oldImages = $portofolio->images ?? [];

        if ($request->deleted_images) {
            foreach ($request->deleted_images as $deleteImg) {
                if (file_exists($deleteImg)) {
                    unlink($deleteImg);
                }
            }

            $oldImages = array_values(array_diff($oldImages, $request->deleted_images));
        }

        $newImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $imageName = time().'-'.uniqid().'.webp';
                $imagePath = 'uploads/portofolios';

                if (! file_exists($imagePath)) {
                    mkdir($imagePath, 0755, true);
                }

                $img = ImageManager::gd()
                    ->read($image->getRealPath())
                    ->scaleDown(height: 1500)
                    ->toWebp(80);

                file_put_contents($imagePath.'/'.$imageName, $img);

                $newImages[] = $imagePath.'/'.$imageName;
            }
        }

        $allImages = array_merge($oldImages, $newImages);

        $portofolio->update([
            'id_category' => $request->id_category,
            'name' => $request->name,
            'year' => $request->year,
            'images' => $allImages,
        ]);

        return redirect()
            ->route('dashboard-admin.portofolios.index')
            ->with('success', 'Portofolio berhasil diupdate.');
    }

    public function destroy($id)
    {
        $portofolio = Portofolio::findOrFail($id);
        if ($portofolio->images && is_array($portofolio->images)) {
            foreach ($portofolio->images as $img) {
                if (file_exists($img)) {
                    @unlink($img);
                }
            }
        }

        $portofolio->delete();

        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.portofolios.index')
            ->with('success', 'Portofolio berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'span' => 'nullable|string|max:255',
            'subTitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'project_tag' => 'nullable|string|max:255',
            'project_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_image' => 'nullable|boolean',
        ]);

        $section = SectionPortofolio::first();
        $destinationDir = 'uploads/portofolios';

        if (! file_exists(($destinationDir))) {
            mkdir(($destinationDir), 0755, true);
        }

        if ($request->boolean('remove_image')) {
            if ($section && $section->image && file_exists(($section->image))) {
                @unlink(($section->image));
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($section && $section->image && file_exists(($section->image))) {
                @unlink(($section->image));
            }

            $file = $request->file('image');
            $filename = time().'-hero-portfolio-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';

            $img = ImageManager::gd()
                ->read($file->getRealPath())
                ->scaleDown(width: 1400)
                ->toWebp(80);

            file_put_contents(($destinationDir.'/'.$filename), (string) $img);
            $data['image'] = $destinationDir.'/'.$filename;
        }

        unset($data['remove_image']);

        SectionPortofolio::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.portofolios.index')
            ->with('success', 'Label portofolio berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
