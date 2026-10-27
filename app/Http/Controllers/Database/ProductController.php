<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\CategoryProduct;
use App\Models\Database\Product;
use App\Models\Database\SectionProduct;
use App\Services\Image\WebpConversionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('permission:lihat produk|lihat kategori-produk', only: ['index']), new Middleware('permission:buat produk', only: ['create', 'store']), new Middleware('permission:edit produk', only: ['edit', 'update', 'storeOrUpdate']), new Middleware('permission:hapus produk', only: ['destroy'])];
    }

    public function index()
    {
        return view('pages.products.index', ['datas' => Product::with('category')->orderBy('order')->latest()->get(), 'categories' => CategoryProduct::latest()->get(), 'label' => SectionProduct::first()]);
    }

    public function create()
    {
        return view('pages.products.create', [
            'categories' => CategoryProduct::with('product')->orderBy('order')->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request, WebpConversionService $converter)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        DB::transaction(function () use ($request, $validated, $converter) {
            $product = new Product($this->productData($validated, $request));
            if ($request->hasFile('image')) {
                $product->image = $this->storeImage($request->file('image'), 'uploads/products', $converter);
            }
            $product->images = $this->storeGallery($request->file('images', []), $converter);
            $product->save();
            $this->syncContent($product, $validated, $request, $converter);
        });

        return redirect()->route('dashboard-admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load(['featureSection.features', 'ecosystemSection', 'solutionSection.solutions', 'productOverview', 'faqs']);
        $product->images = $this->imagePaths($product->images);

        return view('pages.products.edit', [
            'data' => $product,
            'categories' => CategoryProduct::with('product')->orderBy('order')->orderBy('title')->get(),
            'swapProducts' => Product::with('category')->whereKeyNot($product->getKey())->whereNotNull('id_category')->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Product $product, WebpConversionService $converter)
    {
        $validated = $request->validate($this->rules($product->id, $request->filled('swap_product_id')), $this->messages());
        DB::transaction(function () use ($request, $validated, $product, $converter) {
            if (! empty($validated['swap_product_id'])) {
                $swapProduct = Product::query()->lockForUpdate()->findOrFail($validated['swap_product_id']);
                $currentCategory = $product->id_category;
                $targetCategory = $swapProduct->id_category;
                $product->id_category = null;
                $product->save();
                $swapProduct->id_category = $currentCategory;
                $swapProduct->save();
                $validated['id_category'] = $targetCategory;
            }
            $product->fill($this->productData($validated, $request));
            if ($request->hasFile('image')) {
                $this->deleteFile($product->image);
                $product->image = $this->storeImage($request->file('image'), 'uploads/products', $converter);
            }
            $existing = $this->imagePaths($product->images);
            $retained = $request->boolean('gallery_state_submitted') ? array_values(array_intersect($existing, $validated['old_images'] ?? [])) : $existing;
            foreach (array_diff($existing, $retained) as $image) {
                $this->deleteFile($image);
            }
            $product->images = array_merge($retained, $this->storeGallery($request->file('images', []), $converter));
            $product->save();
            $this->syncContent($product, $validated, $request, $converter);
        });

        return redirect()->route('dashboard-admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->load(['featureSection.features', 'ecosystemSection', 'solutionSection.solutions', 'productOverview']);
        $paths = [$product->image, ...$this->imagePaths($product->images), $product->ecosystemSection?->image, $product->productOverview?->image];
        foreach ($product->featureSection?->features ?? [] as $item) {
            $paths[] = $item->image;
        }
        foreach ($product->solutionSection?->solutions ?? [] as $item) {
            $paths[] = $item->image;
        }
        $product->delete();
        foreach ($paths as $path) {
            $this->deleteFile($path);
        }

        return redirect()->route('dashboard-admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subTitle' => 'nullable|string|max:255',
            'span' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_image' => 'nullable|boolean',
        ]);

        $section = SectionProduct::first();
        $destinationDir = 'uploads/products';

        if (! file_exists(($destinationDir))) {
            mkdir(($destinationDir), 0755, true);
        }

        if ($request->boolean('remove_image')) {
            if ($section && $section->image && file_exists(($section->image))) {
                @unlink(($section->image));
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($section && $section->image && file_exists(($section->image))) {
                @unlink(($section->image));
            }

            $file = $request->file('image');
            $filename = time().'-hero-product-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';

            $img = ImageManager::gd()
                ->read($file->getRealPath())
                ->scaleDown(width: 1400)
                ->toWebp(80);

            file_put_contents(($destinationDir.'/'.$filename), (string) $img);
            $data['image'] = $destinationDir.'/'.$filename;
        }

        unset($data['remove_image']);

        SectionProduct::updateOrCreate(['id' => 1], $data);
        $tab = $request->input('active_tab', 'tab-label');

        return redirect()->route('dashboard-admin.products.index')->with('success', 'Label produk berhasil diperbarui.')->with('activeTab', $tab);
    }

    private function rules(?int $id = null, bool $isSwap = false): array
    {
        $categoryRules = ['required', 'integer', 'exists:categories_product,id'];
        $rules = [
            'id_category' => $categoryRules,
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'highlight' => ['nullable', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'button_name' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'old_images' => ['nullable', 'array'],
            'old_images.*' => ['string'],
            'gallery_state_submitted' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_descriptions' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'swap_product_id' => ['nullable', 'integer', Rule::exists('products', 'id')->whereNot('id', $id)],
            'order' => ['nullable', 'integer', 'min:0', $id ? Rule::unique('products')->ignore($id) : Rule::unique('products')],
        ];
        foreach (['feature_section', 'ecosystem', 'solution_section', 'product_overview'] as $key) {
            $rules[$key] = ['nullable', 'array'];
            foreach (['title', 'subtitle', 'highlight', 'button_name'] as $f) {
                $rules["$key.$f"] = ['nullable', 'string', 'max:255'];
            } foreach (['description', 'button_url'] as $f) {
                $rules["$key.$f"] = ['nullable', 'string'];
            } $rules["$key.order"] = ['nullable', 'integer', 'min:0'];
            $rules["$key.status"] = ['nullable', 'boolean'];
        }
        $rules['ecosystem.image'] = $rules['product_overview.image'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'];
        foreach (['features', 'solutions', 'faqs'] as $key) {
            $rules[$key] = ['nullable', 'array'];
            $rules["$key.*.id"] = ['nullable', 'integer'];
            $rules["$key.*.order"] = ['nullable', 'integer', 'min:0'];
            $rules["$key.*.status"] = ['nullable', 'boolean'];
        }
        foreach (['features', 'solutions'] as $key) {
            $rules["$key.*.title"] = ['nullable', 'string', 'max:255'];
            $rules["$key.*.description"] = ['nullable', 'string'];
            $rules["$key.*.button_name"] = ['nullable', 'string', 'max:255'];
            $rules["$key.*.button_url"] = ['nullable', 'string'];
            $rules["$key.*.image"] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'];
        }
        $rules['features.*.tab_name'] = ['nullable', 'string', 'max:255'];
        $rules['faqs.*.question'] = ['nullable', 'string', 'max:255'];
        $rules['faqs.*.answer'] = ['nullable', 'string'];

        return $rules;
    }

    private function messages(): array
    {
        return ['id_category.required' => 'Kategori produk wajib dipilih.', 'id_category.exists' => 'Kategori produk tidak valid.', 'title.required' => 'Judul produk wajib diisi.', 'image.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WEBP.', '*.image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.', 'order.unique' => 'Urutan produk sudah digunakan.'];
    }

    private function productData(array $data, Request $request): array
    {
        $result = Arr::only($data, ['id_category', 'title', 'subtitle', 'highlight', 'overview', 'description', 'button_name', 'button_url', 'icon', 'meta_title', 'meta_keywords', 'meta_descriptions', 'order']);
        $result['is_featured'] = $request->boolean('is_featured');
        $result['status'] = $request->boolean('status');

        return $result;
    }

    private function syncContent(Product $product, array $data, Request $request, WebpConversionService $converter): void
    {
        $feature = $product->featureSection()->updateOrCreate([], $this->sectionData($data['feature_section'] ?? [], $request, 'feature_section'));
        $this->syncItems($feature->features(), $data['features'] ?? [], $request, 'features', 'uploads/products/features', $converter, ['tab_name', 'title', 'description', 'button_name', 'button_url', 'order']);
        $ecosystem = $product->ecosystemSection()->firstOrNew();
        $ecosystem->fill($this->sectionData($data['ecosystem'] ?? [], $request, 'ecosystem'));
        $this->replaceImage($ecosystem, $request->file('ecosystem.image'), 'uploads/products/ecosystems', $converter);
        $ecosystem->save();
        $solution = $product->solutionSection()->updateOrCreate([], $this->sectionData($data['solution_section'] ?? [], $request, 'solution_section'));
        $this->syncItems($solution->solutions(), $data['solutions'] ?? [], $request, 'solutions', 'uploads/products/solutions', $converter, ['title', 'description', 'button_name', 'button_url', 'order']);
        $overview = $product->productOverview()->firstOrNew();
        $overview->fill($this->sectionData($data['product_overview'] ?? [], $request, 'product_overview'));
        $this->replaceImage($overview, $request->file('product_overview.image'), 'uploads/products/overviews', $converter);
        $overview->save();
        $this->syncItems($product->faqs(), $data['faqs'] ?? [], $request, 'faqs', '', $converter, ['question', 'answer', 'order'], false);
    }

    private function sectionData(array $data, Request $request, string $key): array
    {
        $result = Arr::only($data, ['title', 'subtitle', 'highlight', 'description', 'button_name', 'button_url', 'order']);
        $result['order'] = (int) ($result['order'] ?? 0);
        $result['status'] = $request->boolean("$key.status");

        return $result;
    }

    private function syncItems($relation, array $items, Request $request, string $key, string $dir, WebpConversionService $converter, array $fields, bool $images = true): void
    {
        $existing = $relation->get()->keyBy('id');
        $kept = [];
        foreach ($items as $i => $item) {
            if (! filled($item['title'] ?? $item['question'] ?? null)) {
                continue;
            }
            $model = isset($item['id']) ? $existing->get((int) $item['id']) : null;
            $model ??= $relation->make();
            $model->fill(Arr::only($item, $fields));
            $model->order = (int) ($item['order'] ?? 0);
            $model->status = $request->boolean("$key.$i.status");
            if ($images) {
                $this->replaceImage($model, $request->file("$key.$i.image"), $dir, $converter);
            }
            $model->save();
            $kept[] = $model->getKey();
        }
        foreach ($existing->whereNotIn('id', $kept) as $model) {
            if ($images) {
                $this->deleteFile($model->image);
            }
            $model->delete();
        }
    }

    private function replaceImage($model, $file, string $dir, WebpConversionService $converter): void
    {
        if ($file) {
            $this->deleteFile($model->image);
            $model->image = $this->storeImage($file, $dir, $converter);
        }
    }

    private function storeImage($file, string $dir, WebpConversionService $converter): string
    {
        $path = $dir.'/'.time().'-'.uniqid().'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp';
        $converter->convert($file->getRealPath(), $path);

        return $path;
    }

    private function storeGallery(array $files, WebpConversionService $converter): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file) {
                $paths[] = $this->storeImage($file, 'uploads/products/gallery', $converter);
            }
        }

        return $paths;
    }

    private function imagePaths(mixed $images): array
    {
        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        return is_array($images) ? array_values(array_filter($images, fn ($v) => is_string($v) && filled($v))) : [];
    }

    private function deleteFile(?string $path): void
    {
        if ($path && is_file($path)) {
            @unlink($path);
        }
    }
}
