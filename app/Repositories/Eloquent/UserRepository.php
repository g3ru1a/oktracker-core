<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Auth;
use Carbon\Carbon;
use Crypt;
use Exception;
use Hash;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param integer $user_id
     * @return User
     */
    public function find($user_id): User
    {
        if ($user_id == null) $user = Auth::user();
        else{
            $user = User::findOrFail($user_id);
        }
        return $user;
    }

    /**
     * @param User $user
     * @param string $old_password
     * @param string $new_password
     * @return User
     */
    public function updatePassword($user, $old_password, $new_password): User
    {
        if (!$user || !Hash::check($old_password, $user->password)) {
            throw new Exception("Password Does Not Match");
        }
        $user->password = bcrypt($new_password);
        $user->save();
        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function updateToken($user): User
    {
        $user->remember_token = bin2hex(random_bytes(20));
        $user->save();
        return $user;
    }

    /**
     * @param User $user
     * @param string $crypted_email
     * @param string $token
     * @return User
     */
    public function confirmEmail($user, $crypted_email, $token): User
    {
        if ($user->remember_token == $token) {
            $user->email_verified_at = Carbon::now();
            $user->email = Crypt::decryptString($crypted_email);
            $user->remember_token = null;
            $user->save();
        }else{
            throw new Exception("Token's do not match.");
        }
        return $user;
    }

    /**
     * @param User $user
     * @param string $name
     * @return User
     */
    public function updateName($user, $name): User
    {
        $user->name = $name;
        $user->save();
        return $user;
    }

    /**
     * @param User $user
     * @param string $image_path
     * @return User
     */
    public function updateProfilePicture($user, $image_path): User
    {
        $user->profile_photo_path = $image_path;
        $user->save();
        return $user;
    }

    /**
     * @param User $user
     * @return array
     */
    public function updatedInfoResponse($user): array
    {
        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
