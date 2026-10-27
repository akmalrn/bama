<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Workshop;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class WorkshopController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat workshop', only: ['index']),
            new Middleware('permission:buat workshop', only: ['create', 'store']),
            new Middleware('permission:edit workshop', only: ['edit', 'update']),
            new Middleware('permission:hapus workshop', only: ['destroy']),
        ];
    }


    public function index()
    {
        $workshops = Workshop::latest()->get();

        return view(
            'pages.workshops.index',
            compact('workshops')
        );
    }

    public function create()
    {
        return view('pages.workshops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string'],
            'map' => ['nullable', 'string', 'max:5000'],
        ], [
            'title.required' => 'Judul workshop wajib diisi.',
            'title.max' => 'Judul workshop maksimal 255 karakter.',
            'map.max' => 'Data Google Maps terlalu panjang.',
        ]);

        Workshop::query()->create($validated);

        return redirect()
            ->route('dashboard-admin.workshops.index')
            ->with('success', 'Workshop berhasil ditambahkan.');
    }

    public function edit(Workshop $workshop)
    {
        return view(
            'pages.workshops.edit',
            compact('workshop')
        );
    }

    public function update(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string'],
            'map' => ['nullable', 'string', 'max:5000'],
        ], [
            'title.required' => 'Judul workshop wajib diisi.',
            'title.max' => 'Judul workshop maksimal 255 karakter.',
            'map.max' => 'Data Google Maps terlalu panjang.',
        ]);

        $workshop->update($validated);

        return redirect()
            ->route('dashboard-admin.workshops.index')
            ->with('success', 'Workshop berhasil diperbarui.');
    }

    public function destroy(Workshop $workshop)
    {
        $workshop->delete();

        return redirect()
            ->route('dashboard-admin.workshops.index')
            ->with('success', 'Workshop berhasil dihapus.');
    }
}
