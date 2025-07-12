<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'البريد الإلكتروني أو رقم الهاتف مطلوب.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        Log::info('Login attempt', [
            'input' => $request->only('email'),
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            Log::info('Login successful', [
                'user_id' => Auth::id(),
                'email' => $request->email,
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);

            $user = Auth::user();
            if ($user->role_name === 'teacher') {
                return redirect()->route('dashboard')
                    ->with('success', 'تم تسجيل الدخول بنجاح!');
            } elseif ($user->role_name === 'organization') {
                return redirect()->route('pdashboard')
                    ->with('success', 'تم تسجيل الدخول بنجاح!');
            }

            return redirect()->route('pdashboard')
                ->with('success', 'تم تسجيل الدخول بنجاح!');
        }

        Log::warning('Login failed', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->back()
            ->withErrors(['email' => 'بيانات الاعتماد هذه غير متطابقة مع سجلاتنا.'])
            ->withInput($request->only('email', 'remember'));
    }    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login')
            ->with('success', 'تم تسجيل الخروج بنجاح!');
    }
}