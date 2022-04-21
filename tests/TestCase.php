<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function loginV1($email, $password)
    {
        $response = $this->post(route('v1.auth.login'), [
            'email' => $email,
            'password' => $password
        ]);
        $response->assertStatus(200);
        $r = $response->decodeResponseJson();
        return [
            'token' => $r['token'],
            'user' => $r['user']
        ];
    }

    private function loginV2($email, $password)
    {
        $response = $this->post(route('v2.auth.login'), [
            'email' => $email,
            'password' => $password
        ]);
        $response->assertStatus(200);
        $r = $response->decodeResponseJson();
        return [
            'token' => $r['token'],
            'user' => $r['user']
        ];
    }

    public function authAs(User $user, $version = "v1"){
        if($version == "v1"){
            $data = $this->loginV1($user->email, 'password');
        }else if ($version == "v2") {
            $data = $this->loginV2($user->email, 'password');
        }
        $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $data['token']);
        return $this;
    }
}
