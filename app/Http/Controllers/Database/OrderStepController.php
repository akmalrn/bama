<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\OrderStep as DatabaseOrderStep;
use App\Models\Database\StepSection;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Validation\Rule;


class OrderStepController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat langkah-pemesanan', only: ['index']),
            new Middleware('permission:buat langkah-pemesanan', only: ['create', 'store']),
            new Middleware('permission:edit langkah-pemesanan', only: ['edit', 'update']),
            new Middleware('permission:hapus langkah-pemesanan', only: ['destroy']),
            new Middleware('permission:edit label langkah-pemesanan', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $label = StepSection::first();
        $steps = DatabaseOrderStep::orderBy('order', 'asc')->get();
        return view('pages.order_steps.index', compact('label', 'steps'));
    }

    public function create()
    {
        return view('pages.order_steps.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp',
            'order' => [
                'required',
                'integer',
                Rule::unique(DatabaseOrderStep::class, 'order'),
            ],
        ], [
            'order.unique' => 'Nomor urutan sudah digunakan. Silakan pilih nomor urutan lain.',
        ]);

        $step = new DatabaseOrderStep();
        $step->title = $request->title;
        $step->description = $request->description;
        $step->order = $request->order;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = time() . '-' .
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) .
                '.webp';

            $imagePath = 'uploads/order-steps';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 800)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);

            $step->image = $imagePath . '/' . $imageName;
        }

        $step->save();

        return redirect()
            ->route('dashboard-admin.order-steps.index')
            ->with('success', 'Step berhasil ditambahkan');
    }

    public function edit($id)
    {
        $step = DatabaseOrderStep::findOrFail($id);
        return view('pages.order_steps.edit', compact('step'));
    }

    public function update(Request $request, $id)
    {
        $step = DatabaseOrderStep::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp',
            'order' => [
                'required',
                'integer',
                Rule::unique(DatabaseOrderStep::class, 'order')
                    ->ignore($step->id),
            ],
        ], [
            'order.required' => 'Nomor urutan wajib diisi.',
            'order.integer' => 'Nomor urutan harus berupa angka.',
            'order.unique' => 'Nomor urutan sudah digunakan. Silakan pilih nomor lain.',
        ]);

        $step->title = $request->title;
        $step->description = $request->description;
        $step->order = $request->order;

        if ($request->hasFile('image')) {

            if ($step->image && file_exists($step->image)) {
                @unlink($step->image);
            }

            $image = $request->file('image');

            $imageName = time() . '-' .
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) .
                '.webp';

            $imagePath = 'uploads/order-steps';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 800)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);

            $step->image = $imagePath . '/' . $imageName;
        }

        $step->save();

        return redirect()
            ->route('dashboard-admin.order-steps.index')
            ->with('success', 'Step berhasil diupdate');
    }

    public function destroy($id)
    {
        $step = DatabaseOrderStep::findOrFail($id);

        if ($step->image && file_exists($step->image)) {
            @unlink($step->image);
        }

        $step->delete();

        return redirect()
            ->route('dashboard-admin.order-steps.index')
            ->with('success', 'Step berhasil dihapus');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        StepSection::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.order-steps.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
