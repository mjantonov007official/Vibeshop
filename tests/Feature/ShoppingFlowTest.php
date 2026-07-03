<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShoppingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_product_to_cart_and_place_order(): void
    {
        $user = User::query()->create([
            'name' => 'Alex Buyer',
            'email' => 'alex@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $product = Product::query()->create([
            'name' => 'Essential Black Tee',
            'slug' => 'essential-black-tee',
            'sku' => 'TL-TEE-BLK',
            'description' => 'Premium cotton tee.',
            'price' => 799,
            'compare_at_price' => 1200,
            'category' => 'Basic',
            'image_url' => 'https://example.com/tee.jpg',
            'sizes' => ['S', 'M', 'L'],
            'stock' => 20,
            'is_active' => true,
        ]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 2,
        ])->assertRedirect();

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Essential Black Tee')
            ->assertSee('PHP 1,598');

        $this->post(route('checkout.place'), [
            'first_name' => 'Alex',
            'last_name' => 'Buyer',
            'email' => 'alex@example.com',
            'phone' => '+63 900 000 0000',
            'street_address' => '128 Studio Alley',
            'city' => 'Metro Manila',
            'zip_code' => '1200',
            'shipping_method' => 'standard',
            'payment_method' => 'cod',
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'email' => 'alex@example.com',
            'payment_status' => 'pending',
            'status' => 'processing',
            'subtotal' => 1598,
            'shipping_total' => 100,
            'total' => 1698,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Essential Black Tee',
            'size' => 'M',
            'quantity' => 2,
            'line_total' => 1598,
        ]);

        $this->assertSame(1, Order::query()->count());
    }

    public function test_cart_quantity_can_be_updated_and_removed_using_cart_item_key(): void
    {
        $product = Product::query()->create([
            'name' => 'Essential Black Tee',
            'slug' => 'essential-black-tee',
            'sku' => 'TL-TEE-BLK',
            'description' => 'Premium cotton tee.',
            'price' => 799,
            'category' => 'Basic',
            'image_url' => 'https://example.com/tee.jpg',
            'sizes' => ['S', 'M', 'L'],
            'stock' => 20,
            'is_active' => true,
        ]);

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'L',
            'quantity' => 1,
        ])->assertRedirect();

        $itemKey = $product->id.':L';

        $this->patch(route('cart.update', $itemKey), [
            'quantity' => 3,
        ])->assertRedirect();

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Size: L')
            ->assertSee('PHP 2,397');

        $this->delete(route('cart.remove', $itemKey))
            ->assertRedirect();

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Your cart is empty');
    }

    public function test_different_sizes_are_stored_as_separate_cart_items(): void
    {
        $product = Product::query()->create([
            'name' => 'Essential Black Tee',
            'slug' => 'essential-black-tee',
            'sku' => 'TL-TEE-BLK',
            'description' => 'Premium cotton tee.',
            'price' => 799,
            'category' => 'Basic',
            'image_url' => 'https://example.com/tee.jpg',
            'sizes' => ['S', 'M', 'L'],
            'stock' => 20,
            'is_active' => true,
        ]);

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'S',
            'quantity' => 1,
        ])->assertRedirect();

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 1,
        ])->assertRedirect();

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Size: S')
            ->assertSee('Size: M');
    }

    public function test_cart_update_can_return_ajax_payload_for_in_place_refresh(): void
    {
        $product = Product::query()->create([
            'name' => 'Ajax Tee',
            'slug' => 'ajax-tee',
            'sku' => 'TL-AJAX',
            'description' => 'Ajax tee.',
            'price' => 799,
            'category' => 'Basic',
            'image_url' => 'https://example.com/ajax-tee.jpg',
            'sizes' => ['M'],
            'stock' => 20,
            'is_active' => true,
        ]);

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 1,
        ])->assertRedirect();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->patch(route('cart.update', $product->id.':M'), [
            'quantity' => 2,
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'itemTypesCount' => 1,
                'itemLabel' => 'piece',
            ]);

        $this->assertStringContainsString('Ajax Tee', $response->json('cartItemsHtml'));
        $this->assertStringContainsString('PHP 1,598', $response->json('cartItemsHtml'));
    }

    public function test_shop_sort_and_filter_can_return_ajax_payload(): void
    {
        Product::query()->create([
            'name' => 'Basic Premium Tee',
            'slug' => 'basic-premium-tee',
            'sku' => 'TL-BASIC-2',
            'description' => 'Higher priced basic tee.',
            'price' => 1200,
            'category' => 'Basic',
            'image_url' => 'https://example.com/basic-premium.jpg',
            'sizes' => ['M'],
            'stock' => 10,
            'is_active' => true,
        ]);

        Product::query()->create([
            'name' => 'Basic Core Tee',
            'slug' => 'basic-core-tee',
            'sku' => 'TL-BASIC-1',
            'description' => 'Lower priced basic tee.',
            'price' => 700,
            'category' => 'Basic',
            'image_url' => 'https://example.com/basic-core.jpg',
            'sizes' => ['M'],
            'stock' => 10,
            'is_active' => true,
        ]);

        Product::query()->create([
            'name' => 'Oversized Carry Tote',
            'slug' => 'oversized-carry-tote',
            'sku' => 'TL-OVR-1',
            'description' => 'Accessory item.',
            'price' => 950,
            'category' => 'Accessories',
            'image_url' => 'https://example.com/tote.jpg',
            'sizes' => ['OS'],
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get(route('shop', [
            'category' => 'Basic',
            'sort' => 'price_desc',
        ]));

        $response->assertOk()
            ->assertJsonStructure([
                'toolbarHtml',
                'gridHtml',
            ]);

        $toolbarHtml = $response->json('toolbarHtml');
        $gridHtml = $response->json('gridHtml');

        $this->assertStringContainsString('shop-filter is-selected', $toolbarHtml);
        $this->assertStringContainsString('Price: High to Low', $toolbarHtml);
        $this->assertStringContainsString('Basic Premium Tee', $gridHtml);
        $this->assertStringContainsString('Basic Core Tee', $gridHtml);
        $this->assertStringNotContainsString('Oversized Carry Tote', $gridHtml);
        $this->assertTrue(
            strpos($gridHtml, 'Basic Premium Tee') < strpos($gridHtml, 'Basic Core Tee')
        );
    }

    public function test_paid_order_is_visible_in_customer_and_admin_dashboards(): void
    {
        $customer = User::query()->create([
            'name' => 'Visible Customer',
            'email' => 'visible@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $admin = User::query()->create([
            'name' => 'Visible Admin',
            'email' => 'admin-visible@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $product = Product::query()->create([
            'name' => 'Dashboard Tee',
            'slug' => 'dashboard-tee',
            'sku' => 'TL-DASH',
            'description' => 'Dashboard tee.',
            'price' => 999,
            'category' => 'Basic',
            'image_url' => 'https://example.com/dashboard-tee.jpg',
            'sizes' => ['M'],
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 1,
        ]);

        $this->actingAs($customer)->post(route('checkout.place'), [
            'first_name' => 'Visible',
            'last_name' => 'Customer',
            'email' => 'visible@example.com',
            'phone' => '+63 900 000 0000',
            'street_address' => '88 Update Street',
            'city' => 'Metro Manila',
            'zip_code' => '1200',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
        ])->assertRedirect();

        $order = Order::query()->firstOrFail();

        $this->actingAs($customer)
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee($order->reference)
            ->assertSee('Paid')
            ->assertSee('Processing');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee($order->reference)
            ->assertSee('Visible Customer')
            ->assertSee('Paid')
            ->assertSee('Processing');
    }

    public function test_admin_can_update_order_status_and_customer_sees_the_new_status(): void
    {
        $customer = User::query()->create([
            'name' => 'Status Customer',
            'email' => 'status@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $admin = User::query()->create([
            'name' => 'Status Admin',
            'email' => 'status-admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $order = Order::query()->create([
            'reference' => 'TL-STATUS1',
            'user_id' => $customer->id,
            'customer_name' => 'Status Customer',
            'email' => 'status@example.com',
            'phone' => '+63 900 000 0000',
            'street_address' => '77 Queue Lane',
            'city' => 'Metro Manila',
            'zip_code' => '1200',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'status' => 'processing',
            'status_updated_at' => now(),
            'subtotal' => 799,
            'shipping_total' => 100,
            'total' => 899,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), [
                'status' => 'delivered',
            ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'delivered',
        ]);

        $this->assertNotNull($order->fresh()->status_updated_at);

        $this->actingAs($customer)
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('Delivered')
            ->assertSee('Paid')
            ->assertSee($order->reference);
    }

    public function test_admin_can_upload_a_real_product_with_featured_and_gallery_images(): void
    {
        Storage::fake('public');

        $admin = User::query()->create([
            'name' => 'Product Admin',
            'email' => 'product-admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Studio Heavy Tee',
            'description' => 'A real uploaded product.',
            'price' => 1499,
            'stock' => 18,
            'category' => 'Basic',
            'featured_image' => $this->fakePngUpload('featured.png'),
        ]);

        $response->assertRedirect(route('admin.dashboard').'#products');

        $product = Product::query()->where('name', 'Studio Heavy Tee')->firstOrFail();

        $this->assertSame('Basic', $product->category);
        $this->assertSame(18, $product->stock);
        $this->assertStringStartsWith('/storage/products/featured/', $product->image_url);
        $this->assertCount(0, $product->gallery ?? []);

        Storage::disk('public')->assertExists(str_replace('/storage/', '', $product->image_url));
    }

    public function test_admin_can_update_and_delete_a_product(): void
    {
        Storage::fake('public');

        $admin = User::query()->create([
            'name' => 'Manage Product Admin',
            'email' => 'manage-product-admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $product = Product::query()->create([
            'name' => 'Manage Tee',
            'slug' => 'manage-tee',
            'sku' => 'TL-MNG-001',
            'description' => 'Original description.',
            'price' => 899,
            'category' => 'Basic',
            'image_url' => '/storage/products/featured/original.png',
            'gallery' => ['/storage/products/gallery/original-1.png'],
            'sizes' => ['S', 'M', 'L'],
            'stock' => 12,
            'is_active' => true,
        ]);

        Storage::disk('public')->put('products/featured/original.png', 'old');
        Storage::disk('public')->put('products/gallery/original-1.png', 'old');

        $this->actingAs($admin)
            ->patch(route('admin.products.update', $product), [
                'title' => 'Manage Tee Updated',
                'description' => 'Updated description.',
                'price' => 1299,
                'stock' => 25,
                'category' => 'Minimal',
                'featured_image' => $this->fakePngUpload('updated-featured.png'),
            ])
            ->assertRedirect(route('admin.dashboard').'#products');

        $product->refresh();

        $this->assertSame('Manage Tee Updated', $product->name);
        $this->assertSame(1299, $product->price);
        $this->assertSame(25, $product->stock);
        $this->assertSame('Minimal', $product->category);
        $this->assertSame('manage-tee-updated', $product->slug);
        $this->assertStringStartsWith('/storage/products/featured/', $product->image_url);
        $this->assertNotSame('/storage/products/featured/original.png', $product->image_url);
        Storage::disk('public')->assertMissing('products/featured/original.png');
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $product->image_url));

        $this->get(route('shop'))
            ->assertOk()
            ->assertSee($product->displayImageUrl(), false)
            ->assertDontSee('/storage/products/featured/original.png', false);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.dashboard').'#products');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    private function fakePngUpload(string $filename): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');

        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9sotkYQAAAAASUVORK5CYII='));

        return new UploadedFile($path, $filename, 'image/png', null, true);
    }
}
