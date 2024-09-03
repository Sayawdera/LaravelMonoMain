<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostUserStoreRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_User_Post_request(): void
    {
        $response = $this->Post('/');

        $response->assertStatus(200);
    }
}
