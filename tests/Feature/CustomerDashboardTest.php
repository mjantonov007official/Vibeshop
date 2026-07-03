<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_customer_dashboard(): void
    {
        $this->get(route('customer.dashboard'))
            ->assertRedirect(route('customer.login'));
    }

    public function test_customer_can_view_dashboard(): void
    {
        $user = User::query()->create([
            'name' => 'Alex Customer',
            'email' => 'alex@example.com',
            'password' => 'password',
            'role' => 'customer',
        ]);

        $this->actingAs($user)
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('Welcome Back')
            ->assertSee('Alex');
    }
}
