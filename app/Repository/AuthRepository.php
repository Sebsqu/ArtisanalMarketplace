<?php
namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verification_token' => $data['email_verification_token']
        ]);
        return $user;
    }

    public function verifyAccount($id, $token)
    {
        $user = User::findOrFail($id);
        if (Hash::check($token, $user->email_verification_token)) {
            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->save();
            return true;
        }
        return false;
    }

    public function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }
}