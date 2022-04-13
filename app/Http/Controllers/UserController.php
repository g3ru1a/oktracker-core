<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Http\Resources\UserResource;
use App\Mail\EmailChangeVerify;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Auth;
use Crypt;
use Exception;
use Mail;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository; 

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Find a user and return its resource
     * 
     * @param Integer|NULL $user_id 
     * @return UserResource
     */
    public function find($user_id = null): UserResource
    {
        $user = $this->userRepository->find($user_id);
        return UserResource::make($user);
    }

    /**
     * Change authenticated user's password
     * 
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->updatePassword(Auth::user(), $request->old_password, $request->password);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
        return self::tokenResponse($user);
    }

    /**
     * Send confirmation email to change authenticated user's email
     * 
     * @param ChangeEmailRequest $request
     * @return JsonResponse
     */
    public function changeEmail(ChangeEmailRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->updateToken(Auth::user(), $request->email);

            $encrypted_mail = Crypt::encryptString($request->email);
            Mail::mailer()->to($request->email)->send(new EmailChangeVerify($user, $encrypted_mail));
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 422);
        }
        return response()->json(["EMAIL_SENT"]);
    }

    /**
     * Confirm email change for the specified user.
     * 
     * @param User $user
     * @param string $email_crypted
     * @param string $token
     * @return JsonResponse
     */
    public function confirmEmail(User $user, $email_crypted, $token): JsonResponse
    {
        try {
            $user = $this->userRepository->confirmEmail($user, $email_crypted, $token);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 422);
        }
        return response()->json("Email Confirmed.");
    }

    /**
     * Update authenticated user's name and optionally Profile Picture
     * 
     * @param UpdateUserInfoRequest $request
     * 
     * @return JsonResponse
     */
    public function updateInfo(UpdateUserInfoRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user = $this->userRepository->updateName($user, $request->name);
        if ($request->hasFile('profile')) {
            $path = ImageUploadController::uploadUserProfilePicture($user, $request->file('profile'));
            $user = $this->userRepository->updateProfilePicture($user, $path);
        }
        return self::tokenResponse($user);
    }

    /**
     * Generate a new auth token and format the JsonResponse
     * 
     * @param User $user
     * @return JsonResponse
     */
    private function tokenResponse($user): JsonResponse
    {
        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($response);
    }
}
