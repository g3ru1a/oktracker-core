<?php

namespace Tests\Feature\Api\V1;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectionsTest extends TestCase
{
    use RefreshDatabase;

    private $routes = [];
    private $user = null;
    private $otherUser = null;

    function setUp(): void
    {
        parent::setUp();
        $ver = 'v1.';
        $this->routes = [
            'list' => route($ver . 'collections.list'),
            'x-items' => $ver . 'collections.items',
            'x-find' => $ver . 'collections.find',
            'store' => route($ver . 'collections.store'),
            'x-update' => $ver . 'collections.update',
            'x-destroy' => $ver . 'collections.destroy',
        ];
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    /**
     * Test if the user can find a collection and edge cases
     *
     * @return void
     */
    public function test_find()
    {
        $collection = Collection::factory()->count(3)->create();
        $this->user->collections()->saveMany($collection);

        $routeList = $this->routes['list'];
        $routeFind = route($this->routes['x-find'], ['collection' => $this->user->collections->first()->id]);

        $response = $this->authAs($this->user, 'v1')->json("GET", $routeList);
        $response->assertStatus(200);

        $response = $this->authAs($this->user, 'v1')->json("GET", $routeFind);
        $response->assertStatus(200);
    }

    public function test_find_unauthorised()
    {
        $collection = Collection::factory()->count(3)->create();
        $this->user->collections()->saveMany($collection);

        $routeList = $this->routes['list'];
        $routeFind = route($this->routes['x-find'], ['collection' => $this->user->collections->first()->id]);

        $response = $this->json("GET", $routeList);
        $response->assertStatus(401);

        $response = $this->json("GET", $routeFind);
        $response->assertStatus(401);

        $response = $this->authAs($this->otherUser, 'v1')->json("GET", $routeFind);
        $response->assertStatus(401);
    }

    /**
     * Test if the user can find a collection and edge cases
     *
     * @return void
     */
    public function test_items()
    {
        $collection = Collection::factory()->count(3)->create();
        $this->user->collections()->saveMany($collection);

        $route = route($this->routes['x-items'], ['collection' => $this->user->collections->first()->id]);

        $response = $this->authAs($this->user, 'v1')->json("GET", $route);
        $response->assertStatus(200);
    }

    public function test_items_unauthorised()
    {
        $collection = Collection::factory()->count(3)->create();
        $this->user->collections()->saveMany($collection);

        $route = route($this->routes['x-items'], ['collection' => $this->user->collections->first()->id]);
        
        $response = $this->json("GET", $route);
        $response->assertStatus(401);

        $response = $this->authAs($this->otherUser, 'v1')->json("GET", $route);
        $response->assertStatus(401);
    }

    /**
     * Test if the user can create a collection and edge cases
     *
     * @return void
     */
    public function test_create()
    {
        $data = [
            'name' => 'TestCol',
            'total_books' => 0,
        ];
        $response = $this->authAs($this->user, "v1")->json("POST", $this->routes['store'], $data);
        $response->assertStatus(201);

        $response->assertJsonPath('data.currency', 'USD');

        $data = [
            'name' => 'TestCol',
            'total_books' => 0,
            'currency' => 'GBP'
        ];
        $response = $this->authAs($this->user, "v1")->json("POST", $this->routes['store'], $data);
        $response->assertStatus(201);

        $response->assertJsonPath('data.currency', 'GBP');

        $response = $this->authAs($this->user, "v1")->json("POST", $this->routes['store'], []);
        $response->assertStatus(422);
    }

    /**
     * Test if the user can update a collection and edge cases
     *
     * @return void
     */
    public function test_update()
    {
        $collection = Collection::factory()->create();
        $this->user->collections()->save($collection);

        $data = [
            'name' => 'TestCol2',
        ];

        $route = route($this->routes['x-update'], ['collection' => $collection->id]);
        $routeWrongResource = route($this->routes['x-update'], ['collection' => '6969669']);

        $response = $this->authAs($this->user, 'v1')->json("POST", $route, $data);
        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'TestCol2');

        $response = $this->authAs($this->user, 'v1')->json("POST", $route, []);
        $response->assertStatus(422);

        $response = $this->authAs($this->user, 'v1')->json("POST", $routeWrongResource, $data);
        $response->assertStatus(404);

       
    }

    public function test_update_unauthorised()
    {
        $collection = Collection::factory()->create();
        $this->user->collections()->save($collection);

        $data = [
            'name' => 'TestCol2',
        ];
        $route = route($this->routes['x-update'], ['collection' => $collection->id]);
        $response = $this->json("POST", $route, $data);
        $response->assertStatus(401);
        $response = $this->authAs($this->otherUser, 'v1')->json("POST", $route, $data);
        $response->assertStatus(401);
    }

    /**
     * Test if the user can update a collection and edge cases
     *
     * @return void
     */
    public function test_delete()
    {
        $collection = Collection::factory()->create();
        $this->user->collections()->save($collection);

        $route = route($this->routes['x-destroy'], ['collection' => $collection->id]);
        $routeWrongResource = route($this->routes['x-destroy'], ['collection' => '6969669']);

        $response = $this->authAs($this->user, 'v1')->json("POST", $route, []);
        $response->assertStatus(200);

        $response = $this->authAs($this->user, 'v1')->json("POST", $routeWrongResource, []);
        $response->assertStatus(404);
    }

    public function test_delete_unauthorised()
    {
        $collection = Collection::factory()->create();
        $this->user->collections()->save($collection);

        $data = [
            'name' => 'TestCol2',
        ];
        $route = route($this->routes['x-destroy'], ['collection' => $collection->id]);
        $response = $this->json("POST", $route, $data);
        $response->assertStatus(401);
        $response = $this->authAs($this->otherUser, 'v1')->json("POST", $route, $data);
        $response->assertStatus(401);
    }
}
