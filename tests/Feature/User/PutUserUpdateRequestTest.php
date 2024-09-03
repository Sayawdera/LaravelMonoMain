<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PutUserUpdateRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_User_Put_request(): void
    {
        $response = $this->Put('/');

        $response->assertStatus(200);
    }
}
