<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryProject;
use App\Models\Database\Project;
use App\Models\Database\SectionProject;
use App\Services\Image\WebpConversionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat projek|lihat kategori-projek', only: ['index']),
            new Middleware('permission:buat projek', only: ['create', 'store']),
            new Middleware('permission:edit projek', only: ['edit', 'update']),
            new Middleware('permission:hapus projek', only: ['destroy']),
            new Middleware('permission:edit label projek', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $label = SectionProject::first();
        $datas = Project::with('category')->orderBy('order')->latest()->get();
        $categories = CategoryProject::latest()->get();

        return view('pages.projects.index', compact('label', 'datas', 'categories'));
    }

    public function create()
    {
        $categories = CategoryProject::all();

        return view('pages.projects.create', compact('categories'));
    }

    public function store(Request $request, WebpConversionService $converter)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        DB::transaction(function () use ($request, $validated, $converter) {
            $project = new Project($this->projectData($validated, $request));

            if ($request->hasFile('image')) {
                $project->image = $this->storeImage($request->file('image'), 'uploads/projects', $converter);
            }

            $project->save();

            $this->syncContent($project, $validated, $request, $converter);
        });

        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.projects.index')
            ->with('success', 'Perbandingan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Project::with([
            'partnerSection.partners',
            'superioritySection.superiorities',
            'featureSection.features',
            'whyUsSection.items',
            'ctaSection',
        ])->findOrFail($id);

        $categories = CategoryProject::all();

        return view('pages.projects.edit', compact('data', 'categories'));
    }

    public function update(Request $request, $id, WebpConversionService $converter)
    {
        $validated = $request->validate($this->rules($id), $this->messages());

        DB::transaction(function () use ($request, $validated, $id, $converter) {
            $project = Project::findOrFail($id);
            $project->fill($this->projectData($validated, $request));

            if ($request->hasFile('image')) {
                $this->deleteFile($project->image);
                $project->image = $this->storeImage($request->file('image'), 'uploads/projects', $converter);
            }

            $project->save();

            $this->syncContent($project, $validated, $request, $converter);
        });

        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.projects.index')
            ->with('success', 'Perbandingan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $project = Project::with([
            'partnerSection.partners',
            'superioritySection.superiorities',
            'featureSection.features',
            'whyUsSection.items',
            'ctaSection',
        ])->findOrFail($id);

        $this->deleteFile($project->image);

        if ($project->partnerSection) {
            foreach ($project->partnerSection->partners as $p) {
                $this->deleteFile($p->image);
            }
        }

        if ($project->superioritySection) {
            foreach ($project->superioritySection->superiorities as $s) {
                $this->deleteFile($s->image);
            }
        }

        if ($project->featureSection) {
            foreach ($project->featureSection->features as $f) {
                $this->deleteFile($f->image);
            }
        }

        if ($project->whyUsSection) {
            foreach ($project->whyUsSection->items as $w) {
                $this->deleteFile($w->image);
            }
        }

        if ($project->ctaSection) {
            $this->deleteFile($project->ctaSection->banner_image);
            $this->deleteFile($project->ctaSection->solution_image);
        }

        $project->delete();

        Artisan::call('sitemap:generate');

        return redirect()
            ->route('dashboard-admin.projects.index')
            ->with('success', 'Perbandingan berhasil dihapus.');
    }

    private function rules($id = null): array
    {
        return [
            'id_category' => ['nullable', 'exists:categories_project,id'],
            'title' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'name_link' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'string'],
            'button_name' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string'],
            'button_name_2' => ['nullable', 'string', 'max:255'],
            'button_url_2' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_descriptions' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],

            // Partner Section
            'partner_section' => ['nullable', 'array'],
            'partners' => ['nullable', 'array'],
            'partners.*.id' => ['nullable', 'integer'],
            'partners.*.name' => ['nullable', 'string', 'max:255'],
            'partners.*.image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'partners.*.order' => ['nullable', 'integer', 'min:0'],

            // Superiority Section
            'superiority_section' => ['nullable', 'array'],
            'superiorities' => ['nullable', 'array'],
            'superiorities.*.id' => ['nullable', 'integer'],
            'superiorities.*.title' => ['nullable', 'string', 'max:255'],
            'superiorities.*.description' => ['nullable', 'string'],
            'superiorities.*.icon' => ['nullable', 'string', 'max:255'],
            'superiorities.*.image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'superiorities.*.order' => ['nullable', 'integer', 'min:0'],

            // Feature Section
            'feature_section' => ['nullable', 'array'],
            'features' => ['nullable', 'array'],
            'features.*.id' => ['nullable', 'integer'],
            'features.*.tab_name' => ['nullable', 'string', 'max:255'],
            'features.*.title' => ['nullable', 'string', 'max:255'],
            'features.*.subtitle' => ['nullable', 'string', 'max:255'],
            'features.*.description' => ['nullable', 'string'],
            'features.*.button_name' => ['nullable', 'string', 'max:255'],
            'features.*.button_url' => ['nullable', 'string'],
            'features.*.bullet_points' => ['nullable'],
            'features.*.image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'features.*.order' => ['nullable', 'integer', 'min:0'],

            // Why Us Section
            'why_us_section' => ['nullable', 'array'],
            'why_us_items' => ['nullable', 'array'],
            'why_us_items.*.id' => ['nullable', 'integer'],
            'why_us_items.*.title' => ['nullable', 'string', 'max:255'],
            'why_us_items.*.description' => ['nullable', 'string'],
            'why_us_items.*.card_type' => ['nullable', 'string'],
            'why_us_items.*.image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'why_us_items.*.order' => ['nullable', 'integer', 'min:0'],

            // CTA Section
            'cta_section' => ['nullable', 'array'],
            'cta_section.banner_title' => ['nullable', 'string'],
            'cta_section.banner_subtitle' => ['nullable', 'string'],
            'cta_section.banner_button_name' => ['nullable', 'string'],
            'cta_section.banner_button_url' => ['nullable', 'string'],
            'cta_section.banner_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'cta_section.solution_title' => ['nullable', 'string'],
            'cta_section.solution_subtitle' => ['nullable', 'string'],
            'cta_section.solution_button_name' => ['nullable', 'string'],
            'cta_section.solution_button_url' => ['nullable', 'string'],
            'cta_section.solution_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    private function messages(): array
    {
        return [
            'title.required' => 'Judul perbandingan wajib diisi.',
            'image.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WEBP.',
            '*.image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
        ];
    }

    private function projectData(array $data, Request $request): array
    {
        $result = Arr::only($data, [
            'id_category',
            'title',
            'badge',
            'overview',
            'name_link',
            'link',
            'button_name',
            'button_url',
            'button_name_2',
            'button_url_2',
            'meta_title',
            'meta_keywords',
            'meta_descriptions',
            'order',
        ]);

        $result['status'] = $request->boolean('status');
        $result['is_featured'] = $request->boolean('is_featured');
        $result['order'] = (int) ($result['order'] ?? 0);

        return $result;
    }

    private function syncContent(Project $project, array $data, Request $request, WebpConversionService $converter): void
    {
        // 1. Partner Section & Partners
        $partnerSection = $project->partnerSection()->firstOrNew();
        $partnerSection->title = $data['partner_section']['title'] ?? null;
        $partnerSection->status = $request->boolean('partner_section.status');
        $partnerSection->save();

        $this->syncRepeaterItems(
            $partnerSection->partners(),
            $data['partners'] ?? [],
            $request,
            'partners',
            'uploads/projects/partners',
            $converter,
            ['name', 'order']
        );

        // 2. Superiority Section & Superiorities
        $supSection = $project->superioritySection()->firstOrNew();
        $supSection->title = $data['superiority_section']['title'] ?? null;
        $supSection->status = $request->boolean('superiority_section.status');
        $supSection->save();

        $this->syncRepeaterItems(
            $supSection->superiorities(),
            $data['superiorities'] ?? [],
            $request,
            'superiorities',
            'uploads/projects/superiorities',
            $converter,
            ['title', 'description', 'icon', 'order']
        );

        // 3. Feature Section & Features
        $featSection = $project->featureSection()->firstOrNew();
        $featSection->title = $data['feature_section']['title'] ?? null;
        $featSection->subtitle = $data['feature_section']['subtitle'] ?? null;
        $featSection->description = $data['feature_section']['description'] ?? null;
        $featSection->status = $request->boolean('feature_section.status');
        $featSection->save();

        $this->syncFeatureItems(
            $featSection->features(),
            $data['features'] ?? [],
            $request,
            'features',
            'uploads/projects/features',
            $converter
        );

        // 4. Why Us Section & Items
        $whySection = $project->whyUsSection()->firstOrNew();
        $whySection->title = $data['why_us_section']['title'] ?? null;
        $whySection->status = $request->boolean('why_us_section.status');
        $whySection->save();

        $this->syncRepeaterItems(
            $whySection->items(),
            $data['why_us_items'] ?? [],
            $request,
            'why_us_items',
            'uploads/projects/why-us',
            $converter,
            ['title', 'description', 'card_type', 'order']
        );

        // 5. CTA Section
        $ctaSection = $project->ctaSection()->firstOrNew();
        $ctaSection->banner_title = $data['cta_section']['banner_title'] ?? null;
        $ctaSection->banner_subtitle = $data['cta_section']['banner_subtitle'] ?? null;
        $ctaSection->banner_button_name = $data['cta_section']['banner_button_name'] ?? null;
        $ctaSection->banner_button_url = $data['cta_section']['banner_button_url'] ?? null;

        if ($request->hasFile('cta_section.banner_image')) {
            $this->deleteFile($ctaSection->banner_image);
            $ctaSection->banner_image = $this->storeImage($request->file('cta_section.banner_image'), 'uploads/projects/cta', $converter);
        }

        $ctaSection->solution_title = $data['cta_section']['solution_title'] ?? null;
        $ctaSection->solution_subtitle = $data['cta_section']['solution_subtitle'] ?? null;
        $ctaSection->solution_button_name = $data['cta_section']['solution_button_name'] ?? null;
        $ctaSection->solution_button_url = $data['cta_section']['solution_button_url'] ?? null;

        if ($request->hasFile('cta_section.solution_image')) {
            $this->deleteFile($ctaSection->solution_image);
            $ctaSection->solution_image = $this->storeImage($request->file('cta_section.solution_image'), 'uploads/projects/cta', $converter);
        }

        $ctaSection->status = $request->boolean('cta_section.status');
        $ctaSection->save();
    }

    private function syncRepeaterItems($relation, array $items, Request $request, string $key, string $dir, WebpConversionService $converter, array $fields): void
    {
        $existing = $relation->get()->keyBy('id');
        $kept = [];

        foreach ($items as $i => $item) {
            if (! filled($item['title'] ?? $item['name'] ?? null)) {
                continue;
            }

            $model = isset($item['id']) ? $existing->get((int) $item['id']) : null;
            $model ??= $relation->make();
            $model->fill(Arr::only($item, $fields));
            $model->order = (int) ($item['order'] ?? 0);
            $model->status = $request->boolean("$key.$i.status");

            if ($request->hasFile("$key.$i.image")) {
                $this->deleteFile($model->image);
                $model->image = $this->storeImage($request->file("$key.$i.image"), $dir, $converter);
            }

            $model->save();
            $kept[] = $model->getKey();
        }

        foreach ($existing->whereNotIn('id', $kept) as $model) {
            $this->deleteFile($model->image);
            $model->delete();
        }
    }

    private function syncFeatureItems($relation, array $items, Request $request, string $key, string $dir, WebpConversionService $converter): void
    {
        $existing = $relation->get()->keyBy('id');
        $kept = [];

        foreach ($items as $i => $item) {
            if (! filled($item['title'] ?? $item['tab_name'] ?? null)) {
                continue;
            }

            $model = isset($item['id']) ? $existing->get((int) $item['id']) : null;
            $model ??= $relation->make();
            $model->tab_name = $item['tab_name'] ?? null;
            $model->title = $item['title'] ?? '';
            $model->subtitle = $item['subtitle'] ?? null;
            $model->description = $item['description'] ?? null;
            $model->button_name = $item['button_name'] ?? null;
            $model->button_url = $item['button_url'] ?? null;

            // Handle bullet points
            $rawBullets = $item['bullet_points'] ?? [];
            if (is_string($rawBullets)) {
                $rawBullets = array_values(array_filter(array_map('trim', explode("\n", $rawBullets))));
            }
            $model->bullet_points = is_array($rawBullets) ? $rawBullets : [];

            $model->order = (int) ($item['order'] ?? 0);
            $model->status = $request->boolean("$key.$i.status");

            if ($request->hasFile("$key.$i.image")) {
                $this->deleteFile($model->image);
                $model->image = $this->storeImage($request->file("$key.$i.image"), $dir, $converter);
            }

            $model->save();
            $kept[] = $model->getKey();
        }

        foreach ($existing->whereNotIn('id', $kept) as $model) {
            $this->deleteFile($model->image);
            $model->delete();
        }
    }

    private function storeImage($file, string $dir, WebpConversionService $converter): string
    {
        $path = $dir.'/'.time().'-'.uniqid().'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
        $converter->convert($file->getRealPath(), $path);

        return $path;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && is_file(($path))) {
            @unlink(($path));
        }
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'span' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        SectionProject::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.projects.index')
            ->with('success', 'Label perbandingan berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
