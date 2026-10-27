<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CareerPosition;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class CareerPositionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat karir', only: ['index', 'show']),
            new Middleware('permission:edit karir', only: ['create', 'store', 'edit', 'update', 'destroy', 'toggleStatus']),
        ];
    }

    public function index()
    {
        return redirect()->route('dashboard-admin.careers.create', ['tab' => 'lowongan']);
    }

    public function create()
    {
        $nextOrder = (CareerPosition::max('order') ?? 0) + 1;

        return view('pages.career-positions.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Nama posisi / lowongan karir wajib diisi.',
            'title.max' => 'Nama posisi maksimal 255 karakter.',
        ]);

        $validated['career_id'] = 1;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['order'] = $validated['order'] ?? ((CareerPosition::max('order') ?? 0) + 1);
        $validated['is_active'] = $request->boolean('is_active', true);

        CareerPosition::create($validated);

        return redirect()->route('dashboard-admin.careers.create', ['tab' => 'lowongan'])
            ->with('success', 'Posisi lowongan karir berhasil ditambahkan.');
    }

    public function edit(CareerPosition $careerPosition)
    {
        return view('pages.career-positions.edit', compact('careerPosition'));
    }

    public function update(Request $request, CareerPosition $careerPosition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Nama posisi / lowongan karir wajib diisi.',
            'title.max' => 'Nama posisi maksimal 255 karakter.',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['order'] = $validated['order'] ?? $careerPosition->order;
        $validated['is_active'] = $request->boolean('is_active');

        $careerPosition->update($validated);

        return redirect()->route('dashboard-admin.careers.create', ['tab' => 'lowongan'])
            ->with('success', 'Posisi lowongan karir berhasil diperbarui.');
    }

    public function destroy(CareerPosition $careerPosition)
    {
        $careerPosition->delete();

        return redirect()->route('dashboard-admin.careers.create', ['tab' => 'lowongan'])
            ->with('success', 'Posisi lowongan karir berhasil dihapus.');
    }

    public function toggleStatus(CareerPosition $careerPosition)
    {
        $careerPosition->update([
            'is_active' => ! $careerPosition->is_active,
        ]);

        return redirect()->route('dashboard-admin.careers.create', ['tab' => 'lowongan'])
            ->with('success', 'Status lowongan karir berhasil diperbarui.');
    }
}
