<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_customer_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Customer Test',
            'email' => 'customer-test-' . time() . '@example.com',
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
        User::factory()->create([
            'email' => 'customer-login-' . time() . '@example.com',
            'password' => Hash::make('mysecret'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => 'customer-login-' . time() . '@example.com',
            'password' => 'mysecret',
        ]);

        $response->assertRedirect(route('home'));
    }

    public function test_admin_cannot_login_through_customer_login_route(): void
    {
        User::factory()->create([
            'email' => 'admin-user-' . time() . '@example.com',
            'password' => Hash::make('adminpass'),
            'role' => 'admin',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin-user-' . time() . '@example.com',
            'password' => 'adminpass',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Sai tài khoản hoặc mật khẩu');
    }

}
