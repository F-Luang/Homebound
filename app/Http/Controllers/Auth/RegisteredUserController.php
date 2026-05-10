<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => in_array($request->role, ['adopter', 'volunteer']) ? $request->role : 'adopter',
            'is_approved' => $request->role === 'volunteer' ? false : true,
            'verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(15),
        ]);

        // Send verification email
        Mail::send([], [], function ($message) use ($user, $code) {
            $message->to($user->email)
                ->subject('Your Homebound verification code')
                ->html("
                    <div style='font-family:sans-serif;max-width:480px;margin:0 auto;padding:32px;'>
                        <h2 style='font-size:20px;color:#1a1a18;margin-bottom:8px;'>Verify your email</h2>
                        <p style='color:#666;font-size:14px;margin-bottom:24px;'>
                            Enter this code to complete your Homebound registration. It expires in 15 minutes.
                        </p>
                        <div style='background:#F5F4F0;border-radius:12px;padding:24px;text-align:center;margin-bottom:24px;'>
                            <div style='font-size:36px;font-weight:700;letter-spacing:10px;color:#1D9E75;'>{$code}</div>
                        </div>
                        <p style='color:#aaa;font-size:12px;'>If you didn't create a Homebound account, you can ignore this email.</p>
                    </div>
                ");
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.code.show');
    }

    // Show the code entry form
    public function showVerificationForm(): View
    {
        return view('auth.verify-code');
    }

    // Handle code submission
    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();

        if (
            $user->verification_code !== $request->code ||
            now()->isAfter($user->verification_code_expires_at)
        ) {
            throw ValidationException::withMessages([
                'code' => 'The code is invalid or has expired.',
            ]);
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Email verified! Welcome to Homebound.');
    }

    // Resend a fresh code
    public function resendCode(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(15),
        ]);

        Mail::send([], [], function ($message) use ($user, $code) {
            $message->to($user->email)
                ->subject('Your new Homebound verification code')
                ->html("
                    <div style='font-family:sans-serif;max-width:480px;margin:0 auto;padding:32px;'>
                        <h2 style='font-size:20px;color:#1a1a18;margin-bottom:8px;'>New verification code</h2>
                        <p style='color:#666;font-size:14px;margin-bottom:24px;'>
                            Here's your new code. It expires in 15 minutes.
                        </p>
                        <div style='background:#F5F4F0;border-radius:12px;padding:24px;text-align:center;margin-bottom:24px;'>
                            <div style='font-size:36px;font-weight:700;letter-spacing:10px;color:#1D9E75;'>{$code}</div>
                        </div>
                        <p style='color:#aaa;font-size:12px;'>If you didn't request this, you can ignore this email.</p>
                    </div>
                ");
        });

        return back()->with('success', 'A new code has been sent to your email.');
    }
}
