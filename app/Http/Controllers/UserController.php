<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function changePassword(Request $request){
        $fields = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);
    }

    public function changeEmail(Request $request)
    {
        $fields = $request->validate([
            'old_email' => 'required|string',
            'email' => 'required|string|unique:users,email',
        ]);

    }

    public function changeEmailConfirm($token){

    }

    //Update image, name and other info
    public function update(){

    }
}
