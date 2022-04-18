<?php

namespace Tests\Feature\Api\V2;

use App\Mail\ConfirmEmail;
use App\Mail\PasswordReset;
use App\Mail\PasswordResetNotif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private $user = null;
    private $headers = ['Accept' => 'application/json'];
    private $routes = [];

    function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $ver = 'v2.';
        $this->routes = [
            'register' => route($ver.'auth.register'),
            'login' => route($ver.'auth.login'),
            'logout' => route($ver.'users.logout'),
            'forgot_password' => route($ver . 'auth.forgot-password'),
            'reset_password' => route($ver . 'auth.reset-password'),
        ];
    }

    /**
     * Test if users can register.
     *
     * @return void
     */
    public function test_register()
    {
        $data = [
            'name' => 'test',
            'email' => 'some@email.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ];

        Mail::fake();

        $response = $this->post($this->routes['register'], $data, $this->headers);
        $response->assertStatus(201);

        Mail::assertSent(ConfirmEmail::class, function ($mail) {
            return $mail->hasTo('some@email.com');
        });

        $response = $this->post($this->routes['register'], [], $this->headers);
        $response->assertStatus(422);
    }

    /**
     * Test if users can login.
     *
     * @return void
     */
    public function test_login()
    {
        $data = [
            'email' => $this->user['email'],
            'password' => 'password',
        ];

        $response = $this->post($this->routes['login'], [], $this->headers);
        $response->assertStatus(422);

        $response = $this->post($this->routes['login'], $data, $this->headers);
        $response->assertStatus(200);
        $response->assertJsonPath('user.id', $this->user['id']);
    }

    /**
     * Test if users can logout.
     *
     * @return void
     */
    public function test_logout(){
        $data = [
            'email' => $this->user['email'],
            'password' => 'password',
        ];
        $response = $this->post($this->routes['login'], $data, $this->headers);
        $token = $response->decodeResponseJson()['token'];

        $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token];
        $response = $this->post($this->routes['logout'], [], $headers);
        $response->assertStatus(200);
    }

    /**
     * Test if users can logout.
     *
     * @return void
     */
    public function test_forgot_password()
    {
        $data = [
            'email' => $this->user['email'],
        ];
        Mail::fake();
        $response = $this->post($this->routes['forgot_password'], $data, $this->headers);
        $response->assertStatus(200);

        $userEmail = $this->user['email'];
        Mail::assertSent(PasswordReset::class, function ($mail) use ($userEmail) {
            return $mail->hasTo($userEmail);
        });

        $user = User::find($this->user['id']);
        $data = [
            'email' => $user->email,
            'token' => $user->remember_token,
            'password' => '12345',
            'password_confirmation' => '12345',
        ];
        $response = $this->post($this->routes['reset_password'], $data, $this->headers);
        $response->assertStatus(200);

        Mail::assertSent(PasswordResetNotif::class, function ($mail) use ($userEmail) {
            return $mail->hasTo($userEmail);
        });

        $data = [
            'email' => $this->user['email'],
            'password' => '12345',
        ];

        $response = $this->post($this->routes['login'], $data, $this->headers);
        $response->assertStatus(200);
        $response->assertJsonPath('user.id', $this->user['id']);
    }
    
}
