<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\BusinessField;
use App\Models\Database\SectionBusiness;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;

class BusinessFieldController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat bidang-usaha', only: ['index']),
            new Middleware('permission:buat bidang-usaha', only: ['create', 'store']),
            new Middleware('permission:edit bidang-usaha', only: ['edit', 'update']),
            new Middleware('permission:hapus bidang-usaha', only: ['destroy']),
            new Middleware('permission:edit label bidang-usaha', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $label = SectionBusiness::first();
        $datas = BusinessField::orderByRaw('CAST(`order` AS UNSIGNED) ASC')->latest()->get();

        return view('pages.business-fields.index', compact('label', 'datas'));
    }

    public function create()
    {
        return view('pages.business-fields.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $businessField = new BusinessField($this->normalizedData($validated, $request));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' .
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) .
                '.webp';

            $imagePath = 'uploads/business-fields';

            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);

            $businessField->image = $imagePath . '/' . $imageName;
        }

        $businessField->save();

        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.business-fields.index')
            ->with('success', 'Bidang Usaha berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = BusinessField::findOrFail($id);

        return view('pages.business-fields.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules($id), $this->messages());
        $businessField = BusinessField::findOrFail($id);
        $businessField->fill($this->normalizedData($validated, $request));

        if ($request->hasFile('image')) {
            if ($businessField->image && file_exists($businessField->image)) {
                @unlink($businessField->image);
            }

            $image = $request->file('image');
            $imageName = time() . '-' .
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) .
                '.webp';

            $imagePath = 'uploads/business-fields';

            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);

            $businessField->image = $imagePath . '/' . $imageName;
        }

        $businessField->save();

        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.business-fields.index')
            ->with('success', 'Bidang Usaha berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $businessField = BusinessField::findOrFail($id);

        if ($businessField->image && file_exists($businessField->image)) {
            @unlink($businessField->image);
        }

        $businessField->delete();

        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.business-fields.index')
            ->with('success', 'Bidang Usaha berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?int $id = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_descriptions' => ['nullable', 'string'],
            'status' => ['nullable'],
            'order' => [
                'nullable',
                'integer',
                'min:0',
                $id === null
                    ? Rule::unique('business_fields')
                    : Rule::unique('business_fields')->ignore($id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'title.required' => 'Judul bidang usaha wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'overview.string' => 'Ringkasan harus berupa teks.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'icon.string' => 'Icon harus berupa teks.',
            'icon.max' => 'Icon maksimal 1000 karakter.',
            'meta_keywords.string' => 'Meta keywords harus berupa teks.',
            'meta_descriptions.string' => 'Meta description harus berupa teks.',
            'image.mimes' => 'Thumbnail harus berformat JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran thumbnail maksimal 5MB.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan minimal 0.',
            'order.unique' => 'Urutan sudah digunakan.',
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizedData(array $validated, Request $request): array
    {
        $data = Arr::only($validated, [
            'title',
            'overview',
            'description',
            'icon',
            'meta_keywords',
            'meta_descriptions',
            'order',
        ]);

        $data['status'] = $request->boolean('status');

        return $data;
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        SectionBusiness::updateOrCreate(['id' => 1], $data);

        $activeTab = $request->input('active_tab', 'tab-label');

        $message = 'Label bidang usaha berhasil diperbarui.';

        return redirect()
            ->route('dashboard-admin.business-fields.index')
            ->with('success', $message)
            ->with('activeTab', $activeTab);
    }
}
