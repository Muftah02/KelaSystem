<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'disbursement_id',
        'product_id',
        'quantity'
    ];

    public function disbursement()
    {
        return $this->belongsTo(Disbursement::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 