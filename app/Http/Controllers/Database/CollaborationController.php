<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Collaboration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Intervention\Image\ImageManager;

class CollaborationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat kerja-sama', only: ['create']),
            new Middleware('permission:edit kerja-sama', only: ['storeOrUpdate']),
        ];
    }

    public function create()
    {
        $collaboration = Collaboration::first();

        return view('pages.collaborations.create', compact('collaboration'));
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

        $collaboration = Collaboration::find(1);
        $destinationDir = 'uploads/collaboration';

        if (! file_exists(($destinationDir))) {
            mkdir(($destinationDir), 0755, true);
        }

        // Hapus Hero Image jika diminta
        if ($request->boolean('remove_hero_image')) {
            if ($collaboration && $collaboration->hero_image && file_exists(($collaboration->hero_image))) {
                @unlink(($collaboration->hero_image));
            } elseif ($collaboration && $collaboration->hero_image && file_exists($collaboration->hero_image)) {
                @unlink($collaboration->hero_image);
            }
            $data['hero_image'] = null;
        }

        // Upload Hero Image baru
        if ($request->hasFile('hero_image')) {
            if ($collaboration && $collaboration->hero_image && file_exists(($collaboration->hero_image))) {
                @unlink(($collaboration->hero_image));
            } elseif ($collaboration && $collaboration->hero_image && file_exists($collaboration->hero_image)) {
                @unlink($collaboration->hero_image);
            }

            $heroFile = $request->file('hero_image');
            $heroFilename = time().'-hero-collaboration-'.pathinfo($heroFile->getClientOriginalName(), PATHINFO_FILENAME).'.webp';

            $heroImg = ImageManager::gd()
                ->read($heroFile->getRealPath())
                ->scaleDown(width: 1400)
                ->toWebp(80);

            file_put_contents(($destinationDir.'/'.$heroFilename), (string) $heroImg);
            $data['hero_image'] = $destinationDir.'/'.$heroFilename;
        }

        // Hapus gambar konten jika diminta
        if ($request->boolean('remove_image')) {
            if ($collaboration && $collaboration->image && file_exists(($collaboration->image))) {
                @unlink(($collaboration->image));
            } elseif ($collaboration && $collaboration->image && file_exists($collaboration->image)) {
                @unlink($collaboration->image);
            }
            $data['image'] = null;
        }

        // Upload gambar konten baru
        if ($request->hasFile('image')) {
            if ($collaboration && $collaboration->image && file_exists(($collaboration->image))) {
                @unlink(($collaboration->image));
            } elseif ($collaboration && $collaboration->image && file_exists($collaboration->image)) {
                @unlink($collaboration->image);
            }

            $file = $request->file('image');
            $filename = time().'-collaboration-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';

            $img = ImageManager::gd()
                ->read($file->getRealPath())
                ->scaleDown(width: 1400)
                ->toWebp(80);

            file_put_contents(($destinationDir.'/'.$filename), (string) $img);
            $data['image'] = $destinationDir.'/'.$filename;
        }

        unset($data['remove_image'], $data['remove_hero_image']);

        Collaboration::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.collaborations.create')
            ->with('success', 'Data Kerja Sama berhasil disimpan.');
    }
}
