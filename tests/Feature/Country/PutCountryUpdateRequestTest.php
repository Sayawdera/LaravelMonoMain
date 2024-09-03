<?php

namespace Tests\Feature\Country;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PutCountryUpdateRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Country_Put_request(): void
    {
        $response = $this->Put('/');

        $response->assertStatus(200);
    }
}
