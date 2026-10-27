<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Configuration;
use App\Support\FrontendCache;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ConfigurationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat pengaturan-website', only: ['create']),
            new Middleware('permission:edit pengaturan-website', only: ['storeOrUpdate']),
        ];
    }

    public function create()
    {
        $configuration = Configuration::first();

        return view('pages.configurations.create', compact('configuration'));
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'banner_cta' => 'nullable',
            'image_cta' => 'nullable',
            'image_cta_2' => 'nullable',
            'banner' => 'nullable',
            'image_login' => 'nullable',
            'logo_hitam_page' => 'nullable',
            'logo_putih_page' => 'nullable',
            'logo_footer_page' => 'nullable',
            'favicon_page' => 'nullable',
            'company_profile_pdf' => 'nullable|file|mimes:pdf',

            // dashboard
            'name_dashboard' => 'nullable|string|max:255',
            'title_dashboard' => 'nullable|string|max:255',

            // login
            'title_login' => 'nullable|string|max:255',
            'name_login' => 'nullable|string|max:255',

            // page atau website
            'name_page' => 'nullable|string|max:255',
            'title_page' => 'nullable|string|max:255',
            'off_canvas_page' => 'nullable|string',
            'footer_page' => 'nullable|string',
            'text_footer_page' => 'nullable|string',
            'cta' => 'nullable|string',
            'title_link_cta' => 'nullable|string',

            'description_cta' => 'nullable|string',
            'link_cta' => 'nullable|string',
            'name_link_cta' => 'nullable|string',

            'meta_descriptions' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'meta_title' => 'nullable|string',
            'meta_author' => 'nullable|string',
            'meta_subject' => 'nullable|string',

            'base_url' => 'nullable|string',
            'site_url' => 'nullable|string',
            'canonical_url' => 'nullable|string',

            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image',
            'og_url' => 'nullable|string',
            'og_type' => 'nullable|string',
            'og_site_name' => 'nullable|string',
            'og_title' => 'nullable|string',

            'twitter_card' => 'nullable|string',
            'twitter_site' => 'nullable|string',
            'twitter_creator' => 'nullable|string',
            'twitter_title' => 'nullable|string',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|image',

            'geo_latitude' => 'nullable|numeric',
            'geo_placename' => 'nullable|string',
            'geo_region' => 'nullable|string',
            'geo_longitude' => 'nullable|numeric',

            'language' => 'nullable|string|max:50',
            'robots' => 'nullable|string',
            'distribution' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'revisit_after' => 'nullable|string|max:50',
            'copyright' => 'nullable|string',
            'designer' => 'nullable|string',
            'generator' => 'nullable|string',
        ], [
            'company_profile_pdf.file' => 'Company profile harus berupa file.',
            'company_profile_pdf.mimes' => 'Company profile harus menggunakan format PDF.',
            'company_profile_pdf.max' => 'Ukuran company profile maksimal 100 MB.',
        ]);

        $configuration = Configuration::firstOrCreate(['id' => 1]);

        $images = [
            // page atau website
            'logo_hitam_page' => 500,
            'logo_putih_page' => 500,
            'logo_footer_page' => 500,
            'favicon_page' => 64,
            'banner' => 1920,
            'banner_cta' => 1920,
            'image_cta' => 1200,
            'image_cta_2' => 1200,
            'image_login' => 1200,

            // dashboard
            'logo_dashboard' => 500,
            'logo_dashboard_2' => 500,
            'favicon_dashboard' => 64,

            // login
            'logo_login' => 500,
            'favicon_login' => 64,

            // meta
            'og_image' => 1200,
            'twitter_image' => 1200,
        ];

        foreach ($images as $field => $size) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadImage(
                    $request->file($field),
                    $configuration->$field ?? null,
                    $size
                );
            }
        }

        if ($request->hasFile('company_profile_pdf')) {
            $data['company_profile_pdf'] = $this->uploadPdf(
                $request->file('company_profile_pdf'),
                $configuration->company_profile_pdf
            );
        }

        if ($request->hasFile('favicon_page')) {
            try {
                $favFile = $request->file('favicon_page');
                $img = ImageManager::gd()->read($favFile->getRealPath())->scaleDown(width: 64);
                file_put_contents(('favicon.png'), $img->toPng());
                file_put_contents(('favicon.ico'), $img->toPng());
            } catch (\Throwable $e) {
                // Abaikan jika konversi biner gagal
            }
        }

        $configuration->update($data);

        FrontendCache::forgetShared();

        return redirect()->route('dashboard-admin.configurations.create')
            ->with('success', 'Konfigurasi berhasil disimpan.');
    }

    private function uploadImage($file, $oldPath = null, $width = 300)
    {
        $folder = ('asdasdasd');

        if (! file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        if ($oldPath) {
            $oldFile = (ltrim($oldPath, '/\\'));

            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
        }

        $name = time().'-'.Str::random(5).'.webp';

        $img = ImageManager::gd()
            ->read($file->getRealPath())
            ->scaleDown(width: $width)
            ->toWebp(80);

        file_put_contents($folder.DIRECTORY_SEPARATOR.$name, $img);

        return 'asdasdasd/'.$name;
    }

    private function uploadPdf($file, $oldPath = null)
    {
        $folder = ('asdasdasd');

        if (! file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $name = $file->getClientOriginalName();
        $file->move($folder, $name);

        if ($oldPath) {
            $oldFile = (ltrim($oldPath, '/\\'));

            if (is_file($oldFile) && basename($oldFile) !== $name) {
                @unlink($oldFile);
            }
        }

        return 'asdasdasd/'.$name;
    }
}
