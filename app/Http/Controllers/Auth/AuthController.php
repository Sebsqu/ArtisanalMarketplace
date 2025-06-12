<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm (){
        return view('auth.register');
    }

    public function createUser(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:10|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verification_token' => Str::random(64),
        ]);

        Mail::raw(
            'Kliknij, aby aktywować konto: ' . url('/verify-email/' . $user->email_verification_token),
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Aktywacja konta');
            }
        );
        
        return redirect()->route('registerForm');
    }

    public function loginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:10',
        ]);

        if (auth()->attempt($credentials)) {
            if (is_null(auth()->user()->email_verified_at)) {
                auth()->logout();
                return back()->withErrors(['email' => 'Najpierw potwierdź adres e-mail.']);
            } else{
                session([
                    'user_id' => auth()->user()->id,
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                ]);
                return redirect('/');
            }
        } else {
            return back()->withErrors(['email' => 'Nieprawidłowy email lub hasło.']);
        }
    }

    public function verifyEmail($token){
        $user = User::where('email_verification_token', $token)->firstOrFail();
        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return redirect('/loginForm')->with('status', 'Konto zostało potwierdzone. Możesz się zalogować.');
    }

    public function logout(){
        auth()->logout();
        session()->flush();
        return redirect('/');
    }

    public function forgotPasswordForm() {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        return back()->with('status', __($status));
    }

    public function resetPasswordForm($token) {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:10|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('loginForm')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

}
