<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ImpersonateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:impersonate', only: ['impersonate']),
        ];
    }
    public function impersonate($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() !== $user->id) {
            auth()->user()->impersonate($user);

            return redirect()
                ->route('dashboard-admin.index')
                ->with('success', 'Anda sekarang sedang melakukan peniruan identitas: ' . $user->name);
        }

        abort(403);
    }
    public function leave()
    {
        if (!auth()->user()->isImpersonated()) {
            abort(403);
        }

        auth()->user()->leaveImpersonation();

        return redirect()
            ->route('dashboard-admin.users.index')
            ->with('success', 'Kembali ke akun admin.');
    }
}
