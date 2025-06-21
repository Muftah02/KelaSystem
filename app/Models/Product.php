<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'unit_type_id',
        'supply_quantity',
        'available_quantity',
        'minimum_quantity',
        'maximum_quantity',
        'category_id',
        'company_id'
    ];

    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * علاقة مع عناصر التوريد
     */
    public function supplyItems()
    {
        return $this->hasMany(SupplyItem::class);
    }

    /**
     * علاقة مع عناصر الصرف
     */
    public function disbursementItems()
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function updateSuppliedQuantity()
    {
        $totalSupplied = $this->supplyItems()->sum('quantity');
        $this->update(['supply_quantity' => $totalSupplied]);
    }

    public function updateAvailableQuantity()
    {
        $totalSupplied = $this->supplyItems()->sum('quantity');
        $totalDisbursed = $this->disbursementItems()->sum('quantity');
        $this->update(['available_quantity' => $totalSupplied - $totalDisbursed]);
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->supply_quantity - $this->disbursementItems()->sum('quantity');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplies()
    {
        return $this->belongsToMany(Supply::class, 'supply_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function disbursements()
    {
        return $this->belongsToMany(Disbursement::class, 'disbursement_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getTotalQuantityAttribute()
    {
        return $this->supplyItems()->sum('quantity');
    }
} 