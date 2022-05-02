<?php

namespace Tests\Feature\Api\V2;

use App\Models\Book;
use App\Models\BookVendor;
use App\Models\Collection;
use App\Models\Item;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    private $routes = [];
    private $authVersion = "v2";
    private $jsonHeaders = ['Accept' => 'application/json'];

    function setUp(): void
    {
        parent::setUp();
        $ver = 'v2.';
        $this->routes = [
            'store' => route($ver . 'items.create'),
            'x-find' => $ver . 'items.find',
            'x-update' => $ver . 'items.update',
            'x-destroy' => $ver . 'items.destroy',
            'x-collection' => $ver . 'collections.items',
        ];
        Collection::factory()->count(2)->for(User::factory(), 'user')->create();
        Collection::factory()->count(2)->for(User::factory(), 'user')->create();
        Series::factory()->has(Book::factory()->count(3), 'books')->count(5)->create();
        BookVendor::factory()->count(4)->create();
        Item::create([
            'book_id' => Book::first()->id,
            'collection_id' => Collection::first()->id,
            'vendor_id' => BookVendor::first()->id,
            'price' => 69,
            'bought_on' => '10-10-2010',
        ]);
    }

    private function route($route, array $attrs = null)
    {
        if ($this->routes[$route] == null) {
            $this->assertTrue(false, "Route not defined.");
            return null;
        }
        if (strstr($route, 'x-')) {
            return route($this->routes[$route], $attrs);
        } else return $this->routes[$route];
    }

    /**
     * @return void
     */
    public function test_find()
    {
        $response = $this->authAs(User::first(), $this->authVersion)
            ->json("GET", $this->route("x-find", ['item' => Item::first()->id]));
        $response->assertStatus(200);

        $response = $this->authAs(User::first(), $this->authVersion)
            ->json("GET", $this->route("x-collection", ['collection' => Collection::first()->id]));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_find_unauthorised()
    {
        $response = $this->authAs(User::get()->last(), $this->authVersion)
            ->json("GET", $this->route("x-find", ['item' => Item::first()->id]));
        $response->assertStatus(403);
        $response = $this->authAs(User::get()->last(), $this->authVersion)
            ->json("GET", $this->route("x-collection", ['collection' => Collection::first()->id]));
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_store()
    {
        $data = [
            'book_id' => Book::first()->id,
            'collection_id' => Collection::first()->id,
            'vendor_id' => BookVendor::first()->id,
            'price' => 69,
            'bought_on' => '10-10-2010',
        ];
        $wrongData = $data;
        $wrongData['vendor_id'] = null;
        $response = $this->authAs(User::first(), $this->authVersion)
            ->json("POST", $this->route("store"), $data);

        $response->assertStatus(201);

        $response = $this->authAs(User::first(), $this->authVersion)
            ->json("POST", $this->route("store"), $wrongData);

        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_store_bad_auth()
    {
        $data = [
            'book_id' => Book::first()->id,
            'collection_id' => Collection::first()->id,
            'vendor_id' => BookVendor::first()->id,
            'price' => 69,
            'bought_on' => '10-10-2010',
        ];
        $response = $this->authAs(User::get()->last(), $this->authVersion)
            ->json("POST", $this->route("store"), $data);

        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_update()
    {
        $data = [
            'collection_id' => Collection::where('user_id', User::first()->id)->get()->last()->id,
            'vendor_id' => BookVendor::get()->last()->id,
            'price' => 12,
            'bought_on' => '10-10-2020',
        ];
        $wrongData = $data;
        $wrongData['vendor_id'] = null;
        $response = $this->authAs(User::first(), $this->authVersion)
            ->json("PUT", $this->route("x-update", ["item" => Item::first()->id]), $data);

        $response->assertStatus(200);

        $response = $this->authAs(User::first(), $this->authVersion)
            ->json("PUT", $this->route("x-update", ["item" => Item::first()->id]), $wrongData);

        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_update_bad_auth()
    {
        $data = [
            'collection_id' => Collection::where('user_id', User::first()->id)->get()->last()->id,
            'vendor_id' => BookVendor::get()->last()->id,
            'price' => 12,
            'bought_on' => '10-10-2020',
        ];
        $response = $this->authAs(User::get()->last(), $this->authVersion)
            ->json("PUT", $this->route("x-update", ["item" => Item::first()->id]), $data);

        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_destroy()
    {
        $response = $this->authAs(User::first(), $this->authVersion)
            ->json("DELETE", $this->route("x-destroy", ["item" => Item::first()->id]));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_destroy_bad_auth()
    {
        $response = $this->authAs(User::get()->last(), $this->authVersion)
            ->json("DELETE", $this->route("x-destroy", ["item" => Item::first()->id]));

        $response->assertStatus(403);
    }
}
