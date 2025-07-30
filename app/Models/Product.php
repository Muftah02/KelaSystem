<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'category_id',
        'price',
        'cost_price',
        'sale_price',
        'stock_quantity',
        'low_stock_threshold',
        'brand',
        'model',
        'color',
        'size',
        'material',
        'weight',
        'images',
        'attributes',
        'is_featured',
        'is_active',
        'track_quantity',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'images' => 'array',
        'attributes' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'track_quantity' => 'boolean',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer'
    ];

    // إنشاء slug و SKU تلقائياً
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
            if (empty($model->sku)) {
                $model->sku = 'ACC-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // العلاقات
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= low_stock_threshold');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Accessors
    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100, 2);
        }
        return 0;
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images && count($this->images) > 0 ? $this->images[0] : null;
    }

    // Methods
    public function updateStock($quantity, $operation = 'decrease')
    {
        if (!$this->track_quantity) {
            return true;
        }

        if ($operation === 'decrease') {
            $this->stock_quantity -= $quantity;
        } else {
            $this->stock_quantity += $quantity;
        }

        return $this->save();
    }

    public function canSell($quantity = 1)
    {
        if (!$this->is_active || $this->status !== 'active') {
            return false;
        }

        if (!$this->track_quantity) {
            return true;
        }

        return $this->stock_quantity >= $quantity;
    }
}