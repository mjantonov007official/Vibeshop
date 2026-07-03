<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login_with_credentials_used_during_registration(): void
    {
        Notification::fake();

        $this->post(route('customer.register.store'), [
            'name' => 'Alex Customer',
            'email' => 'alex@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ])->assertRedirect(route('customer.dashboard'));

        Notification::assertSentTo(
            User::query()->where('email', 'alex@example.com')->firstOrFail(),
            VerifyEmail::class,
        );

        auth()->logout();

        $this->post(route('customer.login.store'), [
            'email' => 'alex@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('customer.dashboard'));

        $user = User::query()->where('email', 'alex@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_admin_can_login_with_credentials_used_during_registration(): void
    {
        Notification::fake();

        $this->post(route('admin.register.store'), [
            'full_name' => 'Alex Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ])->assertRedirect(route('admin.dashboard'));

        Notification::assertSentTo(
            User::query()->where('email', 'admin@example.com')->firstOrFail(),
            VerifyEmail::class,
        );

        auth()->logout();

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));

        $user = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_legacy_plaintext_password_is_rehashed_on_login(): void
    {
        $user = new User();
        $user->forceFill([
            'name' => 'Legacy Customer',
            'email' => 'legacy@example.com',
            'password' => 'plaintext-password',
            'role' => 'customer',
        ])->save();

        $this->post(route('customer.login.store'), [
            'email' => 'legacy@example.com',
            'password' => 'plaintext-password',
        ])->assertRedirect(route('customer.dashboard'));

        $user->refresh();

        $this->assertTrue(Hash::check('plaintext-password', $user->password));
        $this->assertNotSame('plaintext-password', $user->password);
    }

    public function test_password_reset_link_can_be_requested_and_password_can_be_reset(): void
    {
        Notification::fake();

        $user = User::query()->create([
            'name' => 'Reset User',
            'email' => 'reset@example.com',
            'password' => Hash::make('old-password'),
            'role' => 'customer',
        ]);

        $this->post(route('password.email'), [
            'email' => 'reset@example.com',
        ])->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);

        $token = Password::broker()->createToken($user);

        $this->post(route('password.store'), [
            'token' => $token,
            'email' => 'reset@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('customer.login'));

        $user->refresh();

        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_verification_email_can_be_resent_and_signed_link_verifies_user(): void
    {
        Notification::fake();

        $user = User::query()->create([
            'name' => 'Verify User',
            'email' => 'verify@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertSessionHas('status', 'verification-link-sent');

        Notification::assertSentTo($user, VerifyEmail::class);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ],
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect(route('customer.dashboard'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_guest_is_redirected_to_login_when_opening_checkout(): void
    {
        $this->get(route('checkout'))
            ->assertRedirect(route('customer.login'));
    }

    public function test_customer_login_returns_to_checkout_when_redirected_from_guest_checkout(): void
    {
        $user = User::query()->create([
            'name' => 'Checkout User',
            'email' => 'checkout@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->get(route('checkout'));

        $this->post(route('customer.login.store'), [
            'email' => 'checkout@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('checkout'));
    }

    public function test_customer_login_honors_explicit_checkout_redirect_target(): void
    {
        $user = User::query()->create([
            'name' => 'Explicit Redirect User',
            'email' => 'explicit@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->post(route('customer.login.store'), [
            'email' => 'explicit@example.com',
            'password' => 'password123',
            'redirect_to' => '/checkout',
        ])->assertRedirect('/checkout');
    }

    public function test_customer_registration_returns_to_checkout_when_redirected_from_guest_checkout(): void
    {
        Notification::fake();

        $this->get(route('checkout'));

        $this->post(route('customer.register.store'), [
            'name' => 'Checkout Register',
            'email' => 'checkout-register@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ])->assertRedirect(route('checkout'));
    }

    public function test_guest_cart_page_shows_checkout_login_prompt_when_cart_has_items(): void
    {
        $product = \App\Models\Product::query()->create([
            'name' => 'Prompt Tee',
            'slug' => 'prompt-tee',
            'sku' => 'TL-PROMPT',
            'description' => 'Prompt tee.',
            'price' => 799,
            'category' => 'Basic',
            'image_url' => 'https://example.com/prompt-tee.jpg',
            'sizes' => ['M'],
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 1,
        ])->assertRedirect();

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Please login/register to continue checkout.');
    }

    public function test_guest_buy_now_login_continues_to_checkout_with_selected_size_added_to_cart(): void
    {
        $user = User::query()->create([
            'name' => 'Buy Now User',
            'email' => 'buynow@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $product = \App\Models\Product::query()->create([
            'name' => 'Buy Now Tee',
            'slug' => 'buy-now-tee',
            'sku' => 'TL-BUYNOW',
            'description' => 'Buy now tee.',
            'price' => 799,
            'category' => 'Basic',
            'image_url' => 'https://example.com/buy-now-tee.jpg',
            'sizes' => ['S', 'M', 'L'],
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post(route('customer.login.store'), [
            'email' => 'buynow@example.com',
            'password' => 'password123',
            'redirect_to' => '/checkout',
            'buy_now_product_id' => $product->id,
            'buy_now_size' => 'L',
            'buy_now_quantity' => 1,
        ])->assertRedirect('/checkout');

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Buy Now Tee')
            ->assertSee('Size: L');
    }

    public function test_customer_cart_persists_after_logout_and_login(): void
    {
        $user = User::query()->create([
            'name' => 'Persistent Cart User',
            'email' => 'persist@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $product = \App\Models\Product::query()->create([
            'name' => 'Persistent Tee',
            'slug' => 'persistent-tee',
            'sku' => 'TL-PERSIST',
            'description' => 'Persistent tee.',
            'price' => 799,
            'category' => 'Basic',
            'image_url' => 'https://example.com/persistent-tee.jpg',
            'sizes' => ['M'],
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 1,
        ])->assertRedirect();

        auth()->logout();

        $this->post(route('customer.login.store'), [
            'email' => 'persist@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('customer.dashboard'));

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Persistent Tee')
            ->assertSee('Size: M');
    }

    public function test_guest_session_cart_is_merged_into_customer_cart_on_login(): void
    {
        $user = User::query()->create([
            'name' => 'Merge Cart User',
            'email' => 'merge@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $product = \App\Models\Product::query()->create([
            'name' => 'Merge Tee',
            'slug' => 'merge-tee',
            'sku' => 'TL-MERGE',
            'description' => 'Merge tee.',
            'price' => 799,
            'category' => 'Basic',
            'image_url' => 'https://example.com/merge-tee.jpg',
            'sizes' => ['L'],
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'L',
            'quantity' => 1,
        ])->assertRedirect();

        $this->post(route('customer.login.store'), [
            'email' => 'merge@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('customer.dashboard'));

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Merge Tee')
            ->assertSee('Size: L');
    }
}
