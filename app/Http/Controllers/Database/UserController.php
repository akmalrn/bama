<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use App\Models\CustomPermission as Permission;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat user', only: ['index']),
            new Middleware('permission:buat user', only: ['create', 'store']),
            new Middleware('permission:edit user', only: ['edit', 'update']),
            new Middleware('permission:hapus user', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::with('roles')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'God Admin');
            })
            ->when(
                $search,
                fn($query) =>
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
            )
            ->get();

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['God Admin', 'karyawan'])->get();

        return view('pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role'     => 'required|exists:roles,name',
            ],
            [

                'name.required' => 'Nama wajib diisi.',
                'name.max'      => 'Nama maksimal 255 karakter.',


                'email.required' => 'Email wajib diisi.',
                'email.email'    => 'Format email tidak valid.',
                'email.unique'   => 'Email sudah digunakan.',


                'password.required'  => 'Password wajib diisi.',
                'password.min'       => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',


                'role.required' => 'Role wajib dipilih.',
                'role.exists'   => 'Role tidak valid.',
            ],
            [

                'name'     => 'Nama',
                'email'    => 'Email',
                'password' => 'Password',
                'role'     => 'Role',
            ]
        );

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('dashboard-admin.users.index')->with('success', 'User berhasil ditambahkan dan diberikan role.');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        if ($user->hasAnyRole(['God Admin', 'karyawan'])) {
            abort(403, 'User ini tidak bisa diedit.');
        }

        $roles = Role::where('name', '!=', 'God Admin')->get();

        $permissions = Permission::all();

        return view('pages.users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $user->syncRoles([$validated['role']]);

        return redirect()->route('dashboard-admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function trashList()
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('pages.users.trash-list', compact('users'));
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('dashboard-admin.users.index')->with('success', 'User berhasil dikembalikan.');
    }

    public function trash($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('dashboard-admin.users.index')->with('success', 'User berhasil dipindahkan ke Trash.');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('dashboard-admin.users.index')->with('success', 'User berhasil dihapus permanen.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('pages.users.viewProfile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // upload foto
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if ($user->foto && file_exists(($user->foto))) {
                unlink(($user->foto));
            }

            $image = $request->file('foto');
            $imageName = time() . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $imagePath = 'uploads/users';

            if (!file_exists(($imagePath))) {
                mkdir(($imagePath), 0755, true);
            }

            $img = ImageManager::gd()
                ->read($image->getRealPath())
                ->scaleDown(width: 500)
                ->toWebp(80);

            file_put_contents(($imagePath . '/' . $imageName), $img);

            $data['foto'] = $imagePath . '/' . $imageName;
        }

        $user->update($data);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
