<?php

namespace App\Mail;

interface AuthMailInterface {
    
    public function changeEmailConfirmation($new_email, $user): bool;
    public function registrationEmailConfirmation($user): bool;
    public function forgotPassword($user): bool;
    public function passwordChangedNotif($user): bool;

}