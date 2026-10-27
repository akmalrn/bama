<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Overview;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class OverviewController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat tentang-kami', only: ['create']),
            new Middleware('permission:edit tentang-kami', only: ['storeOrUpdate']),
        ];
    }


    public function create()
    {
        $overview = Overview::first();
        return view('pages.overviews.create', compact('overview'));
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subTitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',            
            'overview' => 'nullable|string',
            'note' => 'nullable|string|max:255',
            'name_link' => 'nullable|string',
            'link' => 'nullable|string',
        ]);

        $overview = overview::find(1);

        if ($request->hasFile('image')) {
            if ($overview && $overview->image && file_exists(($overview->image))) {
                @unlink(($overview->image));
            }

            $image = $request->file('image');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/overview';
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 1920)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);
            $data['image'] = $imagePath . '/' . $imageName;
        }

        if ($request->hasFile('background')) {
            if ($overview && $overview->background && file_exists(($overview->background))) {
                @unlink(($overview->background));
            }

            $background = $request->file('background');
            $backgroundName = time() . '-' . pathinfo($background->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $backgroundPath = 'uploads/overview';
            if (!file_exists($backgroundPath)) {
                mkdir($backgroundPath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($background->getRealPath())
                ->scaleDown(width: 1920)
                ->toWebp(80);

            file_put_contents($backgroundPath . '/' . $backgroundName, $img);
            $data['background'] = $backgroundPath . '/' . $backgroundName;
        }

        overview::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.overviews.create')->with('success', 'Ringkasan berhasil disimpan.');
    }
}
