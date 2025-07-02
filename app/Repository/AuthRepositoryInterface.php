<?php
namespace App\Repository;

interface AuthRepositoryInterface
{
    public function createUser(array $data);
    public function verifyAccount($id, $token);
    public function resetPassword($user, $password);
}