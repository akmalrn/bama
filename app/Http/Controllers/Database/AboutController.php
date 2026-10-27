<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\About;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Intervention\Image\ImageManager;

class AboutController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat tentang-kami', only: ['create']),
            new Middleware('permission:edit tentang-kami', only: ['storeOrUpdate']),
        ];
    }

    public function create()
    {
        $about = About::first();

        return view('pages.abouts.create', compact('about'));
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $data = $request->validate([
            'label_title' => 'nullable|string|max:255',
            'label_subTitle' => 'nullable|string|max:255',
            'label_description' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'span' => 'nullable|string|max:255',
            'subTitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'company_values' => 'nullable|string',
            'certifications' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'image_visi_misi' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'title_visi_misi' => 'nullable|string|max:255',
            'subTitle_visi_misi' => 'nullable|string|max:255',
            'span_visi_misi' => 'nullable|string|max:255',
            'description_visi_misi' => 'nullable|string',
            'title_visi' => 'nullable|string|max:255',
            'title_misi' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'ks_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'ks_title' => 'nullable|string|max:255',
            'ks_subTitle' => 'nullable|string|max:255',
            'ks_span' => 'nullable|string|max:255',
            'ks_description' => 'nullable|string',
            'so_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'so_title' => 'nullable|string|max:255',
            'so_subTitle' => 'nullable|string|max:255',
            'so_span' => 'nullable|string|max:255',
            'so_description' => 'nullable|string',
            'sejarah_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'sejarah_title' => 'nullable|string|max:255',
            'sejarah_description' => 'nullable|string',
            'budaya_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'budaya_title' => 'nullable|string|max:255',
            'budaya_description' => 'nullable|string',
            'counter_title' => 'nullable|string|max:255',
            'counter_description' => 'nullable|string',
        ], [
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'subTitle.string' => 'Sub judul harus berupa teks.',
            'subTitle.max' => 'Sub judul maksimal 255 karakter.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'image.image' => 'File gambar harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'image_visi_misi.image' => 'File gambar visi & misi harus berupa gambar.',
            'image_visi_misi.mimes' => 'Format gambar visi & misi harus JPG, JPEG, PNG, atau WEBP.',
            'ks_image.image' => 'File kepemilikan saham harus berupa gambar.',
            'ks_image.mimes' => 'Format gambar kepemilikan saham harus JPG, JPEG, PNG, atau WEBP.',
            'so_image.image' => 'File struktur organisasi harus berupa gambar.',
            'so_image.mimes' => 'Format gambar struktur organisasi harus JPG, JPEG, PNG, atau WEBP.',
            'sejarah_image.image' => 'File gambar sejarah harus berupa gambar.',
            'sejarah_image.mimes' => 'Format gambar sejarah harus JPG, JPEG, PNG, atau WEBP.',
            'budaya_image.image' => 'File gambar budaya perusahaan harus berupa gambar.',
            'budaya_image.mimes' => 'Format gambar budaya perusahaan harus JPG, JPEG, PNG, atau WEBP.',
            'visi.string' => 'Visi harus berupa teks.',
            'misi.string' => 'Misi harus berupa teks.',
        ]);

        $about = About::find(1);

        foreach (['image', 'image_visi_misi', 'ks_image', 'so_image', 'sejarah_image', 'budaya_image'] as $imageField) {
            if (! $request->hasFile($imageField)) {
                continue;
            }

            if ($about && $about->{$imageField} && file_exists($about->{$imageField})) {
                @unlink($about->{$imageField});
            }

            $image = $request->file($imageField);
            $imageName = time().'-'.$imageField.'-'
                .pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $imagePath = 'uploads/about';

            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(80);

            file_put_contents($imagePath.'/'.$imageName, $img);
            $data[$imageField] = $imagePath.'/'.$imageName;
        }

        About::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.abouts.create')
            ->with('success', 'Informasi perusahaan berhasil disimpan.')
            ->with('activeTab', 'tab-label');
    }
}
