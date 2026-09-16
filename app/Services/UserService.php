<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepo) {}

    public function getAllUsers()
    {
        return $this->userRepo->getAll();
    }

    public function getUserById(int $id)
    {
        return $this->userRepo->findById($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepo->create($data);
    }

    public function updateUser(int $id, array $data)
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepo->update($id, $data);
    }

    public function deleteUser(int $id)
    {
        return $this->userRepo->delete($id);
    }
}
