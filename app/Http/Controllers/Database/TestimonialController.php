<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\SectionTestimonial;
use App\Models\Database\Testimonial;
use App\Services\Image\WebpConversionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TestimonialController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat testimoni', only: ['index']),
            new Middleware('permission:buat testimoni', only: ['create', 'store']),
            new Middleware('permission:edit testimoni', only: ['edit', 'update']),
            new Middleware('permission:hapus testimoni', only: ['destroy']),
        ];
    }

    public function index()
    {
        $label = SectionTestimonial::first();
        $testimonials = Testimonial::latest()->get();

        return view('pages.testimonials.index', compact('label', 'testimonials'));
    }

    public function create()
    {
        return view('pages.testimonials.create');
    }

    public function store(Request $request, WebpConversionService $converter)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'star' => 'nullable|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'logo_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $testimonial = new Testimonial();
        $nameOrTitle = $request->input('title') ?: $request->input('name');
        $testimonial->title = $nameOrTitle;
        $testimonial->name = $nameOrTitle;
        $testimonial->position = $request->position;
        $testimonial->address = $request->address;
        $testimonial->description = $request->description;
        $testimonial->star = $request->star ?: 5;

        $imageFile = $request->file('image') ?: $request->file('foto');
        if ($imageFile) {
            $imagePath = $this->storeImage($imageFile, 'image', $converter);
            $testimonial->image = $imagePath;
            $testimonial->foto = $imagePath;
        }

        if ($request->hasFile('logo_image')) {
            $testimonial->logo_image = $this->storeImage($request->file('logo_image'), 'logo', $converter);
        }

        $testimonial->save();

        return redirect()
            ->route('dashboard-admin.testimonials.index')
            ->with('success', 'Testimonial berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return view('pages.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id, WebpConversionService $converter)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'star' => 'nullable|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'logo_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $testimonial = Testimonial::findOrFail($id);

        $nameOrTitle = $request->input('title') ?: $request->input('name');
        if ($nameOrTitle !== null) {
            $testimonial->title = $nameOrTitle;
            $testimonial->name = $nameOrTitle;
        }

        $testimonial->position = $request->position;
        $testimonial->address = $request->address;
        $testimonial->description = $request->description;
        if ($request->filled('star')) {
            $testimonial->star = $request->star;
        }

        $imageFile = $request->file('image') ?: $request->file('foto');
        if ($imageFile) {
            $this->deleteFile($testimonial->image);
            if ($testimonial->foto !== $testimonial->image) {
                $this->deleteFile($testimonial->foto);
            }
            $imagePath = $this->storeImage($imageFile, 'image', $converter);
            $testimonial->image = $imagePath;
            $testimonial->foto = $imagePath;
        }

        if ($request->hasFile('logo_image')) {
            $this->deleteFile($testimonial->logo_image);
            $testimonial->logo_image = $this->storeImage($request->file('logo_image'), 'logo', $converter);
        }

        $testimonial->save();

        return redirect()
            ->route('dashboard-admin.testimonials.index')
            ->with('success', 'Testimonial berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $this->deleteFile($testimonial->image);
        if ($testimonial->foto !== $testimonial->image) {
            $this->deleteFile($testimonial->foto);
        }
        $this->deleteFile($testimonial->logo_image);

        $testimonial->delete();

        return redirect()
            ->route('dashboard-admin.testimonials.index')
            ->with('success', 'Testimonial berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'span' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        SectionTestimonial::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.testimonials.index')
            ->with('success', 'Label testimonial berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }

    private function storeImage($file, string $prefix, WebpConversionService $converter): string
    {
        $filename = time().'-'.uniqid().'-'.$prefix.'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
        $path = 'uploads/testimonials/'.$filename;
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
