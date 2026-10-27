<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\About;
use App\Models\Database\CounterSection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Intervention\Image\ImageManager;

class CounterSectionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat stat-perusahaan', only: ['index']),
            new Middleware('permission:buat stat-perusahaan', only: ['create', 'store']),
            new Middleware('permission:edit stat-perusahaan', only: ['edit', 'update', 'storeOrUpdateLabel']),
            new Middleware('permission:hapus stat-perusahaan', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $datas = CounterSection::orderBy('order', 'asc')->latest()->get();
        $about = About::first();

        return view('pages.counters.index', compact('datas', 'about'));
    }

    public function storeOrUpdateLabel(Request $request)
    {
        $data = $request->validate([
            'counter_title' => 'nullable|string|max:255',
            'counter_description' => 'nullable|string',
        ]);

        About::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.counter-section.index')
            ->with('success', 'Label Stat Perusahaan berhasil diperbarui.');
    }

    public function create()
    {
        $datas = CounterSection::all();

        return view('pages.counters.create', compact('datas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'value' => 'required|integer',
            'suffix' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
            'order' => 'required|integer|unique:counter_section,order',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',

            'value.required' => 'Value wajib diisi.',

            'suffix.required' => 'Suffix wajib dipilih.',

            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus PNG, JPG, JPEG, atau WEBP.',

            'order.required' => 'Urutan wajib diisi.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.unique' => 'Urutan sudah digunakan, silakan pilih angka lain.',
        ]);

        $company_stat = new CounterSection;
        $company_stat->title = $request->title;
        $company_stat->icon = $request->icon;
        $company_stat->value = $request->value;
        $company_stat->suffix = $request->suffix;
        $company_stat->order = $request->order;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'-'.pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $imagePath = 'uploads/company_stats';
            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->toWebp(80);

            file_put_contents($imagePath.'/'.$imageName, $img);
            $company_stat->image = $imagePath.'/'.$imageName;
        }

        $company_stat->save();

        return redirect()->route('dashboard-admin.counter-section.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $datas = CounterSection::all();
        $data = CounterSection::findOrFail($id);

        return view('pages.counters.edit', compact('datas', 'data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'value' => 'required|string',
            'suffix' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
            'order' => 'required|integer|unique:counter_section,order,'.$id,
        ], [
            'title.required' => 'Judul wajib diisi.',
            'value.required' => 'Value wajib diisi.',
            'suffix.required' => 'Suffix wajib dipilih.',
            'order.required' => 'Urutan wajib diisi.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.unique' => 'Urutan sudah digunakan, silakan pilih angka lain.',
        ]);

        $company_stat = CounterSection::findOrFail($id);
        $company_stat->icon = $request->icon;
        $company_stat->title = $request->title;
        $company_stat->value = $request->value;
        $company_stat->suffix = $request->suffix;
        $company_stat->order = $request->order;

        if ($request->hasFile('image')) {
            if ($company_stat->image && file_exists(($company_stat->image))) {
                @unlink(($company_stat->image));
            }

            $image = $request->file('image');
            $imageName = time().'-'.pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $imagePath = 'uploads/company_stats';
            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->toWebp(80);

            file_put_contents($imagePath.'/'.$imageName, $img);
            $company_stat->image = $imagePath.'/'.$imageName;
        }

        $company_stat->save();

        return redirect()->route('dashboard-admin.counter-section.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $company_stat = CounterSection::findOrFail($id);
        if ($company_stat->image && file_exists(($company_stat->image))) {
            @unlink(($company_stat->image));
        }
        $company_stat->delete();

        return redirect()->route('dashboard-admin.counter-section.index')->with('success', 'Data berhasil dihapus.');
    }
}
