<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
    }
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'mmmmm@gmail.com',
            'password' => 'Mm.1@23456',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'mmmmm@gmail.com']);
    }

    public function test_user_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'mmmmm@gmail.com',
            'password' => 'Mm.1@23456',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'token'
            ]
        ]);
        $this->assertNotNull($response->json('data.token'));
    }
}
