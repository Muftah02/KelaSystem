<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_date',
        'shipping_address',
        'billing_address',
        'shipping_method',
        'shipped_at',
        'delivered_at',
        'notes',
        'internal_notes',
        'discount_code',
        'discount_percentage'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'payment_date' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'shipping_address' => 'array',
        'billing_address' => 'array'
    ];

    // إنشاء رقم طلب تلقائياً
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = 'ORD-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // العلاقات
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['delivered']);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
            'on_hold' => 'dark',
            default => 'secondary'
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'paid' => 'success',
            'partial' => 'info',
            'refunded' => 'danger',
            default => 'secondary'
        };
    }

    public function getItemsCountAttribute()
    {
        return $this->items()->sum('quantity');
    }

    // Methods
    public function calculateTotals()
    {
        $this->subtotal = $this->items()->sum('total_price');
        
        // حساب الضريبة (15% في السعودية)
        $this->tax_amount = $this->subtotal * 0.15;
        
        // حساب الخصم
        if ($this->discount_percentage > 0) {
            $this->discount_amount = $this->subtotal * ($this->discount_percentage / 100);
        }
        
        // الإجمالي النهائي
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount + $this->shipping_cost;
        
        return $this->save();
    }

    public function updateStatus($status, $user = null)
    {
        $this->status = $status;
        
        if ($status === 'shipped') {
            $this->shipped_at = now();
        } elseif ($status === 'delivered') {
            $this->delivered_at = now();
        }
        
        $saved = $this->save();
        
        // تحديث إجماليات العميل
        if ($this->customer && in_array($status, ['delivered', 'cancelled'])) {
            $this->customer->updateTotals();
        }
        
        return $saved;
    }

    public function addItem($product, $quantity, $price = null)
    {
        $price = $price ?? $product->current_price;
        
        return $this->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'unit_price' => $price,
            'quantity' => $quantity,
            'total_price' => $price * $quantity,
            'product_attributes' => [
                'color' => $product->color,
                'size' => $product->size,
                'brand' => $product->brand
            ]
        ]);
    }
}