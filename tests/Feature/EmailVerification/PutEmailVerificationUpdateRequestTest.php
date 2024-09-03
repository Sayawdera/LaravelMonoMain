<?php

namespace Tests\Feature\EmailVerification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PutEmailVerificationUpdateRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_EmailVerification_Put_request(): void
    {
        $response = $this->Put('/');

        $response->assertStatus(200);
    }
}
