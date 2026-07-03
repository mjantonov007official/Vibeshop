<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'products' => Product::query()->where('is_active', true)->take(5)->get(),
        ]);
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function shop(Request $request): View|JsonResponse
    {
        $query = Product::query()->where('is_active', true);

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($innerQuery) use ($search): void {
                $innerQuery
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        match ($request->query('sort')) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query->get();
        $categories = ['All', 'Basic', 'Oversized', 'Minimal', 'Accessories'];

        if ($request->expectsJson()) {
            return response()->json([
                'toolbarHtml' => view('partials.shop-toolbar', [
                    'categories' => $categories,
                ])->render(),
                'gridHtml' => view('partials.shop-grid', [
                    'products' => $products,
                ])->render(),
            ]);
        }

        return view('shop', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function product(string $slug): View
    {
        $product = Product::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('product', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->where('is_active', true)
                ->whereKeyNot($product->id)
                ->take(3)
                ->get(),
        ]);
    }

    public function cart(CartService $cart): View
    {
        return view('cart', [
            'cartItems' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'shippingTotal' => $cart->shippingTotal(),
            'total' => $cart->total(),
        ]);
    }

    public function addToCart(Request $request, CartService $cart): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'size' => ['nullable', 'string', 'max:20'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $cart->add(
            Product::query()->findOrFail($validated['product_id']),
            (int) ($validated['quantity'] ?? 1),
            $validated['size'] ?? null,
        );

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        return back()->with('success', 'Added to cart.');
    }

    public function updateCart(string $key, Request $request, CartService $cart): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart->update($key, (int) $validated['quantity']);

        if ($request->expectsJson()) {
            return response()->json($this->cartResponseData($cart));
        }

        return back();
    }

    public function removeCart(string $key, Request $request, CartService $cart): RedirectResponse|JsonResponse
    {
        $cart->remove($key);

        if ($request->expectsJson()) {
            return response()->json($this->cartResponseData($cart));
        }

        return back();
    }

    public function customerLogin(): View
    {
        return view('customer-login');
    }

    public function customerLoginStore(Request $request, CartService $cart): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->attemptWebLogin(
            $credentials['email'],
            $credentials['password'],
            $request->boolean('remember'),
        );

        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'Invalid login details.']);
        }

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        $cart->mergeSessionIntoUser($user);
        $this->handlePendingBuyNow($request, $cart);

        if ($redirect = $this->validatedRedirectTarget($request->input('redirect_to'))) {
            return redirect()->to($redirect);
        }

        return redirect()->intended(route('customer.dashboard'));
    }

    public function customerRegister(): View
    {
        return view('customer-register');
    }

    public function customerRegisterStore(Request $request, CartService $cart): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $user->sendEmailVerificationNotification();

        $cart->mergeSessionIntoUser($user);
        $this->handlePendingBuyNow($request, $cart);

        if ($redirect = $this->validatedRedirectTarget($request->input('redirect_to'))) {
            return redirect()->to($redirect);
        }

        return redirect()->intended(route('customer.dashboard'));
    }

    public function customerGoogleRedirect(Request $request): RedirectResponse
    {
        $this->storeGoogleAuthIntent($request);

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function customerGoogleCallback(Request $request, CartService $cart): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            Log::warning('Google authentication failed.', [
                'message' => $exception->getMessage(),
            ]);

            $this->clearGoogleAuthIntent($request);

            return redirect()
                ->route('customer.login')
                ->withErrors(['email' => 'Google sign in could not be completed. Please try again.']);
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            $this->clearGoogleAuthIntent($request);

            return redirect()
                ->route('customer.login')
                ->withErrors(['email' => 'Google did not return an email address for this account.']);
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($user && $user->role === 'admin') {
            $this->clearGoogleAuthIntent($request);

            return redirect()
                ->route('customer.login')
                ->withErrors(['email' => 'This Google account is linked to an admin account. Please use the admin login.']);
        }

        if (! $user) {
            $user = User::query()->create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'role' => 'customer',
                'google_id' => $googleUser->getId(),
                'google_avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        } else {
            $updates = [];

            if (! $user->google_id) {
                $updates['google_id'] = $googleUser->getId();
            }

            if ($googleUser->getAvatar() && $user->google_avatar !== $googleUser->getAvatar()) {
                $updates['google_avatar'] = $googleUser->getAvatar();
            }

            if (! $user->email_verified_at) {
                $updates['email_verified_at'] = now();
            }

            if ($updates !== []) {
                $user->forceFill($updates)->save();
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        $cart->mergeSessionIntoUser($user);
        $this->handlePendingBuyNowFromSession($request, $cart);

        $redirect = $this->validatedRedirectTarget($request->session()->pull('google_auth.redirect_to'));
        $this->clearGoogleAuthIntent($request);

        if ($redirect) {
            return redirect()->to($redirect);
        }

        return redirect()->intended(route('customer.dashboard'));
    }

    public function customerDashboard(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('customer.login');
        }

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $orders = Order::query()
            ->with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $totalSpent = (int) Order::query()
            ->where('user_id', Auth::id())
            ->sum('total');

        $totalOrders = Order::query()
            ->where('user_id', Auth::id())
            ->count();

        return view('customer-dashboard', [
            'user' => Auth::user(),
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'totalSpent' => $totalSpent,
            'rewardPoints' => (int) floor($totalSpent / 10),
            'latestOrder' => $orders->first(),
            'recommendedProducts' => Product::query()
                ->where('is_active', true)
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }

    public function checkout(CartService $cart): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('customer.login'));
        }

        if ($cart->count() === 0) {
            return redirect()->route('cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        return view('checkout', [
            'cartItems' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'shippingTotal' => $cart->shippingTotal(),
            'total' => $cart->total(),
        ]);
    }

    public function placeOrder(Request $request, CartService $cart): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('customer.login'));
        }

        if ($cart->count() === 0) {
            return redirect()->route('cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'zip_code' => ['required', 'string', 'max:20'],
            'shipping_method' => ['required', 'in:standard,express'],
            'payment_method' => ['required', 'in:card,cod'],
        ]);

        $order = Order::query()->create([
            'reference' => 'TL-'.Str::upper(Str::random(8)),
            'user_id' => Auth::id(),
            'customer_name' => $validated['first_name'].' '.$validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'street_address' => $validated['street_address'],
            'city' => $validated['city'],
            'zip_code' => $validated['zip_code'],
            'shipping_method' => $validated['shipping_method'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'paid',
            'status' => 'processing',
            'status_updated_at' => now(),
            'subtotal' => $cart->subtotal(),
            'shipping_total' => $cart->shippingTotal($validated['shipping_method']),
            'total' => $cart->total($validated['shipping_method']),
        ]);

        foreach ($cart->items() as $item) {
            $product = $item['product'];

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'size' => $item['size'],
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'line_total' => $item['line_total'],
            ]);
        }

        $cart->clear();

        return redirect()->route('order.success', $order);
    }

    public function orderSuccess(?Order $order = null): View
    {
        return view('order-success', [
            'order' => $order,
        ]);
    }

    public function adminLogin(): View
    {
        return view('admin-login');
    }

    public function adminLoginStore(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->attemptWebLogin(
            $credentials['email'],
            $credentials['password'],
            $request->boolean('remember'),
        );

        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'Invalid admin login details.']);
        }

        if ($user->role !== 'admin') {
            Auth::logout();

            return back()->withInput()->withErrors(['email' => 'This account is not an admin account.']);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function adminRegister(): View
    {
        return view('admin-register');
    }

    public function adminRegisterStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = User::query()->create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $user->sendEmailVerificationNotification();

        return redirect()->route('admin.dashboard');
    }

    public function admin(): View|RedirectResponse
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $orders = Order::query()->with(['items', 'user'])->latest()->take(10)->get();

        return view('admin-dashboard', [
            'orders' => $orders,
            'products' => Product::query()->latest()->get(),
            'totalOrders' => Order::query()->count(),
            'totalRevenue' => Order::query()->sum('total'),
            'pendingOrders' => Order::query()->whereIn('status', ['processing', 'on_hold'])->count(),
            'completedOrders' => Order::query()->where('status', 'delivered')->count(),
            'paymentStatusOptions' => Order::paymentStatusOptions(),
            'statusOptions' => Order::statusOptions(),
            'productCategories' => ['Basic', 'Oversized', 'Minimal', 'Accessories'],
        ]);
    }

    public function adminUpdateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'payment_status' => ['nullable', 'in:'.implode(',', Order::paymentStatusOptions())],
            'status' => ['nullable', 'in:'.implode(',', Order::statusOptions())],
        ]);

        $updates = [];

        if (! empty($validated['payment_status']) && $validated['payment_status'] !== $order->payment_status) {
            $updates['payment_status'] = $validated['payment_status'];
        }

        if (! empty($validated['status']) && $validated['status'] !== $order->status) {
            $updates['status'] = $validated['status'];
            $updates['status_updated_at'] = now();
        }

        if ($updates !== []) {
            $order->update($updates);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('admin_status_success', 'Order '.$order->reference.' updated successfully.');
    }

    public function adminStoreProduct(Request $request): RedirectResponse
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['required', 'in:Basic,Oversized,Minimal,Accessories'],
            'featured_image' => ['required', 'image', 'max:5120'],
            'gallery_images.*' => ['image', 'max:5120'],
        ]);

        $galleryFiles = $request->file('gallery_images');
        $galleryFiles = is_array($galleryFiles) ? array_slice($galleryFiles, 0, 4) : [];

        $featuredImagePath = $request->file('featured_image')->store('products/featured', 'public');

        $gallery = collect($galleryFiles)
            ->map(fn ($image) => Storage::url($image->store('products/gallery', 'public')))
            ->values()
            ->all();

        $product = Product::query()->create([
            'name' => $validated['title'],
            'slug' => $this->generateUniqueProductSlug($validated['title']),
            'sku' => $this->generateUniqueProductSku($validated['category']),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category' => $validated['category'],
            'image_url' => Storage::url($featuredImagePath),
            'gallery' => $gallery,
            'sizes' => $validated['category'] === 'Accessories' ? ['One Size'] : ['S', 'M', 'L', 'XL'],
            'is_active' => true,
        ]);

        return redirect()
            ->to(route('admin.dashboard').'#products')
            ->with('admin_product_success', 'Product '.$product->name.' uploaded successfully.');
    }

    public function adminUpdateProduct(Request $request, Product $product): RedirectResponse
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['required', 'in:Basic,Oversized,Minimal,Accessories'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images.*' => ['image', 'max:5120'],
            'remove_featured_image' => ['nullable', 'boolean'],
            'removed_gallery_images' => ['nullable', 'string'],
        ]);

        $updates = [
            'name' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category' => $validated['category'],
            'sizes' => $validated['category'] === 'Accessories' ? ['One Size'] : ['S', 'M', 'L', 'XL'],
        ];

        if ($validated['title'] !== $product->name) {
            $updates['slug'] = $this->generateUniqueProductSlug($validated['title']);
        }

        if ($request->boolean('remove_featured_image') && ! $request->hasFile('featured_image')) {
            $this->deleteStorageUrlPath($product->image_url);
            $updates['image_url'] = '';
        }

        if ($request->hasFile('featured_image')) {
            $this->deleteStorageUrlPath($product->image_url);
            $updates['image_url'] = Storage::url($request->file('featured_image')->store('products/featured', 'public'));
        }

        $galleryFiles = $request->file('gallery_images');
        $galleryFiles = is_array($galleryFiles) ? array_slice($galleryFiles, 0, 4) : [];
        $removedGalleryImages = collect(explode('|', (string) ($validated['removed_gallery_images'] ?? '')))
            ->filter()
            ->values();
        $currentGallery = collect($product->gallery ?? []);

        if ($galleryFiles !== []) {
            foreach ($product->gallery ?? [] as $galleryImage) {
                $this->deleteStorageUrlPath($galleryImage);
            }

            $updates['gallery'] = collect($galleryFiles)
                ->map(fn ($image) => Storage::url($image->store('products/gallery', 'public')))
                ->values()
                ->all();
        } elseif ($removedGalleryImages->isNotEmpty()) {
            $removedGalleryImages->each(fn ($galleryImage) => $this->deleteStorageUrlPath($galleryImage));

            $updates['gallery'] = $currentGallery
                ->reject(fn ($galleryImage) => $removedGalleryImages->contains($galleryImage))
                ->values()
                ->all();
        }

        $product->update($updates);

        return redirect()
            ->to(route('admin.dashboard').'#products')
            ->with('admin_product_success', 'Product '.$product->name.' updated successfully.');
    }

    public function adminDestroyProduct(Product $product): RedirectResponse
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $productName = $product->name;

        $this->deleteStorageUrlPath($product->image_url);

        foreach ($product->gallery ?? [] as $galleryImage) {
            $this->deleteStorageUrlPath($galleryImage);
        }

        $product->delete();

        return redirect()
            ->to(route('admin.dashboard').'#products')
            ->with('admin_product_success', 'Product '.$productName.' deleted successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function newsletter(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return back()->with('newsletter_success', 'You are subscribed to THREADLAB updates.');
    }

    public function forgotPassword(): View
    {
        return view('auth-forgot-password');
    }

    public function forgotPasswordStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $validated['email'],
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            return back()->withInput()->withErrors([
                'email' => __($status),
            ]);
        }

        return back()->with('status', __($status));
    }

    public function resetPassword(Request $request, string $token): View
    {
        return view('auth-reset-password', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function resetPasswordStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user) use ($validated): void {
                $user->forceFill([
                    'password' => Hash::make($validated['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput()->withErrors([
                'email' => __($status),
            ]);
        }

        return redirect()->route('customer.login')->with('status', __($status));
    }

    public function emailVerificationNotice(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('customer.login');
        }

        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'customer.dashboard');
        }

        return view('auth-verify-email');
    }

    public function emailVerificationSend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('customer.login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'customer.dashboard');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    public function emailVerificationVerify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()
            ->route($request->user()->role === 'admin' ? 'admin.dashboard' : 'customer.dashboard')
            ->with('status', 'verified');
    }

    private function attemptWebLogin(string $email, string $password, bool $remember = false): ?User
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return null;
        }

        if (Hash::check($password, $user->password)) {
            Auth::login($user, $remember);

            return $user;
        }

        if ($this->isLegacyPlaintextPassword($user->password, $password)) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();

            Auth::login($user, $remember);

            return $user;
        }

        return null;
    }

    private function isLegacyPlaintextPassword(string $storedPassword, string $password): bool
    {
        return Hash::info($storedPassword)['algo'] === null
            && hash_equals($storedPassword, $password);
    }

    private function validatedRedirectTarget(?string $target): ?string
    {
        if (! is_string($target) || $target === '') {
            return null;
        }

        if (str_starts_with($target, '/')) {
            return $target;
        }

        $appUrl = rtrim((string) config('app.url'), '/');

        if (str_starts_with($target, $appUrl)) {
            return $target;
        }

        $targetParts = parse_url($target);
        $currentHost = request()->getHost();

        if (($targetParts['host'] ?? null) === $currentHost) {
            return $target;
        }

        return null;
    }

    private function cartResponseData(CartService $cart): array
    {
        $cartItems = $cart->items();
        $subtotal = $cart->subtotal();
        $shippingTotal = $cart->shippingTotal();
        $total = $cart->total();

        return [
            'cartItemsHtml' => view('partials.cart-items', [
                'cartItems' => $cartItems,
            ])->render(),
            'cartSummaryHtml' => view('partials.cart-summary', [
                'cartItems' => $cartItems,
                'subtotal' => $subtotal,
                'shippingTotal' => $shippingTotal,
                'total' => $total,
            ])->render(),
            'headerCartHtml' => view('partials.mini-cart')->render(),
            'itemTypesCount' => $cartItems->count(),
            'itemLabel' => Str::plural('piece', $cartItems->count()),
        ];
    }

    private function handlePendingBuyNow(Request $request, CartService $cart): void
    {
        $productId = $request->integer('buy_now_product_id');

        if ($productId < 1) {
            return;
        }

        $product = Product::query()
            ->whereKey($productId)
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return;
        }

        $size = $request->string('buy_now_size')->toString();
        $size = $size !== '' ? $size : null;

        $quantity = max(1, min(99, $request->integer('buy_now_quantity', 1)));

        $cart->add($product, $quantity, $size);
    }

    private function storeGoogleAuthIntent(Request $request): void
    {
        if ($redirect = $this->validatedRedirectTarget($request->input('redirect_to'))) {
            $request->session()->put('google_auth.redirect_to', $redirect);
        } else {
            $request->session()->forget('google_auth.redirect_to');
        }

        $productId = $request->integer('buy_now_product_id');

        if ($productId > 0) {
            $request->session()->put('google_auth.buy_now', [
                'product_id' => $productId,
                'size' => $request->string('buy_now_size')->toString(),
                'quantity' => max(1, min(99, $request->integer('buy_now_quantity', 1))),
            ]);

            return;
        }

        $request->session()->forget('google_auth.buy_now');
    }

    private function handlePendingBuyNowFromSession(Request $request, CartService $cart): void
    {
        $payload = $request->session()->pull('google_auth.buy_now');

        if (! is_array($payload)) {
            return;
        }

        $productId = (int) ($payload['product_id'] ?? 0);

        if ($productId < 1) {
            return;
        }

        $product = Product::query()
            ->whereKey($productId)
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return;
        }

        $size = isset($payload['size']) && is_string($payload['size']) && $payload['size'] !== ''
            ? $payload['size']
            : null;

        $quantity = max(1, min(99, (int) ($payload['quantity'] ?? 1)));

        $cart->add($product, $quantity, $size);
    }

    private function clearGoogleAuthIntent(Request $request): void
    {
        $request->session()->forget([
            'google_auth.redirect_to',
            'google_auth.buy_now',
        ]);
    }

    private function generateUniqueProductSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug !== '' ? $baseSlug : 'product';
        $counter = 2;

        while (Product::query()->where('slug', $slug)->exists()) {
            $slug = ($baseSlug !== '' ? $baseSlug : 'product').'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function generateUniqueProductSku(string $category): string
    {
        $prefix = match ($category) {
            'Oversized' => 'OVR',
            'Minimal' => 'MIN',
            'Accessories' => 'ACC',
            default => 'BSC',
        };

        do {
            $sku = 'TL-'.$prefix.'-'.Str::upper(Str::random(6));
        } while (Product::query()->where('sku', $sku)->exists());

        return $sku;
    }

    private function deleteStorageUrlPath(?string $url): void
    {
        if (! $url || ! str_starts_with($url, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($url, '/storage/'));
    }

    public function generate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_type' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        try {
            Generation::create([
                'business_type' => $validated['business_type'],
                'result' => 'Generated successfully',
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            ]);
        } catch (QueryException $exception) {
            Log::error('Generation save failed.', [
                'message' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'business_type' => 'We could not save your request right now. Please try again later.',
                ]);
        }

        return back()->with('success', 'Generated successfully.');
    }
}
