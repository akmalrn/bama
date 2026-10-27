<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\SectionTeam;
use Illuminate\Http\Request;
use App\Models\Database\Team;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TeamController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat tim-kami', only: ['index']),
            new Middleware('permission:buat tim-kami', only: ['create', 'store']),
            new Middleware('permission:edit tim-kami', only: ['edit', 'update']),
            new Middleware('permission:hapus tim-kami', only: ['destroy']),
            new Middleware('permission:edit label tim-kami', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $label = SectionTeam::first();
        $teams = Team::latest()->get();
        return view('pages.teams.index', compact('label', 'teams'));
    }

    public function create()
    {
        return view('pages.teams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/teams';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 400)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);
            $data['image'] = $imagePath . '/' . $imageName;
        }

        Team::create($data);

        return redirect()->route('dashboard-admin.teams.index')
            ->with('success', 'Team berhasil disimpan.');
    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return view('pages.teams.edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
        ]);

        if ($request->hasFile('image')) {

            if (!empty($team->image) && file_exists($team->image)) {
                @unlink($team->image);
            }

            $image = $request->file('image');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/teams';

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 400)
                ->toWebp(80);

            file_put_contents($imagePath . '/' . $imageName, $img);
            $data['image'] = $imagePath . '/' . $imageName;
        }

        $team->update($data);

        return redirect()->route('dashboard-admin.teams.index')
            ->with('success', 'Team berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        if (!empty($team->image) && file_exists($team->image)) {
            @unlink($team->image);
        }

        $team->delete();

        return redirect()->route('dashboard-admin.teams.index')
            ->with('success', 'Team berhasil dihapus.');
    }
    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
            'span' => 'nullable|string',
        ]);

        SectionTeam::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.teams.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
