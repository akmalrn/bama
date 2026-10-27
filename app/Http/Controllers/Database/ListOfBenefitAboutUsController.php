<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\ListOfBenefitAboutUs;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ListOfBenefitAboutUsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat benefit-tentang-kami', only: ['index']),
            new Middleware('permission:buat benefit-tentang-kami', only: ['create', 'store']),
            new Middleware('permission:edit benefit-tentang-kami', only: ['edit', 'update']),
            new Middleware('permission:hapus benefit-tentang-kami', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $datas = ListOfBenefitAboutUs::latest()->get();
        return view('pages.abouts.benefits.index', compact('datas'));
    }

    public function create()
    {
        return view('pages.abouts.benefits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/about/benefits';
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 400)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);
            $data['image'] = $imagePath . '/' . $imageName;
        }

        ListOfBenefitAboutUs::create($data);

        return redirect()->route('dashboard-admin.list-of-benerfit-about-us.index')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $data = ListOfBenefitAboutUs::findOrFail($id);
        return view('pages.abouts.benefits.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
        ]);

        $data = ListOfBenefitAboutUs::findOrFail($id);
        $data->title = $validated['title'] ?? null;
        $data->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {

            if (!empty($data->image) && file_exists($data->image)) {
                @unlink($data->image);
            }

            $image = $request->file('image');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/about/benefits';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 400)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);

            $data->image = $imagePath . '/' . $imageName;
        }

        $data->save();

        return redirect()
            ->route('dashboard-admin.list-of-benerfit-about-us.index')
            ->with('success', 'Data berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $data = ListOfBenefitAboutUs::findOrFail($id);

        if (!empty($data->image) && file_exists(($data->image))) {
            @unlink(($data->image));
        }
        $data->delete();

        return redirect()->route('dashboard-admin.list-of-benerfit-about-us.index')->with('success', 'Data berhasil dihapus.');
    }
}
