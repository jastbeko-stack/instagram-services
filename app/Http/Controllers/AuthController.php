<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'public, s-maxage=300, stale-while-revalidate=600');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $credentials['login'];
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$fieldType => $loginInput, 'password' => $credentials['password']], $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->is_banned) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $reason = $user->ban_reason ? "السبب: {$user->ban_reason}" : 'يرجى مراجعة الإدارة عبر تليجرام.';
                return back()->withErrors([
                    'login' => "تم حظر هذا الحساب من استخدام المنصة. {$reason}",
                ])->onlyInput('login');
            }

            $request->session()->regenerate();

            if ($user->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login' => 'اسم المستخدم / البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return response()
            ->view('auth.register')
            ->header('Cache-Control', 'public, s-maxage=300, stale-while-revalidate=600');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'telegram' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'telegram' => $validated['telegram'] ?? null,
                'password' => Hash::make($validated['password']),
                'plain_password' => $validated['password'],
                'balance' => 0.00,
                'is_admin' => false,
            ]);

            Auth::login($user);

            return redirect()->route('home')->with('success', 'أهلاً بك! تم إنشاء حسابك بنجاح.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Registration Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors([
                'email' => 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
