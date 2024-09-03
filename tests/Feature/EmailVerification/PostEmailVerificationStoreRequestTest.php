<?php

namespace Tests\Feature\EmailVerification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostEmailVerificationStoreRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_EmailVerification_Post_request(): void
    {
        $response = $this->Post('/');

        $response->assertStatus(200);
    }
}
