<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUserShowRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_User_Get_request(): void
    {
        $response = $this->Get('/');

        $response->assertStatus(200);
    }
}
