<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'supply_date'
    ];

    protected $casts = [
        'supply_date' => 'date'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(SupplyItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'supply_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function updateProductQuantities()
    {
        foreach ($this->items as $item) {
            $item->product->updateSuppliedQuantity();
        }
    }
} 