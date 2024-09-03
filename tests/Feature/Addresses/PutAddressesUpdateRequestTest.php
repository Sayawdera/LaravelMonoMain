<?php

namespace Tests\Feature\Addresses;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PutAddressesUpdateRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Addresses_Put_request(): void
    {
        $response = $this->Put('/');

        $response->assertStatus(200);
    }
}
