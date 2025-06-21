<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'disbursement_date'
    ];

    protected $casts = [
        'disbursement_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'disbursement_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }
} 