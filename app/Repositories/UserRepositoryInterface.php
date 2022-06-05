<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
   public function all(): Collection;
   public function find($user_id): User;
   
   /**
    * @return User|null
    */
   public function findByEmail($email): User;
   public function create(array $attributes): User;

   public function updatePassword($user, $new_password): ?User;
   
   public function updateName($user, $name): User;
   public function updateProfilePicture($user, $image_path): User;

   public function updateToken($user, $token = null): User;
   public function confirmEmail($user, $token, $ence = null): bool;

   public function updatedInfoResponse($user): array;

   
}