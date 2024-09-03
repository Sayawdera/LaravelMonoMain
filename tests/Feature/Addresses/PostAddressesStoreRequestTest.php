<?php

namespace Tests\Feature\Addresses;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostAddressesStoreRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Addresses_Post_request(): void
    {
        $response = $this->Post('/');

        $response->assertStatus(200);
    }
}
