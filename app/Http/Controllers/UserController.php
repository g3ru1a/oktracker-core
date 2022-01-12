<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangeVerify;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Crypt;
use Hash;
use Illuminate\Http\Request;
use Mail;

class UserController extends Controller
{
    public function changePassword(Request $request){
        $fields = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $user = Auth::user();

        if (!$user || !Hash::check($fields['old_password'], $user->password)) {
            return response(['message' => 'Bad Credentials.'], 401);
        }

        $user->password = bcrypt($fields['password']);
        $user->save();

        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function changeEmail(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
        ]);
        $user = Auth::user();
        $encrypted_mail = Crypt::encryptString($fields['email']);

        try {
            $user->remember_token = bin2hex(random_bytes(20));
            $user->save();

            Mail::mailer()->to($fields['email'])->send(new EmailChangeVerify($user, $encrypted_mail));
        } catch (\Throwable $th) {
            return response()->json("Could not send email", 422);
        }

        return response()->json(["message"=>"Sent verification email."]);
    }

    public function changeEmailConfirm(User $user, $email_crypted, $token){
        try {
            if ($user->remember_token == $token) {
                $user->email_verified_at = Carbon::now();
                $user->email = Crypt::decryptString($email_crypted);
                $user->save();
                return response()->json("Email Changed Successfully!");
            }
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json("Bad Request", 422);
        }

    }

    //Update image, name and other info
    public function update(Request $request){

        $fields = $request->validate([
            'name' => 'string',
            'profile' => 'image'
        ]);

        $user = Auth::user();
        if ($request->hasFile('profile')) {
            $originalExtension = $request->file('profile')->getClientOriginalExtension();

            if (file_exists(public_path() . 'profile/' . $user->id . '/profile' . $originalExtension)) {
                unlink(public_path() . 'profile/' . $user->id . '/profile' . $originalExtension);
            }

            $filename = 'profile' . $originalExtension;
            $path = $request->file('profile')->storeAs('public/profile/' . $user->id, $filename);
            $user->profile_photo_path = '/' . str_replace('public', 'storage', $path);
            $user->save();
        }
        if(isset($fields['name'])){
            $user->name = $fields['name'];
            $user->save();
        }
        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
}
