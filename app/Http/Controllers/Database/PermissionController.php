<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use App\Models\CustomPermission as Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $permissions = $query->orderBy('name')->get();
        return view('pages.users.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('pages.users.permissions.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('dashboard-admin.permissions.index')->with('success', 'Permission berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('pages.users.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('dashboard-admin.permissions.index')->with('success', 'Permission berhasil di update.');
    }

    public function trashList()
    {
        $permissions = Permission::onlyTrashed()->paginate(10);
        return view('pages.users.permissions.trash-list', compact('permissions'));
    }

    public function restore($id)
    {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->restore();
        return redirect()->route('dashboard-admin.permissions.index')->with('success', 'Permission berhasil dipulihkan.');
    }

    public function trash($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('dashboard-admin.permissions.index')->with('success', 'Permission berhasil dipindahkan ke Trash.');
    }

    public function forceDelete($id)
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->forceDelete();
        return redirect()->route('dashboard-admin.permissions.index')->with('success', 'Permission berhasil dihapus permanen.');
    }
}
