<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmEmail;
use App\Mail\PasswordReset;
use App\Mail\PasswordResetNotif;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Mail;
use Jenssegers\Agent\Agent;


class ApiAuthController extends Controller
{

    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $email_token = bin2hex(random_bytes(20));
        
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'remember_token' => $email_token,
        ]);

        $response = [
            'user' => $user,
        ];

        Mail::mailer()->to($user->email)->send(new ConfirmEmail($user));

        return response($response, 201);
    }

    public function verifyEmail(User $user, $token){
        if($user->remember_token == $token){
            $user->email_verified_at = Carbon::now();
            $user->remember_token = null;
            $user->save();
            return redirect(route('open.app.login'));
        }else return response()->json("Invalid Token", 422);
    }

    public function resetPasswordRequest(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
        ]);
        $user = User::where("email", $fields['email'])->first();
        if($user){
            $email_token = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->remember_token = $email_token;
            $user->save();
            Mail::mailer()->to($user->email)->send(new PasswordReset($user));
            return response()->json(["message" => "reset password email sent"], 200);
        }else{
            return response()->json("Invalid Request", 422);
        }
    }

    public function resetPassword(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);
        $user = User::where("email", $fields['email'])->first();
        if ($user) {
            if($user->remember_token == $fields['token']){
                $user->password = bcrypt($fields['password']);
                $user->remember_token = null;
                $user->save();
                Mail::mailer()->to($user->email)->send(new PasswordResetNotif($user));
                return response()->json(["message" => "password reset successfully"], 200);
            }
            return response()->json("Token doesn't match", 422);
        } else {
            return response()->json("Invalid Request", 422);
        }
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response(['message' => 'Bad Credentials.'], 401);
        }

        if($user->email_verified_at == null){
            return response(["errors" => ["email" => ["Email hasn't been verified."]]], 422);
        }

        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response(['message' => 'Logged out'], 200);
    }
}
