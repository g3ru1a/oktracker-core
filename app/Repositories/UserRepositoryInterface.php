<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
   public function all(): Collection;
   public function find($user_id): User;

   public function updatePassword($user, $old_password, $new_password): ?User;
   
   public function updateName($user, $name): User;
   public function updateProfilePicture($user, $image_path): User;

   public function updateToken($user): User;
   public function confirmEmail($user, $crypted_email, $token): bool;

   public function updatedInfoResponse($user): array;

   
}