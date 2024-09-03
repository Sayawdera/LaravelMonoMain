<?php

namespace Tests\Feature\EmailVerification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetEmailVerificationIndexRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_EmailVerification_Get_request(): void
    {
        $response = $this->Get('/');

        $response->assertStatus(200);
    }
}
