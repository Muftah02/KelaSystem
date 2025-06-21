<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id',
        'product_id',
        'quantity'
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::created(function ($supplyItem) {
            $supplyItem->product->updateSuppliedQuantity();
        });

        static::updated(function ($supplyItem) {
            $supplyItem->product->updateSuppliedQuantity();
        });

        static::deleted(function ($supplyItem) {
            $supplyItem->product->updateSuppliedQuantity();
        });
    }
}
