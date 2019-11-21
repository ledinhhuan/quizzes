<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepository;
use Illuminate\Validation\UnauthorizedException;

class UserService
{
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register User
     *
     * @param array $data
     * @return mixed
     */
    public function create ($data = [])
    {
        $user = $this->userRepository->create($data);
        return $user;
    }

    /**
     * Login User
     *
     * @param array $data
     * @return mixed
     */
    public function login (array $data)
    {
        $user = $this->userRepository->findWhere(['email' => $data['email']])->first();
        if ($user && app('hash')->check($data['password'], $user['password'])) {
            if ($user['status'] === User::ACTIVE) {
                return $user;
            }
        }
        throw new UnauthorizedException();
    }
}