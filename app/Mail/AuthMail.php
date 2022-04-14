<?php

namespace App\Mail;

use Mail;
use Crypt;
use Throwable;

class AuthMail implements AuthMailInterface{

    public function changeEmailConfirmation($new_email, $user): bool
    {
        try{
            $encrypted_mail = Crypt::encryptString($new_email);
            Mail::mailer()->to($new_email)->send(new EmailChangeVerify($user, $encrypted_mail));
            return true;
        }catch(Throwable $th){
            return false;
        }
    }
    public function registrationEmailConfirmation($user): bool
    {
        try {
            Mail::mailer()->to($user->email)->send(new ConfirmEmail($user));
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function forgotPassword($user): bool
    {
        try {
            Mail::mailer()->to($user->email)->send(new PasswordReset($user));
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }
    public function passwordChangedNotif($user): bool
    {
        try {
            Mail::mailer()->to($user->email)->send(new PasswordResetNotif($user));
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

}