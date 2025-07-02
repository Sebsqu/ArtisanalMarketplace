<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\User;

class AuthNotificationService
{
    public function registerUser($user, $token)
    {
        Mail::raw(
            'Aby aktywować konto kliknij w poniższy link: ' . url('/verify-account/' . $user->id . '/' . $token),
            function($message) use ($user){
                $message->to($user->email)->subject('Aktywacja konta na rynku rzemieślniczym');
            }
        );
    }
}