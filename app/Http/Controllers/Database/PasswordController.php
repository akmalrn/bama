<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function changePassword()
    {
        return view('backend.pages.password.change-password');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'current_password' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'current_password.required' => 'Password lama wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ]);
        }
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
