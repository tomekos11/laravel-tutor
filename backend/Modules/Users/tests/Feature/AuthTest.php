<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Modules\Users\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup Passport client before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => 'Test Personal Access Client',
            '--no-interaction' => true
        ]);
    }

    /**
     * Test registration with valid data.
     *
     * @test
     */
    public function it_registers_a_user_successfully()
    {
        $payload = [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'name' => 'John',
            'surname' => 'Doe',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User registered successfully',
                 ]);

        $this->assertDatabaseHas('user__users', [
            'email' => 'john@example.com',
            'username' => 'johndoe'
        ]);

        $response->assertCookie('tutor_access_token');
    }

    /**
     * Test registration fails if email already exists.
     *
     * @test
     */
    public function it_fails_registration_when_email_is_taken()
    {
        User::factory()->create([
            'email' => 'john@example.com'
        ]);

        $payload = [
            'username' => 'otheruser',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['email']
                 ]);
    }

    /**
     * Test login with valid credentials.
     *
     * @test
     */
    public function it_logs_in_successfully()
    {
        $user = User::factory()->create([
            'password' => Hash::make('my_secret_password')
        ]);

        $response = $this->postJson('/api/login', [
            'login' => $user->email,
            'password' => 'my_secret_password',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $response->assertCookie('tutor_access_token');
    }

    /**
     * Test login fails with wrong password.
     *
     * @test
     */
    public function it_fails_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct_password')
        ]);

        $response = $this->postJson('/api/login', [
            'login' => $user->email,
            'password' => 'WRONG_PASSWORD',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    /**
     * Test getting current authenticated user details.
     *
     * @test
     */
    public function it_returns_authenticated_user_details()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
                         ->getJson('/api/me');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $user->id,
                         'email' => $user->email,
                     ]
                 ]);
    }
}
