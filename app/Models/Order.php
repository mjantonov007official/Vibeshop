<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUS_OPTIONS = [
        'processing',
        'on_hold',
        'delivered',
    ];

    public const PAYMENT_STATUS_OPTIONS = [
        'pending',
        'paid',
    ];

    protected $fillable = [
        'reference',
        'user_id',
        'customer_name',
        'email',
        'phone',
        'street_address',
        'city',
        'zip_code',
        'shipping_method',
        'payment_method',
        'payment_status',
        'status',
        'status_updated_at',
        'subtotal',
        'shipping_total',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'status_updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statusOptions(): array
    {
        return self::STATUS_OPTIONS;
    }

    public static function paymentStatusOptions(): array
    {
        return self::PAYMENT_STATUS_OPTIONS;
    }

    public function statusLabel(): string
    {
        return Str::headline($this->status);
    }

    public function statusClass(): string
    {
        return match ($this->status) {
            'delivered' => 'status-delivered',
            'on_hold' => 'status-on-hold',
            default => 'status-progress',
        };
    }

    public function paymentStatusLabel(): string
    {
        return Str::headline($this->payment_status);
    }

    public function paymentStatusClass(): string
    {
        return match ($this->payment_status) {
            'paid' => 'status-paid',
            default => 'status-pending',
        };
    }
}
