<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryPartner;
use App\Models\Database\Partner;
use App\Models\Database\SectionPartner;
use App\Support\FrontendCache;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Intervention\Image\ImageManager;

class PartnerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat partner', only: ['index']),
            new Middleware('permission:buat partner', only: ['create', 'store']),
            new Middleware('permission:edit partner', only: ['edit', 'update']),
            new Middleware('permission:hapus partner', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $partners = Partner::with('category')->latest()->get();
        $categories = CategoryPartner::latest()->get();
        $label = SectionPartner::first();

        return view('pages.partners.index', compact('partners', 'categories', 'label'));
    }

    public function create()
    {
        $categories = CategoryPartner::all();

        return view('pages.partners.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'id_category' => 'nullable|exists:categories_partner,id',
            'is_slider' => 'nullable|boolean',
            'image' => 'required|mimes:jpg,jpeg,png,webp',
        ], [
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $partner = new Partner;
        $partner->name = $request->name;
        $partner->id_category = $request->id_category;
        $partner->is_slider = $request->boolean('is_slider');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'-'.pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $imagePath = 'uploads/partners';
            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 500)
                ->toWebp(90);

            file_put_contents($imagePath.'/'.$imageName, $img);
            $partner->image = $imagePath.'/'.$imageName;
        }

        $partner->save();
        FrontendCache::forgetHome();

        return redirect()->route('dashboard-admin.partners.index')->with('success', 'partner berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        $categories = CategoryPartner::all();

        return view('pages.partners.edit', compact('partner', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'id_category' => 'nullable|exists:categories_partner,id',
            'is_slider' => 'nullable|boolean',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp',
        ]);

        $partner = Partner::findOrFail($id);
        $partner->name = $request->name;
        $partner->id_category = $request->id_category;
        $partner->is_slider = $request->boolean('is_slider');

        if ($request->hasFile('image')) {
            if ($partner->image && file_exists(($partner->image))) {
                @unlink(($partner->image));
            }

            $image = $request->file('image');
            $imageName = time().'-'.pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $imagePath = 'uploads/partners';
            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 500)
                ->toWebp(90);

            file_put_contents($imagePath.'/'.$imageName, $img);
            $partner->image = $imagePath.'/'.$imageName;
        }

        $partner->save();
        FrontendCache::forgetHome();

        return redirect()->route('dashboard-admin.partners.index')->with('success', 'partner berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $partner = partner::findOrFail($id);
        if ($partner->image && file_exists(($partner->image))) {
            @unlink(($partner->image));
        }
        if ($partner->bg_content && file_exists(($partner->bg_content))) {
            @unlink(($partner->bg_content));
        }
        $partner->delete();
        FrontendCache::forgetHome();

        return redirect()->route('dashboard-admin.partners.index')->with('success', 'partner berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'span' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        SectionPartner::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.partners.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
