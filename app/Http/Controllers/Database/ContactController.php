<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Contact;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ContactController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat pengaturan-kontak', only: ['create']),
            new Middleware('permission:edit pengaturan-kontak', only: ['storeOrUpdate']),
        ];
    }

    public function create()
    {
        $contact = contact::first();

        return view('pages.contacts.create', compact('contact'));
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'span' => 'nullable|string',
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
            'image_general' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'remove_image_general' => 'nullable|boolean',

            // section demo form
            'form_brand' => 'nullable|string|max:255',
            'form_title' => 'nullable|string',
            'form_subtitle' => 'nullable|string',
            'form_button_text' => 'nullable|string|max:100',

            'location' => 'nullable|string',
            'location_2' => 'nullable|string',
            'link_location' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'working_hours_2' => 'nullable|string',

            'phone' => 'nullable|string',
            'phone_2' => 'nullable|string',

            'whatsapp' => 'nullable|string',
            'whatsapp_2' => 'nullable|string',

            'email' => 'nullable|string',
            'email_2' => 'nullable|string',

            'map' => 'nullable|string',
            'map_2' => 'nullable|string',

            // sosmed
            'link' => 'nullable|string',
            'name' => 'nullable|string',
            'image' => 'nullable|image',

            'link_2' => 'nullable|string',
            'name_2' => 'nullable|string',
            'image_2' => 'nullable|image',

            'link_3' => 'nullable|string',
            'name_3' => 'nullable|string',
            'image_3' => 'nullable|image',

            'link_4' => 'nullable|string',
            'name_4' => 'nullable|string',
            'image_4' => 'nullable|image',

            'link_5' => 'nullable|string',
            'name_5' => 'nullable|string',
            'image_5' => 'nullable|image',

            'remove_image' => 'nullable|boolean',
            'remove_image_2' => 'nullable|boolean',
            'remove_image_3' => 'nullable|boolean',
            'remove_image_4' => 'nullable|boolean',
            'remove_image_5' => 'nullable|boolean',

            'title_sosmed' => 'nullable|string',
        ]);

        $contact = contact::firstOrCreate(['id' => 1]);

        $images = [
            'image_general' => 1200,
            'image' => 300,
            'image_2' => 300,
            'image_3' => 300,
            'image_4' => 300,
            'image_5' => 300,
        ];

        foreach ($images as $field => $size) {
            $removeField = 'remove_' . $field;

            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadImage(
                    $request->file($field),
                    $contact->$field ?? null,
                    $size
                );
            } elseif ($request->boolean($removeField)) {
                $this->deleteImage($contact->$field ?? null);
                $data[$field] = null;
            }

            unset($data[$removeField]);
        }

        $contact->update($data);

        return redirect()->route('dashboard-admin.contacts.create')
            ->with('success', 'Kontak berhasil disimpan.');
    }

    private function uploadImage($file, $oldPath = null, $width = 300)
    {
        $folder = ('uploads/contact');

        if (! file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $this->deleteImage($oldPath);

        $name = time() . '-' . Str::random(5) . '.webp';

        $img = ImageManager::gd()
            ->read($file->getRealPath())
            ->scaleDown(width: $width)
            ->toWebp(80);

        file_put_contents($folder . '/' . $name, $img);

        return 'uploads/contact/' . $name;
    }

    private function deleteImage($path): void
    {
        if ($path && file_exists($path)) {
            @unlink($path);
        }
    }
}
