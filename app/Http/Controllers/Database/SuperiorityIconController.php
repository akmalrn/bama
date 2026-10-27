<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\LabelSuperiorityIcon;
use App\Models\Database\SuperiorityIcon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SuperiorityIconController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat keunggulan-(ikon)', only: ['index']),
            new Middleware('permission:buat keunggulan-(ikon)', only: ['create', 'store']),
            new Middleware('permission:edit keunggulan-(ikon)', only: ['edit', 'update']),
            new Middleware('permission:hapus keunggulan-(ikon)', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $label = LabelSuperiorityIcon::first();

        $superioritys = SuperiorityIcon::latest()->get();
        return view('pages.superioritys_(icon).index', compact('superioritys', 'label'));
    }

    public function create()
    {
        $superiority = SuperiorityIcon::first();
        return view('pages.superioritys_(icon).create', compact('superiority'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        SuperiorityIcon::create($data);

        return redirect()->route('dashboard-admin.superioritys_icon.index')->with('success', 'Keunggulan berhasil disimpan.');
    }

    public function edit($id)
    {
        $data = SuperiorityIcon::findOrFail($id);
        return view('pages.superioritys_(icon).edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $superiority = SuperiorityIcon::findOrFail($id);
        $superiority->title = $request->title;
        $superiority->description = $request->description;
        $superiority->icon = $request->icon;

        $superiority->update($data);

        return redirect()->route('dashboard-admin.superioritys_icon.index')->with('success', 'Keunggulan berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $superiority = SuperiorityIcon::findOrFail($id);

        $superiority->delete();

        return redirect()->route('dashboard-admin.superioritys_icon.index')->with('success', 'Keunggulan berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subTitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'span' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $label = LabelSuperiorityIcon::find(1);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageDirectory = ('uploads/superiorities');

            if (! file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0755, true);
            }

            $imageName = time()
                . '-'
                . uniqid()
                . '.webp';

            $imageFullPath = $imageDirectory . '/' . $imageName;

            $convertedImage = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(80);

            file_put_contents($imageFullPath, (string) $convertedImage);

            if ($label?->image) {
                $oldImagePath = ($label->image);

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $validated['image'] = 'uploads/superiorities/' . $imageName;
        }

        LabelSuperiorityIcon::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()
            ->route('dashboard-admin.superioritys_icon.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
