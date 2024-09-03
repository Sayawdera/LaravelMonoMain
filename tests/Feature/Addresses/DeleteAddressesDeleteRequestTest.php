<?php

namespace Tests\Feature\Addresses;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteAddressesDeleteRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Addresses_Delete_request(): void
    {
        $response = $this->Delete('/');

        $response->assertStatus(200);
    }
}
