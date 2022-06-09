<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\FollowResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function toggleFollow(User $user){
        $follow = $this->userRepository->toggleFollow($user);
        return FollowResource::make($follow);
    }

    public function getFollowers(User $user = null)
    {
        $collection = $this->userRepository->getFollowers($user);
        return UserResource::collection($collection);
    }

    public function getFollows(User $user = null)
    {
        $collection = $this->userRepository->getFollows($user);
        return UserResource::collection($collection);
    }
}
