<?php

namespace Tests\Feature\EmailVerification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteEmailVerificationDeleteRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_EmailVerification_Delete_request(): void
    {
        $response = $this->Delete('/');

        $response->assertStatus(200);
    }
}
