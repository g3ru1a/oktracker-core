<?php

namespace Tests\Feature\Api\V2;

use App\Mail\EmailChangeVerify;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mail;
use Storage;
use Tests\TestCase;

class UpdateUserDataTest extends TestCase
{
    use RefreshDatabase;

    private $token = null;
    private $user = null;
    private $encrypted_mail = null;
    private $routes = [];

    function setUp(): void
    {
        parent::setUp();

        $ver = 'v2.';
        $this->routes = [
            'login' => route($ver . 'auth.login'),
            'user-info' => route($ver . 'users.info'),
            'change-password' => route($ver . 'users.change-password'),
            'change-email' => route($ver . 'users.change-email'),
            'change-info' => route($ver . 'users.change-info'),
            'x-verify-email' => $ver . 'auth.verify-email',
        ];

        $user = User::factory()->create();
        $data = $this->authenticate($user);
        $this->token = $data['token'];
        $this->user = $data['user'];
    }

    private function authenticate($user)
    {
        $response = $this->post($this->routes['login'], [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $r = $response->decodeResponseJson();
        return $r;
    }
    /**
     * Check if user info can be requested
     *
     * @return void
     */
    public function test_get_user_info()
    {
        $response = $this->get($this->routes['user-info'], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(200);
        $response->assertJsonPath('data.email', $this->user['email']);
    }

    /**
     * Check if user info can be requested by unauthenticated request
     *
     * @return void
     */
    public function test_get_user_info_unauthenticated()
    {
        $response = $this->get($this->routes['user-info'], ['Accept' => 'application/json']);
        $response->assertStatus(401);
    }

    /**
     * Check if user can change passwords
     *
     * @return void
     */
    public function test_change_password()
    {
        //Change password check
        $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $this->token];
        $data = [
            'old_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];
        $response = $this->post($this->routes['change-password'], $data, $headers);
        $response->assertStatus(200);
        $response->assertJsonPath('user.id', $this->user['id']);

        //Check if user can login with new password
        $response = $this->post($this->routes['login'], [
            'email' => $this->user['email'],
            'password' => 'new_password'
        ]);
        $response->assertStatus(200);
    }


    /**
     * Check if user can change passwords with wrong old password
     *
     * @return void
     */
    public function test_change_password_wrong_password()
    {
        //Change password check
        $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $this->token];
        $data = [
            'old_password' => 'asd_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];
        $response = $this->post($this->routes['change-password'], $data, $headers);
        $response->assertStatus(401);
    }

    /**
     * Check if user can change emails
     *
     * @return array
     */
    public function test_change_email(): array
    {
        $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $this->token];
        $data = [
            'email' => 'newemail@example.com',
        ];

        Mail::fake();

        $response = $this->post($this->routes['change-email'], $data, $headers);
        $response->assertStatus(200);

        $thisObj = $this;
        Mail::assertSent(EmailChangeVerify::class, function ($mail) use ($thisObj) {
            $thisObj->encrypted_mail = $mail->encrypted_email;
            return $mail->hasTo('newemail@example.com');
        });

        $this->assertNotNull($thisObj->encrypted_mail);
        return [$this->user['id'], $thisObj->encrypted_mail, User::find($this->user['id'])->remember_token];
    }

    /**
     * Check if user can change emails
     *
     * @return void
     */
    public function test_change_email_confirm()
    {
        $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $this->token];
        $data = [
            'email' => 'newemail@example.com',
        ];

        Mail::fake();

        $response = $this->post($this->routes['change-email'], $data, $headers);
        $response->assertStatus(200);

        $thisObj = $this;
        Mail::assertSent(EmailChangeVerify::class, function ($mail) use ($thisObj) {
            $thisObj->encrypted_mail = $mail->encrypted_email;
            return $mail->hasTo('newemail@example.com');
        });

        $headers = ['Accept' => 'application/json'];
        $params = [
            'user' => $this->user['id'],
            'email_crypted' => $thisObj->encrypted_mail,
            'token' => User::find($this->user['id'])->remember_token
        ];

        $route = route($this->routes['x-verify-email'], $params);
        $response = $this->get($route, $headers);
        
        $response->assertStatus(200);
        $this->assertEquals('newemail@example.com', User::find($this->user['id'])->email);
    }

    /**
     * Check if user can change emails with wrong data
     *
     * @return void
     */
    public function test_change_email_confirm_wrong_data()
    {
        $headers = ['Accept' => 'application/json'];
        $params = [
            'user' => $this->user['id'],
            'email_crypted' => 'asd', 
            'token' => 'asss'
        ];
        $route = route($this->routes['x-verify-email'], $params);
        $response = $this->get($route, $headers);
        $response->assertStatus(422);
    }

    /**
     * Check if user can change information
     *
     * @return void
     */
    public function test_change_info()
    {
        Storage::fake();
        $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $this->token];
        $data = [
            'name' => 'Yamada Taro',
            'profile' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->json('POST', $this->routes['change-info'], $data, $headers);
        $response->assertStatus(200);
        Storage::assertExists(User::find($this->user['id'])->profile_photo_path);
    }

    /**
     * Check if user can change information unauthenticated
     *
     * @return void
     */
    public function test_change_info_unauthenticated()
    {
        $headers = ['Accept' => 'application/json'];
        $data = [
            'name' => 'Yamada Taro',
        ];

        $response = $this->json('POST', $this->routes['change-info'], $data, $headers);
        $response->assertStatus(401);
    }
}
