<?php
namespace App\Services\Interfaces;

use App\User;

Interface iUser
{
    public function createUser($request): User;

    public function updateUser(User $user, $request): User;

    public function deleteUser(User $user);
}
