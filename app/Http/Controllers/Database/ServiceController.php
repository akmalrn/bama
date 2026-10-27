<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryService;
use App\Models\Database\Service;
use App\Models\Database\ServiceSection;
use App\Support\FrontendCache;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\ImageManager;

class ServiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat layanan|lihat kategori-layanan', only: ['index']),
            new Middleware('permission:buat layanan', only: ['create', 'store']),
            new Middleware('permission:edit layanan', only: ['edit', 'update']),
            new Middleware('permission:hapus layanan', only: ['destroy']),
            new Middleware('permission:edit label layanan', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $label = ServiceSection::first();
        $datas = Service::with('category')->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        $categories = CategoryService::latest()->get();

        return view('pages.services.index', compact('label', 'datas', 'categories'));
    }

    public function create()
    {
        $categories = CategoryService::all();

        return view('pages.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $service = new Service($this->normalizedData($validated, $request));

        if (array_key_exists('number_of_people', $validated)) {
            $service->number_of_people = $validated['number_of_people'];
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time().'-'.
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).
                '.webp';

            $imagePath = 'uploads/services';

            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(100);

            file_put_contents($imagePath.'/'.$imageName, $img);

            $service->image = $imagePath.'/'.$imageName;
        }

        if ($request->hasFile('hero_image')) {
            $heroImage = $request->file('hero_image');
            $heroImageName = time().'-hero-'.pathinfo($heroImage->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $heroImagePath = 'uploads/services/hero';

            if (! file_exists($heroImagePath)) {
                mkdir($heroImagePath, 0755, true);
            }

            $imgHero = ImageManager::gd()
                ->read($heroImage->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(100);

            file_put_contents($heroImagePath.'/'.$heroImageName, $imgHero);
            $service->hero_image = $heroImagePath.'/'.$heroImageName;
        }

        $galleryImages = [];

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $gallery) {

                $galleryName = time().'-'.$key.'-'.
                    pathinfo($gallery->getClientOriginalName(), PATHINFO_FILENAME).
                    '.webp';

                $galleryPath = 'uploads/services/gallery';

                if (! file_exists($galleryPath)) {
                    mkdir($galleryPath, 0755, true);
                }

                $imgGallery = ImageManager::gd()
                    ->read($gallery->getRealPath())
                    ->scaleDown(width: 1200)
                    ->toWebp(100);

                file_put_contents($galleryPath.'/'.$galleryName, $imgGallery);

                $galleryImages[] = $galleryPath.'/'.$galleryName;
            }
        }

        $service->images = $galleryImages;
        $service->projects_data = $this->processProjectsData($request);
        $service->workflow_data = $this->processWorkflowData($request);
        $service->portfolios_data = $this->processPortfoliosData($request);

        $service->save();

        FrontendCache::forgetService($service->slug);
        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Service::findOrFail($id);
        $data->images = $this->imagePaths($data->images);

        $categories = CategoryService::all();

        return view('pages.services.edit', compact(
            'data',
            'categories'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules($id), $this->messages());
        $service = Service::findOrFail($id);
        $service->fill($this->normalizedData($validated, $request));

        if (array_key_exists('number_of_people', $validated)) {
            $service->number_of_people = $validated['number_of_people'];
        }

        if ($request->hasFile('image')) {
            if ($service->image && file_exists($service->image)) {
                @unlink($service->image);
            }

            $image = $request->file('image');

            $imageName = time().'-'.
                pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).
                '.webp';

            $imagePath = 'uploads/services';

            if (! file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(100);

            file_put_contents($imagePath.'/'.$imageName, $img);

            $service->image = $imagePath.'/'.$imageName;
        }

        if ($request->boolean('remove_hero_image')) {
            if ($service->hero_image && file_exists(($service->hero_image))) {
                @unlink(($service->hero_image));
            } elseif ($service->hero_image && file_exists($service->hero_image)) {
                @unlink($service->hero_image);
            }
            $service->hero_image = null;
        }

        if ($request->hasFile('hero_image')) {
            if ($service->hero_image && file_exists(($service->hero_image))) {
                @unlink(($service->hero_image));
            } elseif ($service->hero_image && file_exists($service->hero_image)) {
                @unlink($service->hero_image);
            }

            $heroImage = $request->file('hero_image');
            $heroImageName = time().'-hero-'.pathinfo($heroImage->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
            $heroImagePath = 'uploads/services/hero';

            if (! file_exists($heroImagePath)) {
                mkdir($heroImagePath, 0755, true);
            }

            $imgHero = ImageManager::gd()
                ->read($heroImage->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(100);

            file_put_contents($heroImagePath.'/'.$heroImageName, $imgHero);
            $service->hero_image = $heroImagePath.'/'.$heroImageName;
        }

        $existingImages = $this->imagePaths($service->images);
        $oldImages = $request->boolean('gallery_state_submitted')
            ? array_values(array_intersect($existingImages, $validated['old_images'] ?? []))
            : $existingImages;
        $newImages = [];

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $gallery) {

                if (! $gallery) {
                    continue;
                }

                $galleryName = time().'-'.$key.'-'.
                    pathinfo($gallery->getClientOriginalName(), PATHINFO_FILENAME).
                    '.webp';

                $galleryPath = 'uploads/services/gallery';

                if (! file_exists($galleryPath)) {
                    mkdir($galleryPath, 0755, true);
                }

                $imgGallery = ImageManager::gd()
                    ->read($gallery->getRealPath())
                    ->scaleDown(width: 1200)
                    ->toWebp(100);

                file_put_contents($galleryPath.'/'.$galleryName, $imgGallery);

                $newImages[] = $galleryPath.'/'.$galleryName;
            }
        }
        $deletedImages = array_diff($existingImages, $oldImages);
        foreach ($deletedImages as $deletedImage) {

            if (file_exists($deletedImage)) {
                @unlink($deletedImage);
            }
        }
        $service->images = array_merge($oldImages, $newImages);
        $service->projects_data = $this->processProjectsData($request, $service->projects_data ?? []);
        $service->workflow_data = $this->processWorkflowData($request, $service->workflow_data ?? []);
        $service->portfolios_data = $this->processPortfoliosData($request, $service->portfolios_data ?? []);

        $service->save();

        FrontendCache::forgetService($service->slug);
        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image && file_exists(($service->image))) {
            @unlink(($service->image));
        }
        $images = $this->imagePaths($service->images);
        if ($images !== []) {
            foreach ($images as $image) {
                if ($image && file_exists(($image))) {
                    @unlink(($image));
                }
            }
        }

        // Cleanup project images
        if (is_array($service->projects_data)) {
            foreach ($service->projects_data as $p) {
                if (! empty($p['image']) && file_exists($p['image'])) {
                    @unlink($p['image']);
                }
            }
        }

        // Cleanup workflow images
        if (is_array($service->workflow_data)) {
            foreach ($service->workflow_data as $wf) {
                if (! empty($wf['image']) && file_exists($wf['image'])) {
                    @unlink($wf['image']);
                }
            }
        }

        // Cleanup portfolios images
        if (is_array($service->portfolios_data)) {
            foreach ($service->portfolios_data as $pf) {
                if (! empty($pf['image']) && file_exists($pf['image'])) {
                    @unlink($pf['image']);
                }
            }
        }

        $slug = $service->slug;
        $service->delete();

        FrontendCache::forgetService($slug);
        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subTitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'span' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_image' => 'nullable|boolean',
            'title_cta' => 'nullable|string',
            'description_cta' => 'nullable|string',
            'name_link' => 'nullable|string',
            'link' => 'nullable|string',
            'subTitle_cta' => 'nullable|string',
        ]);

        $serviceSection = ServiceSection::first();
        $destinationDir = 'uploads/services';

        if (! file_exists(($destinationDir))) {
            mkdir(($destinationDir), 0755, true);
        }

        if ($request->boolean('remove_image')) {
            if ($serviceSection && $serviceSection->image && file_exists(($serviceSection->image))) {
                @unlink(($serviceSection->image));
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($serviceSection && $serviceSection->image && file_exists(($serviceSection->image))) {
                @unlink(($serviceSection->image));
            }

            $file = $request->file('image');
            $filename = time().'-hero-service-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';

            $img = ImageManager::gd()
                ->read($file->getRealPath())
                ->scaleDown(width: 1200)
                ->toWebp(100);

            file_put_contents(($destinationDir.'/'.$filename), (string) $img);
            $data['image'] = $destinationDir.'/'.$filename;
        }

        unset($data['remove_image']);

        ServiceSection::updateOrCreate(['id' => 1], $data);

        FrontendCache::forgetService();

        $activeTab = $request->input('active_tab', 'tab-label');

        $message = $activeTab === 'tab-informasi'
            ? 'Informasi layanan berhasil diperbarui.'
            : 'Label layanan berhasil diperbarui.';

        return redirect()
            ->route('dashboard-admin.services.index')
            ->with('success', $message)
            ->with('activeTab', $activeTab);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?int $serviceId = null): array
    {
        return [
            'id_category' => ['nullable', 'integer', 'exists:categories_service,id'],
            'title' => ['required', 'string', 'max:255'],
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'hero_span' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'overview' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'button_name' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:2048'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'old_images' => ['nullable', 'array'],
            'old_images.*' => ['string', 'max:2048'],
            'gallery_state_submitted' => ['nullable', 'boolean'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_descriptions' => ['nullable', 'string'],
            'projects_data' => ['nullable', 'array'],
            'projects_badge' => ['nullable', 'string', 'max:255'],
            'projects_title' => ['nullable', 'string', 'max:255'],
            'show_projects' => ['nullable', 'boolean'],
            'workflow_data' => ['nullable', 'array'],
            'workflow_title' => ['nullable', 'string', 'max:255'],
            'workflow_subtitle' => ['nullable', 'string'],
            'show_workflow' => ['nullable', 'boolean'],
            'portfolios_data' => ['nullable', 'array'],
            'portfolios_badge' => ['nullable', 'string', 'max:255'],
            'portfolios_title' => ['nullable', 'string', 'max:255'],
            'show_portfolios' => ['nullable', 'boolean'],
            'status' => ['nullable'],
            'order' => ['nullable', 'integer', 'min:0'],
            'number_of_people' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'id_category.exists' => 'Kategori layanan tidak valid.',
            'title.required' => 'Judul layanan wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'overview.string' => 'Ringkasan harus berupa teks.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'icon.string' => 'Icon harus berupa teks.',
            'icon.max' => 'Icon maksimal 255 karakter.',
            'meta_keywords.string' => 'Meta keywords harus berupa teks.',
            'meta_descriptions.string' => 'Meta description harus berupa teks.',
            'image.mimes' => 'Thumbnail harus berformat JPG, JPEG, PNG, atau WEBP.',
            'images.array' => 'Galeri harus berupa array.',
            'images.*.mimes' => 'Semua gambar galeri harus berformat JPG, JPEG, PNG, atau WEBP.',
            'order.integer' => 'Urutan layanan harus berupa angka.',
            'order.min' => 'Urutan layanan minimal 0.',
            'order.unique' => 'Urutan layanan sudah digunakan.',
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizedData(array $validated, Request $request): array
    {
        $data = Arr::only($validated, [
            'id_category',
            'title',
            'hero_badge',
            'hero_span',
            'hero_title',
            'hero_description',
            'overview',
            'description',
            'button_name',
            'button_url',
            'icon',
            'meta_keywords',
            'meta_descriptions',
            'projects_badge',
            'projects_title',
            'workflow_title',
            'workflow_subtitle',
            'portfolios_badge',
            'portfolios_title',
            'status',
            'order',
        ]);

        $data['show_projects'] = $request->has('show_projects') ? $request->boolean('show_projects') : true;
        $data['show_workflow'] = $request->has('show_workflow') ? $request->boolean('show_workflow') : true;
        $data['show_portfolios'] = $request->has('show_portfolios') ? $request->boolean('show_portfolios') : true;
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    /**
     * @return array<int, string>
     */
    private function imagePaths(mixed $images): array
    {
        while (is_string($images)) {
            $decoded = json_decode($images, true);

            if ($decoded === null || $decoded === $images) {
                break;
            }

            $images = $decoded;
        }

        if (! is_array($images)) {
            return [];
        }

        return collect($images)
            ->filter(fn (mixed $image): bool => is_string($image) && trim($image) !== '')
            ->values()
            ->all();
    }

    private function processProjectsData(Request $request, ?array $currentProjects = []): ?array
    {
        $raw = $request->input('projects_data', []);
        if (! is_array($raw) || empty($raw)) {
            return null;
        }

        $files = $request->file('projects_data', []);
        $projects = [];

        foreach ($raw as $index => $item) {
            if (empty($item['title']) && empty($item['description'])) {
                continue;
            }

            $imagePath = $item['old_image'] ?? null;

            if (isset($files[$index]['image']) && $files[$index]['image']->isValid()) {
                $file = $files[$index]['image'];
                $dir = 'uploads/services/projects';
                if (! file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $fileName = time().'-proj-'.$index.'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
                $dest = $dir.'/'.$fileName;
                $img = ImageManager::gd()->read($file->getRealPath())->scaleDown(width: 1200)->toWebp(100);
                file_put_contents($dest, $img);

                if ($imagePath && file_exists($imagePath)) {
                    @unlink($imagePath);
                }
                $imagePath = $dest;
            }

            $projects[] = [
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'image' => $imagePath,
                'point_1_title' => $item['point_1_title'] ?? '',
                'point_1_desc' => $item['point_1_desc'] ?? '',
                'point_2_title' => $item['point_2_title'] ?? '',
                'point_2_desc' => $item['point_2_desc'] ?? '',
                'button_text' => $item['button_text'] ?? 'Buat Website Anda Sekarang',
                'button_url' => $item['button_url'] ?? '',
                'status' => isset($item['status']) ? (bool) $item['status'] : true,
            ];
        }

        return $projects ?: null;
    }

    private function processWorkflowData(Request $request, ?array $currentWorkflow = []): ?array
    {
        $raw = $request->input('workflow_data', []);
        if (! is_array($raw) || empty($raw)) {
            return null;
        }

        $files = $request->file('workflow_data', []);
        $workflow = [];

        foreach ($raw as $index => $item) {
            if (empty($item['title']) && empty($item['description'])) {
                continue;
            }

            $imagePath = $item['old_image'] ?? null;
            if (isset($files[$index]['image']) && $files[$index]['image']->isValid()) {
                $file = $files[$index]['image'];
                $dir = 'uploads/services/workflow';
                if (! file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $fileName = time().'-wf-'.$index.'.webp';
                $dest = $dir.'/'.$fileName;
                $img = ImageManager::gd()->read($file->getRealPath())->scaleDown(width: 400)->toWebp(100);
                file_put_contents($dest, $img);
                if ($imagePath && file_exists($imagePath)) {
                    @unlink($imagePath);
                }
                $imagePath = $dest;
            }

            $workflow[] = [
                'step' => $item['step'] ?? ($index + 1),
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'icon' => $item['icon'] ?? '',
                'image' => $imagePath,
                'status' => isset($item['status']) ? (bool) $item['status'] : true,
            ];
        }

        return $workflow ?: null;
    }

    private function processPortfoliosData(Request $request, ?array $currentPortfolios = []): ?array
    {
        $raw = $request->input('portfolios_data', []);
        if (! is_array($raw) || empty($raw)) {
            return null;
        }

        $files = $request->file('portfolios_data', []);
        $portfolios = [];

        foreach ($raw as $index => $item) {
            if (empty($item['title'])) {
                continue;
            }

            $imagePath = $item['old_image'] ?? null;
            if (isset($files[$index]['image']) && $files[$index]['image']->isValid()) {
                $file = $files[$index]['image'];
                $dir = 'uploads/services/portfolios';
                if (! file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $fileName = time().'-pf-'.$index.'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
                $dest = $dir.'/'.$fileName;
                $img = ImageManager::gd()->read($file->getRealPath())->scaleDown(width: 1000)->toWebp(100);
                file_put_contents($dest, $img);
                if ($imagePath && file_exists($imagePath)) {
                    @unlink($imagePath);
                }
                $imagePath = $dest;
            }

            $portfolios[] = [
                'title' => $item['title'] ?? '',
                'subtitle' => $item['subtitle'] ?? '',
                'url' => $item['url'] ?? '',
                'image' => $imagePath,
                'status' => isset($item['status']) ? (bool) $item['status'] : true,
            ];
        }

        return $portfolios ?: null;
    }
}
