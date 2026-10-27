<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\CustomRole as Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat role', only: ['index']),
            new Middleware('permission:buat role', only: ['create', 'store']),
            new Middleware('permission:edit role', only: ['edit', 'update']),
            new Middleware('permission:hapus role', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $isGodAdmin = $currentUser && $currentUser->hasRole('God Admin');

        $query = Role::with(['permissions', 'users']);

        if (! $isGodAdmin) {
            $query->where('name', '!=', 'God Admin');
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $roles = $query->get();

        return view('pages.users.roles.index', compact('roles', 'isGodAdmin'));
    }

    public function create()
    {
        $permissions = Permission::all();

        return view('pages.users.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        try {
            $role = Role::create([
                'name' => $request->name,
            ]);

            $permissions = $request->input('permissions', []);
            $role->syncPermissions($permissions);

            return redirect()->route('dashboard-admin.roles.index')->with('success', 'Role berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan role: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $role = Role::with(['permissions', 'users'])->findOrFail($id);

        return view('pages.users.roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $currentUser = auth()->user();

        if ($role->isGodAdmin() && (! $currentUser || ! $currentUser->hasRole('God Admin'))) {
            abort(403, 'Akses ditolak. Hanya God Admin yang berhak mengakses role ini.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('pages.users.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $currentUser = auth()->user();

        if ($role->isGodAdmin() && (! $currentUser || ! $currentUser->hasRole('God Admin'))) {
            abort(403, 'Akses ditolak. Hanya God Admin yang berhak mengubah role ini.');
        }

        // If core role: name is immutable
        if ($role->isCore()) {
            $request->validate([
                'permissions' => 'nullable|array',
            ]);
        } else {
            $request->validate([
                'name' => 'required|unique:roles,name,'.$id,
                'permissions' => 'nullable|array',
            ]);

            $role->update(['name' => $request->name]);
        }

        try {
            if ($role->isGodAdmin()) {
                // God Admin always gets all permissions
                $role->syncPermissions(Permission::all());
            } else {
                $permissions = $request->input('permissions', []);
                $role->syncPermissions($permissions);
            }

            $message = $role->isCore()
                ? 'Permission untuk role inti sistem "'.$role->name.'" berhasil diperbarui (nama role dikunci).'
                : 'Role berhasil diperbarui.';

            return redirect()->route('dashboard-admin.roles.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui role: '.$e->getMessage());
        }
    }

    public function trashList()
    {
        $currentUser = auth()->user();
        $isGodAdmin = $currentUser && $currentUser->hasRole('God Admin');

        $query = Role::onlyTrashed();
        if (! $isGodAdmin) {
            $query->where('name', '!=', 'God Admin');
        }

        $roles = $query->paginate(10);

        return view('pages.users.roles.trash-list', compact('roles', 'isGodAdmin'));
    }

    public function restore($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $currentUser = auth()->user();

        if ($role->isCore() && (! $currentUser || ! $currentUser->hasRole('God Admin'))) {
            return redirect()->route('dashboard-admin.roles.trash.list')
                ->with('error', 'Role inti sistem hanya dapat dipulihkan oleh God Admin.');
        }

        $role->restore();

        return redirect()->route('dashboard-admin.roles.index')->with('success', 'Role berhasil dipulihkan.');
    }

    public function trash($id)
    {
        $role = Role::findOrFail($id);
        $currentUser = auth()->user();

        if ($role->isGodAdmin()) {
            return redirect()->route('dashboard-admin.roles.index')
                ->with('error', 'Role God Admin adalah root tertinggi sistem dan tidak dapat dihapus.');
        }

        if ($role->isCore() && (! $currentUser || ! $currentUser->hasRole('God Admin'))) {
            return redirect()->route('dashboard-admin.roles.index')
                ->with('error', 'Role inti sistem ("'.$role->name.'") tidak dapat dihapus kecuali oleh God Admin.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('dashboard-admin.roles.index')
                ->with('error', 'Role "'.$role->name.'" tidak dapat dihapus karena masih digunakan oleh '.$role->users()->count().' user.');
        }

        $role->delete();

        return redirect()->route('dashboard-admin.roles.index')->with('success', 'Role "'.$role->name.'" berhasil dipindahkan ke Trash.');
    }

    public function forceDelete($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $currentUser = auth()->user();

        if ($role->isGodAdmin()) {
            return redirect()->route('dashboard-admin.roles.trash.list')
                ->with('error', 'Role God Admin adalah root tertinggi sistem dan tidak dapat dihapus permanen.');
        }

        if ($role->isCore() && (! $currentUser || ! $currentUser->hasRole('God Admin'))) {
            return redirect()->route('dashboard-admin.roles.trash.list')
                ->with('error', 'Role inti sistem ("'.$role->name.'") tidak dapat dihapus permanen kecuali oleh God Admin.');
        }

        $role->forceDelete();

        return redirect()->route('dashboard-admin.roles.trash.list')->with('success', 'Role "'.$role->name.'" berhasil dihapus secara permanen.');
    }
}
