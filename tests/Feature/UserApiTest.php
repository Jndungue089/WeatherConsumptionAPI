<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['city' => 'London']);
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_can_register_user()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'email'], 'token']);
    }

    public function test_can_get_weather()
    {
        // Mock HTTP response
        \Illuminate\Support\Facades\Http::fake([
            'api.openweathermap.org/*' => \Illuminate\Support\Facades\Http::response([
                'main' => ['temp' => 15],
                'weather' => [['description' => 'clear sky']],
            ], 200),
        ]);

        $response = $this->getJson('/api/users/weather', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['city', 'temperature', 'description']]);
    }

    // Add tests for index, show, update, destroy
}