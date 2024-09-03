<?php

namespace Tests\Feature\Addresses;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAddressesShowRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Addresses_Get_request(): void
    {
        $response = $this->Get('/');

        $response->assertStatus(200);
    }
}
