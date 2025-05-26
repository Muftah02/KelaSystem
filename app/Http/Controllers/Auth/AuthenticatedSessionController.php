<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();

        // التوجيه بناءً على الدور
        if ($user->role === 'manager') {
            return redirect()->route('manager.dashboard'); // صفحة لوحة تحكم المدير
        } elseif ($user->role === 'accountant') {
            return redirect()->route('accountant.dashboard'); // صفحة لوحة تحكم المحاسب
        }
        
        // في حال عدم وجود دور صحيح
        return redirect()->route('dashboard'); // صفحة الافتراضية إذا لم يكن هناك دور محدد
    }

    return back()->withErrors([
        'email' => __('auth.failed'),
    ]);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
