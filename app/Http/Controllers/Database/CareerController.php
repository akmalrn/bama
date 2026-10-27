<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Career;
use App\Models\Database\CareerPosition;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Intervention\Image\ImageManager;

class CareerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat karir', only: ['create']),
            new Middleware('permission:edit karir', only: ['storeOrUpdate']),
        ];
    }

    public function create()
    {
        $career = Career::first();
        $positions = CareerPosition::orderBy('order')->orderBy('id')->get();

        return view('pages.careers.create', compact('career', 'positions'));
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'hero_badge' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_hero_image' => 'nullable|boolean',
            'hero_button_text' => 'nullable|string|max:255',
            'hero_button_link' => 'nullable|string|max:1000',
            'hero_button_2_text' => 'nullable|string|max:255',
            'hero_button_2_link' => 'nullable|string|max:1000',

            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_image' => 'nullable|boolean',
            'description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_descriptions' => 'nullable|string',
            'cta_title' => 'nullable|string|max:255',
            'cta_description' => 'nullable|string',
            'cta_button_text' => 'nullable|string|max:255',
            'cta_button_link' => 'nullable|string|max:1000',
            'cta_button_icon' => 'nullable|string|max:255',
        ], [
            'hero_title.string' => 'Judul Hero harus berupa teks.',
            'hero_title.max' => 'Judul Hero maksimal 255 karakter.',
            'hero_image.image' => 'File Hero harus berupa gambar.',
            'hero_image.mimes' => 'Format gambar Hero harus JPG, JPEG, PNG, atau WEBP.',
            'hero_image.max' => 'Ukuran gambar Hero maksimal 5MB.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $career = Career::find(1);
        $destinationDir = 'uploads/career';

        if (! file_exists(($destinationDir))) {
            mkdir(($destinationDir), 0755, true);
        }

        // Hapus Hero Image jika diminta
        if ($request->boolean('remove_hero_image')) {
            if ($career && $career->hero_image && file_exists(($career->hero_image))) {
                @unlink(($career->hero_image));
            } elseif ($career && $career->hero_image && file_exists($career->hero_image)) {
                @unlink($career->hero_image);
            }
            $data['hero_image'] = null;
        }

        // Upload Hero Image baru
        if ($request->hasFile('hero_image')) {
            if ($career && $career->hero_image && file_exists(($career->hero_image))) {
                @unlink(($career->hero_image));
            } elseif ($career && $career->hero_image && file_exists($career->hero_image)) {
                @unlink($career->hero_image);
            }

            $heroFile = $request->file('hero_image');
            $heroFilename = time().'-hero-career-'.pathinfo($heroFile->getClientOriginalName(), PATHINFO_FILENAME).'.webp';

            $heroImg = ImageManager::gd()
                ->read($heroFile->getRealPath())
                ->scaleDown(width: 1400)
                ->toWebp(80);

            file_put_contents(($destinationDir.'/'.$heroFilename), (string) $heroImg);
            $data['hero_image'] = $destinationDir.'/'.$heroFilename;
        }

        // Hapus gambar konten jika diminta
        if ($request->boolean('remove_image')) {
            if ($career && $career->image && file_exists(($career->image))) {
                @unlink(($career->image));
            } elseif ($career && $career->image && file_exists($career->image)) {
                @unlink($career->image);
            }
            $data['image'] = null;
        }

        // Upload gambar konten baru
        if ($request->hasFile('image')) {
            if ($career && $career->image && file_exists(($career->image))) {
                @unlink(($career->image));
            } elseif ($career && $career->image && file_exists($career->image)) {
                @unlink($career->image);
            }

            $file = $request->file('image');
            $filename = time().'-career-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';

            $img = ImageManager::gd()
                ->read($file->getRealPath())
                ->scaleDown(width: 1400)
                ->toWebp(80);

            file_put_contents(($destinationDir.'/'.$filename), (string) $img);
            $data['image'] = $destinationDir.'/'.$filename;
        }

        unset($data['remove_image'], $data['remove_hero_image']);

        Career::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.careers.create')
            ->with('success', 'Data Karir berhasil disimpan.');
    }
}
