<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view('auth.register');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:10|confirmed'
        ]);

        $token = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verification_token' => bcrypt($token)
        ]);

        Mail::raw(
            'Aby aktywować konto kliknij w poniższy link: ' . url('/verify-account/' . $user->id . '/' . $token),
            function($message) use ($user){
                $message->to($user->email)->subject('Aktywacja konta na rynku rzemieślniczym');
            }
        );

        return redirect()->route('registerForm')->with('status', 'Aktywuj konto linkiem wysłanym na podany adres email');
    }

    public function verifyAccount($id, $token)
    {
        $user = User::findOrFail($id);
        if(Hash::check($token, $user->email_verification_token)){
            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->save();

            return redirect('/loginForm')->with('status', 'Konto zostało potwierdzone. Możesz się zalogować.');
        } else {
            return redirect('/loginForm')->withErrors(['email' => 'Nieprawidłowy token aktywacyjny.']);
        }
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:10'
        ]);

        if (auth()->attempt($credentials)) {
            if (is_null(auth()->user()->email_verified_at)) {
                auth()->logout();
                return back()->withErrors(['email' => 'Najpierw potwierdź adres e-mail.']);
            } else{
                $cart = Cart::where('user_id', auth()->user()->id)->first();
                session()->put([
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'user_id' => auth()->user()->id,
                    'cart' => $cart ? json_decode($cart->cart_items, true) : []
                ]);
                
                if(!empty($cart)){
                    $cart->delete();
                }
                
                return redirect('/');
            }
        } else {
            return back()->withErrors(['email' => 'Nieprawidłowy email lub hasło.']);
        }
    }

    public function logout()
    {
        if(!empty(session('cart'))){
            $cart = new Cart();
            $cart->user_id = session('user_id');
            $cart->cart_items = json_encode(session('cart'));
            $cart->save();
        }
        auth()->logout();
        session()->flush();
        return redirect('/');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
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
