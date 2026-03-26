<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_customer_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Customer Test',
            'email' => 'customer-test-'.time().'@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Đăng ký thành công');

        $this->assertDatabaseHas('users', [
            'name' => 'Customer Test',
            'role' => 'customer',
        ]);
    }

    public function test_customer_can_login_with_correct_credentials(): void
    {
        $email = 'customer-login-'.time().'@example.com';
        User::factory()->create([
            'email' => $email,
            'password' => Hash::make('mysecret'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'mysecret',
        ]);

        $response->assertRedirect(route('home'));
    }

    public function test_admin_cannot_login_through_customer_login_route(): void
    {
        $email = 'admin-user-'.time().'@example.com';
        User::factory()->create([
            'email' => $email,
            'password' => Hash::make('adminpass'),
            'role' => 'admin',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $email,
            'password' => 'adminpass',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Không đúng quyền');
    }
}
