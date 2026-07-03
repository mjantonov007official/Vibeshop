<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    private const SESSION_KEY = 'cart.items';

    public function add(Product $product, int $quantity = 1, ?string $size = null): void
    {
        if ($user = Auth::user()) {
            $this->addForUser($user, $product, $quantity, $size);

            return;
        }

        $items = session(self::SESSION_KEY, []);
        $key = $this->key($product->id, $size);

        $items[$key] = [
            'product_id' => $product->id,
            'size' => $size,
            'quantity' => min(99, ($items[$key]['quantity'] ?? 0) + max(1, $quantity)),
        ];

        session([self::SESSION_KEY => $items]);
    }

    public function update(string $key, int $quantity): void
    {
        if ($user = Auth::user()) {
            $item = CartItem::query()
                ->where('user_id', $user->id)
                ->where('item_key', $key)
                ->first();

            if (! $item) {
                return;
            }

            if ($quantity < 1) {
                $item->delete();
            } else {
                $item->update([
                    'quantity' => min(99, $quantity),
                ]);
            }

            return;
        }

        $items = session(self::SESSION_KEY, []);

        if (! isset($items[$key])) {
            return;
        }

        if ($quantity < 1) {
            unset($items[$key]);
        } else {
            $items[$key]['quantity'] = min(99, $quantity);
        }

        session([self::SESSION_KEY => $items]);
    }

    public function remove(string $key): void
    {
        if ($user = Auth::user()) {
            CartItem::query()
                ->where('user_id', $user->id)
                ->where('item_key', $key)
                ->delete();

            return;
        }

        $items = session(self::SESSION_KEY, []);
        unset($items[$key]);

        session([self::SESSION_KEY => $items]);
    }

    public function clear(): void
    {
        if ($user = Auth::user()) {
            CartItem::query()->where('user_id', $user->id)->delete();

            return;
        }

        session()->forget(self::SESSION_KEY);
    }

    public function items(): Collection
    {
        if ($user = Auth::user()) {
            return CartItem::query()
                ->with('product')
                ->where('user_id', $user->id)
                ->get()
                ->map(function (CartItem $item): ?array {
                    $product = $item->product;

                    if (! $product) {
                        return null;
                    }

                    return [
                        'key' => $item->item_key,
                        'product' => $product,
                        'size' => $item->size,
                        'quantity' => (int) $item->quantity,
                        'line_total' => $product->price * (int) $item->quantity,
                    ];
                })
                ->filter()
                ->values();
        }

        $rawItems = collect(session(self::SESSION_KEY, []));
        $products = Product::query()
            ->whereIn('id', $rawItems->pluck('product_id')->all())
            ->get()
            ->keyBy('id');

        return $rawItems
            ->map(function (array $item, string $key) use ($products): ?array {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    return null;
                }

                $quantity = (int) $item['quantity'];

                return [
                    'key' => $key,
                    'product' => $product,
                    'size' => $item['size'],
                    'quantity' => $quantity,
                    'line_total' => $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function count(): int
    {
        return $this->items()->sum('quantity');
    }

    public function subtotal(): int
    {
        return $this->items()->sum('line_total');
    }

    public function shippingTotal(?string $method = null): int
    {
        return $method === 'express' ? 300 : 100;
    }

    public function total(?string $method = null): int
    {
        return $this->subtotal() + ($this->count() > 0 ? $this->shippingTotal($method) : 0);
    }

    public function mergeSessionIntoUser(?User $user = null): void
    {
        $user ??= Auth::user();

        if (! $user) {
            return;
        }

        $items = collect(session(self::SESSION_KEY, []));

        foreach ($items as $item) {
            $product = Product::query()->find($item['product_id']);

            if (! $product) {
                continue;
            }

            $this->addForUser(
                $user,
                $product,
                (int) ($item['quantity'] ?? 1),
                $item['size'] ?? null,
            );
        }

        session()->forget(self::SESSION_KEY);
    }

    private function addForUser(User $user, Product $product, int $quantity = 1, ?string $size = null): void
    {
        $key = $this->key($product->id, $size);
        $item = CartItem::query()->firstOrNew([
            'user_id' => $user->id,
            'item_key' => $key,
        ]);

        $item->fill([
            'product_id' => $product->id,
            'size' => $size,
            'quantity' => min(99, ((int) $item->quantity ?: 0) + max(1, $quantity)),
        ])->save();
    }

    private function key(int $productId, ?string $size): string
    {
        return $productId.':'.($size ?: 'default');
    }
}
