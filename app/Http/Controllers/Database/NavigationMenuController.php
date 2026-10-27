<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\NavigationMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class NavigationMenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat menu-navigasi', only: ['index']),
            new Middleware('permission:buat menu-navigasi', only: ['create', 'store']),
            new Middleware('permission:edit menu-navigasi', only: ['edit', 'update']),
            new Middleware('permission:hapus menu-navigasi', only: ['destroy']),
            new Middleware('permission:edit label menu-navigasi', only: ['storeOrUpdate']),
        ];
    }

    public function index()
    {
        $menus = NavigationMenu::query()
            ->with('parent')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get();

        return view('pages.navigation-menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = NavigationMenu::query()
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('pages.navigation-menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $validated['is_active'] = $request->boolean('is_active');

        NavigationMenu::create($validated);

        return redirect()
            ->route('dashboard-admin.navigation-menus.index')
            ->with('success', 'Menu navigasi berhasil ditambahkan.');
    }

    public function edit(NavigationMenu $navigationMenu)
    {
        $parentMenus = NavigationMenu::query()
            ->whereNull('parent_id')
            ->whereKeyNot($navigationMenu->getKey())
            ->orderBy('order')
            ->get();

        return view(
            'pages.navigation-menus.edit',
            compact('navigationMenu', 'parentMenus')
        );
    }

    public function update(Request $request, NavigationMenu $navigationMenu)
    {
        $validated = $request->validate($this->rules($navigationMenu));

        if (!$request->user()->hasRole('God Admin')) {
            $validated['key'] = $navigationMenu->key;
        }

        if ($this->isDescendant($navigationMenu, $validated['parent_id'] ?? null)) {
            return back()
                ->withInput()
                ->withErrors([
                    'parent_id' => 'Menu turunan tidak dapat dijadikan sebagai parent.',
                ]);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $navigationMenu->update($validated);

        return redirect()
            ->route('dashboard-admin.navigation-menus.index')
            ->with('success', 'Menu navigasi berhasil diperbarui.');
    }

    public function destroy(NavigationMenu $navigationMenu)
    {
        $navigationMenu->delete();

        return redirect()
            ->route('dashboard-admin.navigation-menus.index')
            ->with('success', 'Menu navigasi berhasil dihapus.');
    }

    private function rules(?NavigationMenu $navigationMenu = null): array
    {
        return [
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('navigation_menus', 'id'),
                Rule::notIn(array_filter([$navigationMenu?->getKey()])),
            ],
            'key' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('navigation_menus', 'key')->ignore($navigationMenu?->getKey()),
            ],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2048'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    private function isDescendant(NavigationMenu $navigationMenu, ?int $parentId): bool
    {
        while ($parentId !== null) {
            if ($parentId === $navigationMenu->getKey()) {
                return true;
            }

            $parentId = NavigationMenu::whereKey($parentId)->value('parent_id');
        }

        return false;
    }
}
