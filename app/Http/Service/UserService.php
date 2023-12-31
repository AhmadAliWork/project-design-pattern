<?php

namespace App\Http\Service;

use App\Http\Repositories\UserRepository;
use App\Models\User;

class UserService extends BaseService
{
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function createUserWithRole(array $userData, string $roleName): User
    {
        $user = $this->create($userData);
        // Assign the role to the user
        $user->assignRole($roleName);

        return $user;
    }

    /**
     * Update a user's information and roles.
     */
    public function updateUserWithRole(User $user, array $userData, ?string $roleName): User
    {
        // Update the user's information
        $user->update($userData);

        // If a role name is provided, update the user's roles
        if ($roleName !== null) {
            $user->roles()->detach();
            $user->assignRole($roleName);
        }

        return $user;
    }
}
