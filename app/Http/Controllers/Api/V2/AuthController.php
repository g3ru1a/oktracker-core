<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiForgotPasswordRequest;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiRegisterRequest;
use App\Http\Requests\ApiResetPasswordRequest;
use App\Mail\AuthMailInterface;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private AuthMailInterface $authMail;

    public function __construct(UserRepositoryInterface $userRepository, AuthMailInterface $authMail)
    {
        $this->userRepository = $userRepository;
        $this->authMail = $authMail;
    }

    public function register(ApiRegisterRequest $request){        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'remember_token' => bin2hex(random_bytes(20))
        ];

        $user = $this->userRepository->create($data);
        $sent = $this->authMail->registrationEmailConfirmation($user);
        if(!$sent) return response()->json([], 500);
        return response()->json(['user' => $user], 201);
    }

    public function login(ApiLoginRequest $request)
    {
        $user = $this->userRepository->findByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Bad Credentials.'], 401);
        }

        if ($user->email_verified_at == null) {
            return response()->json(["errors" => ["email" => ["Email hasn't been verified."]]], 422);
        }

        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response(['message' => 'Logged out'], 200);
    }

    public function verifyEmail(User $user, $token){
        if($user->remember_token == $token){
            $user->email_verified_at = Carbon::now();
            $user->remember_token = null;
            $user->save();
            return redirect(route('open.app.login'));
        }else return response()->json("Invalid Token", 422);
    }

    public function forgotPassword(ApiForgotPasswordRequest $request){
        $user = $this->userRepository->findByEmail($request->email);
        if($user == null) return response()->json("Invalid Request", 422);

        $user = $this->userRepository->updateToken($user, str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
        $sent = $this->authMail->forgotPassword($user);
        
        if (!$sent) return response()->json([], 500);
        return response()->json(["message" => "EMAIL_SENT"], 200);
    }

    public function resetPassword(ApiResetPasswordRequest $request)
    {
        $user = $this->userRepository->findByEmail($request->email);
        if ($user == null) return response()->json(["message" => "Could not find user with given email"], 404);
        if ($user->remember_token != $request->token) return response()->json(["message" => "Token doesn't match"], 422);

        $user = $this->userRepository->updatePassword($user, $request->password);
        $sent = $this->authMail->passwordChangedNotif($user);

        if(!$sent) return response()->json([], 500);
        return response()->json(["message" => "Password Reset Successfully"], 200);
    }
}
