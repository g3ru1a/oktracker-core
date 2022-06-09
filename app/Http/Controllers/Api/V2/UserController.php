<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageUploadController;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResultResource;
use App\Mail\AuthMailInterface;
use App\Models\Collection;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Auth;
use Carbon\Carbon;
use Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private AuthMailInterface $authMail; 

    public function __construct(UserRepositoryInterface $userRepository, AuthMailInterface $authMail)
    {
        $this->userRepository = $userRepository;
        $this->authMail = $authMail;
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
     * Find a user and return its profile resource
     * 
     * @param Integer|NULL $user_id 
     * @return JSONResponse
     */
    public function profile($user): JSONResponse
    {
        $user = $this->userRepository->find($user);
        $current_user = Auth::user();

        $collections = Collection::where('user_id', $user->id)->get();
        $total_books = 0;
        foreach ($collections as $c) {
            $total_books += $c->total_books;
        }

        $now = Carbon::now("UTC");
        $acc_create = Carbon::createFromFormat("Y-m-d H:i:s", $user->created_at, "UTC");
        $days_diff = $acc_create->diffInDays($now) + 1;

        return response()->json([
            "data" => [
                "user" => UserResultResource::make($user),
                "followed" => $current_user->isFollowing($user),
                "total_books" => $total_books,
                "days_collecting" => $days_diff,
            ]
        ]);
    }

    /**
     * Change authenticated user's password
     * 
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return response()->json([], 401);
        }
        $user = $this->userRepository->updatePassword($user, $request->password);
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
        $user = $this->userRepository->updateToken(Auth::user(), bin2hex(random_bytes(20)));
        $sent = $this->authMail->changeEmailConfirmation($request->email, $user);
        if($sent) return response()->json(["EMAIL_SENT"]);
        else return response()->json([], 422);
    }

    /**
     * Confirm email change for the specified user.
     * 
     * @param User $user
     * @param string $email_crypted
     * @param string $token
     * @return JsonResponse
     */
    public function confirmEmail(User $user, $token): JsonResponse
    {
        $confirmed = $this->userRepository->confirmEmail($user, $token);
        if(!$confirmed) return response()->json([], 422);
        return response()->json("Email Confirmed.");
    }

    /**
     * Confirm email change for the specified user.
     * 
     * @param User $user
     * @param string $email_crypted
     * @param string $token
     * @return JsonResponse
     */
    public function confirmEmailChange(User $user, $email_crypted, $token): JsonResponse
    {
        $confirmed = $this->userRepository->confirmEmail($user, $token, $email_crypted);
        if (!$confirmed) return response()->json([], 422);
        return response()->json("Email Confirmed.");
    }

    /**
     * Update authenticated user's name and optionally Profile Picture
     * 
     * @param UpdateUserInfoRequest $request
     * 
     * @return JsonResponse
     */
    public function changeInfo(UpdateUserInfoRequest $request): JsonResponse
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
     * Update authenticated user's name and optionally Profile Picture
     * 
     * @param string $query
     * @param string $page Default 1
     * @param string $count Default 20
     * 
     * @return JsonResponse
     */
    public function search($query, $page = 1, $count = 20)
    {
        $users = $this->userRepository->search($query, $page, $count);
        $max_pages = $this->userRepository->searchMaxPages($query, $page, $count);

        $pagination_result = [
            "max_pages" => $max_pages,
            "prev_page" => ($page > 1) ? $page - 1 : null,
            "next_page" => ($page + 1 <= $max_pages) ? $page + 1 : null,
        ];
        return response()->json([
            "data" => [
                "users" => UserResultResource::collection($users),
                "pagination" => $pagination_result
            ]
        ]);
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
