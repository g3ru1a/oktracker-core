<?php

namespace Tests\Feature\Api\V1;

use App\Models\BookVendor;
use App\Models\User;
use Facade\Ignition\Support\FakeComposer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    private $routes = [];
    private $user = null;
    private $otherUser = null;
    private $authVersion = "v1";
    private $jsonHeaders = ['Accept' => 'application/json'];

    function setUp(): void
    {
        parent::setUp();
        $ver = 'v1.';
        $this->routes = [
            'all' => route($ver . 'vendors.all'),
            'bulk' => route($ver . 'vendors.bulk'),
            'suggest' => route($ver . 'vendors.suggest'),
            'private-all' => route($ver . 'vendors.private-all'),
            'private-create' => route($ver . 'vendors.private-create'),
            'x-private-update' => $ver . 'vendors.private-update',
            'x-private-destroy' => $ver . 'vendors.private-destroy',
        ];
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
        BookVendor::factory()->count(3)->create();
        $bv = BookVendor::factory()->create();
        $bv->public = false;
        $bv->user_id = $this->user->id;
        $bv->save();
        $bv2 = BookVendor::factory()->create();
        $bv2->public = false;
        $bv2->user_id = $this->otherUser->id;
        $bv2->save();
    }
    /**
     * Get all vendors
     *
     * @return void
     */
    public function test_get()
    {
        $response = $this->get($this->routes['all']);
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * Get a bulk of vendors
     *
     * @return void
     */
    public function test_get_bulk()
    {
        $data = [
            "vendor_ids" => json_encode(BookVendor::all()->pluck('id')->toArray()),
        ];
        $response = $this->authAs($this->user, $this->authVersion)->json("POST", $this->routes['bulk'], $data);
        $response->assertStatus(200);
        $response->assertJsonCount(5, "data");
    }

    /**
     * Get a bulk of vendors
     *
     * @return void
     */
    public function test_get_private()
    {
        $response = $this->authAs($this->user, $this->authVersion)->get($this->routes['private-all']);
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");
    }

    /**
     * Suggest a vendor
     *
     * @return void
     */
    public function test_suggest()
    {
        $data = [
            "name" => "TestCompany",
        ];
        $response = $this->authAs($this->user, $this->authVersion)->json("POST", $this->routes['suggest'], $data);
        $response->assertStatus(201);

        $response = $this->authAs($this->user, $this->authVersion)->json("POST", $this->routes['suggest'], []);
        $response->assertStatus(422);
    }

    /**
     * Create private vendor
     *
     * @return void
     */
    public function test_create_private()
    {
        $data = [
            "name" => "Private Vendor",
        ];

        $response = $this->authAs($this->user, $this->authVersion)->json("POST", $this->routes['private-create'], $data);
        $response->assertStatus(201);

        $response = $this->authAs($this->user, $this->authVersion)->json("POST", $this->routes['private-create'], []);
        $response->assertStatus(422);

    }

    /**
     * Update private vendor
     *
     * @return void
     */
    public function test_update_private()
    {
        $data = [
            "name" => "Private Vendor",
        ];
        $vendorId = BookVendor::where("user_id", $this->user->id)->first()->id;
        $route = route($this->routes['x-private-update'], ["vendor" => $vendorId]);
        $routeWrong = route($this->routes['x-private-update'], ["vendor" => '812798']);

        $response = $this->authAs($this->user, $this->authVersion)->json("PUT", $route, $data);
        $response->assertStatus(200);

        $response = $this->authAs($this->user, $this->authVersion)->json("PUT", $route, []);
        $response->assertStatus(422);

        $response = $this->authAs($this->user, $this->authVersion)->json("PUT", $routeWrong, $data);
        $response->assertStatus(404);
    }

    /**
     * Update private vendor unauthorised
     *
     * @return void
     */
    public function test_update_private_unauthorized()
    {
        $data = [
            "name" => "Private Vendor",
        ];
        $vendorId = BookVendor::where("user_id", $this->user->id)->first()->id;
        $route = route($this->routes['x-private-update'], ["vendor" => $vendorId]);

        $response = $this->authAs($this->otherUser, $this->authVersion)->json("PUT", $route, $data);
        $response->assertStatus(403);
    }

    /**
     * Destroy private vendor
     *
     * @return void
     */
    public function test_destroy_private()
    {
        $vendorId = BookVendor::where("user_id", $this->user->id)->first()->id;
        $route = route($this->routes['x-private-destroy'], ["vendor" => $vendorId]);
        $routeWrong = route($this->routes['x-private-destroy'], ["vendor" => '812798']);

        $response = $this->authAs($this->user, $this->authVersion)->json("DELETE", $route);
        $response->assertStatus(200);

        $response = $this->authAs($this->user, $this->authVersion)->json("DELETE", $routeWrong);
        $response->assertStatus(404);
    }

    /**
     * Destroy private vendor unauthorised
     *
     * @return void
     */
    public function test_destroy_private_unauthorized()
    {
        $vendorId = BookVendor::where("user_id", $this->user->id)->first()->id;
        $route = route($this->routes['x-private-destroy'], ["vendor" => $vendorId]);

        $response = $this->authAs($this->otherUser, $this->authVersion)->json("DELETE", $route);
        $response->assertStatus(403);
    }
}
