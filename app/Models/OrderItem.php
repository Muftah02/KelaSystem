<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'total_price',
        'product_attributes',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
        'product_attributes' => 'array'
    ];

    // العلاقات
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Boot method لحساب الإجمالي تلقائياً
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total_price = $model->unit_price * $model->quantity;
        });

        static::saved(function ($model) {
            // إعادة حساب إجماليات الطلب
            $model->order->calculateTotals();
        });

        static::deleted(function ($model) {
            // إعادة حساب إجماليات الطلب بعد الحذف
            $model->order->calculateTotals();
        });
    }

    // Accessors
    public function getFormattedAttributesAttribute()
    {
        if (!$this->product_attributes) {
            return null;
        }

        return collect($this->product_attributes)
            ->filter()
            ->map(function ($value, $key) {
                return ucfirst($key) . ': ' . $value;
            })
            ->implode(' | ');
    }
}