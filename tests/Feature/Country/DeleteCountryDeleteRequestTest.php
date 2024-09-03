<?php

namespace Tests\Feature\Country;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCountryDeleteRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Country_Delete_request(): void
    {
        $response = $this->Delete('/');

        $response->assertStatus(200);
    }
}
