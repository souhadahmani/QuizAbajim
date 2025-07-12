<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        $role = Role::where('name', 'teacher')->orwhere('name', 'organization')->get();
        \Log::info('Roles fetched for registration form', ['roles' => $role->toArray()]);
        return view('auth.register', compact('role'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,id'],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'الاسم الكامل مطلوب.',
            'name.string' => 'الاسم الكامل يجب أن يكون نصًا.',
            'name.max' => 'الاسم الكامل يجب ألا يتجاوز 255 حرفًا.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صالحًا.',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'role.required' => 'نوع الحساب مطلوب.',
            'role.exists' => 'نوع الحساب غير صالح.',
            'terms.required' => 'يجب الموافقة على الشروط والقوانين.',
            'terms.accepted' => 'يجب الموافقة على الشروط والقوانين.',
        ]);

        Log::info('Registration attempt', [
            'input' => $request->all(),
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);

        $findnamerole = Role::where('id', $request->role)->pluck('name');

        if ($findnamerole->isEmpty()) {
            Log::error('Role not found for ID: ' . $request->role);
            return redirect()->back()->withErrors(['role' => 'نوع الحساب غير صالح.']);
        }

        try {
            $user = User::create([
                'full_name' => $request->name,
                'email' => $request->email,
                'role_name' => $findnamerole[0],
                'role_id' => $request->role,
                'status' => 'pending',
                'password' => Hash::make($request->password),
                'mobile' => null,
                'affiliate' => 0,
                'timezone' => null,
            ]);

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            // Send email verification notification
            $user->sendEmailVerificationNotification();

            event(new Registered($user));

            Log::info('Registration successful', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            // Log the user in for both teachers and other users
            Auth::login($user);

            // If user is a teacher, redirect to profile creation
            if ($findnamerole[0] === 'teacher') {
                return redirect()->route('teacher.profile.create')
                    ->with('success', 'تم التسجيل بنجاح! يرجى اختيار المواد والمستوى الدراسي الذي تدرسه.');
            }

            // For other users (like organizations), redirect to email verification
            return redirect()->route('verification.notice')
                ->with('status', 'تم التسجيل بنجاح! يرجى التحقق من بريدك الإلكتروني وتفعيل حسابك.');
                
        } catch (\Exception $e) {
            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);

            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()]);
        }
    }
}