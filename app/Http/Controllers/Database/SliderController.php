<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\LabelSlider;
use App\Models\Database\Slider;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SliderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat slider', only: ['index']),
            new Middleware('permission:buat slider', only: ['create', 'store']),
            new Middleware('permission:edit slider', only: ['edit', 'update']),
            new Middleware('permission:hapus slider', only: ['destroy']),
            new Middleware('permission:edit label slider', only: ['storeOrUpdate']),
        ];
    }

    public function index(Request $request)
    {
        $sliders = Slider::latest()->get();
        $label = LabelSlider::first();
        return view('pages.sliders.index', compact('sliders', 'label'));
    }


    public function create()
    {
        return view('pages.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'required|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:20480',

            'name_link' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'name_link_2' => 'nullable|string|max:255',
            'link_2' => 'nullable|string|max:255',
        ], [
            'image.mimes' => 'Format file harus JPG, JPEG, PNG, WEBP',
            'image.max' => 'Ukuran file maksimal 20MB.',
        ]);

        $slider = new Slider();

        $slider->title = $request->title;
        $slider->sub_title = $request->sub_title;
        $slider->description = $request->description;

        $slider->name_link = $request->name_link;
        $slider->link = $request->link;

        $slider->name_link_2 = $request->name_link_2;
        $slider->link_2 = $request->link_2;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $extension = strtolower($file->getClientOriginalExtension());

            $imagePath = 'uploads/sliders';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {

                $imageName = time() . '-' .
                    pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .
                    '.webp';

                $img = ImageManager::gd()
                    ->read($file->getRealPath())
                    ->scale(width: 1920)
                    ->toWebp(90);

                file_put_contents($imagePath . '/' . $imageName, $img);

                $slider->image = $imagePath . '/' . $imageName;
            } else {

                $videoName = time() . '-' . $file->getClientOriginalName();

                $file->move($imagePath, $videoName);

                $slider->image = $imagePath . '/' . $videoName;
            }
        }

        $slider->save();

        return redirect()
            ->route('dashboard-admin.sliders.index')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('pages.sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',

            'image' => 'nullable|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:20480',

            'name_link' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'name_link_2' => 'nullable|string|max:255',
            'link_2' => 'nullable|string|max:255',
        ], [
            'image.mimes' => 'Format file harus JPG, JPEG, PNG, WEBP',
            'image.max' => 'Ukuran file maksimal 20MB.',
        ]);

        $slider = Slider::findOrFail($id);

        $slider->title = $request->title;
        $slider->sub_title = $request->sub_title;
        $slider->description = $request->description;

        $slider->name_link = $request->name_link;
        $slider->link = $request->link;

        $slider->name_link_2 = $request->name_link_2;
        $slider->link_2 = $request->link_2;

        if ($request->hasFile('image')) {
            if ($slider->image && file_exists($slider->image)) {
                @unlink($slider->image);
            }

            $file = $request->file('image');

            $extension = strtolower($file->getClientOriginalExtension());

            $imagePath = 'uploads/sliders';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {

                $imageName = time() . '-' .
                    pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .
                    '.webp';

                $img = ImageManager::gd()
                    ->read($file->getRealPath())
                    ->scale(width: 1920)
                    ->toWebp(90);

                file_put_contents($imagePath . '/' . $imageName, $img);

                $slider->image = $imagePath . '/' . $imageName;
            } else {

                $videoName = time() . '-' . $file->getClientOriginalName();

                $file->move($imagePath, $videoName);

                $slider->image = $imagePath . '/' . $videoName;
            }
        }

        $slider->save();

        return redirect()
            ->route('dashboard-admin.sliders.index')
            ->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image && file_exists(($slider->image))) {
            @unlink(($slider->image));
        }
        $slider->delete();
        return redirect()->route('dashboard-admin.sliders.index')->with('success', 'Slider berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'name_link' => 'nullable|string',
            'link' => 'nullable|string',
            'name_link_2' => 'nullable|string',
            'link_2' => 'nullable|string',
        ]);

        LabelSlider::updateOrCreate(['id' => 1], $data);

        $activeTab = $request->input('active_tab', 'tab-label');

        $message = $activeTab === 'tab-informasi'
            ? 'Informasi layanan berhasil diperbarui.'
            : 'Label layanan berhasil diperbarui.';

        return redirect()
            ->route('dashboard-admin.sliders.index')
            ->with('success', $message)
            ->with('activeTab', $activeTab);
    }
}
