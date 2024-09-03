<?php

namespace Tests\Feature\Country;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetCountryShowRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Country_Get_request(): void
    {
        $response = $this->Get('/');

        $response->assertStatus(200);
    }
}
