<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\LabelSuperiority;
use App\Models\Database\Superiority;
use App\Services\Image\WebpConversionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SuperiorityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat keunggulan-(gambar)', only: ['index']),
            new Middleware('permission:buat keunggulan-(gambar)', only: ['create', 'store']),
            new Middleware('permission:edit keunggulan-(gambar)', only: ['edit', 'update']),
            new Middleware('permission:hapus keunggulan-(gambar)', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $label = LabelSuperiority::first();

        $superioritys = Superiority::latest()->get();

        return view('pages.superioritys_(image).index', compact('superioritys', 'label'));
    }

    public function create()
    {
        $superiority = Superiority::first();

        return view('pages.superioritys_(image).create', compact('superiority'));
    }

    public function store(Request $request, WebpConversionService $converter)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quality_title' => 'nullable|string|max:255',
            'quality_items' => 'nullable|array|max:20',
            'quality_items.*' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
            'logo_image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
        ], [
            'image.image' => 'File harus berupa gambar.',
            'logo_image.image' => 'File logo harus berupa gambar.',
        ]);

        $data['quality_items'] = $this->normalizeQualityItems($request->input('quality_items', []));

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), 'image', $converter);
        }

        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $this->storeImage($request->file('logo_image'), 'logo', $converter);
        }

        Superiority::create($data);

        return redirect()
            ->route('dashboard-admin.superioritys_image.index')
            ->with('success', 'Keunggulan berhasil disimpan.');
    }

    public function edit($id)
    {
        $superiority = Superiority::findOrFail($id);

        return view('pages.superioritys_(image).edit', compact('superiority'));
    }

    public function update(Request $request, $id, WebpConversionService $converter)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quality_title' => 'nullable|string|max:255',
            'quality_items' => 'nullable|array|max:20',
            'quality_items.*' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
            'logo_image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
        ]);

        $data['quality_items'] = $this->normalizeQualityItems($request->input('quality_items', []));

        $superiority = Superiority::findOrFail($id);
        $superiority->title = $request->title;
        $superiority->description = $request->description;

        if ($request->hasFile('image')) {
            $this->deleteFile($superiority->image);
            $data['image'] = $this->storeImage($request->file('image'), 'image', $converter);
        }

        if ($request->hasFile('logo_image')) {
            $this->deleteFile($superiority->logo_image);
            $data['logo_image'] = $this->storeImage($request->file('logo_image'), 'logo', $converter);
        }

        $superiority->update($data);

        return redirect()->route('dashboard-admin.superioritys_image.index')->with('success', 'Keunggulan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $superiority = Superiority::findOrFail($id);

        $this->deleteFile($superiority->image);
        $this->deleteFile($superiority->logo_image);
        $superiority->delete();

        return redirect()->route('dashboard-admin.superioritys_image.index')->with('success', 'Keunggulan berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
            'span' => 'nullable|string',
        ]);

        LabelSuperiority::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.superioritys_image.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }

    private function normalizeQualityItems(array $items): array
    {
        return collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter(fn ($item) => $item !== '')
            ->values()
            ->all();
    }

    private function storeImage($file, string $prefix, WebpConversionService $converter): string
    {
        $filename = time().'-'.uniqid().'-'.$prefix.'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
        $path = 'uploads/superioritys/'.$filename;
        $converter->convert($file->getRealPath(), $path);

        return $path;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && is_file($path)) {
            @unlink($path);
        }
    }
}
